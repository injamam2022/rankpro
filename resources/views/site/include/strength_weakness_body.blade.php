@php
    $swRoute = request()->routeIs('exam_strength') ? 'exam_strength' : 'strength';
    $examType = $exam_type ?? '';
    $subjectId = $subject_id ?? '';
    $examId = $exam_id ?? null;
    $sw = $sw ?? ['summary' => ['mastered' => 0, 'focus' => 0, 'negative' => 0], 'strengths' => [], 'weaknesses' => [], 'mistakes' => []];
    $letters = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D'];

    $swUrl = function ($overrides = []) use ($swRoute, $examType, $subjectId, $examId) {
        $params = [
            'exam_id' => $examId,
            'subject_id' => $subjectId,
            'exam_type' => $examType,
        ];
        foreach ($overrides as $k => $v) {
            $params[$k] = $v;
        }
        if ($swRoute !== 'exam_strength') {
            unset($params['exam_id']);
        }
        return route($swRoute, array_filter($params, function ($v) {
            return $v !== null && $v !== '';
        }));
    };

    $subClass = function ($name) {
        $n = strtolower((string) $name);
        if (str_contains($n, 'phys')) return 'sw-subbadge--phy';
        if (str_contains($n, 'chem')) return 'sw-subbadge--che';
        if (str_contains($n, 'bio')) return 'sw-subbadge--bio';
        return 'sw-subbadge--phy';
    };

    $optHtml = function ($text, $isImage) {
        if ($isImage && $text) {
            return '<img src="'.e($text).'" alt="">';
        }
        return $text ?: '—';
    };

    $drillUrl = $swRoute === 'exam_strength'
        ? route('exam_strength.drilldown')
        : route('strength.drilldown');
    $reviewUrl = $swRoute === 'exam_strength'
        ? route('exam_strength.review_mistake')
        : route('strength.review_mistake');
    $listUrl = $swRoute === 'exam_strength'
        ? route('exam_strength.list')
        : route('strength.list');
    $pager = $sw['pager'] ?? [
        'strengths' => ['page' => 1, 'last_page' => 1, 'total' => 0, 'from' => 0, 'to' => 0, 'per_page' => 4],
        'weaknesses' => ['page' => 1, 'last_page' => 1, 'total' => 0, 'from' => 0, 'to' => 0, 'per_page' => 4],
        'mistakes' => ['page' => 1, 'last_page' => 1, 'total' => 0, 'from' => 0, 'to' => 0, 'per_page' => 8],
    ];
@endphp

<div class="sw-page">
    <div class="sw-head">
        <h1 class="sw-head__title">
            <span class="sw-head__icon"><i class="fas fa-stethoscope"></i></span>
            Strength &amp; Weakness Diagnostic Center
        </h1>
        <div class="sw-head__right">
            <div class="sw-scope">
                @include('site.include.analytics_exam_select')
            </div>
        </div>
    </div>

    <div class="sw-kpis">
        <div class="sw-kpi sw-kpi--ok">
            <div class="sw-kpi__label">Mastered High-Yield Topics</div>
            <div class="sw-kpi__n">{{ (int) ($sw['summary']['mastered'] ?? 0) }} Units</div>
            <div class="sw-kpi__hint">&gt;80% Accuracy</div>
        </div>
        <div class="sw-kpi sw-kpi--warn">
            <div class="sw-kpi__label">Critical Review Topics</div>
            <div class="sw-kpi__n">{{ (int) ($sw['summary']['focus'] ?? 0) }} Focus Areas</div>
            <div class="sw-kpi__hint">Needs targeted revision</div>
        </div>
        <div class="sw-kpi sw-kpi--bad">
            <div class="sw-kpi__label">Negative Marks at Stake</div>
            <div class="sw-kpi__n">{{ (int) ($sw['summary']['negative'] ?? 0) }} Potential Marks</div>
            <div class="sw-kpi__hint">Lost to wrong attempts</div>
        </div>
    </div>

    <div class="sw-cols">
        <div class="sw-col sw-col--ok">
            <div class="sw-col__head">
                <h2><i class="fas fa-lightbulb"></i> Strengths &amp; High-Yield Mastered Units</h2>
                <span class="sw-pill sw-pill--ok">&gt;80% Accuracy</span>
            </div>
            <div id="swStrengthList">
                @include('site.include.strength_weakness_units', ['units' => $sw['strengths'] ?? [], 'kind' => 'strength'])
            </div>
            <div id="swStrengthPager">
                @include('site.include.strength_weakness_pager', ['pager' => $pager['strengths'], 'kind' => 'strengths'])
            </div>
        </div>

        <div class="sw-col sw-col--bad">
            <div class="sw-col__head">
                <h2><i class="fas fa-exclamation-circle"></i> Weaknesses &amp; Negative Mark Traps</h2>
                <span class="sw-pill sw-pill--bad">Action Required</span>
            </div>
            <div id="swWeakList">
                @include('site.include.strength_weakness_units', ['units' => $sw['weaknesses'] ?? [], 'kind' => 'weakness'])
            </div>
            <div id="swWeakPager">
                @include('site.include.strength_weakness_pager', ['pager' => $pager['weaknesses'], 'kind' => 'weaknesses'])
            </div>
        </div>
    </div>

    <div class="sw-mistakes">
        <div class="sw-mistakes__head">
            <h2><i class="fas fa-times-circle" style="color:#dc2626;"></i> Mistake Review: Specific Questions &amp; NCERT Explanations</h2>
            <div class="sw-chips" id="swMistakeChips">
                <button type="button" class="sw-chip is-on" data-subject="all">All</button>
                @foreach($subject_list as $sub)
                    <button type="button" class="sw-chip" data-subject="{{ $sub->id }}">{{ $sub->name }}</button>
                @endforeach
            </div>
        </div>

        <div id="swMistakeList">
            @include('site.include.strength_weakness_mistakes', ['mistakes' => $sw['mistakes'] ?? []])
        </div>
        <div id="swMistakePager">
            @include('site.include.strength_weakness_pager', ['pager' => $pager['mistakes'], 'kind' => 'mistakes'])
        </div>
    </div>
