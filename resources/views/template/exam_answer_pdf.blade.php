<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $details->name ?? 'Exam' }} - Answer Key</title>
    <style>
        @page { margin-top: 85px; margin-bottom: 40px; margin-left: 30px; margin-right: 30px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; line-height: 1.4; margin: 0; padding: 0; }
        .header-bar { position: fixed; top: -75px; left: 0; right: 0; height: 70px; }
        .top-header-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .exam-info-banner { width: 100%; border-collapse: collapse; background: #f1f3f6; border-top: 1.5px solid #6200ea; border-bottom: 1px solid #d0d5dd; }
        .exam-info-banner td { padding: 5px 12px; font-size: 10.5px; font-weight: bold; color: #333; }
        .footer-bar { position: fixed; bottom: -30px; left: 0; right: 0; height: 20px; border-top: 1px solid #ddd; }
        .subject-box { margin-bottom: 16px; }
        .subject-title {
            background: #eef2ff; border: 1px solid #c7d2fe; border-left: 4px solid #6200ea;
            padding: 6px 12px; font-size: 11px; font-weight: bold; color: #3730a3;
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;
        }
        .ans-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .ans-table tr { page-break-inside: avoid; }
        .ans-table th {
            background: #4338ca; color: #fff; font-size: 10px; font-weight: bold;
            padding: 5px 2px; text-align: center; border: 1px solid #3730a3;
        }
        .ans-table td { border: 1px solid #d0d5dd; text-align: center; padding: 4px 2px; font-size: 10.5px; }
        .ans-table td.q-num-cell { background: #f8fafc; font-weight: bold; color: #475569; }
        .ans-table td.ans-val-cell { background: #fff; font-weight: bold; color: #6200ea; }
        .ans-badge {
            background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; border-radius: 50%;
            display: inline-block; width: 18px; height: 18px; line-height: 12px; text-align: center;
            font-weight: bold; font-size: 10px;
        }
        .ft { font-size: 8.5px; color: #737373; padding-top: 4px; }
    </style>
</head>
<body>
@php
    $grouped_subjects = [];
    $global_q = 1;
    $total_questions_count = 0;
    foreach ($subject_list as $s) {
        $s_name = $s->subject_name ?? 'Subject';
        if (!isset($grouped_subjects[$s_name])) {
            $grouped_subjects[$s_name] = ['subject_name' => $s_name, 'questions' => []];
        }
        foreach (collect($s->question_list ?? []) as $q) {
            $q_copy = clone $q;
            $q_copy->global_q_num = $global_q++;
            $grouped_subjects[$s_name]['questions'][] = $q_copy;
            $total_questions_count++;
        }
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
            <td style="width:70%; text-align:left; text-transform:uppercase;">{{ $details->name ?? '' }}</td>
            <td style="width:30%; text-align:right;">Total Questions: {{ $total_questions_count }}</td>
        </tr>
    </table>
</div>
<div class="footer-bar"><div class="ft">RankPro</div></div>

@foreach($grouped_subjects as $subject_data)
    @php
        $questions = $subject_data['questions'];
        $subjectQCount = count($questions);
        if ($subjectQCount === 0) {
            continue;
        }
        $firstQNum = $questions[0]->global_q_num;
        $lastQNum = $questions[$subjectQCount - 1]->global_q_num;
        $pairsPerRow = 5;
        $chunks = array_chunk($questions, $pairsPerRow);
    @endphp
    <div class="subject-box">
        <div class="subject-title">
            Section: {{ $subject_data['subject_name'] }}
            <span style="font-weight:normal; font-size:10px; color:#6366f1; text-transform:none; margin-left:8px;">
                (Questions: {{ $firstQNum }} – {{ $lastQNum }})
            </span>
        </div>
        <table class="ans-table">
            <thead>
                <tr>
                    @for($c = 0; $c < $pairsPerRow; $c++)
                        <th style="width:8%;">Q.No</th>
                        <th style="width:12%;">Answer</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($chunks as $rowQuestions)
                    <tr>
                        @for($c = 0; $c < $pairsPerRow; $c++)
                            @if(isset($rowQuestions[$c]))
                                @php $item = $rowQuestions[$c]; @endphp
                                <td class="q-num-cell">{{ $item->global_q_num }}</td>
                                <td class="ans-val-cell"><span class="ans-badge">{{ $item->answer ?? '-' }}</span></td>
                            @else
                                <td class="q-num-cell" style="background:#fff; border:1px solid #f1f5f9;">&nbsp;</td>
                                <td class="ans-val-cell" style="background:#fff; border:1px solid #f1f5f9;">&nbsp;</td>
                            @endif
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach
</body>
</html>
