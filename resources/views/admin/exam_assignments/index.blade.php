@extends('layouts.backend')

@section('css_after')
<style>
    .ta-page { max-width: 1120px; }
    .ta-header { margin-top: 2.5rem; margin-bottom: 1.5rem; }
    .ta-header h1 { font-size: 1.5rem; font-weight: 500; letter-spacing: -.02em; margin: 0 0 .35rem; }
    .ta-sub { color: #667085; font-size: .9rem; margin: 0; }
    .ta-link { color: #6200ea; text-decoration: none; font-weight: 400; font-size: .875rem; }
    .ta-link:hover { text-decoration: underline; }

    .ta-shell {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 1.25rem;
        align-items: start;
    }
    @media (max-width: 991px) {
        .ta-shell { grid-template-columns: 1fr; }
    }

    .ta-panel {
        background: #fff;
        border: 1px solid #eaecf0;
        border-radius: .75rem;
    }
    .ta-panel-pad { padding: 1.25rem 1.35rem; }
    .ta-side-title {
        font-size: .72rem; font-weight: 500; letter-spacing: .06em; text-transform: uppercase;
        color: #98a2b3; padding: .9rem 1rem .35rem;
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
    }

    .ta-label { display: block; font-size: .8125rem; font-weight: 400; color: #344054; margin-bottom: .4rem; }
    .ta-input, .ta-select {
        width: 100%; border: 1px solid #d0d5dd; border-radius: .5rem; padding: .6rem .75rem;
        font-size: .925rem; background: #fff; color: #101828;
    }
    .ta-input:focus, .ta-select:focus {
        outline: 0; border-color: #6200ea; box-shadow: 0 0 0 3px rgba(98,0,234,.12);
    }

    .ta-exam-meta {
        display: flex; flex-wrap: wrap; gap: .5rem .85rem; margin: .85rem 0 1.1rem;
        font-size: .8rem; color: #667085;
    }
    .ta-exam-meta strong { color: #101828; font-weight: 500; }

    .ta-section { margin-top: 1.15rem; }
    .ta-section-title {
        font-size: .8125rem; font-weight: 500; color: #101828; margin-bottom: .5rem;
    }
    .ta-collapse-head {
        display: flex; align-items: center; justify-content: space-between; gap: .75rem;
        width: 100%; border: 1px solid #eaecf0; background: #fcfcfd; border-radius: .55rem;
        padding: .55rem .75rem; cursor: pointer; margin-bottom: .55rem; text-align: left;
    }
    .ta-collapse-head:hover { background: #f9fafb; }
    .ta-collapse-head[aria-expanded="true"] {
        border-bottom-left-radius: 0; border-bottom-right-radius: 0; margin-bottom: 0;
        border-bottom-color: transparent;
    }
    .ta-collapse-title {
        display: flex; align-items: center; gap: .5rem; font-size: .8125rem; font-weight: 500; color: #101828;
    }
    .ta-collapse-meta { font-size: .78rem; font-weight: 400; color: #98a2b3; }
    .ta-collapse-meta strong { color: #6941c6; font-weight: 500; }
    .ta-collapse-icon {
        color: #98a2b3; font-size: 20px !important; line-height: 1; transition: transform .15s ease;
    }
    .ta-collapse-head[aria-expanded="true"] .ta-collapse-icon { transform: rotate(180deg); }
    .ta-collapse-body {
        border: 1px solid #eaecf0; border-top: 0; border-radius: 0 0 .55rem .55rem;
        padding: .7rem .75rem .75rem; background: #fff;
    }
    .ta-collapse-body[hidden] { display: none !important; }

    .ta-toolbar {
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: .5rem;
        margin-bottom: .65rem;
    }
    @media (max-width: 575px) {
        .ta-toolbar { grid-template-columns: 1fr 1fr; }
        .ta-toolbar .ta-search { grid-column: 1 / -1; }
    }
    .ta-ghost {
        border: 1px solid #d0d5dd; background: #fff; color: #344054; border-radius: .5rem;
        padding: .45rem .7rem; font-size: .8rem; font-weight: 400; white-space: nowrap; cursor: pointer;
    }
    .ta-ghost:hover { background: #f9fafb; }

    .ta-students {
        border: 1px solid #eaecf0; border-radius: .65rem; max-height: 300px; overflow: auto; background: #fcfcfd;
    }
    .ta-student {
        display: flex; align-items: flex-start; gap: .7rem; padding: .6rem .8rem;
        border-bottom: 1px solid #f2f4f7; margin: 0; cursor: pointer;
    }
    .ta-student:last-child { border-bottom: 0; }
    .ta-student:hover { background: #f8f5ff; }
    .ta-student.hidden { display: none !important; }
    .ta-student.student-covered { opacity: .65; background: #fffcf5; }
    .ta-student.student-covered .ta-student-meta::after {
        content: " · already in selected batch";
        color: #b54708;
    }
    .ta-student input { margin-top: .2rem; accent-color: #6200ea; width: 1rem; height: 1rem; flex-shrink: 0; }
    .ta-student-name { display: block; font-size: .9rem; font-weight: 400; color: #101828; line-height: 1.25; }
    .ta-student-meta { display: block; font-size: .78rem; color: #98a2b3; margin-top: .1rem; }

    .ta-footer {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: .75rem; margin-top: 1rem;
    }
    .ta-hint { font-size: .78rem; color: #98a2b3; max-width: 28rem; }
    .ta-btn {
        border: 0; border-radius: .5rem; padding: .55rem 1rem; font-size: .875rem; font-weight: 500; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center;
    }
    .ta-btn-primary { background: #6200ea; color: #fff; }
    .ta-btn-primary:hover { background: #5600cc; color: #fff; }

    .ta-empty { padding: 1rem; color: #98a2b3; font-size: .875rem; }
    .ta-alert { border-radius: .65rem; padding: .75rem 1rem; font-size: .875rem; margin-bottom: 1rem; border: 1px solid transparent; }
    .ta-alert-ok { background: #ecfdf3; border-color: #abefc6; color: #067647; }
    .ta-alert-warn { background: #fffaeb; border-color: #fedf89; color: #b54708; }
    .ta-alert-err { background: #fef3f2; border-color: #fecdca; color: #b42318; }

    .ta-assign-list { list-style: none; margin: 0; padding: 0 0 .35rem; }
    .ta-assign-item a {
        display: block; padding: .8rem 1rem; text-decoration: none; color: inherit;
        border-left: 3px solid transparent; border-bottom: 1px solid #f2f4f7;
    }
    .ta-assign-item:last-child a { border-bottom: 0; }
    .ta-assign-item a:hover { background: #f9fafb; }
    .ta-assign-item.active a { background: #f8f5ff; border-left-color: #6200ea; }
    .ta-assign-name { font-size: .92rem; font-weight: 500; color: #101828; }
    .ta-assign-meta { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .4rem; }
    .ta-pill {
        display: inline-flex; align-items: center; padding: .15rem .45rem; border-radius: 999px;
        background: #f4ebff; color: #6200ea; font-size: .72rem; font-weight: 500; line-height: 1.2;
    }
    .ta-pill-muted { background: #f2f4f7; color: #475467; }

    .ta-batch-list { list-style: none; margin: 0; padding: 0 0 .35rem; }
    .ta-batch-item {
        display: flex; align-items: center; justify-content: space-between; gap: .75rem;
        padding: .75rem 1rem; border-bottom: 1px solid #f2f4f7;
    }
    .ta-batch-item:last-child { border-bottom: 0; }
    .ta-batch-name { font-size: .9rem; font-weight: 500; color: #101828; }
    .ta-batch-stats {
        display: inline-flex; align-items: center; padding: .15rem .5rem; border-radius: 999px;
        background: #f4ebff; color: #6200ea; font-size: .72rem; font-weight: 500; white-space: nowrap;
    }

    .ta-side-tools { padding: 0 .75rem .55rem; }
    .ta-side-search {
        width: 100%; border: 1px solid #d0d5dd; border-radius: .45rem; padding: .45rem .65rem;
        font-size: .8rem; background: #fff; color: #101828;
    }
    .ta-side-search:focus {
        outline: 0; border-color: #6200ea; box-shadow: 0 0 0 3px rgba(98,0,234,.1);
    }
    .ta-side-pager {
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
        padding: .55rem .75rem .75rem; border-top: 1px solid #f2f4f7;
    }
    .ta-side-page-info { font-size: .72rem; color: #667085; font-weight: 500; }
    .ta-side-page-btns { display: inline-flex; gap: .35rem; }
    .ta-side-page-btn {
        border: 1px solid #d0d5dd; background: #fff; color: #344054; border-radius: .4rem;
        width: 28px; height: 28px; font-size: .9rem; line-height: 1; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .ta-side-page-btn:hover:not(:disabled) { background: #f8f5ff; border-color: #c4b5fd; color: #6200ea; }
    .ta-side-page-btn:disabled { opacity: .4; cursor: not-allowed; }
    .ta-side-empty { display: none; padding: .85rem 1rem 1rem; color: #98a2b3; font-size: .8rem; }
    .ta-side-item.is-filtered-out, .ta-side-item.is-paged-out { display: none !important; }

    .ms-dropdown { border: 1px solid #d0d5dd; border-radius: .55rem; background: #fff; }
    .ms-dropdown.open { border-color: #6200ea; box-shadow: 0 0 0 3px rgba(98,0,234,.12); }
    .ms-dropdown-toggle {
        width: 100%; min-height: 44px; display: flex; align-items: center; gap: .5rem;
        padding: .5rem .7rem; cursor: pointer; background: transparent; border: 0; text-align: left;
    }
    .ms-chips { display: flex; flex-wrap: wrap; gap: .35rem; flex: 1; min-width: 0; }
    .ms-chip {
        display: inline-flex; align-items: center; gap: .25rem; padding: .18rem .5rem;
        border-radius: 999px; background: #f4ebff; color: #6200ea; font-size: .8rem; font-weight: 500;
    }
    .ms-chip button {
        border: 0; background: transparent; color: #6941c6; line-height: 1; padding: 0; font-size: .95rem; cursor: pointer;
    }
    .ms-placeholder { color: #98a2b3; font-size: .9rem; }
    .ms-controls { display: inline-flex; align-items: center; gap: .25rem; margin-left: auto; flex-shrink: 0; }
    .ms-count {
        min-width: 1.4rem; height: 1.4rem; border-radius: 999px; background: #6200ea; color: #fff;
        font-size: .72rem; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; padding: 0 .35rem;
    }
    .ms-icon-btn {
        border: 0; background: transparent; color: #667085; width: 28px; height: 28px; border-radius: .35rem;
        display: inline-flex; align-items: center; justify-content: center; cursor: pointer;
    }
    .ms-icon-btn:hover { background: #f2f4f7; color: #101828; }
    .ms-panel { display: none; border-top: 1px solid #eaecf0; padding: .75rem; }
    .ms-dropdown.open .ms-panel { display: block; }
    .ms-search-wrap { position: relative; margin-bottom: .55rem; }
    .ms-search-wrap input {
        width: 100%; border: 1px solid #d0d5dd; border-radius: .45rem; padding: .5rem 2.1rem .5rem .7rem; font-size: .875rem;
    }
    .ms-search-wrap .material-icons {
        position: absolute; right: .55rem; top: 50%; transform: translateY(-50%); color: #98a2b3; font-size: 18px;
    }
    .ms-panel-actions { display: flex; gap: .45rem; margin-bottom: .55rem; }
    .ms-options { max-height: 200px; overflow: auto; }
    .ms-option {
        display: flex; align-items: center; gap: .6rem; padding: .4rem .3rem; border-radius: .35rem; cursor: pointer;
    }
    .ms-option:hover { background: #f8f5ff; }
    .ms-option.hidden { display: none !important; }
    .ms-option input { width: 1rem; height: 1rem; accent-color: #6200ea; cursor: pointer; }
    .ms-option span { color: #344054; font-size: .875rem; }

    /* Select2 - Select test dropdown */
    .ta-page .select2-container { width: 100% !important; }
    .ta-page .select2-container .select2-selection--single {
        height: 44px !important;
        border: 1px solid #d0d5dd !important;
        border-radius: .55rem !important;
        padding: .4rem .7rem !important;
        background: #fff !important;
        box-shadow: none !important;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .ta-page .select2-container--default.select2-container--open .select2-selection--single,
    .ta-page .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #6200ea !important;
        box-shadow: 0 0 0 3px rgba(98,0,234,.12) !important;
        outline: 0 !important;
    }
    .ta-page .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        color: #101828 !important;
        padding-left: 0 !important;
        font-size: .925rem !important;
        font-weight: 400;
    }
    .ta-page .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #98a2b3 !important;
        font-weight: 400;
    }
    .ta-page .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: .45rem !important;
        width: 24px !important;
    }
    .ta-page .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #667085 transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
        margin-left: -4px !important;
        margin-top: -2px !important;
    }
    .ta-page .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #667085 transparent !important;
        border-width: 0 4px 5px 4px !important;
        margin-top: -3px !important;
    }
    .select2-dropdown.ta-exam-dropdown {
        border: 1px solid #eaecf0 !important;
        border-radius: .65rem !important;
        box-shadow: 0 8px 24px rgba(16,24,40,.08) !important;
        overflow: hidden;
        margin-top: .35rem;
        background: #fff;
    }
    .select2-dropdown.ta-exam-dropdown .select2-search--dropdown {
        padding: .65rem .7rem .45rem !important;
        background: #fff;
    }
    .select2-dropdown.ta-exam-dropdown .select2-search--dropdown .select2-search__field {
        border: 1px solid #d0d5dd !important;
        border-radius: .45rem !important;
        padding: .5rem .7rem !important;
        font-size: .875rem !important;
        color: #101828 !important;
        outline: 0 !important;
        box-shadow: none !important;
    }
    .select2-dropdown.ta-exam-dropdown .select2-search--dropdown .select2-search__field:focus {
        border-color: #6200ea !important;
        box-shadow: 0 0 0 3px rgba(98,0,234,.1) !important;
    }
    .select2-dropdown.ta-exam-dropdown .select2-results {
        padding: .25rem .4rem .55rem;
    }
    .select2-dropdown.ta-exam-dropdown .select2-results__options {
        max-height: 260px;
    }
    .select2-dropdown.ta-exam-dropdown .select2-results__option {
        padding: .55rem .7rem !important;
        border-radius: .4rem !important;
        font-size: .875rem !important;
        color: #344054 !important;
        margin: .1rem 0;
    }
    .select2-dropdown.ta-exam-dropdown .select2-results__option--highlighted[aria-selected],
    .select2-dropdown.ta-exam-dropdown .select2-results__option--highlighted {
        background: #f8f5ff !important;
        color: #6200ea !important;
    }
    .select2-dropdown.ta-exam-dropdown .select2-results__option[aria-selected=true] {
        background: #f4ebff !important;
        color: #6200ea !important;
        font-weight: 400;
    }
    .select2-dropdown.ta-exam-dropdown .select2-results__message {
        color: #98a2b3 !important;
        font-size: .825rem !important;
        padding: .75rem !important;
    }
</style>
@endsection

@section('content')
@php
    $selectedBatches = [];
    if ($exam) {
        $selectedBatches = old('exam_id')
            ? array_values(array_filter(array_map('intval', old('batches', []))))
            : array_values(array_unique(array_merge(
                $exam->batches->where('status', 1)->modelKeys(),
                $selectedBatchId ? [(int) $selectedBatchId] : []
            )));
        $activeBatchIds = $batches->modelKeys();
        $selectedBatches = array_values(array_filter(
            $selectedBatches,
            fn ($id) => in_array((int) $id, $activeBatchIds, true)
        ));
    }
    $batchPayload = $batches->map(function ($batch) {
        return [
            'id' => (int) $batch->id,
            'name' => $batch->name,
            'status' => (int) ($batch->status ?? 1),
            'students_count' => (int) $batch->students_count,
            'student_ids' => $batch->students->pluck('id')->map(fn ($id) => (int) $id)->values(),
        ];
    })->values();
    $currentUnique = $exam ? ($exams->firstWhere('id', $exam->id)->unique_students_count ?? 0) : 0;
@endphp
<div class="container-xl px-5 ta-page">
    <div class="ta-header d-flex flex-wrap justify-content-between align-items-end gap-2">
        <div>
            <h1>Test Assignments</h1>
            <p class="ta-sub">Assign a test to batches or individual students. Unassigned tests stay hidden.</p>
        </div>
        <a class="ta-link" href="{{ route('admin.batches') }}">Manage batches -></a>
    </div>

    @if(session('success'))
        <div class="ta-alert ta-alert-ok">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="ta-alert ta-alert-warn">{{ session('warning') }}</div>
    @endif
    @if($errors->any())
        <div class="ta-alert ta-alert-err">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="ta-shell">
        <div class="ta-panel ta-panel-pad">
            <form method="get" action="{{ route('admin.exam_assignments') }}" id="exam-load-form">
                <label class="ta-label" for="exam-picker">Select test</label>
                <select id="exam-picker" class="ta-select select2-exam" name="exam_id" required onchange="this.form.submit()">
                    <option value="">Choose a test...</option>
                    @foreach($exams as $item)
                        <option value="{{ $item->id }}" @selected(optional($exam)->id == $item->id)>
                            {{ $item->name }} - {{ $item->exam_code }} ({{ $item->type == 1 ? 'Online' : 'Offline' }})
                        </option>
                    @endforeach
                </select>
            </form>

            @if($exam)
                <div class="ta-exam-meta">
                    <span><strong>{{ $exam->name }}</strong></span>
                    <span>{{ $exam->exam_code }}</span>
                    <span>{{ $exam->type == 1 ? 'Online' : 'Offline' }}</span>
                    <span>{{ $exam->batches->count() }} batches · {{ $currentUnique }} unique students</span>
                </div>

                <form method="post" action="{{ route('admin.exam_assignments.save') }}" id="assignment-form">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                    <div class="ta-section">
                        <div class="ta-section-title">Batches</div>
                        <div id="batch-multiselect" class="ms-dropdown" data-batches='@json($batchPayload)'>
                            <div id="batch-hidden-inputs"></div>
                            <button type="button" class="ms-dropdown-toggle" id="batch-ms-toggle" aria-expanded="false">
                                <div class="ms-chips" id="batch-chips">
                                    <span class="ms-placeholder" id="batch-placeholder">Select one or more batches...</span>
                                </div>
                                <div class="ms-controls">
                                    <span class="ms-count" id="batch-count" hidden>0</span>
                                    <span class="ms-icon-btn" id="batch-clear" title="Clear all" hidden>
                                        <i class="material-icons" style="font-size:18px;">close</i>
                                    </span>
                                    <span class="ms-icon-btn" id="batch-caret" title="Toggle">
                                        <i class="material-icons" style="font-size:20px;">expand_more</i>
                                    </span>
                                </div>
                            </button>
                            <div class="ms-panel" id="batch-panel">
                                <div class="ms-search-wrap">
                                    <input type="search" id="batch-search" placeholder="Search batches..." autocomplete="off">
                                    <i class="material-icons">search</i>
                                </div>
                                <div class="ms-panel-actions">
                                    <button type="button" class="ta-ghost" id="batch-select-all">Select all</button>
                                    <button type="button" class="ta-ghost" id="batch-clear-visible">Clear</button>
                                </div>
                                <div class="ms-options" id="batch-options">
                                    @forelse($batches as $item)
                                        <label class="ms-option" data-search="{{ strtolower($item->name) }}">
                                            <input type="checkbox" class="batch-option-cb" value="{{ $item->id }}"
                                                @checked(in_array((int) $item->id, $selectedBatches, true))>
                                            <span>{{ $item->name }} - {{ $item->students_count }} students</span>
                                        </label>
                                    @empty
                                        <p class="ta-empty mb-0">No batches yet. <a class="ta-link" href="{{ route('admin.batches') }}">Create one</a>.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ta-section">
                        @php
                            $individualSelectedCount = old('exam_id')
                                ? count(array_filter(array_map('intval', old('students', []))))
                                : $exam->assignedStudents->count();
                        @endphp
                        <button type="button" class="ta-collapse-head" id="students-collapse-toggle" aria-expanded="false" aria-controls="students-collapse-body">
                            <span class="ta-collapse-title">
                                Individual students
                                <span class="ta-collapse-meta"><strong id="selected-students-count">{{ $individualSelectedCount }}</strong> selected</span>
                            </span>
                            <i class="material-icons ta-collapse-icon" aria-hidden="true">expand_more</i>
                        </button>
                        <div class="ta-collapse-body" id="students-collapse-body" hidden>
                            <div class="ta-toolbar">
                                <input type="search" id="student-search" class="ta-input ta-search" placeholder="Search students...">
                                <button type="button" class="ta-ghost" id="select-visible-students">Select visible</button>
                                <button type="button" class="ta-ghost" id="clear-students">Clear</button>
                            </div>
                            <div class="ta-students" id="student-checklist">
                                @forelse($students as $student)
                                    @php
                                        $name = trim($student->first_name.' '.$student->last_name) ?: 'Student #'.$student->id;
                                        $meta = $student->email_id;
                                        if ($student->rankpro_id) { $meta .= ' · '.$student->rankpro_id; }
                                        $search = strtolower($name.' '.$student->email_id.' '.($student->rankpro_id ?? ''));
                                        $selectedStudentIds = old('exam_id')
                                            ? array_map('intval', old('students', []))
                                            : $exam->assignedStudents->modelKeys();
                                        $checked = in_array((int) $student->id, array_map('intval', $selectedStudentIds), true);
                                    @endphp
                                    <label class="ta-student student-row" data-search="{{ $search }}" data-student-id="{{ $student->id }}">
                                        <input class="student-cb" type="checkbox" name="students[]" value="{{ $student->id }}"
                                               data-student-name="{{ $name }}" @checked($checked)>
                                        <span>
                                            <span class="ta-student-name">{{ $name }}</span>
                                            <span class="ta-student-meta">{{ $meta }}</span>
                                        </span>
                                    </label>
                                @empty
                                    <div class="ta-empty">No active students found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="ta-footer">
                        <div class="ta-hint">Students already in a selected batch are covered automatically and counted once.</div>
                        <button class="ta-btn ta-btn-primary" type="submit">Save assignments</button>
                    </div>
                </form>
            @else
                <p class="ta-empty mb-0 mt-3">Pick a test to start assigning batches and students.</p>
            @endif
        </div>

        <aside>
            <div class="ta-panel mb-3" id="assigned-tests-panel" data-page-size="5">
                <div class="ta-side-title">
                    <span>Assigned tests</span>
                    <span id="assigned-tests-count">{{ $assignmentOverview->count() }}</span>
                </div>
                @if($assignmentOverview->isEmpty())
                    <div class="ta-empty">Nothing assigned yet.</div>
                @else
                    <div class="ta-side-tools">
                        <input type="search" class="ta-side-search" id="assigned-tests-search" placeholder="Search assigned tests..." autocomplete="off">
                    </div>
                    <ul class="ta-assign-list" id="assigned-tests-list">
                        @foreach($assignmentOverview as $row)
                            <li class="ta-assign-item ta-side-item @if(optional($exam)->id == $row->id) active @endif"
                                data-search="{{ strtolower(trim($row->name.' '.$row->exam_code)) }}">
                                <a href="{{ route('admin.exam_assignments', ['exam_id' => $row->id]) }}">
                                    <div class="ta-assign-name">{{ $row->name }}</div>
                                    <div class="ta-assign-meta">
                                        <span class="ta-pill ta-pill-muted">{{ $row->exam_code }}</span>
                                        <span class="ta-pill">{{ $row->batches_count }} batches</span>
                                        <span class="ta-pill">{{ $row->unique_students_count }} students</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="ta-side-empty" id="assigned-tests-empty">No matching tests.</div>
                    <div class="ta-side-pager" id="assigned-tests-pager">
                        <span class="ta-side-page-info" id="assigned-tests-page-info">1 / 1</span>
                        <div class="ta-side-page-btns">
                            <button type="button" class="ta-side-page-btn" id="assigned-tests-prev" aria-label="Previous"><</button>
                            <button type="button" class="ta-side-page-btn" id="assigned-tests-next" aria-label="Next">></button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="ta-panel" id="batches-panel" data-page-size="5">
                <div class="ta-side-title">
                    <span>Batches</span>
                    <a class="ta-link" href="{{ route('admin.batches') }}">Manage</a>
                </div>
                @if($batches->isEmpty())
                    <div class="ta-empty">No batches yet.</div>
                @else
                    <div class="ta-side-tools">
                        <input type="search" class="ta-side-search" id="batches-side-search" placeholder="Search batches..." autocomplete="off">
                    </div>
                    <ul class="ta-batch-list" id="batches-side-list">
                        @foreach($batches as $item)
                            <li class="ta-batch-item ta-side-item" data-search="{{ strtolower($item->name) }}">
                                <span class="ta-batch-name">{{ $item->name }}</span>
                                <span class="ta-batch-stats">{{ $item->students_count }} students</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="ta-side-empty" id="batches-side-empty">No matching batches.</div>
                    <div class="ta-side-pager" id="batches-side-pager">
                        <span class="ta-side-page-info" id="batches-side-page-info">1 / 1</span>
                        <div class="ta-side-page-btns">
                            <button type="button" class="ta-side-page-btn" id="batches-side-prev" aria-label="Previous"><</button>
                            <button type="button" class="ta-side-page-btn" id="batches-side-next" aria-label="Next">></button>
                        </div>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection

@section('js_after')
<script>
(function () {
    function initSideList(config) {
        var panel = document.getElementById(config.panelId);
        if (!panel) return;
        var list = document.getElementById(config.listId);
        var search = document.getElementById(config.searchId);
        var empty = document.getElementById(config.emptyId);
        var pager = document.getElementById(config.pagerId);
        var info = document.getElementById(config.infoId);
        var prev = document.getElementById(config.prevId);
        var next = document.getElementById(config.nextId);
        var countEl = config.countId ? document.getElementById(config.countId) : null;
        if (!list || !search || !empty || !pager || !info || !prev || !next) return;

        var pageSize = parseInt(panel.getAttribute('data-page-size') || '5', 10) || 5;
        var page = 1;
        var items = Array.prototype.slice.call(list.querySelectorAll('.ta-side-item'));

        function visibleItems() {
            var q = (search.value || '').toLowerCase().trim();
            return items.filter(function (item) {
                var hay = item.getAttribute('data-search') || '';
                var match = !q || hay.indexOf(q) !== -1;
                item.classList.toggle('is-filtered-out', !match);
                return match;
            });
        }

        function render() {
            var matched = visibleItems();
            var totalPages = Math.max(1, Math.ceil(matched.length / pageSize));
            if (page > totalPages) page = totalPages;
            if (page < 1) page = 1;

            var start = (page - 1) * pageSize;
            var end = start + pageSize;
            matched.forEach(function (item, index) {
                item.classList.toggle('is-paged-out', index < start || index >= end);
            });

            empty.style.display = matched.length ? 'none' : 'block';
            list.style.display = matched.length ? '' : 'none';
            pager.style.display = matched.length ? 'flex' : 'none';
            info.textContent = matched.length
                ? ('Page ' + page + ' of ' + totalPages + ' · ' + matched.length + ' result' + (matched.length === 1 ? '' : 's'))
                : '0 results';
            prev.disabled = page <= 1;
            next.disabled = page >= totalPages;
            if (countEl) {
                countEl.textContent = String(matched.length);
            }
        }

        search.addEventListener('input', function () {
            page = 1;
            render();
        });
        prev.addEventListener('click', function () {
            if (page > 1) {
                page -= 1;
                render();
            }
        });
        next.addEventListener('click', function () {
            page += 1;
            render();
        });

        render();
    }

    initSideList({
        panelId: 'assigned-tests-panel',
        listId: 'assigned-tests-list',
        searchId: 'assigned-tests-search',
        emptyId: 'assigned-tests-empty',
        pagerId: 'assigned-tests-pager',
        infoId: 'assigned-tests-page-info',
        prevId: 'assigned-tests-prev',
        nextId: 'assigned-tests-next',
        countId: 'assigned-tests-count'
    });

    initSideList({
        panelId: 'batches-panel',
        listId: 'batches-side-list',
        searchId: 'batches-side-search',
        emptyId: 'batches-side-empty',
        pagerId: 'batches-side-pager',
        infoId: 'batches-side-page-info',
        prevId: 'batches-side-prev',
        nextId: 'batches-side-next'
    });

    if (window.jQuery && jQuery().select2) {
        jQuery('#exam-picker').select2({
            width: '100%',
            placeholder: 'Choose a test...',
            allowClear: false,
            dropdownCssClass: 'ta-exam-dropdown'
        });
        jQuery('#exam-picker').on('change', function () {
            if (this.value) {
                document.getElementById('exam-load-form').submit();
            }
        });
    }

    var root = document.getElementById('batch-multiselect');
    if (!root) {
        return;
    }

    var batches = [];
    try { batches = JSON.parse(root.getAttribute('data-batches') || '[]'); } catch (e) { batches = []; }
    var batchMap = {};
    batches.forEach(function (b) { batchMap[String(b.id)] = b; });

    var toggle = document.getElementById('batch-ms-toggle');
    var chips = document.getElementById('batch-chips');
    var countEl = document.getElementById('batch-count');
    var clearBtn = document.getElementById('batch-clear');
    var caret = document.getElementById('batch-caret');
    var search = document.getElementById('batch-search');
    var hiddenWrap = document.getElementById('batch-hidden-inputs');
    var options = Array.prototype.slice.call(document.querySelectorAll('.batch-option-cb'));

    function selectedIds() {
        return options.filter(function (cb) { return cb.checked; }).map(function (cb) { return String(cb.value); });
    }

    function batchCoveredStudentIds() {
        var ids = {};
        selectedIds().forEach(function (id) {
            var batch = batchMap[id];
            if (!batch || !batch.student_ids || Number(batch.status) !== 1) return;
            batch.student_ids.forEach(function (sid) { ids[String(sid)] = batch.name; });
        });
        return ids;
    }

    function syncHiddenInputs() {
        hiddenWrap.innerHTML = '';
        selectedIds().forEach(function (id) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'batches[]';
            input.value = id;
            hiddenWrap.appendChild(input);
        });
    }

    function renderChips() {
        var ids = selectedIds();
        chips.innerHTML = '';
        if (!ids.length) {
            var span = document.createElement('span');
            span.className = 'ms-placeholder';
            span.id = 'batch-placeholder';
            span.textContent = 'Select one or more batches...';
            chips.appendChild(span);
            countEl.hidden = true;
            clearBtn.hidden = true;
        } else {
            ids.forEach(function (id) {
                var batch = batchMap[id];
                var chip = document.createElement('span');
                chip.className = 'ms-chip';
                chip.innerHTML = '<span></span><button type="button" aria-label="Remove">&times;</button>';
                chip.querySelector('span').textContent = batch ? batch.name : ('Batch #' + id);
                chip.querySelector('button').addEventListener('click', function (e) {
                    e.stopPropagation();
                    var cb = options.find(function (item) { return String(item.value) === String(id); });
                    if (cb) {
                        cb.checked = false;
                        refresh();
                    }
                });
                chips.appendChild(chip);
            });
            countEl.hidden = false;
            countEl.textContent = String(ids.length);
            clearBtn.hidden = false;
        }
        syncHiddenInputs();
        markCoveredStudents();
    }

    function markCoveredStudents() {
        var covered = batchCoveredStudentIds();
        document.querySelectorAll('#student-checklist .student-row').forEach(function (row) {
            var sid = row.getAttribute('data-student-id');
            var cb = row.querySelector('.student-cb');
            var isCovered = !!covered[String(sid)];
            row.classList.toggle('student-covered', isCovered);
            if (isCovered && cb && cb.checked) {
                cb.checked = false;
            }
        });
    }

    function refresh() {
        renderChips();
        updateSelectedStudentsCount();
    }

    function setOpen(open) {
        root.classList.toggle('open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        caret.querySelector('.material-icons').textContent = open ? 'expand_less' : 'expand_more';
        if (open && search) {
            setTimeout(function () { search.focus(); }, 0);
        }
    }

    toggle.addEventListener('click', function (e) {
        if (e.target.closest('#batch-clear')) return;
        setOpen(!root.classList.contains('open'));
    });

    clearBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        options.forEach(function (cb) { cb.checked = false; });
        refresh();
    });

    options.forEach(function (cb) {
        cb.addEventListener('change', refresh);
    });

    if (search) {
        search.addEventListener('input', function () {
            var q = (search.value || '').toLowerCase().trim();
            document.querySelectorAll('#batch-options .ms-option').forEach(function (row) {
                row.classList.toggle('hidden', q && (row.getAttribute('data-search') || '').indexOf(q) === -1);
            });
        });
        search.addEventListener('click', function (e) { e.stopPropagation(); });
    }

    var batchSelectAll = document.getElementById('batch-select-all');
    if (batchSelectAll) {
        batchSelectAll.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('#batch-options .ms-option:not(.hidden) .batch-option-cb').forEach(function (cb) {
                cb.checked = true;
            });
            refresh();
        });
    }

    var batchClearVisible = document.getElementById('batch-clear-visible');
    if (batchClearVisible) {
        batchClearVisible.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('#batch-options .ms-option:not(.hidden) .batch-option-cb').forEach(function (cb) {
                cb.checked = false;
            });
            refresh();
        });
    }

    document.addEventListener('click', function (e) {
        if (!root.contains(e.target)) {
            setOpen(false);
        }
    });

    document.querySelectorAll('#student-checklist .student-cb').forEach(function (cb) {
        cb.addEventListener('click', function (e) {
            if (!cb.checked) return;
            var covered = batchCoveredStudentIds();
            var sid = String(cb.value);
            if (covered[sid]) {
                e.preventDefault();
                cb.checked = false;
                var name = cb.getAttribute('data-student-name') || 'This student';
                alert(name + ' is already in batch "' + covered[sid] + '". They already have access through that batch, so they are not counted again as an individual assignee.');
            }
            updateSelectedStudentsCount();
        });
        cb.addEventListener('change', updateSelectedStudentsCount);
    });

    function updateSelectedStudentsCount() {
        var el = document.getElementById('selected-students-count');
        if (!el) return;
        el.textContent = String(document.querySelectorAll('#student-checklist .student-cb:checked').length);
    }

    (function initStudentsCollapse() {
        var toggle = document.getElementById('students-collapse-toggle');
        var body = document.getElementById('students-collapse-body');
        if (!toggle || !body) return;
        toggle.addEventListener('click', function () {
            var open = toggle.getAttribute('aria-expanded') !== 'true';
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            body.hidden = !open;
            if (open) {
                var search = document.getElementById('student-search');
                if (search) setTimeout(function () { search.focus(); }, 0);
            }
        });
    })();

    var studentSearch = document.getElementById('student-search');
    var checklist = document.getElementById('student-checklist');
    if (studentSearch && checklist) {
        studentSearch.addEventListener('input', function () {
            var q = (studentSearch.value || '').toLowerCase().trim();
            checklist.querySelectorAll('.student-row').forEach(function (row) {
                row.classList.toggle('hidden', q && row.getAttribute('data-search').indexOf(q) === -1);
            });
        });
    }

    var selectVisible = document.getElementById('select-visible-students');
    if (selectVisible) {
        selectVisible.addEventListener('click', function () {
            var covered = batchCoveredStudentIds();
            var skipped = [];
            document.querySelectorAll('#student-checklist .student-row:not(.hidden) .student-cb').forEach(function (cb) {
                if (covered[String(cb.value)]) {
                    skipped.push(cb.getAttribute('data-student-name') || cb.value);
                    cb.checked = false;
                    return;
                }
                cb.checked = true;
            });
            updateSelectedStudentsCount();
            if (skipped.length) {
                alert(skipped.length + ' student(s) were skipped because they are already in a selected batch:\n\n' + skipped.join('\n') + '\n\nThey already have access through their batch and are counted only once.');
            }
        });
    }

    var clearStudents = document.getElementById('clear-students');
    if (clearStudents) {
        clearStudents.addEventListener('click', function () {
            document.querySelectorAll('#student-checklist .student-cb').forEach(function (cb) {
                cb.checked = false;
            });
            updateSelectedStudentsCount();
        });
    }

    var form = document.getElementById('assignment-form');
    if (form) {
        form.addEventListener('submit', function () {
            var covered = batchCoveredStudentIds();
            var removed = [];
            document.querySelectorAll('#student-checklist .student-cb:checked').forEach(function (cb) {
                if (covered[String(cb.value)]) {
                    removed.push(cb.getAttribute('data-student-name') || cb.value);
                    cb.checked = false;
                }
            });
            syncHiddenInputs();
            if (removed.length) {
                alert(removed.length + ' student(s) were not saved as individual assignees because they are already in a selected batch:\n\n' + removed.join('\n') + '\n\nThey keep access through the batch and are counted only once.');
            }
        });
    }

    refresh();
})();
</script>
@endsection
