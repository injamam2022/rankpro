@extends('layouts.backend')

@section('css_after')
<style>
    .ctb-qtext { max-width: 720px; }
    .ctb-qtext img { max-width: 180px; height: auto; }
    .ctb-opt { margin: 4px 0; font-size: 13px; }
    .ctb-opt img { max-width: 120px; height: auto; display: block; margin-top: 4px; }
    .ctb-opt.is-correct { color: #065f46; font-weight: 600; }
    .ctb-opt.is-student { background: #fff7ed; border-radius: 4px; padding: 2px 6px; }
    .ctb-meta { font-size: 13px; color: #64748b; }
</style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-start">
            <div style="flex:1;">
                <h1 class="page-header mb-0">Custom Test Answers</h1>
                <p class="text-muted mb-0 mt-1" style="font-size:14px;">
                    {{ $custom->name }}
                    · Student: {{ $studentName }}
                    · ID: {{ $studentId }}
                    · Status: {{ ucfirst($custom->status) }}
                </p>
                @unless($hasAttempt)
                    <p class="text-warning mb-0 mt-1" style="font-size:13px;">This test has not been attempted yet — student answers will be empty.</p>
                @endunless
            </div>
            <div class="rp-actions">
                <a href="{{ route('admin.custom_test_bank') }}" class="rp-act rp-act-ghost rp-act-label" title="Back">
                    <i class="material-icons">arrow_back</i>
                    Back
                </a>
                @if($custom->question_paper_id)
                    <a href="{{ route('admin.custom_test_bank.download_question', ['id' => $custom->id]) }}" class="rp-act rp-act-download rp-act-label" title="Download question PDF">
                        <i class="material-icons">download</i>
                        Question PDF
                    </a>
                    <a href="{{ route('admin.custom_test_bank.download_answer', ['id' => $custom->id]) }}" class="rp-act rp-act-download rp-act-label" title="Download answer PDF">
                        <i class="material-icons">download</i>
                        Answer PDF
                    </a>
                @endif
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Question</th>
                    <th style="width:90px;">Correct</th>
                    <th style="width:90px;">Student</th>
                    <th style="width:110px;">Result</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $row)
                    @php
                        $opts = [
                            1 => ['text' => $row->option1, 'img' => $row->is_option1_image],
                            2 => ['text' => $row->option2, 'img' => $row->is_option2_image],
                            3 => ['text' => $row->option3, 'img' => $row->is_option3_image],
                            4 => ['text' => $row->option4, 'img' => $row->is_option4_image],
                        ];
                    @endphp
                    <tr>
                        <td>{{ $row->question_number ?: $loop->iteration }}</td>
                        <td class="ctb-qtext">
                            {!! question_html_media($row->question_text) !!}
                            @if($row->question_image)
                                <div class="mt-2">
                                    <img src="{{ question_media_url($row->question_image) }}" alt="">
                                </div>
                            @endif
                            <div class="mt-2">
                                @foreach($opts as $n => $opt)
                                    @php
                                        $classes = 'ctb-opt';
                                        if ((string) $row->correct_answer === (string) $n) $classes .= ' is-correct';
                                        if ((string) $row->student_answer === (string) $n) $classes .= ' is-student';
                                    @endphp
                                    <div class="{{ $classes }}">
                                        <strong>{{ $n }}.</strong>
                                        @if($opt['img'])
                                            <img src="{{ question_media_url($opt['text']) }}" alt="Option {{ $n }}">
                                        @else
                                            {!! question_html_media($opt['text']) !!}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td>{{ $row->correct_answer ?: '—' }}</td>
                        <td>
                            @if($row->student_answer === null || $row->student_answer === '')
                                —
                            @elseif((string) $row->student_answer === (string) $row->correct_answer)
                                <span class="text-success">{{ $row->student_answer }}</span>
                            @else
                                <span class="text-danger">{{ $row->student_answer }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $row->result_class }}">{{ $row->result_label }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No questions found for this custom test.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('js_after')
@endsection
