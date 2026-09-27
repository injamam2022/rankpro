@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <h1 class="page-header mt-5 mb-4">Test Assignments</h1>
    <p>Students can access tests assigned directly to them or to one of their batches. New tests have no assignments.</p>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <p><a href="{{ route('admin.batches') }}">Manage Batch Master</a></p>
    <div class="row">
        <div class="col-lg-6 mb-5">
            <h2>Assign a test</h2>
            <form method="get" action="{{ route('admin.exam_assignments') }}" class="mb-4">
                <label for="exam-picker">Test</label>
                <select id="exam-picker" class="form-control" name="exam_id" required>
                    <option value="">Select a test</option>
                    @foreach($exams as $item)<option value="{{ $item->id }}" @selected(optional($exam)->id == $item->id)>{{ $item->name }} — {{ $item->exam_code }} ({{ $item->type == 1 ? 'Online' : 'Offline' }})</option>@endforeach
                </select>
                <label for="batch-picker" class="mt-3">Batch Master</label>
                <select id="batch-picker" class="form-control" name="batch_id">
                    <option value="">Select a batch (optional)</option>
                    @foreach($batches as $item)
                        <option value="{{ $item->id }}" @selected($selectedBatchId == $item->id)>{{ $item->name }} ({{ $item->students_count }} students)</option>
                    @endforeach
                </select>
                <p class="small">Choose a batch to add to this test, then review and save the assignments.</p>
                @if($batches->isEmpty())<p><a href="{{ route('admin.batches') }}">Create a batch in Batch Master</a> to populate this dropdown.</p>@endif
                <button class="btn btn-secondary mt-2">Manage assignments</button>
            </form>
            @if($exam)
                <h3>{{ $exam->name }}</h3>
                <form method="post" action="{{ route('admin.exam_assignments.save') }}">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                    <div id="assigned-batches-label">Assign to batch</div>
                    <div id="batch-dropdowns" role="group" aria-labelledby="assigned-batches-label">
                        @foreach((old('exam_id') ? old('batches', []) : array_unique(array_merge($exam->batches->modelKeys(), $selectedBatchId ? [$selectedBatchId] : []))) ?: [''] as $batchId)
                            <div class="d-flex gap-2 mb-3">
                                <select class="form-control" name="batches[]" aria-label="Batch">
                                    <option value="">Select a batch (optional)</option>
                                    @foreach($batches as $item)<option value="{{ $item->id }}" @selected($batchId == $item->id)>{{ $item->name }} ({{ $item->students_count }} students)</option>@endforeach
                                </select>
                                <button class="btn btn-outline-secondary" type="button" onclick="this.parentElement.remove()">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <template id="batch-dropdown-template">
                        <div class="d-flex gap-2 mb-3">
                            <select class="form-control" name="batches[]" aria-label="Batch">
                                <option value="">Select a batch (optional)</option>
                                @foreach($batches as $item)<option value="{{ $item->id }}">{{ $item->name }} ({{ $item->students_count }} students)</option>@endforeach
                            </select>
                            <button class="btn btn-outline-secondary" type="button" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    </template>
                    <button class="btn btn-secondary mb-3" type="button" onclick="document.getElementById('batch-dropdowns').appendChild(document.getElementById('batch-dropdown-template').content.cloneNode(true))">Add another batch</button>
                    @if($batches->isEmpty())<p>Create a batch in <a href="{{ route('admin.batches') }}">Batch Master</a> first.</p>@endif
                    <div id="assigned-students-label">Assign to individual students</div>
                    <div class="border rounded p-3" role="group" aria-labelledby="assigned-students-label" style="max-height: 300px; overflow-y: auto;">
                        @forelse($students as $student)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="assigned-student-{{ $student->id }}" name="students[]" value="{{ $student->id }}" @checked(in_array($student->id, old('exam_id') ? old('students', []) : $exam->assignedStudents->modelKeys()))>
                                <label class="form-check-label" for="assigned-student-{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} — {{ $student->email_id }} (#{{ $student->id }})</label>
                            </div>
                        @empty
                            <p class="mb-0">No students available.</p>
                        @endforelse
                    </div>
                    <p class="small">Check the students to assign. Saving replaces this test's assignments. Deselect all batches and uncheck all students to remove all access.</p>
                    <button class="btn btn-primary">Save assignments</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
