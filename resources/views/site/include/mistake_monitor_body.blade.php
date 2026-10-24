@php
    $mm = $mm ?? [];
    $filters = $mm['filters'] ?? ['status' => 'All', 'reason' => 'All'];
    $taxonomy = $mm['taxonomy'] ?? [];
    $subjects = $mm['subjects'] ?? [];
    $hotspots = $mm['hotspots'] ?? [];
    $entries = $mm['entries'] ?? [];
    $total = max(1, (int) ($mm['total'] ?? 0));
    $pager = $mm['pager'] ?? ['page' => 1, 'per_page' => 10, 'total' => 0, 'last_page' => 1, 'from' => 0, 'to' => 0];
    $scopeQ = \App\Support\StudentScope::query();
    $mmUrl = function ($overrides = []) use ($scopeQ, $filters, $pager) {
        $filterKeys = ['mm_status', 'mm_reason'];
        $changingFilter = count(array_intersect(array_keys($overrides), $filterKeys)) > 0;
        $params = array_merge($scopeQ, [
            'mm_status' => $filters['status'] ?? 'All',
            'mm_reason' => $filters['reason'] ?? 'All',
            'mm_page' => $pager['page'] ?? 1,
        ], $overrides);
        if ($changingFilter && !array_key_exists('mm_page', $overrides)) {
            $params['mm_page'] = 1;
        }
        foreach ($params as $k => $v) {
            if ($v === null || $v === '' || $v === 'All' || ($k === 'mm_page' && (int) $v <= 1)) {
                if (in_array($k, ['mm_status', 'mm_reason'], true) && $v === 'All') {
                    unset($params[$k]);
                } elseif ($k === 'mm_page' && (int) $v <= 1) {
                    unset($params[$k]);
                } elseif ($v === null || $v === '') {
                    unset($params[$k]);
                }
            }
        }
        $url = route('common_confusion', $params);
        return $url.'#mm-records';
    };
    $pageList = [];
    $lastPage = max(1, (int) ($pager['last_page'] ?? 1));
    $curPage = max(1, (int) ($pager['page'] ?? 1));
    if ($lastPage <= 7) {
        $pageList = range(1, $lastPage);
    } else {
        $pageList[] = 1;
        $start = max(2, $curPage - 1);
        $end = min($lastPage - 1, $curPage + 1);
        if ($start > 2) {
            $pageList[] = '...';
        }
        for ($p = $start; $p <= $end; $p++) {
            $pageList[] = $p;
        }
        if ($end < $lastPage - 1) {
            $pageList[] = '...';
        }
        $pageList[] = $lastPage;
    }
    $tones = ['red' => '#ef4444', 'amber' => '#f59e0b', 'purple' => '#8b5cf6', 'blue' => '#3b82f6', 'slate' => '#94a3b8'];
    $dotTones = ['Physics' => '#ef4444', 'Chemistry' => '#f59e0b', 'Biology' => '#10b981', 'Other' => '#64748b'];
    $reasonFilter = (string) ($filters['reason'] ?? 'All');
    $reasonOptions = [
        ['value' => 'All', 'title' => 'All Error Reasons', 'sub' => 'Every tagged and untagged error'],
        ['value' => 'untagged', 'title' => 'Untagged', 'sub' => 'No reason chosen yet'],
        ['value' => 'conceptual', 'title' => 'Conceptual Error', 'sub' => 'Taxonomy group'],
        ['value' => 'calculation', 'title' => 'Calculation Slip', 'sub' => 'Taxonomy group'],
        ['value' => 'formula', 'title' => 'Formula Recall', 'sub' => 'Taxonomy group'],
        ['value' => 'misread', 'title' => 'Misread Question', 'sub' => 'Taxonomy group'],
    ];
    foreach (($mm['reasons'] ?? []) as $reason) {
        $reasonOptions[] = [
            'value' => (string) $reason->id,
            'title' => $reason->name,
            'sub' => 'Logged reason',
        ];
    }
    $selectedReasonLabel = 'All Error Reasons';
    foreach ($reasonOptions as $opt) {
        if ((string) $opt['value'] === $reasonFilter) {
            $selectedReasonLabel = $opt['title'];
            break;
        }
    }
@endphp

