<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
    <link href="{{ asset('') }}web/css/style_a.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/form.css" rel="stylesheet">
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

    <section id="testseriesDetails" class="topbannerA">
        <div class="cusContainer">
            <div class="row">
                <div class="col-lg-7">
                    <div class="testseriesDetailsLeft">
                        <div class="offlineTestTitle">
                            {!! $test->name !!}
                        </div>

                        <div class="quickBlock">
                            
                            <div><span class="subjectsTitle">Exam Code : </span> {{$test->exam_code}} </div>
                            <div><span class="subjectsTitle">Exam Date : </span> {{$test->exam_date}} </div>
                            <div><span class="subjectsTitle">Exam Time : </span> {{$test->exam_time}} </div>
                            <div><span class="subjectsTitle">Location : </span> {{$test->location_name}} </div>
                            <div><span class="subjectsTitle">Total No. Of Question : </span> {{$test->no_of_question}} </div>
                            <div><span class="subjectsTitle">Totals Marks for Exam : </span> {{$test->totals_marks_for_exam}} </div>
                            <div><span class="subjectsTitle">Total Time for Exam (In minutes) : </span> {{$test->total_time_for_exam}} </div>

                            <div class="subjectsTitle mb-3">Description</div>
                                {!! $test->description !!}

                            <div class="subjectsTitle mb-3">Instructions</div>
                                {!! $test->exam_instructions !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-4">
                    <div class="testseriesDetailsRight testseriesBoxShadow">
                        <div class="feesHeading">
                            <div class="feesTitle">Annual Fee</div>
                            @if (!empty($test->price) && !empty($test->dis_price))
                                <div class="feesPrice">
                                    <div class="feesPriceValue">
                                        <span>₹{{ $test->price }}</span> ₹{{ $test->price - $test->dis_price }}
                                    </div>
                                    @if (!empty($test->tax))
                                        <div class="feesPriceTax">+ ₹{{ $test->tax }} Taxes</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="feesBelowBlock">
                            <form action="{{ route('testseries.checkout') }}" method="get">
                                <input type="hidden" name="test_id" value="{{ $test->id }}">
                                @if (!empty($exam_language_list) && count($exam_language_list))
                                    <div class="subjectsTitle">Select course language</div>
                                    @foreach($exam_language_list as $index => $lang)
                                        <div class="form-check-inline">
                                            <label class="form-check-label" for="language{{ $index }}">
                                                <input type="radio" class="form-check-input" id="language{{ $index }}" name="language"
                                                    value="{{ $lang->id }}" {{ $index == 0 ? 'checked' : '' }}>
                                                {{ $lang->language_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                @if (!empty($exam_location_list) && count($exam_location_list))
                                    <div class="subjectsTitle mt-4">Select location</div>
                                    @foreach($exam_location_list as $index => $loc)
                                        <div class="form-check-non-inline">
                                            <label class="form-check-label" for="location{{ $index }}">
                                                <input type="radio" class="form-check-input" id="location{{ $index }}" name="location"
                                                    value="{{ $loc->id }}" {{ $index == 0 ? 'checked' : '' }}>
                                                {{ $loc->location_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                @if (!empty($exam_date_list) && count($exam_date_list))
                                    <div class="subjectsTitle mt-4">Select starting date</div>
                                    @foreach($exam_date_list as $index => $d)
                                        <div class="form-check-non-inline">
                                            <label class="form-check-label" for="startingDate{{ $index }}">
                                                <input type="radio" class="form-check-input" id="startingDate{{ $index }}" name="startingDate"
                                                    value="{{ $d->id }}" {{ $index == 0 ? 'checked' : '' }}>
                                                {{ $d->phase }} starting {{ \Carbon\Carbon::parse($d->date)->format('d M Y') }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="feesInfo">* Missed tests will be available for re-attempt</div>
                                @if (Auth::check())
                                    <button type="submit" class="btn btn-buyNow">Buy Now</button>
                                @else
                                    <button type="button" class="btn btn-buyNow" data-bs-toggle="modal" data-bs-target="#successPopup">Buy Now</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal otpPopupModal fade" id="successPopup">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form>
              <div class="check_success">
                <img src="{{ asset('') }}web/images/form/check_success.png" class="img-fluid" alt="">
              </div>
              <div class="otpPopupTitle">Alert!</div>
              <div class="otpPopupSubTitle">Please login before use.</div>

              <div class="text-center">
                <a href="{{ route('login') }}" class="btn btnRegister mt-0">Continue</a>
              </div>
            </form>
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
