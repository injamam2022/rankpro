<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'Series Tests | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    <style>.calltoaction{display:none}.ps-lock{max-width:640px;margin:0 auto;text-align:center;padding:2rem 1rem}.ps-lock__icon{width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#5b4bb7,#7c3aed);color:#fff;display:grid;place-items:center;margin:0 auto 1rem;font-size:1.4rem}</style>
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
        <div class="ps-lock">
          <div class="ps-lock__icon"><i class="fas fa-lock"></i></div>
          <h1 class="h4 mb-2">{{ isset($focusPackage) ? $focusPackage->name : 'Series Tests' }}</h1>
          <p class="text-muted mb-4">Purchase a course package like Rank Builder to unlock the scheduled online tests (and OMR where included). Premium question bank only.</p>
          @foreach(($featured ?? collect()) as $p)
            <a href="{{ route('course_packages.show', $p->slug) }}" class="btn btn-primary mb-2 d-inline-block">
              {{ $p->name }} — {{ $p->formattedPrice() }}
            </a>
            <div class="small text-muted mb-3">{{ $p->tagline }}</div>
          @endforeach
          <div class="mt-2"><a href="{{ route('course_packages') }}">Browse all packages</a></div>
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
