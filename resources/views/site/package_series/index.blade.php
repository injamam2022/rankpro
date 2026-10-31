<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'My Series Tests | RankPro']])
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
        .ps-wrap{max-width:880px;margin:0 auto;padding-bottom:2rem}
        .ps-card{border:1px solid #eaecf0;border-radius:1rem;background:#fff;padding:1.1rem 1.2rem;display:flex;gap:1rem;align-items:center;margin-bottom:.85rem;text-decoration:none;color:inherit}
        .ps-card:hover{border-color:#c7d2fe;box-shadow:0 8px 22px rgba(16,24,40,.06);color:inherit}
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
        <div class="ps-wrap">
          <div class="dashboardTitle mb-1">My <span>Series Tests</span></div>
          <p class="text-muted mb-3">Open a purchased package to view the schedule and start online exams.</p>
          @foreach($orders as $order)
            @php $p = $order->package; @endphp
            @if($p)
              <a class="ps-card" href="{{ route('package_series.show', $p->slug) }}">
                <div class="flex-grow-1">
                  <div class="fw-semibold">{{ $p->name }}</div>
                  <div class="small text-muted">{{ $order->modeBadge() }} · Order {{ $order->order_number }}</div>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
              </a>
            @endif
          @endforeach
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
