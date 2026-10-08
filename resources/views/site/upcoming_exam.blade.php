<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Give Exam | RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="title" content="Give Exam & Mock Test Portal – RankPro">
    <meta name="description" content="Take NEET mock tests on demand, view upcoming exam schedule, and manage your daily study plan on RankPro.">

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
    <link href="{{ asset('web/css/give-exam.css') }}" rel="stylesheet">

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
        $availableCount = (int) ($available_count ?? 0);
        $scheduledCount = (int) ($scheduled_count ?? 0);
        $attemptedCount = (int) ($attempted_count ?? 0);
        $highestScore = (int) ($highest_score ?? 0);
        $highestMax = (int) ($highest_max ?? 720);
        $nextSlotLabel = $next_slot_label ?? 'No upcoming slot';
        $searchQ = (string) ($q ?? '');
        $searchOpen = $searchQ !== '';
        $planDone = 2;
        $planTotal = 5;
        $planPct = $planTotal > 0 ? (int) round(($planDone / $planTotal) * 100) : 0;
        $scheduleList = $schedule_exam_list ?? collect();

        $calendarEvents = [];
        foreach ($scheduleList as $ex) {
            try {
                $start = \Carbon\Carbon::parse(($ex->exam_date ?? '') . ' ' . ($ex->exam_time ?: '00:00:00'));
                $end = !empty($ex->exam_end_date)
                    ? \Carbon\Carbon::parse($ex->exam_end_date . ' ' . ($ex->exam_end_time ?: '23:59:59'))
                    : $start->copy()->addMinutes((int) (($ex->total_time_for_exam ?? 3) * 60));
                $calendarEvents[] = [
                    'id' => $ex->id,
                    'title' => $ex->name,
                    'date' => $start->format('Y-m-d'),
                    'day' => (int) $start->format('j'),
                    'month' => (int) $start->format('n') - 1,
                    'year' => (int) $start->format('Y'),
                    'time' => $start->format('g:i A') . ' – ' . $end->format('g:i A') . ' IST',
                    'mode' => ((int) $ex->type === 2) ? 'Offline Examination' : 'Online Examination',
                    'offline' => ((int) $ex->type === 2),
                ];
            } catch (\Throwable $e) {
                // skip bad dates
            }
        }
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
                        <div class="ge-portal">

                            {{-- Hero + metrics --}}
                            <div class="ge-card ge-hero">
                                <div class="ge-hero__top">
                                    <div class="ge-hero__title-row">
                                        <span class="ge-hero__icon"><i class="far fa-calendar-check"></i></span>
                                        <h1 class="ge-hero__title">Give Exam &amp; Mock Test Portal</h1>
                                        <span class="ge-badge-pink">NEET 2027</span>
                                    </div>

                                    <div class="ge-tabs" role="tablist">
                                        <button type="button" class="ge-tab is-active" data-ge-tab="convenience">Take Mock at Convenience</button>
                                        <button type="button" class="ge-tab" data-ge-tab="schedule">Upcoming Exam Schedule &amp; Calendar</button>
                                        <button type="button" class="ge-tab" data-ge-tab="plan">Study Plan &amp; Timetable</button>
                                    </div>
                                </div>

                                <div class="ge-metrics">
                                    <div class="ge-metric">
                                        <div class="ge-metric__label">Available Mocks</div>
                                        <div class="ge-metric__value">{{ $availableCount }} <span>active</span></div>
                                        <div class="ge-metric__hint is-green">Ready to take right now</div>
                                    </div>
                                    <div class="ge-metric ge-metric--blue">
                                        <div class="ge-metric__label">Scheduled Dates</div>
                                        <div class="ge-metric__value">{{ $scheduledCount }} <span>upcoming</span></div>
                                        <div class="ge-metric__hint is-blue">Next: {{ $nextSlotLabel }}</div>
                                    </div>
                                    <div class="ge-metric ge-metric--pink">
                                        <div class="ge-metric__label">Mocks Attempted</div>
                                        <div class="ge-metric__value">{{ $attemptedCount }} <span>completed</span></div>
                                        <div class="ge-metric__hint is-pink">Highest: {{ $highestScore }} / {{ $highestMax }}</div>
                                    </div>
                                    <div class="ge-metric ge-metric--green">
                                        <div class="ge-metric__label">Study Plan Adherence</div>
                                        <div class="ge-metric__value" id="gePlanPct">{{ $planPct }}%</div>
                                        <div class="ge-metric__hint is-green" id="gePlanHint">{{ $planDone }} of {{ $planTotal }} sessions done</div>
                                        <div class="ge-metric__bar"><span id="gePlanBar" style="width: {{ $planPct }}%"></span></div>
                                    </div>
                                </div>

                                <div class="ge-notice">
                                    <span class="ge-notice__icon"><i class="far fa-bell"></i></span>
                                    <div>
                                        <div class="ge-notice__title">
                                            Upcoming Notifications &amp; Push Reminders
                                            <span class="ge-badge-slate">Coming Soon</span>
                                        </div>
                                        <div class="ge-notice__text">
                                            Automated push and SMS alerts for mock windows, schedule changes, and study-plan reminders will appear here soon.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab: Convenience --}}
                            <div class="ge-card ge-panel ge-panel-section" data-ge-panel="convenience">
                                <div class="ge-panel__head">
                                    <div>
                                        <h2 class="ge-panel__title">
                                            Available Mocks for NEET 2027
                                            <span class="ge-badge-blue">On Demand</span>
                                        </h2>
                                        <p class="ge-panel__sub">Start eligible mocks instantly, or wait for scheduled windows.</p>
                                    </div>
                                    <div class="ge-panel__tools">
                                        <button type="button"
                                                class="ge-search-toggle {{ $searchOpen ? 'is-active' : '' }}"
                                                id="geSearchToggle"
                                                aria-expanded="{{ $searchOpen ? 'true' : 'false' }}"
                                                aria-controls="geSearchPanel"
                                                title="Search mocks">
                                            <i class="fas fa-search"></i>
                                            <span>Search</span>
                                        </button>
                                        @php
                                            $firstReady = $upcoming_exam_list->firstWhere('portal_status', 'available');
                                            if (!$firstReady) {
                                                $firstReady = $scheduleList->firstWhere('portal_status', 'available');
                                            }
                                        @endphp
                                        @if($firstReady && (int) $firstReady->type !== 2 && method_exists($firstReady, 'canBeStarted') && $firstReady->canBeStarted())
                                            <a href="{{ route('start_exam', ['id' => $firstReady->id]) }}"
                                               class="ge-btn ge-btn--blue"
                                               onclick="openStartExamConfirm(this.href, @json($firstReady->name), {{ !empty($firstReady->is_proctored) ? 'true' : 'false' }}); return false;">
                                                <i class="fas fa-bolt"></i> Quick Start Recommended Mock
                                            </a>
                                        @else
                                            <button type="button" class="ge-btn ge-btn--blue is-disabled" disabled>
                                                <i class="fas fa-bolt"></i> Quick Start Recommended Mock
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="ge-search-panel {{ $searchOpen ? 'is-open' : '' }}" id="geSearchPanel" @if(!$searchOpen) hidden @endif>
                                    <form class="ge-search-form" method="get" action="{{ route('upcoming_exam') }}" role="search">
                                        @if(!empty($exam_type))
                                            <input type="hidden" name="exam_type" value="{{ $exam_type }}">
                                        @endif
                                        @if(!empty($subject_id))
                                            <input type="hidden" name="subject_id" value="{{ $subject_id }}">
                                        @endif
                                        <div class="ge-search-field">
                                            <i class="fas fa-search"></i>
                                            <input type="search"
                                                   name="q"
                                                   id="geSearchInput"
                                                   value="{{ $searchQ }}"
                                                   placeholder="Search by mock name or exam ID…"
                                                   autocomplete="off">
                                        </div>
                                        <button type="submit" class="ge-btn ge-btn--blue ge-btn--sm">Search</button>
                                        @if($searchQ !== '')
                                            <a class="ge-btn ge-btn--ghost ge-btn--sm"
                                               href="{{ route('upcoming_exam', array_filter(['exam_type' => $exam_type ?: null, 'subject_id' => $subject_id ?: null])) }}">Clear</a>
                                        @endif
                                    </form>
                                </div>

                                <div class="ge-mocks">
                                    @forelse($upcoming_exam_list as $value)
                                        @php
                                            $mins = (int) round(((float) ($value->total_time_for_exam ?? 0)) * 60);
                                            if ($mins <= 0) { $mins = 200; }
                                            $status = $value->portal_status ?? 'scheduled';
                                            $isOffline = ((int) $value->type === 2);
                                            $syllabus = $value->subject_names ?: 'Full Syllabus · Physics · Chemistry · Biology';
                                        @endphp
                                        <article class="ge-mock">
                                            <div class="ge-mock__top">
                                                <div class="ge-mock__series">{{ $value->series_label ?? 'RANKPRO NEET ELITE SERIES 2027' }}</div>
                                                <div class="ge-mock__duration"><i class="far fa-clock"></i> {{ $mins }} mins</div>
                                            </div>
                                            <h3 class="ge-mock__name">{{ $value->name }}</h3>
                                            <p class="ge-mock__desc">{{ $syllabus }}</p>
                                            <div class="ge-mock__meta">
                                                <span>Questions: <strong>{{ $value->no_of_question ?? '—' }}</strong></span>
                                                <span>Marks: <strong>{{ $value->totals_marks_for_exam ?? '—' }}</strong></span>
                                                <span>Difficulty: <strong>{{ $value->difficulty_label ?? 'NTA Standard' }}</strong></span>
                                            </div>

                                            @if($isOffline && $value->exam_status === null)
                                                <div class="ge-accept-row">
                                                    <a href="{{ route('upcoming_exam.accept', ['id' => $value->id]) }}"
                                                       class="ge-btn-accept"
                                                       onclick="return confirm('Do you really want to accept this exam?');">Accept</a>
                                                    <a href="{{ route('upcoming_exam.reject', ['id' => $value->id]) }}"
                                                       class="ge-btn-reject"
                                                       onclick="return confirm('Do you really want to reject this exam?');">Reject</a>
                                                </div>
                                            @endif

                                            <div class="ge-mock__foot">
                                                @if($status === 'available')
                                                    <span class="ge-status"><span class="ge-status__dot is-ready"></span> Ready on Demand</span>
                                                    @if(!$isOffline && method_exists($value, 'canBeStarted') && $value->canBeStarted())
                                                        <a href="{{ route('start_exam', ['id' => $value->id]) }}"
                                                           class="ge-btn ge-btn--pink ge-btn--sm"
                                                           onclick="openStartExamConfirm(this.href, @json($value->name), {{ !empty($value->is_proctored) ? 'true' : 'false' }}); return false;">
                                                            <i class="fas fa-play"></i> Start Exam
                                                        </a>
                                                    @else
                                                        <span class="ge-btn ge-btn--disabled ge-btn--sm">Offline / Center Based</span>
                                                    @endif
                                                @elseif($status === 'attempted')
                                                    <span class="ge-status">
                                                        <span class="ge-status__dot is-done"></span>
                                                        Score: {{ $value->attempt_score ?? 0 }}/{{ $value->attempt_max ?? $value->totals_marks_for_exam ?? 720 }}
                                                    </span>
                                                    <span class="ge-btn ge-btn--disabled ge-btn--sm">Re-Attempt Mock</span>
                                                @else
                                                    <span class="ge-status">
                                                        <span class="ge-status__dot is-scheduled"></span>
                                                        Scheduled: {{ \Carbon\Carbon::parse($value->exam_date)->format('D, j M Y') }}
                                                    </span>
                                                    <span class="ge-btn ge-btn--disabled ge-btn--sm">Starts on Exam Date</span>
                                                @endif
                                            </div>
                                        </article>
                                    @empty
                                        <div class="ge-empty">
                                            @if($searchQ !== '')
                                                No mocks matched “{{ $searchQ }}”.
                                            @else
                                                No mocks available right now. Check back soon.
                                            @endif
                                        </div>
                                    @endforelse
                                </div>

                                @if(method_exists($upcoming_exam_list, 'total') && $upcoming_exam_list->total() > 0)
                                    <div class="ge-pagination">
                                        <div class="ge-pagination__meta">
                                            Showing {{ $upcoming_exam_list->firstItem() }}–{{ $upcoming_exam_list->lastItem() }} of {{ $upcoming_exam_list->total() }}
                                        </div>
                                        {{ $upcoming_exam_list->onEachSide(1)->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            </div>

                            {{-- Tab: Schedule --}}
                            <div class="ge-card ge-panel ge-panel-section" data-ge-panel="schedule" hidden>
                                <div class="ge-panel__head">
                                    <div>
                                        <h2 class="ge-panel__title">
                                            Upcoming Exam Schedule Calendar
                                            <span class="ge-badge-blue">Live Schedule</span>
                                        </h2>
                                        <p class="ge-panel__sub">Your assigned exam windows on the calendar.</p>
                                    </div>
                                </div>

                                <div class="ge-cal">
                                    <div class="ge-cal__toolbar">
                                        <div class="ge-cal__month" id="geCalMonthLabel">—</div>
                                        <div class="ge-cal__nav">
                                            <button type="button" id="geCalPrev" aria-label="Previous month"><i class="fas fa-chevron-left"></i></button>
                                            <button type="button" id="geCalNext" aria-label="Next month"><i class="fas fa-chevron-right"></i></button>
                                        </div>
                                    </div>
                                    <div class="ge-cal__grid" id="geCalDows">
                                        <div class="ge-cal__dow">Sun</div>
                                        <div class="ge-cal__dow">Mon</div>
                                        <div class="ge-cal__dow">Tue</div>
                                        <div class="ge-cal__dow">Wed</div>
                                        <div class="ge-cal__dow">Thu</div>
                                        <div class="ge-cal__dow">Fri</div>
                                        <div class="ge-cal__dow">Sat</div>
                                    </div>
                                    <div class="ge-cal__grid" id="geCalDays"></div>
                                </div>

                                <div class="ge-schedule-list">
                                    <div class="ge-schedule-list__title">Upcoming Tests Detailed Schedule:</div>
                                    @forelse($scheduleList->where('portal_status', '!=', 'attempted') as $value)
                                        @php
                                            $start = \Carbon\Carbon::parse(($value->exam_date ?? now()->toDateString()) . ' ' . ($value->exam_time ?: '00:00:00'));
                                            $end = !empty($value->exam_end_date)
                                                ? \Carbon\Carbon::parse($value->exam_end_date . ' ' . ($value->exam_end_time ?: '23:59:59'))
                                                : $start->copy()->addMinutes((int) round(((float) ($value->total_time_for_exam ?? 3)) * 60));
                                        @endphp
                                        <div class="ge-schedule-item">
                                            <div class="ge-schedule-item__left">
                                                <span class="{{ ((int)$value->type === 2) ? 'ge-badge-pink' : 'ge-badge-blue' }}">
                                                    {{ ((int)$value->type === 2) ? 'Offline Examination' : 'Online Examination' }}
                                                </span>
                                                <span class="ge-schedule-item__name">{{ $value->name }}</span>
                                            </div>
                                            <div class="ge-schedule-item__when">
                                                <span><i class="far fa-calendar"></i> {{ $start->format('Y-m-d') }}</span>
                                                <span><i class="far fa-clock"></i> {{ $start->format('h:i A') }} - {{ $end->format('h:i A') }} IST</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="ge-empty">No upcoming scheduled tests.</div>
                                    @endforelse
                                </div>

                                <div class="ge-advice">
                                    <div class="ge-advice__title">Circadian Rhythm Advice for NEET 2027</div>
                                    <p class="ge-advice__text">
                                        Train your focus during the official NEET UG window (2:00 PM – 5:20 PM). Schedule at least one full mock in that slot each week so exam-day alertness feels natural.
                                    </p>
                                </div>
                            </div>

                            {{-- Tab: Study Plan --}}
                            <div class="ge-card ge-panel ge-panel-section" data-ge-panel="plan" hidden>
                                <div class="ge-panel__head">
                                    <div>
                                        <h2 class="ge-panel__title">
                                            NEET 2027 Daily Preparation Study Plan &amp; Timetable
                                            <span class="ge-badge-blue">NCERT High-Yield</span>
                                        </h2>
                                        <p class="ge-panel__sub">Tap a row to mark it complete. Plans are saved on this device.</p>
                                    </div>
                                    <div class="ge-plan-meta">
                                        <span>Today's Target: <strong id="gePlanHours">8.5 Study Hours</strong></span>
                                        <button type="button" class="ge-btn ge-btn--blue ge-btn--sm" id="geOpenPlanModal">
                                            <i class="fas fa-plus"></i> Add Study Plan
                                        </button>
                                    </div>
                                </div>

                                <div class="ge-plan-list" id="gePlanList"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Add Study Plan Modal --}}
    <div class="ge-modal-backdrop" id="gePlanModal" aria-hidden="true">
        <div class="ge-modal" role="dialog" aria-modal="true" aria-labelledby="gePlanModalTitle">
            <div class="ge-modal__head">
                <h3 class="ge-modal__title" id="gePlanModalTitle">
                    <i class="far fa-calendar-check" style="color:#2563eb"></i>
                    Add Study Plan &amp; Timetable Item
                </h3>
                <button type="button" class="ge-modal__close" data-ge-close>&times;</button>
            </div>

            <div class="ge-error" id="gePlanError"></div>

            <form id="gePlanForm" autocomplete="off">
                <div class="ge-field">
                    <div class="ge-field__label">1. Choose Subject <span style="color:#ef4444">*</span></div>
                    <div class="ge-subj-picks">
                        <button type="button" class="ge-subj-pick is-phy" data-subject="Physics">⚡ Physics</button>
                        <button type="button" class="ge-subj-pick is-chem" data-subject="Chemistry">🧪 Chemistry</button>
                        <button type="button" class="ge-subj-pick is-bio is-active" data-subject="Biology">🧬 Biology</button>
                    </div>
                </div>

                <div class="ge-field">
                    <div class="ge-field__label">
                        <span>2. Topic <span style="color:#ef4444">* (Mandatory)</span></span>
                        <span class="ge-field__hint is-req">Required</span>
                    </div>
                    <input type="text" class="ge-input" id="gePlanTopic" placeholder="e.g. Endocrine System & Hormone Regulation" required>
                </div>

                <div class="ge-field">
                    <div class="ge-field__label">
                        <span>3. Sub-topic <span style="color:#94a3b8;font-weight:500">(Optional)</span></span>
                        <span class="ge-field__hint is-opt">Optional</span>
                    </div>
                    <input type="text" class="ge-input" id="gePlanSubTopic" placeholder="e.g. Thyroid, Adrenal & Pituitary Hormones">
                </div>

                <div class="ge-row-2">
                    <div class="ge-field">
                        <div class="ge-field__label">Target Output Goal</div>
                        <input type="text" class="ge-input" id="gePlanTarget" value="Solve 45 High-Yield NCERT Questions">
                    </div>
                    <div class="ge-field">
                        <div class="ge-field__label">Duration &amp; Slot</div>
                        <div class="ge-dur-row">
                            <input type="number" class="ge-input" id="gePlanDuration" min="15" max="240" step="15" value="60">
                            <input type="text" class="ge-input" id="gePlanSlot" value="06:00 PM – 07:00 PM">
                        </div>
                    </div>
                </div>

                <div class="ge-modal__foot">
                    <div class="ge-priority">
                        <span class="ge-priority__label">Priority:</span>
                        <div class="ge-priority__group">
                            <button type="button" class="ge-priority__btn is-high is-active" data-priority="High">High</button>
                            <button type="button" class="ge-priority__btn is-med" data-priority="Medium">Medium</button>
                        </div>
                    </div>
                    <div class="ge-modal__actions">
                        <button type="button" class="ge-link-cancel" data-ge-close>Cancel</button>
                        <button type="submit" class="ge-btn ge-btn--blue">
                            <i class="fas fa-plus"></i> Add to Study Plan
                        </button>
                    </div>
                </div>
            </form>
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
      var searchToggle = document.getElementById('geSearchToggle');
      var searchPanel = document.getElementById('geSearchPanel');
      var searchInput = document.getElementById('geSearchInput');

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

      var tabs = document.querySelectorAll('[data-ge-tab]');
      var panels = document.querySelectorAll('[data-ge-panel]');

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          var key = tab.getAttribute('data-ge-tab');
          tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
          panels.forEach(function (p) {
            var show = p.getAttribute('data-ge-panel') === key;
            if (show) p.removeAttribute('hidden');
            else p.setAttribute('hidden', '');
          });
        });
      });

      /* Calendar */
      var events = @json($calendarEvents);
      var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
      var now = new Date();
      var calMonth = now.getMonth();
      var calYear = now.getFullYear();
      if (events.length) {
        calMonth = events[0].month;
        calYear = events[0].year;
      }

      function renderCalendar() {
        var label = document.getElementById('geCalMonthLabel');
        var daysEl = document.getElementById('geCalDays');
        if (!label || !daysEl) return;
        label.textContent = monthNames[calMonth] + ' ' + calYear;

        var firstDow = new Date(calYear, calMonth, 1).getDay();
        var daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
        var html = '';
        var i;
        for (i = 0; i < firstDow; i++) {
          html += '<div class="ge-cal__day is-muted"></div>';
        }
        for (var d = 1; d <= daysInMonth; d++) {
          var dayEvents = events.filter(function (e) {
            return e.day === d && e.month === calMonth && e.year === calYear;
          });
          html += '<div class="ge-cal__day"><div class="ge-cal__num">' + d + '</div>';
          dayEvents.slice(0, 2).forEach(function (ev) {
            html += '<span class="ge-cal__event' + (ev.offline ? ' is-offline' : '') + '" title="' + (ev.title || '') + '">' + (ev.title || '') + '</span>';
          });
          html += '</div>';
        }
        var cells = firstDow + daysInMonth;
        var pad = (7 - (cells % 7)) % 7;
        for (i = 0; i < pad; i++) {
          html += '<div class="ge-cal__day is-muted"></div>';
        }
        daysEl.innerHTML = html;
      }

      var prevBtn = document.getElementById('geCalPrev');
      var nextBtn = document.getElementById('geCalNext');
      if (prevBtn) prevBtn.addEventListener('click', function () {
        calMonth -= 1;
        if (calMonth < 0) { calMonth = 11; calYear -= 1; }
        renderCalendar();
      });
      if (nextBtn) nextBtn.addEventListener('click', function () {
        calMonth += 1;
        if (calMonth > 11) { calMonth = 0; calYear += 1; }
        renderCalendar();
      });
      renderCalendar();

      /* Study plan (localStorage) */
      var STORAGE_KEY = 'rankpro_give_exam_study_plan_v1';
      var defaultPlan = [
        { id: 'p1', subject: 'Biology', topic: 'NCERT Line-by-Line: Human Endocrine System', subTopic: '', targetOutput: 'Revise + 40 PYQs', durationMins: 90, timeSlot: '06:00 AM – 07:30 AM', priority: 'High', completed: true },
        { id: 'p2', subject: 'Physics', topic: 'Rotational Dynamics', subTopic: 'Torque & Angular Momentum', targetOutput: 'Solve 45 High-Yield NCERT Questions', durationMins: 60, timeSlot: '10:00 AM – 11:00 AM', priority: 'High', completed: false },
        { id: 'p3', subject: 'Chemistry', topic: 'Chemical Bonding & Molecular Structure', subTopic: '', targetOutput: 'NCERT Exemplar Set A', durationMins: 75, timeSlot: '04:00 PM – 05:15 PM', priority: 'Medium', completed: false },
        { id: 'p4', subject: 'Biology', topic: 'RankPro Mock Exam Window', subTopic: 'Full Syllabus Simulation', targetOutput: 'Complete live mock under timer', durationMins: 200, timeSlot: '02:00 PM – 05:20 PM', priority: 'High', completed: false },
        { id: 'p5', subject: 'Physics', topic: 'Current Electricity Revision', subTopic: '', targetOutput: 'Error log + formula sheet', durationMins: 45, timeSlot: '08:00 PM – 08:45 PM', priority: 'Medium', completed: true }
      ];

      function loadPlan() {
        try {
          var raw = localStorage.getItem(STORAGE_KEY);
          if (!raw) return defaultPlan.slice();
          var parsed = JSON.parse(raw);
          return Array.isArray(parsed) && parsed.length ? parsed : defaultPlan.slice();
        } catch (e) {
          return defaultPlan.slice();
        }
      }

      function savePlan(plan) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(plan));
      }

      var plan = loadPlan();
      var subjectClass = { Biology: 'ge-subj--bio', Physics: 'ge-subj--phy', Chemistry: 'ge-subj--chem' };

      function updatePlanMetrics() {
        var done = plan.filter(function (p) { return p.completed; }).length;
        var total = plan.length || 1;
        var pct = Math.round((done / total) * 100);
        var hours = (plan.reduce(function (s, p) { return s + (Number(p.durationMins) || 0); }, 0) / 60).toFixed(1);
        var pctEl = document.getElementById('gePlanPct');
        var hintEl = document.getElementById('gePlanHint');
        var barEl = document.getElementById('gePlanBar');
        var hoursEl = document.getElementById('gePlanHours');
        if (pctEl) pctEl.textContent = pct + '%';
        if (hintEl) hintEl.textContent = done + ' of ' + total + ' sessions done';
        if (barEl) barEl.style.width = pct + '%';
        if (hoursEl) hoursEl.textContent = hours + ' Study Hours';
      }

      function renderPlan() {
        var list = document.getElementById('gePlanList');
        if (!list) return;
        if (!plan.length) {
          list.innerHTML = '<div class="ge-empty">No study plan items yet. Click “Add Study Plan”.</div>';
          updatePlanMetrics();
          return;
        }
        list.innerHTML = plan.map(function (item) {
          var subj = subjectClass[item.subject] || 'ge-subj--bio';
          return (
            '<div class="ge-plan-item' + (item.completed ? ' is-done' : '') + '" data-id="' + item.id + '">' +
              '<div class="ge-plan-item__left">' +
                '<span class="ge-plan-check">' + (item.completed ? '✓' : '') + '</span>' +
                '<div class="ge-plan-item__body">' +
                  '<div class="ge-plan-item__tags">' +
                    '<span class="ge-subj ' + subj + '">' + item.subject + '</span>' +
                    '<span class="ge-plan-item__topic">' + (item.topic || item.title || '') + '</span>' +
                  '</div>' +
                  (item.subTopic ? '<div class="ge-plan-item__sub">↳ Sub-topic: ' + item.subTopic + '</div>' : '') +
                  '<div class="ge-plan-item__meta">' +
                    '<span>Target: ' + (item.targetOutput || '—') + '</span>' +
                    '<span>·</span>' +
                    '<span><i class="far fa-clock"></i> ' + (item.durationMins || 60) + ' mins (' + (item.timeSlot || '') + ')</span>' +
                  '</div>' +
                '</div>' +
              '</div>' +
              '<span class="ge-plan-status">' + (item.completed ? 'Completed' : 'Pending') + '</span>' +
            '</div>'
          );
        }).join('');

        list.querySelectorAll('.ge-plan-item').forEach(function (row) {
          row.addEventListener('click', function () {
            var id = row.getAttribute('data-id');
            plan = plan.map(function (p) {
              if (p.id === id) p.completed = !p.completed;
              return p;
            });
            savePlan(plan);
            renderPlan();
          });
        });
        updatePlanMetrics();
      }

      renderPlan();

      /* Modal */
      var modal = document.getElementById('gePlanModal');
      var openBtn = document.getElementById('geOpenPlanModal');
      var form = document.getElementById('gePlanForm');
      var planSubject = 'Biology';
      var planPriority = 'High';
      var placeholders = {
        Biology: { topic: 'e.g. Endocrine System & Hormone Regulation', sub: 'e.g. Thyroid, Adrenal & Pituitary Hormones' },
        Physics: { topic: 'e.g. Current Electricity & Kirchhoff Laws', sub: 'e.g. Meter Bridge Sensitivity & Wheatstone Bridge' },
        Chemistry: { topic: 'e.g. Coordination Compounds & Crystal Field Theory', sub: 'e.g. IUPAC Nomenclature & Magnetic Moments' }
      };

      function openModal() {
        if (!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.getElementById('gePlanError').classList.remove('is-visible');
      }

      function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
      }

      if (openBtn) openBtn.addEventListener('click', openModal);
      document.querySelectorAll('[data-ge-close]').forEach(function (btn) {
        btn.addEventListener('click', closeModal);
      });
      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target === modal) closeModal();
        });
      }

      document.querySelectorAll('.ge-subj-pick').forEach(function (btn) {
        btn.addEventListener('click', function () {
          planSubject = btn.getAttribute('data-subject');
          document.querySelectorAll('.ge-subj-pick').forEach(function (b) {
            b.classList.toggle('is-active', b === btn);
          });
          var ph = placeholders[planSubject] || placeholders.Biology;
          document.getElementById('gePlanTopic').placeholder = ph.topic;
          document.getElementById('gePlanSubTopic').placeholder = ph.sub;
        });
      });

      document.querySelectorAll('.ge-priority__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          planPriority = btn.getAttribute('data-priority');
          document.querySelectorAll('.ge-priority__btn').forEach(function (b) {
            b.classList.toggle('is-active', b === btn);
          });
        });
      });

      if (form) {
        form.addEventListener('submit', function (e) {
          e.preventDefault();
          var topic = (document.getElementById('gePlanTopic').value || '').trim();
          var err = document.getElementById('gePlanError');
          if (!topic) {
            err.textContent = 'Topic is mandatory. Please enter a topic title.';
            err.classList.add('is-visible');
            return;
          }
          err.classList.remove('is-visible');
          plan.unshift({
            id: 'plan-' + Date.now(),
            subject: planSubject,
            topic: topic,
            subTopic: (document.getElementById('gePlanSubTopic').value || '').trim(),
            targetOutput: (document.getElementById('gePlanTarget').value || '').trim() || 'Daily Target Practice',
            durationMins: Number(document.getElementById('gePlanDuration').value) || 60,
            timeSlot: (document.getElementById('gePlanSlot').value || '').trim() || 'Scheduled Slot',
            priority: planPriority,
            completed: false
          });
          savePlan(plan);
          document.getElementById('gePlanTopic').value = '';
          document.getElementById('gePlanSubTopic').value = '';
          closeModal();
          renderPlan();
          // switch to plan tab
          var planTab = document.querySelector('[data-ge-tab="plan"]');
          if (planTab) planTab.click();
        });
      }
    })();
    </script>

    @include('site.include.start_exam_confirm_modal')
</body>

</html>
