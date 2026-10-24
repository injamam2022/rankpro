@php
    $pr = $pr ?? [];
    $history = $pr['history'] ?? [];
    $online = $pr['online'] ?? [];
    $offline = $pr['offline'] ?? [];
    $subjects = $pr['subjects'] ?? [];
    $growth = (int) ($pr['growth'] ?? 0);
    $growthAbs = abs($growth);
@endphp

<div class="pr-page">
    <div class="pr-hero">
        <div class="pr-hero__top">
            <div class="pr-hero__title-wrap">
                <span class="pr-icon pr-icon--blue"><i class="fas fa-chart-line"></i></span>
                <h1 class="pr-hero__title">Progress Report &amp; Performance Trajectory</h1>
            </div>
            @include('site.include.analytics_exam_select')
        </div>

        <div class="pr-kpis">
            <div class="pr-kpi pr-kpi--slate">
                <div class="pr-kpi__label">Baseline Score</div>
                <div class="pr-kpi__value">{{ (int) ($pr['baseline'] ?? 0) }} <span>/ {{ (int) ($pr['current_max'] ?? 720) }}</span></div>
                <div class="pr-kpi__meta">{{ $pr['baseline_label'] ?? 'Baseline' }}</div>
            </div>
            <div class="pr-kpi pr-kpi--blue">
                <div class="pr-kpi__label">Current Mock Average</div>
                <div class="pr-kpi__value">{{ (int) ($pr['current'] ?? 0) }} <span>/ {{ (int) ($pr['current_max'] ?? 720) }}</span></div>
                <div class="pr-kpi__meta {{ $growth >= 0 ? 'is-up' : 'is-down' }}">
                    <i class="fas fa-{{ $growth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ $growth >= 0 ? '+' : '-' }}{{ $growthAbs }} Marks Growth
                </div>
            </div>
            <div class="pr-kpi pr-kpi--green">
                <div class="pr-kpi__label">All-India Percentile</div>
                <div class="pr-kpi__value">{{ number_format((float) ($pr['percentile'] ?? 0), 2) }}%</div>
                <div class="pr-kpi__meta">AIR #{{ number_format((int) ($pr['air'] ?? 0)) }} Projected</div>
            </div>
            <div class="pr-kpi pr-kpi--pink">
                <div class="pr-kpi__label">Target Score Delta</div>
                <div class="pr-kpi__value">{{ (int) ($pr['target_delta'] ?? 0) }} Marks</div>
                <div class="pr-kpi__meta">Gap to reach {{ (int) ($pr['current_max'] ?? 720) }} Perfect</div>
            </div>
        </div>
    </div>

    <div class="pr-card">
        <div class="pr-card__head">
            <div class="pr-card__title">
                <i class="fas fa-heartbeat"></i>
                Score Growth Trajectory (Mock Test Progression)
            </div>
            <span class="pr-pill {{ !empty($pr['trend_up']) ? 'pr-pill--green' : 'pr-pill--amber' }}">{{ $pr['trend'] ?? '' }}</span>
        </div>

        @if(empty($history))
            <div class="pr-empty">Attempt a mock to see your score trajectory.</div>
        @else
            <div class="pr-traj">
                @foreach($history as $item)
                    <div class="pr-traj__row {{ !empty($item['is_focus']) ? 'is-focus' : '' }}">
                        <div class="pr-traj__meta">
                            <div class="pr-traj__left">
                                <strong>{{ $item['exam'] }}</strong>
                                @if($item['date'])
                                    <span class="pr-chip">{{ $item['date'] }}</span>
                                @endif
                                <span class="pr-chip pr-chip--blue">{{ $item['mode'] }}</span>
                            </div>
                            <div class="pr-traj__right">
                                <span class="pr-traj__pct">{{ $item['percentile'] }}</span>
                                <span class="pr-traj__score">{{ $item['score'] }} <small>/ {{ $item['max'] }}</small></span>
                            </div>
                        </div>
                        <div class="pr-traj__bar">
                            <span style="width: {{ $item['width'] }}%"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="pr-split">
        <div class="pr-card">
            <div class="pr-card__head">
                <div class="pr-card__title">
                    <i class="fas fa-flask"></i>
                    Online CBT vs Offline OMR Comparative Matrix
                </div>
                <span class="pr-pill pr-pill--purple">Exam Format Analysis</span>
            </div>
            <div class="pr-matrix">
                @foreach([$online, $offline] as $mode)
                    <div class="pr-mode {{ ($mode['title'] ?? '') === 'Offline OMR' ? 'pr-mode--omr' : 'pr-mode--cbt' }}">
                        <div class="pr-mode__head">
                            <strong>{{ $mode['title'] ?? '' }}</strong>
                            <span>{{ $mode['badge'] ?? '' }}</span>
                        </div>
                        <div class="pr-mode__row">
                            <span>Average Score:</span>
                            <b>{{ (int) ($mode['avg_score'] ?? 0) }} / {{ (int) ($mode['avg_max'] ?? 720) }}</b>
                        </div>
                        <div class="pr-mode__row">
                            <span>Accuracy:</span>
                            <b class="is-green">{{ $mode['accuracy'] ?? 0 }}%</b>
                        </div>
                        <div class="pr-mode__row">
                            <span>Time Used:</span>
                            <b>{{ $mode['time_label'] ?? '—' }}</b>
                        </div>
                        <div class="pr-mode__row">
                            <span>{{ $mode['extra_label'] ?? '' }}:</span>
                            <b>{{ $mode['extra_value'] ?? '—' }}</b>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pr-card">
            <div class="pr-card__head">
                <div class="pr-card__title">
                    <i class="fas fa-award"></i>
                    All-India Rank (AIR) Projector &amp; Medical Seat
                </div>
                <span class="pr-pill pr-pill--green">NTA Percentile Mapping</span>
            </div>
            <div class="pr-air">
                <div class="pr-air__banner">
                    <div>
                        <div class="pr-air__label">Projected All-India Rank Range</div>
                        <div class="pr-air__range">AIR #{{ number_format((int) ($pr['air_low'] ?? 0)) }} – #{{ number_format((int) ($pr['air_high'] ?? 0)) }}</div>
                    </div>
                    <span class="pr-pill pr-pill--green">{{ $pr['govt_label'] ?? '' }}</span>
                </div>
                <div class="pr-air__subjects">
                    <div>
                        <span>Biology Score</span>
                        <b class="is-green">{{ (int) ($subjects['biology']['score'] ?? 0) }} / {{ (int) ($subjects['biology']['max'] ?? 360) }}</b>
                    </div>
                    <div>
                        <span>Chemistry Score</span>
                        <b class="is-blue">{{ (int) ($subjects['chemistry']['score'] ?? 0) }} / {{ (int) ($subjects['chemistry']['max'] ?? 180) }}</b>
                    </div>
                    <div>
                        <span>Physics Score</span>
                        <b class="is-purple">{{ (int) ($subjects['physics']['score'] ?? 0) }} / {{ (int) ($subjects['physics']['max'] ?? 180) }}</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
