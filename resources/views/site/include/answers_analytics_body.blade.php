@php
    $aaRoute = request()->routeIs('exam_answers_analytics') ? 'exam_answers_analytics' : 'answers_analytics';
    $examType = $exam_type ?? '';
    $subjectId = $subject_id ?? '';
    $difficulty = (string) ($difficulty ?? '');
    $examId = $exam_id ?? null;
    $isAllMode = $examType === '' || $examType === null || (string) $examType === '3';

    $aaUrl = function ($overrides = []) use ($aaRoute, $examType, $subjectId, $difficulty, $examId) {
        $params = [
            'exam_id' => $examId,
            'subject_id' => $subjectId,
            'exam_type' => $examType,
            'difficulty' => $difficulty,
        ];
        foreach ($overrides as $k => $v) {
            $params[$k] = $v;
        }
        if ($aaRoute !== 'exam_answers_analytics') {
            unset($params['exam_id']);
        }
        return route($aaRoute, array_filter($params, function ($v) {
            return $v !== null && $v !== '';
        }));
    };

    $rowVal = function ($row, $key) {
        return (int) ($row->{$key} ?? 0);
    };
    $accPct = function ($row) use ($rowVal) {
        $q = $rowVal($row, 'total_question');
        $skip = $rowVal($row, 'skip_answer');
        $attempted = max(0, $q - $skip);
        $right = $rowVal($row, 'right_answer');
        return $attempted > 0 ? (int) round(($right / $attempted) * 100) : 0;
    };
    $strike = function ($row) use ($rowVal) {
        $right = $rowVal($row, 'right_answer');
        $wrong = $rowVal($row, 'wrong_answer');
        $attempted = $right + $wrong;
        $pct = $attempted > 0 ? round(($right / $attempted) * 100, 1) : 0;
        return compact('right', 'wrong', 'attempted', 'pct');
    };

    if ($difficulty === '1') {
        $topper_data = $easy_toper_list ?? $topper_data;
        $average_data = $avg_easy_toper_list ?? $average_data;
        $you_data = $my_easy_toper_list ?? $you_data;
    } elseif ($difficulty === '2') {
        $topper_data = $medium_toper_list ?? $topper_data;
        $average_data = $avg_medium_toper_list ?? $average_data;
        $you_data = $my_medium_toper_list ?? $you_data;
    } elseif ($difficulty === '3') {
        $topper_data = $hard_toper_list ?? $topper_data;
        $average_data = $avg_hard_toper_list ?? $average_data;
        $you_data = $my_hard_toper_list ?? $you_data;
    }

    $youMark = (int) round((float) (optional($you_data)->total_mark ?? 0));
    $youTotal = (int) round((float) (optional($you_data)->totals_marks_for_exam ?? 0));
    $topMark = (int) round((float) (optional($topper_data)->total_mark ?? 0));
    $topTotal = (int) round((float) (optional($topper_data)->totals_marks_for_exam ?? $youTotal));
    $avgMark = (int) round((float) (optional($average_data)->total_mark ?? 0));
    $avgTotal = (int) round((float) (optional($average_data)->totals_marks_for_exam ?? $youTotal));
    $maxBar = max($topMark, $avgMark, $youMark, 1);
    $youPct = $youTotal > 0 ? (int) round($youMark / $youTotal * 100) : 0;
    $rank = (int) ($your_score ?? 0);
    $airDeg = max(8, min(100, $youPct));
    $paceHint = function ($seconds, $name) {
        $seconds = (int) $seconds;
        if ($seconds <= 0) {
            return 'Attempt questions to see pace.';
        }
        if ($seconds < 45) {
            return 'Optimal: strong '.$name.' pace';
        }
        if ($seconds <= 60) {
            return 'Within target range (45 - 60s)';
        }
        $m = intdiv($seconds, 60);
        $s = $seconds % 60;
        return $m.'m '.$s.'s per '.$name.' question';
    };
    $hourCopy = [
        1 => ['title' => 'Hour 1 · Opening stretch', 'note' => 'Peak mental alertness'],
        2 => ['title' => 'Hour 2 · Mid paper', 'note' => 'Steady calculation pace'],
        3 => ['title' => 'Hour 3 · Closing stretch', 'note' => 'Fatigue drop window'],
    ];
    $paceColors = ['green', 'blue', 'purple'];

    $easyYou = $strike($my_easy_toper_list ?? null);
    $medYou = $strike($my_medium_toper_list ?? null);
    $hardYou = $strike($my_hard_toper_list ?? null);

    $easyAcc = $accPct($my_easy_toper_list ?? null);
    $medAcc = $accPct($my_medium_toper_list ?? null);
    $hardAcc = $accPct($my_hard_toper_list ?? null);

    $aa_diagnostics = $aa_diagnostics ?? [];
    $testsCount = (int) ($aa_diagnostics['tests_count'] ?? ($total_exam_attemped ?? ($examId ? 1 : 0)));
    $qAttempted = $rowVal($you_data ?? null, 'right_answer') + $rowVal($you_data ?? null, 'wrong_answer');
    $pace = $aa_diagnostics['pace'] ?? [];
    $negatives = $aa_diagnostics['negatives'] ?? [];
    $negTotal = (int) ($aa_diagnostics['negative_total'] ?? 0);
    $hours = $aa_diagnostics['hours'] ?? [];

    $showEasy = $difficulty === '' || $difficulty === '1';
    $showMed = $difficulty === '' || $difficulty === '2';
    $showHard = $difficulty === '' || $difficulty === '3';
    $diffCols = (int) $showEasy + (int) $showMed + (int) $showHard;
