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
            @foreach($banner_list as $value)
                <div class="cusContainer d-flex" style="max-width: 1420px;">
                    <div class="leftText wow fadeInUp" data-wow-delay="0.1s">
                        {!!$value->text!!}
                        @if (!Auth::check())
                            <a href="{{ route('signup') }}">Enroll now</a>
                        @endif
                    </div>
                    <div class="rightImg wow fadeIn" data-wow-delay="0.1s">
                        <img src="{{ asset('') }}web/images/bannerart.png" class="img-fluid leH" alt="" style="position: absolute;">
                        <img src="{{ asset('') }}uploads/banner/{{$value->image}}" class="img-fluid getH" width="678" height="381"
                            alt="NEET successor">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- banner End -->

    <!-- Rankers top mate  -->
    <div class="rankarstopmargin">
        <div class="cusContainer">
            <h2 class="mainheading wow fadeInUp" data-wow-delay="0.1s">
                Ace NEET with NEET rankers
            </h2>
            <span class="headinginfo wow fadeInUp" data-wow-delay="0.1s">Study smarter with direct one-on-one mentorship
                from
                NEET rankers.</span>
        </div>
        <div class="rankers-carousel wow fadeInUp" data-wow-delay="0.1s">
            <div class="slider__items">
                @if($ranker_list->isNotEmpty())
                    @php
                        $colors = ['pink', 'blue', 'orange', 'green'];
                    @endphp
                    @foreach($ranker_list as $index => $ranker)
                    @php
                        $colorClass = $colors[$index % count($colors)];
                    @endphp
                        <div class="rankersblock {{ $colorClass }}" style="background-image: url('{{ asset('uploads/ranker/' . ($ranker->icon ?? 'default.png')) }}');">
                            <h5>{{ $ranker->name }}</h5>
                            <span>Score : {{ $ranker->score }}</span>
                            <span>College : {{ $ranker->college }}</span>
                            <span>Year : {{ $ranker->year }}</span>
                            <span>AIR : {{ $ranker->air }}</span>
                            <a href="{{ route('ranker.detail', encrypt($ranker->id)) }}" class="rconnect">Connect</a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <a href="{{ route('ranker') }}" class="explorerrank">Explore Rankers</a>
        </div>
    </div>
    <!-- Rankers top mate ends -->

    <!-- scholarship top mate starts -->
    <div class="cusContainer scholarship">
        <div class="rankprotest">
            <div class="leftfixt">
                <img src="{{ asset('') }}web/images/scolar_test.png" width="598" height="624" class="img-fluid" alt="">
            </div>
            <div class="rightscroll">

                <div class="rankpro">Rank<span>Pro</span></div>
                <h2 class="wow fadeInUp" data-wow-delay="0.1s"> NEET Pro Test <span>Series (Offline)</span></h2>
                <ul class="wow fadeInUp" data-wow-delay="0.1s">
                    <li>Real-Exam Simulation</li>
                    <li>Dual Language Support</li>
                    <li>Convenient Locations</li>
                    <li>Same-Day Results</li>
                    <li>NEET Rank Prediction</li>
                </ul>
                <div class="ranktestbuttons">
                    @if (!Auth::check())
                        <a href="{{ route('signup')}}" class="registerbtn">Register Now</a>
                    @endif
                    <a href="{{ route('contact')}}" class="talktous">Talk to us</a>
                </div>

                <div class="rankpro">Rank<span>Pro</span></div>
                <h2 class="wow fadeInUp" data-wow-delay="0.1s"> Scholarship <span>Test</span></h2>
                <ul class="wow fadeInUp" data-wow-delay="0.1s">
                    <li>Offline + online mode</li>
                    <li>Scholarship Rewards</li>
                    <li>Convenient Centers: Multiple locations to fit your schedule.</li>
                    <li>Detailed result analysis</li>
                </ul>
                <div class="ranktestbuttons">
                    @if (!Auth::check())
                        <a href="{{ route('signup')}}" class="registerbtn">Register Now</a>
                    @endif
                    <a href="{{ route('contact')}}" class="talktous">Talk to us</a>
                </div>

                <div class="rankpro">Rank<span>Pro</span></div>
                <h2 class="wow fadeInUp" data-wow-delay="0.1s"> Test Master: <span>Free Customizable Mock Tests</span>
                </h2>
                <ul class="wow fadeInUp" data-wow-delay="0.1s">
                    <li>Unlimited Access</li>
                    <li>Fully Customizable</li>
                    <li>Advanced Result Analytics</li>
                    <li>NEET Rank Prediction</li>
                </ul>
                <div class="ranktestbuttons" style="padding-bottom: 0;">
                    @if (!Auth::check())
                        <a href="{{ route('signup')}}" class="registerbtn">Register Now</a>
                    @endif
                    <a href="{{ route('contact')}}" class="talktous">Talk to us</a>
                </div>

            </div>
        </div>
    </div>


    <!--<div class="advisorycommittee rankers-carousel wow fadeInUp" data-wow-delay="0.1s">-->
    <!--    <div class="cusContainer">-->
    <!--        <h2 class="mainheading"> Doctor-Led Advisory Committee </h2>-->
    <!--        <div class="realinfo">-->
    <!--            Experts share their insights to craft Shikkha’s NEET Prep-->
    <!--            curriculum-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="slider__items">-->
    <!--        <div class="advisoryblk pink">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('') }}web/images/advisory05.png" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk blue">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('') }}web/images/advisory01.png" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk orange">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('') }}web/images/advisory02.png" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk green">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('') }}web/images/advisory03.png" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk blue">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('') }}web/images/advisory04.png" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- scholarship top mate ends -->
    <div class="rankarstopmargin">
        <h2 class="mainheading">
            Upcoming Test
        </h2>
        <span class="headinginfo">Smart Preparation with the upcoming RankPro Tests</span>
        <div class="owl-carousel upcoming-carousel wow fadeInUp" data-wow-delay="0.1s">
            @foreach($upcoming_test as $value)
                <div class="upcomingblocks pinkupcoming">
                    <div class="upcomingleft">
                        <h5>{{$value->name}}</h5>
                        <p class="exampdate">
                            Exam Date: <span>{{$value->exam_date}}</span>
                        </p>
                        <p class="examplocation">
                            Location: <span>{{$value->location_name}}</span>
                        </p>
                        <a href="{{ route('testseries.details', encrypt($value->id)) }}">Buy Now</a>
                    </div>
                    <div class="upcomingright">
                        <img src="{{ asset('uploads/exam/' . ($value->exam_logo ?? 'upcoming_test_img_01.png')) }}" width="170" height="267" class="img-fluid">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="locationsection rankers-carousel wow fadeInUp" data-wow-delay="0.1s">
        <div class="cusContainer">
            <h2 class="mainheading"> RankPro Test Centers </h2>
            <div class="realinfo">Access your nearest test centers</div>
        </div>
        <div class="slider__items">
            
            @foreach($location as $value)
                <div class="locationblocks pink">
                    <h5>{{$value->location_name}}</h5>
                    <div class="schoolimg">
                        <img src="{{ asset('uploads/location/' . ($value->logo ?? 'default.png')) }}" width="315" height="170" class="img-fluid">
                    </div>
                    <p>{{$value->location_description}}</p>
                    <span class="loaddress">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
                            style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <g>
                                <path
                                    d="M218,0.5c8.5,1.3,17.1,2.2,25.6,3.9c49.8,10.1,90.5,35.2,121.9,75c23.7,30.1,37.9,64.4,42.1,102.6
                                                                    		c1.5,13.8,1.2,27.9,1.7,42.8c-2-1-2.6-1.1-3-1.5c-12-10.9-24-21.7-35.8-32.7c-1.4-1.3-2.3-3.6-2.6-5.6
                                                                    		c-4.5-33.8-17.7-63.7-40.4-89.1c-26.4-29.5-59.2-47.5-98.4-53.5c-35.4-5.4-69.1,0.2-100.7,16.9c-35.9,18.9-61.7,47.2-76.6,85
                                                                    		c-19.4,49.2-14.6,97.1,9.6,143.6c23.2,44.6,54.5,83.2,89.1,119.2c15.5,16.1,31.9,31.2,48.2,46.5c4.1,3.8,6.2,7.4,5.9,13.2
                                                                    		c-0.5,12.6-0.1,25.3-0.2,38c0,1.6-0.1,3.2-0.3,5.8c-4.7-3.8-8.9-7-12.9-10.3c-50.9-42.9-97-90.4-135.5-144.9
                                                                    		C32,321.6,12.4,285.6,3.7,244.6c-1.5-7.3-2.5-14.7-3.7-22.1c0-10.3,0-20.7,0-31c0.4-3.1,0.9-6.2,1.2-9.3
                                                                    		c4.6-38,18.4-72.4,42.1-102.5c31.4-40,72.2-65.1,122.1-75.3c8.4-1.7,17-2.6,25.5-3.9C200,0.5,209,0.5,218,0.5z" />
                                <path
                                    d="M288,511.5c-3.3-0.9-6.7-1.6-10-2.6c-23.3-7-38.4-27.8-38.6-53.5c-0.2-18.5,0-37,0-55.5c0-1.8,0-3.6,0-6.6
                                                                    		c-4.1,3.7-7.4,6.6-11.2,9.9c-8.9-9.8-17.7-19.4-26.9-29.4c51.9-47.3,103.5-94.4,155.4-141.7c13.3,12.1,26.3,24,39.3,35.9
                                                                    		c38.1,34.8,76.3,69.6,114.4,104.4c0.5,0.4,1.1,0.7,1.6,1.1c0,0.3,0,0.7,0,1c-9,9.5-17.9,19-27,28.6c-3.6-3.2-6.9-6.2-11-9.9
                                                                    		c0,2.7,0,4.3,0,5.9c0,19.5,0.2,39-0.1,58.5c-0.3,23.9-16.2,44.7-38.8,51.4c-3.3,1-6.7,1.7-10.1,2.5
                                                                    		C379.3,511.5,333.7,511.5,288,511.5z M356.8,286.3c-0.8,0.6-1.5,1.1-2.1,1.6c-24.4,22.2-48.8,44.4-73.1,66.8c-1.4,1.3-2.3,4-2.3,6
                                                                    		c-0.1,31.3-0.1,62.7-0.1,94c0,11.1,5.6,16.7,16.6,16.7c40.5,0,81,0,121.5,0c11.3,0,16.7-5.5,16.7-17c0-31.2,0-62.3,0.1-93.5
                                                                    		c0-3.2-1-5.2-3.3-7.3c-12.8-11.5-25.4-23.1-38.1-34.7C380.8,308.2,368.9,297.3,356.8,286.3z" />
                                <path
                                    d="M289.2,205.2c-0.1,46.9-38.3,84.8-85.1,84.5c-46.8-0.3-84.4-38.1-84.3-84.8c0.1-46.9,38.3-84.8,85.1-84.5
                                                                    		C251.7,120.7,289.3,158.5,289.2,205.2z M204.1,249.7c24.6,0.4,44.9-19.7,45.1-44.5c0.2-24.5-19.3-44.4-44.1-45
                                                                    		c-24.6-0.5-44.9,19.3-45.4,44.2C159.3,229,179.3,249.4,204.1,249.7z" />
                                <path
                                    d="M327.4,430.2c0-20,0-39.5,0-59.4c19.8,0,39.4,0,59.3,0c0,19.7,0,39.4,0,59.4C367,430.2,347.4,430.2,327.4,430.2z" />
                            </g>
                        </svg>
                        {{$value->address}}, {{$value->country_name}},
                        {{$value->state_name}}, {{$value->country_name}} - {{$value->zip_code}}.</span>
                    <span class="lophone">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
                            style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <g>
                                <path d="M502,331.2c-1,3.8-2.1,7.6-3.1,11.4c-3.2,12.7-11.3,21.7-21.8,28.6c-3.2,2.1-4,4.4-4.2,8.1c-0.6,12.6,0.1,25.6-3.2,37.5
                                                		c-8.2,30.2-28.8,49.1-59.5,55.1c-12.6,2.4-25.8,1.2-38.8,1.3c-3.2,0-5.1,0.9-6.9,3.6c-8.7,12.7-20.2,20.2-36.3,19.7
                                                		c-12.2-0.4-24.3,0.1-36.5-0.2c-17.3-0.4-28.6-10.1-35.4-25c-6.8-15-3.8-29.3,6.6-41.9c8-9.7,18.7-14.1,31.2-14.3
                                                		c11.5-0.2,23-0.2,34.5,0c15.8,0.2,28.1,6.9,36.7,20.3c0.8,1.2,2.3,2.8,3.5,2.7c11.8,0,23.7,0.6,35.4-0.8
                                                		c17.2-2.1,31.8-18.7,32.8-36c0.4-7,0.1-14,0.1-21.4c-8.8-2.4-16.2-7.3-21.8-14.7c-5.2-6.8-7.4-14.9-7.4-23.4
                                                		c-0.1-29.2,0-58.3,0-87.5c0-17.9,9.2-31.2,25.9-37.6c0.8-0.3,1.5-0.6,2.2-0.9c-5-80-73.3-161.5-174-164.8
                                                		C158.2,47.7,82.4,128.5,75.5,215.8c8.7,2.7,16.1,7.7,21.6,15.1c4.8,6.5,6.8,14.1,6.9,22c0.1,29.8,0.1,59.7,0,89.5
                                                		c-0.1,23.8-21.1,41.1-44.8,38.2c-22.9-2.8-43.6-21.7-47.7-41.8c-0.5-2.6-1-5.1-1.6-7.7c0-22.3,0-44.7,0-67c0.6-3.1,1-6.2,1.8-9.2
                                                		c4-13.9,12.6-24.3,24.5-32.2c1.6-1.1,3.3-3.4,3.4-5.2c1.3-23.3,7.3-45.6,15.5-67.2c7.6-19.8,19-37.7,32.4-54.1
                                                		c24.7-30.3,55.1-52.6,91.8-66.5C213.2,17,248,12.9,283.5,17.3c38,4.8,72.6,18.7,103.1,42.2c31.1,23.9,54.8,53.5,69.6,90.1
                                                		c8.9,22,14.6,44.7,16.1,68.5c0.1,1.6,1.6,3.5,3,4.4c11.4,7.6,20.5,16.9,23.9,30.7c0.9,3.7,2,7.3,3,10.9
                                                		C502,286.5,502,308.8,502,331.2z M68.6,297.7c0-13.8,0-27.7,0-41.5c0-6.5-1.5-7.6-7.9-6c-9,2.2-15.6,10.2-15.7,19.5
                                                		c-0.1,18.7-0.1,37.3,0,56c0.1,9.3,6.8,17.4,15.7,19.5c6.4,1.6,7.8,0.5,7.9-6.1C68.6,325.3,68.6,311.5,68.6,297.7z M443.2,297.7
                                                		c0,14.2,0,28.3,0,42.5c0,5.3,1.3,6.3,6.3,5.4c9.1-1.6,15.9-8.2,16.3-17.4c0.7-15.3,0.8-30.6,0.9-45.9c0-5.1-0.3-10.3-0.9-15.4
                                                		c-0.9-8.4-7-14.9-15.4-16.8c-5.9-1.3-7.3-0.3-7.3,5.6C443.2,269.7,443.2,283.7,443.2,297.7z M311.7,450.2c-5.5,0-11,0-16.5,0
                                                		c-3.8,0-6.9,1.4-6.8,5.5c0,3.9,2.7,5.7,6.7,5.7c11-0.1,21.9,0,32.9,0c4,0,7-1,6.9-5.8c0-4.7-3.4-5.2-6.8-5.3
                                                		C322.7,450.1,317.2,450.2,311.7,450.2z" />
                                <path
                                    d="M256.3,127c24.3,0,48.7,0,73,0c26,0,47.7,16.8,54.2,41.8c1,3.8,1.5,7.9,1.5,11.8c0.1,33.8,0.1,67.7,0,101.5
                                                		c-0.1,27.3-24.3,52.4-51.6,53.1c-20.2,0.4-40.3,0-60.5,0.3c-2.5,0-5.5,1.3-7.3,3c-12.7,12.2-25.3,24.7-37.8,37
                                                		c-9.4,9.3-20.4,12.5-32.8,7.2c-11.7-5.1-17.8-14.6-18.4-27.4c-0.2-5.3-0.3-10.7,0-16c0.1-3.2-1.1-4.3-4-4.9
                                                		c-20.4-3.9-34.6-15.7-42.1-35c-2-5.2-3.3-11-3.3-16.5c-0.3-34.3-0.3-68.7-0.1-103c0.2-27.9,24.9-52.7,52.8-53
                                                		C205.3,126.9,230.8,127.1,256.3,127C256.3,127.1,256.3,127,256.3,127z M212,340.7c0.6,0.5,1.3,0.9,1.9,1.4
                                                		c10.9-11.5,21.9-23,32.8-34.6c4.9-5.2,10.7-7.4,17.8-7.4c21.8,0.1,43.7,0.1,65.5,0c10.9,0,19.6-8.1,19.7-19
                                                		c0.2-33.3,0.2-66.7,0-100c-0.1-10.6-8.8-18.8-19.4-18.8c-49.5-0.1-99-0.1-148.5,0c-10.9,0-19.6,8.4-19.6,19.2
                                                		c-0.1,33.2-0.1,66.3,0,99.5c0,10.8,8.8,19,19.6,19.2c2.8,0,5.7,0,8.5,0c14.6,0,21.7,7.2,21.8,21.8C212,328.2,212,334.4,212,340.7z" />
                                <path d="M279,233.4c0,12.7-10.4,22.8-23.3,22.8c-12.8,0-22.9-10.3-22.8-23.3c0-12.8,10.4-22.9,23.3-22.8
                                                		C269,210.1,279.1,220.5,279,233.4z" />
                                <path d="M200,256.2c-12.2,0-22.7-10.6-22.8-23c-0.1-12.6,10.6-23.2,23.2-23.1c12.4,0.1,22.7,10.5,22.7,23
                                                		C223.1,245.8,212.7,256.2,200,256.2z" />
                                <path d="M311.9,256.2c-12.9,0-23.1-10.1-23.1-23c-0.1-12.9,10.2-23.1,23.1-23.1c12.4,0,22.7,10.5,22.7,23
                                                		C334.5,245.6,324.2,256.2,311.9,256.2z" />
                            </g>
                        </svg>
                        {{$value->phone_number}}</span>
                    <a href="{{route('contact')}}?id={{$value->id}}&type=TC">Enroll Now</a>
                </div>
            @endforeach
        </div>
    </div>

    @if ($test_series->isNotEmpty())
        <div class="testsetopmargin rankers-carousel wow fadeInUp" data-wow-delay="0.1s">
            <div class="cusContainer">
                <h2 class="mainheading">New Light Test Series</h2>
                <div class="realinfo">Boost your NEET prep with the Test Series by New Light Institute</div>
            </div>
            <div class="slider__items">
                @php
                    $colors = ['pink', 'blue', 'orange', 'green'];
                    $i = 0;
                @endphp

                @foreach ($test_series as $test)
                    <div class="testserblocks {{ $colors[$i % count($colors)] }}">
                        <div class="testserleft">
                            <h5>{!! $test->headings[0]->heading !!}</h5>
                            <p class="testtext">{!! $test->descriptions[0]->text !!}</p>
                                
                            <a href="{{ route('new_light.details', encrypt($test->id)) }}">Buy Now</a>
                        </div>
                        <div class="testserright">
                            <img src="{{ asset('uploads/test_series/' . ($test->icon ?? 'default.png')) }}" width="206" height="264" class="img-fluid">
                        </div>
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
        </div>
    @endif


    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <a href="{{ route('new_light') }}" class="explorerrank">Explore Test Series</a>
    </div>

    <!--<div class="cusContainer havequeries wow fadeInUp" data-wow-delay="0.1s">-->
    <!--    <div class="havequerieblock">-->
    <!--        <div class="queryleft">-->
    <!--            <div class="joinqury">-->
    <!--                <h3><span>Have Doubts?</span> We’re Just a Message Away!</h3>-->
    <!--                <span class="qstext">Let us help you to make NEET prep easier!</span><br>-->
    <!--                <a href="#" class="joinquerybtn">Enquiry Now</a>-->
    <!--                <a href="#" class="roundcall">Talk to us</a>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="queryform">-->
    <!--            <h3>Your First Step to NEET Success – </h3>-->
    <!--            <span class="qstext">Book Your Free Mock Test Now!</span>-->
    <!--            <form action="#">-->
    <!--                <div class="row">-->
    <!--                    <div class="col-sm-6"><input type="text"></div>-->
    <!--                    <div class="col-sm-6"><input type="text"></div>-->
    <!--                </div>-->
    <!--                <div class="row">-->
    <!--                    <div class="col-sm-6"><input type="text"></div>-->
    <!--                    <div class="col-sm-6">-->
    <!--                        <select>-->
    <!--                            <option>Select Course Applying for</option>-->
    <!--                            <option>General query</option>-->
    <!--                            <option>Test series</option>-->
    <!--                            <option>Report issues</option>-->
    <!--                            <option>Careers at Rank Pro</option>-->
    <!--                        </select>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <textarea></textarea>-->
    <!--                <button type="submit">Submit Now</button>-->
    <!--            </form>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->

    @if ($head_quaters->isNotEmpty())
        <div class="cusContainer headquater wow fadeInUp" data-wow-delay="0.1s">
            @foreach ($head_quaters as $head_quater)
                <div class="headquaterblock">
                    <div class="headvideo">
                        @if (!empty($head_quater->video))
                            @if (Str::contains($head_quater->video, 'youtube.com') || Str::contains($head_quater->video, 'youtu.be'))
                                <iframe width="626" height="353"
                                    src="{{ Str::replace('watch?v=', 'embed/', $head_quater->video) }}"
                                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                                </iframe>
                            @else
                                <video width="626" height="353" controls>
                                    <source src="{{ config('app.admin_url') . $head_quater->video }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        @else
                            <img src="{{ asset('web/images/head_quater_video.png') }}" width="626" height="353" alt="" class="img-fluid">
                        @endif
                    </div>
                    <div class="headcontent">
                        @if ($head_quater->descriptions->isNotEmpty())
                            @foreach ($head_quater->descriptions as $description)
                                {!! $description->text !!}
                            @endforeach
                        @else
                        @endif
                        <a href="#">Attend free session with our Top educators</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($real_story || $success_story->isNotEmpty())
        <div class="realstory wow fadeInUp" data-wow-delay="0.1s">
            <div class="realstoryblock rankers-carousel">
                <div class="cusContainer">
                    <h3>Real <span class="blue">Story</span> Real <span class="pink">Success</span></h3>
                    @if ($real_story)
                        <div class="realinfo">{!! $real_story->text !!}</div>
                    @endif
                </div>


                <!--.storylong01 {-->
                <!--    background:#a9b379 url(../images/storylong01.png) no-repeat; padding: 300px 40px 40px 40px; text-align: center; color: #000;-->
                <!--}-->
                <!--.storylong02 {-->
                <!--    background:#2f4952 url(../images/storylong02.png) no-repeat center bottom; text-align: center;color: #fff;padding: 60px 40px 40px 40px;-->
                <!--}-->
                <!--.storylong03 {-->
                <!--    background:#fff url(../images/storylong03.png) no-repeat center bottom; padding-top: 40px;text-align: center;padding: 40px;line-height: 42px;color: #14806f;-->
                <!--}-->
                <!--.storyshort01 {-->
                <!--    background: url(../images/storyshort01.png) no-repeat; padding: 62px 20px 20px 270px;-->
                <!--}-->
                <!--.storyshort02 {-->
                <!--    background: url(../images/storyshort02.png) no-repeat; padding: 62px 240px 20px 25px;-->
                <!--}-->
                

                @if ($success_story->isNotEmpty())
                    <div class="slider__items">
                        @php
                            $boxClass = ['yellow', 'blue', 'pink', 'orange', 'green'];
                            $boxType = ['onebox storylong01', 'samebox storyshort01', 'samebox storyshort02', 'onebox storylong02', 'onebox storylong03'];
                            $totalStories = count($success_story);
                        @endphp

                        @foreach ($success_story as $index => $story)
                            @php
                                $classIndex = $index % count($boxClass);
                                $typeIndex = $index % count($boxType);
                            @endphp

                            {{-- Check if it's the second and third item, wrap them in "twobox" --}}
                            @if ($index % 5 == 1)
                                <div class="twobox">
                            @endif

                            <div class="{{ $boxType[$typeIndex] }} {{ $boxClass[$classIndex] }}" style="background:#a9b379 url({{ asset('uploads/success_story/' . ($story->image ?? 'default.png')) }}) no-repeat;">
                                @foreach ($story->descriptions as $desc)
                                    {!! $desc->text !!}
                                @endforeach
                                <h6>{{ $story->name }}</h6>
                                <span>{{ $story->address }}</span>
                            </div>

                            {{-- Close "twobox" after second and third item --}}
                            @if ($index % 5 == 2 || $index == $totalStories - 1)
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!--<div class="cusContainer hurry">-->
    <!--    <div class="row">-->
    <!--        <div class="col-lg-6 d-flex align-items-end">-->
    <!--            <h3>-->
    <!--                <span>Don’t Wait,</span> Start Your Test Series and <span>Track Your Growth</span>-->
    <!--            </h3>-->
    <!--            <img src="{{ asset('') }}web/images/icons/hurry.png" width="148" height="160" alt="" class="hurryimg">-->
    <!--        </div>-->
    <!--        <div class="col-lg-6">-->
    <!--            <div class="hurryblock">-->
    <!--                <p>Get personalized growth insights with every test. Don’t wait – start now and see how quickly you-->
    <!--                    can improve.</p>-->
    <!--                <a href="#">Register Now</a>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- leftfixt2 -->
    
    <div class="cusContainer faqcont">
        <div class="row">
            <div class="col-lg-5 leftfixt2">
                <h3>Frequently Asked Questions</h3>
                <p class="mobiledisplay">Welcome to Edufast! Discover the art and science of creating engaging user
                    Interfaces and seamless
                    user
                    experiences</p>
                <div id="list-example" class="list-group">
                    <a class="list-group-item list-group-item-action active" href="#list-item-1">Online Education</a>
                    <a class="list-group-item list-group-item-action" href="#list-item-2">Payment Method</a>
                    <a class="list-group-item list-group-item-action" href="#list-item-3">Pricing Plan</a>
                </div>
            </div>
                    @if ($asked_questien->isNotEmpty())
                        @php
                            $onlineEducation = $asked_questien->filter(fn($q) => $q->type == 'Online Education');
                            $paymentMethod = $asked_questien->filter(fn($q) => $q->type == 'Payment Method');
                            $pricingPlan = $asked_questien->filter(fn($q) => $q->type == 'Pricing Plan');
                        @endphp

                        <div class="col-lg-7">
                            <div>
                                {{-- Online Education Section --}}
                                @if ($onlineEducation->isNotEmpty())
                                    <div id="list-item-1">
                                        <h5>Online Education</h5>
                                        <div class="questionsdivs">
                                            <div class="question">
                                                <div class="accordion" id="accordionOnlineEducation">
                                                    @foreach ($onlineEducation as $key => $question)
                                                        @php
                                                            $headingId = "headingOE" . ucfirst(num2word($key + 1));
                                                            $collapseId = "collapseOE" . ucfirst(num2word($key + 1));
                                                        @endphp
                                                        <div class="accordion-item">
                                                            <h4 class="accordion-header" id="{{ $headingId }}">
                                                                <button class="accordion-button {{ $key == 0 ? '' : 'collapsed' }}" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                                                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                                                    aria-controls="{{ $collapseId }}">
                                                                    {!! strip_tags($question->text, '<strong><em><u>') !!}
                                                                </button>
                                                            </h4>
                                                            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                                                aria-labelledby="{{ $headingId }}" data-bs-parent="#accordionOnlineEducation">
                                                                <div class="accordion-body">
                                                                    {!! preg_replace('/<div class="raw-html-embed">|<\/div>/', '', $question->text2) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Payment Method Section --}}
                                @if ($paymentMethod->isNotEmpty())
                                    <div id="list-item-2">
                                        <h5>Payment Method</h5>
                                        <div class="questionsdivs">
                                            <div class="question">
                                                <div class="accordion" id="accordionPaymentMethod">
                                                    @foreach ($paymentMethod as $key => $question)
                                                        @php
                                                            $headingId = "headingPM" . ucfirst(num2word($key + 1));
                                                            $collapseId = "collapsePM" . ucfirst(num2word($key + 1));
                                                        @endphp
                                                        <div class="accordion-item">
                                                            <h4 class="accordion-header" id="{{ $headingId }}">
                                                                <button class="accordion-button {{ $key == 0 ? '' : 'collapsed' }}" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                                                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                                                    aria-controls="{{ $collapseId }}">
                                                                    {!! strip_tags($question->text, '<strong><em><u>') !!}
                                                                </button>
                                                            </h4>
                                                            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                                                aria-labelledby="{{ $headingId }}" data-bs-parent="#accordionPaymentMethod">
                                                                <div class="accordion-body">
                                                                    {!! preg_replace('/<div class="raw-html-embed">|<\/div>/', '', $question->text2) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Pricing Plan Section --}}
                                @if ($pricingPlan->isNotEmpty())
                                    <div id="list-item-3">
                                        <h5>Pricing Plan</h5>
                                        <div class="questionsdivs">
                                            <div class="question">
                                                <div class="accordion" id="accordionPricingPlan">
                                                    @foreach ($pricingPlan as $key => $question)
                                                        @php
                                                            $headingId = "headingPP" . ucfirst(num2word($key + 1));
                                                            $collapseId = "collapsePP" . ucfirst(num2word($key + 1));
                                                        @endphp
                                                        <div class="accordion-item">
                                                            <h4 class="accordion-header" id="{{ $headingId }}">
                                                                <button class="accordion-button {{ $key == 0 ? '' : 'collapsed' }}" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                                                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                                                    aria-controls="{{ $collapseId }}">
                                                                    {!! strip_tags($question->text, '<strong><em><u>') !!}
                                                                </button>
                                                            </h4>
                                                            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                                                aria-labelledby="{{ $headingId }}" data-bs-parent="#accordionPricingPlan">
                                                                <div class="accordion-body">
                                                                    {!! preg_replace('/<div class="raw-html-embed">|<\/div>/', '', $question->text2) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($gallery->isNotEmpty())
        <div class="closerlook">
            <h3>Take a closer look at <b class="rankpro">Rank<span>Pro</span></b></h3>
            <div class="rankers-carousel">
                <div class="slider__items">
                    @foreach($gallery as $index => $image)
                        @if($index % 2 == 0)
                            <div class="closerdiv {{ $index == 2 ? 'green' : '' }}">
                        @endif
                                <div class="{{ $index % 2 == 0 ? 'ablock' : 'bblock' }}
                                    {{ ['pink', 'yellow', 'orange', 'blue', 'green'][$index % 5] }}">
                                    <img src="{{ asset('uploads/gallery/' . ($image->image ?? 'default.png')) }}"
                                        alt="" width="480" height="{{ $index % 2 == 0 ? '306' : '433' }}" class="img-fluid">
                                </div>
                        @if($index % 2 == 1 || $loop->last)
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($as_mention->isNotEmpty())
        <div class="cusContainer mentioned">
            <h3>"As Mentioned In"</h3>
            <div class="mentionedblock">
                @foreach($as_mention as $mention)
                    <div class="mentionadd">
                        <img src="{{ asset('uploads/mention/' . $mention->image) }}" alt="" width="262" height="82" class="img-fluid">
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    <div class="booktest">
        <div class="cusContainer booktestblock">
            <h3>
                RankPro makes learning easy
                <span> Get tests delivered to your doorstep and excel from home!</span>
            </h3>
            <form action="{{ route('save_subscription') }}" method="get" onsubmit="return rankProFormValidation();">
                <div class="bookinputs">
                    <input type="text" id="name" name="name" placeholder="enter your name*">
                    <input type="text" id="email" name="email" placeholder="enter your email*">
                </div>
                <button type="submit">Submit now</button>
            </form>
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
    <script>
        $(document).ready(function () {

            var sectionIds = $('a.list-group-item');

            $(document).scroll(function () {
                sectionIds.each(function () {

                    var container = $(this).attr('href');
                    var containerOffset = $(container).offset().top;
                    var containerHeight = $(container).outerHeight();
                    var containerBottom = containerOffset + containerHeight;
                    var scrollPosition = $(document).scrollTop();

                    if (scrollPosition < containerBottom - 20 && scrollPosition >= containerOffset - 20) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
            });
            getLeH();
        });
        
        function getLeH() {
            var getH = $('.getH').height();
            $('.leH').css('height',getH);
        }
        $(window).resize(function(){
          getLeH();
        });
    </script>
</body>

</html>
