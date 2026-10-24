<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $details->name ?? 'Exam Question Paper' }}</title>
    <style>
        @page { margin-top: 78px; margin-bottom: 36px; margin-left: 28px; margin-right: 28px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; line-height: 1.45; margin: 0; padding: 0; }
        p, h1, h2, h3, h4, ul, ol, div { margin: 0; padding: 0; }
        table { border-collapse: collapse; }
        .header-bar { position: fixed; top: -68px; left: 0; right: 0; height: 62px; }
        .top-header-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .exam-info-banner { width: 100%; border-collapse: collapse; background: #f1f3f6; border-top: 1.5px solid #6200ea; border-bottom: 1px solid #d0d5dd; }
        .exam-info-banner td { padding: 6px 8px; font-size: 10.5px; font-weight: bold; color: #333; white-space: nowrap; }
        .exam-info-banner .exam-name { white-space: normal; }
        .footer-bar { position: fixed; bottom: -28px; left: 0; right: 0; height: 20px; border-top: 1px solid #ddd; }
        .subject-page-break { page-break-before: always; }
        .subject-banner {
            background: #eef2ff; border: 1px solid #c7d2fe; border-left: 4px solid #6200ea;
            padding: 6px 12px; font-size: 12px; font-weight: bold; color: #3730a3;
            text-transform: uppercase; letter-spacing: 0.4px; margin: 0 0 6px;
            page-break-after: avoid;
        }
        .q-block { padding: 5px 0 6px; border-bottom: 1px solid #eceff3; margin: 0; }
        .q-head-table { width: 100%; border-collapse: collapse; }
        .q-num { width: 26px; vertical-align: top; font-weight: bold; font-size: 12px; padding: 0 6px 0 0; }
        .q-content { vertical-align: top; font-size: 12px; }
        .q-content p, .q-content div, .opt-body p, .opt-body div { margin: 0; padding: 0; }
        .q-content table, .opt-body table { width: auto; max-width: 100%; }
        .q-content img { vertical-align: middle; }
        .q-diagram { max-width: 88%; max-height: 170px; height: auto; display: block; margin: 6px 0 4px; }
        .options-grid { width: 100%; border-collapse: collapse; margin: 5px 0 0 26px; }
        .opt-cell { width: 50%; vertical-align: top; padding: 2px 12px 2px 0; }
        .opt-inner { width: 100%; border-collapse: collapse; }
        .opt-num { width: 24px; vertical-align: top; font-weight: bold; font-size: 11px; color: #334155; padding-right: 4px; }
        .opt-body { vertical-align: top; font-size: 11px; color: #1e293b; }
        .opt-body img { vertical-align: middle; }
        .opt-diagram { max-width: 95%; max-height: 100px; height: auto; display: block; margin: 2px 0; }
        .ft { font-size: 8.5px; color: #737373; padding-top: 4px; }
    </style>
</head>
<body>
@php
    if (!function_exists('exam_pdf_image_src')) {
        function exam_pdf_image_src($filename) {
            $filename = trim((string) $filename);
            if ($filename === '') {
                return '';
            }
            if (is_file($filename)) {
                return str_replace('\\', '/', $filename);
            }
            $basename = basename($filename);
            foreach ([
                public_path('uploads/question/'.$basename),
                public_path('uploads/option/'.$basename),
            ] as $path) {
                if (is_file($path)) {
                    return str_replace('\\', '/', $path);
                }
            }
            return '';
        }
    }
    $q_global_num = 1;
    $sections = [];
    foreach ($subject_list as $subject) {
        $qs = collect($subject->question_list ?? [])->filter();
        if ($qs->isEmpty()) {
            continue;
        }
        $name = trim((string) ($subject->subject_name ?? 'Questions'));
        if ($name === '') {
            $name = 'Questions';
        }
        if (! isset($sections[$name])) {
            $sections[$name] = collect();
        }
        $sections[$name] = $sections[$name]->concat($qs);
    }
    $pdf_question_count = collect($sections)->sum(fn ($qs) => $qs->count());
    if ($pdf_question_count < 1) {
        $pdf_question_count = (int) ($details->no_of_question ?? 0);
    }
    $pdf_total_marks = $details->totals_marks_for_exam ?? '';
    if ($pdf_total_marks === '' || $pdf_total_marks === null) {
        $per = (int) ($details->marks_per_question ?? 0);
        $pdf_total_marks = $per > 0 ? $per * $pdf_question_count : '';
    }
@endphp

<div class="header-bar">
    <table class="top-header-table">
        <tr>
            <td style="width:50%; vertical-align:middle;">
                @if(!empty($pdf_logo))
                    <img src="{{ $pdf_logo }}" height="24" alt="RankPro">
                @else
                    <strong style="color:#6200ea; font-size:15px;">RankPro</strong>
                @endif
            </td>
            <td style="width:50%; text-align:right; vertical-align:middle; font-size:9.5px; color:#666;">
                RankPro – Educational Advisory Solutions
            </td>
        </tr>
    </table>
    <table class="exam-info-banner">
        <tr>
            <td class="exam-name" style="width:46%; text-align:left; text-transform:uppercase;">{{ $details->name ?? '' }}</td>
            <td style="width:18%; text-align:center;">Questions: {{ $pdf_question_count }}</td>
            <td style="width:18%; text-align:center;">Marks: {{ $pdf_total_marks }}</td>
            <td style="width:18%; text-align:right;">Time: {{ $details->total_time_for_exam ?? '' }} Min</td>
        </tr>
    </table>
</div>
<div class="footer-bar"><div class="ft">RankPro</div></div>

@foreach($sections as $section_name => $questions)
    <div class="subject-section{{ $loop->first ? '' : ' subject-page-break' }}">
        <div class="subject-banner">Section: {{ $section_name }}</div>
        @foreach($questions as $q)
            @include('template.partials.exam_pdf_question', ['q' => $q, 'num' => $q_global_num++])
        @endforeach
    </div>
@endforeach
</body>
</html>
