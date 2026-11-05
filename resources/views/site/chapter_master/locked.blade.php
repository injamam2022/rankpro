<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'Rank Chapter Master | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    <style>
        .calltoaction{display:none}
        .cm-wrap{max-width:880px;margin:0 auto;padding-bottom:2rem}
        .ps-tabs{display:flex;flex-wrap:wrap;gap:.5rem;margin:0 0 1rem}
        .ps-tab{display:inline-flex;align-items:center;gap:.35rem;padding:.45rem .9rem;border-radius:999px;border:1px solid #e5e7eb;background:#fff;color:#334155;font-size:.82rem;font-weight:600;text-decoration:none}
        .ps-tab:hover{border-color:#c7d2fe;color:#4338ca}
        .ps-tab.is-on{background:#5b4bb7;border-color:#5b4bb7;color:#fff}
        .ps-tab.is-locked{color:#667085}
        .ps-tab.is-on.is-locked{background:#ede9fe;border-color:#c4b5fd;color:#5b21b6}
        .ps-tab .fa-lock{font-size:.65rem}
        .ps-pay{border:1px solid #eaecf0;border-radius:1.1rem;background:#fff;padding:1.6rem 1.4rem;text-align:center;max-width:520px;margin:0 auto}
        .ps-pay__lock{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#5b4bb7,#7c3aed);color:#fff;display:grid;place-items:center;margin:0 auto 1rem}
        .ps-pay__price{font-size:1.7rem;font-weight:800;color:#5b4bb7;margin-bottom:.85rem}
        .ps-pay__feats{list-style:none;padding:0;margin:0 0 1.1rem;text-align:left}
        .ps-pay__feats li{font-size:.88rem;color:#334155;margin-bottom:.4rem;display:flex;gap:.5rem}
        .ps-pay__feats i{color:#16a34a;margin-top:.2rem}
    </style>
    @include('site.include.head_meta')
</head>
<body class="rp-dash-body">
@include('site.include.body_meta')
@include('site.include.student_dashboard_header')
<section id="dashboard">
  <div class="container-fluid">
    <div class="dashboardAll dashboardPh">
      <div class="menuBarBtn menuBarBtnOpen"><i class="fas fa-bars"></i></div>
      <div class="dashboardLeft dashboardLeftOff">@include('site.include.student_left_menu')</div>
      <div class="dashboardRight"><div class="dashboardRightBody">
        <div class="cm-wrap">
          <div class="dashboardTitle mb-1">Series <span>Tests</span></div>
          @include('site.include.series_package_tabs')
          <h2 class="h5 mb-3">Rank Chapter Master</h2>
          @include('site.include.series_pay_panel', ['payPackage' => $package ?? null, 'payMode' => $payMode ?? null])
        </div>
      </div></div>
    </div>
  </div>
</section>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('web/bootstrap-5.0.2/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('web/js/main.js') }}"></script>
</body>
</html>
