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


    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/form.css" rel="stylesheet">
    @include('site.include.head_meta')
</head>

<!-- <body style="background: url(images/details-page.png) no-repeat center top;"> -->

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

    <div class="rankersDetailspage">
        <div class="leftRdetails" style="background-color:#ff3180;">
            <div class="leftRdetailscontainer">
                @if($ranker)
                    <div class="rankerdetailranker" style="background-color:#2e318b;">
                        <img src="{{ asset('uploads/ranker/' . ($ranker->profile_icon ?? 'default.png')) }}" class="img-fluid" alt="">
                    </div>
                    <h3>{{ $ranker->name ?? '' }}, {{ $ranker->about }}</h3>
                    <p>{{ $ranker->description ?? '' }}</p>
                    <div class="rankerdetailRankervid">
                        <img src="{{ asset('images/upload/rankersvid.png') }}" class="img-fluid" alt="">
                    </div>
                @else
                @endif
            </div>
        </div>
        <div class="rightRdetails">
            <div class="rightRdetailscontainer">
                <div class="detailgreenblkholder">
                    @if(count($ranker->meetings))
                        @foreach($ranker->meetings as $value)
                            <div class="detailgreenblk" style="background-color:#fcc7e2;">
                                <h4>{{$value->title}}</h4>
                                <p>{!! $value->description !!}</p>
                                @if (Auth::check())
                                    <a href="{{ route('ranker.checkout', encrypt($value->id)) }}" class="dgreenbar" style="text-decoration: none;cursor: pointer;background-color:#3561fe;">
                                        <div class="dgreenbarleft">
                                            <b>{{ $value->duration ?? '' }}</b>
                                            {{ $value->type }}
                                        </div>
                                        <div class="dgreenbarRight">
                                            <span>₹ {{ $value->price ?? '' }}</span> ₹{{ $value->dis_price ?? '' }}+
                                        </div>
                                    </a>
                                @else
                                    <a href="javascript:void(0);" class="dgreenbar" style="text-decoration: none;cursor: pointer;background-color:#3561fe;" data-bs-toggle="modal" data-bs-target="#successPopup">
                                        <div class="dgreenbarleft">
                                            <b>{{ $value->duration ?? '' }}</b>
                                            {{ $value->type }}
                                        </div>
                                        <div class="dgreenbarRight">
                                            <span>₹ {{ $value->price ?? '' }}</span> ₹{{ $value->dis_price ?? '' }}+
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    
                </div>
                @if($ranker->feedbacks->isNotEmpty())
                    <h3>Ratings and Feedback</h3>
                    <div class="dratingholder">
                    
                        @foreach($ranker->feedbacks as $feedback)
                            <div class="drating">
                                <div class="drate">
                                    <span> {{$feedback->rating}}/5 </span>
                                    <img src="{{ asset('images/upload/star.png') }}" class="img-fluid" alt="">
                                </div>
                                <p>{!! $feedback->text ?? '' !!}</p>
                                <div class="rankposternamdate">
                                    {{$feedback->first_name}} {{$feedback->last_name}}
                                    <span>{{date('M d, Y', strtotime($feedback->created_at))}}</span>
                                </div>
                                
                            </div>
                        @endforeach
                    
                    </div>
                @endif
                <!--<a href="#" class="drankersbtn">Show All Review</a>-->

                <h3>Upcoming Test</h3>
                <div class="upcomingholder">
                    <div class="upcomingblocks pinkupcoming">
                        <div class="upcomingleft">
                            <h5>Biology Topic Test For 12th And 12th Plus Students</h5>
                            <p class="exampdate">Exam Date: <span>12 Feb 2025</span></p>
                            <p class="examplocation">Location: <span>Diamond Harbour</span></p>
                            <a href="#">Buy Now</a>
                        </div>
                        <div class="upcomingright">
                            <img src="{{ asset('') }}web/images/upcoming_test_img_01.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="upcomingblocks purplecoming">
                        <div class="upcomingleft">
                            <h5>Physics Topic Test
                                For 12th And 12th Plus
                                Students</h5>
                            <p class="exampdate">Exam Date: <span>12 Feb 2025</span></p>
                            <p class="examplocation">Location: <span>Behala</span></p>
                            <a href="#">Buy Now</a>
                        </div>
                        <div class="upcomingright">
                            <img src="{{ asset('') }}web/images/upcoming_test_img_02.png" class="img-fluid">
                        </div>
                    </div>
                </div>
                <a href="{{ route('testseries') }}" class="drankersbtn">View More</a>

                @if($rules->isNotEmpty())
                <div class="rulesanregulation">
                    <h3>Rules and Regulations</h3>
                    <ul>
                        @foreach($rules as $rule)
                            <li>{!! $rule->rule !!}</li>
                        @endforeach
                    </ul>
                    <a href="#" class="drankersbtn">View More</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>

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
    @include('site.include.back_to_top')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
    <script src="{{ asset('') }}web/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('') }}web/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('') }}web/lib/owlcarousel/owl.carousel.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>
</body>

</html>
