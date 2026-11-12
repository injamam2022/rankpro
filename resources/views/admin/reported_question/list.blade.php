@extends('layouts.backend')

@section('css_after')
    <style>
        .reported-qtext {
            max-width: 360px;
            max-height: 120px;
            overflow: hidden;
            font-size: 13px;
            line-height: 1.4;
        }
        .reported-qtext img {
            max-width: 160px;
            height: auto;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Reported Questions</h1>
                <div class="text-muted small mt-1">Questions flagged by students during online exams</div>
            </div>
        </div>

        <form action="" method="get">
            <div class="admin-list-bar" style="margin-bottom: 10px;">
                <div class="admin-list-field">
                    <label for="subject_id">Subject</label>
                    <select class="form-control" id="subject_id" name="subject_id">
                        <option value="">All subjects</option>
                        @foreach($subject_list as $subject)
                            <option value="{{ $subject->id }}" @if((int)$subject_id === (int)$subject->id) selected @endif>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                @include('admin.include.list_toolbar', [
                    'q' => $q,
                    'search_placeholder' => 'Student, exam, question…',
                    'clear_url' => route('admin.reported_question'),
                ])
            </div>
        </form>

        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Exam</th>
                    <th>Subject</th>
                    <th>Question</th>
                    <th>Student ans</th>
                    <th>Correct</th>
                    <th>Reported at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($list as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>
                            {{ trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: '—' }}
                            @if(!empty($row->email_id))
                                <div class="text-muted small">{{ $row->email_id }}</div>
                            @endif
                        </td>
                        <td>{{ $row->exam_name ?? ('Exam #' . $row->exam_id) }}</td>
                        <td>
                            {{ $row->subject_name ?? '—' }}
                            @if(!empty($row->chapter_name))
                                <div class="text-muted small">{{ $row->chapter_name }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="small mb-1">
                                <strong>Q#{{ $row->question_id }}</strong>
                            </div>
                            <div class="reported-qtext">
                                {!! question_html_media($row->question_text) !!}
                            </div>
                        </td>
                        <td>
                            @if($row->answer === null || $row->answer === '' || (string)$row->answer === '0')
                                <span class="text-muted">—</span>
                            @else
                                {{ $row->answer }}
                            @endif
                        </td>
                        <td>{{ $row->correct_answer ?? '—' }}</td>
                        <td>{{ \App\Support\AdminListQuery::when($row->updated_at) }}</td>
                        <td>
                            @if(!empty($row->question_id))
                                <a href="{{ route('admin.question.edit', ['id' => $row->question_id]) }}" class="btn btn-primary btn-sm mr-1" title="Edit question">
                                    <i class="material-icons">edit</i>
                                </a>
                            @endif
                            <a href="{{ route('admin.reported_question.clear', ['id' => $row->id]) }}"
                               class="btn btn-success btn-sm mr-1"
                               title="Clear report"
                               onclick="return confirm('Clear this report?');">
                                <i class="material-icons">done</i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No reported questions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('js_after')
@endsection
