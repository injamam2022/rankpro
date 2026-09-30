<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\CustomTest;
use App\Models\Exam;
use App\Models\Exam_user;
use App\Models\Question;
use App\Models\Question_paper;
use App\Models\Question_paper_question;
use App\Models\Question_paper_subject;
use App\Models\Subject;
use App\Models\Sub_topic;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomTestController extends Controller
{
    private array $subjectIcons = [
        'physics' => ['color' => '#2f6fed', 'icon' => 'fas fa-atom'],
        'chemistry' => ['color' => '#e67e22', 'icon' => 'fas fa-flask'],
        'biology' => ['color' => '#27ae60', 'icon' => 'fas fa-dna'],
    ];

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'create');
        $subjects = $this->activeSubjects();
        $builder = $this->builder();

        $subjectCards = [];
        foreach ($subjects as $subject) {
            $key = strtolower($subject->name);
            $state = $builder['subjects'][$subject->id] ?? [];
            $available = $this->countAvailableQuestions(
                (int) $subject->id,
                $state['selection'] ?? null
            );
            $subjectCards[] = [
                'subject' => $subject,
                'meta' => $this->subjectIcons[$key] ?? ['color' => '#5b4bb7', 'icon' => 'fas fa-book'],
                'presets' => $key === 'biology' ? [10, 15, 20, 90] : [10, 15, 20, 45],
                'question_count' => (int) ($state['question_count'] ?? 0),
                'available' => $available,
                'has_selection' => !empty($state['selection']),
                'selection_label' => $this->selectionLabel($state['selection'] ?? null),
            ];
        }

        $attempted = CustomTest::with('examUser')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['ready', 'attempted'])
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(function (CustomTest $item) {
                $examUser = $item->examUser;
                $finished = $examUser
                    && $examUser->percentage !== null
                    && $examUser->percentage !== '';
                $progress = $finished ? round((float) $examUser->percentage, 1) : 0.0;
                if ($progress < 0) {
                    $progress = 0.0;
                }
                if ($progress > 100) {
                    $progress = 100.0;
                }

                $item->is_finished = $finished;
                $item->progress_pct = $progress;
                $item->list_href = $finished
                    ? route('custom_test.analysis', $item->id)
                    : route('custom_test.resume', $item->id);

                return $item;
            });

        return view('site.custom_test.index', [
            'tab' => $tab,
            'subjectCards' => $subjectCards,
            'attempted' => $attempted,
        ]);
    }

    public function chapters(Request $request, $subjectId)
    {
        $subject = Subject::where('id', $subjectId)->where('status', 1)->firstOrFail();
        $builder = $this->builder();
        $saved = $builder['subjects'][$subject->id]['selection'] ?? [];

        return view('site.custom_test.chapters', [
            'subject' => $subject,
            'meta' => $this->subjectIcons[strtolower($subject->name)] ?? ['color' => '#5b4bb7', 'icon' => 'fas fa-book'],
            'savedSelection' => $saved,
        ]);
    }

    public function chaptersData(Request $request, $subjectId)
    {
        $subject = Subject::where('id', $subjectId)->where('status', 1)->firstOrFail();

        $chapters = Chapter::where('subject_id', $subject->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $topics = Topic::where('subject_id', $subject->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'chapter_id', 'name']);

        $subTopics = Sub_topic::where('subject_id', $subject->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'chapter_id', 'topic_id', 'name']);

        $chapterCounts = Question::query()
            ->select('chapter_id', DB::raw('COUNT(*) as total'))
            ->where('subject_id', $subject->id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->whereNotNull('chapter_id')
            ->groupBy('chapter_id')
            ->pluck('total', 'chapter_id');

        $topicCounts = Question::query()
            ->select('topic_id', DB::raw('COUNT(*) as total'))
            ->where('subject_id', $subject->id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->whereNotNull('topic_id')
            ->groupBy('topic_id')
            ->pluck('total', 'topic_id');

        $subTopicCounts = Question::query()
            ->select('sub_topic_id', DB::raw('COUNT(*) as total'))
            ->where('subject_id', $subject->id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->whereNotNull('sub_topic_id')
            ->groupBy('sub_topic_id')
            ->pluck('total', 'sub_topic_id');

        $topicsByChapter = $topics->groupBy('chapter_id');
        $subTopicsByTopic = $subTopics->groupBy('topic_id');

        $payload = $chapters->map(function ($chapter) use ($topicsByChapter, $subTopicsByTopic, $chapterCounts, $topicCounts, $subTopicCounts) {
            $chapterTopics = ($topicsByChapter->get($chapter->id) ?? collect())->map(function ($topic) use ($subTopicsByTopic, $topicCounts, $subTopicCounts) {
                $subs = ($subTopicsByTopic->get($topic->id) ?? collect())->map(function ($sub) use ($subTopicCounts) {
                    return [
                        'id' => (int) $sub->id,
                        'name' => $sub->name,
                        'questions' => (int) ($subTopicCounts[$sub->id] ?? 0),
                    ];
                })->filter(fn ($sub) => $sub['questions'] > 0)->values();

                $topicQuestions = (int) ($topicCounts[$topic->id] ?? 0);

                return [
                    'id' => (int) $topic->id,
                    'name' => $topic->name,
                    'questions' => $topicQuestions,
                    'subtopics' => $subs,
                ];
            })->filter(fn ($topic) => $topic['questions'] > 0 || $topic['subtopics']->count() > 0)->values();

            $chapterQuestions = (int) ($chapterCounts[$chapter->id] ?? 0);

            return [
                'id' => (int) $chapter->id,
                'name' => $chapter->name,
                'questions' => $chapterQuestions,
                'topic_count' => $chapterTopics->count(),
                'topics' => $chapterTopics,
            ];
        })->filter(fn ($chapter) => $chapter['questions'] > 0 || $chapter['topic_count'] > 0)->values();

        return response()->json([
            'subject' => ['id' => $subject->id, 'name' => $subject->name],
            'chapters' => $payload,
        ]);
    }

    /** Save chapter/topic selection for one subject, then return to Step 1 cards. */
    public function saveChapters(Request $request)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'selection' => ['nullable', 'array'],
            'selection.*.chapter_id' => ['required', 'integer'],
            'selection.*.topic_ids' => ['nullable', 'array'],
            'selection.*.topic_ids.*' => ['integer'],
            'selection.*.sub_topic_ids' => ['nullable', 'array'],
            'selection.*.sub_topic_ids.*' => ['integer'],
            'clear' => ['nullable', 'boolean'],
        ]);

        $subjectId = (int) $data['subject_id'];
        $builder = $this->builder();

        if (!empty($data['clear']) || empty($data['selection'])) {
            unset($builder['subjects'][$subjectId]['selection']);
            if (empty($builder['subjects'][$subjectId]['question_count'])) {
                unset($builder['subjects'][$subjectId]);
            }
            session(['custom_test_builder' => $builder]);

            return redirect()->route('custom_test')->with('success', 'Chapter filters cleared.');
        }

        $available = $this->countAvailableQuestions($subjectId, $data['selection']);
        if ($available < 1) {
            return redirect()
                ->route('custom_test.chapters', $subjectId)
                ->with('error', 'No questions available for the selected chapters/topics.');
        }

        $builder['subjects'][$subjectId] = array_merge($builder['subjects'][$subjectId] ?? [], [
            'selection' => array_values($data['selection']),
            'available' => $available,
        ]);

        // Default to first common preset after chapter pick.
        if (empty($builder['subjects'][$subjectId]['question_count'])) {
            $builder['subjects'][$subjectId]['question_count'] = min(15, $available);
        } else {
            $builder['subjects'][$subjectId]['question_count'] = min(
                (int) $builder['subjects'][$subjectId]['question_count'],
                $available
            );
        }

        session(['custom_test_builder' => $builder]);

        return redirect()->route('custom_test')->with('success', 'Chapters saved. Choose how many questions, then Next.');
    }

    /** Step 1 Next: save per-subject question counts, go to Step 2. */
    public function configure(Request $request)
    {
        $data = $request->validate([
            'counts' => ['required', 'array'],
            'counts.*' => ['nullable', 'integer', 'min:0', 'max:200'],
        ]);

        $builder = $this->builder();
        $subjects = $this->activeSubjects()->keyBy('id');
        $plan = [];

        foreach ($data['counts'] as $subjectId => $count) {
            $subjectId = (int) $subjectId;
            $count = (int) $count;
            if ($count < 1 || !$subjects->has($subjectId)) {
                continue;
            }

            $selection = $builder['subjects'][$subjectId]['selection'] ?? null;
            if (empty($selection)) {
                continue; // only subjects with chapter/topic selection (TrackPrep-style)
            }

            $available = $this->countAvailableQuestions($subjectId, $selection);
            $count = min($count, $available);
            if ($count < 1) {
                continue;
            }

            $plan[$subjectId] = [
                'question_count' => $count,
                'selection' => $selection,
                'available' => $available,
                'name' => $subjects[$subjectId]->name,
            ];
        }

        if (empty($plan)) {
            return redirect()
                ->route('custom_test')
                ->with('error', 'Use Show Chapters to select at least one subject, then choose a question count.');
        }

        $builder['subjects'] = $plan;
        $builder['ready'] = true;
        session(['custom_test_builder' => $builder]);

        $totalQuestions = collect($plan)->sum('question_count');
        $names = collect($plan)->pluck('name')->implode(' + ');

        return view('site.custom_test.configure', [
            'plan' => $plan,
            'totalQuestions' => $totalQuestions,
            'subjectNames' => $names,
            'defaultDuration' => max(30, (int) ceil($totalQuestions * 1.5)),
        ]);
    }

    public function generate(Request $request)
    {
        $builder = $this->builder();
        $plan = $builder['subjects'] ?? [];
        if (empty($plan) || empty($builder['ready'])) {
            return redirect()->route('custom_test')->with('error', 'Please configure your custom test again.');
        }

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:80'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:300'],
            'difficulty' => ['nullable', 'in:1,2,3'],
            'negative_marking' => ['nullable', 'boolean'],
        ]);

        $difficulty = $data['difficulty'] ?? null;
        $userId = Auth::id();
        $picked = [];
        foreach ($plan as $subjectId => $item) {
            $ids = $this->pickQuestions(
                (int) $subjectId,
                $item['selection'] ?? null,
                (int) $item['question_count'],
                $difficulty,
                (int) $userId
            );
            if (count($ids) < 1) {
                return back()->with('error', 'Not enough questions for '.$item['name'].'. Adjust count or chapters.');
            }
            $picked[$subjectId] = $ids;
        }

        $totalCount = collect($picked)->sum(fn ($ids) => count($ids));
        if ($totalCount < 1) {
            return back()->with('error', 'Could not pick questions. Try again.');
        }

        $duration = (int) $data['duration_minutes'];
        $marksPerQ = 4;
        $negative = (bool) ($data['negative_marking'] ?? false);
        $negMarks = $negative ? 1 : 0;
        $subjectNames = collect($plan)->pluck('name')->values()->all();
        $namesJoined = implode(' + ', $subjectNames);
        $primarySubjectId = (int) array_key_first($plan);
        $testName = trim((string) ($data['name'] ?? ''));
        if ($testName === '') {
            $testName = 'Custom Test';
        }

        $custom = DB::transaction(function () use (
            $plan, $picked, $totalCount, $duration, $marksPerQ, $negative, $negMarks,
            $userId, $data, $namesJoined, $primarySubjectId, $testName, $subjectNames
        ) {
            $paper = Question_paper::create([
                'name' => $testName.' - '.$namesJoined.' - '.now()->format('d M Y H:i'),
                'no_of_question' => $totalCount,
                'totals_marks_for_exam' => $totalCount * $marksPerQ,
                'total_time_for_exam' => $duration,
                'marks_per_question' => $marksPerQ,
                'time_per_question' => max(1, (int) floor(($duration * 60) / max(1, $totalCount))),
                'negative_marking_applicable' => $negative ? 1 : 0,
                'negative_marking_per_question' => $negMarks,
                'hard_level' => 0,
                'medium_level' => 0,
                'easy_level' => 0,
                'status' => 1,
                'is_deleted' => 0,
            ]);

            $n = 1;
            foreach ($picked as $subjectId => $questionIds) {
                $paperSubject = Question_paper_subject::create([
                    'question_paper_id' => $paper->id,
                    'subject_id' => $subjectId,
                    'total_no_of_question' => count($questionIds),
                    'status' => 1,
                ]);

                foreach ($questionIds as $qid) {
                    Question_paper_question::create([
                        'question_number' => $n++,
                        'question_id' => $qid,
                        'question_paper_id' => $paper->id,
                        'question_paper_subject_id' => $paperSubject->id,
                        'status' => 1,
                    ]);
                }
            }

            $examCode = 'CT-'.$userId.'-'.strtoupper(substr(uniqid(), -6));
            $exam = Exam::create([
                'name' => $testName,
                'type' => 1,
                'exam_code' => $examCode,
                'question_paper_id' => $paper->id,
                'no_of_question' => $totalCount,
                'totals_marks_for_exam' => $totalCount * $marksPerQ,
                'total_time_for_exam' => $duration,
                'marks_per_question' => $marksPerQ,
                'time_per_question' => max(1, (int) floor(($duration * 60) / max(1, $totalCount))),
                'negative_marking_applicable' => $negative ? 1 : 0,
                'negative_marking_per_question' => $negMarks,
                'exam_date' => now()->toDateString(),
                'exam_time' => now()->format('H:i:s'),
                'status' => 1,
                'is_deleted' => 0,
                'is_proctored' => 0,
            ]);

            DB::table('exam_assignments')->insert([
                'exam_id' => $exam->id,
                'user_id' => $userId,
            ]);

            return CustomTest::create([
                'user_id' => $userId,
                'subject_id' => $primarySubjectId,
                'name' => $testName,
                'question_count' => $totalCount,
                'duration_minutes' => $duration,
                'marks_per_question' => $marksPerQ,
                'negative_marking' => $negative,
                'negative_marks' => $negMarks,
                'difficulty' => $data['difficulty'] ?? null,
                'question_paper_id' => $paper->id,
                'exam_id' => $exam->id,
                'exam_user_id' => null,
                'status' => 'ready',
                'selection' => [
                    'plan' => $plan,
                    'subject_names' => $subjectNames,
                ],
            ]);
        });

        session()->forget('custom_test_builder');

        return redirect()->route('custom_test.ready', $custom->id);
    }

    public function ready(Request $request, $id)
    {
        $custom = CustomTest::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $subjectNames = $custom->selection['subject_names']
            ?? collect($custom->selection['plan'] ?? [])->pluck('name')->values()->all();

        return view('site.custom_test.ready', [
            'custom' => $custom,
            'subjectNames' => $subjectNames,
            'subjectLabel' => implode(' · ', $subjectNames),
        ]);
    }

    public function start(Request $request, $id)
    {
        $custom = CustomTest::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if (!$custom->exam_id) {
            return redirect()->route('custom_test')->with('error', 'This custom test is incomplete.');
        }

        $userId = Auth::id();
        $examUser = null;
        if ($custom->exam_user_id) {
            $examUser = Exam_user::where('id', $custom->exam_user_id)->where('user_id', $userId)->first();
        }

        if (!$examUser) {
            $examUser = Exam_user::create([
                'exam_id' => $custom->exam_id,
                'user_id' => $userId,
                'exam_type' => 'CUSTOM',
                'total_time' => 0,
            ]);
            $custom->update([
                'exam_user_id' => $examUser->id,
                'status' => 'attempted',
            ]);
        }

        return redirect()->route('start_online_exam', ['id' => $examUser->id]);
    }

    public function download(Request $request, $id)
    {
        $custom = CustomTest::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if (!$custom->question_paper_id) {
            abort(404);
        }

        $rows = Question_paper_question::query()
            ->select([
                'question_paper_questions.question_number',
                'questions.id as question_id',
                'questions.subject_id',
                'questions.answer',
                'subjects.name as subject_name',
                'chapters.name as chapter_name',
                'topics.name as topic_name',
                'question_details.question_text',
                'question_details.option1',
                'question_details.option2',
                'question_details.option3',
                'question_details.option4',
                'question_details.is_option1_image',
                'question_details.is_option2_image',
                'question_details.is_option3_image',
                'question_details.is_option4_image',
                'question_details.question_image',
                'question_details.solution',
            ])
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'questions.subject_id')
            ->leftJoin('chapters', 'chapters.id', '=', 'questions.chapter_id')
            ->leftJoin('topics', 'topics.id', '=', 'questions.topic_id')
            ->leftJoin('question_details', function ($join) {
                $join->on('question_details.question_id', '=', 'questions.id')
                    ->where('question_details.language_id', 1);
            })
            ->where('question_paper_questions.question_paper_id', $custom->question_paper_id)
            ->orderByRaw("FIELD(subjects.name, 'Physics', 'Chemistry', 'Biology')")
            ->orderBy('subjects.name')
            ->orderBy('question_paper_questions.question_number')
            ->get();

        $grouped = [];
        $serial = 1;
        foreach ($rows as $row) {
            $subject = $row->subject_name ?: 'General';
            if (!isset($grouped[$subject])) {
                $grouped[$subject] = [
                    'subject_name' => $subject,
                    'questions' => [],
                    'topics' => [],
                ];
            }
            $row->paper_serial = $serial++;
            $grouped[$subject]['questions'][] = $row;
            if ($row->chapter_name || $row->topic_name) {
                $label = trim(($row->chapter_name ?? '').($row->topic_name ? ' - '.$row->topic_name : ''));
                if ($label !== '') {
                    $grouped[$subject]['topics'][$label] = $label;
                }
            }
        }

        foreach ($grouped as &$group) {
            $group['topics'] = array_values($group['topics']);
        }
        unset($group);

        $difficulty = match ((int) ($custom->difficulty ?? 0)) {
            1 => 'Easy',
            2 => 'Medium',
            3 => 'Hard',
            default => 'Mixed',
        };

        $user = Auth::user();
        $studentName = trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: ($user->email_id ?? 'Student');
        $studentMeta = collect([
            $studentName,
            $user->email_id ?? null,
            $user->mobile_no ?? ($user->phone ?? null),
        ])->filter()->implode(' | ');

        return view('site.custom_test.download', [
            'custom' => $custom,
            'grouped' => $grouped,
            'subjectNames' => array_keys($grouped),
            'difficulty' => $difficulty,
            'totalMarks' => (int) round($custom->question_count * (float) $custom->marks_per_question),
            'studentMeta' => $studentMeta,
            'generatedAt' => now()->format('d/m/Y, h:i:s A'),
        ]);
    }

    public function resume(Request $request, $id)
    {
        $custom = CustomTest::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($custom->exam_user_id) {
            $examUser = Exam_user::where('id', $custom->exam_user_id)->where('user_id', Auth::id())->first();
            if ($examUser && $examUser->percentage !== null && $examUser->percentage !== '') {
                return redirect()->route('custom_test.analysis', $custom->id);
            }
        }

        if ($custom->status === 'ready' && !$custom->exam_user_id) {
            return redirect()->route('custom_test.ready', $custom->id);
        }

        if (!$custom->exam_user_id) {
            return redirect()->route('custom_test.ready', $custom->id);
        }

        return redirect()->route('start_online_exam', ['id' => $custom->exam_user_id]);
    }

    public function analysis(Request $request, $id)
    {
        $custom = CustomTest::with('examUser')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $examUser = $custom->examUser;
        if (!$examUser || $examUser->percentage === null || $examUser->percentage === '') {
            return redirect()->route('custom_test.resume', $custom->id);
        }

        $totalQuestions = max(1, (int) $custom->question_count);
        $marksPerQ = (float) ($custom->marks_per_question ?: 4);
        $totalMarks = (int) ($examUser->total_mark ?: round($totalQuestions * $marksPerQ));
        $score = (float) ($examUser->total_number ?? 0);
        $correct = (int) ($examUser->total_right_answer ?? 0);
        $attempted = (int) ($examUser->total_answer ?? 0);
        $incorrect = max(0, $attempted - $correct);
        $unattempted = max(0, $totalQuestions - $attempted);
        $accuracy = $attempted > 0 ? round(($correct / $attempted) * 100) : 0;
        $timeMinutes = (int) floor(((int) ($examUser->total_time ?? 0)) / 60);

        $subjectStats = [];
        if ($custom->question_paper_id) {
            $rows = Question_paper_question::query()
                ->select([
                    'question_paper_questions.id as paper_question_id',
                    'questions.subject_id',
                    'subjects.name as subject_name',
                    'questions.answer as correct_answer',
                    'exam_results.answer as user_answer',
                    'exam_results.result as result_marks',
                ])
                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                ->leftJoin('subjects', 'subjects.id', '=', 'questions.subject_id')
                ->leftJoin('exam_results', function ($join) use ($examUser) {
                    $join->on('exam_results.exam_question_id', '=', 'question_paper_questions.id')
                        ->where('exam_results.exam_user_id', '=', $examUser->id);
                })
                ->where('question_paper_questions.question_paper_id', $custom->question_paper_id)
                ->orderBy('question_paper_questions.question_number')
                ->get();

            $grouped = [];
            foreach ($rows as $row) {
                $sid = (int) ($row->subject_id ?? 0);
                if ($sid < 1) {
                    continue;
                }
                if (!isset($grouped[$sid])) {
                    $grouped[$sid] = [
                        'id' => $sid,
                        'name' => $row->subject_name ?: 'Subject',
                        'total' => 0,
                        'correct' => 0,
                        'incorrect' => 0,
                        'unattempted' => 0,
                        'score' => 0.0,
                    ];
                }
                $grouped[$sid]['total']++;
                $userAnswer = $row->user_answer;
                if ($userAnswer === null || $userAnswer === '') {
                    $grouped[$sid]['unattempted']++;
                } elseif ((string) $userAnswer === (string) $row->correct_answer) {
                    $grouped[$sid]['correct']++;
                    $grouped[$sid]['score'] += $marksPerQ;
                } else {
                    $grouped[$sid]['incorrect']++;
                    if ($custom->negative_marking) {
                        $grouped[$sid]['score'] -= (float) $custom->negative_marks;
                    }
                }
            }

            foreach ($grouped as $item) {
                $subjectTotalMarks = max(1, (int) round($item['total'] * $marksPerQ));
                $attemptedSubject = $item['correct'] + $item['incorrect'];
                $subjectAccuracy = $attemptedSubject > 0
                    ? round(($item['correct'] / $attemptedSubject) * 100)
                    : 0;
                $pctOfMax = round(max(0, $item['score']) / $subjectTotalMarks * 100);
                $needsWork = $subjectAccuracy < 50;

                $subjectStats[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'score' => (int) round($item['score']),
                    'total_marks' => $subjectTotalMarks,
                    'accuracy' => $subjectAccuracy,
                    'correct' => $item['correct'],
                    'incorrect' => $item['incorrect'],
                    'unattempted' => $item['unattempted'],
                    'bar_pct' => min(100, $pctOfMax),
                    'needs_improvement' => $needsWork,
                    'status_label' => $needsWork ? 'Needs improvement' : 'Looking good',
                ];
            }
        }

        return view('site.custom_test.analysis', [
            'custom' => $custom,
            'examUser' => $examUser,
            'score' => (int) round($score),
            'totalMarks' => $totalMarks,
            'accuracy' => $accuracy,
            'correct' => $correct,
            'incorrect' => $incorrect,
            'unattempted' => $unattempted,
            'timeMinutes' => $timeMinutes,
            'subjectStats' => $subjectStats,
            'reviewUrl' => route('exam_result_detail', ['id' => $examUser->id]),
        ]);
    }

    private function activeSubjects()
    {
        return Subject::where('status', 1)
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->orderByRaw("FIELD(name, 'Physics', 'Chemistry', 'Biology')")
            ->orderBy('name')
            ->get();
    }

    private function builder(): array
    {
        $builder = session('custom_test_builder', []);
        if (!isset($builder['subjects']) || !is_array($builder['subjects'])) {
            $builder['subjects'] = [];
        }

        return $builder;
    }

    private function selectionLabel(?array $selection): string
    {
        if (empty($selection)) {
            return 'All chapters';
        }
        $chapters = count($selection);
        $topics = 0;
        $subs = 0;
        foreach ($selection as $item) {
            $topics += count($item['topic_ids'] ?? []);
            $subs += count($item['sub_topic_ids'] ?? []);
        }
        $parts = [$chapters.' chapter'.($chapters === 1 ? '' : 's')];
        if ($topics) {
            $parts[] = $topics.' topic'.($topics === 1 ? '' : 's');
        }
        if ($subs) {
            $parts[] = $subs.' subtopic'.($subs === 1 ? '' : 's');
        }

        return implode(' · ', $parts);
    }

    private function baseQuestionQuery(int $subjectId, ?array $selection, $difficulty = null)
    {
        $query = Question::query()
            ->where('subject_id', $subjectId)
            ->where('is_deleted', 0)
            ->where('status', 1);

        if ($difficulty) {
            $query->where('difficulty_level', (int) $difficulty);
        }

        if (empty($selection)) {
            return $query;
        }

        $query->where(function ($outer) use ($selection) {
            foreach ($selection as $item) {
                $chapterId = (int) ($item['chapter_id'] ?? 0);
                $topicIds = array_values(array_filter(array_map('intval', $item['topic_ids'] ?? [])));
                $subTopicIds = array_values(array_filter(array_map('intval', $item['sub_topic_ids'] ?? [])));
                if ($chapterId < 1) {
                    continue;
                }
                $outer->orWhere(function ($q) use ($chapterId, $topicIds, $subTopicIds) {
                    $q->where('chapter_id', $chapterId);
                    if (!empty($subTopicIds)) {
                        $q->whereIn('sub_topic_id', $subTopicIds);
                    } elseif (!empty($topicIds)) {
                        $q->whereIn('topic_id', $topicIds);
                    }
                });
            }
        });

        return $query;
    }

    private function countAvailableQuestions(int $subjectId, ?array $selection, $difficulty = null): int
    {
        return (int) $this->baseQuestionQuery($subjectId, $selection, $difficulty)->count();
    }

    private function pickQuestions(int $subjectId, ?array $selection, int $count, $difficulty = null, ?int $userId = null): array
    {
        $base = $this->baseQuestionQuery($subjectId, $selection, $difficulty);
        $picked = [];

        // Prefer questions this student has not already seen in a custom test.
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

        // Top up from the full pool when unused questions are insufficient.
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
