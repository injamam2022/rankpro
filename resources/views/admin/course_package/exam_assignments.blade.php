@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4 align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-header mb-0">Assign Series Exam Papers</h1>
            <p class="text-muted mb-0 mt-1" style="font-size:13px;">
                Make a paper in Question Bank, then assign it here to Rank Builder / Rank Champion / Mission 720 schedule tests.
                Students who purchased that package will see it under Series Tests.
            </p>
        </div>
        <a href="{{ route('admin.question_paper.add') }}" class="rp-act rp-act-add">
            <i class="material-icons">add</i>
            Make paper
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Assign paper via dropdown</h5>
            <form action="{{ route('admin.course_package.exam_assignments.save') }}" method="post" id="seriesAssignForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Package <span class="text-danger">*</span></label>
                        <select name="course_package_id" id="assign_package_id" class="form-control" required>
                            <option value="">Select package</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" @selected(old('course_package_id') == $pkg->id)>{{ $pkg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Scheduled test <span class="text-danger">*</span></label>
                        <select name="course_schedule_id" id="assign_schedule_id" class="form-control" required>
                            <option value="">Select package first</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Question paper <span class="text-danger">*</span></label>
                        <select name="question_paper_id" id="assign_paper_id" class="form-control" required>
                            <option value="">Select paper</option>
                            @foreach($papers as $paper)
                                <option value="{{ $paper->id }}" @selected(old('question_paper_id') == $paper->id)>
                                    {{ $paper->name }} ({{ $paper->no_of_question }} Q · {{ $paper->total_time_for_exam }} min)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Assign paper</button>
                </div>
            </form>
        </div>
    </div>

    <table id="datatablesSimple" class="table table-striped">
        <thead>
            <tr>
                <th>Package</th>
                <th>Scheduled test</th>
                <th>Date</th>
                <th>Paper</th>
                <th>Questions</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($list as $row)
                <tr>
                    <td>{{ $row->package->name ?? '—' }}</td>
                    <td>
                        {{ $row->schedule->test_type ?? '—' }}
                        @if($row->schedule)
                            <div class="small text-muted">{{ ucfirst($row->schedule->section) }} syllabus</div>
                        @endif
                    </td>
                    <td>{{ optional($row->schedule->test_date ?? null)->format('d M Y') ?: '—' }}</td>
                    <td>
                        {{ $row->questionPaper->name ?? '—' }}
                        @if($row->question_paper_id)
                            <div>
                                <a href="{{ route('admin.question_paper.view', ['id' => $row->question_paper_id]) }}" class="small">View paper</a>
                            </div>
                        @endif
                    </td>
                    <td>{{ $row->questionPaper->no_of_question ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.course_package.exam_assignments.change', ['id' => $row->id]) }}" onclick="return confirm('Change status?');">
                            @if($row->status)
                                <span class="btn btn-success btn-sm">Active</span>
                            @else
                                <span class="btn btn-danger btn-sm">Inactive</span>
                            @endif
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.course_package.exam_assignments.remove', ['id' => $row->id]) }}"
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('Remove this assignment?');">Remove</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted">No papers assigned yet. Create a paper, then use the dropdowns above.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('js_after')
<script>
(function () {
    var packageEl = document.getElementById('assign_package_id');
    var scheduleEl = document.getElementById('assign_schedule_id');
    var schedulesUrl = @json(route('admin.course_package.exam_assignments.schedules'));
    var oldScheduleId = @json(old('course_schedule_id'));

    function loadSchedules(packageId, selectedId) {
        scheduleEl.innerHTML = '<option value="">Loading…</option>';
        if (!packageId) {
            scheduleEl.innerHTML = '<option value="">Select package first</option>';
            return;
        }
        fetch(schedulesUrl + '?package_id=' + encodeURIComponent(packageId), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var rows = data.schedules || [];
                if (!rows.length) {
                    scheduleEl.innerHTML = '<option value="">No schedules for this package</option>';
                    return;
                }
                scheduleEl.innerHTML = '<option value="">Select scheduled test</option>';
                rows.forEach(function (row) {
                    var opt = document.createElement('option');
                    opt.value = row.id;
                    opt.textContent = row.label;
                    if (String(selectedId) === String(row.id)) opt.selected = true;
                    scheduleEl.appendChild(opt);
                });
            })
            .catch(function () {
                scheduleEl.innerHTML = '<option value="">Failed to load schedules</option>';
            });
    }

    packageEl.addEventListener('change', function () {
        loadSchedules(this.value, null);
    });

    if (packageEl.value) {
        loadSchedules(packageEl.value, oldScheduleId);
    }
})();
</script>
@endsection