<div class="mm-page">
    <div class="pr-card mm-hero">
        <div class="pr-card__head">
            <div class="pr-hero__title-wrap">
                <span class="pr-icon pr-icon--pink"><i class="fas fa-stethoscope"></i></span>
                <div>
                    <h1 class="pr-hero__title">Doctor Mistake Monitor &amp; Error Analytics</h1>
                    <p class="pr-hero__sub">Incorrect attempts from your mocks, with optional reason tags and NCERT retention notes</p>
                </div>
            </div>
            <span class="pr-pill pr-pill--pink">{{ (int) ($mm['pending'] ?? 0) }} Errors Pending Remediation</span>
        </div>

        <div class="mm-kpis">
            <div class="pr-kpi pr-kpi--slate">
                <div class="pr-kpi__label">Logged Errors <i class="fas fa-chart-bar"></i></div>
                <div class="pr-kpi__value">{{ (int) ($mm['total'] ?? 0) }} <span>questions</span></div>
                <div class="pr-kpi__meta">Across All Mocks Taken</div>
            </div>
            <div class="pr-kpi pr-kpi--red">
                <div class="pr-kpi__label">Negative Mark Loss <i class="fas fa-bolt"></i></div>
                <div class="pr-kpi__value">-{{ (int) ($mm['pending'] ?? 0) }} <span>marks</span></div>
                <div class="pr-kpi__meta">-1 NTA penalty per unforced error</div>
            </div>
            <div class="pr-kpi pr-kpi--green">
                <div class="pr-kpi__label">Mastered Rate <i class="fas fa-check-circle"></i></div>
                <div class="pr-kpi__value">{{ (int) ($mm['mastered_rate'] ?? 0) }}%</div>
                <div class="pr-kpi__meta">{{ (int) ($mm['mastered'] ?? 0) }} of {{ (int) ($mm['total'] ?? 0) }} errors rectified</div>
            </div>
            <div class="pr-kpi pr-kpi--blue">
                <div class="pr-kpi__label">Potential Recovery <i class="fas fa-magic"></i></div>
                <div class="pr-kpi__value">+{{ (int) ($mm['recovery'] ?? 0) }} <span>Marks</span></div>
                <div class="pr-kpi__meta">+5 swing per rectified mistake</div>
            </div>
        </div>

        <div class="mm-panels">
            <div class="mm-panel">
                <div class="mm-panel__head">
                    <h3>Error Root Cause Distribution</h3>
                    <span>NTA Pattern Psychology</span>
                </div>
                @foreach($taxonomy as $key => $row)
                    @if($key === 'other' && (int) $row['count'] === 0)
                        @continue
                    @endif
                    @php $pct = $total > 0 ? round(($row['count'] / $total) * 100) : 0; @endphp
                    <div class="mm-cause">
                        <div class="mm-cause__meta">
                            <span>{{ $row['label'] }}</span>
                            <b class="tone-{{ $row['tone'] }}">{{ (int) $row['count'] }} errors ({{ $pct }}%)</b>
                        </div>
                        <div class="mm-cause__bar">
                            <span class="tone-bg-{{ $row['tone'] }}" style="width: {{ $pct }}%"></span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mm-panel">
                <div class="mm-panel__head">
                    <h3>Subject &amp; High-Yield Hotspots</h3>
                    <span>Critical Review Priority</span>
                </div>
                <div class="mm-subs">
                    <div class="mm-sub mm-sub--phy">
                        <span>Physics</span>
                        <b>{{ (int) ($subjects['Physics'] ?? 0) }} Errors</b>
                    </div>
                    <div class="mm-sub mm-sub--chem">
                        <span>Chemistry</span>
                        <b>{{ (int) ($subjects['Chemistry'] ?? 0) }} Errors</b>
                    </div>
                    <div class="mm-sub mm-sub--bio">
                        <span>Biology</span>
                        <b>{{ (int) ($subjects['Biology'] ?? 0) }} Errors</b>
                    </div>
                </div>
                <div class="mm-hots">
                    @forelse($hotspots as $hot)
                        <div class="mm-hot">
                            <div>
                                <i class="mm-dot" style="background: {{ $dotTones[$hot['subject']] ?? '#64748b' }}"></i>
                                <strong>{{ $hot['chapter'] }}</strong>
                            </div>
                            <span>{{ $hot['subject'] }} · {{ $hot['count'] }} Errors</span>
                        </div>
                    @empty
                        <div class="pr-empty">No incorrect answers in this Mode / Subject yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="pr-card" id="mm-records">
        <div class="pr-card__head mm-filter-head">
            <div class="pr-card__title"><i class="fas fa-filter"></i> Filter Mistake Records</div>
            <div class="mm-filters">
                <div class="mm-seg mm-seg--dark">
                    @foreach(['All','Pending','Mastered'] as $st)
                        <a class="{{ ($filters['status'] ?? 'All') === $st ? 'is-on' : '' }}" href="{{ $mmUrl(['mm_status' => $st]) }}">{{ $st }}</a>
                    @endforeach
                </div>
                <div class="mm-reason-pick" id="mmReasonPicker">
                    <button type="button" class="mm-reason-pick__trigger" id="mmReasonTrigger" aria-haspopup="listbox" aria-expanded="false">
                        <span class="mm-reason-pick__meta">
                            <span class="mm-reason-pick__eyebrow">Reason</span>
                            <span class="mm-reason-pick__value" id="mmReasonValue">{{ $selectedReasonLabel }}</span>
                        </span>
                        <i class="fas fa-chevron-down mm-reason-pick__caret" aria-hidden="true"></i>
                    </button>
                    <div class="mm-reason-pick__panel" id="mmReasonPanel" hidden>
                        <div class="mm-reason-pick__toolbar">
                            <button type="button" class="mm-reason-pick__search-toggle" id="mmReasonSearchToggle" aria-label="Search reasons" title="Search reasons">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="mm-reason-pick__search" id="mmReasonSearchWrap" hidden>
                                <input type="search" id="mmReasonSearch" class="mm-reason-pick__search-input" placeholder="Search reason…" autocomplete="off">
                            </div>
                        </div>
                        <ul class="mm-reason-pick__list" id="mmReasonList" role="listbox">
                            @foreach($reasonOptions as $opt)
                                @php $isActive = (string) $opt['value'] === $reasonFilter; @endphp
                                <li>
                                    <a
                                        class="mm-reason-pick__option {{ $isActive ? 'is-active' : '' }}"
                                        href="{{ $mmUrl(['mm_reason' => $opt['value']]) }}"
                                        data-search="{{ strtolower($opt['title'].' '.$opt['sub']) }}"
                                        role="option"
                                        @if($isActive) aria-selected="true" @endif
                                    >
                                        <span class="mm-reason-pick__option-title">{{ $opt['title'] }}</span>
                                        <span class="mm-reason-pick__option-sub">{{ $opt['sub'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                            <li class="mm-reason-pick__empty" id="mmReasonEmpty" hidden>No reasons match</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($mm['untagged']) && (int) ($mm['total'] ?? 0) > 0)
            <div class="mm-hint">
                {{ (int) $mm['untagged'] }} of these errors have no tagged reason yet.
                Open a mock result and choose why you got it wrong to classify Conceptual / Calculation / Formula / Misread.
            </div>
        @endif

        <div class="mm-list">
            @forelse($entries as $entry)
                <div class="mm-item {{ $entry['mastered'] ? 'is-mastered' : '' }}">
                    <div class="mm-item__top">
                        <div class="mm-item__tags">
                            <strong>{{ $entry['exam'] }}@if($entry['question_number']) · Q{{ $entry['question_number'] }}@endif</strong>
                            <span class="pr-chip pr-chip--{{ strtolower($entry['subject_bucket']) }}">{{ $entry['subject_bucket'] }}: {{ $entry['chapter'] }}</span>
                            <span class="pr-chip pr-chip--red">{{ $entry['taxonomy_label'] }}</span>
                            @if(empty($entry['tagged']))
                                <span class="pr-chip">Untagged</span>
                            @endif
                        </div>
                        <div class="mm-item__actions">
                            @if(!empty($entry['exam_user_id']))
                                <a class="mm-tag-link" href="{{ route('mistake_monitor_input', ['user_exam_id' => $entry['exam_user_id'], 'id' => $entry['id']]) }}">Tag reason</a>
                            @endif
                            <form method="post" action="{{ route('common_confusion.master') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $entry['id'] }}">
                                <input type="hidden" name="return" value="{{ $mmUrl() }}">
                                <button type="submit" class="mm-master {{ $entry['mastered'] ? 'is-on' : '' }}">
                                    <i class="fas fa-check"></i>
                                    {{ $entry['mastered'] ? 'Marked as Mastered' : 'Mark as Mastered' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="mm-item__q">&ldquo;{{ $entry['snippet'] }}&rdquo;</p>
                    <div class="mm-note">
                        <strong>Clinical Retention Note:</strong> {{ $entry['note'] }}
                    </div>
                </div>
            @empty
                <div class="pr-empty">
                    @if((int) ($mm['total'] ?? 0) === 0)
                        No incorrect answers in this Mode / Subject yet. Give a mock, then wrong attempts appear here automatically.
                    @else
                        No mistake records match the selected subject, reason, or status filters.
                    @endif
                </div>
            @endforelse
        </div>

        @if((int) ($pager['total'] ?? 0) > 0)
            <div class="mm-pager">
                <span class="mm-pager__meta">Showing {{ $pager['from'] }}–{{ $pager['to'] }} of {{ $pager['total'] }}</span>
                @if($lastPage > 1)
                    <div class="mm-pager__btns">
                        @if($curPage <= 1)
                            <span class="mm-page-btn is-disabled">Prev</span>
                        @else
                            <a class="mm-page-btn" href="{{ $mmUrl(['mm_page' => $curPage - 1]) }}">Prev</a>
                        @endif
                        @foreach($pageList as $p)
                            @if($p === '...')
                                <span class="mm-pager__gap">…</span>
                            @elseif((int) $p === $curPage)
                                <span class="mm-page-btn is-on">{{ $p }}</span>
                            @else
                                <a class="mm-page-btn" href="{{ $mmUrl(['mm_page' => $p]) }}">{{ $p }}</a>
                            @endif
                        @endforeach
                        @if($curPage >= $lastPage)
                            <span class="mm-page-btn is-disabled">Next</span>
                        @else
                            <a class="mm-page-btn" href="{{ $mmUrl(['mm_page' => $curPage + 1]) }}">Next</a>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
(function () {
    var root = document.getElementById('mmReasonPicker');
    if (!root || root.dataset.bound === '1') return;
    root.dataset.bound = '1';

    var trigger = document.getElementById('mmReasonTrigger');
    var panel = document.getElementById('mmReasonPanel');
    var searchToggle = document.getElementById('mmReasonSearchToggle');
    var searchWrap = document.getElementById('mmReasonSearchWrap');
    var searchInput = document.getElementById('mmReasonSearch');
    var emptyState = document.getElementById('mmReasonEmpty');
    var options = Array.prototype.slice.call(root.querySelectorAll('.mm-reason-pick__option'));

    function openPanel() {
        root.classList.add('is-open');
        panel.hidden = false;
        trigger.setAttribute('aria-expanded', 'true');
    }

    function closePanel() {
        root.classList.remove('is-open');
        panel.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');
        closeSearch();
    }

    function openSearch() {
        searchWrap.hidden = false;
        searchToggle.classList.add('is-active');
        searchToggle.setAttribute('aria-expanded', 'true');
        setTimeout(function () { searchInput.focus(); }, 0);
    }

    function closeSearch() {
        searchWrap.hidden = true;
        searchToggle.classList.remove('is-active');
        searchToggle.setAttribute('aria-expanded', 'false');
        searchInput.value = '';
        filterOptions('');
    }

    function filterOptions(query) {
        var q = (query || '').toLowerCase().trim();
        var visible = 0;
        options.forEach(function (opt) {
            var match = !q || (opt.getAttribute('data-search') || '').indexOf(q) !== -1;
            opt.parentElement.hidden = !match;
            if (match) visible += 1;
        });
        emptyState.hidden = visible > 0;
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        if (panel.hidden) openPanel();
        else closePanel();
    });

    searchToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        if (searchWrap.hidden) openSearch();
        else closeSearch();
    });

    searchInput.addEventListener('input', function () {
        filterOptions(this.value);
    });

    searchInput.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    panel.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    document.addEventListener('click', function (e) {
        if (!root.contains(e.target)) closePanel();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePanel();
    });
})();
</script>
