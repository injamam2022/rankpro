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
    <link href="{{ asset('') }}web/css/rankers.css" rel="stylesheet">
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

    <div class="topbannerA rankerBanner">
        <div class="sliderheightloader">
            <div class="slideloaderfilter">
                <div id="circularG">
                    <div id="circularG_1" class="circularG"></div>
                    <div id="circularG_2" class="circularG"></div>
                    <div id="circularG_3" class="circularG"></div>
                    <div id="circularG_4" class="circularG"></div>
                    <div id="circularG_5" class="circularG"></div>
                    <div id="circularG_6" class="circularG"></div>
                    <div id="circularG_7" class="circularG"></div>
                    <div id="circularG_8" class="circularG"></div>
                </div>
            </div>
            <!-- <div class="owl-carousel upcoming-carousel wow fadeInUp" data-wow-delay="0.1s"> -->
            <div class="owl-carousel upcoming-carousel_a" id="ranker_banner_list_id">
                @foreach($ranker_banner_list as $value)
                    <div class="filter ranker_banner_list_id" data-subject="{{$value->subject_id}}" data-language="{{$value->language_id}}" data-course="{{$value->course_id}}" id="filter_div_{{$value->id}}" style="display: block;">
                        <div class="rankersblockSlider pink">
                            <h5>{{$value->name}}</h5>
                            <div class="rankersblockSliderText">{{$value->about}}</div>
                            <div class="row">
                                <div class="col-rankersblock">
                                    <div class="rankersblockLeft">
                                        <a href="javascript:void(0);" onclick="openModal('{{$value->video_link}}')"><img src="{{ asset('') }}web/images/icons/rankersblockSlider-play.png" alt="play button"
                                                class="topmateplaly"></a>
                                        <a href="{{ route('ranker.detail', encrypt($value->id)) }}" class="rconnect">Connect</a>
                                    </div>
                                </div>
                                <div class="col-rankersblock">
                                    <img src="{{ asset('uploads/ranker/' . ($value->profile_icon ?? 'default.png')) }}" class="img-fluid rankersImg"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <ul class="list-unstyled mb-0 filterButtonAll wow fadeInUp">
                <li class="filter-button filterBtn active" onclick="allFilter();">All</li>
                <li class="filterBtn languageClick">Language</li>
                <li class="filterSubBtn filterLanguageBtn">
                    <ul class="list-unstyled mb-0">
                        @foreach($language_list as $value)
                            <li class="filter-button filterBtn" onclick="changeFilter('{{$value->id}}','1')">{{$value->name}}</li>
                        @endforeach
                    </ul>
                </li>
                <!--<li class="filterBtn courseClick">Course</li>-->
                <!--<li class="filterSubBtn filterCourceBtn" style="display: none;">-->
                <!--    <ul class="list-unstyled mb-0" style="display: flex;">-->
                <!--        @foreach($course_list as $value)-->
                <!--            <li class="filter-button filterBtn" onclick="changeFilter('{{$value->id}}','2')">{{$value->name}}</li>-->
                <!--        @endforeach-->
                <!--    </ul>-->
                <!--</li>-->
                <li class="filterBtn subjectClick">Subject</li>
                <li class="filterSubBtn filterSubjectBtn" style="display: none;">
                    <ul class="list-unstyled mb-0" style="display: flex;">
                        @foreach($subject_list as $value)
                            <li class="filter-button filterBtn" onclick="changeFilter('{{$value->id}}','3')">{{$value->name}}</li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- Rankers top mate  -->
    <div class="rankarstopmarginA rankerRanks">
        <div class="cusContainer">
            <h2 class="mainheading wow fadeInUp" data-wow-delay="0.1s">
                Ace NEET with NEET rankers
            </h2>
            <span class="headinginfo wow fadeInUp" data-wow-delay="0.1s">Get a <b>One-on-One Session</b> with NEET
                Toppers – Bid for <b>First Free Session, Secure Your Spot</b>, and Learn from the Best to <b>Boost Your
                    Preparation!</b></span>
            <div class="rankerRanksBlocks">
                <div class="row">
                    @if($ranker_list->isNotEmpty())
                        @php
                            $colors = ['pink', 'blue', 'orange', 'green'];
                        @endphp
                        @foreach($ranker_list as $index => $ranker)
                        @php
                            $colorClass = $colors[$index % count($colors)];
                        @endphp
                            <div class="col-lg-6">
                                <div class="rankers-carousel wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="rankersblock {{ $colorClass }}" style="background-image: url('{{ asset('uploads/ranker/' . ($ranker->icon ?? 'default.png')) }}');">
                                        <h5>{{ $ranker->name }}</h5>
                                        <span>Score : {{ $ranker->score }}</span>
                                        <span>College : {{ $ranker->college }}</span>
                                        <span>Year : {{ $ranker->year }}</span>
                                        <span>AIR : {{ $ranker->air }}</span>
                                        @if($ranker->video_link)
                                        <a href="javascript:void(0);" onclick="openModal('{{$ranker->video_link}}')">
                                            <img src="{{ asset('web/images/icons/rankersblockSlider-play.png') }}" alt="play button" class="topmateplaly">
                                        </a>
                                        @endif
                                        <a href="{{ route('ranker.detail', encrypt($ranker->id)) }}" class="rconnect">Connect</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Rankers top mate ends -->

    <div class="booktest">
        <div class="cusContainer booktestblock">
            <h3>Want to be a part of our rankers
                <!-- RankPro makes learning easy  -->
                <!-- <span> Get tests delivered to your doorstep and excel from home!</span> -->
            </h3>
            <form action="#">
                <div class="bookinputs">
                    <input type="text" placeholder="enter your name*">
                    <input type="text" placeholder="enter your email*">
                </div>
                <button type="submit">Join now</button>
            </form>
        </div>
    </div>

    @include('site.include.footer')
    @include('site.include.call_to_action')
    @include('site.include.back_to_top')

    <!-- popup -->
    <div class="modal fade" id="examVideo">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <img src="images/examVideoClose_ic.png" class="img-fluid examVideoClose_ic" data-bs-dismiss="modal" alt="">
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
          <iframe id="modal_video" width="100%" height="420" src=""></iframe>
        </div>
      </div>
    </div>


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
    <script src="{{ asset('') }}web/js/main_a.js"></script>

    <script>
        $(document).ready(function () {
            // gallery function

            // gallery function end

            $(".languageClick").click(function () {
                $(this).toggleClass("languageActive");
            });

            $(".subjectClick").click(function () {
                $(this).toggleClass("languageActive");
            });

            $(".courseClick").click(function () {
                $(this).toggleClass("languageActive");
            });
        });

        $('.languageClick').on('click', function () {
            $('.filterLanguageBtn').toggleClass('visible');
            $('.filterLanguageBtn').animate({
                width: 'toggle',
            }, 200);
        });

        $('.subjectClick').on('click', function () {
            $('.filterSubjectBtn').toggleClass('visible');
            $('.filterSubjectBtn').animate({
                width: 'toggle',
            }, 200);
        });

        $('.courseClick').on('click', function () {
            $('.filterCourceBtn').toggleClass('visible');
            $('.filterCourceBtn').animate({
                width: 'toggle',
            }, 200);
        });


    </script>


    <script type="text/javascript">

        function changeFilter(id,type){
            var rb = $('.upcoming-carousel_a').height();
            $('.upcoming-carousel_a').owlCarousel('destroy');
            $('.upcoming-carousel_a').removeClass('owl-carousel');
            $('.upcoming-carousel_a').css('height', rb);
            $('.slideloaderfilter').fadeIn('slow');
            setTimeout(function () {
                $('.upcoming-carousel_a').addClass('owl-carousel');
                $('.upcoming-carousel_a').css('height', 'auto');
                $('.slideloaderfilter').fadeOut('slow');
                $(".upcoming-carousel_a").owlCarousel({
                    autoplay: true,
                    smartSpeed: 1000,
                    margin: 0,
                    loop: true,
                    center: false,
                    autoWidth: true,
                    dots: false,
                    nav: false,
                    responsive: {
                        0: {
                            items: 1
                        },
                        650: {
                            items: 2
                        },
                        992: {
                            items: 3
                        },
                        1400: {
                            items: 4
                        },
                        1600: {
                            items: 5
                        }
                    }
                });
            }, 1000);


            const listItems = document.querySelectorAll('.ranker_banner_list_id');

            if(type == 1){
                listItems.forEach(item => {
                  const language = item.getAttribute('data-language');
                  console.log(language,id);
                  if(language == id){
                    item.style.display = 'block';
                  }else{
                    item.style.display = 'none';
                  }
                });
            }

            if(type == 2){
                listItems.forEach(item => {
                  const course = item.getAttribute('data-course');

                  if(course == id){
                    item.style.display = 'block';
                  }else{
                    item.style.display = 'none';
                  }
                });

            }

            if(type == 3){
                listItems.forEach(item => {
                  const subject = item.getAttribute('data-subject');

                  if(subject == id){
                    item.style.display = 'block';
                  }else{
                    item.style.display = 'none';
                  }
                });

            }

            
        }

        function allFilter(){

            var rb = $('.upcoming-carousel_a').height();
            $('.upcoming-carousel_a').owlCarousel('destroy');
            $('.upcoming-carousel_a').removeClass('owl-carousel');
            $('.upcoming-carousel_a').css('height', rb);
            $('.slideloaderfilter').fadeIn('slow');
            setTimeout(function () {
                $('.upcoming-carousel_a').addClass('owl-carousel');
                $('.upcoming-carousel_a').css('height', 'auto');
                $('.slideloaderfilter').fadeOut('slow');
                $(".upcoming-carousel_a").owlCarousel({
                    autoplay: true,
                    smartSpeed: 1000,
                    margin: 0,
                    loop: true,
                    center: false,
                    autoWidth: true,
                    dots: false,
                    nav: false,
                    responsive: {
                        0: {
                            items: 1
                        },
                        650: {
                            items: 2
                        },
                        992: {
                            items: 3
                        },
                        1400: {
                            items: 4
                        },
                        1600: {
                            items: 5
                        }
                    }
                });
            }, 1000);

            const listItems = document.querySelectorAll('.ranker_banner_list_id');

            listItems.forEach(item => {
              item.style.display = 'block';
            });
        }
        function openModal(video_link) {
            document.getElementById("modal_video").src = "https://www.youtube.com/embed/"+video_link+"?controls=0";
            $("#examVideo").modal("show");
        }
    </script>
</body>

</html>
