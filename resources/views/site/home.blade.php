<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta name="title" content="NEET AI Platform - Unlimited Free Mock Tests, Test Series & Find Your Mentor">
    <meta name="description" content="Prepare for NEET with AI-powered tools. Access unlimited free mock tests and full test series based on the latest NEET exam pattern. Get expert guidance, detailed analysis, and connect with top NEET mentors to boost your score.">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
       <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('web/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('web/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" rel="stylesheet">



    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <!-- New design fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Redesign Stylesheet (loads after style.css so it overrides cleanly) -->
     <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    <link href="{{ asset('web/css/redesign.css') }}" rel="stylesheet">
    @include('site.include.head_meta')

   <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/Draggable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/InertiaPlugin.min.js"></script>

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

<!-- banner Start (Veluno-left + neetprep-right hero) -->
    <section class="rh">
        <div class="rh__timer" id="neetTimer" data-target="2027-05-03T09:00:00+05:30">
            <span class="rh__timer-kicker">NEET 2027 countdown</span>
            <div class="rh__timer-units" aria-live="polite">
                <div class="rh__timer-unit"><b id="ntDays">00</b><span>Days</span></div>
                <div class="rh__timer-unit"><b id="ntHours">00</b><span>Hours</span></div>
                <div class="rh__timer-unit"><b id="ntMins">00</b><span>Mins</span></div>
                <div class="rh__timer-unit"><b id="ntSecs">00</b><span>Secs</span></div>
            </div>
        </div>
        <div class="rh__inner">
            <!-- LEFT -->
            <div class="rh__left">
                <span class="rh__eyebrow"><i></i>NEET UG &middot; AI-Powered Prep</span>
                <h1 class="rh__title">Timeless prep<br>for <span class="rh__accent">serious NEET aspirants</span></h1>
                <p class="rh__lead">Unlimited free mock tests, real exam patterns, and one-on-one mentorship from top NEET rankers - crafted for Class 12 &amp; repeaters.</p>

                <div class="rh__cta">
                    @if (Auth::check())
                        <a href="{{ route('custom_test') }}" class="rh__btn rh__btn--dark">
                            Start practising
                            <span class="rh__btn-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H8M17 7v9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="rh__btn rh__btn--pink">Go to Dashboard</a>
                    @else
                        <a href="{{ route('signup') }}" class="rh__btn rh__btn--dark">
                            Start practising
                            <span class="rh__btn-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H8M17 7v9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>
                    @endif
                </div>

                <div class="rh__lowerrow">
                    <!-- mini card -->
                    <a href="{{ route('new_light') }}" class="rh__mini">
                        <div class="rh__mini-img" style="background-image:url('{{ asset('web/images/scolar_test.png') }}');"></div>
                        <div class="rh__mini-txt">
                            <strong>NEET Pro Test Series</strong>
                            <span>Real-exam simulation. Same-day results.</span>
                        </div>
                    </a>
                </div>
            </div>

           <!-- RIGHT: Start Test card -->
            <div class="rh__right" id="rhTilt">
                <div class="rh__badge-float rh__badge-float--a" data-depth="34">&#10003; Projected Score &middot; <b>648</b></div>
                <div class="rh__badge-float rh__badge-float--b" data-depth="46">&uarr; 7-day streak &middot; <b>+142 solved</b></div>

                <div class="rh__test" id="rhTestCard">
                    <div class="rh__test-head">
                        <span class="rh__test-kicker">Ready when you are</span>
                        <span class="rh__test-live"><i></i>Live now</span>
                    </div>

                    <div class="rh__subjects" id="rhSubjects">
                        <button class="rh__subj is-active" data-subject="physics" data-q="45" data-min="60" data-ch="Kinematics &middot; Optics &middot; Modern Physics">Physics</button>
                        <button class="rh__subj" data-subject="chemistry" data-q="45" data-min="60" data-ch="Organic &middot; Electrochemistry &middot; Bonding">Chemistry</button>
                        <button class="rh__subj" data-subject="biology" data-q="90" data-min="90" data-ch="Genetics &middot; Morphology &middot; Ecology">Biology</button>
                    </div>

                    <h3 class="rh__test-title" id="rhTestTitle">Physics - Full Chapter Test</h3>

                    <div class="rh__test-meta">
                        <div class="rh__meta"><span class="rh__meta-num" id="rhQ">45</span><span class="rh__meta-lbl">Questions</span></div>
                        <div class="rh__meta"><span class="rh__meta-num" id="rhMin">60</span><span class="rh__meta-lbl">Minutes</span></div>
                        <div class="rh__meta"><span class="rh__meta-num">NEET</span><span class="rh__meta-lbl">Pattern</span></div>
                    </div>

                    <div class="rh__chaps" id="rhChaps">
                        <span class="rh__chap">Kinematics</span><span class="rh__chap">Optics</span><span class="rh__chap">Modern Physics</span>
                    </div>

                    @if (Auth::check())
                        <a href="{{ route('custom_test') }}" class="rh__start-btn" id="rhStart">
                            <span>Start Test</span>
                            <span class="rh__start-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>
                    @else
                        <a href="{{ route('signup', ['next' => 'custom_test']) }}" class="rh__start-btn" id="rhStart">
                            <span>Start Test</span>
                            <span class="rh__start-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>
                    @endif
                    <span class="rh__test-foot">No card needed &middot; Instant result &amp; rank prediction</span>
                </div>
            </div>
        </div>
    </section>
    <!-- banner End -->

    <!-- Pricing -->
    <section class="pr" id="pricing">
        <div class="pr__inner">
            <div class="pr__head">
                <div>
                    <span class="pr__eyebrow"><i></i>Pricing</span>
                    <h2 class="pr__title">Simple, transparent <span class="pr__grad">pricing</span></h2>
                </div>
                <p class="pr__sub">Choose the plan that fits your NEET preparation journey. First 3 custom tests are completely free.</p>
            </div>

            <div class="pr__grid">
                @php
                    $plans = [
                        ['name'=>'Starter','tag'=>'Best value','featured'=>true,'desc'=>'For focused CBT practice','price'=>'1,999','period'=>'until 3 May 2027','cta'=>'Get this deal','features'=>['Real CBT-based practice platform','Chapter-wise practice (P/C/B)','Complete test series access','15 years NEET PYQs','Performance analytics']],
                        ['name'=>'Pro','tag'=>'Popular','featured'=>false,'desc'=>'CBT + live classes','price'=>'2,999','period'=>'until 3 May 2027','cta'=>'Get this deal','features'=>['Everything in Starter','Live classes','Live poll-based sessions','Recorded lectures','Notes PDF (annotated)']],
                        ['name'=>'Pro Plus','tag'=>'Popular','featured'=>false,'desc'=>'Full live + doubt solving','price'=>'4,999','period'=>'until 3 May 2027','cta'=>'Get this deal','features'=>['Everything in Pro','Live doubt-solving sessions','Extra AI credits','Theory lectures','Priority support']],
                        ['name'=>'Elite','tag'=>'All access','featured'=>false,'desc'=>'Everything, all-in','price'=>'6,999','period'=>'until 3 May 2027','cta'=>'Get this deal','features'=>['Everything in Pro Plus','1-on-1 mentorship','Offline test centres','Rank prediction','Dedicated mentor']],
                    ];
                @endphp
                @foreach($plans as $p)
                    <article class="pr__card {{ $p['featured'] ? 'is-featured' : '' }}">
                        @if($p['featured'])<span class="pr__badge">Recommended</span>@endif
                        <div class="pr__card-top">
                            <span class="pr__tag">{{ $p['tag'] }}</span>
                            <h3 class="pr__name">{{ $p['name'] }}</h3>
                            <p class="pr__desc">{{ $p['desc'] }}</p>
                        </div>
                        <div class="pr__price">
                            <span class="pr__currency">&#8377;</span><span class="pr__amount">{{ $p['price'] }}</span>
                            <span class="pr__period">/ {{ $p['period'] }}</span>
                        </div>
                        <a href="{{ Auth::check() ? route('plans') : route('signup') }}" class="pr__cta {{ $p['featured'] ? 'pr__cta--solid' : '' }}">{{ $p['cta'] }}</a>
                        <ul class="pr__features">
                            @foreach($p['features'] as $f)
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ $f }}
                                </li>
                            @endforeach
                        </ul>
                        <span class="pr__note">First 3 custom tests are free.</span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Pricing end -->

    <!-- How it works -->
    <section class="hw" id="features">
        <div class="hw__inner">
            <div class="hw__head">
                <span class="hw__eyebrow"><i></i>See it in action</span>
                <h2 class="hw__headline" id="hwHeadline">Watch how RankPro works</h2>
                <p class="hw__sub">In just 2 minutes, see how thousands of aspirants go from their first mock test to a predicted NEET rank - with AI analysis, real exam patterns, and mentorship built in.</p>
                <div class="hw__points">
                    <span class="hw__point"><i></i>Set up in under a minute</span>
                    <span class="hw__point"><i></i>No credit card needed</span>
                    <span class="hw__point"><i></i>See your weak chapters instantly</span>
                </div>
            </div>

            <a href="https://www.youtube.com" target="_blank" rel="noopener" class="hw__video" id="hwVideo">
                <div class="hw__video-photo hw__video-photo--local"></div>
                <div class="hw__video-shade"></div>
                <span class="hw__video-title">RankPro Walkthrough</span>
                <span class="hw__play" id="hwPlay">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                </span>
                <span class="hw__playlabel">Watch the walkthrough</span>
                <span class="hw__meta"><span class="hw__meta-dot"></span>2:14 &middot; Full walkthrough</span>
            </a>
        </div>
    </section>
    <!-- How it works end -->

    <!-- Benefits flow -->
    <section class="bf">
        <div class="bf__inner">
            <div class="bf__head">
                <span class="bf__eyebrow"><i></i>The RankPro method</span>
                <h2 class="bf__title">Every step <span class="bf__grad">leads to your rank</span></h2>
                <p class="bf__sub">A connected system where each thing you do compounds into a better NEET score.</p>
            </div>

            <div class="bf__flow" id="bfFlow">
                <!-- animated connecting line -->
                <svg class="bf__line" viewBox="0 0 1000 900" preserveAspectRatio="none" aria-hidden="true">
                    <path class="bf__line-bg" d="M180,90 C420,90 560,230 700,230 C840,230 500,430 260,430 C60,430 300,650 620,650 C820,650 760,830 500,830" fill="none"/>
                    <path class="bf__line-fg" id="bfPath" d="M180,90 C420,90 560,230 700,230 C840,230 500,430 260,430 C60,430 300,650 620,650 C820,650 760,830 500,830" fill="none"/>
                    <circle class="bf__line-dot" id="bfDot" r="7"/>
                </svg>

                @php
                    $steps = [
                        ['n'=>'01','t'=>'Take a mock test','d'=>'Real NEET-pattern tests that mirror exam-day pressure.','pos'=>'left'],
                        ['n'=>'02','t'=>'Get instant analysis','d'=>'AI pinpoints your weak chapters the moment you finish.','pos'=>'right'],
                        ['n'=>'03','t'=>'Practice smarter','d'=>'Targeted DPPs auto-curated to exactly where you slip.','pos'=>'left'],
                        ['n'=>'04','t'=>'Learn from rankers','d'=>'One-on-one mentorship from students who already cracked it.','pos'=>'right'],
                        ['n'=>'05','t'=>'Climb the rank','d'=>'Watch your predicted AIR rise, test after test.','pos'=>'center'],
                    ];
                @endphp
                <div class="bf__steps">
                    @foreach($steps as $s)
                        <article class="bf__card bf__card--{{ $s['pos'] }}">
                            <span class="bf__card-num">{{ $s['n'] }}</span>
                            <div class="bf__card-body">
                                <h3 class="bf__card-title">{{ $s['t'] }}</h3>
                                <p class="bf__card-desc">{{ $s['d'] }}</p>
                            </div>
                            <span class="bf__card-node"></span>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Benefits flow end -->

    <section class="cn">
        <a href="{{ Auth::check() ? route('custom_test') : route('signup') }}" class="cn__btn">Crack NEET 2027</a>
    </section>

    <!-- Paper proof -->
    <section class="pp">
        <div class="pp__inner">
            <div class="pp__left">
                <span class="pp__eyebrow"><i></i>Real overlap &middot; Paper proof</span>
                <h2 class="pp__title">Questions from <span class="pp__grad">NEET&nbsp;2026</span> you already solved here</h2>
                <p class="pp__sub">See how what you practised on RankPro lines up with the actual paper - tap a subject to open the proof PDF.</p>

                <div class="pp__subjects">
                    @php
                        $proof = [
                            ['s'=>'Physics','d'=>'Numericals & conceptual frames you already drilled.','pdf'=>'#'],
                            ['s'=>'Chemistry','d'=>'Organic, inorganic & physical hits from your streaks.','pdf'=>'#'],
                            ['s'=>'Biology','d'=>'Diagram- and NCERT-tight repeats straight from your bank.','pdf'=>'#'],
                        ];
                    @endphp
                    @foreach($proof as $i => $p)
                        <a href="{{ $p['pdf'] }}" target="_blank" rel="noopener" class="pp__row">
                            <span class="pp__row-ico">
                                @if($i===0)<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/><ellipse cx="12" cy="12" rx="10" ry="4" stroke="currentColor" stroke-width="1.8"/><ellipse cx="12" cy="12" rx="10" ry="4" stroke="currentColor" stroke-width="1.8" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4" stroke="currentColor" stroke-width="1.8" transform="rotate(120 12 12)"/></svg>
                                @elseif($i===1)<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M9 3h6M10 3v6l-4 8a2 2 0 0 0 2 3h8a2 2 0 0 0 2-3l-4-8V3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @else<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 4c6 2 6 8 0 10M20 4c-6 2-6 8 0 10M4 4c4 6 12 6 16 0M4 20c4-6 12-6 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>@endif
                            </span>
                            <div class="pp__row-txt">
                                <strong>{{ $p['s'] }}</strong>
                                <span>{{ $p['d'] }}</span>
                            </div>
                            <span class="pp__row-cta">Open proof PDF
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H8M17 7v9" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pp__right" id="ppRight">
                <div class="pp__viewport">
                    <div class="pp__slides" id="ppSlides">
                        @php
                            $proofs = [
                                ['sub'=>'Physics','match'=>'98%','p'=>'A body is projected with velocity u at angle &theta;. Find the maximum height reached.','e'=>'A body is projected with velocity u at an angle &theta; with the horizontal. The maximum height reached is:'],
                                ['sub'=>'Chemistry','match'=>'96%','p'=>'Arrange the given species in order of increasing stability.','e'=>'The correct order of stability of the given species is:'],
                                ['sub'=>'Biology','match'=>'99%','p'=>'Correct sequence of events during sexual reproduction in flowering plants.','e'=>'The correct sequence of events during sexual reproduction in flowering plants is:'],
                                ['sub'=>'Physics','match'=>'95%','p'=>'In an ideal transformer, turns ratio Np/Ns = 1/2. Find Vs : Vp.','e'=>'In an ideal transformer, the turns ratio is Np/Ns = 1/2. The ratio Vs : Vp is equal to:'],
                            ];
                        @endphp
                        @foreach($proofs as $pr)
                            <div class="pp__proof">
                                <div class="pp__proof-head">
                                    <span class="pp__proof-tag">NEET 2026 &middot; {{ $pr['sub'] }}</span>
                                    <span class="pp__proof-match"><i></i>{{ $pr['match'] }} match</span>
                                </div>
                                <div class="pp__proof-pair">
                                    <div class="pp__q pp__q--practised">
                                        <span class="pp__q-label">You practised on RankPro</span>
                                        <p class="pp__q-text">{{ $pr['p'] }}</p>
                                    </div>
                                    <div class="pp__link-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M9 12h6M12 9v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/></svg>
                                    </div>
                                    <div class="pp__q pp__q--exam">
                                        <span class="pp__q-label">Appeared in NEET 2026</span>
                                        <p class="pp__q-text">{{ $pr['e'] }}</p>
                                        <span class="pp__q-verified">&#10003; Verified from question bank</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="pp__dots" id="ppDots"></div>
            </div>
        </div>
    </section>
    <!-- Paper proof end -->

   <!-- Rankers top mate (spotlight marquee) -->
    <section class="rk">
        <div class="rk__glow rk__glow--a"></div>
        <div class="rk__glow rk__glow--b"></div>
        <div class="rk__inner">
            <div class="rk__head">
                <span class="rk__eyebrow"><i></i>Real results &middot; Real rankers</span>
                <h2 class="rk__title">Aspirants who trained on RankPro<br><span class="rk__grad">and cracked NEET</span></h2>
                <p class="rk__sub">Every rank below is a student who practised here.</p>
            </div>
        </div>

        <div class="rk__marquee" id="rkMarquee">
            <div class="rk__track" id="rkTrack">
                @if($ranker_list->isNotEmpty())
                    @for($pass = 0; $pass < 3; $pass++)
                        @foreach($ranker_list as $index => $ranker)
                            <article class="rk__card">
                                <div class="rk__card-photo" style="background-image:url('{{ asset('uploads/ranker/' . ($ranker->icon ?? 'default.png')) }}');"></div>
                                <div class="rk__card-wash"></div>
                                <div class="rk__card-top">
                                    <span class="rk__card-airlbl">AIR</span>
                                    <span class="rk__card-air">{{ $ranker->air }}</span>
                                </div>
                                <div class="rk__card-body">
                                    <h5 class="rk__card-name">{{ $ranker->name }}</h5>
                                    <div class="rk__card-stats">
                                        <span><b>{{ $ranker->score }}</b>Score</span>
                                        <span><b>{{ $ranker->year }}</b>Year</span>
                                    </div>
                                    <span class="rk__card-college">{{ $ranker->college }}</span>
                                    @php
                                        $rankerQuote = optional($ranker->feedbacks->first())->text
                                            ?: ($ranker->about ?: $ranker->description);
                                        if (!$rankerQuote && isset($success_story) && $success_story->isNotEmpty()) {
                                            $story = $success_story[$index % $success_story->count()];
                                            $rankerQuote = optional($story->descriptions->first())->text;
                                        }
                                        $rankerQuote = trim(preg_replace('/\s+/', ' ', strip_tags((string) $rankerQuote)));
                                    @endphp
                                    @if($rankerQuote !== '')
                                        <blockquote class="rk__card-quote">{{ \Illuminate\Support\Str::limit($rankerQuote, 110) }}</blockquote>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    @endfor
                @endif
            </div>
        </div>

    </section>
    <!-- Rankers top mate ends -->


    <!--<div class="advisorycommittee rankers-carousel wow fadeInUp" data-wow-delay="0.1s">-->
    <!--    <div class="cusContainer">-->
    <!--        <h2 class="mainheading"> Doctor-Led Advisory Committee </h2>-->
    <!--        <div class="realinfo">-->
    <!--            Experts share their insights to craft Shikkha's NEET Prep-->
    <!--            curriculum-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="slider__items">-->
    <!--        <div class="advisoryblk pink">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('web/images/advisory05.png') }}" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk blue">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('web/images/advisory01.png') }}" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk orange">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('web/images/advisory02.png') }}" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk green">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('web/images/advisory03.png') }}" height="210" width="245" alt="" class="img-fluid">-->
    <!--            </div>-->
    <!--            <h4>Dr A K Bardhan</h4>-->
    <!--            <div class="department">Cardiology | 30 years exp.</div>-->
    <!--            <div class="languageknown">English, Bengali, Hindi</div>-->
    <!--            <div class="degree">MBBS (1971), MD (1979), Dip. Card. (1976), FCCP</div>-->
    <!--            <a href="#">Connect</a>-->
    <!--        </div>-->
    <!--        <div class="advisoryblk blue">-->
    <!--            <div class="docimg">-->
    <!--                <img src="{{ asset('web/images/advisory04.png') }}" height="210" width="245" alt="" class="img-fluid">-->
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


    <!--<div class="cusContainer havequeries wow fadeInUp" data-wow-delay="0.1s">-->
    <!--    <div class="havequerieblock">-->
    <!--        <div class="queryleft">-->
    <!--            <div class="joinqury">-->
    <!--                <h3><span>Have Doubts?</span> We're Just a Message Away!</h3>-->
    <!--                <span class="qstext">Let us help you to make NEET prep easier!</span><br>-->
    <!--                <a href="#" class="joinquerybtn">Enquiry Now</a>-->
    <!--                <a href="#" class="roundcall">Talk to us</a>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="queryform">-->
    <!--            <h3>Your First Step to NEET Success - </h3>-->
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

    <!-- @if ($head_quaters->isNotEmpty())
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
    @endif -->


    <!--<div class="cusContainer hurry">-->
    <!--    <div class="row">-->
    <!--        <div class="col-lg-6 d-flex align-items-end">-->
    <!--            <h3>-->
    <!--                <span>Don't Wait,</span> Start Your Test Series and <span>Track Your Growth</span>-->
    <!--            </h3>-->
    <!--            <img src="{{ asset('web/images/icons/hurry.png') }}" width="148" height="160" alt="" class="hurryimg">-->
    <!--        </div>-->
    <!--        <div class="col-lg-6">-->
    <!--            <div class="hurryblock">-->
    <!--                <p>Get personalized growth insights with every test. Don't wait - start now and see how quickly you-->
    <!--                    can improve.</p>-->
    <!--                <a href="#">Register Now</a>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- leftfixt2 -->

   <!-- FAQ (redesigned) -->
    <section class="fq">
        <div class="fq__inner">
            <div class="fq__head">
                <span class="fq__eyebrow"><i></i>FAQ</span>
                <h2 class="fq__title">Frequently asked <span class="fq__grad">questions</span></h2>
                <p class="fq__sub">Everything you need to know about RankPro - tests, payments, and plans.</p>
            </div>

            @if ($asked_questien->isNotEmpty())
                @php
                    $onlineEducation = $asked_questien->filter(fn($q) => $q->type == 'Online Education');
                    $paymentMethod = $asked_questien->filter(fn($q) => $q->type == 'Payment Method');
                    $pricingPlan = $asked_questien->filter(fn($q) => $q->type == 'Pricing Plan');
                @endphp

                <div class="fq__tabs" id="fqTabs">
                    <button class="fq__tab is-active" data-target="fqPanel1">Online Education</button>
                    <button class="fq__tab" data-target="fqPanel2">Payment Method</button>
                    <button class="fq__tab" data-target="fqPanel3">Pricing Plan</button>
                </div>

                <div class="fq__panels">
                    {{-- Online Education --}}
                    <div class="fq__panel is-active" id="fqPanel1">
                        <div class="accordion" id="accordionOnlineEducation">
                            @foreach ($onlineEducation as $key => $question)
                                @php $cId = "collapseOE".ucfirst(num2word($key+1)); $hId = "headingOE".ucfirst(num2word($key+1)); @endphp
                                <div class="fq__item">
                                    <h4 class="fq__q" id="{{ $hId }}">
                                        <button class="fq__q-btn {{ $key==0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $cId }}" aria-expanded="{{ $key==0?'true':'false' }}" aria-controls="{{ $cId }}">
                                            <span>{!! strip_tags($question->text, '<strong><em><u>') !!}</span>
                                            <span class="fq__icon"></span>
                                        </button>
                                    </h4>
                                    <div id="{{ $cId }}" class="accordion-collapse collapse {{ $key==0?'show':'' }}" aria-labelledby="{{ $hId }}" data-bs-parent="#accordionOnlineEducation">
                                        <div class="fq__a">{!! preg_replace('/<div class="raw-html-embed">|<\/div>/', '', $question->text2) !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="fq__panel" id="fqPanel2">
                        <div class="accordion" id="accordionPaymentMethod">
                            @foreach ($paymentMethod as $key => $question)
                                @php $cId = "collapsePM".ucfirst(num2word($key+1)); $hId = "headingPM".ucfirst(num2word($key+1)); @endphp
                                <div class="fq__item">
                                    <h4 class="fq__q" id="{{ $hId }}">
                                        <button class="fq__q-btn {{ $key==0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $cId }}" aria-expanded="{{ $key==0?'true':'false' }}" aria-controls="{{ $cId }}">
                                            <span>{!! strip_tags($question->text, '<strong><em><u>') !!}</span>
                                            <span class="fq__icon"></span>
                                        </button>
                                    </h4>
                                    <div id="{{ $cId }}" class="accordion-collapse collapse {{ $key==0?'show':'' }}" aria-labelledby="{{ $hId }}" data-bs-parent="#accordionPaymentMethod">
                                        <div class="fq__a">{!! preg_replace('/<div class="raw-html-embed">|<\/div>/', '', $question->text2) !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pricing Plan --}}
                    <div class="fq__panel" id="fqPanel3">
                        <div class="accordion" id="accordionPricingPlan">
                            @foreach ($pricingPlan as $key => $question)
                                @php $cId = "collapsePP".ucfirst(num2word($key+1)); $hId = "headingPP".ucfirst(num2word($key+1)); @endphp
                                <div class="fq__item">
                                    <h4 class="fq__q" id="{{ $hId }}">
                                        <button class="fq__q-btn {{ $key==0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $cId }}" aria-expanded="{{ $key==0?'true':'false' }}" aria-controls="{{ $cId }}">
                                            <span>{!! strip_tags($question->text, '<strong><em><u>') !!}</span>
                                            <span class="fq__icon"></span>
                                        </button>
                                    </h4>
                                    <div id="{{ $cId }}" class="accordion-collapse collapse {{ $key==0?'show':'' }}" aria-labelledby="{{ $hId }}" data-bs-parent="#accordionPricingPlan">
                                        <div class="fq__a">{!! preg_replace('/<div class="raw-html-embed">|<\/div>/', '', $question->text2) !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- FAQ end -->


    @if($as_mention->isNotEmpty())
    <!-- As mentioned in (redesigned) -->
    <section class="mn">
        <div class="mn__inner">
            <span class="mn__label">As mentioned in</span>
            <div class="mn__logos">
                @foreach($as_mention as $mention)
                    <div class="mn__logo">
                        <img src="{{ asset('uploads/mention/' . $mention->image) }}" alt="Featured in" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- As mentioned end -->
    @endif


    @include('site.include.footer')
    @include('site.include.back_to_top')

   <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('web/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('web/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('web/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('web/js/main.js') }}"></script>
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

        // cursor-reactive dashboard parallax + magnetic buttons
            (function(){
                var tilt = document.getElementById("rhTilt");
                var dash = tilt ? tilt.querySelector(".rh__dash") : null;
                if(tilt && dash){
                    var layers = tilt.querySelectorAll("[data-depth]");
                    tilt.addEventListener("mousemove", function(e){
                        var r = tilt.getBoundingClientRect();
                        var px = (e.clientX - r.left)/r.width - .5;
                        var py = (e.clientY - r.top)/r.height - .5;
                        gsap.to(dash, {rotateY: px*8, rotateX: -py*8, duration:.6, ease:"power2.out"});
                        layers.forEach(function(l){
                            var d = parseFloat(l.dataset.depth)||10;
                            gsap.to(l, {x: px*d, y: py*d, duration:.6, ease:"power2.out"});
                        });
                    });
                    tilt.addEventListener("mouseleave", function(){
                        gsap.to(dash, {rotateY:0, rotateX:0, duration:.8, ease:"power3.out"});
                        layers.forEach(function(l){ gsap.to(l,{x:0,y:0,duration:.8,ease:"power3.out"}); });
                    });
                }
                function magnetic(el){
                    if(!el) return;
                    el.addEventListener("mousemove", function(e){
                        var r = el.getBoundingClientRect();
                        gsap.to(el,{x:(e.clientX-r.left-r.width/2)*.3, y:(e.clientY-r.top-r.height/2)*.4, duration:.4, ease:"power2.out"});
                    });
                    el.addEventListener("mouseleave", function(){ gsap.to(el,{x:0,y:0,duration:.5,ease:"elastic.out(1,.4)"}); });
                }
                magnetic(document.getElementById("rhStart"));
            })();
    </script>

<!-- Hero animation -->
    <script>
        if (window.gsap) {
            gsap.registerPlugin(ScrollTrigger);

            // hero entrance
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });
            tl.from(".rh__eyebrow", { y: 16, opacity: 0, duration: 0.6 })
              .from(".rh__title", { y: 28, opacity: 0, duration: 0.8 }, "-=0.3")
              .from(".rh__lead", { y: 20, opacity: 0, duration: 0.6 }, "-=0.4")
              .from(".rh__cta", { y: 20, opacity: 0, duration: 0.6 }, "-=0.4")
              .from(".rh__lowerrow", { y: 20, opacity: 0, duration: 0.6 }, "-=0.4")
              .from(".rh__test", { x: 40, opacity: 0, duration: 0.9 }, "-=0.7")
              .from(".rh__badge-float", { scale: 0.8, opacity: 0, duration: 0.5, stagger: 0.12 }, "-=0.4");

            // cursor-reactive tilt + magnetic button + subject toggle
            (function () {
                var tilt = document.getElementById("rhTilt");
                var card = document.getElementById("rhTestCard");
                if (tilt && card) {
                    var layers = tilt.querySelectorAll("[data-depth]");
                    tilt.addEventListener("mousemove", function (e) {
                        var r = tilt.getBoundingClientRect();
                        var px = (e.clientX - r.left) / r.width - 0.5;
                        var py = (e.clientY - r.top) / r.height - 0.5;
                        gsap.to(card, { rotateY: px * 7, rotateX: -py * 7, duration: 0.6, ease: "power2.out" });
                        layers.forEach(function (l) {
                            var d = parseFloat(l.dataset.depth) || 10;
                            gsap.to(l, { x: px * d, y: py * d, duration: 0.6, ease: "power2.out" });
                        });
                    });
                    tilt.addEventListener("mouseleave", function () {
                        gsap.to(card, { rotateY: 0, rotateX: 0, duration: 0.8, ease: "power3.out" });
                        layers.forEach(function (l) { gsap.to(l, { x: 0, y: 0, duration: 0.8, ease: "power3.out" }); });
                    });
                }

                var start = document.getElementById("rhStart");
                if (start) {
                    start.addEventListener("mousemove", function (e) {
                        var r = start.getBoundingClientRect();
                        gsap.to(start, { x: (e.clientX - r.left - r.width / 2) * 0.15, y: (e.clientY - r.top - r.height / 2) * 0.3, duration: 0.4, ease: "power2.out" });
                    });
                    start.addEventListener("mouseleave", function () {
                        gsap.to(start, { x: 0, y: 0, duration: 0.5, ease: "elastic.out(1,.4)" });
                    });
                }

                var subs = document.getElementById("rhSubjects");
                if (subs) {
                    subs.addEventListener("click", function (e) {
                        var b = e.target.closest(".rh__subj"); if (!b) return;
                        subs.querySelectorAll(".rh__subj").forEach(function (x) { x.classList.remove("is-active"); });
                        b.classList.add("is-active");
                        var name = b.textContent.trim();
                        document.getElementById("rhTestTitle").textContent = name + " - Full Chapter Test";
                        document.getElementById("rhQ").textContent = b.dataset.q;
                        document.getElementById("rhMin").textContent = b.dataset.min;
                        var chaps = b.dataset.ch.split(" &middot; ");
                        document.getElementById("rhChaps").innerHTML = chaps.map(function (c) { return '<span class="rh__chap">' + c + '</span>'; }).join("");
                        gsap.fromTo("#rhTestTitle, #rhTestCard .rh__test-meta, #rhChaps", { opacity: 0.3, y: 6 }, { opacity: 1, y: 0, duration: 0.4, ease: "power2.out", stagger: 0.05 });
                    });
                }
            })();
        }
    </script>


    <!-- Rankers marquee -->
    <script>
        window.addEventListener("load", function () {
            var track = document.getElementById("rpxTrack");
            var mask = document.getElementById("rpxMask");
            if (!track || !mask) { console.error("MARQUEE: elements missing"); return; }
            if (typeof gsap === "undefined" || typeof Draggable === "undefined") { console.error("MARQUEE: gsap/Draggable missing"); return; }

            gsap.registerPlugin(Draggable, InertiaPlugin);

            var half = track.scrollWidth / 2;
            var x = 0, paused = false, dragging = false, speed = 60;
            console.log("MARQUEE: half =", half, "children =", track.children.length);
            if (half <= 0) { console.error("MARQUEE: half is 0 - cards not sized"); return; }

            function wrap(v){ if(v <= -half) v += half; if(v > 0) v -= half; return v; }

            gsap.ticker.add(function(t, dt){
                if (paused || dragging) return;
                x = wrap(x - speed * dt / 1000);
                gsap.set(track, { x: x });
            });

            mask.addEventListener("mouseenter", function(){ paused = true; });
            mask.addEventListener("mouseleave", function(){ if(!dragging) paused = false; });

            Draggable.create(track, {
                type: "x", inertia: true, dragClickables: false,
                onPress: function(){ dragging = true; },
                onDrag: function(){ x = wrap(this.x); gsap.set(track, { x: x }); },
                onThrowUpdate: function(){ x = wrap(gsap.getProperty(track, "x")); gsap.set(track, { x: x }); },
                onRelease: function(){ if(!this.isThrowing){ dragging = false; paused = false; } },
                onThrowComplete: function(){ dragging = false; paused = false; }
            });

            window.addEventListener("resize", function(){ half = track.scrollWidth / 2; });
            console.log("MARQUEE: initialized");
        });


        // ===== NEET countdown =====
            (function(){
                var root = document.getElementById("neetTimer");
                if(!root) return;
                var target = new Date(root.getAttribute("data-target")).getTime();
                var daysEl = document.getElementById("ntDays");
                var hoursEl = document.getElementById("ntHours");
                var minsEl = document.getElementById("ntMins");
                var secsEl = document.getElementById("ntSecs");
                function pad(n){ return String(Math.max(0, n)).padStart(2, "0"); }
                function tick(){
                    var diff = Math.max(0, target - Date.now());
                    var days = Math.floor(diff / 86400000);
                    var hours = Math.floor((diff % 86400000) / 3600000);
                    var mins = Math.floor((diff % 3600000) / 60000);
                    var secs = Math.floor((diff % 60000) / 1000);
                    if(daysEl) daysEl.textContent = pad(days);
                    if(hoursEl) hoursEl.textContent = pad(hours);
                    if(minsEl) minsEl.textContent = pad(mins);
                    if(secsEl) secsEl.textContent = pad(secs);
                }
                tick();
                setInterval(tick, 1000);
            })();

            // ===== How it works =====
            (function(){
                var video = document.getElementById("hwVideo");
                var headline = document.getElementById("hwHeadline");
                var play = document.getElementById("hwPlay");
                if(!video || !headline || !window.gsap) return;
                gsap.registerPlugin(ScrollTrigger);

                // split headline into per-character spans
                var text = headline.textContent;
                headline.textContent = "";
                var chars = [];
                text.split("").forEach(function(ch){
                    var s = document.createElement("span");
                    s.className = "hw__char";
                    s.textContent = ch === " " ? "\u00A0" : ch;
                    headline.appendChild(s);
                    chars.push(s);
                });

                var tl = gsap.timeline({ scrollTrigger:{ trigger:".hw", start:"top 68%" }, defaults:{ ease:"power4.out" } });
                tl.from(".hw__eyebrow", { y:20, opacity:0, duration:.5 })
                  .from(chars, { yPercent:120, opacity:0, duration:.7, stagger:.02 }, "-=.2")
                  .from(".hw__sub", { y:20, opacity:0, duration:.6 }, "-=.3")
                  .from(".hw__point", { y:16, opacity:0, duration:.5, stagger:.1 }, "-=.3")
                  .fromTo(video,
                        { clipPath:"inset(45% 0% 45% 0% round 26px)", opacity:0 },
                        { clipPath:"inset(0% 0% 0% 0% round 26px)", opacity:1, duration:1, ease:"power4.inOut" }, "-=.2")
                  .from(play, { scale:0, opacity:0, duration:.5, ease:"back.out(1.8)" }, "-=.4");

                if(window.matchMedia("(hover:hover)").matches){
                    video.addEventListener("mousemove", function(e){
                        var r=video.getBoundingClientRect();
                        var px=(e.clientX-r.left)/r.width-.5, py=(e.clientY-r.top)/r.height-.5;
                        gsap.to(video,{ rotateY:px*5, rotateX:-py*5, duration:.6, ease:"power2.out" });
                        gsap.to(play,{ x:px*36, y:py*36, duration:.5, ease:"power2.out" });
                    });
                    video.addEventListener("mouseleave", function(){
                        gsap.to(video,{ rotateY:0, rotateX:0, duration:.8, ease:"power3.out" });
                        gsap.to(play,{ x:0, y:0, duration:.6, ease:"elastic.out(1,.4)" });
                    });
                }
            })();

            // ===== Paper proof (auto slider) =====
            (function(){
                var slides = document.getElementById("ppSlides");
                var dotsWrap = document.getElementById("ppDots");
                if(!slides || !dotsWrap) return;
                var total = slides.children.length;
                if(!total) return;

                var idx = 0, timer;
                // build dots
                for(var i=0;i<total;i++){
                    var d = document.createElement("button");
                    d.className = "pp__dot" + (i===0?" is-active":"");
                    d.setAttribute("aria-label","Slide "+(i+1));
                    (function(n){ d.addEventListener("click", function(){ go(n); reset(); }); })(i);
                    dotsWrap.appendChild(d);
                }
                var dots = dotsWrap.querySelectorAll(".pp__dot");

                function go(n){
                    idx = (n+total)%total;
                    slides.style.transform = "translateX(-"+(idx*100)+"%)";
                    dots.forEach(function(x,j){ x.classList.toggle("is-active", j===idx); });
                }
                function next(){ go(idx+1); }
                function start(){ timer = setInterval(next, 3500); }
                function reset(){ clearInterval(timer); start(); }

                // pause on hover
                var right = document.getElementById("ppRight");
                if(right){
                    right.addEventListener("mouseenter", function(){ clearInterval(timer); });
                    right.addEventListener("mouseleave", start);
                }
                start();

                // scroll reveal
                if(window.gsap && window.ScrollTrigger){
                    gsap.registerPlugin(ScrollTrigger);
                    var tl = gsap.timeline({ scrollTrigger:{ trigger:".pp", start:"top 68%" }, defaults:{ ease:"power3.out" } });
                    tl.from(".pp__eyebrow, .pp__title, .pp__sub", { y:26, opacity:0, duration:.6, stagger:.1 })
                      .from(".pp__row", { x:-30, opacity:0, duration:.6, stagger:.12 }, "-=.2")
                      .from(".pp__viewport", { y:50, opacity:0, scale:.96, duration:.9 }, "-=.6")
                      .from(".pp__dots", { opacity:0, duration:.4 }, "-=.3");
                }
            })();

            // ===== Rankers spotlight marquee =====
            (function(){
                var track = document.getElementById("rkTrack");
                var marquee = document.getElementById("rkMarquee");
                if(!track || !marquee || !track.children.length || !window.gsap) return;

                var half = track.scrollWidth / 3;   // 3 passes &rarr; one third is one loop
                var x = 0, paused = false, speed = 45;
                function remeasure(){ half = track.scrollWidth / 3; }
                window.addEventListener("load", remeasure);
                setTimeout(remeasure, 600);
                window.addEventListener("resize", remeasure);

                gsap.ticker.add(function(t, dt){
                    if(paused || half <= 0) return;
                    x -= (speed * dt) / 1000;
                    if(x <= -half) x += half;
                    gsap.set(track, { x: x });
                });
                marquee.addEventListener("mouseenter", function(){ paused = true; });
                marquee.addEventListener("mouseleave", function(){ paused = false; });

                // entrance
                if(window.ScrollTrigger){
                    gsap.registerPlugin(ScrollTrigger);
                    gsap.from(".rk__head > *", {
                        scrollTrigger:{ trigger:".rk", start:"top 72%" },
                        y:26, opacity:0, duration:.6, stagger:.1, ease:"power3.out"
                    });
                }
            })();

            // ===== Pricing reveal =====
            if (window.gsap && window.ScrollTrigger) {
                gsap.registerPlugin(ScrollTrigger);
                gsap.from(".pr__head > *", {
                    scrollTrigger:{ trigger:".pr", start:"top 76%" },
                    y:26, opacity:0, duration:.6, stagger:.1, ease:"power3.out"
                });
                gsap.from(".pr__card", {
                    scrollTrigger:{ trigger:".pr__grid", start:"top 82%" },
                    y:50, opacity:0, duration:.7, stagger:.1, ease:"power3.out"
                });
            }

            // ===== Benefits flow (draw line on scroll) =====
            (function(){
                var flow = document.getElementById("bfFlow");
                var path = document.getElementById("bfPath");
                var dot = document.getElementById("bfDot");
                if(!flow || !path || !window.gsap || !window.ScrollTrigger) return;
                gsap.registerPlugin(ScrollTrigger);

                // inject gradient def for the line
                var svg = flow.querySelector(".bf__line");
                var ns = "http://www.w3.org/2000/svg";
                var defs = document.createElementNS(ns,"defs");
                defs.innerHTML = '<linearGradient id="bfGrad" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#345ff4"/><stop offset="1" stop-color="#f20b81"/></linearGradient>';
                svg.insertBefore(defs, svg.firstChild);

                var len = path.getTotalLength();
                gsap.set(path, { strokeDasharray: len, strokeDashoffset: len });

                // draw the line as the section scrolls through
                gsap.to(path, {
                    strokeDashoffset: 0,
                    ease: "none",
                    scrollTrigger: { trigger: flow, start: "top 70%", end: "bottom 80%", scrub: 0.6 }
                });

                // travel the dot along the path
                var dotObj = { p: 0 };
                gsap.to(dotObj, {
                    p: 1, ease: "none",
                    scrollTrigger: { trigger: flow, start: "top 70%", end: "bottom 80%", scrub: 0.6 },
                    onUpdate: function(){
                        var pt = path.getPointAtLength(dotObj.p * len);
                        dot.setAttribute("cx", pt.x); dot.setAttribute("cy", pt.y);
                    }
                });

                // reveal cards in sequence
                gsap.utils.toArray(".bf__card").forEach(function(card){
                    gsap.from(card, {
                        scrollTrigger: { trigger: card, start: "top 82%" },
                        y: 40, opacity: 0, duration: .7, ease: "power3.out"
                    });
                });
                gsap.from(".bf__head > *", {
                    scrollTrigger:{ trigger:".bf", start:"top 78%" },
                    y:24, opacity:0, duration:.6, stagger:.1, ease:"power3.out"
                });
            })();

            // ===== Upcoming test reveal =====
            if (window.gsap && window.ScrollTrigger) {
                gsap.registerPlugin(ScrollTrigger);
                gsap.from(".ut__head > *", {
                    scrollTrigger:{ trigger:".ut", start:"top 76%" },
                    y:24, opacity:0, duration:.6, stagger:.1, ease:"power3.out"
                });
                gsap.from(".ut__card", {
                    scrollTrigger:{ trigger:".ut__grid", start:"top 82%" },
                    y:44, opacity:0, duration:.7, stagger:.1, ease:"power3.out"
                });
            }

            // ===== Testimonials marquee =====
            (function(){
                var track = document.getElementById("tsTrack");
                var marquee = document.getElementById("tsMarquee");
                if(!track || !marquee || !track.children.length || !window.gsap) return;

                var half = track.scrollWidth / 2;   // 2 passes &rarr; half is one loop
                var x = 0, paused = false, speed = 40;
                function remeasure(){ half = track.scrollWidth / 2; }
                window.addEventListener("load", remeasure);
                setTimeout(remeasure, 600);
                window.addEventListener("resize", remeasure);

                gsap.ticker.add(function(t, dt){
                    if(paused || half <= 0) return;
                    x -= (speed * dt) / 1000;
                    if(x <= -half) x += half;
                    gsap.set(track, { x: x });
                });
                marquee.addEventListener("mouseenter", function(){ paused = true; });
                marquee.addEventListener("mouseleave", function(){ paused = false; });

                if(window.ScrollTrigger){
                    gsap.registerPlugin(ScrollTrigger);
                    gsap.from(".ts__head > *", {
                        scrollTrigger:{ trigger:".ts", start:"top 74%" },
                        y:24, opacity:0, duration:.6, stagger:.1, ease:"power3.out"
                    });
                }
            })();

            // ===== FAQ tabs =====
            (function(){
                var tabs = document.getElementById("fqTabs");
                if(!tabs) return;
                var btns = tabs.querySelectorAll(".fq__tab");
                var panels = document.querySelectorAll(".fq__panel");
                tabs.addEventListener("click", function(e){
                    var b = e.target.closest(".fq__tab"); if(!b) return;
                    btns.forEach(function(x){ x.classList.remove("is-active"); });
                    panels.forEach(function(p){ p.classList.remove("is-active"); });
                    b.classList.add("is-active");
                    var target = document.getElementById(b.dataset.target);
                    if(target) target.classList.add("is-active");
                });
                if(window.gsap && window.ScrollTrigger){
                    gsap.from(".fq__head > *", { scrollTrigger:{ trigger:".fq", start:"top 78%" }, y:24, opacity:0, duration:.6, stagger:.1, ease:"power3.out" });
                }
            })();

            // ===== Gallery + mentions reveal =====
            if (window.gsap && window.ScrollTrigger) {
                gsap.registerPlugin(ScrollTrigger);
                gsap.from(".gl__head > *", { scrollTrigger:{ trigger:".gl", start:"top 78%" }, y:24, opacity:0, duration:.6, stagger:.1, ease:"power3.out" });
                gsap.from(".gl__item", { scrollTrigger:{ trigger:".gl__grid", start:"top 82%" }, y:40, opacity:0, duration:.7, stagger:.06, ease:"power3.out" });
                gsap.from(".mn__logo", { scrollTrigger:{ trigger:".mn", start:"top 85%" }, y:20, opacity:0, duration:.5, stagger:.08, ease:"power3.out" });
            }

            // ===== CTA reveal + magnetic button =====
            (function(){
                if(!window.gsap) return;
                if(window.ScrollTrigger){
                    gsap.registerPlugin(ScrollTrigger);
                    gsap.from(".cta__content > *", {
                        scrollTrigger:{ trigger:".cta", start:"top 80%" },
                        y:26, opacity:0, duration:.6, stagger:.08, ease:"power3.out"
                    });
                }
                var btn = document.getElementById("ctaBtn");
                if(btn && window.matchMedia("(hover:hover)").matches){
                    btn.addEventListener("mousemove", function(e){
                        var r=btn.getBoundingClientRect();
                        gsap.to(btn,{ x:(e.clientX-(r.left+r.width/2))*.2, y:(e.clientY-(r.top+r.height/2))*.3, duration:.4, ease:"power2.out" });
                    });
                    btn.addEventListener("mouseleave", function(){ gsap.to(btn,{ x:0, y:0, duration:.5, ease:"elastic.out(1,.4)" }); });
                }
            })();
    </script>
</body>

</html>
