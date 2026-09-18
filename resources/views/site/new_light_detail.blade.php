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
                        @if ($test->headings->isNotEmpty() && !empty($test->headings->first()->heading))
                            <div class="offlineTestTitle">
                                {!! $test->headings->first()->heading !!}
                            </div>
                        @endif

                        @if (!empty($subjects) && $subjects->count())
                            <div class="subjectsTitle">Subjects</div>
                            <div class="subjectsText">
                                @foreach ($subjects as $subject)
                                    {{ $subject->name }}@if (!$loop->last),@endif
                                @endforeach
                            </div>
                        @endif

                        <div class="quickBlock">
                            @if ($test->overviews->isNotEmpty() && !empty($test->overviews->first()->overview))
                                <div class="subjectsTitle mb-3">Quick Overview</div>
                                {!! $test->overviews->first()->overview !!}
                            @endif

                            @if ($test->examdescs->isNotEmpty() && !empty($test->examdescs->first()->examdesc))
                                <div class="subjectsTitle mt-4 mb-3">Exam Description</div>
                                {!! $test->examdescs->first()->examdesc !!}
                            @endif

                            @if ($test->markschemes->isNotEmpty() && !empty($test->markschemes->first()->markscheme))
                                <div class="subjectsTitle mt-4 mb-3">Marking Scheme:</div>
                                {!! $test->markschemes->first()->markscheme !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-4">
                    <div class="testseriesDetailsRight testseriesBoxShadow">
                        <div class="feesHeading">
                            <div class="feesTitle">SHOW INTERREST</div>
                            
                        </div>
                        <div class="feesBelowBlock">
                            <form action="{{ route('new_light.checkout') }}" method="post">
                                @csrf
                                <input type="hidden" name="test_id" value="{{ $test->id }}">
                                @if (!empty($test->language) && count($test->language))
                                    <div class="subjectsTitle">Select course language</div>
                                    @foreach($test->language as $index => $lang)
                                        <div class="form-check-inline">
                                            <label class="form-check-label" for="language{{ $index }}">
                                                <input type="radio" class="form-check-input" id="language{{ $index }}" name="language"
                                                    value="{{ $lang->id }}" {{ $index == 0 ? 'checked' : '' }}>
                                                {{ $lang->language_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                @if (!empty($test->location) && count($test->location))
                                    <div class="subjectsTitle mt-4">Select location</div>
                                    @foreach($test->location as $index => $loc)
                                        <div class="form-check-non-inline">
                                            <label class="form-check-label" for="location{{ $index }}">
                                                <input type="radio" class="form-check-input" id="location{{ $index }}" name="location"
                                                    value="{{ $loc->id }}" {{ $index == 0 ? 'checked' : '' }}>
                                                {{ $loc->location_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                @if (!empty($test->date) && count($test->date))
                                    <div class="subjectsTitle mt-4">Select starting date</div>
                                    @foreach($test->date as $index => $d)
                                        <div class="form-check-non-inline">
                                            <label class="form-check-label" for="startingDate{{ $index }}">
                                                <input type="radio" class="form-check-input" id="startingDate{{ $index }}" name="startingDate"
                                                    value="{{ $d->id }}" {{ $index == 0 ? 'checked' : '' }}>
                                                {{ $d->phase }} starting {{ \Carbon\Carbon::parse($d->date)->format('d M Y') }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="subjectsTitle mt-4">
                                    <label class="form-label" for="name">Name</label>
                                    <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                                </div>
                                <div class="subjectsTitle mt-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input class="form-control" id="email" type="text" name="email" placeholder=""  value="" />
                                </div>
                                <div class="subjectsTitle mt-4">
                                    <label class="form-label" for="mobile_number">Mobile Number</label>
                                    <input class="form-control" id="mobile_number" type="text" name="mobile_number" placeholder=""  value="" />
                                </div>

                                <button type="submit" class="btn btn-buyNow">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal otpPopupModal fade" id="successPopup" data-bs-backdrop="static" data-bs-keyboard="false">
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