@endphp

<div class="aa-page">
    <div class="aa-head">
        <h1 class="aa-head__title">
            <span class="aa-head__icon"><i class="fas fa-bullseye"></i></span>
            Answers Analytics
        </h1>
        <div class="aa-head__meta">Benchmark Comparison: <strong>Topper vs Average vs You</strong></div>
    </div>

    <div class="aa-filters">
        <div class="aa-filters__label"><i class="fas fa-filter"></i> Multi-filter diagnostic bar</div>
        <div class="aa-filters__row">
            <div class="aa-filter">
                <span class="aa-filter__name">Exam Mode</span>
                <div class="aa-chips">
                    <a class="aa-chip {{ $isAllMode ? 'is-on-dark' : '' }}" href="{{ $aaUrl(['exam_type' => '']) }}">All Modes</a>
                    <a class="aa-chip {{ (string) $examType === '1' ? 'is-on-dark' : '' }}" href="{{ $aaUrl(['exam_type' => 1]) }}">Online CBT</a>
                    <a class="aa-chip {{ (string) $examType === '2' ? 'is-on-dark' : '' }}" href="{{ $aaUrl(['exam_type' => 2]) }}">Offline OMR</a>
                </div>
            </div>
            <div class="aa-filter">
                <span class="aa-filter__name">Subject</span>
                <div class="aa-chips">
                    <a class="aa-chip {{ $subjectId === '' || $subjectId === null ? 'is-on' : '' }}" href="{{ $aaUrl(['subject_id' => '']) }}">All</a>
                    @foreach($subject_list as $sub)
                        <a class="aa-chip {{ (string) $subjectId === (string) $sub->id ? 'is-on' : '' }}" href="{{ $aaUrl(['subject_id' => $sub->id]) }}">{{ $sub->name }}</a>
                    @endforeach
                </div>
            </div>
            <div class="aa-filter">
                <span class="aa-filter__name">Question Difficulty</span>
                <div class="aa-chips">
                    <a class="aa-chip {{ $difficulty === '' ? 'is-on-purple' : '' }}" href="{{ $aaUrl(['difficulty' => '']) }}">All</a>
                    <a class="aa-chip {{ $difficulty === '1' ? 'is-on-purple' : '' }}" href="{{ $aaUrl(['difficulty' => 1]) }}">Easy</a>
                    <a class="aa-chip {{ $difficulty === '2' ? 'is-on-purple' : '' }}" href="{{ $aaUrl(['difficulty' => 2]) }}">Medium</a>
                    <a class="aa-chip {{ $difficulty === '3' ? 'is-on-purple' : '' }}" href="{{ $aaUrl(['difficulty' => 3]) }}">Hard</a>
                </div>
            </div>
            <div class="aa-scope">
                <span class="aa-filter__name">Test Series Scope</span>
                @include('site.include.analytics_exam_select')
            </div>
        </div>
    </div>

    <div class="aa-diff-grid" style="{{ $diffCols === 1 ? 'grid-template-columns:1fr;' : ($diffCols === 2 ? 'grid-template-columns:1fr 1fr;' : '') }}">
        @if($showEasy)
        <div class="aa-diff aa-diff--easy">
            <div class="aa-diff__head">Easy <span class="aa-diff__badge">Level 1 NCERT</span></div>
            @include('site.include.answers_analytics_diff_table', [
                'top' => $easy_toper_list ?? null,
                'avg' => $avg_easy_toper_list ?? null,
                'you' => $my_easy_toper_list ?? null,
            ])
        </div>
        @endif
        @if($showMed)
        <div class="aa-diff aa-diff--medium">
            <div class="aa-diff__head">Medium <span class="aa-diff__badge">Level 2 Multistep</span></div>
            @include('site.include.answers_analytics_diff_table', [
                'top' => $medium_toper_list ?? null,
                'avg' => $avg_medium_toper_list ?? null,
                'you' => $my_medium_toper_list ?? null,
            ])
        </div>
        @endif
        @if($showHard)
        <div class="aa-diff aa-diff--hard">
            <div class="aa-diff__head">Hard <span class="aa-diff__badge">Level 3 Rank Decider</span></div>
            @include('site.include.answers_analytics_diff_table', [
                'top' => $hard_toper_list ?? null,
                'avg' => $avg_hard_toper_list ?? null,
                'you' => $my_hard_toper_list ?? null,
            ])
        </div>
        @endif
    </div>

    <div class="aa-charts">
        <div class="aa-card">
            <div class="aa-card__title">Topper Vs Average Vs You <small>Total Scores</small></div>
            <div class="aa-compare">
                <div class="aa-bars">
                    <div class="aa-hbar">
                        <span class="aa-hbar__name is-top">Topper:</span>
                        <div class="aa-hbar__track">
                            <div class="aa-hbar__fill is-top" style="width: {{ min(100, round($topMark / $maxBar * 100)) }}%;">{{ $topMark }} / {{ $topTotal }}</div>
                        </div>
                    </div>
                    <div class="aa-hbar">
                        <span class="aa-hbar__name is-avg">Average:</span>
                        <div class="aa-hbar__track">
                            <div class="aa-hbar__fill is-avg" style="width: {{ min(100, round($avgMark / $maxBar * 100)) }}%;">{{ $avgMark }} / {{ $avgTotal }}</div>
                        </div>
                    </div>
                    <div class="aa-hbar">
                        <span class="aa-hbar__name is-you">You:</span>
                        <div class="aa-hbar__track">
                            <div class="aa-hbar__fill is-you" style="width: {{ min(100, round($youMark / $maxBar * 100)) }}%;">{{ $youMark }} / {{ $youTotal }}</div>
                        </div>
                    </div>
                </div>
                <div class="aa-air">
                    <div class="aa-air__ring" style="--aa-air: {{ $airDeg }}%;">
                        <span>AIR #{{ $rank ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="aa-card">
            <div class="aa-card__title">Precision &amp; Accuracy Gauges <span class="aa-badge aa-badge--ok">High Accuracy</span></div>
            <div class="aa-gauges">
                <div class="aa-gauge">
                    <canvas id="aaEasyAcc"></canvas>
                    <div class="aa-gauge__label">Easy Precision</div>
                </div>
                <div class="aa-gauge">
                    <canvas id="aaMedAcc"></canvas>
                    <div class="aa-gauge__label">Med Benchmark</div>
                </div>
                <div class="aa-gauge">
                    <canvas id="aaHardAcc"></canvas>
                    <div class="aa-gauge__label">Hard Precision</div>
                </div>
            </div>
        </div>

        <div class="aa-card">
            <div class="aa-card__title">Total Score Accuracy <span class="aa-badge aa-badge--ok">{{ $youPct }}% Net</span></div>
            <div class="aa-score-wrap">
                <canvas id="aaScoreChart"></canvas>
                <div class="aa-score-num">{{ $youMark }} / {{ $youTotal }}</div>
                <div class="aa-score-cap">Marks Aggregated (Filtered View)</div>
            </div>
        </div>
    </div>

    <div class="aa-summaries">
        <div class="aa-sum aa-sum--blue">
            <div class="aa-sum__n">{{ $testsCount }}</div>
            <div class="aa-sum__t">Total Tests in Selected Scope</div>
        </div>
        <div class="aa-sum aa-sum--pink">
            <div class="aa-sum__n">{{ $qAttempted }}</div>
            <div class="aa-sum__t">Total Questions Attempted by You</div>
        </div>
    </div>

    <div class="aa-widget">
        <div class="aa-widget__head">
            <h2 class="aa-widget__title"><i class="fas fa-sliders-h"></i> Interactive Multi-Parameter Exam Diagnostic Widget</h2>
            <div class="aa-chips">
                <a class="aa-chip {{ $isAllMode ? 'is-on-dark' : '' }}" href="{{ $aaUrl(['exam_type' => '']) }}">All Modes</a>
                <a class="aa-chip {{ (string) $examType === '1' ? 'is-on-dark' : '' }}" href="{{ $aaUrl(['exam_type' => 1]) }}">Online CBT</a>
                <a class="aa-chip {{ (string) $examType === '2' ? 'is-on-dark' : '' }}" href="{{ $aaUrl(['exam_type' => 2]) }}">Offline OMR</a>
            </div>
        </div>
        <div class="aa-tabs" id="aaWidgetTabs">
            <button type="button" class="aa-tab is-on" data-panel="aaPanelStrike">Level &amp; Strike Rate</button>
            <button type="button" class="aa-tab" data-panel="aaPanelPace">Time Spent Per Question (Speed)</button>
            <button type="button" class="aa-tab" data-panel="aaPanelNeg">Negative Penalty Cost (-1)</button>
            <button type="button" class="aa-tab" data-panel="aaPanelHour">3rd Hour Stamina &amp; Fatigue</button>
        </div>

        <div class="aa-panel" id="aaPanelStrike">
            <div class="aa-strike">
                <div class="aa-strike-card aa-strike-card--easy">
                    <h4>Easy Question Strike Rate</h4>
                    <div class="aa-strike-pct">{{ $easyYou['pct'] }}%</div>
                    <div class="aa-meter"><span style="width:{{ min(100, $easyYou['pct']) }}%;background:#10b981;"></span></div>
                    <div class="aa-strike-meta">
                        <span>{{ $easyYou['right'] }} Correct / {{ $easyYou['attempted'] }} Attempted</span>
                        <b>+{{ $rowVal($my_easy_toper_list ?? null, 'total_mark') }} Marks</b>
                    </div>
                </div>
                <div class="aa-strike-card aa-strike-card--med">
                    <h4>Medium Question Strike Rate</h4>
                    <div class="aa-strike-pct">{{ $medYou['pct'] }}%</div>
                    <div class="aa-meter"><span style="width:{{ min(100, $medYou['pct']) }}%;background:#3b82f6;"></span></div>
                    <div class="aa-strike-meta">
                        <span>{{ $medYou['right'] }} Correct / {{ $medYou['attempted'] }} Attempted</span>
                        <b>+{{ $rowVal($my_medium_toper_list ?? null, 'total_mark') }} Marks</b>
                    </div>
                </div>
                <div class="aa-strike-card aa-strike-card--hard">
                    <h4>Hard Question Strike Rate</h4>
                    <div class="aa-strike-pct">{{ $hardYou['pct'] }}%</div>
                    <div class="aa-meter"><span style="width:{{ min(100, $hardYou['pct']) }}%;background:#8b5cf6;"></span></div>
                    <div class="aa-strike-meta">
                        <span>{{ $hardYou['right'] }} Correct / {{ $hardYou['attempted'] }} Attempted</span>
                        <b>+{{ $rowVal($my_hard_toper_list ?? null, 'total_mark') }} Marks</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="aa-panel" id="aaPanelPace" hidden>
            <div class="aa-pace">
                @forelse($pace as $i => $p)
                    <div class="aa-pace-card aa-pace-card--{{ $paceColors[$i % 3] }}">
                        <h4>{{ $p['name'] }} Solving Pace</h4>
                        <div class="aa-pace-n">{{ $p['label'] }}</div>
                        <p>{{ $paceHint($p['seconds'] ?? 0, $p['name']) }}</p>
                    </div>
                @empty
                    <div class="aa-pace-card"><h4>No timing data yet</h4><p>Attempt an exam to see subject-wise pace.</p></div>
                @endforelse
            </div>
        </div>

        <div class="aa-panel" id="aaPanelNeg" hidden>
            <div class="aa-neg">
                <div class="aa-neg__top">
                    <span><i class="fas fa-minus-circle"></i> Total Marks Lost to Negative Marking (-):</span>
                    <span>{{ $negTotal }} Marks</span>
                </div>
                <div class="aa-neg__rows">
                    @forelse($negatives as $n)
                        <span>{{ $n['name'] }} Negative: {{ $n['marks'] }} Marks</span>
                    @empty
                        <span>No negative marks in this scope.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="aa-panel" id="aaPanelHour" hidden>
            <div class="aa-hours">
                @foreach($hours as $h)
                    @php $copy = $hourCopy[$h['hour']] ?? ['title' => 'Hour '.$h['hour'], 'note' => '']; @endphp
                    <div class="aa-hour aa-hour--{{ $h['hour'] }}">
                        <h4>{{ $copy['title'] }}</h4>
                        <div class="aa-hour-n">{{ $h['pct'] }}%</div>
                        <p>{{ $copy['note'] }} · {{ $h['right'] }} correct / {{ $h['attempted'] }} attempted</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var tabs = document.querySelectorAll('#aaWidgetTabs .aa-tab');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('is-on'); });
            tab.classList.add('is-on');
            ['aaPanelStrike','aaPanelPace','aaPanelNeg','aaPanelHour'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.hidden = id !== tab.getAttribute('data-panel');
            });
        });
    });

    function donut(id, value, color) {
        var el = document.getElementById(id);
        if (!el || typeof Chart === 'undefined') return;
        var v = Math.max(0, Math.min(100, parseInt(value, 10) || 0));
        new Chart(el, {
            type: 'doughnut',
            data: { datasets: [{ data: [v, 100 - v], backgroundColor: [color, '#eef2f7'], borderWidth: 0 }] },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '78%',
                plugins: { legend: { display: false }, tooltip: { enabled: false }, centerText: { score: v } }
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
                    ctx.font = '700 16px Poppins, Arial';
                    ctx.fillStyle = '#0f172a';
                    ctx.fillText(score + '%', meta.data[0].x, meta.data[0].y + 6);
                    ctx.restore();
                }
            }]
        });
    }

    function startCharts() {
        if (typeof Chart === 'undefined') return;
        donut('aaEasyAcc', {{ $easyAcc }}, '#3b82f6');
        donut('aaMedAcc', {{ $medAcc }}, '#ec4899');
        donut('aaHardAcc', {{ $hardAcc }}, '#8b5cf6');

        var scoreEl = document.getElementById('aaScoreChart');
        if (scoreEl) {
            var ym = {{ $youMark }};
            var yt = {{ max(1, $youTotal) }};
            new Chart(scoreEl, {
                type: 'doughnut',
                data: { datasets: [{ data: [ym, Math.max(0, yt - ym)], backgroundColor: ['#2563eb', '#eef2f7'], borderWidth: 0 }] },
                options: {
                    cutout: '74%',
                    plugins: { legend: { display: false }, tooltip: { enabled: false }, centerText: { score: {{ $youPct }} } }
                },
                plugins: [{
                    id: 'scoreCenter',
                    afterDraw: function (chart) {
                        var score = chart.options.plugins.centerText.score;
                        var meta = chart.getDatasetMeta(0);
                        if (!meta || !meta.data || !meta.data.length) return;
                        var ctx = chart.ctx;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '800 22px Poppins, Arial';
                        ctx.fillStyle = '#1d4ed8';
                        ctx.fillText(score + '%', meta.data[0].x, meta.data[0].y + 8);
                        ctx.restore();
                    }
                }]
            });
        }
    }
    if (window.Chart) startCharts();
    else window.addEventListener('load', startCharts);
})();
</script>
