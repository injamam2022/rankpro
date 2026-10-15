<?php

namespace App\Support;

use App\Models\Exam_mistake_input;
use App\Models\Exam_result;
use App\Models\Mistake_input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MistakeMonitorStats
{
    public const MASTERED_STATUS = 2;
    public const PENDING_STATUS = 1;
    public const PER_PAGE = 10;

    public static function dashboard($examType, $subjectId, array $filters = []): array
    {
        $userId = Auth::id();
        $examType = AnswersAnalyticsStats::normalizeExamType($examType);
        $subjectId = $subjectId !== null && $subjectId !== '' ? (int) $subjectId : null;

        $rows = self::baseQuery($userId, $examType, $subjectId)->get();
        $entries = [];
        foreach ($rows as $row) {
            $entries[] = self::mapRow($row);
        }

        $total = count($entries);
        $pending = count(array_filter($entries, fn ($e) => !$e['mastered']));
        $mastered = $total - $pending;
        $untagged = count(array_filter($entries, fn ($e) => empty($e['tagged'])));
        $masteredRate = $total > 0 ? (int) round(($mastered / $total) * 100) : 0;

        $taxonomy = [
            'conceptual' => ['label' => 'Conceptual Trap (NMC Deep Concept)', 'count' => 0, 'tone' => 'red'],
            'calculation' => ['label' => 'Calculation / Arithmetic Slip', 'count' => 0, 'tone' => 'amber'],
            'formula' => ['label' => 'Formula / Constant Recall', 'count' => 0, 'tone' => 'purple'],
            'misread' => ['label' => 'Misread Question / "Not Incorrect" Trap', 'count' => 0, 'tone' => 'blue'],
            'other' => ['label' => 'Other / Unclassified', 'count' => 0, 'tone' => 'slate'],
        ];
        $subjects = ['Physics' => 0, 'Chemistry' => 0, 'Biology' => 0, 'Other' => 0];
        $chapters = [];

        foreach ($entries as $e) {
            $taxonomy[$e['taxonomy']]['count']++;
            $subjects[$e['subject_bucket']] = ($subjects[$e['subject_bucket']] ?? 0) + 1;
            $ck = $e['chapter'].'|'.$e['subject_bucket'];
            if (!isset($chapters[$ck])) {
                $chapters[$ck] = [
                    'chapter' => $e['chapter'],
                    'subject' => $e['subject_bucket'],
                    'count' => 0,
                ];
            }
            $chapters[$ck]['count']++;
        }
        uasort($chapters, fn ($a, $b) => $b['count'] <=> $a['count']);
        $hotspots = array_slice(array_values($chapters), 0, 3);

        $filterSubject = $filters['subject'] ?? 'All';
        $filterStatus = $filters['status'] ?? 'All';
        $filterReason = $filters['reason'] ?? 'All';

        $filtered = array_values(array_filter($entries, function ($e) use ($filterSubject, $filterStatus, $filterReason) {
            if ($filterSubject !== 'All' && $e['subject_bucket'] !== $filterSubject) {
                return false;
            }
            if ($filterStatus === 'Pending' && $e['mastered']) {
                return false;
            }
            if ($filterStatus === 'Mastered' && !$e['mastered']) {
                return false;
            }
            if ($filterReason !== 'All' && $filterReason !== '') {
                if ($filterReason === 'untagged') {
                    return empty($e['tagged']);
                }
                if (in_array($filterReason, ['conceptual', 'calculation', 'formula', 'misread', 'other'], true)) {
                    if ($e['taxonomy'] !== $filterReason) {
                        return false;
                    }
                } elseif ((string) $e['reason_id'] !== (string) $filterReason) {
                    return false;
                }
            }
            return true;
        }));

        $reasons = Mistake_input::where('status', 1)->orderBy('name')->get(['id', 'name']);

        $filteredTotal = count($filtered);
        $page = max(1, (int) ($filters['page'] ?? 1));
        $last = max(1, (int) ceil($filteredTotal / self::PER_PAGE));
        $page = min($page, $last);
        $slice = array_slice($filtered, ($page - 1) * self::PER_PAGE, self::PER_PAGE);

        return [
            'total' => $total,
            'pending' => $pending,
            'mastered' => $mastered,
            'untagged' => $untagged,
            'mastered_rate' => $masteredRate,
            'recovery' => $pending * 5,
            'taxonomy' => $taxonomy,
            'subjects' => $subjects,
            'hotspots' => $hotspots,
            'entries' => $slice,
            'reasons' => $reasons,
            'filters' => [
                'subject' => $filterSubject,
                'status' => $filterStatus,
                'reason' => $filterReason,
            ],
            'pager' => [
                'page' => $page,
                'per_page' => self::PER_PAGE,
                'total' => $filteredTotal,
                'last_page' => $last,
                'from' => $filteredTotal ? (($page - 1) * self::PER_PAGE) + 1 : 0,
                'to' => min($filteredTotal, $page * self::PER_PAGE),
            ],
        ];
    }

    public static function toggleMastered(int $id, $userId): bool
    {
        $result = Exam_result::where('id', $id)->where('user_id', $userId)->first();
        if (!$result) {
            $row = Exam_mistake_input::where('id', $id)->where('user_id', $userId)->first();
            if (!$row) {
                return false;
            }
            $next = (int) $row->status === self::MASTERED_STATUS ? self::PENDING_STATUS : self::MASTERED_STATUS;
            $row->update(['status' => $next]);

            return true;
        }

        $row = Exam_mistake_input::where('question_id', $result->id)
            ->where('exam_user_id', $result->exam_user_id)
            ->where('user_id', $userId)
            ->first();

        if ($row) {
            $next = (int) $row->status === self::MASTERED_STATUS ? self::PENDING_STATUS : self::MASTERED_STATUS;
            $row->update(['status' => $next]);
            return true;
        }

        Exam_mistake_input::create([
            'exam_id' => $result->exam_id,
            'user_id' => $userId,
            'exam_user_id' => $result->exam_user_id,
            'question_id' => $result->id,
            'status' => self::MASTERED_STATUS,
        ]);

        return true;
    }

    public static function taxonomyKey(?string $name): string
    {
        $n = strtolower((string) $name);
        if (str_contains($n, 'concept') || str_contains($n, 'theory') || str_contains($n, 'ncert') || str_contains($n, 'understand')) {
            return 'conceptual';
        }
        if (str_contains($n, 'calcul') || str_contains($n, 'arith') || str_contains($n, 'slip') || str_contains($n, 'silly') || str_contains($n, 'careless')) {
            return 'calculation';
        }
        if (str_contains($n, 'formula') || str_contains($n, 'constant') || str_contains($n, 'recall')) {
            return 'formula';
        }
        if (str_contains($n, 'misread') || str_contains($n, 'read') || str_contains($n, 'incorrect') || str_contains($n, 'irrelev') || str_contains($n, 'wrong')) {
            return 'misread';
        }

        return 'other';
    }

    public static function reasonLabel(string $key, ?string $fallback = null): string
    {
        return match ($key) {
            'conceptual' => 'Conceptual Error',
            'calculation' => 'Calculation Slip',
            'formula' => 'Formula Recall',
            'misread' => 'Misread Question',
            default => $fallback ?: 'Other',
        };
    }

    public static function subjectBucket(?string $name): string
    {
        $n = strtolower((string) $name);
        if (str_contains($n, 'phys')) {
            return 'Physics';
        }
        if (str_contains($n, 'chem')) {
            return 'Chemistry';
        }
        if (str_contains($n, 'bio') || str_contains($n, 'bot') || str_contains($n, 'zoo')) {
            return 'Biology';
        }

        return 'Other';
    }

    protected static function mapRow($row): array
    {
        $tagged = !empty($row->mistake_input_name);
        if ($tagged) {
            $taxonomy = self::taxonomyKey($row->mistake_input_name);
        } else {
            $diff = (int) ($row->difficulty_level ?? 0);
            $taxonomy = $diff === 1 ? 'formula' : ($diff === 2 ? 'calculation' : ($diff === 3 ? 'conceptual' : 'other'));
        }
        $snippet = self::plain($row->question_text, 180);
        $note = self::plain($row->solution, 220);
        if ($note === '') {
            $chapter = $row->chapter_name ?: 'this chapter';
            $note = 'Revisit '.$chapter.' in NCERT and re-attempt similar PYQs until the trap is automatic.';
        }

        return [
            'id' => (int) $row->id,
            'exam_user_id' => (int) $row->exam_user_id,
            'exam' => $row->exam_name ?: 'Mock',
            'question_number' => $row->question_number ?: '',
            'subject' => $row->subject_name ?: 'Subject',
            'subject_bucket' => self::subjectBucket($row->subject_name),
            'chapter' => $row->chapter_name ?: 'General',
            'reason' => $row->mistake_input_name ?: self::reasonLabel($taxonomy),
            'reason_id' => $row->mistake_input_id,
            'taxonomy' => $taxonomy,
            'taxonomy_label' => self::reasonLabel($taxonomy, $row->mistake_input_name),
            'snippet' => $snippet !== '' ? $snippet : 'Question text unavailable.',
            'note' => $note,
            'tagged' => $tagged,
            'mastered' => (int) $row->status === self::MASTERED_STATUS,
        ];
    }

    protected static function plain($html, int $limit): string
    {
        $text = trim(html_entity_decode(strip_tags((string) $html)));
        $text = preg_replace('/\s+/', ' ', $text) ?: '';
        if (mb_strlen($text) > $limit) {
            return mb_substr($text, 0, $limit - 1).'…';
        }

        return $text;
    }

    protected static function baseQuery($userId, $examType, $subjectId)
    {
        return Exam_result::query()
            ->select([
                'exam_results.id',
                'exam_results.exam_user_id',
                'exam_mistake_inputs.status',
                'exam_mistake_inputs.mistake_input_id',
                'mistake_inputs.name as mistake_input_name',
                'exams.name as exam_name',
                DB::raw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) as difficulty_level'),
                DB::raw('COALESCE(question_paper_questions.question_number, offline_exam_questions.question_number) as question_number'),
                DB::raw('COALESCE((SELECT question_details.question_text FROM question_details WHERE question_details.question_id = questions.id LIMIT 1), offline_exam_questions.question_text) as question_text'),
                DB::raw('(SELECT question_details.solution FROM question_details WHERE question_details.question_id = questions.id LIMIT 1) as solution'),
                DB::raw("COALESCE(subjects.name, '') as subject_name"),
                DB::raw("COALESCE(chapters.name, '') as chapter_name"),
            ])
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('exam_mistake_inputs', function ($join) use ($userId) {
                $join->on('exam_mistake_inputs.question_id', '=', 'exam_results.id')
                    ->on('exam_mistake_inputs.exam_user_id', '=', 'exam_results.exam_user_id')
                    ->where('exam_mistake_inputs.user_id', '=', $userId);
            })
            ->leftJoin('mistake_inputs', 'mistake_inputs.id', '=', 'exam_mistake_inputs.mistake_input_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('subjects', function ($join) {
                $join->whereRaw('subjects.id = COALESCE(questions.subject_id, offline_exam_questions.subject_id)');
            })
            ->leftJoin('chapters', function ($join) {
                $join->whereRaw('chapters.id = COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)');
            })
            ->where('exam_results.user_id', $userId)
            ->where('exam_results.answer', '!=', 0)
            ->where('exam_results.result', '<=', 0)
            ->where('exams.is_deleted', 0)
            ->when($examType, function ($q) use ($examType) {
                $q->where('exams.type', $examType);
            })
            ->when($subjectId, function ($q) use ($subjectId) {
                $q->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subjectId]);
            })
            ->orderByDesc('exam_results.id');
    }
}
