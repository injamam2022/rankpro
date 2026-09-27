<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['batch_id' => ['nullable', 'integer', 'exists:batches,id']]);
        return view('admin.exam_assignments.index', [
            'selectedBatchId' => $request->input('batch_id'),
            'batches' => Batch::withCount('students')->orderBy('name')->get(),
            'students' => User::orderBy('first_name')->get(),
            'exams' => Exam::where('is_deleted', 0)->orderBy('name')->get(),
            'exam' => $request->filled('exam_id') ? Exam::where('is_deleted', 0)->with(['batches', 'assignedStudents'])->findOrFail($request->exam_id) : null,
        ]);
    }

    public function batches(Request $request)
    {
        return view('admin.exam_assignments.batches', [
            'batches' => Batch::withCount('students')->orderBy('name')->get(),
            'students' => User::orderBy('first_name')->get(),
            'batch' => $request->filled('batch_id') ? Batch::with('students')->findOrFail($request->batch_id) : null,
        ]);
    }

    public function saveBatch(Request $request)
    {
        $data = $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('batches')->ignore($request->input('batch_id'))],
            'students' => ['array'],
            'students.*' => ['integer', 'distinct', 'exists:users,id'],
        ]);
        $batch = DB::transaction(function () use ($data) {
            $batch = !empty($data['batch_id']) ? Batch::findOrFail($data['batch_id']) : new Batch();
            $batch->fill(['name' => $data['name']])->save();
            $batch->students()->sync($data['students'] ?? []);
            return $batch;
        });
        return redirect()->route('admin.batches', ['batch_id' => $batch->id])->with('success', 'Batch saved.');
    }

    public function saveAssignments(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', Rule::exists('exams', 'id')->where('is_deleted', 0)],
            'batches' => ['array'],
            'batches.*' => ['nullable', 'integer', 'distinct', 'exists:batches,id'],
            'students' => ['array'],
            'students.*' => ['integer', 'distinct', 'exists:users,id'],
        ]);
        DB::transaction(function () use ($data) {
            $exam = Exam::findOrFail($data['exam_id']);
            $exam->batches()->sync(array_filter($data['batches'] ?? []));
            $exam->assignedStudents()->sync($data['students'] ?? []);
        });
        return redirect()->route('admin.exam_assignments', ['exam_id' => $data['exam_id']])->with('success', 'Test assignments saved.');
    }
}
