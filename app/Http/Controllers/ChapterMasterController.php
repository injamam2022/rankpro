<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\CustomTest;
use App\Models\Exam;
use App\Models\Exam_user;
use App\Models\Question;
use App\Models\Question_source;
use App\Models\Question_paper;
use App\Models\Question_paper_question;
use App\Models\Question_paper_subject;
use App\Models\Subject;
use App\Models\Topic;
use App\Support\CoursePackageAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChapterMasterController extends Controller
{
    private const QUESTIONS_PER_CHAPTER = 45;

    private array $subjectIcons = [
        'physics' => ['color' => '#2f6fed', 'icon' => 'fas fa-atom'],
        'chemistry' => ['color' => '#e67e22', 'icon' => 'fas fa-flask'],
        'biology' => ['color' => '#27ae60', 'icon' => 'fas fa-dna'],
        'botany' => ['color' => '#16a34a', 'icon' => 'fas fa-leaf'],
        'zoology' => ['color' => '#0d9488', 'icon' => 'fas fa-paw'],
    ];

    public function index()
    {
        $tabState = CoursePackageAccess::seriesTabState();

        if (!CoursePackageAccess::hasChapterMaster()) {
            $package = CoursePackageAccess::chapterMasterPackage();
            if ($package) {
                $package->loadMissing(['features', 'activeModes']);
            }

            return view('site.chapter_master.locked', [
                'package' => $package,
                'payMode' => $package ? CoursePackageAccess::onlineModeForPackage($package) : null,
                'activePackageTab' => 'chapter_master',
            ] + $tabState);
        }

        $fullAccess = CoursePackageAccess::chapterMasterFullAccess();
        $ownedIds = $fullAccess ? [] : CoursePackageAccess::ownedChapterIds();

        $subjects = $this->activeSubjects()->map(function (Subject $subject) use ($fullAccess, $ownedIds) {
            $key = strtolower($subject->name);
            $chapterIds = Chapter::where('subject_id', $subject->id)->where('status', 1)
                ->pluck('id')
                ->map(fn ($id) => (int) $id);
            if (!$fullAccess) {
                $chapterIds = $chapterIds->filter(fn ($id) => in_array($id, $ownedIds, true))->values();
            }

            $chaptersReady = (int) $chapterIds
                ->filter(fn ($id) => Question_source::chapterMasterDisplayCount($id, self::QUESTIONS_PER_CHAPTER) > 0)
                ->count();
            $available = (int) $chapterIds
                ->sum(fn ($id) => Question_source::chapterMasterDisplayCount($id, self::QUESTIONS_PER_CHAPTER));

            return [
                'subject' => $subject,
                'meta' => $this->subjectIcons[$key] ?? ['color' => '#5b4bb7', 'icon' => 'fas fa-book'],
                'available' => $available,
                'chapters_ready' => $chaptersReady,
                'owned_count' => $chapterIds->count(),
                'ready' => $chaptersReady > 0,
            ];
        })->filter(fn ($card) => $fullAccess || $card['owned_count'] > 0)->values();

        $attempts = CustomTest::with('examUser')
            ->where('user_id', Auth::id())
            ->where('name', 'like', 'Chapter Master%')
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        return view('site.chapter_master.index', [
            'subjects' => $subjects,
            'attempts' => $attempts,
            'questionsPerChapter' => self::QUESTIONS_PER_CHAPTER,
            'legacyFull' => $fullAccess,
            'fullAccess' => $fullAccess,
            'fullAccessSource' => CoursePackageAccess::chapterMasterFullSource(),
            'ownedChapterCount' => $fullAccess ? null : count($ownedIds),
            'activePackageTab' => 'chapter_master',
        ] + $tabState);
    }

    public function subject($subjectId)
    {
        if (!CoursePackageAccess::hasChapterMaster()) {
            return redirect()->route('chapter_master')
                ->with('error', 'Purchase Rank Chapter Master to access premium chapter tests.');
        }

        $subject = Subject::where('id', $subjectId)->where('status', 1)->firstOrFail();
        $fullAccess = CoursePackageAccess::chapterMasterFullAccess();
        if (!$fullAccess) {
            $ownedInSubject = Chapter::where('subject_id', $subject->id)
                ->where('status', 1)
                ->whereIn('id', CoursePackageAccess::ownedChapterIds())
                ->exists();
            if (!$ownedInSubject) {
                return redirect()->route('chapter_master')
                    ->with('error', 'You have not purchased chapters in this subject yet.');
            }
        }

        $key = strtolower($subject->name);
        $buyPackage = CoursePackageAccess::chapterMasterPackage();

        return view('site.chapter_master.subject', [
            'subject' => $subject,
            'meta' => $this->subjectIcons[$key] ?? ['color' => '#5b4bb7', 'icon' => 'fas fa-book'],
            'questionsPerChapter' => self::QUESTIONS_PER_CHAPTER,
            'fullAccess' => $fullAccess,
            'chapterBuyUrl' => $buyPackage
                ? route('course_packages.show', $buyPackage->slug)
                : route('course_packages'),
            'chapterUnitPrice' => $buyPackage ? (float) $buyPackage->lowestModePrice() : 49,
        ]);
    }

    public function chaptersData($subjectId)
    {
        if (!CoursePackageAccess::hasChapterMaster()) {
            return response()->json(['message' => 'Purchase required.'], 403);
        }

        $subject = Subject::where('id', $subjectId)->where('status', 1)->firstOrFail();

        $chapterQuery = Chapter::where('subject_id', $subject->id)->where('status', 1);
        $hasClass = Schema::hasColumn('chapters', 'class_level');
        if ($hasClass) {
            $chapterQuery->orderBy('class_level');
        }
        $chapters = $chapterQuery->orderBy('name')
            ->get($hasClass ? ['id', 'name', 'class_level'] : ['id', 'name'])
            ->map(function ($ch) {
                $level = (int) ($ch->class_level ?? 0);
                if ($level === 11) {
                    $ch->name .= ' (Class 11)';
                } elseif ($level === 12) {
                    $ch->name .= ' (Class 12)';
                }
                return $ch;
            });

        $topics = Topic::where('subject_id', $subject->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'chapter_id', 'name']);

        $topicCounts = Question::query()
            ->select('topic_id', DB::raw('COUNT(*) as total'))
            ->where('subject_id', $subject->id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->chapterMasterBank()
            ->whereNotNull('topic_id')
            ->groupBy('topic_id')
            ->pluck('total', 'topic_id');

        $topicsByChapter = $topics->groupBy('chapter_id');
        $fullAccess = CoursePackageAccess::chapterMasterFullAccess();
        $ownedLookup = $fullAccess ? [] : array_flip(CoursePackageAccess::ownedChapterIds());

        if (!$fullAccess) {
            $chapters = $chapters->filter(fn ($chapter) => isset($ownedLookup[(int) $chapter->id]))->values();
        }

        $payload = $chapters->map(function ($chapter) use ($topicsByChapter, $topicCounts) {
            $chapterTopics = ($topicsByChapter->get($chapter->id) ?? collect())->map(function ($topic) use ($topicCounts) {
                $q = (int) ($topicCounts[$topic->id] ?? 0);
                return [
                    'id' => (int) $topic->id,
                    'name' => $topic->name,
                    'questions' => $q,
                    'coming_soon' => $q < 1,
                ];
            })->values();

            $chapterQuestions = Question_source::chapterMasterDisplayCount((int) $chapter->id, self::QUESTIONS_PER_CHAPTER);

            return [
                'id' => (int) $chapter->id,
                'name' => $chapter->name,
                'questions' => $chapterQuestions,
                'coming_soon' => $chapterQuestions < 1,
                'owned' => true,
                'topics' => $chapterTopics,
            ];
        })->values();

        return response()->json([
            'subject' => ['id' => $subject->id, 'name' => $subject->name],
            'chapters' => $payload,
            'target_questions' => self::QUESTIONS_PER_CHAPTER,
            'full_access' => $fullAccess,
        ]);
    }

    public function start(Request $request)
    {
        if (!CoursePackageAccess::hasChapterMaster()) {
            return redirect()->route('chapter_master')
                ->with('error', 'Purchase Rank Chapter Master to start premium chapter tests.');
        }

        $data = $request->validate([
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'chapter_id' => ['required', 'integer'],
            'topic_id' => ['nullable', 'integer'],
        ]);

        $subject = Subject::where('id', $data['subject_id'])->where('status', 1)->firstOrFail();
        $chapter = Chapter::where('id', $data['chapter_id'])
            ->where('subject_id', $subject->id)
            ->where('status', 1)
            ->firstOrFail();

        if (!CoursePackageAccess::ownsChapter((int) $chapter->id)) {
            return redirect()
                ->route('chapter_master.subject', $subject->id)
                ->with('error', 'This chapter is not unlocked. Choose it on Rank Chapter Master to purchase it.');
        }

        $topic = null;
        if (!empty($data['topic_id'])) {
            $topic = Topic::where('id', $data['topic_id'])
                ->where('chapter_id', $chapter->id)
                ->where('status', 1)
                ->first();
        }

        $selection = [[
            'chapter_id' => (int) $chapter->id,
            'topic_ids' => $topic ? [(int) $topic->id] : [],
            'sub_topic_ids' => [],
        ]];

        $available = $this->premiumCount((int) $subject->id, $selection);
        if ($available < 1) {
            return redirect()
                ->route('chapter_master.subject', $subject->id)
                ->with('error', 'Coming soon — premium questions for this topic are not available yet.');
        }

        $count = min(self::QUESTIONS_PER_CHAPTER, $available);
        $questionIds = $this->pickPremiumQuestions((int) $subject->id, $selection, $count, Auth::id());
        if (count($questionIds) < 1) {
            return redirect()
                ->route('chapter_master.subject', $subject->id)
                ->with('error', 'Coming soon — premium questions for this selection are not available yet.');
        }

        $label = $topic
            ? ($chapter->name.' · '.$topic->name)
            : $chapter->name;
        $testName = 'Chapter Master — '.$subject->name.' — '.$label;
        $duration = max(15, (int) ceil(count($questionIds) * 1.0)); // ~1 min / Q
        $marksPerQ = function_exists('custom_test_marks_per_question')
            ? custom_test_marks_per_question()
            : 4;

        $custom = DB::transaction(function () use (
            $subject, $chapter, $topic, $questionIds, $testName, $duration, $marksPerQ, $selection, $label
        ) {
            $totalCount = count($questionIds);
            $paper = Question_paper::create([
                'name' => $testName.' - '.now()->format('d M Y H:i'),
                'no_of_question' => $totalCount,
                'totals_marks_for_exam' => $totalCount * $marksPerQ,
                'total_time_for_exam' => $duration,
                'marks_per_question' => $marksPerQ,
                'time_per_question' => max(1, (int) floor(($duration * 60) / max(1, $totalCount))),
                'negative_marking_applicable' => 1,
                'negative_marking_per_question' => 1,
                'hard_level' => 0,
                'medium_level' => 0,
                'easy_level' => 0,
                'status' => 1,
                'is_deleted' => 0,
            ]);

            $paperSubject = Question_paper_subject::create([
                'question_paper_id' => $paper->id,
                'subject_id' => $subject->id,
                'total_no_of_question' => $totalCount,
                'status' => 1,
            ]);

            $n = 1;
            foreach ($questionIds as $qid) {
                Question_paper_question::create([
                    'question_number' => $n++,
                    'question_id' => $qid,
                    'question_paper_id' => $paper->id,
                    'question_paper_subject_id' => $paperSubject->id,
                    'status' => 1,
                ]);
            }

            $exam = Exam::create([
                'name' => $testName,
                'type' => 1,
                'exam_code' => 'CM-'.Auth::id().'-'.strtoupper(substr(uniqid(), -6)),
                'question_paper_id' => $paper->id,
                'no_of_question' => $totalCount,
                'totals_marks_for_exam' => $totalCount * $marksPerQ,
                'total_time_for_exam' => $duration,
                'marks_per_question' => $marksPerQ,
                'time_per_question' => max(1, (int) floor(($duration * 60) / max(1, $totalCount))),
                'negative_marking_applicable' => 1,
                'negative_marking_per_question' => 1,
                'exam_date' => now()->toDateString(),
                'exam_time' => now()->format('H:i:s'),
                'status' => 1,
                'is_deleted' => 0,
                'is_proctored' => 0,
            ]);

            DB::table('exam_assignments')->insert([
                'exam_id' => $exam->id,
                'user_id' => Auth::id(),
            ]);

            return CustomTest::create([
                'user_id' => Auth::id(),
                'subject_id' => $subject->id,
                'name' => $testName,
                'question_count' => $totalCount,
                'duration_minutes' => $duration,
                'marks_per_question' => $marksPerQ,
                'negative_marking' => true,
                'negative_marks' => 1,
                'difficulty' => null,
                'question_paper_id' => $paper->id,
                'exam_id' => $exam->id,
                'exam_user_id' => null,
                'status' => 'ready',
                'selection' => [
                    'source' => 'chapter_master',
                    'premium_only' => true,
                    'chapter_id' => $chapter->id,
                    'topic_id' => $topic?->id,
                    'label' => $label,
                    'plan' => [
                        $subject->id => [
                            'question_count' => $totalCount,
                            'selection' => $selection,
                            'name' => $subject->name,
                        ],
                    ],
                    'subject_names' => [$subject->name],
                ],
            ]);
        });

        return redirect()->route('custom_test.ready', $custom->id)
            ->with('success', 'Premium chapter test ready. Start when you are prepared.');
    }

    private function activeSubjects()
    {
        return Subject::where('status', 1)
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->orderByRaw("FIELD(name, 'Physics', 'Chemistry', 'Biology', 'Botany', 'Zoology')")
            ->orderBy('name')
            ->get();
    }

    private function premiumBaseQuery(int $subjectId, ?array $selection = null)
    {
        $query = Question::query()
            ->where('subject_id', $subjectId)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->chapterMasterBank();

        if (empty($selection)) {
            return $query;
        }

        $query->where(function ($outer) use ($selection) {
            foreach ($selection as $item) {
                $chapterId = (int) ($item['chapter_id'] ?? 0);
                $topicIds = array_values(array_filter(array_map('intval', $item['topic_ids'] ?? [])));
                if ($chapterId < 1) {
                    continue;
                }
                $outer->orWhere(function ($q) use ($chapterId, $topicIds) {
                    Question::constrainBySources($q, Question_source::chapterMasterSetIdsForChapter($chapterId));
                    if (!empty($topicIds)) {
                        $q->whereIn('topic_id', $topicIds);
                    }
                });
            }
        });

        return $query;
    }

    private function premiumCount(int $subjectId, ?array $selection = null): int
    {
        return (int) $this->premiumBaseQuery($subjectId, $selection)->count();
    }

    private function chaptersWithPremium(int $subjectId): int
    {
        $chapterIds = Chapter::where('subject_id', $subjectId)->where('status', 1)->pluck('id')->map(fn ($id) => (int) $id);

        return (int) $chapterIds
            ->filter(fn ($id) => Question_source::chapterMasterDisplayCount((int) $id, self::QUESTIONS_PER_CHAPTER) > 0)
            ->count();
    }

    private function pickPremiumQuestions(int $subjectId, array $selection, int $count, ?int $userId = null): array
    {
        $base = $this->premiumBaseQuery($subjectId, $selection);
        $picked = [];

        if ($userId) {
            $usedSubquery = DB::table('question_paper_questions as qpq')
                ->join('custom_tests as ct', 'ct.question_paper_id', '=', 'qpq.question_paper_id')
                ->where('ct.user_id', $userId)
                ->whereNotNull('ct.question_paper_id')
                ->select('qpq.question_id');

            $picked = (clone $base)
                ->whereNotIn('id', $usedSubquery)
                ->orderByRaw('RAND(?)', [mt_rand()])
                ->limit($count)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        if (count($picked) < $count) {
            $needed = $count - count($picked);
            $filler = (clone $base)
                ->when(!empty($picked), fn ($q) => $q->whereNotIn('id', $picked))
                ->orderByRaw('RAND(?)', [mt_rand()])
                ->limit($needed)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $picked = array_merge($picked, $filler);
        }

        shuffle($picked);

        return $picked;
    }
}
