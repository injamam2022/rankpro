<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard | RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="title" content="RankPro Student Dashboard">
    <meta name="description" content="Prepare for NEET with AI-powered tools. Access mock tests, analytics, and mentorship on RankPro.">

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
        $user_name = session()->get('parant_login_type') == 'P'
            ? ($user->father_full_name ?? '')
            : trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

        $modeLabel = 'Overall';
        if ((string) $exam_type === '1') {
            $modeLabel = 'Online';
        } elseif ((string) $exam_type === '2') {
            $modeLabel = 'Offline';
        }

        $subjectLabel = 'Combined';
        if (!empty($subject_id)) {
            foreach ($subject_list as $s) {
                if ((string) $s->id === (string) $subject_id) {
                    $subjectLabel = $s->name;
                    break;
                }
            }
        }

        $dashQs = [
            'subject_id' => $subject_id,
            'exam_type' => $exam_type,
            'exam_user_type' => $exam_user_type,
        ];

        $giveExams = collect($upcoming_list ?? [])
            ->concat($trending_test ?? [])
            ->concat($exam_schedule_list ?? [])
            ->unique('id')
            ->sortBy(function ($exam) {
                // Live exams first, then soonest schedule, keep PROCTO EX near top.
                $live = (method_exists($exam, 'canBeStarted') && $exam->canBeStarted()) ? 0 : 1;
                $procto = stripos((string) ($exam->name ?? ''), 'PROCTO EX') === 0 ? 0 : 1;
                $date = (string) ($exam->exam_date ?? '9999-12-31');
                return sprintf('%d-%d-%s-%06d', $live, $procto, $date, (int) ($exam->id ?? 0));
            })
            ->values();
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
                        <div class="rp-stack">

                            <div class="rp-card rp-filter">
                                <div class="rp-filter__group">
                                    <span class="rp-filter__label"><i class="fas fa-sliders-h"></i> Mode:</span>
                                    <div class="rp-seg">
                                        <a href="{{ route('dashboard', array_merge($dashQs, ['exam_type' => ''])) }}"
                                           class="{{ $exam_type === '' || $exam_type === null || (string)$exam_type === '3' ? 'is-active-pink' : '' }}">Overall</a>
                                        <a href="{{ route('dashboard', array_merge($dashQs, ['exam_type' => 1])) }}"
                                           class="{{ (string)$exam_type === '1' ? 'is-active-pink' : '' }}">Online</a>
                                        <a href="{{ route('dashboard', array_merge($dashQs, ['exam_type' => 2])) }}"
                                           class="{{ (string)$exam_type === '2' ? 'is-active-pink' : '' }}">Offline</a>
                                    </div>
                              </div>
                                <div class="rp-filter__group">
                                    <span class="rp-filter__label">Subject:</span>
                                    <div class="rp-seg">
                                        <a href="{{ route('dashboard', array_merge($dashQs, ['subject_id' => ''])) }}"
                                           class="{{ $subject_id === '' || $subject_id === null ? 'is-active-blue' : '' }}">Combined</a>
                                        @foreach($subject_list as $subj)
                                            <a href="{{ route('dashboard', array_merge($dashQs, ['subject_id' => $subj->id])) }}"
                                               class="{{ (string)$subject_id === (string)$subj->id ? 'is-active-blue' : '' }}">{{ $subj->name }}</a>
                                        @endforeach
                                    </div>
                                    <div class="rp-filter__active">
                                        Active: <strong>{{ $modeLabel }}</strong> &middot; <em>{{ $subjectLabel }}</em>
                                    </div>
                                </div>
                                        </div>
                                
                            <div class="rp-card">
                                <div class="rp-lb__head">
                                    <h2 class="rp-lb__title">
                                        <i class="fas fa-trophy" style="color:#f59e0b;"></i>
                                        Rank Pro Leader Board
                                        @if($exam_user_type)
                                            <small style="font-size:12px;color:#64748b;font-weight:600;">({{ $exam_user_type }})</small>
                                        @endif
                                    </h2>
                                    <div class="rp-lb__controls">
                                        <div class="rp-lb__exam-dd">
                                            <button type="button" class="rp-lb__exam-btn" id="rpExamTypeBtn">
                                                Exams <i class="fas fa-caret-down"></i>
                                            </button>
                                            <div class="rp-lb__exam-menu" id="rpExamTypeMenu">
                                                <a href="{{ route('dashboard', array_merge($dashQs, ['exam_user_type' => 'RNS'])) }}" class="{{ $exam_user_type=='RNS' ? 'active' : '' }}">RNS</a>
                                                <a href="{{ route('dashboard', array_merge($dashQs, ['exam_user_type' => 'RPS'])) }}" class="{{ $exam_user_type=='RPS' ? 'active' : '' }}">RPS</a>
                                                <a href="{{ route('dashboard', array_merge($dashQs, ['exam_user_type' => 'SNT'])) }}" class="{{ $exam_user_type=='SNT' ? 'active' : '' }}">SNT</a>
                                                <a href="{{ route('dashboard', array_merge($dashQs, ['exam_user_type' => 'OTS'])) }}" class="{{ $exam_user_type=='OTS' ? 'active' : '' }}">OTS</a>
                                            </div>
                                        </div>
                                        <label class="rp-switch">
                                            <span>My Score</span>
                                            <input type="checkbox" id="rpMyScoreToggle">
                                            <span class="rp-switch__track"></span>
                                        </label>
                                  </div>
                                </div>  
                                  
                                <div class="rp-lb-main" id="rpLbMain">
                                    <div class="rp-table-wrap">
                                        <table class="rp-table">
                                            <thead>
                                                <tr>
                                                    <th class="is-center" style="width:64px;">Rank</th>
                                                    <th>Student Name</th>
                                                    <th class="is-center">Total Marks</th>
                                                    <th class="is-center">%</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($leader_board as $key => $value)
                                                    @php
                                                        $isMe = (int)($value->user_id ?? 0) === (int) Auth::id();
                                                        $pct = ($value->total_mark > 0)
                                                            ? number_format(($value->total_number / $value->total_mark) * 100, 2)
                                                            : '—';
                                                        $img = !empty($value->profile_img)
                                                            ? basename(str_replace('\\', '/', $value->profile_img))
                                                            : '';
                                                    @endphp
                                                    <tr class="{{ $isMe ? 'is-you' : '' }}">
                                                        <td class="is-center"><span class="rp-rank">#{{ $key + 1 }}</span></td>
                                                        <td>
                                                            <div class="rp-name-cell">
                                                                @if($img !== '')
                                                                    <img src="{{ asset('uploads/profileImage/'.$img) }}" alt="">
                                                                @else
                                                                    <span class="rp-avatar-fallback">{{ strtoupper(substr($value->first_name ?? 'S', 0, 1)) }}</span>
                                                                @endif
                                                                <span>
                                                                    {{ $value->first_name }} {{ $value->last_name }}
                                                                    @if($isMe)<span class="rp-you-tag">YOU</span>@endif
                                                                </span>
                                                            </div>
                                                        </td> 
                                                        <td class="is-center">{{ $value->total_number }}/{{ $value->total_mark }}</td>
                                                        <td class="is-center">{{ $pct }}@if($pct !== '—')%@endif</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="is-center" style="padding:24px;color:#94a3b8;">No leaderboard data yet.</td>
                                                    </tr>
                                                @endforelse

                                                @if(!empty($leader_board_show_me) && $my_leader_board)
                                                    @php
                                                        $myPct = ($my_leader_board->total_mark > 0)
                                                            ? number_format(($my_leader_board->total_number / $my_leader_board->total_mark) * 100, 2)
                                                            : '—';
                                                        $myImg = !empty($my_leader_board->profile_img)
                                                            ? basename(str_replace('\\', '/', $my_leader_board->profile_img))
                                                            : '';
                                                    @endphp
                                                    <tr class="is-you">
                                                        <td class="is-center"><span class="rp-rank">#{{ $your_score }}</span></td>
                                                        <td>
                                                            <div class="rp-name-cell">
                                                                @if($myImg !== '')
                                                                    <img src="{{ asset('uploads/profileImage/'.$myImg) }}" alt="">
                                                            @else
                                                                    <span class="rp-avatar-fallback">{{ strtoupper(substr($user->first_name ?? 'Y', 0, 1)) }}</span>
                                                            @endif
                                                                <span>{{ $user_name }} <span class="rp-you-tag">YOU</span></span>
                                                            </div>
                                                        </td>
                                                        <td class="is-center">{{ $my_leader_board->total_number }}/{{ $my_leader_board->total_mark }}</td>
                                                        <td class="is-center">{{ $myPct }}@if($myPct !== '—')%@endif</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                      </div>
                                    <div class="rp-lb__foot">
                                        <span>Showing top {{ min(5, count($leader_board)) }} ranks</span>
                                        <a href="{{ route('leader_board') }}">View All Students <i class="fas fa-external-link-alt" style="font-size:10px;"></i></a>
                                    </div>
                                    </div>
                                    
                                <div class="rp-my-score-panel" id="rpMyScorePanel">
                                    @if($my_leader_board)
                                        <div class="rp-table-wrap" style="margin-bottom:12px;">
                                            <table class="rp-table">
                                            <thead>
                                                <tr>
                                                        <th>Student Name</th>
                                                        <th class="is-center">Total Marks</th>
                                                        <th class="is-center">%</th>
                                                        <th class="is-center">Rank</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @php
                                                        $myPct2 = ($my_leader_board->total_mark > 0)
                                                            ? number_format(($my_leader_board->total_number / $my_leader_board->total_mark) * 100, 2)
                                                            : '—';
                                                        $myImg2 = !empty($my_leader_board->profile_img)
                                                            ? basename(str_replace('\\', '/', $my_leader_board->profile_img))
                                                            : '';
                                                    @endphp
                                                    <tr class="is-you">
                                                        <td>
                                                            <div class="rp-name-cell">
                                                                @if($myImg2 !== '')
                                                                    <img src="{{ asset('uploads/profileImage/'.$myImg2) }}" alt="">
                                                                @else
                                                                    <span class="rp-avatar-fallback">{{ strtoupper(substr($user->first_name ?? 'Y', 0, 1)) }}</span>
                                                                @endif
                                                                <span>{{ $user_name }} <span class="rp-you-tag">YOU</span></span>
                                                            </div>
                                                        </td>
                                                        <td class="is-center">{{ $my_leader_board->total_number }}/{{ $my_leader_board->total_mark }}</td>
                                                        <td class="is-center">{{ $myPct2 }}@if($myPct2 !== '—')%@endif</td>
                                                        <td class="is-center"><span class="rp-rank">#{{ $your_score }}</span></td>
                                                    </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                        <div class="row g-3">
                                            @foreach($subject_list as $value)
                                                  <div class="col-sm-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div style="width:72px;height:72px;">
                                                            <canvas id="comparison_{{ $value->id }}"></canvas>
                                                              </div>
                                                        <div>
                                                            <div style="font-size:12px;font-weight:700;">{{ $value->name }}</div>
                                                            <div style="font-size:11px;color:#64748b;">{{ (int)$value->count }} correct</div>
                                                          </div>
                                                      </div>
                                                  </div>
                                            @endforeach
                                          </div>
                                    @else
                                        <div class="rp-empty">Take an exam to see your score here.</div>
                                    @endif
                              </div>
                          </div>
                          
                            <div class="rp-exam-grid">
                                <div class="rp-card">
                                    <div class="rp-section-head">
                                        <h3 class="rp-section-title">
                                            <span class="rp-icon-box is-purple"><i class="fas fa-flask"></i></span>
                                            Exams Given
                                        </h3>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <div class="rp-seg" id="rpGivenFilter">
                                                <a href="javascript:void(0)" data-mode="all" class="is-active-purple">All</a>
                                                <a href="javascript:void(0)" data-mode="1">Online Exam</a>
                                                <a href="javascript:void(0)" data-mode="2">Offline Exam</a>
                              </div>
                                            <a href="{{ route('exam_given') }}" class="rp-link-sm">Full History <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
                          </div>
                                    </div>

                                  @if(count($exam_list))
                                    <div class="rp-page-list" id="rpGivenList" data-page-size="5">
                                    @foreach($exam_list as $value)
                                            @php
                                                $examMode = (int)($value->type ?? 0);
                                                $modeText = $examMode === 1 ? 'Online Examination' : ($examMode === 2 ? 'Offline Examination' : 'Examination');
                                                $badgeClass = $examMode === 1 ? 'is-blue' : 'is-emerald';
                                                $scoreGot = $value->total_number ?? 0;
                                                $scoreMax = $value->total_mark ?? ($value->totals_marks_for_exam ?? '—');
                                                $examDateStr = \Carbon\Carbon::parse($value->exam_date)->format('Y-m-d');
                                                $participants = (int) ($value->participant_count ?? 0);
                                                $rankVal = $value->rank;
                                                $subjectScores = $value->subject_scores ?? [];
                                                $resultUrl = route('exam_result_detail', ['id' => $value->user_exam_id]);
                                            @endphp
                                            <div class="rp-exam-card rp-page-item" data-exam-mode="{{ $examMode }}">
                                                <div class="rp-exam-card__top">
                                                    <div style="min-width:0;flex:1;">
                                                        <div class="rp-badges">
                                                            <span class="rp-badge {{ $badgeClass }}">{{ $modeText }}</span>
                                                            <span class="rp-exam-card__meta">{{ $examDateStr }}</span>
                                                        </div>
                                                        <h4 class="rp-exam-card__title">{{ $value->name }}</h4>
                                                    </div>
                                                    <div class="rp-score-box">
                                                        <span>Score</span>
                                                        <strong>
                                                            {{ $scoreGot }}
                                                            <small>/ {{ $scoreMax }}</small>
                                                        </strong>
                                                    </div>
                                          </div>
                                                <div class="rp-exam-card__foot">
                                                    @if(!empty($rankVal))
                                                        <span class="rp-air">
                                                            <i class="fas fa-medal" style="color:#f59e0b;"></i>
                                                            All-India Rank:
                                                            <strong>AIR {{ $rankVal }}@if($participants > 0) of {{ number_format($participants) }}@endif</strong>
                                                        </span>
                                                    @else
                                                        <span></span>
                                                    @endif
                                                    <button type="button"
                                                        class="rp-btn-purple rp-open-eval"
                                                        data-title="{{ e($value->name) }}"
                                                        data-mode="{{ e($modeText) }}"
                                                        data-date="{{ e($examDateStr) }}"
                                                        data-score="{{ e($scoreGot) }}"
                                                        data-max="{{ e($scoreMax) }}"
                                                        data-rank="{{ e($rankVal ?: '') }}"
                                                        data-participants="{{ e($participants) }}"
                                                        data-result-url="{{ e($resultUrl) }}"
                                                        data-subjects="{{ e(json_encode($subjectScores)) }}">
                                                        Detailed Results &amp; Ranks
                                                        <i class="fas fa-external-link-alt" style="font-size:10px;"></i>
                                                    </button>
                                          </div>
                                      </div>
                                    @endforeach
                                    </div>
                                        <div class="rp-empty rp-given-empty" style="display:none;">No exams found for this filter.</div>
                                        <div class="rp-pager" id="rpGivenPager" aria-label="Exams Given pagination"></div>
                                  @else
                                        <div class="rp-empty">No exams given yet.</div>
                                  @endif
                                </div>

                                <div class="rp-card">
                                    <div class="rp-section-head">
                                        <h3 class="rp-section-title">
                                            <span class="rp-icon-box is-pink"><i class="far fa-calendar-check"></i></span>
                                            Give Exam
                                        </h3>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <button type="button" class="rp-btn-schedule" id="rpOpenSchedule"><i class="far fa-calendar-alt"></i> Exam Schedule</button>
                                            <a href="{{ route('upcoming_exam') }}" class="rp-link-sm" style="color:#e01a88 !important;">All Mocks <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
                                        </div>
                                    </div>

                                    @if($giveExams->count())
                                        <div class="rp-page-list" id="rpGiveList" data-page-size="6">
                                        @foreach($giveExams as $value)
                                            @php
                                                $isLive = method_exists($value, 'canBeStarted') && $value->canBeStarted();
                                                $givingCount = (int) ($value->giving_count ?? 0);
                                                $isTrending = !empty($value->is_live_trending);
                                                $examMode = (int)($value->type ?? 0);
                                                $modeText = $examMode === 1 ? 'Online Examination' : ($examMode === 2 ? 'Offline Examination' : 'Examination');
                                            @endphp
                                            <div class="rp-exam-card is-give rp-page-item">
                                                <div class="rp-give-top">
                                                    <div class="rp-badges">
                                                        @if($isLive)
                                                            <span class="rp-badge is-live">LIVE NOW</span>
                                @else
                                                            <span class="rp-badge is-sky">Upcoming</span>
                                                        @endif
                                                        @if($isTrending)
                                                            <span class="rp-badge is-orange"><i class="fas fa-fire"></i> Trending ({{ number_format($givingCount) }} Given)</span>
                                                        @endif
                                        </div>
                                                    <span class="rp-exam-card__mode-label">{{ $modeText }}</span>
                                    </div>
                                                <h4 class="rp-exam-card__title">{{ $value->name }}</h4>
                                                <div class="rp-exam-card__foot rp-give-foot">
                                                    <div class="rp-exam-card__meta">
                                                        <i class="far fa-clock"></i>
                                                        @if($isLive)
                                                            Date: Live Now
                                                            @if($value->exam_end_time)
                                                                &bull; Ends {{ \Carbon\Carbon::parse($value->exam_end_time)->format('g:i A') }} IST
                                                            @endif
                                                        @else
                                                            Date: {{ \Carbon\Carbon::parse($value->exam_date)->format('l, M j, Y') }}
                                                            @if($value->exam_time)
                                                                &bull; {{ \Carbon\Carbon::parse($value->exam_time)->format('h:i A') }} IST
                                                            @endif
                                @endif
                              </div>
                                                    @if($isLive)
                                                        <a href="{{ route('start_exam', ['id' => $value->id]) }}"
                                                           class="rp-btn-pink"
                                                           onclick="openStartExamConfirm(this.href, @json($value->name), {{ !empty($value->is_proctored) ? 'true' : 'false' }}); return false;">
                                                            <i class="fas fa-play" style="font-size:10px;"></i>
                                                            Take Mock Now
                                                        </a>
                                                    @else
                                                        <span class="rp-btn-disabled">Starts on Scheduled Date</span>
                                                    @endif
                                              </div>
                                            </div>
                                        @endforeach
                                        </div>
                                        <div class="rp-pager" id="rpGivePager" aria-label="Give Exam pagination"></div>
                                    @else
                                        <div class="rp-empty">No live or upcoming mocks right now.</div>
                                    @endif
                                </div>
                              </div>

                            <div class="rp-card">
                                <div class="rp-section-head">
                                    <div>
                                        <h3 class="rp-section-title">
                                            <span class="rp-icon-box is-amber"><i class="far fa-bell"></i></span>
                                            Notice &amp; Notification Centre
                                            <span class="rp-badge is-orange" style="margin-left:4px;">Latest Circulars</span>
                                        </h3>
                                        <p style="margin:6px 0 0;font-size:11px;color:#64748b;">Official RankPro admissions &amp; test updates</p>
                                    </div>
                                    <a href="{{ route('notice') }}" class="rp-link-sm is-blue">View All Notices <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
                                </div>

                                <div class="rp-notice-grid">
                                    @forelse(($dashboard_notices ?? []) as $notice)
                                        <a href="{{ route('notice') }}" class="rp-notice-card {{ !empty($notice->is_urgent) ? 'is-urgent' : '' }}">
                                            <div>
                                                <div class="rp-notice-card__top">
                                                    <span class="rp-notice-card__cat">{{ !empty($notice->is_urgent) ? 'Urgent' : 'Update' }}</span>
                                                    <span class="rp-notice-card__date">
                                                        {{ \Carbon\Carbon::parse($notice->created_at)->format('Y-m-d') }}
                                                    </span>
                                                </div>
                                                <h4 class="rp-notice-card__title">{{ $notice->name }}</h4>
                                                <p class="rp-notice-card__sum">{!! \Illuminate\Support\Str::limit(strip_tags($notice->description ?? ''), 110) !!}</p>
                                            </div>
                                            <div class="rp-notice-card__foot">
                                                <span>Read Circular</span>
                                                <i class="fas fa-external-link-alt" style="font-size:10px;"></i>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="rp-empty" style="grid-column:1/-1;">No notices yet.</div>
                                    @endforelse
                          </div>
                      </div>

                      @if(count($dashboard_banner))
                                <div class="rp-banner-strip">
                        <div class="dashboardSliderAll">
                          <ul class="dashboardSlider list-unstyled mb-0">
                                @foreach($dashboard_banner as $value)
                                  <li>
                                                    <div class="dashboardSliderImg" @if($value->video_link) onclick="openVideoModel('{{ $value->video_link }}')" @endif>
                                                        <img src="{{ asset('uploads/dashboard_banner/'.$value->icon) }}" class="img-fluid" alt="">
                                      </div>
                                  </li>
                                @endforeach
                          </ul>
                                    </div>
                        </div>
                      @endif
                      
                        </div>
                  </div>
              </div>
          </div>
      </div>
    </section>
    
    <div class="modal fade" id="examVideo">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
                <img src="{{ asset('web/images/examVideoClose_ic.png') }}" class="img-fluid examVideoClose_ic" data-bs-dismiss="modal" alt="">
          <iframe id="modal_video" width="100%" height="420" src=""></iframe>
        </div>
      </div>
    </div>

    <div class="rp-eval-modal" id="rpEvalModal" aria-hidden="true">
        <div class="rp-eval-modal__backdrop" data-rp-eval-close></div>
        <div class="rp-eval-modal__card" role="dialog" aria-modal="true" aria-labelledby="rpEvalTitle">
            <div class="rp-eval-modal__head">
                <p class="rp-eval-modal__eyebrow"><i class="fas fa-flask"></i> Exam Evaluation &amp; All-India Rank</p>
                <button type="button" class="rp-eval-modal__close" data-rp-eval-close aria-label="Close">&times;</button>
            </div>
            <h3 class="rp-eval-modal__title" id="rpEvalTitle">—</h3>
            <div class="rp-eval-modal__meta">
                <span class="rp-badge is-blue" id="rpEvalMode">Mode: —</span>
                <span id="rpEvalDate">Date: —</span>
            </div>
            <div class="rp-eval-stats">
                <div class="rp-eval-stat is-score">
                    <span>Score Achieved</span>
                    <strong id="rpEvalScore">—</strong>
                </div>
                <div class="rp-eval-stat is-rank">
                    <span>All-India Rank</span>
                    <strong id="rpEvalRank">—</strong>
                </div>
            </div>
            <div class="rp-eval-subjects">
                <h4>Subject Performance:</h4>
                <ul id="rpEvalSubjects">
                    <li><span>No subject breakdown available</span></li>
                </ul>
            </div>
            <div class="rp-eval-modal__actions">
                <button type="button" class="rp-btn-gray" data-rp-eval-close>Close</button>
                <a href="#" class="rp-btn-purple" id="rpEvalReviewBtn">Review Full Solutions</a>
            </div>
        </div>
    </div>

    <div class="rp-eval-modal" id="rpScheduleModal" aria-hidden="true">
        <div class="rp-eval-modal__backdrop" data-rp-schedule-close></div>
        <div class="rp-cal-modal" role="dialog" aria-modal="true" aria-labelledby="rpScheduleTitle">
            <div class="rp-cal-modal__head">
                <div class="rp-cal-modal__heading">
                    <span class="rp-cal-modal__icon" aria-hidden="true"><i class="far fa-calendar-alt"></i></span>
                    <div>
                        <h3 class="rp-cal-modal__title" id="rpScheduleTitle">NEET 2027 Upcoming Exam Schedule Calendar</h3>
                        <p class="rp-cal-modal__sub">All nationwide test series scheduled dates and examination windows.</p>
                    </div>
                </div>
                <button type="button" class="rp-cal-modal__close" data-rp-schedule-close aria-label="Close">&times;</button>
            </div>

            <div class="rp-cal-modal__list">
                @php $scheduleExams = collect($exam_schedule_list ?? []); @endphp
                @forelse($scheduleExams as $value)
                    @php
                        $isLive = method_exists($value, 'canBeStarted') && $value->canBeStarted();
                        $examMode = (int)($value->type ?? 0);
                        $modeText = $examMode === 1 ? 'Online Examination' : ($examMode === 2 ? 'Offline Examination' : 'Examination');
                        $badgeClass = $examMode === 2 ? 'is-offline' : 'is-online';
                        $startDate = !empty($value->exam_date) ? \Carbon\Carbon::parse($value->exam_date) : null;
                        $startTime = !empty($value->exam_time) ? \Carbon\Carbon::parse($value->exam_time) : null;
                        $endDate = !empty($value->exam_end_date) ? \Carbon\Carbon::parse($value->exam_end_date) : $startDate;
                        $endTime = !empty($value->exam_end_time) ? \Carbon\Carbon::parse($value->exam_end_time) : null;
                        $dateLine = 'Schedule to be announced';
                        if ($isLive) {
                            $dateLine = 'Live Now';
                            if ($endTime) {
                                $dateLine .= ' • Ends '.$endTime->format('h:i A').' IST';
                            }
                        } elseif ($startDate) {
                            $dateLine = $startDate->format('l, M j, Y');
                            if ($startTime) {
                                $dateLine .= ' • '.$startTime->format('h:i A');
                                if ($endTime) {
                                    $dateLine .= ' – '.$endTime->format('h:i A');
                                }
                                $dateLine .= ' IST';
                            }
                        }
                        $detail = trim(strip_tags((string) ($value->description ?? '')));
                        if ($detail === '') {
                            $detail = $examMode === 2
                                ? 'Pen & Paper OMR Physical Center Mock'
                                : ($isLive ? 'Live mock window currently open' : 'Scheduled RankPro mock examination');
                        }
                        if (mb_strlen($detail) > 90) {
                            $detail = mb_substr($detail, 0, 87).'...';
                        }
                    @endphp
                    <article class="rp-cal-card">
                        <div class="rp-cal-card__top">
                            <span class="rp-cal-card__badge {{ $badgeClass }}">{{ $modeText }}</span>
                            <h4 class="rp-cal-card__title">{{ $value->name }}</h4>
                        </div>
                        <div class="rp-cal-card__date">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ $dateLine }}</span>
                        </div>
                        <p class="rp-cal-card__detail">{{ $detail }}</p>
                    </article>
                @empty
                    <div class="rp-empty">No scheduled exams found.</div>
                @endforelse
            </div>

            <div class="rp-cal-modal__foot">
                <button type="button" class="rp-cal-modal__close-btn" data-rp-schedule-close>Close Calendar</button>
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
    <script src="{{ asset('web/js/jquery.bxslider.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('web/js/main.js') }}"></script>

    <script>
      $(document).ready(function () {
        if ($(".dashboardSlider").length) {
        $(".dashboardSlider").bxSlider({
          slideWidth: 342,
          minSlides: 1,
          maxSlides: 4,
          slideMargin: 15,
          controls: false,
          pager: false,
          infiniteLoop: true,
          auto: true,
          autoHover: true,
          touchEnabled: false,
          moveSlides: 1
        });
        }
        
        $(".menuBarBtn.menuBarBtnOpen").click(function () {
            $(".dashboardLeft").removeClass("dashboardLeftOff");
        });
        $(".menuBarBtn.menuBarBtnClose").click(function () {
            $(".dashboardLeft").addClass("dashboardLeftOff");
        });

        $("#rpMyScoreToggle").on("change", function () {
          var on = $(this).is(":checked");
          $("#rpMyScorePanel").toggleClass("is-on", on);
          $("#rpLbMain").toggleClass("is-hidden", on);
        });

        $("#rpExamTypeBtn").on("click", function (e) {
          e.stopPropagation();
          $("#rpExamTypeMenu").toggle();
        });
        $(document).on("click", function () {
          $("#rpExamTypeMenu").hide();
        });

        function createListPager(listEl, pagerEl, options) {
          if (!listEl || !pagerEl) return null;
          var pageSize = parseInt(listEl.getAttribute("data-page-size") || "5", 10);
          var state = { page: 1, mode: "all", pageSize: pageSize };
          var itemSelector = (options && options.itemSelector) || ".rp-page-item";

          function eligibleItems() {
            return Array.prototype.slice.call(listEl.querySelectorAll(itemSelector)).filter(function (el) {
              if (state.mode === "all") return true;
              return String(el.getAttribute("data-exam-mode") || "") === String(state.mode);
            });
          }

          function render() {
            var items = Array.prototype.slice.call(listEl.querySelectorAll(itemSelector));
            var eligible = eligibleItems();
            var totalPages = Math.max(1, Math.ceil(eligible.length / state.pageSize));
            if (state.page > totalPages) state.page = totalPages;
            if (state.page < 1) state.page = 1;

            items.forEach(function (el) { el.style.display = "none"; });
            var start = (state.page - 1) * state.pageSize;
            var end = start + state.pageSize;
            eligible.slice(start, end).forEach(function (el) { el.style.display = ""; });

            if (options && options.emptyEl) {
              options.emptyEl.style.display = (eligible.length === 0 && items.length > 0) ? "" : "none";
            }

            pagerEl.innerHTML = "";
            if (eligible.length <= state.pageSize) {
              pagerEl.style.display = "none";
              return;
            }
            pagerEl.style.display = "flex";

            var info = document.createElement("span");
            info.className = "rp-pager__info";
            info.textContent = "Page " + state.page + " of " + totalPages;
            pagerEl.appendChild(info);

            var controls = document.createElement("div");
            controls.className = "rp-pager__controls";

            function addBtn(label, disabled, onClick, isActive) {
              var btn = document.createElement("button");
              btn.type = "button";
              btn.className = "rp-pager__btn" + (isActive ? " is-active" : "");
              btn.textContent = label;
              btn.disabled = !!disabled;
              btn.addEventListener("click", onClick);
              controls.appendChild(btn);
            }

            addBtn("Prev", state.page <= 1, function () {
              state.page -= 1;
              render();
            });

            var maxButtons = 5;
            var startPage = Math.max(1, state.page - 2);
            var endPage = Math.min(totalPages, startPage + maxButtons - 1);
            startPage = Math.max(1, endPage - maxButtons + 1);
            for (var p = startPage; p <= endPage; p++) {
              (function (pageNum) {
                addBtn(String(pageNum), false, function () {
                  state.page = pageNum;
                  render();
                }, pageNum === state.page);
              })(p);
            }

            addBtn("Next", state.page >= totalPages, function () {
              state.page += 1;
              render();
            });

            pagerEl.appendChild(controls);
          }

          return {
            setMode: function (mode) {
              state.mode = mode || "all";
              state.page = 1;
              render();
            },
            render: render
          };
        }

        var givenPager = createListPager(
          document.getElementById("rpGivenList"),
          document.getElementById("rpGivenPager"),
          { emptyEl: document.querySelector(".rp-given-empty") }
        );
        var givePager = createListPager(
          document.getElementById("rpGiveList"),
          document.getElementById("rpGivePager")
        );
        if (givenPager) givenPager.render();
        if (givePager) givePager.render();

        $("#rpGivenFilter a").on("click", function () {
          var mode = $(this).data("mode");
          $("#rpGivenFilter a").removeClass("is-active-purple is-active-blue is-active-emerald");
          if (mode === "all") $(this).addClass("is-active-purple");
          else if (String(mode) === "1") $(this).addClass("is-active-blue");
          else $(this).addClass("is-active-emerald");
          if (givenPager) givenPager.setMode(mode);
        });

        var evalModal = document.getElementById("rpEvalModal");
        function closeEvalModal() {
          if (!evalModal) return;
          evalModal.classList.remove("is-open");
          evalModal.setAttribute("aria-hidden", "true");
        }
        function openEvalModal(btn) {
          if (!evalModal || !btn) return;
          var title = btn.getAttribute("data-title") || "Exam";
          var mode = btn.getAttribute("data-mode") || "Examination";
          var date = btn.getAttribute("data-date") || "—";
          var score = btn.getAttribute("data-score") || "0";
          var max = btn.getAttribute("data-max") || "—";
          var rank = btn.getAttribute("data-rank") || "";
          var participants = parseInt(btn.getAttribute("data-participants") || "0", 10);
          var resultUrl = btn.getAttribute("data-result-url") || "#";
          var subjects = [];
          try { subjects = JSON.parse(btn.getAttribute("data-subjects") || "[]"); } catch (e) { subjects = []; }

          document.getElementById("rpEvalTitle").textContent = title;
          document.getElementById("rpEvalMode").textContent = "Mode: " + mode;
          document.getElementById("rpEvalDate").textContent = "Date: " + date;
          document.getElementById("rpEvalScore").textContent = score + " / " + max;
          document.getElementById("rpEvalRank").textContent = rank
            ? ("AIR " + rank + (participants > 0 ? (" of " + participants.toLocaleString()) : ""))
            : "Not ranked yet";
          document.getElementById("rpEvalReviewBtn").setAttribute("href", resultUrl);

          var list = document.getElementById("rpEvalSubjects");
          list.innerHTML = "";
          if (!subjects.length) {
            list.innerHTML = "<li><span>No subject breakdown available</span></li>";
        } else {
            subjects.forEach(function (s) {
              var li = document.createElement("li");
              var name = document.createElement("span");
              name.textContent = (s.name || "Subject") + ":";
              var val = document.createElement("strong");
              var obtained = (typeof s.obtained !== "undefined") ? s.obtained : 0;
              var smax = (typeof s.max !== "undefined" && s.max) ? s.max : "—";
              val.textContent = obtained + " / " + smax;
              li.appendChild(name);
              li.appendChild(val);
              list.appendChild(li);
            });
          }

          evalModal.classList.add("is-open");
          evalModal.setAttribute("aria-hidden", "false");
        }

        $(document).on("click", ".rp-open-eval", function () {
          openEvalModal(this);
        });
        $(document).on("click", "[data-rp-eval-close]", closeEvalModal);

        var scheduleModal = document.getElementById("rpScheduleModal");
        function closeScheduleModal() {
          if (!scheduleModal) return;
          scheduleModal.classList.remove("is-open");
          scheduleModal.setAttribute("aria-hidden", "true");
        }
        function openScheduleModal() {
          if (!scheduleModal) return;
          scheduleModal.classList.add("is-open");
          scheduleModal.setAttribute("aria-hidden", "false");
        }
        $("#rpOpenSchedule").on("click", openScheduleModal);
        $(document).on("click", "[data-rp-schedule-close]", closeScheduleModal);

        $(document).on("keydown", function (e) {
          if (e.key === "Escape") {
            closeEvalModal();
            closeScheduleModal();
          }
        });
      });
        
      var max = {{ (int) $total_question }};
        @foreach($subject_list as $value)
      (function () {
        var el = document.getElementById("comparison_{{ $value->id }}");
        if (!el || typeof Chart === "undefined") return;
        new Chart(el, {
          type: "doughnut",
                data: {
                    datasets: [{
              data: [{{ (int) $value->count }}, Math.max(0, max - {{ (int) $value->count }})],
              backgroundColor: ["#2563eb", "#e2e8f0"],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
            cutout: "78%",
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false },
              centerText: { score: {{ (int) $value->score }} }
            }
          },
                plugins: [{
            id: "centerText",
            afterDraw: function (chart) {
              var score = chart.options.plugins.centerText.score;
              var meta = chart.getDatasetMeta(0);
                        if (!meta || !meta.data || !meta.data.length) return;
              var ctx = chart.ctx;
              var cx = meta.data[0].x;
              var cy = meta.data[0].y;
                        ctx.save();
              ctx.textAlign = "center";
              ctx.font = "10px Poppins, sans-serif";
              ctx.fillStyle = "#64748b";
              ctx.fillText("Score", cx, cy - 4);
              ctx.font = "bold 13px Poppins, sans-serif";
              ctx.fillStyle = "#0f172a";
              ctx.fillText(score, cx, cy + 14);
                        ctx.restore();
                    }
                }]
            });
      })();
        @endforeach
      
      function openVideoModel(video_link) {
          document.getElementById("modal_video").src = video_link;
            $("#examVideo").modal("show");
      }

    </script>
    
    @include('site.include.start_exam_confirm_modal')
</body>

</html>
