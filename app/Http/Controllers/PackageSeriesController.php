<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\CoursePackage;
use App\Models\CourseSchedule;
use App\Models\CustomTest;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Question_paper;
use App\Models\Question_paper_question;
use App\Models\Question_paper_subject;
use App\Models\Subject;
use App\Models\Topic;
use App\Support\CoursePackageAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageSeriesController extends Controller
{
    /** Target mix for a NEET-style series paper when premium bank has enough Qs. */
    private const TARGET_PHYSICS = 45;
    private const TARGET_CHEMISTRY = 45;
    private const TARGET_BIOLOGY = 90;
    private const MIN_QUESTIONS_TO_START = 10;

    public function index()
    {
        $orders = CoursePackageAccess::ownedSeriesOrders();

        if ($orders->isEmpty()) {
            $featured = CoursePackage::active()
                ->whereIn('code', CoursePackageAccess::seriesPackageCodes())
                ->orderBy('sort_order')
                ->get();

            return view('site.package_series.locked', compact('featured'));
        }

        if ($orders->count() === 1) {
            return redirect()->route('package_series.show', $orders->first()->package->slug);
        }

        return view('site.package_series.index', compact('orders'));
    }

    public function show(string $slug)
    {
        $package = CoursePackage::active()->where('slug', $slug)->firstOrFail();

        if (!in_array($package->code, CoursePackageAccess::seriesPackageCodes(), true)) {
            abort(404);
        }

        if (!CoursePackageAccess::hasSeriesPackage($package->code)) {
            return view('site.package_series.locked', [
                'featured' => collect([$package]),
                'focusPackage' => $package,
            ]);
        }

        $order = CoursePackageAccess::ownedSeriesOrders()
            ->first(fn ($o) => (int) $o->course_package_id === (int) $package->id);

        $schedules = CoursePackageAccess::schedulesQueryForPackage($package)->get();

        $attempts = CustomTest::with('examUser')
            ->where('user_id', Auth::id())
            ->where('name', 'like', $package->name.'%')
            ->orderByDesc('id')
            ->get()
            ->groupBy(function (CustomTest $t) {
                return (string) data_get($t->selection, 'schedule_id', '');
            });

        $rows = $schedules->map(function (CourseSchedule $s) use ($attempts, $package) {
            $key = (string) $s->id;
            $attemptList = $attempts->get($key, collect());
            $latest = $attemptList->first();
            $today = now()->startOfDay();
            $testDay = optional($s->test_date)->copy()?->startOfDay();

            $status = 'available';
            if ($latest && in_array($latest->status, ['completed', 'submitted', 'finished'], true)) {
                $status = 'completed';
            } elseif ($latest && $latest->status === 'ready') {
                $status = 'ready';
            } elseif ($latest && in_array($latest->status, ['in_progress', 'started'], true)) {
                $status = 'in_progress';
            } elseif ($testDay && $testDay->gt($today)) {
                $status = 'upcoming';
            }

            $coverage = collect($s->subjects ?? [])
                ->map(function ($row) {
                    $sub = trim((string) ($row['subject'] ?? ''));
                    $topics = trim((string) ($row['topics'] ?? ''));
                    if ($sub === '' && $topics !== '') {
                        return $topics;
                    }
                    if ($topics === '') {
                        return $sub;
                    }

                    return $sub.': '.$topics;
                })
                ->filter()
                ->values()
                ->all();

            return [
                'schedule' => $s,
                'coverage' => $coverage,
                'status' => $status,
                'attempt' => $latest,
                'can_start_online' => (bool) $package->includes_online
                    && in_array($status, ['available', 'upcoming'], true),
                'can_resume' => $latest && in_array($status, ['ready', 'in_progress'], true),
            ];
        });

        return view('site.package_series.show', [
            'package' => $package,
            'order' => $order,
            'rows' => $rows,
        ]);
    }

    public function start(Request $request, string $slug)
    {
        $package = CoursePackage::active()->where('slug', $slug)->firstOrFail();

        if (!CoursePackageAccess::hasSeriesPackage($package->code)) {
            return redirect()->route('package_series.show', $slug)
                ->with('error', 'Purchase this package to start series exams.');
        }

        if (!$package->includes_online) {
            return redirect()->route('package_series.show', $slug)
                ->with('error', 'This package is OMR / offline only. Online CBT is not included.');
        }

        $data = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:course_schedules,id'],
        ]);

        $schedule = CourseSchedule::active()->findOrFail($data['schedule_id']);

        // Ensure schedule belongs to this package scope
        $allowedIds = CoursePackageAccess::schedulesQueryForPackage($package)->pluck('id')->all();
        if (!in_array((int) $schedule->id, array_map('intval', $allowedIds), true)) {
            return redirect()->route('package_series.show', $slug)
                ->with('error', 'This test is not part of your package schedule.');
        }

        $existing = CustomTest::where('user_id', Auth::id())
            ->where('status', 'ready')
            ->get()
            ->first(function (CustomTest $t) use ($schedule, $package) {
                return (int) data_get($t->selection, 'schedule_id') === (int) $schedule->id
                    && data_get($t->selection, 'package_code') === $package->code;
            });

        if ($existing) {
            return redirect()->route('custom_test.ready', $existing->id)
                ->with('success', 'Your test is already ready. Start when you are prepared.');
        }

        $picked = $this->pickQuestionsForSchedule($schedule);
        $questionPlan = $picked['plan'];
        $allIds = $picked['question_ids'];

        if (count($allIds) < self::MIN_QUESTIONS_TO_START) {
            return redirect()->route('package_series.show', $slug)
                ->with('error', 'Coming soon — premium questions for this test syllabus are not ready yet. Please check back after the admin fills the Premium Bank.');
        }

        $testName = $package->name.' — '.$schedule->test_type;
        $duration = max(30, (int) ceil(count($allIds) * 1.0));
        $marksPerQ = function_exists('custom_test_marks_per_question')
            ? custom_test_marks_per_question()
            : 4;

        $custom = DB::transaction(function () use (
            $package, $schedule, $questionPlan, $allIds, $testName, $duration, $marksPerQ
        ) {
            $totalCount = count($allIds);
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

            $n = 1;
            $planMeta = [];
            foreach ($questionPlan as $subjectId => $info) {
                $ids = $info['ids'];
                if (empty($ids)) {
                    continue;
                }
                $paperSubject = Question_paper_subject::create([
                    'question_paper_id' => $paper->id,
                    'subject_id' => $subjectId,
                    'total_no_of_question' => count($ids),
                    'status' => 1,
                ]);
                foreach ($ids as $qid) {
                    Question_paper_question::create([
                        'question_number' => $n++,
                        'question_id' => $qid,
                        'question_paper_id' => $paper->id,
                        'question_paper_subject_id' => $paperSubject->id,
                        'status' => 1,
                    ]);
                }
                $planMeta[$subjectId] = [
                    'question_count' => count($ids),
                    'name' => $info['name'],
                    'selection' => $info['selection'],
                ];
            }

            $exam = Exam::create([
                'name' => $testName,
                'type' => 1,
                'exam_code' => 'PS-'.Auth::id().'-'.strtoupper(substr(uniqid(), -6)),
                'question_paper_id' => $paper->id,
                'no_of_question' => $totalCount,
                'totals_marks_for_exam' => $totalCount * $marksPerQ,
                'total_time_for_exam' => $duration,
                'marks_per_question' => $marksPerQ,
                'time_per_question' => max(1, (int) floor(($duration * 60) / max(1, $totalCount))),
                'negative_marking_applicable' => 1,
                'negative_marking_per_question' => 1,
                'exam_date' => optional($schedule->test_date)->toDateString() ?: now()->toDateString(),
                'exam_time' => now()->format('H:i:s'),
                'status' => 1,
                'is_deleted' => 0,
                'is_proctored' => 0,
            ]);

            DB::table('exam_assignments')->insert([
                'exam_id' => $exam->id,
                'user_id' => Auth::id(),
            ]);

            $primarySubjectId = (int) array_key_first($planMeta);

            return CustomTest::create([
                'user_id' => Auth::id(),
                'subject_id' => $primarySubjectId ?: null,
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
                    'source' => 'package_series',
                    'premium_only' => true,
                    'package_code' => $package->code,
                    'package_id' => $package->id,
                    'schedule_id' => $schedule->id,
                    'test_type' => $schedule->test_type,
                    'plan' => $planMeta,
                    'subject_names' => array_values(array_map(fn ($p) => $p['name'], $planMeta)),
                ],
            ]);
        });

        return redirect()->route('custom_test.ready', $custom->id)
            ->with('success', 'Online series test ready. Start when you are prepared.');
    }

    /**
     * Build per-subject premium question picks from schedule syllabus text.
     *
     * @return array{plan: array<int, array{name:string,ids:array,selection:array}>, question_ids: int[]}
     */
    private function pickQuestionsForSchedule(CourseSchedule $schedule): array
    {
        $buckets = $this->resolveSyllabusBuckets($schedule->subjects ?? []);
        $targets = [
            'physics' => self::TARGET_PHYSICS,
            'chemistry' => self::TARGET_CHEMISTRY,
            'biology' => self::TARGET_BIOLOGY,
            'botany' => (int) floor(self::TARGET_BIOLOGY / 2),
            'zoology' => (int) ceil(self::TARGET_BIOLOGY / 2),
        ];

        $plan = [];
        $allIds = [];
        $used = [];

        foreach ($buckets as $key => $bucket) {
            $subject = $bucket['subject'];
            if (!$subject) {
                continue;
            }
            $target = $targets[$key] ?? 45;
            $ids = $this->pickPremiumForSubject(
                (int) $subject->id,
                $bucket['chapter_ids'],
                $bucket['topic_ids'],
                $target,
                $used
            );
            foreach ($ids as $id) {
                $used[$id] = true;
                $allIds[] = $id;
            }
            if (!empty($ids)) {
                $selection = [];
                foreach ($bucket['chapter_ids'] as $chId) {
                    $selection[] = [
                        'chapter_id' => $chId,
                        'topic_ids' => $bucket['topic_ids'],
                        'sub_topic_ids' => [],
                    ];
                }
                $plan[(int) $subject->id] = [
                    'name' => $subject->name,
                    'ids' => $ids,
                    'selection' => $selection,
                ];
            }
        }

        // If biology was split poorly and we got almost nothing, fill from all PCB premium.
        if (count($allIds) < self::MIN_QUESTIONS_TO_START) {
            $fallback = $this->pickPremiumFallback(180 - count($allIds), $used);
            foreach ($fallback as $subjectId => $info) {
                if (isset($plan[$subjectId])) {
                    $plan[$subjectId]['ids'] = array_values(array_unique(array_merge($plan[$subjectId]['ids'], $info['ids'])));
                } else {
                    $plan[$subjectId] = $info;
                }
                foreach ($info['ids'] as $id) {
                    $allIds[] = $id;
                    $used[$id] = true;
                }
            }
            $allIds = array_values(array_unique($allIds));
        }

        return ['plan' => $plan, 'question_ids' => $allIds];
    }

    private function resolveSyllabusBuckets(array $rows): array
    {
        $subjects = Subject::where('status', 1)
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->get()
            ->keyBy(fn ($s) => strtolower(trim($s->name)));

        $buckets = [
            'physics' => ['subject' => $subjects->get('physics'), 'chapter_ids' => [], 'topic_ids' => [], 'raw' => []],
            'chemistry' => ['subject' => $subjects->get('chemistry'), 'chapter_ids' => [], 'topic_ids' => [], 'raw' => []],
            'biology' => ['subject' => $subjects->get('biology'), 'chapter_ids' => [], 'topic_ids' => [], 'raw' => []],
            'botany' => ['subject' => $subjects->get('botany'), 'chapter_ids' => [], 'topic_ids' => [], 'raw' => []],
            'zoology' => ['subject' => $subjects->get('zoology'), 'chapter_ids' => [], 'topic_ids' => [], 'raw' => []],
        ];

        foreach ($rows as $row) {
            $subLabel = trim((string) ($row['subject'] ?? ''));
            $topicsRaw = trim((string) ($row['topics'] ?? ''));
            $key = null;
            $topicText = $topicsRaw;

            if ($subLabel !== '') {
                $key = strtolower($subLabel);
            }

            // "Botany : Cell..." or "Zoology : Animal Kingdom" often live under empty subject.
            if (preg_match('/^\s*(botany|zoology)\s*[:\-]\s*(.+)$/i', $topicsRaw, $m)) {
                $key = strtolower($m[1]);
                $topicText = trim($m[2]);
            } elseif ($key === 'biology' && preg_match('/^\s*(botany|zoology)\s*[:\-]\s*(.+)$/i', $topicsRaw, $m)) {
                $key = strtolower($m[1]);
                $topicText = trim($m[2]);
            }

            if (!$key || !isset($buckets[$key]) || !$buckets[$key]['subject']) {
                // Map unknown biology-ish text onto biology subject
                if ($topicsRaw !== '' && $buckets['biology']['subject']) {
                    $key = 'biology';
                    $topicText = $topicsRaw;
                } else {
                    continue;
                }
            }

            $buckets[$key]['raw'][] = $topicText;
            $matched = $this->matchChaptersAndTopics((int) $buckets[$key]['subject']->id, $topicText);
            $buckets[$key]['chapter_ids'] = array_values(array_unique(array_merge($buckets[$key]['chapter_ids'], $matched['chapter_ids'])));
            $buckets[$key]['topic_ids'] = array_values(array_unique(array_merge($buckets[$key]['topic_ids'], $matched['topic_ids'])));
        }

        // Prefer botany/zoology when present; drop empty biology bucket if B/Z filled.
        if ((!empty($buckets['botany']['raw']) || !empty($buckets['zoology']['raw'])) && empty($buckets['biology']['raw'])) {
            unset($buckets['biology']);
        }

        return array_filter($buckets, fn ($b) => !empty($b['raw']) || !empty($b['chapter_ids']));
    }

    private function matchChaptersAndTopics(int $subjectId, string $topicText): array
    {
        $parts = preg_split('/\s*,\s*/', $topicText) ?: [];
        $chapterIds = [];
        $topicIds = [];

        $chapters = Chapter::where('subject_id', $subjectId)->where('status', 1)->get(['id', 'name']);
        $topics = Topic::where('subject_id', $subjectId)->where('status', 1)->get(['id', 'name', 'chapter_id']);

        foreach ($parts as $part) {
            $needle = Str::lower(trim($part));
            if ($needle === '') {
                continue;
            }
            $needle = preg_replace('/\s+/', ' ', $needle);

            foreach ($topics as $t) {
                $name = Str::lower(trim($t->name));
                if ($name === $needle || Str::contains($name, $needle) || Str::contains($needle, $name)) {
                    $topicIds[] = (int) $t->id;
                    if ($t->chapter_id) {
                        $chapterIds[] = (int) $t->chapter_id;
                    }
                }
            }
            foreach ($chapters as $c) {
                $name = Str::lower(trim($c->name));
                if ($name === $needle || Str::contains($name, $needle) || Str::contains($needle, $name)) {
                    $chapterIds[] = (int) $c->id;
                }
            }
        }

        return [
            'chapter_ids' => array_values(array_unique($chapterIds)),
            'topic_ids' => array_values(array_unique($topicIds)),
        ];
    }

    private function pickPremiumForSubject(
        int $subjectId,
        array $chapterIds,
        array $topicIds,
        int $limit,
        array $used
    ): array {
        $base = Question::query()
            ->where('subject_id', $subjectId)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->premiumOnly();

        if (!empty($used)) {
            $base->whereNotIn('id', array_keys($used));
        }

        $scoped = (clone $base);
        if (!empty($topicIds)) {
            $scoped->whereIn('topic_id', $topicIds);
        } elseif (!empty($chapterIds)) {
            $scoped->whereIn('chapter_id', $chapterIds);
        }

        $ids = $scoped->inRandomOrder()->limit($limit)->pluck('id')->map(fn ($id) => (int) $id)->all();

        if (count($ids) < $limit) {
            $need = $limit - count($ids);
            $extra = (clone $base)
                ->when(!empty($ids), fn ($q) => $q->whereNotIn('id', $ids))
                ->inRandomOrder()
                ->limit($need)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $ids = array_values(array_unique(array_merge($ids, $extra)));
        }

        return $ids;
    }

    private function pickPremiumFallback(int $limit, array $used): array
    {
        if ($limit < 1) {
            return [];
        }

        $subjects = Subject::where('status', 1)
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->get()
            ->filter(fn ($s) => in_array(strtolower(trim($s->name)), ['physics', 'chemistry', 'biology', 'botany', 'zoology'], true))
            ->values();

        $per = max(1, (int) ceil($limit / max(1, $subjects->count())));
        $plan = [];

        foreach ($subjects as $subject) {
            $ids = $this->pickPremiumForSubject((int) $subject->id, [], [], $per, $used);
            foreach ($ids as $id) {
                $used[$id] = true;
            }
            if (!empty($ids)) {
                $plan[(int) $subject->id] = [
                    'name' => $subject->name,
                    'ids' => $ids,
                    'selection' => [],
                ];
            }
        }

        return $plan;
    }
}
