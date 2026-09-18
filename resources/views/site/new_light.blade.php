<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta name="title" content="NEET AI Platform – Unlimited Free Mock Tests, Test Series & Find Your Mentor">
    <meta name="description" content="Prepare for NEET with AI-powered tools. Access unlimited free mock tests and full test series based on the latest NEET exam pattern. Get expert guidance, detailed analysis, and connect with top NEET mentors to boost your score.">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('') }}web/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <style>
        .testserblocks {
            width: 562px;
            margin-top: 30px;
        }
    </style>
    @include('site.include.head_meta')
</head>

<!-- <body style="background: url(images/fullindex.jpg) no-repeat center top;"> -->

<body>
    @include('site.include.body_meta')
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start px-lg-5 -->
    @include('site.include.header')
    <!-- Navbar End -->

    <!-- banner Start owl-carousel header-carousel -->
    <div class="position-relative">
        <div class="topbanner">
            <div class="cusContainer d-flex" style="max-width: 1420px;">
                <div class="leftText wow fadeInUp" data-wow-delay="0.1s">
                    
                    {!!$cms_text->banner_header!!}
                    {!!$cms_text->banner_description!!}
                    <!-- <h2>New Light <span class="sizeOne"> Test Series</span> – The Ultimate NEET Prep Test
                        Series
                    </h2>
                    <h3>
                        <span> Module-specific, NCERT-based tests for NEET aspirants &
                            repeaters.</span>
                    </h3> -->
                    @if (!Auth::check())
                        <a href="{{ route('signup')}}">Enroll now</a>
                    @endif
                </div>
                <div class="rightImg wow fadeIn" data-wow-delay="0.1s">
                    @if($cms_text->banner_logo)
                        <img src="{{ asset('uploads/banner/' . $cms_text->banner_logo) }}" class="img-fluid" alt="NEET successor">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- banner End -->


    <div class="realstory wow fadeInUp" data-wow-delay="0.1s">
        <div class="realstoryblock">
            <div class="cusContainer">
                {!!$cms_text->header!!}
                {!!$cms_text->description!!}
            </div>
        </div>
    </div>
    <div class="testpagemarginbottom wow fadeInUp" data-wow-delay="0.1s">
        <div class="cusContainer">
            <div class="row">
                @php
                    $colors = ['pink', 'blue', 'orange', 'green'];
                @endphp

                @foreach ($test_series as $index => $test)
                    @php
                        $colorClass = $colors[$index % count($colors)];
                    @endphp

                    <div class="col-sm-6 d-flex justify-content-center">
                        <div class="testserblocks {{ $colorClass }}">
                            <div class="neetprep">
                                <img src="{{ asset('uploads/test_series/' . ($test->image ?? 'default.png')) }}" class="img-fluid">

                                <p>{!! $test->headings->first()->heading ?? '' !!}</p>

                                <a href="{{ route('new_light.details', encrypt($test->id)) }}">View Course Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    @include('site.include.footer')
    @include('site.include.call_to_action')
    @include('site.include.back_to_top')


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
    <script src="{{ asset('') }}web/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('') }}web/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('') }}web/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>
</body>

</html>
