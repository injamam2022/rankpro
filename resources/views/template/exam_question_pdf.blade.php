<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; line-height: 1.35; margin: 0; }
        p { margin: 0 0 4px 0; padding: 0; }
        table { border-collapse: collapse; width: 100%; }
        td { vertical-align: top; }
        .hdr { background: #E7E6E6; font-weight: 600; font-size: 12px; padding: 6px 10px; text-transform: uppercase; }
        .q { margin: 0 0 12px 0; page-break-inside: avoid; }
        .q-num { width: 28px; font-weight: 600; }
        .q-body img { max-width: 220px; max-height: 140px; height: auto; }
        .opt td { padding: 2px 0; }
        .opt-n { width: 24px; }
        .ft { margin-top: 16px; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 6px; }
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
    $question_count = 1;
@endphp

@foreach($subject_list as $v1)
    <table style="margin-bottom:10px;">
        <tr>
            <td class="hdr" style="width:34%;">{{ $details->name }}</td>
            <td class="hdr" style="width:33%; text-align:center;">{{ $v1->subject_name }}</td>
            <td class="hdr" style="width:33%; text-align:right;">Time – {{ $details->total_time_for_exam }} Min</td>
        </tr>
    </table>

    @foreach($v1->question_list as $v2)
        <table class="q">
            <tr>
                <td class="q-num">{{ $question_count }}.</td>
                <td class="q-body">
                    <div>{!! $v2->question_text !!}</div>
                    @php $qImgSrc = !empty($v2->question_image) ? exam_pdf_image_src($v2->question_image) : ''; @endphp
                    @if($qImgSrc !== '')
                        <div style="text-align:center;margin:6px 0;">
                            <img src="{{ $qImgSrc }}" alt="">
                        </div>
                    @endif
                    <table class="opt">
                        @foreach([1,2,3,4] as $optN)
                            @php
                                $optKey = 'option'.$optN;
                                $isImgKey = 'is_option'.$optN.'_image';
                                $optSrc = !empty($v2->$isImgKey) ? exam_pdf_image_src($v2->$optKey) : '';
                            @endphp
                            <tr>
                                <td class="opt-n">({{ $optN }})</td>
                                <td>
                                    @if(!empty($v2->$isImgKey))
                                        @if($optSrc !== '')
                                            <img src="{{ $optSrc }}" alt="">
                                        @endif
                                    @else
                                        {!! $v2->$optKey !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
        @php $question_count++; @endphp
    @endforeach
@endforeach

<div class="ft">RankPro</div>
</body>
</html>
