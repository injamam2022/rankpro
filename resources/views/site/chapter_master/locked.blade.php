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
    <style>.calltoaction{display:none}.cm-lock{max-width:560px;margin:2rem auto;text-align:center;padding:2rem;border:1px solid #e8edf5;border-radius:16px;background:#fff}</style>
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
        <div class="cm-lock">
          <div class="mb-3" style="font-size:2.2rem;color:#5b4bb7;"><i class="fas fa-lock"></i></div>
          <h3 class="mb-2">Rank Chapter Master</h3>
          <p class="text-muted mb-3">Unlock chapter-wise premium tests across PCB. Pick a subject and chapter — topics without premium questions show as Coming soon.</p>
          @if($package)
            <div class="fw-bold mb-3">{{ $package->formattedPrice() }}</div>
            <a href="{{ route('course_packages.show', $package->slug) }}" class="btn btn-primary">View details &amp; purchase</a>
          @else
            <a href="{{ route('course_packages') }}" class="btn btn-primary">Browse course packages</a>
          @endif
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
