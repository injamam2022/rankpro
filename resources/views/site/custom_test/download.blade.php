<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $custom->name }} - Question Paper</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <style>
        :root {
            --ink: #111827;
            --muted: #667085;
            --line: #d0d5dd;
            --soft: #f2f4f7;
            --brand: #1f1b4d;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 18px 22px 40px;
            color: var(--ink);
            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            font-size: 13.5px;
            line-height: 1.45;
            background: #fff;
        }
        .toolbar {
            position: sticky; top: 0; z-index: 20;
            display: flex; gap: 10px; align-items: center;
            background: #fff; border-bottom: 1px solid var(--line);
            margin: -18px -22px 18px; padding: 10px 22px;
        }
        .toolbar button, .toolbar a {
            border: 1px solid var(--line); background: #f9fafb; border-radius: 6px;
            padding: 8px 12px; text-decoration: none; color: #222; cursor: pointer; font-size: 13px;
        }
        .paper { max-width: 920px; margin: 0 auto; }
        .cover {
            border: 1px solid var(--line); border-radius: 12px; padding: 22px 24px;
            margin-bottom: 22px; background: linear-gradient(180deg, #fbfbfe 0%, #fff 100%);
        }
        .cover-brand {
            display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px;
        }
        .cover-brand img { max-height: 42px; width: auto; }
        .cover-title {
            font-size: 28px; font-weight: 800; letter-spacing: .02em; text-transform: uppercase;
            color: var(--brand); margin: 0 0 10px;
        }
        .meta-row {
            display: flex; flex-wrap: wrap; gap: 8px 14px; color: var(--muted); font-size: 13px; margin-bottom: 14px;
        }
        .meta-pill {
            display: inline-flex; align-items: center; gap: .35rem;
            background: #fff; border: 1px solid var(--line); border-radius: 999px; padding: .28rem .7rem;
            color: #344054; font-weight: 600; font-size: 12px;
        }
        .topics-title {
            font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            color: var(--muted); margin: 0 0 8px;
        }
        .topic-block { margin-bottom: 8px; }
        .topic-subject { font-weight: 700; color: var(--brand); margin-bottom: 2px; }
        .topic-list { color: #475467; font-size: 12.5px; }

        .subject-banner {
            display: flex; align-items: center; justify-content: space-between;
            background: #e7e6e6; border: 1px solid #d8d8d8; border-radius: 4px;
            padding: 8px 14px; margin: 22px 0 14px; font-weight: 700; text-transform: uppercase; font-size: 13px;
        }
        .subject-banner span:nth-child(2) { color: var(--brand); }

        .q {
            margin: 0 0 16px; padding-bottom: 12px; border-bottom: 1px dashed #eaecf0;
            page-break-inside: avoid;
        }
        .q:last-child { border-bottom: 0; }
        .q-head {
            display: grid; grid-template-columns: 28px 1fr; gap: 8px; align-items: start;
            margin-bottom: 8px;
        }
        .q-no { font-weight: 800; color: var(--brand); }
        .stem { color: #101828; }
        .stem p { margin: 0 0 6px; }
        .stem img, .q-img img, .opt-img img {
            max-width: 100%; height: auto; display: block; margin: 8px 0;
        }
        .stem table { max-width: 100%; border-collapse: collapse; margin: 8px 0; }
        .opts { list-style: none; margin: 0; padding: 0; }
        .opts li {
            display: grid; grid-template-columns: 28px 1fr; gap: 8px;
            margin: 0 0 6px; align-items: flex-start;
        }
        .opt-label {
            font-weight: 700; color: #344054; min-width: 28px;
        }
        .opt-body { min-width: 0; }
        .opt-body p { margin: 0; }

        .section-break {
            page-break-before: always; margin-top: 28px; padding-top: 8px;
        }
        .section-title {
            font-size: 22px; font-weight: 800; color: var(--brand); margin: 0 0 14px;
            border-bottom: 2px solid var(--brand); padding-bottom: 6px;
        }
        .answer-subject {
            font-size: 15px; font-weight: 700; margin: 16px 0 8px; color: #101828;
        }
        .answer-grid {
            width: 100%; border-collapse: collapse; margin-bottom: 8px;
        }
        .answer-grid td {
            border: 1px solid #98a2b3; padding: 7px 8px; width: 10%;
            text-align: center; font-size: 12.5px; font-weight: 600;
        }
        .explain {
            margin: 0 0 14px; padding: 10px 12px; border: 1px solid #eaecf0;
            border-radius: 8px; background: #fcfcfd; page-break-inside: avoid;
        }
        .explain-title { font-weight: 700; margin-bottom: 4px; color: #101828; }
        .explain-body { color: #344054; font-size: 12.5px; }
        .explain-body p { margin: 0 0 6px; }

        .footer-note {
            margin-top: 24px; padding-top: 10px; border-top: 1px solid var(--line);
            color: var(--muted); font-size: 11px; display: flex; justify-content: space-between; gap: 12px;
        }

        @media print {
            .toolbar { display: none !important; }
            body { padding: 0; }
            .cover, .q, .explain { break-inside: avoid; }
            a { color: inherit; text-decoration: none; }
        }
    </style>
</head>
<body>
@php
    $optionLabels = [1 => '(1)', 2 => '(2)', 3 => '(3)', 4 => '(4)'];
    $mapAnswer = function ($answer) {
        $a = trim((string) $answer);
        if ($a === '') return '—';
        if (in_array($a, ['1','2','3','4'], true)) return $a;
        $map = ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4', 'a' => '1', 'b' => '2', 'c' => '3', 'd' => '4'];
        return $map[$a] ?? $a;
    };
@endphp

<div class="toolbar">
    <button type="button" onclick="window.print()">Print / Save PDF</button>
    <a href="{{ route('custom_test.ready', $custom->id) }}">Back</a>
</div>

<div class="paper">
    <section class="cover">
        <div class="cover-brand">
            <img src="{{ asset('web/images/logo.png') }}" alt="RankPro" onerror="this.style.display='none'">
            <div style="font-size:12px;color:#667085;text-align:right;">Custom Practice Paper</div>
        </div>
        <h1 class="cover-title">{{ $custom->name }}</h1>
        <div class="meta-row">
            <span class="meta-pill">Type: Custom Test</span>
            <span class="meta-pill">Questions: {{ $custom->question_count }}</span>
            <span class="meta-pill">Total Marks: {{ $totalMarks }}</span>
            <span class="meta-pill">Duration: {{ $custom->duration_minutes }} min</span>
            <span class="meta-pill">Difficulty: {{ $difficulty }}</span>
        </div>

        <div class="topics-title">Topics covered</div>
        @forelse($grouped as $group)
            <div class="topic-block">
                <div class="topic-subject">{{ $group['subject_name'] }}</div>
                <div class="topic-list">
                    @if(!empty($group['topics']))
                        {{ implode(', ', array_slice($group['topics'], 0, 12)) }}{{ count($group['topics']) > 12 ? '…' : '' }}
                    @else
                        Selected chapters / topics
                    @endif
                </div>
            </div>
        @empty
            <div class="topic-list">No questions found.</div>
        @endforelse
    </section>

    @foreach($grouped as $group)
        <div class="subject-banner">
            <span>{{ $custom->name }}</span>
            <span>{{ $group['subject_name'] }}</span>
            <span>Time – {{ $custom->duration_minutes }} Min</span>
        </div>

        @foreach($group['questions'] as $q)
            <div class="q">
                <div class="q-head">
                    <div class="q-no">{{ $q->paper_serial }}.</div>
                    <div class="stem">{!! $q->question_text !!}</div>
                </div>
                @if(!empty($q->question_image))
                    <div class="q-img"><img src="{{ asset('uploads/question/'.$q->question_image) }}" alt=""></div>
                @endif
                <ul class="opts">
                    @foreach([1=>'option1',2=>'option2',3=>'option3',4=>'option4'] as $n => $field)
                        @php
                            $isImg = (int) data_get($q, 'is_'.$field.'_image') === 1;
                            $val = data_get($q, $field);
                        @endphp
                        <li>
                            <span class="opt-label">{{ $optionLabels[$n] }}</span>
                            <div class="opt-body">
                                @if($isImg && $val)
                                    <div class="opt-img"><img src="{{ asset('uploads/question/'.$val) }}" alt=""></div>
                                @else
                                    {!! $val !!}
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endforeach

    <section class="section-break">
        <h2 class="section-title">Answer Key</h2>

        @foreach($grouped as $group)
            <div class="answer-subject">{{ $group['subject_name'] }}</div>
            <table class="answer-grid">
                @foreach(collect($group['questions'])->chunk(10) as $chunk)
                    <tr>
                        @foreach($chunk as $q)
                            <td>{{ $q->paper_serial }}) {{ $mapAnswer($q->answer) }}</td>
                        @endforeach
                        @for($i = $chunk->count(); $i < 10; $i++)
                            <td>&nbsp;</td>
                        @endfor
                    </tr>
                @endforeach
            </table>
        @endforeach
    </section>

    @php
        $hasExplanations = collect($grouped)->flatMap(fn ($g) => $g['questions'])->contains(function ($q) {
            return filled(trim(strip_tags((string) ($q->solution ?? ''))));
        });
    @endphp

    @if($hasExplanations)
        <section class="section-break">
            <h2 class="section-title">Explanations</h2>
            @foreach($grouped as $group)
                @foreach($group['questions'] as $q)
                    @php $sol = trim((string) ($q->solution ?? '')); @endphp
                    @if($sol !== '' && strip_tags($sol) !== '')
                        <div class="explain">
                            <div class="explain-title">
                                Q{{ $q->paper_serial }}. => Correct Option: {{ $mapAnswer($q->answer) }}
                                <span style="font-weight:500;color:#667085;"> ({{ $group['subject_name'] }})</span>
                            </div>
                            <div class="explain-body">{!! $q->solution !!}</div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </section>
    @endif

    <div class="footer-note">
        <div>Generated by RankPro · {{ $generatedAt }}</div>
        <div>{{ $studentMeta }}</div>
    </div>
</div>
</body>
</html>
