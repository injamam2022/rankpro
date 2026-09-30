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

        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Test name</th>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Questions</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $key => $row)
                    @php
                        $student = $row->user;
                        $studentName = $student
                            ? trim(($student->first_name ?? '').' '.($student->last_name ?? ''))
                            : '—';
                        $created = $row->created_at
                            ? $row->created_at->format('d M Y, h:i A')
                            : '—';
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
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
                        <td>{{ $created }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No custom practice tests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('js_after')
@endsection
