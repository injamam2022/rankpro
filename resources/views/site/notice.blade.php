<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['seo' => ['title' => 'Notices | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
    <link href="{{ asset('web/css/notices.css') }}" rel="stylesheet">

    <style>.calltoaction { display: none; }</style>
    @include('site.include.head_meta')
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
                <div class="menuBarBtn menuBarBtnOpen"><i class="fas fa-bars"></i></div>
                <div class="dashboardLeft dashboardLeftOff">
                    @include('site.include.student_left_menu')
                </div>
                <div class="dashboardRight">
                    <div class="dashboardRightBody">
                        @include('site.include.notices_body')
                    </div>
                </div>
            </div>
        </div>
    </section>

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
    <script src="{{ asset('web/js/main.js') }}"></script>
</body>

</html>
