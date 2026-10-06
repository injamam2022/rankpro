<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $custom->name }} is ready - RankPro</title>
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
        .ct-wrap { max-width: 520px; margin: 0 auto; padding: 1rem 0 7.5rem; text-align: center; }
        .ct-back {
            display: inline-flex; align-items: center; gap: .45rem; color: #344054; text-decoration: none;
            font-weight: 500; margin-bottom: 1.25rem; align-self: flex-start;
        }
        .ct-back:hover { color: #5b4bb7; }
        .ct-hero {
            width: 120px; height: 120px; margin: 0 auto 1.25rem; border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #ffe8a3, #f6c453 45%, #e8a317);
            display: grid; place-items: center; box-shadow: 0 12px 30px rgba(232, 163, 23, .28);
            position: relative;
        }
        .ct-hero::after {
            content: ""; position: absolute; inset: -8px; border-radius: 50%;
            border: 2px dashed rgba(246, 196, 83, .55);
        }
        .ct-hero i { font-size: 3rem; color: #fff; text-shadow: 0 2px 0 rgba(0,0,0,.08); }
        .ct-ready-title { font-size: 1.45rem; font-weight: 700; color: #101828; margin-bottom: 1.35rem; }
        .ct-summary {
            text-align: left; background: #fff; border: 1px solid #eaecf0; border-radius: 1rem;
            padding: .35rem 0; margin-bottom: 1rem; box-shadow: 0 8px 24px rgba(16,24,40,.04);
        }
        .ct-summary-row {
            display: flex; align-items: center; gap: .85rem; padding: .95rem 1.1rem;
            border-bottom: 1px solid #f2f4f7;
        }
        .ct-summary-row:last-child { border-bottom: 0; }
        .ct-summary-icon {
            width: 40px; height: 40px; border-radius: .7rem; display: grid; place-items: center;
            color: #fff; flex-shrink: 0; font-size: .95rem;
        }
        .ct-summary-icon.q { background: #2f6fed; }
        .ct-summary-icon.s { background: #3b82f6; }
        .ct-summary-icon.t { background: #7c3aed; }
        .ct-summary-text { font-size: .95rem; font-weight: 600; color: #101828; line-height: 1.35; }
        .ct-download {
            display: flex; align-items: center; justify-content: center; gap: .55rem;
            width: 100%; border: 1px solid #d0d5dd; background: #fff; color: #344054;
            border-radius: .85rem; padding: .9rem 1rem; font-weight: 500; font-size: .9rem;
            text-decoration: none; margin-bottom: 1rem;
        }
        .ct-download:hover { background: #f9fafb; color: #101828; }
        .ct-footer {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 40;
            background: rgba(255,255,255,.94); backdrop-filter: blur(8px);
            border-top: 1px solid #eaecf0; padding: .85rem 1rem;
        }
        .ct-footer-inner { max-width: 520px; margin: 0 auto; }
        .ct-start {
            width: 100%; border: 0; border-radius: .85rem; padding: .95rem 1rem; font-weight: 600;
            background: #1f1b4d; color: #fff; font-size: .95rem; cursor: pointer;
        }
        .ct-start:hover { background: #16133a; }
        @media (min-width: 992px) {
            .ct-footer { left: 280px; }
        }
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
                        <div style="text-align:left;">
                            <a class="ct-back" href="{{ route('custom_test') }}"><i class="fas fa-arrow-left"></i></a>
                        </div>

                        <div class="ct-hero" aria-hidden="true">
                            <i class="fas fa-thumbs-up"></i>
                        </div>

                        <div class="ct-ready-title">Your {{ $custom->name }} is ready</div>

                        <div class="ct-summary">
                            <div class="ct-summary-row">
                                <span class="ct-summary-icon q"><i class="fas fa-question"></i></span>
                                <span class="ct-summary-text">{{ $custom->question_count }} Questions</span>
                            </div>
                            <div class="ct-summary-row">
                                <span class="ct-summary-icon s"><i class="fas fa-book-open"></i></span>
                                <span class="ct-summary-text">{{ $subjectLabel }}</span>
                            </div>
                            <div class="ct-summary-row">
                                <span class="ct-summary-icon t"><i class="far fa-clock"></i></span>
                                <span class="ct-summary-text">{{ $custom->duration_minutes }} minutes</span>
                            </div>
                        </div>

                        <a class="ct-download" href="{{ route('custom_test.download', $custom->id) }}" target="_blank">
                            <i class="far fa-file-alt"></i> Download test
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="ct-footer">
    <div class="ct-footer-inner">
        <form method="post" action="{{ route('custom_test.start', $custom->id) }}">
            @csrf
            <button type="submit" class="ct-start">Start Now</button>
        </form>
    </div>
</div>

@include('site.include.call_to_action')
@include('site.include.back_to_top')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
</body>
</html>
