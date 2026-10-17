<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $details->name ?? 'Exam Question Paper' }}</title>
    <style>
        @page { margin-top: 85px; margin-bottom: 45px; margin-left: 25px; margin-right: 25px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; line-height: 1.45; margin: 0; padding: 0; }
        p { margin: 0 0 3px 0; }
        .header-bar { position: fixed; top: -75px; left: 0; right: 0; height: 70px; }
        .top-header-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .exam-info-banner { width: 100%; border-collapse: collapse; background: #f1f3f6; border-top: 1.5px solid #6200ea; border-bottom: 1px solid #d0d5dd; }
        .exam-info-banner td { padding: 5px 10px; font-size: 10.5px; font-weight: bold; color: #333; }
        .footer-bar { position: fixed; bottom: -35px; left: 0; right: 0; height: 25px; border-top: 1px solid #ddd; }
        .exam-grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .exam-row { page-break-inside: avoid; }
        .q-cell { width: 50%; vertical-align: top; padding: 8px 12px 10px; border-bottom: 1px dashed #e2e4e8; }
        .q-cell-left { border-right: 1px solid #d0d5dd; padding-right: 14px; }
        .q-cell-right { padding-left: 14px; }
        .subject-page-break { page-break-before: always; }
        .subject-divider-cell {
            padding: 8px 10px; background: #eef2ff; border-top: 2px solid #6200ea; border-bottom: 1.5px solid #c7d2fe;
            font-size: 12px; font-weight: bold; color: #4338ca; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;
        }
        .q-head-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .q-num { width: 26px; vertical-align: top; font-weight: bold; font-size: 11px; }
        .q-content { vertical-align: top; font-size: 11px; }
        .q-content img, .q-diagram { max-width: 95%; max-height: 130px; height: auto; }
        .options-table { width: 100%; border-collapse: collapse; margin-top: 4px; margin-left: 6px; }
        .opt-row td { padding: 2.5px 0; vertical-align: top; }
        .opt-num { width: 22px; font-weight: bold; font-size: 10.5px; color: #444; }
        .opt-body { font-size: 10.5px; color: #333; }
        .opt-body img, .opt-diagram { max-width: 90%; max-height: 90px; height: auto; }
        .ft { font-size: 8.5px; color: #737373; padding-top: 4px; }
    </style>
</head>
<body>
@php
    if (!function_exists('exam_pdf_image_src')) {
        function exam_pdf_image_src($filename) {
            $filename = basename((string) $filename);
            $path = public_path('uploads/question/'.$filename);
            if ($filename === '' || !is_file($path)) {
                return '';
            }
            return str_replace('\\', '/', $path);
        }
    }
    $q_global_num = 1;
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
            <td style="width:70%; text-align:left; text-transform:uppercase;">{{ $details->name ?? '' }}</td>
            <td style="width:30%; text-align:right;">Time: {{ $details->total_time_for_exam ?? '' }} Min</td>
        </tr>
    </table>
</div>
<div class="footer-bar"><div class="ft">RankPro</div></div>

@foreach($subject_list as $sub_idx => $subject)
    @php $q_list = collect($subject->question_list ?? [])->values(); @endphp
    <table class="exam-grid {{ $sub_idx > 0 ? 'subject-page-break' : '' }}">
        <tr>
            <td colspan="2" class="subject-divider-cell">Section: {{ $subject->subject_name }}</td>
        </tr>
        @for($i = 0; $i < $q_list->count(); $i += 2)
            @php
                $q_left = $q_list->get($i);
                $q_right = $q_list->get($i + 1);
            @endphp
            <tr class="exam-row">
                <td class="q-cell q-cell-left">
                    @include('template.partials.exam_pdf_question', ['q' => $q_left, 'num' => $q_global_num++])
                </td>
                <td class="q-cell q-cell-right">
                    @if($q_right)
                        @include('template.partials.exam_pdf_question', ['q' => $q_right, 'num' => $q_global_num++])
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
        @endfor
    </table>
@endforeach
</body>
</html>
