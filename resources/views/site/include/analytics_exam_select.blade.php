@php
    $selectedExamId = $exam_id ?? null;
    $currentSubjectId = $subject_id ?? '';
    $currentExamType = $exam_type ?? '';
    $analyticsExamList = $analytics_exam_list ?? collect();
    $currentRouteName = optional(request()->route())->getName();

    $pickerMap = [
        'answers_analytics' => ['overall' => 'answers_analytics', 'exam' => 'exam_answers_analytics'],
        'exam_answers_analytics' => ['overall' => 'answers_analytics', 'exam' => 'exam_answers_analytics'],
        'strength' => ['overall' => 'strength', 'exam' => 'exam_strength'],
        'exam_strength' => ['overall' => 'strength', 'exam' => 'exam_strength'],
        'weakness' => ['overall' => 'weakness', 'exam' => 'exam_weakness'],
        'exam_weakness' => ['overall' => 'weakness', 'exam' => 'exam_weakness'],
        'progress_report' => ['overall' => 'progress_report', 'exam' => 'exam_progress_report'],
        'exam_progress_report' => ['overall' => 'progress_report', 'exam' => 'exam_progress_report'],
    ];

    $pickerRoutes = $pickerMap[$currentRouteName] ?? ['overall' => 'answers_analytics', 'exam' => 'exam_answers_analytics'];
    $isOverallAnalytics = request()->routeIs($pickerRoutes['overall']);

    $overallUrl = route($pickerRoutes['overall'], array_filter([
        'subject_id' => $currentSubjectId,
        'exam_type' => $currentExamType,
    ]));

    $selectedLabel = 'Overall (All Exams)';
    $selectedUrl = $overallUrl;
    $examOptions = [];

    foreach ($analyticsExamList as $attempt) {
        $name = $attempt->name ?: 'Exam';
        $code = $attempt->exam_code ?: '';
        $date = !empty($attempt->created_at)
            ? \Carbon\Carbon::parse($attempt->created_at)->format('d M Y')
            : '';
        $url = route($pickerRoutes['exam'], array_filter([
            'exam_id' => $attempt->user_exam_id,
            'subject_id' => $currentSubjectId,
            'exam_type' => $currentExamType,
        ]));
        $searchText = strtolower(trim($name . ' ' . $code . ' ' . $date));
        $examOptions[] = [
            'id' => (string) $attempt->user_exam_id,
            'name' => $name,
            'code' => $code,
            'date' => $date,
            'url' => $url,
            'search' => $searchText,
        ];
        if (!$isOverallAnalytics && (string) $selectedExamId === (string) $attempt->user_exam_id) {
            $selectedLabel = $name . ($code ? ' · ' . $code : '');
            $selectedUrl = $url;
        }
    }
@endphp

<div class="ax-exam" id="analyticsExamPicker" data-selected-url="{{ $selectedUrl }}">
    <button type="button" class="ax-exam__trigger" id="axExamTrigger" aria-haspopup="listbox" aria-expanded="false">
        <span class="ax-exam__trigger-meta">
            <span class="ax-exam__eyebrow">Exam</span>
            <span class="ax-exam__value" id="axExamValue">{{ $selectedLabel }}</span>
        </span>
        <i class="fas fa-chevron-down ax-exam__caret" aria-hidden="true"></i>
    </button>

    <div class="ax-exam__panel" id="axExamPanel" hidden>
        <div class="ax-exam__toolbar">
            <button type="button" class="ax-exam__search-toggle" id="axExamSearchToggle" aria-label="Toggle search" aria-expanded="false" title="Search exams">
                <i class="fas fa-search"></i>
            </button>
            <div class="ax-exam__search" id="axExamSearchWrap" hidden>
                <input type="search" id="axExamSearch" class="ax-exam__search-input" placeholder="Search exam…" autocomplete="off">
            </div>
        </div>

        <ul class="ax-exam__list" id="axExamList" role="listbox">
            <li>
                <button
                    type="button"
                    class="ax-exam__option {{ $isOverallAnalytics ? 'is-active' : '' }}"
                    data-url="{{ $overallUrl }}"
                    data-search="overall all exams"
                    role="option"
                    @if($isOverallAnalytics) aria-selected="true" @endif
                >
                    <span class="ax-exam__option-title">Overall</span>
                    <span class="ax-exam__option-sub">All exams combined</span>
                </button>
            </li>
            @foreach($examOptions as $option)
                @php $isActive = !$isOverallAnalytics && (string) $selectedExamId === $option['id']; @endphp
                <li>
                    <button
                        type="button"
                        class="ax-exam__option {{ $isActive ? 'is-active' : '' }}"
                        data-url="{{ $option['url'] }}"
                        data-search="{{ $option['search'] }}"
                        role="option"
                        @if($isActive) aria-selected="true" @endif
                    >
                        <span class="ax-exam__option-title">{{ $option['name'] }}</span>
                        <span class="ax-exam__option-sub">
                            @if($option['code']){{ $option['code'] }}@endif
                            @if($option['code'] && $option['date']) · @endif
                            @if($option['date']){{ $option['date'] }}@endif
                        </span>
                    </button>
                </li>
            @endforeach
            <li class="ax-exam__empty" id="axExamEmpty" hidden>No exams match</li>
        </ul>
    </div>
