<?php

namespace App\Support;

use App\Models\Exam_result;
use App\Models\Question;
use App\Models\Question_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StrengthWeaknessStats
{
    public const STRENGTH_PER_PAGE = 4;
    public const WEAKNESS_PER_PAGE = 4;
    public const MISTAKE_PER_PAGE = 8;
    public const MCQ_PER_PAGE = 4;

    public static function dashboard($examType, $subjectId, $examUserId = null, array $pages = []): array
    {
        $userId = Auth::id();
        $examType = AnswersAnalyticsStats::normalizeExamType($examType);
        $subjectId = $subjectId !== null && $subjectId !== '' ? (int) $subjectId : null;
        $sp = max(1, (int) ($pages['strength'] ?? 1));
        $wp = max(1, (int) ($pages['weakness'] ?? 1));
        $mp = max(1, (int) ($pages['mistake'] ?? 1));
        $mistakeSubject = $pages['mistake_subject'] ?? null;

        $classified = self::classifiedChapters($userId, $examType, $subjectId, $examUserId);
        $strengthsAll = $classified['strengths'];
        $weaknessesAll = $classified['weaknesses'];

        $strengthPager = self::pagerMeta($sp, self::STRENGTH_PER_PAGE, count($strengthsAll));
        $weakPager = self::pagerMeta($wp, self::WEAKNESS_PER_PAGE, count($weaknessesAll));
        $strengthSlice = array_slice($strengthsAll, ($strengthPager['page'] - 1) * self::STRENGTH_PER_PAGE, self::STRENGTH_PER_PAGE);
        $weakSlice = array_slice($weaknessesAll, ($weakPager['page'] - 1) * self::WEAKNESS_PER_PAGE, self::WEAKNESS_PER_PAGE);
        self::attachSubtopics($strengthSlice, $userId, $examType, $subjectId, $examUserId);
        self::attachSubtopics($weakSlice, $userId, $examType, $subjectId, $examUserId);

        $mistakes = self::mistakes($userId, $examType, $subjectId, $examUserId, $mp, $mistakeSubject);

        return [
            'summary' => $classified['summary'],
            'strengths' => $strengthSlice,
            'weaknesses' => $weakSlice,
            'mistakes' => $mistakes['rows'],
            'pager' => [
                'strengths' => $strengthPager,
                'weaknesses' => $weakPager,
                'mistakes' => self::pagerMeta($mp, self::MISTAKE_PER_PAGE, $mistakes['total']),
            ],
        ];
    }

    public static function listPage(string $kind, int $page, $examType, $subjectId, $examUserId = null, $mistakeSubject = null): array
    {
        $userId = Auth::id();
        $examType = AnswersAnalyticsStats::normalizeExamType($examType);
        $subjectId = $subjectId !== null && $subjectId !== '' ? (int) $subjectId : null;
        $page = max(1, $page);

        if ($kind === 'mistakes') {
            $mistakes = self::mistakes($userId, $examType, $subjectId, $examUserId, $page, $mistakeSubject);
            return [
                'mistakes' => $mistakes['rows'],
                'pager' => self::pagerMeta($page, self::MISTAKE_PER_PAGE, $mistakes['total']),
            ];
        }

        $classified = self::classifiedChapters($userId, $examType, $subjectId, $examUserId);
        $isWeak = $kind === 'weaknesses';
        $all = $isWeak ? $classified['weaknesses'] : $classified['strengths'];
        $per = $isWeak ? self::WEAKNESS_PER_PAGE : self::STRENGTH_PER_PAGE;
        $pager = self::pagerMeta($page, $per, count($all));
        $slice = array_slice($all, ($pager['page'] - 1) * $per, $per);
        self::attachSubtopics($slice, $userId, $examType, $subjectId, $examUserId);

        return [
            'units' => $slice,
            'pager' => $pager,
            'kind' => $isWeak ? 'weakness' : 'strength',
        ];
    }

    public static function drilldown(int $subTopicId, $examType, $subjectId, $examUserId = null, string $kind = 'strength', int $mcqPage = 1): array
    {
        $userId = Auth::id();
        $examType = AnswersAnalyticsStats::normalizeExamType($examType);
        $subjectId = $subjectId !== null && $subjectId !== '' ? (int) $subjectId : null;
        $pages = ['mcq' => $mcqPage];

        $stats = self::groupQuery($userId, $examType, $subjectId, $examUserId, 'subtopic', null, $subTopicId)
            ->first();

        $payload = $stats ? self::subtopicPayload($stats) : [
            'id' => $subTopicId,
            'name' => 'Subtopic',
            'chapter_name' => '',
            'subject_name' => '',
            'subject_id' => $subjectId,
            'accuracy' => 0,
            'avg_time' => 0,
            'right' => 0,
            'wrong' => 0,
            'total' => 0,
        ];

        $peerAvg = (int) round((float) (self::baseQuery($userId, $examType, $subjectId, null, false)
            ->whereRaw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) = ?', [$subTopicId])
            ->where('exam_results.result', '>', 0)
            ->where('exam_results.user_id', '!=', $userId)
            ->avg(DB::raw('CASE WHEN exam_results.time > 0 THEN exam_results.time END')) ?? 0));

        $mcqPage = max(1, (int) ($pages['mcq'] ?? 1));
        $formulas = self::formulaCards($subTopicId);
        $traps = self::trapCards($userId, $subTopicId, $examUserId);
        $mcqs = self::targetedMcqs($userId, $subTopicId, $mcqPage);

        $bankCount = Question::query()
            ->where('sub_topic_id', $subTopicId)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->count();
        $weight = $bankCount >= 20
            ? '2 – 3 MCQs in NEET · L6 (8 – 12 Marks)'
            : ($bankCount >= 8 ? '1 – 2 MCQs in NEET · L4 (4 – 8 Marks)' : 'Low-frequency in your bank');

        return [
            'kind' => $kind,
            'subject' => $payload['subject_name'] ?: 'Subject',
            'chapter' => $payload['chapter_name'] ?: 'Chapter',
            'name' => $payload['name'],
            'subject_id' => $payload['subject_id'] ?? $subjectId,
            'accuracy' => $payload['accuracy'],
            'speed' => (int) ($payload['avg_time'] ?? 0),
            'topper_speed' => $peerAvg > 0 ? $peerAvg : 28,
            'weightage' => $weight,
            'formulas' => $formulas,
            'traps' => $traps,
            'mcqs' => $mcqs['rows'],
            'mcq_pager' => $mcqs['pager'],
            'sub_topic_id' => $subTopicId,
            'chapter_id' => (int) ($payload['chapter_id'] ?? 0),
            'custom_test_url' => route('custom_test.from_subtopic'),
        ];
    }

    protected static function classifiedChapters($userId, $examType, $subjectId, $examUserId): array
    {
        $chapterRows = self::groupQuery($userId, $examType, $subjectId, $examUserId, 'chapter');
        $strengths = [];
        $weaknesses = [];
        $negativeTotal = 0;

        foreach ($chapterRows as $row) {
            $unit = self::chapterPayload($row, []);
            $negativeTotal += (int) ($unit['negative'] ?? 0);
            if ($unit['accuracy'] >= 80 && $unit['attempted'] > 0) {
                $strengths[] = $unit;
            } elseif ($unit['wrong'] > 0 || $unit['accuracy'] < 80) {
                $weaknesses[] = $unit;
            }
        }

        usort($strengths, fn ($a, $b) => $b['accuracy'] <=> $a['accuracy']);
        usort($weaknesses, fn ($a, $b) => $a['accuracy'] <=> $b['accuracy']);

        $mastered = count(array_filter($strengths, fn ($u) => $u['accuracy'] >= 90));

        return [
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'summary' => [
                'mastered' => $mastered ?: count($strengths),
                'focus' => count($weaknesses),
                'negative' => $negativeTotal,
            ],
        ];
    }

    protected static function attachSubtopics(array &$units, $userId, $examType, $subjectId, $examUserId): void
    {
        $ids = array_values(array_filter(array_map(fn ($u) => (int) ($u['chapter_id'] ?? 0), $units)));
        if (!$ids) {
            return;
        }
        $rows = self::groupQuery($userId, $examType, $subjectId, $examUserId, 'subtopic', $ids);
        $byChapter = [];
        foreach ($rows as $row) {
            $byChapter[(int) $row->chapter_id][] = self::subtopicPayload($row);
        }
        foreach ($units as &$unit) {
            $unit['subtopics'] = $byChapter[(int) $unit['chapter_id']] ?? [];
        }
        unset($unit);
    }

    protected static function pagerMeta(int $page, int $perPage, int $total): array
    {
        $last = max(1, (int) ceil($total / max(1, $perPage)));
        $page = min($page, $last);

        return [
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => $last,
            'from' => $total ? (($page - 1) * $perPage) + 1 : 0,
            'to' => min($total, $page * $perPage),
        ];
    }

    protected static function groupQuery($userId, $examType, $subjectId, $examUserId, string $level, array $chapterIds = null, $subTopicId = null)
    {
        $select = [
            DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) as chapter_id'),
            DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
            DB::raw('MAX(chapters.name) as chapter_name'),
            DB::raw('MAX(subjects.name) as subject_name'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN exam_results.result > 0 THEN 1 ELSE 0 END) as right_answer'),
            DB::raw('SUM(CASE WHEN exam_results.answer != 0 AND exam_results.result <= 0 THEN 1 ELSE 0 END) as wrong_answer'),
            DB::raw('SUM(CASE WHEN exam_results.answer = 0 AND exam_results.result = 0 THEN 1 ELSE 0 END) as skip_answer'),
            DB::raw('SUM(CASE WHEN exam_results.result < 0 THEN exam_results.result ELSE 0 END) as negative_marks'),
            DB::raw('AVG(CASE WHEN exam_results.time > 0 THEN exam_results.time END) as avg_time'),
        ];

        $group = [DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'), DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)')];

        if ($level === 'subtopic') {
            $select[] = DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) as sub_topic_id');
            $select[] = DB::raw("MAX(COALESCE(sub_topics.name, topics.name, 'General')) as sub_topic_name");
            $group[] = DB::raw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)');
        }

        $query = self::baseQuery($userId, $examType, $subjectId, $examUserId)
            ->leftJoin('chapters', function ($join) {
                $join->whereRaw('chapters.id = COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)');
            })
            ->leftJoin('subjects', function ($join) {
                $join->whereRaw('subjects.id = COALESCE(questions.subject_id, offline_exam_questions.subject_id)');
            });

        if ($level === 'subtopic') {
            $query->leftJoin('sub_topics', function ($join) {
                $join->whereRaw('sub_topics.id = COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)');
            })->leftJoin('topics', function ($join) {
                $join->whereRaw('topics.id = COALESCE(questions.topic_id, offline_exam_questions.topic_id)');
            });
        }

        $query = $query->select($select)
            ->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IS NOT NULL');

        if ($chapterIds) {
            $placeholders = implode(',', array_fill(0, count($chapterIds), '?'));
            $query->whereRaw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id) IN ('.$placeholders.')', array_values($chapterIds));
        }
        if ($subTopicId) {
            $query->whereRaw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) = ?', [$subTopicId]);
        }

        return $query->groupBy($group)->get();
    }

    protected static function baseQuery($userId, $examType, $subjectId, $examUserId, bool $forUser = true)
    {
        $query = Exam_result::query()
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->where('exams.is_deleted', 0);

        if ($forUser) {
            $query->where('exam_results.user_id', $userId);
        }
        if ($examUserId) {
            $query->where('exam_results.exam_user_id', $examUserId);
        }
        if ($examType) {
            $query->where('exams.type', $examType);
        }
        if ($subjectId) {
            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [$subjectId]);
        }

        return $query;
    }

    protected static function chapterPayload($row, array $subtopics): array
    {
        $right = (int) $row->right_answer;
        $wrong = (int) $row->wrong_answer;
        $attempted = $right + $wrong;
        $accuracy = $attempted > 0 ? round(($right / $attempted) * 100, 1) : 0;

        return [
            'chapter_id' => (int) $row->chapter_id,
            'subject_id' => (int) $row->subject_id,
            'subject_name' => $row->subject_name ?: 'Subject',
            'chapter_name' => $row->chapter_name ?: 'Chapter',
            'total' => (int) $row->total,
            'right' => $right,
            'wrong' => $wrong,
            'attempted' => $attempted,
            'accuracy' => $accuracy,
            'negative' => (int) round((float) $row->negative_marks),
            'avg_time' => (int) round((float) $row->avg_time),
            'subtopics' => $subtopics,
        ];
    }

    protected static function subtopicPayload($row): array
    {
        $right = (int) $row->right_answer;
        $wrong = (int) $row->wrong_answer;
        $attempted = $right + $wrong;
        $accuracy = $attempted > 0 ? round(($right / $attempted) * 100, 1) : 0;

        return [
            'id' => (int) ($row->sub_topic_id ?? 0),
            'name' => $row->sub_topic_name ?: 'General',
            'chapter_id' => (int) $row->chapter_id,
            'chapter_name' => $row->chapter_name ?: 'Chapter',
            'subject_id' => (int) $row->subject_id,
            'subject_name' => $row->subject_name ?: 'Subject',
            'total' => (int) $row->total,
            'right' => $right,
            'wrong' => $wrong,
            'attempted' => $attempted,
            'accuracy' => $accuracy,
            'avg_time' => (int) round((float) $row->avg_time),
            'negative' => (int) round((float) $row->negative_marks),
        ];
    }

    protected static function mistakes($userId, $examType, $subjectId, $examUserId, int $page = 1, $mistakeSubject = null): array
    {
        $reviewed = session('sw_reviewed_mistakes', []);
        $query = self::baseQuery($userId, $examType, $subjectId, $examUserId)
            ->leftJoin('question_details', function ($join) {
                $join->on('question_details.question_id', '=', 'questions.id')
                    ->where('question_details.language_id', 1);
            })
            ->leftJoin('chapters', function ($join) {
                $join->whereRaw('chapters.id = COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)');
            })
            ->leftJoin('subjects', function ($join) {
                $join->whereRaw('subjects.id = COALESCE(questions.subject_id, offline_exam_questions.subject_id)');
            })
            ->leftJoin('sub_topics', function ($join) {
                $join->whereRaw('sub_topics.id = COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id)');
            })
            ->where('exam_results.answer', '!=', 0)
            ->where('exam_results.result', '<=', 0);

        if ($reviewed) {
            $query->whereNotIn('exam_results.id', array_map('intval', $reviewed));
        }
        if ($mistakeSubject && $mistakeSubject !== 'all') {
            $query->whereRaw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) = ?', [(int) $mistakeSubject]);
        }

        $total = (int) (clone $query)->count('exam_results.id');
        $page = max(1, $page);
        $last = max(1, (int) ceil($total / self::MISTAKE_PER_PAGE));
        $page = min($page, $last);

        $rows = $query->orderByDesc('exam_results.id')
            ->offset(($page - 1) * self::MISTAKE_PER_PAGE)
            ->limit(self::MISTAKE_PER_PAGE)
            ->get([
                'exam_results.id',
                'exam_results.answer as your_answer',
                'exam_results.result',
                'exams.name as exam_name',
                'exams.exam_code',
                DB::raw('COALESCE(questions.answer, offline_exam_questions.answer) as correct_answer'),
                DB::raw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) as difficulty_level'),
                DB::raw('COALESCE(question_details.question_text, offline_exam_questions.question_text) as question_text'),
                'question_details.option1',
                'question_details.option2',
                'question_details.option3',
                'question_details.option4',
                'question_details.is_option1_image',
                'question_details.is_option2_image',
                'question_details.is_option3_image',
                'question_details.is_option4_image',
                'question_details.solution',
                'subjects.name as subject_name',
                'chapters.name as chapter_name',
                'sub_topics.name as sub_topic_name',
                DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
            ]);

        $out = [];
        $offset = ($page - 1) * self::MISTAKE_PER_PAGE;
        foreach ($rows as $row) {
            $diff = (int) $row->difficulty_level;
            $tag = $diff === 1 ? 'Formula Slip' : ($diff === 2 ? 'Calculation Error' : 'Conceptual Trap');
            $out[] = [
                'id' => (int) $row->id,
                'subject' => $row->subject_name ?: 'Subject',
                'subject_id' => (int) $row->subject_id,
                'chapter' => $row->chapter_name ?: '',
                'subtopic' => $row->sub_topic_name ?: '',
                'exam' => trim(($row->exam_name ?: 'Exam').($row->exam_code ? ' · '.$row->exam_code : '')),
                'tag' => $tag,
                'question' => $row->question_text,
                'options' => [
                    1 => $row->option1,
                    2 => $row->option2,
                    3 => $row->option3,
                    4 => $row->option4,
                ],
                'option_images' => [
                    1 => (int) $row->is_option1_image,
                    2 => (int) $row->is_option2_image,
                    3 => (int) $row->is_option3_image,
                    4 => (int) $row->is_option4_image,
                ],
                'correct' => (int) $row->correct_answer,
                'yours' => (int) $row->your_answer,
                'solution' => $row->solution,
                'n' => $offset + count($out) + 1,
            ];
        }

        return ['rows' => $out, 'total' => $total];
    }

    protected static function formulaCards(int $subTopicId): array
    {
        $rows = Question_detail::query()
            ->join('questions', 'questions.id', '=', 'question_details.question_id')
            ->where('questions.sub_topic_id', $subTopicId)
            ->where('questions.is_deleted', 0)
            ->where('question_details.language_id', 1)
            ->whereNotNull('question_details.solution')
            ->where('question_details.solution', '!=', '')
            ->orderByDesc('questions.id')
            ->limit(12)
            ->pluck('question_details.solution');

        $cards = [];
        foreach ($rows as $solution) {
            $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string) $solution))));
            if ($text === '' || mb_strlen($text) < 8) {
                continue;
            }
            if (mb_strlen($text) > 180) {
                $text = mb_substr($text, 0, 177).'…';
            }
            if (!in_array($text, $cards, true)) {
                $cards[] = $text;
            }
            if (count($cards) >= 4) {
                break;
            }
        }

        return $cards;
    }

    protected static function trapCards($userId, int $subTopicId, $examUserId): array
    {
        $query = Exam_result::query()
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('question_details', function ($join) {
                $join->on('question_details.question_id', '=', 'questions.id')
                    ->where('question_details.language_id', 1);
            })
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->where('exam_results.user_id', $userId)
            ->where('exam_results.answer', '!=', 0)
            ->where('exam_results.result', '<=', 0)
            ->whereRaw('COALESCE(questions.sub_topic_id, offline_exam_questions.sub_topic_id) = ?', [$subTopicId])
            ->orderByDesc('exam_results.id')
            ->limit(6);

        if ($examUserId) {
            $query->where('exam_results.exam_user_id', $examUserId);
        }

        $rows = $query->get([
            'question_details.solution',
            DB::raw('COALESCE(question_details.question_text, offline_exam_questions.question_text) as question_text'),
        ]);

        $cards = [];
        foreach ($rows as $row) {
            $raw = $row->solution ?: $row->question_text;
            $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string) $raw))));
            if ($text === '') {
                continue;
            }
            if (mb_strlen($text) > 160) {
                $text = mb_substr($text, 0, 157).'…';
            }
            $cards[] = $text;
            if (count($cards) >= 3) {
                break;
            }
        }

        return $cards;
    }

    protected static function targetedMcqs($userId, int $subTopicId, int $page = 1): array
    {
        $wrongIds = Exam_result::query()
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->where('exam_results.user_id', $userId)
            ->where('exam_results.result', '<=', 0)
            ->where('questions.sub_topic_id', $subTopicId)
            ->pluck('questions.id')
            ->unique()
            ->take(40)
            ->all();

        $query = Question::query()
            ->join('question_details', function ($join) {
                $join->on('question_details.question_id', '=', 'questions.id')
                    ->where('question_details.language_id', 1);
            })
            ->where('questions.sub_topic_id', $subTopicId)
            ->where('questions.is_deleted', 0)
            ->where('questions.status', 1);

        $total = (int) (clone $query)->count('questions.id');
        $pager = self::pagerMeta($page, self::MCQ_PER_PAGE, $total);

        if ($wrongIds) {
            $query->orderByRaw('CASE WHEN questions.id IN ('.implode(',', array_map('intval', $wrongIds)).') THEN 0 ELSE 1 END');
        } else {
            $query->orderByDesc('questions.id');
        }

        $rows = $query->offset(($pager['page'] - 1) * self::MCQ_PER_PAGE)
            ->limit(self::MCQ_PER_PAGE)
            ->get([
                'questions.id',
                'questions.answer',
                'question_details.question_text',
                'question_details.option1',
                'question_details.option2',
                'question_details.option3',
                'question_details.option4',
                'question_details.solution',
            ]);

        $mcqs = [];
        $n = ($pager['page'] - 1) * self::MCQ_PER_PAGE;
        foreach ($rows as $row) {
            $n++;
            $mcqs[] = [
                'n' => $n,
                'id' => (int) $row->id,
                'text' => $row->question_text,
                'options' => [
                    'A' => $row->option1,
                    'B' => $row->option2,
                    'C' => $row->option3,
                    'D' => $row->option4,
                ],
                'correct' => (int) $row->answer,
                'solution' => $row->solution,
            ];
        }

        return ['rows' => $mcqs, 'pager' => $pager];
    }
}
