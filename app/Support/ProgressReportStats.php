<?php

namespace App\Support;

use App\Models\Exam_result;
use App\Models\Exam_user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgressReportStats
{
    public static function dashboard($examType, $subjectId, $examUserId = null): array
    {
        $userId = Auth::id();
        $examType = AnswersAnalyticsStats::normalizeExamType($examType);
        $subjectId = $subjectId !== null && $subjectId !== '' ? (int) $subjectId : null;
        $examUserId = $examUserId !== null && $examUserId !== '' ? (int) $examUserId : null;

        $attempts = self::attempts($userId, $examType, $subjectId);
        $empty = [
            'has_data' => false,
            'baseline' => 0,
            'baseline_label' => 'No mocks yet',
            'current' => 0,
            'current_max' => 720,
            'growth' => 0,
            'percentile' => 0,
            'air' => null,
            'air_low' => null,
            'air_high' => null,
            'target_delta' => 720,
            'trend' => 'Awaiting first mock',
            'trend_up' => false,
            'history' => [],
            'online' => self::emptyModeStats('Online CBT', 'Digital'),
            'offline' => self::emptyModeStats('Offline OMR', 'Physical'),
            'subjects' => [
                'biology' => ['score' => 0, 'max' => 360],
                'chemistry' => ['score' => 0, 'max' => 180],
                'physics' => ['score' => 0, 'max' => 180],
            ],
            'govt_label' => 'Attempt a mock to project AIR',
        ];

        if ($attempts->isEmpty()) {
            return $empty;
        }

        $first = $attempts->first();
        $latest = $attempts->last();
        $focus = $latest;
        if ($examUserId) {
            $found = $attempts->firstWhere('exam_user_id', $examUserId);
            if ($found) {
                $focus = $found;
            }
        }

        $current = (int) round((float) $focus->score);
        $currentMax = max(1, (int) round((float) $focus->max_mark));
        $baseline = (int) round((float) $first->score);
        $growth = $current - $baseline;
        $targetDelta = max(0, $currentMax - $current);

        $percentile = self::percentileForAttempt($focus);
        $air = self::projectAir($percentile, $focus->rank);
        $trendUp = $current >= $baseline;
        $trend = $attempts->count() < 2
            ? 'First mock logged'
            : ($trendUp ? 'Steady Upward Trend' : 'Needs Recovery Focus');

        $history = [];
        $scoreMin = (int) $attempts->min('score');
        $scoreMax = (int) $attempts->max('score');
        $span = max(40, $scoreMax - $scoreMin);
        foreach ($attempts as $row) {
            $score = (int) round((float) $row->score);
            $maxMark = max(1, (int) round((float) $row->max_mark));
            $pctile = self::percentileForAttempt($row);
            $width = $span > 0
                ? (int) round((($score - ($scoreMin - 10)) / ($span + 20)) * 100)
                : (int) round(($score / $maxMark) * 100);
            $history[] = [
                'exam' => $row->exam_name ?: 'Mock',
                'date' => $row->created_at ? date('M j, Y', strtotime($row->created_at)) : '',
                'score' => $score,
                'max' => $maxMark,
                'mode' => ((int) $row->exam_type === 2) ? 'Offline OMR' : 'Online CBT',
                'percentile' => number_format($pctile, 1).'%',
                'width' => max(6, min(100, $width)),
                'is_focus' => (int) $row->exam_user_id === (int) $focus->exam_user_id,
            ];
        }

        $onlineRows = $attempts->filter(fn ($row) => (int) $row->exam_type === 1);
        $offlineRows = $attempts->filter(fn ($row) => (int) $row->exam_type === 2);

        $subjectScores = self::subjectScores($userId, $examType, $subjectId, (int) $focus->exam_user_id);

        return [
            'has_data' => true,
            'baseline' => $baseline,
            'baseline_label' => $first->created_at ? date('M Y', strtotime($first->created_at)).' Baseline' : 'Baseline',
            'current' => $current,
            'current_max' => $currentMax,
            'growth' => $growth,
            'percentile' => $percentile,
            'air' => $air['mid'],
            'air_low' => $air['low'],
            'air_high' => $air['high'],
            'target_delta' => $targetDelta,
            'trend' => $trend,
            'trend_up' => $trendUp,
            'history' => $history,
            'online' => self::modeStats($userId, 1, $onlineRows, $subjectId),
            'offline' => self::modeStats($userId, 2, $offlineRows, $subjectId),
            'subjects' => $subjectScores,
            'govt_label' => self::govtLabel($percentile),
        ];
    }

    protected static function attempts($userId, $examType, $subjectId)
    {
        $query = Exam_user::query()
            ->select([
                'exam_users.id as exam_user_id',
                'exam_users.exam_id',
                'exam_users.created_at',
                'exam_users.percentage',
                'exam_users.rank',
                'exam_users.total_time',
                'exam_users.total_right_answer',
                'exam_users.total_answer',
                'exams.name as exam_name',
                'exams.type as exam_type',
                DB::raw('COALESCE(SUM(exam_results.result), exam_users.total_number, 0) as score'),
                DB::raw('CASE WHEN exam_users.total_mark >= 20 THEN exam_users.total_mark WHEN COUNT(exam_results.id) > 0 THEN COUNT(exam_results.id) * 4 ELSE 720 END as max_mark'),
            ])
            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
            ->leftJoin('exam_results', 'exam_results.exam_user_id', '=', 'exam_users.id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->where('exam_users.user_id', $userId)
            ->where('exams.is_deleted', 0)
            ->when($examType, function ($q) use ($examType) {
                $q->where('exams.type', $examType);
            })
            ->when($subjectId, function ($q) use ($subjectId) {
                $q->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subjectId]);
            })
            ->groupBy(
                'exam_users.id',
                'exam_users.exam_id',
                'exam_users.created_at',
                'exam_users.percentage',
                'exam_users.rank',
                'exam_users.total_time',
                'exam_users.total_right_answer',
                'exam_users.total_answer',
                'exam_users.total_number',
                'exam_users.total_mark',
                'exams.name',
                'exams.type'
            )
            ->havingRaw('COUNT(exam_results.id) > 0 OR exam_users.total_number IS NOT NULL')
            ->orderBy('exam_users.created_at');

        return $query->get();
    }

    protected static function percentileForAttempt($row): float
    {
        $stored = (float) ($row->percentage ?? 0);
        $examId = (int) ($row->exam_id ?? 0);
        $score = (float) ($row->score ?? 0);
        $max = max(1, (float) ($row->max_mark ?? 720));
        $fullPaper = $max >= 600;

        if ($fullPaper && $examId > 0) {
            $total = Exam_user::where('exam_id', $examId)->count();
            if ($total > 1) {
                $better = Exam_user::where('exam_id', $examId)->where('total_number', '>', $score)->count();
                return round((1 - ($better / $total)) * 100, 2);
            }
        }

        if ($fullPaper && $stored > 0 && $stored <= 100) {
            return round($stored, 2);
        }

        return round(($score / $max) * 100, 2);
    }

    protected static function projectAir(float $percentile, $storedRank): array
    {
        $mid = null;
        if ($storedRank !== null && (int) $storedRank > 0) {
            $mid = (int) $storedRank;
        } else {
            $mid = max(1, (int) round(((100 - min(99.99, max(0, $percentile))) / 100) * 2400000));
        }

        return [
            'mid' => $mid,
            'low' => max(1, (int) round($mid * 0.85)),
            'high' => (int) round($mid * 1.25),
        ];
    }

    protected static function govtLabel(float $percentile): string
    {
        if ($percentile >= 99.5) {
            return 'Government Seat 99.4%';
        }
        if ($percentile >= 98) {
            return 'Govt. Quota Likely';
        }
        if ($percentile >= 95) {
            return 'State Quota Range';
        }

        return 'Push toward 99+ percentile';
    }

    protected static function emptyModeStats(string $title, string $badge): array
    {
        return [
            'title' => $title,
            'badge' => $badge,
            'has_data' => false,
            'avg_score' => 0,
            'avg_max' => 720,
            'accuracy' => 0,
            'time_label' => '—',
            'extra_label' => $title === 'Online CBT' ? 'Review Efficiency' : 'OMR Bubbling Cost',
            'extra_value' => '—',
        ];
    }

    protected static function modeStats($userId, int $type, $rows, $subjectId): array
    {
        $title = $type === 2 ? 'Offline OMR' : 'Online CBT';
        $badge = $type === 2 ? 'Physical' : 'Digital';
        if ($rows->isEmpty()) {
            return self::emptyModeStats($title, $badge);
        }

        $avgScore = (int) round($rows->avg('score'));
        $avgMax = max(1, (int) round($rows->avg('max_mark')));
        $examUserIds = $rows->pluck('exam_user_id')->filter()->all();

        $accQuery = Exam_result::query()
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->where('exam_results.user_id', $userId)
            ->where('exams.is_deleted', 0)
            ->where('exams.type', $type)
            ->when($subjectId, function ($q) use ($subjectId) {
                $q->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subjectId]);
            })
            ->when($examUserIds, function ($q) use ($examUserIds) {
                $q->whereIn('exam_results.exam_user_id', $examUserIds);
            })
            ->select([
                DB::raw('SUM(CASE WHEN exam_results.result > 0 THEN 1 ELSE 0 END) as right_answer'),
                DB::raw('SUM(CASE WHEN exam_results.answer != 0 AND exam_results.result <= 0 THEN 1 ELSE 0 END) as wrong_answer'),
                DB::raw('SUM(CASE WHEN exam_results.review_later = 1 THEN 1 ELSE 0 END) as reviewed'),
            ])
            ->first();

        $right = (int) ($accQuery->right_answer ?? 0);
        $wrong = (int) ($accQuery->wrong_answer ?? 0);
        $attempted = $right + $wrong;
        $accuracy = $attempted > 0 ? round(($right / $attempted) * 100, 1) : 0;
        $reviewed = (int) ($accQuery->reviewed ?? 0);

        $avgTime = (float) $rows->avg('total_time');
        $mins = self::toMinutes($avgTime);

        if ($type === 1) {
            $extraLabel = 'Review Efficiency';
            $extraValue = $reviewed > 0 ? 'Fast (+'.$reviewed.' Qs reviewed)' : 'No marked reviews';
        } else {
            $extraLabel = 'OMR Bubbling Cost';
            $extraValue = $mins >= 12 ? '12 mins' : max(8, (int) round($mins * 0.06)).' mins';
        }

        return [
            'title' => $title,
            'badge' => $badge,
            'has_data' => true,
            'avg_score' => $avgScore,
            'avg_max' => $avgMax,
            'accuracy' => $accuracy,
            'time_label' => $mins > 0 ? $mins.' mins'.($type === 2 ? ' (incl. bubbling)' : '') : '—',
            'extra_label' => $extraLabel,
            'extra_value' => $extraValue,
        ];
    }

    protected static function toMinutes($raw): int
    {
        $raw = (float) $raw;
        if ($raw <= 0) {
            return 0;
        }
        if ($raw > 400) {
            return (int) round($raw / 60);
        }

        return (int) round($raw);
    }

    protected static function subjectScores($userId, $examType, $subjectId, $examUserId): array
    {
        $rows = Exam_result::query()
            ->select([
                DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
                DB::raw('MAX(subjects.name) as subject_name'),
                DB::raw('SUM(exam_results.result) as score'),
                DB::raw('COUNT(*) as total'),
            ])
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('subjects', function ($join) {
                $join->whereRaw('subjects.id = COALESCE(questions.subject_id, offline_exam_questions.subject_id)');
            })
            ->where('exam_results.user_id', $userId)
            ->where('exams.is_deleted', 0)
            ->where('exam_results.exam_user_id', $examUserId)
            ->when($examType, function ($q) use ($examType) {
                $q->where('exams.type', $examType);
            })
            ->when($subjectId, function ($q) use ($subjectId) {
                $q->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subjectId]);
            })
            ->groupBy(DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)'))
            ->get();

        $out = [
            'biology' => ['score' => 0, 'max' => 360],
            'chemistry' => ['score' => 0, 'max' => 180],
            'physics' => ['score' => 0, 'max' => 180],
        ];

        foreach ($rows as $row) {
            $bucket = self::subjectBucket($row->subject_name);
            $score = (int) round((float) $row->score);
            if ($bucket === 'biology') {
                $out['biology']['score'] += $score;
            } elseif (isset($out[$bucket])) {
                $out[$bucket]['score'] = $score;
            }
        }

        return $out;
    }

    public static function subjectBucket(?string $name): string
    {
        $n = strtolower((string) $name);
        if (str_contains($n, 'phys')) {
            return 'physics';
        }
        if (str_contains($n, 'chem')) {
            return 'chemistry';
        }
        if (str_contains($n, 'bio') || str_contains($n, 'bot') || str_contains($n, 'zoo')) {
            return 'biology';
        }

        return 'other';
    }
}
