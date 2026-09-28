@extends('layouts.backend')

@section('css_after')
<style>
    .bm-page { max-width: 1120px; }
    .bm-header { margin-top: 2.5rem; margin-bottom: 1.5rem; }
    .bm-header h1 { font-size: 1.5rem; font-weight: 500; letter-spacing: -.02em; margin: 0 0 .35rem; }
    .bm-sub { color: #667085; font-size: .9rem; margin: 0; }
    .bm-link { color: #6200ea; text-decoration: none; font-weight: 400; font-size: .875rem; }
    .bm-link:hover { text-decoration: underline; }

    .bm-shell {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 1.25rem;
        align-items: start;
    }
    @media (max-width: 991px) {
        .bm-shell { grid-template-columns: 1fr; }
    }

    .bm-panel {
        background: #fff;
        border: 1px solid #eaecf0;
        border-radius: .75rem;
    }
    .bm-panel-pad { padding: 1.25rem 1.35rem; }
    .bm-side-title {
        font-size: .72rem; font-weight: 500; letter-spacing: .06em; text-transform: uppercase;
        color: #98a2b3; padding: .9rem 1rem .35rem;
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
    }

    .bm-label { display: block; font-size: .8125rem; font-weight: 400; color: #344054; margin-bottom: .4rem; }
    .bm-collapse-head {
        display: flex; align-items: center; justify-content: space-between; gap: .75rem;
        width: 100%; border: 1px solid #eaecf0; background: #fcfcfd; border-radius: .55rem;
        padding: .55rem .75rem; cursor: pointer; margin-bottom: .55rem; text-align: left;
    }
    .bm-collapse-head:hover { background: #f9fafb; }
    .bm-collapse-head[aria-expanded="true"] {
        border-bottom-left-radius: 0; border-bottom-right-radius: 0; margin-bottom: 0;
        border-bottom-color: transparent;
    }
    .bm-collapse-title {
        display: flex; align-items: center; gap: .5rem; font-size: .8125rem; font-weight: 400; color: #344054;
    }
    .bm-collapse-meta { font-size: .78rem; color: #98a2b3; }
    .bm-collapse-meta strong { color: #6941c6; font-weight: 500; }
    .bm-collapse-icon {
        color: #98a2b3; font-size: 20px !important; line-height: 1; transition: transform .15s ease;
    }
    .bm-collapse-head[aria-expanded="true"] .bm-collapse-icon { transform: rotate(180deg); }
    .bm-collapse-body {
        border: 1px solid #eaecf0; border-top: 0; border-radius: 0 0 .55rem .55rem;
        padding: .7rem .75rem .75rem; background: #fff; margin-bottom: .15rem;
    }
    .bm-collapse-body[hidden] { display: none !important; }
    .bm-input, .bm-select {
        width: 100%; border: 1px solid #d0d5dd; border-radius: .5rem; padding: .6rem .75rem;
        font-size: .925rem; background: #fff; color: #101828;
    }
    .bm-input:focus, .bm-select:focus {
        outline: 0; border-color: #6200ea; box-shadow: 0 0 0 3px rgba(98,0,234,.12);
    }

    .bm-toolbar {
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: .5rem;
        margin-bottom: .65rem;
    }
    @media (max-width: 575px) {
        .bm-toolbar { grid-template-columns: 1fr 1fr; }
        .bm-toolbar .bm-search { grid-column: 1 / -1; }
    }
    .bm-ghost {
        border: 1px solid #d0d5dd; background: #fff; color: #344054; border-radius: .5rem;
        padding: .45rem .7rem; font-size: .8rem; font-weight: 400; white-space: nowrap; cursor: pointer;
    }
    .bm-ghost:hover { background: #f9fafb; }

    .bm-students {
        border: 1px solid #eaecf0; border-radius: .65rem; max-height: 360px; overflow: auto; background: #fcfcfd;
    }
    .bm-student {
        display: flex; align-items: flex-start; gap: .7rem; padding: .65rem .8rem;
        border-bottom: 1px solid #f2f4f7; margin: 0; cursor: pointer;
    }
    .bm-student:last-child { border-bottom: 0; }
    .bm-student:hover { background: #f8f5ff; }
    .bm-student.hidden { display: none !important; }
    .bm-student input { margin-top: .2rem; accent-color: #6200ea; width: 1rem; height: 1rem; flex-shrink: 0; }
    .bm-student-name { display: block; font-size: .9rem; font-weight: 400; color: #101828; line-height: 1.25; }
    .bm-student-meta { display: block; font-size: .78rem; color: #98a2b3; margin-top: .1rem; }

    .bm-meta-row {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: .75rem; margin-top: .85rem;
    }
    .bm-count { font-size: .8rem; color: #667085; }
    .bm-count strong { color: #101828; }
    .bm-actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
    .bm-btn {
        border: 0; border-radius: .5rem; padding: .55rem 1rem; font-size: .875rem; font-weight: 500; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: .25rem;
    }
    .bm-btn-primary { background: #6200ea; color: #fff; }
    .bm-btn-primary:hover { background: #5600cc; color: #fff; }
    .bm-btn-soft { background: #f4ebff; color: #6200ea; }
    .bm-btn-soft:hover { background: #ebe0ff; color: #6200ea; }
    .bm-btn-danger { background: transparent; color: #b42318; border: 1px solid #fecdca; padding: .45rem .8rem; font-size: .8rem; }
    .bm-btn-danger:hover { background: #fef3f2; }

    .bm-batch-list { list-style: none; margin: 0; padding: 0 0 .35rem; }
    .bm-batch-item {
        position: relative;
    }
    .bm-batch-item a {
        display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem;
        padding: .8rem 2.5rem .8rem 1rem; text-decoration: none; color: inherit;
        border-left: 3px solid transparent; border-bottom: 1px solid #f2f4f7;
    }
    .bm-batch-item:last-child a { border-bottom: 0; }
    .bm-batch-item a:hover { background: #f9fafb; }
    .bm-batch-item.active a { background: #f8f5ff; border-left-color: #6200ea; }
    .bm-batch-name { font-size: .92rem; font-weight: 500; color: #101828; }
    .bm-batch-meta { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .4rem; }
    .bm-pill {
        display: inline-flex; align-items: center; padding: .15rem .45rem; border-radius: 999px;
        background: #f4ebff; color: #6200ea; font-size: .72rem; font-weight: 500; line-height: 1.2;
    }
    .bm-batch-delete {
        position: absolute; top: .65rem; right: .55rem; z-index: 2;
        width: 30px; height: 30px; border: 0; border-radius: .4rem;
        background: transparent; color: #98a2b3; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; padding: 0;
    }
    .bm-batch-delete:hover { background: #fef3f2; color: #b42318; }
    .bm-batch-delete .material-icons { font-size: 18px; }

    .bm-modal-backdrop {
        position: fixed; inset: 0; background: rgba(16,24,40,.45); z-index: 1050;
        display: none; align-items: center; justify-content: center; padding: 1rem;
    }
    .bm-modal-backdrop.open { display: flex; }
    .bm-modal {
        width: 100%; max-width: 420px; background: #fff; border-radius: .85rem;
        box-shadow: 0 20px 40px rgba(16,24,40,.18); overflow: hidden;
        animation: bmModalIn .16s ease-out;
    }
    @keyframes bmModalIn {
        from { transform: translateY(8px); opacity: .6; }
        to { transform: none; opacity: 1; }
    }
    .bm-modal-body { padding: 1.35rem 1.35rem 1rem; text-align: center; }
    .bm-modal-icon {
        width: 52px; height: 52px; border-radius: 999px; margin: 0 auto .9rem;
        background: #fef3f2; color: #b42318;
        display: flex; align-items: center; justify-content: center;
    }
    .bm-modal-icon .material-icons { font-size: 26px; }
    .bm-modal-title { font-size: 1.1rem; font-weight: 500; color: #101828; margin: 0 0 .4rem; }
    .bm-modal-text { font-size: .9rem; color: #667085; margin: 0; line-height: 1.45; }
    .bm-modal-text strong { color: #101828; font-weight: 500; }
    .bm-modal-actions {
        display: flex; gap: .6rem; padding: 0 1.35rem 1.35rem; justify-content: center;
    }
    .bm-modal-btn {
        border-radius: .5rem; padding: .55rem 1rem; font-size: .875rem; font-weight: 500;
        cursor: pointer; border: 1px solid transparent; min-width: 110px;
    }
    .bm-modal-btn-cancel { background: #fff; border-color: #d0d5dd; color: #344054; }
    .bm-modal-btn-cancel:hover { background: #f9fafb; }
    .bm-modal-btn-danger { background: #d92d20; color: #fff; border-color: #d92d20; }
    .bm-modal-btn-danger:hover { background: #b42318; }
    .bm-empty { padding: 1rem; color: #98a2b3; font-size: .875rem; }

    .bm-side-tools { padding: 0 .75rem .55rem; }
    .bm-side-search {
        width: 100%; border: 1px solid #d0d5dd; border-radius: .45rem; padding: .45rem .65rem;
        font-size: .8rem; background: #fff; color: #101828;
    }
    .bm-side-search:focus {
        outline: 0; border-color: #6200ea; box-shadow: 0 0 0 3px rgba(98,0,234,.1);
    }
    .bm-side-pager {
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
        padding: .55rem .75rem .75rem; border-top: 1px solid #f2f4f7;
    }
    .bm-side-page-info { font-size: .72rem; color: #667085; font-weight: 500; }
    .bm-side-page-btns { display: inline-flex; gap: .35rem; }
    .bm-side-page-btn {
        border: 1px solid #d0d5dd; background: #fff; color: #344054; border-radius: .4rem;
        width: 28px; height: 28px; font-size: .9rem; line-height: 1; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .bm-side-page-btn:hover:not(:disabled) { background: #f8f5ff; border-color: #c4b5fd; color: #6200ea; }
    .bm-side-page-btn:disabled { opacity: .4; cursor: not-allowed; }
    .bm-side-empty { display: none; padding: .85rem 1rem 1rem; color: #98a2b3; font-size: .8rem; }
    .bm-side-item.is-filtered-out, .bm-side-item.is-paged-out { display: none !important; }

    .bm-alert { border-radius: .65rem; padding: .75rem 1rem; font-size: .875rem; margin-bottom: 1rem; border: 1px solid transparent; }
    .bm-alert-ok { background: #ecfdf3; border-color: #abefc6; color: #067647; }
    .bm-alert-err { background: #fef3f2; border-color: #fecdca; color: #b42318; }

    .bm-linked-top {
        margin: 0 0 1.1rem;
        padding: .75rem .9rem;
        border: 1px solid #eaecf0;
        border-radius: .65rem;
        background: #fcfcfd;
    }
    .bm-linked-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: .5rem; margin-bottom: .55rem;
    }
    .bm-linked-title { font-size: .8rem; font-weight: 500; color: #667085; margin: 0; }
    .bm-linked-count {
        font-size: .72rem; font-weight: 500; color: #6941c6;
        background: #f4ebff; border-radius: 999px; padding: .12rem .5rem; line-height: 1.4;
    }
    .bm-linked-chips {
        display: flex; flex-wrap: wrap; gap: .45rem;
    }
    .bm-linked-chip {
        display: inline-flex; align-items: center; gap: .4rem; max-width: 100%;
        padding: .35rem .65rem; border-radius: .45rem;
        border: 1px solid #e9d7fe; background: #f9f5ff;
        color: #6941c6; text-decoration: none; font-size: .8125rem; line-height: 1.3;
        transition: background .12s ease, border-color .12s ease;
    }
    .bm-linked-chip:hover {
        background: #f4ebff; border-color: #d6bbfb; color: #5925dc; text-decoration: none;
    }
    .bm-linked-chip-name {
        font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 14rem;
    }
    .bm-linked-chip-code {
        flex-shrink: 0; font-size: .72rem; font-weight: 400; color: #9e77ed;
        background: #fff; border: 1px solid #e9d7fe; border-radius: .3rem; padding: .05rem .35rem;
    }

    .bm-status-row {
        display: flex; align-items: flex-end; gap: .85rem; margin-bottom: 1rem;
    }
    .bm-status-row .bm-name-field { flex: 1; min-width: 0; }
    .bm-toggle-wrap {
        display: flex; flex-direction: column; gap: .4rem; flex-shrink: 0; padding-bottom: .1rem;
    }
    .bm-toggle {
        display: inline-flex; align-items: center; gap: .55rem; cursor: pointer; margin: 0;
        user-select: none;
    }
    .bm-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
    .bm-toggle-track {
        position: relative; width: 42px; height: 24px; border-radius: 999px;
        background: #d0d5dd; transition: background .15s ease; flex-shrink: 0;
    }
    .bm-toggle-track::after {
        content: ""; position: absolute; top: 3px; left: 3px;
        width: 18px; height: 18px; border-radius: 50%; background: #fff;
        box-shadow: 0 1px 2px rgba(16,24,40,.2); transition: transform .15s ease;
    }
    .bm-toggle input:checked + .bm-toggle-track { background: #6200ea; }
    .bm-toggle input:checked + .bm-toggle-track::after { transform: translateX(18px); }
    .bm-toggle-text { font-size: .875rem; color: #344054; min-width: 3.5rem; }
    @media (max-width: 576px) {
        .bm-status-row { flex-direction: column; align-items: stretch; }
        .bm-toggle-wrap { padding-bottom: 0; }
    }
    .bm-pill-muted { background: #f2f4f7; color: #667085; }
    .bm-pill-warn { background: #fffaeb; color: #b54708; }

    /* Select2 - Switch batch */
    .bm-page .select2-container { width: 100% !important; }
    .bm-page .select2-container .select2-selection--single {
        height: 44px !important;
        border: 1px solid #d0d5dd !important;
        border-radius: .55rem !important;
        padding: .4rem .7rem !important;
        background: #fff !important;
        box-shadow: none !important;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .bm-page .select2-container--default.select2-container--open .select2-selection--single,
    .bm-page .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #6200ea !important;
        box-shadow: 0 0 0 3px rgba(98,0,234,.12) !important;
        outline: 0 !important;
    }
    .bm-page .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        color: #101828 !important;
        padding-left: 0 !important;
        font-size: .925rem !important;
        font-weight: 400;
    }
    .bm-page .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #98a2b3 !important;
        font-weight: 400;
    }
    .bm-page .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: .45rem !important;
        width: 24px !important;
    }
    .bm-page .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #667085 transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
        margin-left: -4px !important;
        margin-top: -2px !important;
    }
    .bm-page .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #667085 transparent !important;
        border-width: 0 4px 5px 4px !important;
        margin-top: -3px !important;
    }
    .select2-dropdown.bm-batch-dropdown {
        border: 1px solid #eaecf0 !important;
        border-radius: .65rem !important;
        box-shadow: 0 8px 24px rgba(16,24,40,.08) !important;
        overflow: hidden;
        margin-top: .35rem;
        background: #fff;
    }
    .select2-dropdown.bm-batch-dropdown .select2-search--dropdown {
        padding: .65rem .7rem .45rem !important;
        background: #fff;
    }
    .select2-dropdown.bm-batch-dropdown .select2-search--dropdown .select2-search__field {
        border: 1px solid #d0d5dd !important;
        border-radius: .45rem !important;
        padding: .5rem .7rem !important;
        font-size: .875rem !important;
        color: #101828 !important;
        outline: 0 !important;
        box-shadow: none !important;
    }
    .select2-dropdown.bm-batch-dropdown .select2-search--dropdown .select2-search__field:focus {
        border-color: #6200ea !important;
        box-shadow: 0 0 0 3px rgba(98,0,234,.1) !important;
    }
    .select2-dropdown.bm-batch-dropdown .select2-results {
        padding: .25rem .4rem .55rem;
    }
    .select2-dropdown.bm-batch-dropdown .select2-results__options {
        max-height: 240px;
    }
    .select2-dropdown.bm-batch-dropdown .select2-results__option {
        padding: .55rem .7rem !important;
        border-radius: .4rem !important;
        font-size: .875rem !important;
        color: #344054 !important;
        margin: .1rem 0;
    }
    .select2-dropdown.bm-batch-dropdown .select2-results__option--highlighted[aria-selected],
    .select2-dropdown.bm-batch-dropdown .select2-results__option--highlighted {
        background: #f8f5ff !important;
        color: #6200ea !important;
    }
    .select2-dropdown.bm-batch-dropdown .select2-results__option[aria-selected=true] {
        background: #f4ebff !important;
        color: #6200ea !important;
        font-weight: 400;
    }
    .select2-dropdown.bm-batch-dropdown .select2-results__message {
        color: #98a2b3 !important;
        font-size: .825rem !important;
        padding: .75rem !important;
    }
</style>
@endsection

@section('content')
@php
    $selectedCount = 0;
    if (old('name') !== null) {
        $selectedCount = count(old('students', []));
    } elseif ($batch) {
        $selectedCount = $batch->students->count();
    }
@endphp
<div class="container-xl px-5 bm-page">
    <div class="bm-header d-flex flex-wrap justify-content-between align-items-end gap-2">
        <div>
            <h1>Batch Master</h1>
            <p class="bm-sub">Group students once, then assign tests to the whole batch.</p>
        </div>
        <a class="bm-link" href="{{ route('admin.exam_assignments') }}">Go to Test Assignments -></a>
    </div>

    @if(session('success'))
        <div class="bm-alert bm-alert-ok">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bm-alert bm-alert-err">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bm-shell">
        <div class="bm-panel bm-panel-pad">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div style="font-size:1rem;font-weight: 500;color:#101828;">
                    {{ $batch ? 'Edit batch' : 'New batch' }}
                </div>
                @if($batch)
                    <a class="bm-link" href="{{ route('admin.batches') }}">+ Create another</a>
                @endif
            </div>

            @if($batch && $batch->exams->isNotEmpty())
                <div class="bm-linked-top">
                    <div class="bm-linked-head">
                        <div class="bm-linked-title">Assigned to these tests</div>
                        <span class="bm-linked-count">{{ $batch->exams->count() }}</span>
                    </div>
                    <div class="bm-linked-chips">
                        @foreach($batch->exams as $exam)
                            <a class="bm-linked-chip" href="{{ route('admin.exam_assignments', ['exam_id' => $exam->id]) }}" title="{{ $exam->name }} · {{ $exam->exam_code }}">
                                <span class="bm-linked-chip-name">{{ $exam->name }}</span>
                                @if($exam->exam_code)
                                    <span class="bm-linked-chip-code">{{ $exam->exam_code }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="post" action="{{ route('admin.batches.save') }}" id="batch-save-form">
                @csrf
                <input type="hidden" name="batch_id" value="{{ optional($batch)->id }}">

                @php $batchStatus = (int) old('status', optional($batch)->status ?? 1); @endphp
                <div class="bm-status-row">
                    <div class="bm-name-field">
                        <label class="bm-label" for="batch-name">Batch name</label>
                        <input id="batch-name" class="bm-input" name="name" required maxlength="255"
                               value="{{ old('name', optional($batch)->name) }}" placeholder="e.g. Morning Batch A" autofocus>
                    </div>
                    <div class="bm-toggle-wrap">
                        <span class="bm-label">Status</span>
                        <input type="hidden" name="status" id="batch-status-value" value="{{ $batchStatus }}">
                        <label class="bm-toggle" for="batch-status-toggle">
                            <input type="checkbox" id="batch-status-toggle" @checked($batchStatus === 1)>
                            <span class="bm-toggle-track"></span>
                            <span class="bm-toggle-text" id="batch-status-text">{{ $batchStatus === 1 ? 'Active' : 'Inactive' }}</span>
                        </label>
                    </div>
                </div>

                <button type="button" class="bm-collapse-head" id="students-collapse-toggle" aria-expanded="false" aria-controls="students-collapse-body">
                    <span class="bm-collapse-title">
                        Students
                        <span class="bm-collapse-meta"><strong id="selected-count-head">{{ $selectedCount }}</strong> selected</span>
                    </span>
                    <i class="material-icons bm-collapse-icon" aria-hidden="true">expand_more</i>
                </button>
                <div class="bm-collapse-body" id="students-collapse-body" hidden>
                    <div class="bm-toolbar">
                        <input type="search" id="student-search" class="bm-input bm-search" placeholder="Search by name or email...">
                        <button type="button" class="bm-ghost" id="select-visible-students">Select visible</button>
                        <button type="button" class="bm-ghost" id="clear-students">Clear</button>
                    </div>

                    <div class="bm-students" id="student-checklist">
                        @forelse($students as $student)
                            @php
                                $name = trim($student->first_name.' '.$student->last_name) ?: 'Student #'.$student->id;
                                $meta = $student->email_id;
                                if ($student->rankpro_id) { $meta .= ' / '.$student->rankpro_id; }
                                $search = strtolower($name.' '.$student->email_id.' '.($student->rankpro_id ?? ''));
                                $selectedIds = old('name') !== null
                                    ? old('students', [])
                                    : ($batch ? $batch->students->modelKeys() : []);
                                $checked = in_array($student->id, $selectedIds, true)
                                    || in_array((string) $student->id, array_map('strval', $selectedIds));
                            @endphp
                            <label class="bm-student student-row" data-search="{{ $search }}">
                                <input class="student-cb" type="checkbox" name="students[]" value="{{ $student->id }}" @checked($checked)>
                                <span>
                                    <span class="bm-student-name">{{ $name }}</span>
                                    <span class="bm-student-meta">{{ $meta }}</span>
                                </span>
                            </label>
                        @empty
                            <div class="bm-empty">No active students found.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bm-meta-row">
                    <div class="bm-count"><strong id="selected-count">{{ $selectedCount }}</strong> selected</div>
                    <div class="bm-actions">
                        @if($batch)
                            <a href="{{ route('admin.exam_assignments', ['batch_id' => $batch->id]) }}" class="bm-btn bm-btn-soft">Assign test</a>
                        @endif
                        <button class="bm-btn bm-btn-primary" type="submit">{{ $batch ? 'Save changes' : 'Create batch' }}</button>
                    </div>
                </div>
            </form>
        </div>

        <aside class="bm-panel" id="batches-side-panel" data-page-size="5">
            <div class="bm-side-title">
                <span>Batches</span>
                <span id="batches-side-count">{{ $batches->count() }}</span>
            </div>
            @if($batches->isEmpty())
                <div class="bm-empty">No batches yet. Create one on the left.</div>
            @else
                <div class="bm-side-tools">
                    <input type="search" class="bm-side-search" id="batches-side-search" placeholder="Search batches..." autocomplete="off">
                </div>
                <ul class="bm-batch-list" id="batches-side-list">
                    @foreach($batches as $item)
                        <li class="bm-batch-item bm-side-item @if(optional($batch)->id == $item->id) active @endif"
                            data-search="{{ strtolower($item->name.' '.( ((int)$item->status === 1) ? 'active' : 'inactive')) }}">
                            <a href="{{ route('admin.batches', ['batch_id' => $item->id]) }}">
                                <span>
                                    <span class="bm-batch-name">{{ $item->name }}</span>
                                    <span class="bm-batch-meta">
                                        <span class="bm-pill">{{ $item->students_count }} students</span>
                                        <span class="bm-pill">{{ $item->exams_count }} tests</span>
                                        @if((int) $item->status === 1)
                                            <span class="bm-pill bm-pill-muted">Active</span>
                                        @else
                                            <span class="bm-pill bm-pill-warn">Inactive</span>
                                        @endif
                                    </span>
                                </span>
                            </a>
                            <button type="button"
                                    class="bm-batch-delete"
                                    title="Delete batch"
                                    aria-label="Delete {{ $item->name }}"
                                    data-batch-id="{{ $item->id }}"
                                    data-batch-name="{{ $item->name }}">
                                <i class="material-icons">delete</i>
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="bm-side-empty" id="batches-side-empty">No matching batches.</div>
                <div class="bm-side-pager" id="batches-side-pager">
                    <span class="bm-side-page-info" id="batches-side-page-info">Page 1 of 1</span>
                    <div class="bm-side-page-btns">
                        <button type="button" class="bm-side-page-btn" id="batches-side-prev" aria-label="Previous">&lt;</button>
                        <button type="button" class="bm-side-page-btn" id="batches-side-next" aria-label="Next">&gt;</button>
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>

<div class="bm-modal-backdrop" id="bm-delete-modal" aria-hidden="true">
    <div class="bm-modal" role="dialog" aria-modal="true" aria-labelledby="bm-delete-title">
        <div class="bm-modal-body">
            <div class="bm-modal-icon"><i class="material-icons">delete</i></div>
            <h3 class="bm-modal-title" id="bm-delete-title">Delete batch?</h3>
            <p class="bm-modal-text">
                You are about to delete <strong id="bm-delete-batch-name">this batch</strong>.
                Students keep their accounts, but this batch and its test links will be removed.
            </p>
        </div>
        <div class="bm-modal-actions">
            <button type="button" class="bm-modal-btn bm-modal-btn-cancel" id="bm-delete-cancel">Cancel</button>
            <button type="button" class="bm-modal-btn bm-modal-btn-danger" id="bm-delete-confirm">Delete</button>
        </div>
    </div>
</div>

<form method="post" action="{{ route('admin.batches.delete') }}" id="bm-delete-form" style="display:none;">
    @csrf
    <input type="hidden" name="batch_id" id="bm-delete-batch-id" value="">
</form>
@endsection

@section('js_after')
<script>
(function () {
    (function initStatusToggle() {
        var toggle = document.getElementById('batch-status-toggle');
        var valueInput = document.getElementById('batch-status-value');
        var text = document.getElementById('batch-status-text');
        if (!toggle || !valueInput || !text) return;
        function sync() {
            valueInput.value = toggle.checked ? '1' : '0';
            text.textContent = toggle.checked ? 'Active' : 'Inactive';
        }
        toggle.addEventListener('change', sync);
        sync();
    })();

    function initSideList() {
        var panel = document.getElementById('batches-side-panel');
        var list = document.getElementById('batches-side-list');
        var search = document.getElementById('batches-side-search');
        var empty = document.getElementById('batches-side-empty');
        var pager = document.getElementById('batches-side-pager');
        var info = document.getElementById('batches-side-page-info');
        var prev = document.getElementById('batches-side-prev');
        var next = document.getElementById('batches-side-next');
        var countEl = document.getElementById('batches-side-count');
        if (!panel || !list || !search || !empty || !pager || !info || !prev || !next) return;

        var pageSize = parseInt(panel.getAttribute('data-page-size') || '5', 10) || 5;
        var page = 1;
        var items = Array.prototype.slice.call(list.querySelectorAll('.bm-side-item'));

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
                ? ('Page ' + page + ' of ' + totalPages + ' / ' + matched.length + ' result' + (matched.length === 1 ? '' : 's'))
                : '0 results';
            prev.disabled = page <= 1;
            next.disabled = page >= totalPages;
            if (countEl) countEl.textContent = String(matched.length);
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

    initSideList();

    (function initDeleteModal() {
        var modal = document.getElementById('bm-delete-modal');
        var nameEl = document.getElementById('bm-delete-batch-name');
        var idInput = document.getElementById('bm-delete-batch-id');
        var form = document.getElementById('bm-delete-form');
        var cancelBtn = document.getElementById('bm-delete-cancel');
        var confirmBtn = document.getElementById('bm-delete-confirm');
        if (!modal || !form || !idInput || !nameEl) return;

        function openModal(id, name) {
            idInput.value = id;
            nameEl.textContent = name || 'this batch';
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            confirmBtn.focus();
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            idInput.value = '';
        }

        document.querySelectorAll('.bm-batch-delete').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openModal(btn.getAttribute('data-batch-id'), btn.getAttribute('data-batch-name'));
            });
        });

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });
        confirmBtn.addEventListener('click', function () {
            if (!idInput.value) return;
            form.submit();
        });
    })();

    var search = document.getElementById('student-search');
    var checklist = document.getElementById('student-checklist');
    var countEl = document.getElementById('selected-count');

    function updateCount() {
        var n = String(document.querySelectorAll('#student-checklist .student-cb:checked').length);
        if (countEl) countEl.textContent = n;
        var headCount = document.getElementById('selected-count-head');
        if (headCount) headCount.textContent = n;
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

    if (search && checklist) {
        search.addEventListener('input', function () {
            var q = (search.value || '').toLowerCase().trim();
            checklist.querySelectorAll('.student-row').forEach(function (row) {
                row.classList.toggle('hidden', q && (row.getAttribute('data-search') || '').indexOf(q) === -1);
            });
        });
    }

    if (checklist) {
        checklist.addEventListener('change', updateCount);
    }

    var selectVisible = document.getElementById('select-visible-students');
    if (selectVisible) {
        selectVisible.addEventListener('click', function () {
            document.querySelectorAll('#student-checklist .student-row:not(.hidden) .student-cb').forEach(function (cb) {
                cb.checked = true;
            });
            updateCount();
        });
    }

    var clearBtn = document.getElementById('clear-students');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            document.querySelectorAll('#student-checklist .student-cb').forEach(function (cb) {
                cb.checked = false;
            });
            updateCount();
        });
    }

    updateCount();
})();
</script>
@endsection
