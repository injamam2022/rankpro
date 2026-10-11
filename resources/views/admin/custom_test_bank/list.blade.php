@extends('layouts.backend')

@section('css_after')
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Custom Test Bank</h1>
                <p class="text-muted mb-0 mt-1" style="font-size:14px;">
                    Student practice tests only. These are hidden from Online Exam and Question Bank.
                </p>
            </div>
        </div>

        <form method="get" class="admin-list-bar">
            @include('admin.include.list_toolbar')
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Test name</th>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Questions</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th>Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $row)
                    @php
                        $student = $row->user;
                        $studentName = $student
                            ? trim(($student->first_name ?? '').' '.($student->last_name ?? ''))
                            : '—';
                    @endphp
                    <tr>
                        <td>{{ $list->firstItem() + $loop->index }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $studentName !== '' ? $studentName : '—' }}</td>
                        <td>{{ $student->rankpro_id ?? '—' }}</td>
                        <td>{{ $row->question_count }}</td>
                        <td>{{ $row->duration_minutes }} min</td>
                        <td>
                            <span class="badge {{ $row->status === 'attempted' ? 'bg-success' : ($row->status === 'ready' ? 'bg-primary' : 'bg-secondary') }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                        <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->created_at) }}</td>
                        <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->updated_at) }}</td>
                        <td>
                            <div class="rp-actions">
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.custom_test_bank.answers', ['id' => $row->id]),
                                    'variant' => 'view',
                                    'icon' => 'library_books',
                                    'title' => 'View questions & student answers',
                                ])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">No custom practice tests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('admin.include.list_pager')
    </div>
@endsection

@section('js_after')
@endsection
