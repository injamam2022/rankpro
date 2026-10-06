<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Analysis - {{ $custom->name }} - RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/dashboard.css" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    @include('site.include.head_meta')
    <style>
        .ct-wrap { max-width: 560px; margin: 0 auto; padding: .4rem 0 2.5rem; }
        .ct-back {
            width: 40px; height: 40px; border-radius: 50%; border: 1px solid #e4e7ec;
            display: inline-grid; place-items: center; color: #344054; text-decoration: none;
            background: #fff; margin-bottom: .85rem;
        }
        .ct-back:hover { color: #5b4bb7; border-color: #d6bbfb; }
        .ct-title { font-size: 1.55rem; font-weight: 700; color: #101828; margin: 0 0 .15rem; }
        .ct-name { font-size: .95rem; color: #667085; margin-bottom: 1.15rem; }
        .ct-review-btn {
            display: flex; align-items: center; justify-content: center; width: 100%;
            border: 0; border-radius: .9rem; padding: .95rem 1rem; font-weight: 600;
            background: #5b4bb7; color: #fff; font-size: .95rem; text-decoration: none;
            margin-bottom: 1.1rem;
        }
        .ct-review-btn:hover { background: #4a3ca0; color: #fff; }
        .ct-stats {
            display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; margin-bottom: .85rem;
        }
        .ct-stat {
            border: 1px solid #eaecf0; border-radius: 1rem; background: #fff;
            padding: 1rem 1.05rem; text-align: center;
        }
        .ct-stat-label {
            font-size: .68rem; letter-spacing: .04em; text-transform: uppercase;
            color: #98a2b3; font-weight: 600; margin-bottom: .35rem;
        }
        .ct-stat-value { font-size: 1.55rem; font-weight: 700; line-height: 1.15; color: #5b4bb7; }
        .ct-stat-value.ok { color: #12b76a; }
        .ct-stat-value.bad { color: #f04438; }
        .ct-meta {
            text-align: center; color: #667085; font-size: .85rem; margin-bottom: 1.15rem;
        }
        .ct-card {
            border: 1px solid #eaecf0; border-radius: 1rem; background: #fff;
            padding: 1.05rem 1.15rem; margin-bottom: 1.15rem;
        }
        .ct-card h3 { font-size: .98rem; font-weight: 700; color: #101828; margin: 0 0 .25rem; }
        .ct-card p { font-size: .85rem; color: #667085; margin: 0 0 .55rem; }
        .ct-link {
            color: #5b4bb7; font-weight: 600; font-size: .88rem; text-decoration: none;
        }
        .ct-link:hover { color: #4a3ca0; text-decoration: underline; }
        .ct-section-title {
            font-size: 1.05rem; font-weight: 700; color: #101828; margin: 0 0 .85rem;
        }
        .ct-subject {
            border: 1px solid #eaecf0; border-radius: 1rem; background: #fff;
            padding: 1rem 1.1rem; margin-bottom: .75rem;
        }
        .ct-subject-head {
            display: flex; align-items: baseline; justify-content: space-between; gap: .75rem;
            margin-bottom: .55rem;
        }
        .ct-subject-name { font-weight: 700; color: #101828; font-size: .95rem; }
        .ct-subject-score { font-weight: 700; font-size: .95rem; }
        .ct-subject-score.low { color: #f04438; }
        .ct-subject-score.mid { color: #f79009; }
        .ct-subject-score.high { color: #12b76a; }
        .ct-bar {
            height: 6px; border-radius: 999px; background: #eaecf0; overflow: hidden; margin-bottom: .55rem;
        }
        .ct-bar > span {
            display: block; height: 100%; border-radius: 999px; background: #5b4bb7;
        }
        .ct-subject-row {
            display: flex; justify-content: space-between; gap: .75rem;
            font-size: .8rem; color: #667085; margin-bottom: .7rem;
        }
        .ct-subject-foot {
            display: flex; align-items: center; justify-content: space-between; gap: .75rem;
            border-top: 1px solid #f2f4f7; padding-top: .7rem;
        }
        .ct-status { font-size: .8rem; font-weight: 600; }
        .ct-status.bad { color: #f04438; }
        .ct-status.ok { color: #12b76a; }
        .ct-actions {
            display: flex; flex-wrap: wrap; gap: .55rem; margin-top: 1rem;
        }
        .ct-soft-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            border: 1px solid #d0d5dd; background: #fff; color: #344054;
            border-radius: .7rem; padding: .55rem .9rem; font-size: .82rem; font-weight: 500;
            text-decoration: none;
        }
        .ct-soft-btn:hover { background: #f9fafb; color: #101828; }
    </style>
</head>
<body class="rp-dash-body">
@include('site.include.body_meta')
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
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
                    <div class="ct-wrap">
                        <a class="ct-back" href="{{ route('custom_test', ['tab' => 'attempted']) }}" aria-label="Back">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <h1 class="ct-title">Analysis</h1>
                        <div class="ct-name">{{ $custom->name }}</div>

                        <a class="ct-review-btn" href="{{ $reviewUrl }}">Review questions</a>

                        <div class="ct-stats">
                            <div class="ct-stat">
                                <div class="ct-stat-label">Overall score</div>
                                <div class="ct-stat-value">{{ $score }}/{{ $totalMarks }}</div>
                            </div>
                            <div class="ct-stat">
                                <div class="ct-stat-label">Accuracy</div>
                                <div class="ct-stat-value">{{ $accuracy }}%</div>
                            </div>
                            <div class="ct-stat">
                                <div class="ct-stat-label">Correct</div>
                                <div class="ct-stat-value ok">{{ $correct }} Qs</div>
                            </div>
                            <div class="ct-stat">
                                <div class="ct-stat-label">Incorrect</div>
                                <div class="ct-stat-value bad">{{ $incorrect }} Qs</div>
                            </div>
                        </div>

                        <div class="ct-meta">{{ $unattempted }} unattempted · Time: {{ $timeMinutes }}m</div>

                        <div class="ct-card">
                            <h3>NEET rank &amp; college predictions</h3>
                            <p>See your predicted rank based on this attempt.</p>
                            <a class="ct-link" href="{{ route('air') }}">View predictions →</a>
                        </div>

                        <div class="ct-section-title">Subject-wise Performance</div>

                        @forelse($subjectStats as $subject)
                            @php
                                $scoreClass = $subject['accuracy'] >= 60 ? 'high' : ($subject['accuracy'] >= 30 ? 'mid' : 'low');
                            @endphp
                            <div class="ct-subject">
                                <div class="ct-subject-head">
                                    <span class="ct-subject-name">{{ $subject['name'] }}</span>
                                    <span class="ct-subject-score {{ $scoreClass }}">{{ $subject['score'] }}/{{ $subject['total_marks'] }}</span>
                                </div>
                                <div class="ct-bar"><span style="width: {{ $subject['bar_pct'] }}%;"></span></div>
                                <div class="ct-subject-row">
                                    <span>{{ $subject['accuracy'] }}% Accuracy</span>
                                    <span>{{ $subject['correct'] }} Correct · {{ $subject['incorrect'] }} Incorrect</span>
                                </div>
                                <div class="ct-subject-foot">
                                    <span class="ct-status {{ $subject['needs_improvement'] ? 'bad' : 'ok' }}">
                                        {{ $subject['needs_improvement'] ? '✕' : '✓' }} {{ $subject['status_label'] }}
                                    </span>
                                    <a class="ct-link" href="{{ $reviewUrl }}">Review →</a>
                                </div>
                            </div>
                        @empty
                            <div class="ct-card">
                                <p style="margin:0;">Subject breakdown will appear after the test is scored.</p>
                            </div>
                        @endforelse

                        <div class="ct-actions">
                            <a class="ct-soft-btn" href="{{ route('custom_test.download', $custom->id) }}" target="_blank">
                                <i class="far fa-file-alt"></i> Download paper
                            </a>
                            <a class="ct-soft-btn" href="{{ route('custom_test', ['tab' => 'create']) }}">
                                <i class="fas fa-plus"></i> Create another
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('site.include.call_to_action')
@include('site.include.back_to_top')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
</body>
</html>