</div>

<style>
.ax-exam {
    position: relative;
    margin-left: auto;
    width: min(280px, 100%);
    z-index: 20;
}
.ax-exam__trigger {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    border: 1px solid #e8ecf4;
    background: #f8f9fc;
    border-radius: 12px;
    padding: 8px 12px;
    text-align: left;
    cursor: pointer;
    transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
}
.ax-exam__trigger:hover,
.ax-exam.is-open .ax-exam__trigger {
    border-color: #d0d7e8;
    background: #fff;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
}
.ax-exam__trigger-meta {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 1px;
}
.ax-exam__eyebrow {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: #98a2b3;
    line-height: 1.2;
}
.ax-exam__value {
    font-size: 13px;
    font-weight: 600;
    color: #101828;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 210px;
    line-height: 1.3;
}
.ax-exam__caret {
    font-size: 10px;
    color: #98a2b3;
    flex-shrink: 0;
    transition: transform .15s ease;
}
.ax-exam.is-open .ax-exam__caret {
    transform: rotate(180deg);
}
.ax-exam__panel {
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    width: min(320px, calc(100vw - 48px));
    background: #fff;
    border: 1px solid #eef0f5;
    border-radius: 14px;
    box-shadow: 0 12px 28px rgba(16, 24, 40, 0.1);
    overflow: hidden;
}
.ax-exam__toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-bottom: 1px solid #f2f4f7;
}
.ax-exam__search-toggle {
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 8px;
    background: #f4f6fa;
    color: #667085;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .15s ease, color .15s ease;
}
.ax-exam__search-toggle:hover,
.ax-exam__search-toggle.is-active {
    background: rgba(53, 97, 255, 0.1);
    color: #3561ff;
}
.ax-exam__search {
    flex: 1;
    min-width: 0;
}
.ax-exam__search-input {
    width: 100%;
    border: 1px solid #e8ecf4;
    background: #fafbfc;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: 13px;
    color: #101828;
    outline: none;
}
.ax-exam__search-input:focus {
    border-color: #3561ff;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(53, 97, 255, 0.1);
}
.ax-exam__list {
    list-style: none;
    margin: 0;
    padding: 6px;
    max-height: 260px;
    overflow-y: auto;
}
.ax-exam__option {
    width: 100%;
    border: 0;
    background: transparent;
    border-radius: 10px;
    padding: 9px 10px;
    text-align: left;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 2px;
    transition: background .12s ease;
}
.ax-exam__option:hover {
    background: #f4f6fa;
}
.ax-exam__option.is-active {
    background: rgba(53, 97, 255, 0.08);
}
.ax-exam__option-title {
    font-size: 13px;
    font-weight: 600;
    color: #101828;
    line-height: 1.3;
}
.ax-exam__option.is-active .ax-exam__option-title {
    color: #3561ff;
}
.ax-exam__option-sub {
    font-size: 11px;
    color: #98a2b3;
    line-height: 1.3;
}
.ax-exam__empty {
    padding: 16px 10px;
    text-align: center;
    font-size: 12px;
    color: #98a2b3;
}
@media (max-width: 767px) {
    .ax-exam {
        width: 100%;
        margin-left: 0;
        margin-top: 8px;
    }
    .ax-exam__value {
        max-width: none;
    }
    .ax-exam__panel {
        left: 0;
        right: 0;
        width: auto;
    }
}
</style>

<script>
(function () {
    var root = document.getElementById('analyticsExamPicker');
    if (!root || root.dataset.bound === '1') return;
    root.dataset.bound = '1';

    var trigger = document.getElementById('axExamTrigger');
    var panel = document.getElementById('axExamPanel');
    var searchToggle = document.getElementById('axExamSearchToggle');
    var searchWrap = document.getElementById('axExamSearchWrap');
    var searchInput = document.getElementById('axExamSearch');
    var emptyState = document.getElementById('axExamEmpty');
    var options = Array.prototype.slice.call(root.querySelectorAll('.ax-exam__option'));

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
        options.forEach(function (btn) {
            var match = !q || (btn.getAttribute('data-search') || '').indexOf(q) !== -1;
            btn.parentElement.hidden = !match;
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

    options.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var url = btn.getAttribute('data-url');
            if (url && url !== root.getAttribute('data-selected-url')) {
                window.location.href = url;
                return;
            }
            closePanel();
        });
    });

    document.addEventListener('click', function (e) {
        if (!root.contains(e.target)) closePanel();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePanel();
    });
})();
</script>
