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
                $label = $date.' — '.trim(($s->test_type ?: 'Test').($s->section ? ' ('.ucfirst($s->section).')' : ''));

                return [
                    'id' => $s->id,
                    'label' => $label,
                    'section' => $s->section,
                    'test_type' => $s->test_type,
                    'test_date' => optional($s->test_date)->toDateString(),
                    'suggested_from' => optional($s->test_date)->format('Y-m-d'),
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
            'available_from' => ['nullable', 'date'],
        ]);

        $package = CoursePackage::whereIn('code', CoursePackageAccess::seriesPackageCodes())
            ->findOrFail($data['course_package_id']);

        $allowedIds = CoursePackageAccess::schedulesQueryForPackage($package)->pluck('id')->all();
        if (!in_array((int) $data['course_schedule_id'], array_map('intval', $allowedIds), true)) {
            toastr()->error('Selected schedule does not belong to this package.');
            return back()->withInput();
        }

        $schedule = CourseSchedule::findOrFail($data['course_schedule_id']);
        $from = $data['available_from'] ?: optional($schedule->test_date)->toDateString();
        if (!$from) {
            toastr()->error('This scheduled test has no exam date. Set the date on the test schedule first.');
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
                'available_from' => Carbon::parse($from)->startOfDay(),
                'available_until' => null,
                'status' => 1,
            ]
        );

        toastr()->success('Paper assigned. Purchasers can attempt it from '.$schedule->test_date?->format('d M Y').'.');

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
}
