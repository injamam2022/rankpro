@extends('layouts.backend')

@section('css_after')
<style>
    .spr-page { background:#f3f6fb; border-radius:18px; padding:1.25rem 1.25rem 2rem; min-height:calc(100vh - 110px); }
    .spr-top { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; margin-bottom:1rem; flex-wrap:wrap; }
    .spr-kicker { font-size:11px; letter-spacing:.08em; text-transform:uppercase; color:#6b7a90; margin:0 0 .2rem; font-weight:700; }
    .spr-top h1 { font-size:1.55rem; font-weight:800; color:#1b2a4a; margin:0; }
    .spr-sub { color:#6b7a90; margin:.2rem 0 0; font-size:.92rem; }
    .spr-meta { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .spr-chip { background:#fff; border:1px solid #e4ebf5; border-radius:999px; padding:8px 12px; font-size:12px; color:#334155; display:inline-flex; align-items:center; gap:6px; }
    .spr-back { color:#5b4dff; text-decoration:none; font-size:.875rem; font-weight:600; }
    .spr-back:hover { text-decoration:underline; }
    .spr-hero { background:#fff; border-radius:18px; padding:16px; box-shadow:0 8px 24px rgba(28,55,90,.06); display:grid; grid-template-columns:260px 1fr; gap:14px; margin-bottom:14px; }
    .spr-student { display:flex; gap:12px; align-items:center; padding:6px 8px; }
    .spr-avatar { width:72px; height:72px; border-radius:50%; object-fit:cover; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-weight:800; color:#1d4ed8; font-size:22px; overflow:hidden; flex-shrink:0; }
    .spr-avatar img { width:100%; height:100%; object-fit:cover; }
    .spr-student h2 { margin:0; font-size:1.15rem; font-weight:800; color:#1b2a4a; }
    .spr-student p { margin:2px 0 0; color:#6b7a90; font-size:13px; }
    .spr-kpis { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:10px; }
    .spr-kpi { border-radius:14px; padding:12px 12px 10px; min-height:92px; }
    .spr-kpi span { display:block; font-size:12px; font-weight:700; color:#475569; }
    .spr-kpi strong { display:block; font-size:1.35rem; font-weight:800; margin-top:4px; color:#0f172a; line-height:1.1; }
    .spr-kpi small { color:#64748b; font-size:12px; }
    .kpi-score { background:#e7f8ee; }
    .kpi-grade { background:#efe9ff; }
    .kpi-rank { background:#fff4d6; }
    .kpi-tests { background:#e8f1ff; }
    .kpi-acc { background:#e6fbf4; }
    .spr-grid { display:grid; grid-template-columns:1.15fr .85fr; gap:14px; margin-bottom:14px; }
    .spr-card { background:#fff; border-radius:18px; box-shadow:0 8px 24px rgba(28,55,90,.06); padding:16px; height:100%; }
    .spr-card h3 { margin:0 0 12px; font-size:1rem; font-weight:800; color:#1b2a4a; }
    .spr-insights { list-style:none; padding:0; margin:10px 0 0; }
    .spr-insights li { display:flex; gap:8px; align-items:flex-start; font-size:13px; color:#334155; margin-bottom:8px; }
    .spr-insights i { font-size:16px; color:#16a34a; }
    .spr-table { width:100%; border-collapse:separate; border-spacing:0; font-size:13px; }
    .spr-table th { color:#64748b; font-weight:700; font-size:12px; padding:8px 8px; border-bottom:1px solid #eef2f7; }
    .spr-table td { padding:9px 8px; border-bottom:1px solid #f1f5f9; color:#1e293b; vertical-align:middle; }
    .spr-table tr:last-child td { border-bottom:0; }
    .spr-pill { display:inline-flex; align-items:center; border-radius:999px; padding:3px 9px; font-size:11px; font-weight:700; }
    .tone-excellent { background:#dcfce7; color:#166534; }
    .tone-very-good { background:#d1fae5; color:#047857; }
    .tone-good { background:#dbeafe; color:#1d4ed8; }
    .tone-improve { background:#ffedd5; color:#c2410c; }
    .tone-strong { background:#dcfce7; color:#166534; }
    .tone-practice { background:#fef3c7; color:#b45309; }
    .tone-weak { background:#fee2e2; color:#b91c1c; }
    .tone-high { background:#fee2e2; color:#b91c1c; }
    .tone-medium { background:#fef3c7; color:#b45309; }
    .tone-low { background:#dcfce7; color:#166534; }
    .spr-chart { height:210px; }
    .spr-chart-sm { height:150px; }
    .spr-activity td:nth-child(4) { font-weight:700; }
    .spr-foot { margin-top:8px; background:linear-gradient(90deg,#f3e8ff,#e0f2fe); border-radius:18px; padding:16px 18px; display:flex; justify-content:space-between; gap:12px; align-items:center; }
    .spr-foot h4 { margin:0; font-weight:800; color:#5b21b6; }
    .spr-foot p { margin:4px 0 0; color:#475569; font-size:13px; }
    .spr-empty { color:#94a3b8; text-align:center; padding:18px 8px; }
    .spr-pager { display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; margin-top:10px; }
    .spr-pager__meta { font-size:12px; color:#64748b; }
    .spr-pager .pagination { margin:0; gap:4px; }
    .spr-pager .page-link { padding:4px 9px; font-size:12px; border-radius:8px; }
    @media (max-width: 1100px) {
        .spr-hero, .spr-grid, .spr-kpis { grid-template-columns:1fr; }
    }
</style>
@endsection

@section('content')
@php
    $studentName = trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'Student #'.$user->id;
    $r = $report;
    $initials = collect(explode(' ', $studentName))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
    $classLine = trim(implode(' | ', array_filter([$user->class_name ?? null, $user->section_name ? 'Section '.$user->section_name : null])));
    $nextGoal = $r['weakest']
        ? 'Lift '.$r['weakest']['name'].' toward 90% average'
        : 'Reach 90% overall score';
@endphp
<div class="container-xl px-5 mt-10">
<div class="spr-page">
    <div class="spr-top">
        <div>
            <p class="spr-kicker">Student analytics</p>
            <h1>Student Performance Report</h1>
            <p class="spr-sub">Track progress · Identify strengths · Plan the next goal</p>
        </div>
        <div class="spr-meta">
            <span class="spr-chip"><i class="material-icons" style="font-size:16px;">event</i> Current Academic Year ({{ $r['academic_year'] }})</span>
            <a class="spr-back" href="{{ route('admin.student') }}">← Back to students</a>
        </div>
    </div>

    <div class="spr-hero">
        <div class="spr-student">
            <div class="spr-avatar">
                @if(!empty($user->profile_img))
                    <img src="{{ asset('uploads/profileImage/'.$user->profile_img) }}" alt="">
                @else
                    {{ $initials ?: 'S' }}
                @endif
            </div>
            <div>
                <h2>{{ $studentName }}</h2>
                <p>Roll No: {{ $user->rankpro_id ?: $user->id }}</p>
                <p>{{ $classLine !== '' ? $classLine : ($user->school_name ?: 'RankPro student') }}</p>
            </div>
        </div>
        <div class="spr-kpis">
            <div class="spr-kpi kpi-score">
                <span>Overall Score</span>
                <strong>{{ (int) $r['obtained'] }} / {{ (int) max(1, $r['max_marks']) }}</strong>
                <small>{{ $r['overall_pct'] }}%</small>
            </div>
            <div class="spr-kpi kpi-grade">
                <span>Grade</span>
                <strong>{{ $r['grade'] }}</strong>
                <small>{{ $r['grade_status'] }}</small>
            </div>
            <div class="spr-kpi kpi-rank">
                <span>Class Rank</span>
                <strong>{{ $r['rank'] }} / {{ $r['cohort'] }}</strong>
                <small>among students with attempts</small>
            </div>
            <div class="spr-kpi kpi-tests">
                <span>Tests Attempted</span>
                <strong>{{ $r['attempts'] }} / {{ max(1, $r['assigned']) }}</strong>
                <small>assigned tests</small>
            </div>
            <div class="spr-kpi kpi-acc">
                <span>Accuracy</span>
                <strong>{{ $r['accuracy'] }}%</strong>
                <small>{{ $r['right'] }} / {{ max(1, $r['answered']) }} answered</small>
            </div>
        </div>
    </div>

    <div class="spr-grid">
        <div class="spr-card">
            <h3>Performance Overview</h3>
            <div class="spr-chart"><canvas id="sprTrend"></canvas></div>
            <ul class="spr-insights">
                <li><i class="material-icons">trending_up</i> {{ $r['improvement'] >= 0 ? '+' : '' }}{{ $r['improvement'] }}% from first to latest test</li>
                <li><i class="material-icons">star</i> Strongest subject: {{ $r['strongest']['name'] ?? '—' }}{{ !empty($r['strongest']) ? ' · '.$r['strongest']['percentage'].'%' : '' }}</li>
                <li><i class="material-icons">warning</i> Weakest subject: {{ $r['weakest']['name'] ?? '—' }}{{ !empty($r['weakest']) ? ' · '.$r['weakest']['percentage'].'%' : '' }}</li>
                <li><i class="material-icons">flag</i> Next goal: {{ $nextGoal }}</li>
            </ul>
        </div>
        <div class="spr-card">
            <h3>Subject-wise Performance</h3>
            <div class="table-responsive">
                <table class="spr-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Total</th>
                            <th>%</th>
                            <th>Grade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($r['subjects'] as $subject)
                            <tr>
                                <td>{{ $subject['name'] }}</td>
                                <td>{{ $subject['obtained'] }}</td>
                                <td>{{ $subject['max'] }}</td>
                                <td>{{ $subject['percentage'] }}%</td>
                                <td>{{ $subject['grade'] }}</td>
                                <td><span class="spr-pill tone-{{ $subject['tone'] }}">{{ $subject['status'] }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="spr-empty">No subject scores yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="spr-grid">
        <div class="spr-card" id="spr-tests">
            <h3>Test-wise Performance</h3>
            <div class="table-responsive">
                <table class="spr-table">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Date</th>
                            <th>Marks</th>
                            <th>%</th>
                            <th>Time</th>
                            <th>Result</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($r['tests'] as $test)
                            <tr>
                                <td>{{ $test['name'] }}</td>
                                <td>{{ $test['date'] ? \Carbon\Carbon::parse($test['date'])->format('d M Y') : '—' }}</td>
                                <td>{{ (int) $test['marks'] }}{{ $test['max'] ? '/'.(int) $test['max'] : '' }}</td>
                                <td>{{ (int) $test['percentage'] }}%</td>
                                <td>{{ $test['time'] }}</td>
                                <td><span class="spr-pill tone-{{ $test['result_class'] }}">{{ $test['result'] }}</span></td>
                                <td>
                                    <a href="{{ route('admin.student.exam_details', ['id' => $test['id'], 'user_id' => $test['user_id']]) }}" class="spr-back">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="spr-empty">No exam attempts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('admin.student._spr_pager', ['list' => $r['tests']])
            <div class="spr-chart-sm mt-3"><canvas id="sprTests"></canvas></div>
        </div>
        <div class="spr-card" id="spr-topics">
            <h3>Topic-wise Strengths & Weaknesses</h3>
            <div class="table-responsive">
                <table class="spr-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Chapter / Topic</th>
                            <th>Accuracy</th>
                            <th>Attempted</th>
                            <th>Correct</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($r['topics'] as $topic)
                            <tr>
                                <td>{{ $topic['subject'] }}</td>
                                <td>{{ $topic['topic'] }}</td>
                                <td>{{ $topic['accuracy'] }}%</td>
                                <td>{{ $topic['attempted'] }}</td>
                                <td>{{ $topic['correct'] }}</td>
                                <td><span class="spr-pill tone-{{ $topic['tone'] }}">{{ $topic['status'] }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="spr-empty">Not enough topic data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('admin.student._spr_pager', ['list' => $r['topics']])
        </div>
    </div>

    <div class="spr-grid">
        <div class="spr-card" id="spr-actions">
            <h3>Recent Improvement & Action Plan</h3>
            <div class="table-responsive">
                <table class="spr-table">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th>Current</th>
                            <th>Target</th>
                            <th>Recommended action</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($r['actions'] as $action)
                            <tr>
                                <td>{{ $action['area'] }}</td>
                                <td>{{ $action['current'] }}%</td>
                                <td>{{ $action['target'] }}%</td>
                                <td>{{ $action['action'] }}</td>
                                <td><span class="spr-pill tone-{{ strtolower($action['priority']) }}">{{ $action['priority'] }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="spr-empty">Attempt more tests to build an action plan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('admin.student._spr_pager', ['list' => $r['actions']])
        </div>
        <div class="spr-card">
            <h3>Learning Activity</h3>
            <table class="spr-table spr-activity">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>Current</th>
                        <th>Target</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tests completed</td>
                        <td>{{ $r['attempts'] }} / {{ max(1, $r['assigned']) }}</td>
                        <td>{{ max(1, $r['assigned']) }}</td>
                        <td>{{ $r['attempts'] >= max(1, $r['assigned']) ? 'On Track' : 'In Progress' }}</td>
                    </tr>
                    <tr>
                        <td>Questions answered</td>
                        <td>{{ $r['answered'] }}</td>
                        <td>{{ max($r['attempted_questions'], $r['answered']) }}</td>
                        <td>{{ $r['answered'] > 0 ? 'In Progress' : 'Needs Attention' }}</td>
                    </tr>
                    <tr>
                        <td>Accuracy</td>
                        <td>{{ $r['accuracy'] }}%</td>
                        <td>90%</td>
                        <td>{{ $r['accuracy'] >= 90 ? 'Near Target' : 'In Progress' }}</td>
                    </tr>
                    <tr>
                        <td>Average time / test</td>
                        <td>{{ $r['avg_time'] }}</td>
                        <td>—</td>
                        <td>In Progress</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="spr-foot">
        <div>
            <h4>Keep Going!</h4>
            <p>
                @if(!empty($r['weakest']))
                    Focus on {{ $r['weakest']['name'] }} this week and push overall score toward 90%.
                @else
                    Attempt a test to start mapping strengths, weaknesses, and a weekly plan.
                @endif
            </p>
        </div>
        <i class="material-icons" style="font-size:42px;color:#7c3aed;">emoji_events</i>
    </div>
</div>
</div>
@endsection

@section('js_after')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    var labels = @json($report['trend_labels']);
    var scores = @json($report['trend_scores']);
    function lineChart(id, color) {
        var el = document.getElementById(id);
        if (!el || !labels.length) return;
        new Chart(el, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: scores,
                    borderColor: color,
                    backgroundColor: color === '#22c55e' ? 'rgba(34,197,94,.12)' : 'rgba(59,130,246,.12)',
                    fill: true,
                    tension: .35,
                    pointRadius: 4,
                    pointBackgroundColor: color,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { min: 0, max: 100, ticks: { callback: function (v) { return v + '%'; } }, grid: { color: '#eef2f7' } },
                    x: { ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 6 }, grid: { display: false } }
                }
            }
        });
    }
    lineChart('sprTrend', '#22c55e');
    lineChart('sprTests', '#3b82f6');
})();
</script>
@endsection