</div>

<div class="sw-modal" id="swModal" hidden>
    <div class="sw-modal__bg" data-close="1"></div>
    <div class="sw-modal__card">
        <div class="sw-modal__top">
            <div>
                <div class="sw-modal__crumb" id="swCrumb"></div>
                <h3 id="swModalTitle">Drill down</h3>
                <div class="sw-modal__lead">Detailed diagnostic drill-down: NCERT weightage, essential formulas, student traps &amp; targeted questions</div>
            </div>
            <div>
                <span class="sw-pill" id="swKindPill">High Yield Strength</span>
                <button type="button" class="sw-x" data-close="1">&times;</button>
            </div>
        </div>
        <div class="sw-stats">
            <div class="sw-stat"><span>Accuracy</span><b id="swAcc">—</b></div>
            <div class="sw-stat"><span>Your Speed</span><b id="swSpeed">—</b></div>
            <div class="sw-stat"><span>Topper Benchmark</span><b id="swTop">—</b></div>
            <div class="sw-stat"><span>NEET Weightage</span><b id="swWeight" style="font-size:12px;">—</b></div>
        </div>
        <div class="sw-tabs">
            <button type="button" class="sw-tab is-on" data-panel="swFormulas">Formulas &amp; Concepts</button>
            <button type="button" class="sw-tab" data-panel="swTraps">Traps &amp; Pitfalls</button>
            <button type="button" class="sw-tab" data-panel="swMcqs">Targeted MCQs</button>
        </div>
        <div class="sw-panel" id="swFormulas"></div>
        <div class="sw-panel" id="swTraps" hidden></div>
        <div class="sw-panel" id="swMcqs" hidden></div>
        <div class="sw-modal__foot">
            <button type="button" class="sw-close" data-close="1">Close Drilldown</button>
            <form id="swCtaForm" method="post" action="{{ route('custom_test.from_subtopic') }}">
                @csrf
                <input type="hidden" name="sub_topic_id" id="swCtaSubId" value="">
                <button type="submit" class="sw-cta" id="swCta">Start 10 Targeted MCQs on this Subtopic</button>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    var listUrl = '{{ $listUrl }}';
    var drillUrl = '{{ $drillUrl }}';
    var reviewUrl = '{{ $reviewUrl }}';
    var subjectId = '{{ $subjectId }}';
    var examType = '{{ $examType }}';
    var examExtra = '{{ $examId ? '&exam_id='.$examId : '' }}';
    var mistakeSubject = 'all';
    var activeDrill = { id: '', kind: 'strength' };
    var letters = {1:'A',2:'B',3:'C',4:'D'};
    var pageRoot = document.querySelector('.sw-page');
    var modal = document.getElementById('swModal');

    function qs(kind, page) {
        return listUrl
            + '?kind=' + encodeURIComponent(kind)
            + '&page=' + encodeURIComponent(page)
            + '&subject_id=' + encodeURIComponent(subjectId)
            + '&exam_type=' + encodeURIComponent(examType)
            + (mistakeSubject && mistakeSubject !== 'all' ? '&mistake_subject=' + encodeURIComponent(mistakeSubject) : '')
            + examExtra;
    }

    function bindList(scope) {
        (scope || pageRoot).querySelectorAll('.sw-unit__btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.parentElement.classList.toggle('is-open');
            });
        });
        (scope || pageRoot).querySelectorAll('.sw-review').forEach(function (btn) {
            btn.addEventListener('click', function () {
                fetch(reviewUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ exam_result_id: btn.getAttribute('data-id') })
                }).then(function () {
                    loadKind('mistakes', currentPage('mistakes'));
                });
            });
        });
        (scope || pageRoot).querySelectorAll('.sw-drill').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openDrill(btn.getAttribute('data-id'), btn.getAttribute('data-kind'), 1);
            });
        });
        (scope || pageRoot).querySelectorAll('.sw-page-btn:not(.sw-mcq-page)').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (btn.disabled) return;
                loadKind(btn.getAttribute('data-kind'), parseInt(btn.getAttribute('data-page'), 10) || 1);
            });
        });
    }

    function currentPage(kind) {
        var wrap = kind === 'strengths' ? document.getElementById('swStrengthPager')
            : (kind === 'weaknesses' ? document.getElementById('swWeakPager') : document.getElementById('swMistakePager'));
        var el = wrap ? wrap.querySelector('.sw-pager') : null;
        return el ? parseInt(el.getAttribute('data-page'), 10) || 1 : 1;
    }

    function targets(kind) {
        if (kind === 'weaknesses') return { list: 'swWeakList', pager: 'swWeakPager' };
        if (kind === 'mistakes') return { list: 'swMistakeList', pager: 'swMistakePager' };
        return { list: 'swStrengthList', pager: 'swStrengthPager' };
    }

    function loadKind(kind, page) {
        var t = targets(kind);
        var list = document.getElementById(t.list);
        var pager = document.getElementById(t.pager);
        if (list) list.classList.add('is-loading');
        fetch(qs(kind, page), { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (list) list.innerHTML = data.html || '';
                if (pager) pager.innerHTML = data.pager_html || '';
                bindList(list);
                bindList(pager);
            })
            .finally(function () {
                if (list) list.classList.remove('is-loading');
            });
    }

    document.querySelectorAll('#swMistakeChips .sw-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            document.querySelectorAll('#swMistakeChips .sw-chip').forEach(function (c) { c.classList.remove('is-on'); });
            chip.classList.add('is-on');
            mistakeSubject = chip.getAttribute('data-subject') || 'all';
            loadKind('mistakes', 1);
        });
    });

    function closeModal() {
        modal.classList.remove('is-on');
        modal.hidden = true;
    }
    modal.querySelectorAll('[data-close]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });
    document.querySelectorAll('.sw-modal .sw-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.sw-modal .sw-tab').forEach(function (t) { t.classList.remove('is-on'); });
            tab.classList.add('is-on');
            ['swFormulas','swTraps','swMcqs'].forEach(function (id) {
                document.getElementById(id).hidden = id !== tab.getAttribute('data-panel');
            });
        });
    });

    function renderMcqs(data) {
        var mcqBox = document.getElementById('swMcqs');
        var mcqs = data.mcqs || [];
        var pager = data.mcq_pager || {};
        var html = '';
        if (!mcqs.length) {
            html = '<div class="sw-empty">No bank questions found for this subtopic.</div>';
        } else {
            html = mcqs.map(function (q) {
                var opts = '';
                ['A','B','C','D'].forEach(function (L, idx) {
                    opts += '<div class="sw-opt" data-idx="' + (idx+1) + '"><span><b>' + L + '.</b> ' + (q.options[L] || '—') + '</span></div>';
                });
                return '<div class="sw-mcq" data-correct="' + q.correct + '">'
                    + '<div class="sw-mcq__head"><span>MCQ #' + q.n + '</span><button type="button" class="sw-reveal">Reveal Solution &amp; Citation</button></div>'
                    + '<div class="sw-q">' + (q.text || '') + '</div>'
                    + '<div class="sw-opts">' + opts + '</div>'
                    + '<div class="sw-sol"><b>Correct Option: ' + (letters[q.correct] || '') + '</b><div>' + (q.solution || 'Solution not tagged yet.') + '</div></div>'
                    + '</div>';
            }).join('');
        }
        if (pager.total > 0) {
            html += '<div class="sw-pager sw-pager--modal"><span class="sw-pager__meta">Showing ' + pager.from + '–' + pager.to + ' of ' + pager.total + '</span>';
            if (pager.last_page > 1) {
                html += '<div class="sw-pager__btns">';
                html += '<button type="button" class="sw-page-btn sw-mcq-page" data-page="' + Math.max(1, pager.page - 1) + '"' + (pager.page <= 1 ? ' disabled' : '') + '>Prev</button>';
                html += '<button type="button" class="sw-page-btn sw-mcq-page" data-page="' + Math.min(pager.last_page, pager.page + 1) + '"' + (pager.page >= pager.last_page ? ' disabled' : '') + '>Next</button>';
                html += '</div>';
            }
            html += '</div>';
        }
        mcqBox.innerHTML = html;
        mcqBox.querySelectorAll('.sw-reveal').forEach(function (rev) {
            rev.addEventListener('click', function () {
                var card = rev.closest('.sw-mcq');
                card.classList.toggle('is-open');
                rev.textContent = card.classList.contains('is-open') ? 'Hide Solution' : 'Reveal Solution & Citation';
                if (card.classList.contains('is-open')) {
                    var correct = card.getAttribute('data-correct');
                    card.querySelectorAll('.sw-opt').forEach(function (opt) {
                        opt.classList.toggle('is-correct', opt.getAttribute('data-idx') === correct);
                    });
                }
            });
        });
        mcqBox.querySelectorAll('.sw-mcq-page').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (btn.disabled) return;
                openDrill(activeDrill.id, activeDrill.kind, parseInt(btn.getAttribute('data-page'), 10) || 1, true);
            });
        });
    }

    function openDrill(id, kind, mcqPage, keepTab) {
        activeDrill = { id: id, kind: kind };
        var url = drillUrl + '?sub_topic_id=' + encodeURIComponent(id)
            + '&kind=' + encodeURIComponent(kind)
            + '&mcq_page=' + encodeURIComponent(mcqPage || 1)
            + '&subject_id=' + encodeURIComponent(subjectId)
            + '&exam_type=' + encodeURIComponent(examType)
            + examExtra;
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('swCrumb').textContent = (data.subject || '') + '  /  ' + (data.chapter || '');
                document.getElementById('swModalTitle').textContent = data.name || 'Subtopic';
                document.getElementById('swKindPill').textContent = kind === 'weakness' ? 'Focus Area Weakness' : 'High Yield Strength';
                document.getElementById('swKindPill').className = 'sw-pill ' + (kind === 'weakness' ? 'sw-pill--bad' : 'sw-pill--ok');
                document.getElementById('swAcc').textContent = (data.accuracy || 0) + '%';
                document.getElementById('swSpeed').textContent = (data.speed || 0) + 's / Q';
                document.getElementById('swTop').textContent = (data.topper_speed || 0) + 's avg';
                document.getElementById('swWeight').textContent = data.weightage || '—';
                var ctaId = document.getElementById('swCtaSubId');
                var ctaBtn = document.getElementById('swCta');
                if (ctaId) ctaId.value = data.sub_topic_id || activeDrill.id || '';
                if (ctaBtn) ctaBtn.disabled = !(ctaId && ctaId.value);
                var formulas = (data.formulas || []);
                document.getElementById('swFormulas').innerHTML = formulas.length
                    ? formulas.map(function (t, i) { return '<div class="sw-formula"><b>' + (i+1) + '.</b> ' + t + '</div>'; }).join('')
                    : '<div class="sw-empty">No formula notes stored for this subtopic yet.</div>';
                var traps = (data.traps || []);
                document.getElementById('swTraps').innerHTML = traps.length
                    ? traps.map(function (t) { return '<div class="sw-trap"><b>Negative Mark Trap:</b> ' + t + '</div>'; }).join('')
                    : '<div class="sw-empty">No recorded traps in this scope.</div>';
                renderMcqs(data);
                if (!keepTab) {
                    document.querySelectorAll('.sw-modal .sw-tab').forEach(function (t, idx) {
                        t.classList.toggle('is-on', idx === 0);
                    });
                    document.getElementById('swFormulas').hidden = false;
                    document.getElementById('swTraps').hidden = true;
                    document.getElementById('swMcqs').hidden = true;
                }
                modal.hidden = false;
                modal.classList.add('is-on');
            });
    }

    var ctaForm = document.getElementById('swCtaForm');
    if (ctaForm) {
        ctaForm.addEventListener('submit', function (e) {
            var id = document.getElementById('swCtaSubId');
            var btn = document.getElementById('swCta');
            if (!id || !id.value) {
                e.preventDefault();
                return;
            }
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Creating 10 targeted MCQs…';
            }
        });
    }

    bindList(pageRoot);
})();
</script>
