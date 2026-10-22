@extends('layouts.backend')

@section('css_after')
<style>
    .sa-wrap { margin-top: 2.5rem; }
    .sa-head { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; margin-bottom:1.25rem; }
    .sa-head h1 { font-size:1.4rem; font-weight:500; margin:0 0 .25rem; }
    .sa-sub { color:#667085; font-size:.9rem; margin:0; }
    .sa-back { color:#6200ea; text-decoration:none; font-size:.875rem; }
    .sa-back:hover { text-decoration:underline; }
    .result-card { background:#fff; border:1px solid #eaecf0; border-radius:16px; padding:14px 14px 10px; height:100%; margin-bottom:1rem; }
    .result-card-title { color:#667085; font-size:13px; font-weight:500; margin-bottom:8px; }
    .result-card .table-wrapper { border:1px solid #eaecf0; border-radius:14px; overflow:hidden; }
    .result-card .table { margin:0; font-size:12px; }
    .result-card .table th, .result-card .table td { border:0; padding:.55rem .45rem; }
    .result-card .row-topper td { background:#d6dffe; }
    .result-card .row-average td { background:#ffdbed; }
    .result-card .row-your td { background:#e4e4e4; }
    .label-pill { background:#f4f6fa; border-radius:16px; font-weight:500; font-size:12px; color:#101828; min-width:68px; height:32px; display:inline-flex; justify-content:center; align-items:center; padding:0 8px; }
    .topper-chart { padding:14px; min-height:170px; }
    .chart-Info { display:flex; justify-content:space-between; border-radius:10px; padding:8px 10px; margin-bottom:8px; font-size:12px; font-weight:500; }
    .bg-pink { background:#ffdbed; }
    .bg-blue { background:#d6dffe; }
    .bg-gray { background:#e4e4e4; }
    .topperAvg-title { text-align:center; font-size:12px; margin-top:6px; color:#344054; }
</style>
@endsection

@section('content')
@php
    $studentName = trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'Student #'.$user->id;
    $cards = [
        ['Easy', $easy_toper_list, $avg_easy_toper_list, $my_easy_toper_list],
        ['Medium', $medium_toper_list, $avg_medium_toper_list, $my_medium_toper_list],
        ['Hard', $hard_toper_list, $avg_hard_toper_list, $my_hard_toper_list],
    ];
    $stat = function ($row, $key, $int = true) {
        if (!$row || !isset($row->$key) || $row->$key === null) {
            return '—';
        }
        return $int ? (int) $row->$key : $row->$key;
    };
@endphp
<div class="container-xl px-5 sa-wrap">
    <div class="sa-head">
        <div>
            <h1>{{ $studentName }} — exam analytics</h1>
            <p class="sa-sub">{{ $total_exam_attemped }} attempt(s) · {{ $total_question_attemped }} answers · overall rank {{ $your_score }}</p>
        </div>
        <a class="sa-back" href="{{ route('admin.student') }}">← Back to students</a>
    </div>

    <div class="row">
        @foreach($cards as $card)
            @php [$title, $topper, $average, $you] = $card; @endphp
            <div class="col-sm-4">
                <div class="result-card">
                    <div class="result-card-title">{{ $title }}</div>
                    <div class="table-wrapper table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th></th>
                                    <th>Questions</th>
                                    <th>Right</th>
                                    <th>Wrong</th>
                                    <th>Skipped</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="row-topper text-center">
                                    <td><span class="label-pill">Topper</span></td>
                                    <td>{{ $stat($topper, 'total_question') }}</td>
                                    <td>{{ $stat($topper, 'right_answer') }}</td>
                                    <td>{{ $stat($topper, 'wrong_answer') }}</td>
                                    <td>{{ $stat($topper, 'skip_answer') }}</td>
                                    <td class="fw-semibold">{{ $stat($topper, 'total_mark') }}</td>
                                </tr>
                                <tr class="row-average text-center">
                                    <td><span class="label-pill">Average</span></td>
                                    <td>{{ $stat($average, 'total_question') }}</td>
                                    <td>{{ $stat($average, 'right_answer') }}</td>
                                    <td>{{ $stat($average, 'wrong_answer') }}</td>
                                    <td>{{ $stat($average, 'skip_answer') }}</td>
                                    <td class="fw-semibold">{{ $stat($average, 'total_mark') }}</td>
                                </tr>
                                <tr class="row-your text-center">
                                    <td><span class="label-pill">Student</span></td>
                                    <td>{{ $stat($you, 'total_question') }}</td>
                                    <td>{{ $stat($you, 'right_answer') }}</td>
                                    <td>{{ $stat($you, 'wrong_answer') }}</td>
                                    <td>{{ $stat($you, 'skip_answer') }}</td>
                                    <td class="fw-semibold">{{ $stat($you, 'total_mark') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="result-card">
                <div class="result-card-title">Topper vs Average vs {{ $studentName }}</div>
                <div class="table-wrapper topper-chart">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-Info bg-pink">
                                <div>Topper</div>
                                <div>
                                    @if($topper_data && $topper_data->totals_marks_for_exam)
                                        {{ (int) $topper_data->total_mark }}/{{ (int) $topper_data->totals_marks_for_exam }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="chart-Info bg-blue">
                                <div>Average</div>
                                <div>
                                    @if($average_data && $average_data->totals_marks_for_exam)
                                        {{ (int) $average_data->total_mark }}/{{ (int) $average_data->totals_marks_for_exam }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="chart-Info bg-gray" style="margin-bottom:0;">
                                <div>{{ $studentName }}</div>
                                <div>
                                    @if($you_data && $you_data->totals_marks_for_exam)
                                        {{ (int) $you_data->total_mark }}/{{ (int) $you_data->totals_marks_for_exam }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <canvas id="comparisonChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="result-card">
                <div class="result-card-title">Accuracy percentage</div>
                <div class="table-wrapper topper-chart">
                    <div class="row">
                        <div class="col-4">
                            <canvas id="topperAcc"></canvas>
                            <div class="topperAvg-title">Topper</div>
                        </div>
                        <div class="col-4">
                            <canvas id="avgAcc"></canvas>
                            <div class="topperAvg-title">Average</div>
                        </div>
                        <div class="col-4">
                            <canvas id="youAcc"></canvas>
                            <div class="topperAvg-title">Student</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="result-card">
                <div class="result-card-title">Score</div>
                <div class="table-wrapper topper-chart">
                    <canvas id="scoreChart"></canvas>
                    <div class="topperAvg-title">
                        @if($you_data && $you_data->totals_marks_for_exam)
                            {{ (int) $you_data->total_mark }}/{{ (int) $you_data->totals_marks_for_exam }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>ID</th>
                <th>Exam</th>
                <th>Total answered</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Rank</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($list as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->total_answer }}</td>
                    <td>{{ $row->total_number }}</td>
                    <td>{{ $row->percentage }}</td>
                    <td>{{ $row->rank }}</td>
                    <td>
                        <a href="{{ route('admin.student.exam_details', ['id' => $row->id, 'user_id' => $row->user_id]) }}" class="btn btn-danger btn-sm mr-1" title="View answers">
                            <i class="material-icons">visibility</i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No exam attempts yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('js_after')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    var g = {
        topper: {{ (int) ($chart['topper'] ?? 0) }},
        average: {{ (int) ($chart['average'] ?? 0) }},
        you: {{ (int) ($chart['you'] ?? 0) }},
        yourMark: {{ (float) ($chart['your_mark'] ?? 0) }},
        totalMark: {{ (float) ($chart['total_mark'] ?? 0) }}
    };

    function donut(id, value, color) {
        var el = document.getElementById(id);
        if (!el) return;
        var safe = Math.max(0, Math.min(100, Number(value) || 0));
        new Chart(el, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [safe, 100 - safe],
                    backgroundColor: [color, '#e9ecef'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '80%',
                plugins: { legend: { display: false }, tooltip: { enabled: false }, centerText: { score: safe } }
            },
            plugins: [{
                id: 'centerText',
                afterDraw: function (chart) {
                    var score = chart.options.plugins.centerText.score;
                    var meta = chart.getDatasetMeta(0);
                    if (!meta || !meta.data || !meta.data.length) return;
                    var ctx = chart.ctx;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.font = '16px Arial';
                    ctx.fillStyle = '#000';
                    ctx.fillText(score + ' %', meta.data[0].x, meta.data[0].y + 6);
                    ctx.restore();
                }
            }]
        });
    }

    var comparison = document.getElementById('comparisonChart');
    if (comparison) {
        new Chart(comparison, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [g.topper, g.average, g.you],
                    backgroundColor: ['#ff007f', '#0d6efd', '#6c757d'],
                    borderWidth: 0
                }]
            },
            options: { cutout: '65%', plugins: { legend: { display: false } } }
        });
    }

    donut('topperAcc', g.topper, '#0d6efd');
    donut('avgAcc', g.average, '#ff007f');
    donut('youAcc', g.you, '#6c757d');

    var score = document.getElementById('scoreChart');
    if (score) {
        var remain = Math.max(0, g.totalMark - g.yourMark);
        new Chart(score, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [Math.max(0, g.yourMark), remain],
                    backgroundColor: ['#0d6efd', '#e9ecef'],
                    borderWidth: 0
                }]
            },
            options: { cutout: '70%', plugins: { legend: { display: false } } }
        });
    }
})();
</script>
@endsection
