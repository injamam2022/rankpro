<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\User;
use App\Models\CustomTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'exam_id' => ['nullable', 'integer', 'exists:exams,id'],
        ]);

        $batches = Batch::with(['students' => function ($query) {
            $query->select('users.id');
        }])->withCount('students')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $exams = Exam::query()
            ->where('is_deleted', 0)
            ->where(function ($q) {
                $q->whereNull('exam_code')->orWhere('exam_code', 'not like', 'CT-%');
            })
            ->whereNotIn('id', CustomTest::examIdQuery())
            ->with([
                'batches' => function ($query) {
                    $query->with(['students' => function ($studentQuery) {
                        $studentQuery->select('users.id');
                    }])->withCount('students');
                },
                'assignedStudents' => function ($query) {
                    $query->select('users.id');
                },
            ])
            ->withCount(['batches', 'assignedStudents'])
            ->orderBy('name')
            ->get()
            ->each(function (Exam $exam) {
                $exam->unique_students_count = $exam->batches
                    ->filter(function (Batch $batch) {
                        return (int) $batch->status === 1;
                    })
                    ->flatMap(function (Batch $batch) {
                        return $batch->students->pluck('id');
                    })
                    ->merge($exam->assignedStudents->pluck('id'))
                    ->unique()
                    ->count();
            });

        $exam = null;
        if ($request->filled('exam_id')) {
            $exam = Exam::where('is_deleted', 0)
                ->where(function ($q) {
                    $q->whereNull('exam_code')->orWhere('exam_code', 'not like', 'CT-%');
                })
                ->whereNotIn('id', CustomTest::examIdQuery())
                ->with(['batches', 'assignedStudents'])
                ->findOrFail($request->exam_id);
        }

        return view('admin.exam_assignments.index', [
            'selectedBatchId' => $request->input('batch_id'),
            'batches' => $batches,
            'students' => $this->activeStudents(),
            'exams' => $exams,
            'exam' => $exam,
            'assignmentOverview' => $exams->filter(function ($item) {
                return $item->batches_count > 0 || $item->assigned_students_count > 0;
            })->values(),
        ]);
    }

    public function batches(Request $request)
    {
        $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
        ]);

        return view('admin.exam_assignments.batches', [
            'batches' => Batch::withCount(['students', 'exams'])->orderBy('name')->get(),
            'students' => $this->activeStudents(),
            'batch' => $request->filled('batch_id')
                ? Batch::with(['students', 'exams'])->findOrFail($request->batch_id)
                : null,
        ]);
    }

    public function saveBatch(Request $request)
    {
        $data = $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('batches', 'name')->ignore($request->input('batch_id'))],
            'status' => ['required', 'in:0,1'],
            'students' => ['nullable', 'array'],
            'students.*' => ['integer', 'distinct', 'exists:users,id'],
        ]);

        $batch = DB::transaction(function () use ($data) {
            $batch = !empty($data['batch_id']) ? Batch::findOrFail($data['batch_id']) : new Batch();
            $batch->fill([
                'name' => $data['name'],
                'status' => (int) $data['status'],
            ])->save();
            $batch->students()->sync($data['students'] ?? []);
            return $batch;
        });

        return redirect()
            ->route('admin.batches', ['batch_id' => $batch->id])
            ->with('success', 'Batch saved successfully.');
    }

    public function deleteBatch(Request $request)
    {
        $data = $request->validate([
            'batch_id' => ['required', 'integer', 'exists:batches,id'],
        ]);

        DB::transaction(function () use ($data) {
            $batch = Batch::findOrFail($data['batch_id']);
            $batch->students()->detach();
            $batch->exams()->detach();
            $batch->delete();
        });

        return redirect()
            ->route('admin.batches')
            ->with('success', 'Batch deleted. Related test assignments for that batch were removed.');
    }

    public function saveAssignments(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', Rule::exists('exams', 'id')->where('is_deleted', 0)],
            'exam_date' => ['required', 'date'],
            'exam_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'exam_end_date' => ['required', 'date'],
            'exam_end_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'batches' => ['nullable', 'array'],
            'batches.*' => ['nullable', 'integer', 'distinct', Rule::exists('batches', 'id')->where('status', 1)],
            'students' => ['nullable', 'array'],
            'students.*' => ['integer', 'distinct', 'exists:users,id'],
        ]);

        $startAt = Carbon::parse($data['exam_date'].' '.$data['exam_time']);
        $endAt = Carbon::parse($data['exam_end_date'].' '.$data['exam_end_time']);
        if ($endAt->lte($startAt)) {
            return back()
                ->withErrors(['exam_end_date' => 'End date and time must be after the start date and time.'])
                ->withInput();
        }

        $batchIds = array_values(array_unique(array_filter($data['batches'] ?? [])));
        $studentIds = array_values(array_unique($data['students'] ?? []));

        $batchMemberIds = empty($batchIds)
            ? collect()
            : DB::table('batch_user')->whereIn('batch_id', $batchIds)->pluck('user_id')->map(fn ($id) => (int) $id)->unique();

        $overlapIds = array_values(array_intersect($studentIds, $batchMemberIds->all()));
        $studentIds = array_values(array_diff($studentIds, $batchMemberIds->all()));

        DB::transaction(function () use ($data, $batchIds, $studentIds, $startAt, $endAt) {
            $exam = Exam::findOrFail($data['exam_id']);
            $exam->exam_date = $startAt->toDateString();
            $exam->exam_time = $startAt->format('H:i:s');
            $exam->exam_end_date = $endAt->toDateString();
            $exam->exam_end_time = $endAt->format('H:i:s');
            $exam->save();
            $exam->batches()->sync($batchIds);
            $exam->assignedStudents()->sync($studentIds);
        });

        $redirect = redirect()->route('admin.exam_assignments', ['exam_id' => $data['exam_id']]);
        $warnings = [];

        if ($endAt->lt(now())) {
            $warnings[] = 'This end date and time is already past, so students will not see the test in the portal.';
        }

        if (!empty($overlapIds)) {
            $names = User::whereIn('id', $overlapIds)
                ->orderBy('first_name')
                ->get(['first_name', 'last_name', 'email_id'])
                ->map(fn ($u) => trim($u->first_name.' '.$u->last_name) ?: $u->email_id)
                ->implode(', ');

            $warnings[] = count($overlapIds).' student(s) were not added as individual assignees because they are already covered by a selected batch: '.$names.'. They still have access through their batch and are counted only once.';
        }

        $redirect = $redirect->with('success', 'Test assignments saved. Assigned students receive this test in the portal from the start time until the end time.');

        if (!empty($warnings)) {
            $redirect = $redirect->with('warning', implode(' ', $warnings));
        }

        return $redirect;
    }

    private function activeStudents()
    {
        return User::query()
            ->where('status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'email_id', 'rankpro_id', 'mobile_number']);
    }
}
