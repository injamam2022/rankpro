<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Exams Given | RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="title" content="Exams Given & Past Scorecards – RankPro">
    <meta name="description" content="Review past NEET mock scores, ranks, OMR sheets, and detailed analytics on RankPro.">

    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('web/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('web/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web/css/jquery.bxslider.css') }}">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/exams-given.css') }}" rel="stylesheet">

    <style>
        .calltoaction { display: none; }
    </style>
    @include('site.include.head_meta')
</head>

<body class="rp-dash-body">
    @include('site.include.body_meta')

    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>

    @php
        $filterType = (string) ($exam_type ?? '');
        $searchQ = (string) ($q ?? '');
        $baseQuery = array_filter([
            'subject_id' => $subject_id ?? null,
            'q' => $searchQ !== '' ? $searchQ : null,
        ], function ($v) {
            return $v !== null && $v !== '';
        });
        $searchOpen = $searchQ !== '';
    @endphp

    @include('site.include.student_dashboard_header')

    <section id="dashboard">
        <div class="container-fluid">
            <div class="dashboardAll dashboardPh">
                <div class="menuBarBtn menuBarBtnOpen">
                    <i class="fas fa-bars"></i>
                </div>

                <div class="dashboardLeft dashboardLeftOff">
                    @include('site.include.student_left_menu')
                </div>

                <div class="dashboardRight">
                    <div class="dashboardRightBody">
                        <div class="eg-portal">
                            <div class="eg-card">
                                <div class="eg-head">
                                    <div class="eg-head__title-row">
                                        <span class="eg-head__icon"><i class="fas fa-flask"></i></span>
                                        <h1 class="eg-head__title">Exams Given &amp; Past Scorecards</h1>
                                    </div>

                                    <div class="eg-head__tools">
                                        <button type="button"
                                                class="eg-search-toggle {{ $searchOpen ? 'is-active' : '' }}"
                                                id="egSearchToggle"
                                                aria-expanded="{{ $searchOpen ? 'true' : 'false' }}"
                                                aria-controls="egSearchPanel"
                                                title="Search exams">
                                            <i class="fas fa-search"></i>
                                            <span>Search</span>
                                        </button>

                                        <div class="eg-filters" role="tablist" aria-label="Exam mode filter">
                                            <a class="eg-filter is-all {{ $filterType === '' ? 'is-active' : '' }}"
                                               href="{{ route('exam_given', $baseQuery) }}">All</a>
                                            <a class="eg-filter is-online {{ $filterType === '1' ? 'is-active' : '' }}"
                                               href="{{ route('exam_given', array_merge($baseQuery, ['exam_type' => 1])) }}">Online Exam</a>
                                            <a class="eg-filter is-offline {{ $filterType === '2' ? 'is-active' : '' }}"
                                               href="{{ route('exam_given', array_merge($baseQuery, ['exam_type' => 2])) }}">Offline Exam</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="eg-search-panel {{ $searchOpen ? 'is-open' : '' }}" id="egSearchPanel" @if(!$searchOpen) hidden @endif>
                                    <form class="eg-search-form" method="get" action="{{ route('exam_given') }}" role="search">
                                        @if(!empty($exam_type))
                                            <input type="hidden" name="exam_type" value="{{ $exam_type }}">
                                        @endif
                                        @if(!empty($subject_id))
                                            <input type="hidden" name="subject_id" value="{{ $subject_id }}">
                                        @endif
                                        <div class="eg-search-field">
                                            <i class="fas fa-search"></i>
                                            <input type="search"
                                                   name="q"
                                                   id="egSearchInput"
                                                   value="{{ $searchQ }}"
                                                   placeholder="Search by exam name or exam ID…"
                                                   autocomplete="off">
                                        </div>
                                        <button type="submit" class="eg-btn eg-btn--purple eg-btn--sm">Search</button>
                                        @if($searchQ !== '')
                                            <a class="eg-btn eg-btn--ghost eg-btn--sm"
                                               href="{{ route('exam_given', array_filter(['exam_type' => $exam_type ?: null, 'subject_id' => $subject_id ?: null])) }}">Clear</a>
                                        @endif
                                    </form>
                                </div>

                                <div class="eg-list">
                                    @forelse($exam_list as $value)
                                        @php
                                            $score = $value->total_number ?? 0;
                                            $max = $value->total_mark ?? 0;
                                            $rankRaw = trim((string) ($value->rank ?? ''));
                                            $rankLabel = $rankRaw !== ''
                                                ? (preg_match('/^AIR/i', $rankRaw) ? $rankRaw : ('AIR ' . $rankRaw))
                                                : '—';
                                            $attemptedDate = \Carbon\Carbon::parse($value->created_at)->format('Y-m-d');
                                            $modeClass = ((int) $value->type === 2) ? 'eg-badge--offline' : 'eg-badge--online';
                                        @endphp
                                        <article class="eg-item"
                                            data-title="{{ $value->name }}"
                                            data-mode="{{ $value->mode_label }}"
                                            data-date="{{ $attemptedDate }}"
                                            data-score="{{ $score }}"
                                            data-max="{{ $max }}"
                                            data-rank="{{ $rankLabel }}"
                                            data-omr="{{ $value->omr_url }}"
                                            data-analytics="{{ $value->analytics_url }}"
                                            data-subjects='@json($value->subject_scores ?? [])'>
                                            <div class="eg-item__row">
                                                <div class="eg-item__main">
                                                    <div class="eg-item__meta">
                                                        <span class="eg-badge {{ $modeClass }}">{{ $value->mode_label }}</span>
                                                        @if(!empty($value->is_custom))
                                                            <span class="eg-badge eg-badge--custom">Custom Test</span>
                                                        @endif
                                                        <span class="eg-item__date">Date: {{ $attemptedDate }}</span>
                                                    </div>
                                                    <h2 class="eg-item__name">{{ $value->name }}</h2>
                                                    <p class="eg-item__sub">
                                                        @if(!empty($value->is_custom))
                                                            Custom Test · Exam ID: {{ $value->exam_code }}
                                                        @elseif(!empty($value->exam_code))
                                                            Exam ID: {{ $value->exam_code }}
                                                        @else
                                                            Past scorecard &amp; rank summary
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="eg-item__right">
                                                    <div class="eg-stat eg-stat--score">
                                                        <span class="eg-stat__label">Score Obtained</span>
                                                        <div class="eg-stat__value">{{ $score }} <span>/ {{ $max }}</span></div>
                                                    </div>
                                                    <div class="eg-stat eg-stat--rank">
                                                        <span class="eg-stat__label">Rank</span>
                                                        <div class="eg-stat__value">{{ $rankLabel }}</div>
                                                    </div>
                                                    <button type="button" class="eg-btn eg-btn--purple eg-open-result">
                                                        Detailed Results &amp; Ranks
                                                        <i class="fas fa-trophy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </article>
                                    @empty
                                        <div class="eg-empty">
                                            @if($searchQ !== '')
                                                No exams matched “{{ $searchQ }}”.
                                            @else
                                                No exams found for the selected filter.
                                            @endif
                                        </div>
                                    @endforelse
                                </div>

                                @if($exam_list->total() > 0)
                                    <div class="eg-pagination">
                                        <div class="eg-pagination__meta">
                                            Showing {{ $exam_list->firstItem() }}–{{ $exam_list->lastItem() }} of {{ $exam_list->total() }}
                                        </div>
                                        {{ $exam_list->onEachSide(1)->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Result & Rank modal --}}
    <div class="eg-modal-backdrop" id="egResultModal" aria-hidden="true">
        <div class="eg-modal" role="dialog" aria-modal="true" aria-labelledby="egResultTitle">
            <button type="button" class="eg-modal__close" id="egResultClose" aria-label="Close">&times;</button>

            <div class="eg-modal__eyebrow">
                <i class="fas fa-flask"></i>
                Exam Evaluation &amp; All-India Rank
            </div>
            <h3 class="eg-modal__title" id="egResultTitle">—</h3>
            <div class="eg-modal__meta">
                <span class="eg-mode" id="egResultMode">Mode: —</span>
                <span class="eg-date" id="egResultDate">Date: —</span>
            </div>

            <div class="eg-modal__stats">
                <div class="eg-modal__stat eg-modal__stat--score">
                    <div class="eg-modal__stat-label">Score Achieved</div>
                    <div class="eg-modal__stat-value" id="egResultScore">—</div>
                </div>
                <div class="eg-modal__stat eg-modal__stat--rank">
                    <div class="eg-modal__stat-label">All-India Rank</div>
                    <div class="eg-modal__stat-value" id="egResultRank">—</div>
                </div>
            </div>

            <div class="eg-subjects" id="egSubjectsWrap" hidden>
                <div class="eg-subjects__title">Subject Performance:</div>
                <div id="egSubjectsList"></div>
            </div>

            <div class="eg-modal__foot">
                <button type="button" class="eg-btn eg-btn--ghost" id="egResultCloseBtn">Close</button>
                <button type="button" class="eg-btn eg-btn--purple" id="egReviewBtn">Review Full Solutions</button>
            </div>

            <div class="eg-chooser" id="egChooser">
                <div class="eg-chooser__title">Choose how you want to review:</div>
                <div class="eg-chooser__actions">
                    <a href="#" class="eg-chooser__btn eg-chooser__btn--omr" id="egOmrLink">
                        <i class="fas fa-search"></i>
                        <span>OMR</span>
                        <span class="eg-chooser__hint">Answer sheet view</span>
                    </a>
                    <a href="#" class="eg-chooser__btn eg-chooser__btn--action" id="egActionLink">
                        <i class="fas fa-chart-bar"></i>
                        <span>Answers Analytics</span>
                        <span class="eg-chooser__hint">Detailed action view</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('site.include.call_to_action')
    @include('site.include.how_to_use')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('web/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('web/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('web/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <script src="{{ asset('web/js/jquery.bxslider.js') }}"></script>
    <script src="{{ asset('web/js/main.js') }}"></script>

    <script>
      $(document).ready(function () {
        $(".menuBarBtn.menuBarBtnOpen").click(function () {
          $(".dashboardLeft").removeClass("dashboardLeftOff");
        });
        $(".menuBarBtn.menuBarBtnClose").click(function () {
          $(".dashboardLeft").addClass("dashboardLeftOff");
        });
      });
    </script>

    <script>
    (function () {
      var searchToggle = document.getElementById('egSearchToggle');
      var searchPanel = document.getElementById('egSearchPanel');
      var searchInput = document.getElementById('egSearchInput');

      if (searchToggle && searchPanel) {
        searchToggle.addEventListener('click', function () {
          var open = searchPanel.classList.toggle('is-open');
          searchToggle.classList.toggle('is-active', open);
          searchToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
          if (open) {
            searchPanel.removeAttribute('hidden');
            if (searchInput) setTimeout(function () { searchInput.focus(); }, 50);
          } else if (!(searchInput && searchInput.value.trim())) {
            searchPanel.setAttribute('hidden', '');
          }
        });
      }

      var modal = document.getElementById('egResultModal');
      var chooser = document.getElementById('egChooser');
      var omrLink = document.getElementById('egOmrLink');
      var actionLink = document.getElementById('egActionLink');
      var subjectsWrap = document.getElementById('egSubjectsWrap');
      var subjectsList = document.getElementById('egSubjectsList');

      function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        if (chooser) chooser.classList.remove('is-open');
      }

      function openModal(item) {
        if (!modal || !item) return;
        document.getElementById('egResultTitle').textContent = item.getAttribute('data-title') || '—';
        document.getElementById('egResultMode').textContent = 'Mode: ' + (item.getAttribute('data-mode') || '—');
        document.getElementById('egResultDate').textContent = 'Date: ' + (item.getAttribute('data-date') || '—');
        document.getElementById('egResultScore').textContent =
          (item.getAttribute('data-score') || '0') + ' / ' + (item.getAttribute('data-max') || '0');
        document.getElementById('egResultRank').textContent = item.getAttribute('data-rank') || '—';

        omrLink.href = item.getAttribute('data-omr') || '#';
        actionLink.href = item.getAttribute('data-analytics') || '#';

        var subjects = [];
        try {
          subjects = JSON.parse(item.getAttribute('data-subjects') || '[]') || [];
        } catch (e) {
          subjects = [];
        }

        if (subjects.length && subjectsList && subjectsWrap) {
          subjectsList.innerHTML = subjects.map(function (s) {
            return '<div class="eg-subjects__row"><span>' + (s.name || 'Subject') + ':</span><span>' +
              (s.score != null ? s.score : '—') + '</span></div>';
          }).join('');
          subjectsWrap.hidden = false;
        } else if (subjectsWrap) {
          subjectsWrap.hidden = true;
          if (subjectsList) subjectsList.innerHTML = '';
        }

        if (chooser) chooser.classList.remove('is-open');
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
      }

      document.querySelectorAll('.eg-open-result').forEach(function (btn) {
        btn.addEventListener('click', function () {
          openModal(btn.closest('.eg-item'));
        });
      });

      ['egResultClose', 'egResultCloseBtn'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', closeModal);
      });

      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target === modal) closeModal();
        });
      }

      var reviewBtn = document.getElementById('egReviewBtn');
      if (reviewBtn) {
        reviewBtn.addEventListener('click', function () {
          if (chooser) chooser.classList.toggle('is-open');
        });
      }
    })();
    </script>
</body>

</html>
