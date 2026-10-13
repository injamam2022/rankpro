@php
    $kind = $kind ?? 'strength';
    $units = $units ?? [];
    $isWeak = $kind === 'weakness';
    $subClass = function ($name) {
        $n = strtolower((string) $name);
        if (str_contains($n, 'phys')) return 'sw-subbadge--phy';
        if (str_contains($n, 'chem')) return 'sw-subbadge--che';
        if (str_contains($n, 'bio')) return 'sw-subbadge--bio';
        return 'sw-subbadge--phy';
    };
@endphp
@forelse($units as $i => $unit)
    <div class="sw-unit {{ $i === 0 ? 'is-open' : '' }}" data-subject="{{ $unit['subject_id'] }}">
        <button type="button" class="sw-unit__btn">
            <div class="sw-unit__meta">
                <span class="sw-subbadge {{ $subClass($unit['subject_name']) }}">{{ $unit['subject_name'] }}</span>
                <div class="sw-unit__name">{{ $unit['chapter_name'] }}</div>
                <div class="sw-unit__sub">
                    @if($isWeak)
                        {{ $unit['wrong'] }} mistakes recorded in selected scope
                    @else
                        {{ $unit['right'] }} / {{ $unit['attempted'] }} correct · {{ $unit['total'] }} questions in scope
                    @endif
                </div>
            </div>
            <div class="sw-unit__score">
                <div class="sw-unit__pct">{{ $unit['accuracy'] }}%</div>
                <div class="sw-unit__flag">{{ $isWeak ? 'Review' : ($unit['accuracy'] >= 90 ? 'Mastered' : 'Strong') }}</div>
            </div>
        </button>
        <div class="sw-unit__body">
            <div class="sw-unit__sub" style="margin:4px 0 6px;">{{ $isWeak ? 'Subtopic action targets' : 'Subtopic breakdown · Click any subtopic to drill down' }}</div>
            @forelse($unit['subtopics'] as $sub)
                <div class="sw-sub">
                    <div class="sw-sub__row">
                        <div class="sw-sub__name">{{ $sub['name'] }}</div>
                        <div class="sw-sub__acc {{ $isWeak ? 'is-bad' : 'is-ok' }}">
                            @if($isWeak)
                                {{ $sub['wrong'] }} errors ({{ $sub['accuracy'] }}%)
                            @else
                                {{ $sub['accuracy'] }}%
                            @endif
                        </div>
                    </div>
                    @if($isWeak)
                        <div class="sw-rx">Target Rx: Revise NCERT lines and typical traps in this subtopic</div>
                    @else
                        <div class="sw-sub__meta">Speed: {{ $sub['avg_time'] ?: '—' }}s per question · {{ $sub['right'] }} / {{ $sub['attempted'] }} solved</div>
                    @endif
                    @if(!empty($sub['id']))
                        <button type="button" class="sw-drill" data-kind="{{ $kind }}" data-id="{{ $sub['id'] }}">Drill Down →</button>
                    @endif
                </div>
            @empty
                <div class="sw-empty">No subtopic split for this unit yet.</div>
            @endforelse
        </div>
    </div>
@empty
    <div class="sw-empty">{{ $isWeak ? 'No weakness units in this scope. Keep the accuracy up.' : 'No mastered units in this scope yet. Attempt more questions to build a strength map.' }}</div>
@endforelse
