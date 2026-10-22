@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4 align-items-end justify-content-between gap-2">
        <div>
            <h1 class="page-header mb-0">Student exam result detail</h1>
            <p class="text-muted mb-0 mt-1">
                {{ optional($user)->first_name }} {{ optional($user)->last_name }}
                · {{ $attempt->name ?? 'Exam' }}
                @if(!empty($attempt->total_number) || !empty($attempt->percentage))
                    · score {{ $attempt->total_number }} ({{ $attempt->percentage }}%)
                @endif
            </p>
        </div>
        <a href="{{ route('admin.student.exam_list', ['id' => $attempt->user_id]) }}" class="btn btn-secondary btn-sm">Back to analytics</a>
    </div>

    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Video</th>
                <th>Correct answer</th>
                <th>Student answer</th>
            </tr>
        </thead>
        <tbody>
            @forelse($list as $index => $row)
                @php
                    $correct = (string) ($row->correct_answer ?? $row->answer ?? '');
                    $given = (string) ($row->student_answer ?? '');
                    $ok = $given !== '' && $given === $correct;
                @endphp
                <tr>
                    <td>{{ $row->question_number ?? ($index + 1) }}</td>
                    <td>{!! question_html_media($row->question_text) !!}</td>
                    <td>
                        @if(!empty($row->video_link))
                            <a href="{{ $row->video_link }}" target="_blank" rel="noopener">Open</a>
                        @endif
                    </td>
                    <td>{{ $correct }}</td>
                    <td>
                        @if($given === '')
                            <span class="text-muted">Skipped</span>
                        @elseif($ok)
                            <span class="text-success">{{ $given }}</span>
                        @else
                            <span class="text-danger">{{ $given }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No questions found for this attempt.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
