@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <h1 class="page-header mt-5 mb-4">Batch Master</h1>
    <p>Create batches and check the students who belong to each batch. <a href="{{ route('admin.exam_assignments') }}">Assign tests to a batch</a>.</p>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="row">
        <div class="col-lg-6 mb-5">
            <h2>{{ $batch ? 'Edit batch' : 'Create batch' }}</h2>
            <form method="get" action="{{ route('admin.batches') }}" class="mb-4">
                <label for="batch-picker">Existing batch</label>
                <select id="batch-picker" name="batch_id" class="form-control">
                    <option value="">Create a new batch</option>
                    @foreach($batches as $item)<option value="{{ $item->id }}" @selected(optional($batch)->id == $item->id)>{{ $item->name }} ({{ $item->students_count }} students)</option>@endforeach
                </select>
                <button class="btn btn-secondary mt-2">Open batch</button>
            </form>
            <form method="post" action="{{ route('admin.batches.save') }}">
                @csrf
                <input type="hidden" name="batch_id" value="{{ optional($batch)->id }}">
                <label for="batch-name">Batch name</label>
                <input id="batch-name" class="form-control mb-3" name="name" required maxlength="255" value="{{ old('name', optional($batch)->name) }}">
                <div id="batch-students-label">Students in this batch</div>
                <div class="border rounded p-3" role="group" aria-labelledby="batch-students-label" style="max-height: 300px; overflow-y: auto;">
                    @forelse($students as $student)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="batch-student-{{ $student->id }}" name="students[]" value="{{ $student->id }}" @checked(in_array($student->id, old('name') !== null ? old('students', []) : ($batch ? $batch->students->modelKeys() : [])))>
                            <label class="form-check-label" for="batch-student-{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} — {{ $student->email_id }} (#{{ $student->id }})</label>
                        </div>
                    @empty
                        <p class="mb-0">No students available.</p>
                    @endforelse
                </div>
                <p class="small">Check the students to include. Batch membership changes apply to all tests assigned to this batch.</p>
                <button class="btn btn-primary">Save batch</button>
            </form>
        </div>
    </div>
</div>
@endsection
