<?php

namespace App\Support;

use App\Models\Exam_result;
use App\Models\Exam_user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnswersAnalyticsStats
{
    public static function diagnostics($examType, $subjectId, $examUserId = null, $difficulty = null): array
    {
        $userId = Auth::id();
        $examType = self::normalizeExamType($examType);
        $subjectId = $subjectId !== null && $subjectId !== '' ? (int) $subjectId : null;
        $difficulty = $difficulty !== null && $difficulty !== '' ? (int) $difficulty : null;

        $base = Exam_result::query()
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('subjects', function ($join) {
                $join->whereRaw('subjects.id = COALESCE(questions.subject_id, offline_exam_questions.subject_id)');
            })
            ->where('exam_results.user_id', $userId)
            ->where('exams.is_deleted', 0);

        if ($examUserId) {
            $base->where('exam_results.exam_user_id', $examUserId);
        }
        if ($examType) {
            $base->where('exams.type', $examType);
        }
        if ($subjectId) {
            $base->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subjectId]);
        }
        if ($difficulty) {
            $base->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$difficulty]);
        }

        $bySubject = (clone $base)
            ->select([
                DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
                DB::raw('MAX(subjects.name) as subject_name'),
                DB::raw('AVG(CASE WHEN exam_results.time > 0 THEN exam_results.time END) as avg_time'),
                DB::raw('SUM(CASE WHEN exam_results.result < 0 THEN exam_results.result ELSE 0 END) as negative_marks'),
                DB::raw('COUNT(*) as total_question'),
            ])
            ->groupBy(DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)'))
            ->get();

        $pace = [];
        $negatives = [];
        $totalNegative = 0;
        foreach ($bySubject as $row) {
            $name = $row->subject_name ?: 'Subject';
            $avg = (int) round((float) ($row->avg_time ?? 0));
            $neg = (float) ($row->negative_marks ?? 0);
            $totalNegative += $neg;
            $pace[] = [
                'name' => $name,
                'seconds' => $avg,
                'label' => $avg > 0 ? $avg.' seconds' : '—',
            ];
            $negatives[] = [
                'name' => $name,
                'marks' => (int) round($neg),
            ];
        }

        $hours = self::hourBuckets($userId, $examType, $subjectId, $examUserId, $difficulty);

        $testsQuery = Exam_user::query()
            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
            ->where('exam_users.user_id', $userId)
            ->where('exams.is_deleted', 0);
        if ($examUserId) {
            $testsQuery->where('exam_users.id', $examUserId);
        }
        if ($examType) {
            $testsQuery->where('exams.type', $examType);
        }

        return [
            'pace' => $pace,
            'negatives' => $negatives,
            'negative_total' => (int) round($totalNegative),
            'hours' => $hours,
            'tests_count' => $examUserId ? 1 : (int) $testsQuery->count(),
        ];
    }

    public static function normalizeExamType($examType)
    {
        if ($examType === null || $examType === '' || (string) $examType === '3') {
            return null;
        }

        return (int) $examType;
    }

    protected static function hourBuckets($userId, $examType, $subjectId, $examUserId, $difficulty = null): array
    {
        $query = Exam_result::query()
            ->select([
                'exam_results.id',
                'exam_results.exam_user_id',
                'exam_results.result',
                'exam_results.answer',
                'exam_results.created_at',
            ])
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->where('exam_results.user_id', $userId)
            ->where('exams.is_deleted', 0)
            ->orderBy('exam_results.exam_user_id')
            ->orderBy('exam_results.id');

        if ($examUserId) {
            $query->where('exam_results.exam_user_id', $examUserId);
        }
        if ($examType) {
            $query->where('exams.type', $examType);
        }
        if ($subjectId) {
            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subjectId]);
        }
        if ($difficulty) {
            $query->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$difficulty]);
        }

        $rows = $query->get();
        $groups = $rows->groupBy('exam_user_id');
        $acc = [1 => ['right' => 0, 'attempted' => 0], 2 => ['right' => 0, 'attempted' => 0], 3 => ['right' => 0, 'attempted' => 0]];

        foreach ($groups as $attemptRows) {
            $list = $attemptRows->values();
            $n = $list->count();
            if ($n < 1) {
                continue;
            }
            foreach ($list as $i => $row) {
                $bucket = (int) floor(($i / max(1, $n)) * 3) + 1;
                if ($bucket > 3) {
                    $bucket = 3;
                }
                $attempted = !($row->answer == 0 && (int) $row->result === 0);
                if ($attempted) {
                    $acc[$bucket]['attempted']++;
                    if ((float) $row->result > 0) {
                        $acc[$bucket]['right']++;
                    }
                }
            }
        }

        $out = [];
        foreach ([1, 2, 3] as $h) {
            $attempted = $acc[$h]['attempted'];
            $right = $acc[$h]['right'];
            $pct = $attempted > 0 ? round(($right / $attempted) * 100, 1) : 0;
            $out[] = [
                'hour' => $h,
                'pct' => $pct,
                'attempted' => $attempted,
                'right' => $right,
            ];
        }

        return $out;
    }
}
