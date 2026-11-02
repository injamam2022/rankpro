<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePackage;
use App\Models\CoursePackageExamAssignment;
use App\Models\CourseSchedule;
use App\Models\CustomTest;
use App\Models\Question_paper;
use App\Support\CoursePackageAccess;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CoursePackageExamAssignmentController extends Controller
{
    public function list()
    {
        $data['list'] = CoursePackageExamAssignment::with(['package', 'schedule', 'questionPaper'])
            ->orderByDesc('id')
            ->get();

        $data['packages'] = CoursePackage::query()
            ->whereIn('code', CoursePackageAccess::seriesPackageCodes())
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        $data['papers'] = Question_paper::query()
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->whereNotIn('id', CustomTest::questionPaperIdQuery())
            ->orderByDesc('id')
            ->get(['id', 'name', 'no_of_question', 'total_time_for_exam']);

        return view('admin.course_package.exam_assignments', $data);
    }

    public function schedules(Request $request)
    {
        $package = CoursePackage::whereIn('code', CoursePackageAccess::seriesPackageCodes())
            ->findOrFail($request->package_id);

        $schedules = CoursePackageAccess::schedulesQueryForPackage($package)
            ->get()
            ->map(function (CourseSchedule $s) {
                $date = optional($s->test_date)->format('d M Y') ?: 'No date';
                $label = trim(($s->test_type ?: 'Test').' · '.$date.($s->section ? ' · '.ucfirst($s->section) : ''));

                $from = null;
                if ($s->test_date) {
                    $time = $this->parseScheduleStartTime($s->timing);
                    $from = Carbon::parse($s->test_date->format('Y-m-d').' '.$time)->format('Y-m-d\TH:i');
                }

                $until = null;
                if ($from) {
                    $until = Carbon::parse(str_replace('T', ' ', $from))->addDay()->format('Y-m-d\TH:i');
                }

                return [
                    'id' => $s->id,
                    'label' => $label,
                    'section' => $s->section,
                    'test_type' => $s->test_type,
                    'test_date' => optional($s->test_date)->toDateString(),
                    'timing' => $s->timing,
                    'suggested_from' => $from,
                    'suggested_until' => $until,
                ];
            })
            ->values();

        return response()->json(['schedules' => $schedules]);
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'course_package_id' => ['required', 'integer', 'exists:course_packages,id'],
            'course_schedule_id' => ['required', 'integer', 'exists:course_schedules,id'],
            'question_paper_id' => ['required', 'integer', 'exists:question_papers,id'],
            'available_from' => ['required', 'date'],
            'available_until' => ['required', 'date', 'after:available_from'],
        ], [
            'available_from.required' => 'Set when the exam becomes available to purchasers.',
            'available_until.required' => 'Set until when the exam stays available.',
            'available_until.after' => 'Available until must be after available from.',
        ]);

        $package = CoursePackage::whereIn('code', CoursePackageAccess::seriesPackageCodes())
            ->findOrFail($data['course_package_id']);

        $allowedIds = CoursePackageAccess::schedulesQueryForPackage($package)->pluck('id')->all();
        if (!in_array((int) $data['course_schedule_id'], array_map('intval', $allowedIds), true)) {
            toastr()->error('Selected schedule does not belong to this package.');
            return back()->withInput();
        }

        $paper = Question_paper::where('id', $data['question_paper_id'])
            ->where('is_deleted', 0)
            ->first();
        if (!$paper) {
            toastr()->error('Question paper not found.');
            return back()->withInput();
        }

        CoursePackageExamAssignment::updateOrCreate(
            [
                'course_package_id' => $data['course_package_id'],
                'course_schedule_id' => $data['course_schedule_id'],
            ],
            [
                'question_paper_id' => $data['question_paper_id'],
                'available_from' => Carbon::parse($data['available_from']),
                'available_until' => Carbon::parse($data['available_until']),
                'status' => 1,
            ]
        );

        toastr()->success('Paper assigned with availability dates. Purchasers can attempt only inside that window.');

        if ($request->filled('redirect_to_paper')) {
            return redirect()->route('admin.question_paper.view', ['id' => $data['question_paper_id']]);
        }

        return redirect()->route('admin.course_package.exam_assignments');
    }

    public function remove(Request $request)
    {
        $row = CoursePackageExamAssignment::findOrFail($request->id);
        $row->delete();
        toastr()->success('Assignment removed.');
        return redirect()->route('admin.course_package.exam_assignments');
    }

    public function change(Request $request)
    {
        $row = CoursePackageExamAssignment::findOrFail($request->id);
        $row->update(['status' => $row->status ? 0 : 1]);
        toastr()->success('Assignment status updated.');
        return redirect()->route('admin.course_package.exam_assignments');
    }

    private function parseScheduleStartTime(?string $timing): string
    {
        $timing = trim((string) $timing);
        if ($timing === '') {
            return '00:00:00';
        }

        // Examples: "2:00 PM - 5:00 PM", "14:00-17:00", "2 PM"
        if (preg_match('/(\d{1,2}(:\d{2})?\s*(AM|PM))/i', $timing, $m)) {
            try {
                return Carbon::parse($m[1])->format('H:i:s');
            } catch (\Throwable $e) {
                // fall through
            }
        }

        if (preg_match('/(\d{1,2}:\d{2})/', $timing, $m)) {
            return strlen($m[1]) === 5 ? $m[1].':00' : $m[1];
        }

        return '00:00:00';
    }
}
