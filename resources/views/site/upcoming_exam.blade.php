<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta name="title" content="NEET AI Platform â€“ Unlimited Free Mock Tests, Test Series & Find Your Mentor">
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

    <!-- bxslider -->
    <link rel="stylesheet" href="{{ asset('') }}web/css/jquery.bxslider.css">

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/dashboard.css" rel="stylesheet">
    
    <style>
        .calltoaction {
            display: none;
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

    <!-- dashboard -->
    <section id="dashboard">
      <div class="container-fluid">
          <div class="dashboardAll dashboardPh">
              <div class="menuBarBtn menuBarBtnOpen">
                  <i class="fas fa-bars"></i>
              </div>
              
              <div class="dashboardLeft dashboardLeftOff">
                  @include('site.include.student_left_menu')
              </div>
              <div class="dashboardRight">
                  <div class="dashboardRightBody">
                      <div class="row">
                          <div class="col-xl-12">
                              <div class="dashboardBlock">
                                    <div class="dashboardTitleCount">
                                        <div class="">
                                          <div class="dashboardTitle">{{ $user->first_name }} {{ strtoupper(substr($user->last_name, 0, 1)) }}, (<span>{{ $user->rankpro_id }}</span>)</div>
                                          <div class="dashboardEmail">{{$user->email_id}}</div>
                                        </div>
                                    </div>
                              </div>
                          </div>
                          <div class="col-12">
                              <div class="dashboardBlock dashboardBlockShadow">
                                  <ul class="nav nav-tabs examTab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('upcoming_exam') }}?subject_id={{$subject_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('upcoming_exam') }}?subject_id={{$subject_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('upcoming_exam') }}?subject_id={{$subject_id}}&exam_type=">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('upcoming_exam') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock pb-0">
                                <div class="textTitle_viewAllText">
                                    <div class="dashboardTitle dashboardTitle3">Upcoming <span>Exam</span></div>
                                </div>
                                <div class="tab-pane fade show active">
                                    @if(count($upcoming_exam_list) > 0)
                                        @foreach($upcoming_exam_list as $value)
                                            @if($value->type == 2)
                                                <div class="upcomingExamRow_offline">
                                                    <div class="row upcomingExamRow">
                                                        <div class="col-md-9 upcomingExamCol">
                                                            <div class="upcomingExam-card">
                                                                <!-- Top badges -->
                                                                <div class="d-flex justify-content-between">
                                                                    <span class="badge-soft">Scholarship</span>
                                                                    <span class="badge-soft badge-soft2">Offline</span>
                                                                </div>
                                                        
                                                                <!-- Content -->
                                                                <div class="upcomingExamDetails">
                                                                    <div class="row">
                                                                        <div class="col-sm-8 mb-0">
                                                                            <div class="title">{{$value->name}}</div>
                                                                            <div class="subtitle">{{$value->subject_names}}</div>
                                                                            <div class="datetime">
                                                                                {{\Carbon\Carbon::parse($value->exam_date)->format('jS F, Y')}} &nbsp; | &nbsp; {{\Carbon\Carbon::parse($value->exam_time)->format('g:ia')}}
                                                                            </div>
                                                                        </div>
                                                            
                                                                        <div class="col-sm-4 right-info mb-0">
                                                                            <div>Marks: <span>{{$value->totals_marks_for_exam}}</span></div>
                                                                            <div class="location">{{$value->location_name}}</div>
                                                                            <div class="duration">{{$value->total_time_for_exam}} hours</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        
                                                                <!-- Action Bar -->
                                                                <div class="action-bar d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        @if($value->exam_status === NULL)
                                                                            <a href="{{route('upcoming_exam.accept',['id'=>$value->id])}}" class="btn btn-accept me-2" onclick="return confirm('Do you realy want to accept exam?');">Accept</a>
                                                                            <a href="{{route('upcoming_exam.reject',['id'=>$value->id])}}" class="btn btn-reject" onclick="return confirm('Do you realy want to reject exam?');">Reject</a>
                                                                        @elseif($value->exam_status === 1)
                                                                            <a class="btn btn-accept me-2">Accept</a>
                                                                        @else
                                                                            <a class="btn btn-reject ">Reject</a>
                                                                        @endif
                                                                    </div>
                                                                    <button class="btn btn-test">Take Test Now</button>
                                                                </div>
                                                        
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 upcomingExamCol">
                                                            <div class="upcomingExam-card upcomingExam-card-countdown">
                                                                <div class="neet-countdown-card">
                                                                    <div class="neet-countdown-title">
                                                                        Time Remaining for <span>NEET Exam</span>
                                                                    </div>
                                                                
                                                                    <div class="neet-countdown-progress-bar">
                                                                        <div class="neet-countdown-progress" id="progressNeet"></div>
                                                                    </div>
                                                                
                                                                    <div class="neet-countdown-time-boxes">
                                                                        <div class="neet-countdown-box">
                                                                            <div class="neet-countdown-time" id="daysNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Days</div>
                                                                        </div>
                                                                
                                                                        <div class="neet-countdown-colon">:</div>
                                                                
                                                                        <div class="neet-countdown-box">
                                                                            <div class="neet-countdown-time" id="hoursNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Hours</div>
                                                                        </div>
                                                                
                                                                        <div class="neet-countdown-colon">:</div>
                                                                
                                                                        <div class="neet-countdown-box">
                                                                            <div class="neet-countdown-time" id="minutesNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Minutes</div>
                                                                        </div>
                                                                
                                                                        <div class="neet-countdown-colon d-none">:</div>
                                                                
                                                                        <div class="neet-countdown-box d-none">
                                                                            <div class="neet-countdown-time" id="secondsNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Seconds</div>
                                                                        </div>
                                                                    </div>
                                                                  </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="upcomingExamRow_online">
                                                    <div class="row upcomingExamRow">
                                                        <div class="col-md-9 upcomingExamCol">
                                                            <div class="upcomingExam-card">
                                                                <!-- Top badges -->
                                                                <div class="d-flex justify-content-between">
                                                                    <span class="badge-soft">Scholarship</span>
                                                                    <span class="badge-soft badge-soft2">Online</span>
                                                                </div>
                                                        
                                                                <!-- Content -->
                                                                <div class="upcomingExamDetails">
                                                                    <div class="row">
                                                                        <div class="col-sm-8 mb-0">
                                                                            <div class="title">{{$value->name}}</div>
                                                                            <div class="subtitle">Human Embryology</div>
                                                                            <div class="datetime">
                                                                                {{\Carbon\Carbon::parse($value->exam_date)->format('jS F, Y')}} &nbsp; | &nbsp; {{\Carbon\Carbon::parse($value->exam_time)->format('g:ia')}}
                                                                            </div>
                                                                        </div>
                                                            
                                                                        <div class="col-sm-4 right-info mb-0">
                                                                            <div>Marks: <span>{{$value->totals_marks_for_exam}}</span></div>
                                                                            <div class="location">{{$value->location_name}}</div>
                                                                            <div class="duration">{{$value->total_time_for_exam}} hours</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        
                                                                <!-- Action Bar -->
                                                                <div class="action-bar d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        @if($value->exam_status === NULL)
                                                                            <a href="{{route('upcoming_exam.accept',['id'=>$value->id])}}" class="btn btn-accept me-2" onclick="return confirm('Do you realy want to accept exam?');">Accept</a>
                                                                            <a href="{{route('upcoming_exam.reject',['id'=>$value->id])}}" class="btn btn-reject" onclick="return confirm('Do you realy want to reject exam?');">Reject</a>
                                                                        @elseif($value->exam_status === 1)
                                                                            <a class="btn btn-accept me-2">Accept</a>
                                                                        @else
                                                                            <a class="btn btn-reject ">Reject</a>
                                                                        @endif
                                                                    </div>
                                                                    <a href="{{ route('start_exam',['id'=>$value->id]) }}" class="btn btn-test" onclick="return confirm('{{ !empty($value->is_proctored) ? 'This exam is proctored. Camera and fullscreen are required. Do you want to start?' : 'Do you want to start exam' }}');">Take Test Now</a>
                                                                </div>
                                                        
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 upcomingExamCol">
                                                            <div class="upcomingExam-card upcomingExam-card-countdown">
                                                                <div class="neet-countdown-card">
                                                                    <div class="neet-countdown-title">
                                                                        Time Remaining for <span>NEET Exam</span>
                                                                    </div>
                                                                
                                                                    <div class="neet-countdown-progress-bar">
                                                                        <div class="neet-countdown-progress" id="progressNeet_{{$value->id}}"></div>
                                                                    </div>
                                                                
                                                                    <div class="neet-countdown-time-boxes">
                                                                        <div class="neet-countdown-box">
                                                                            <div class="neet-countdown-time" id="daysNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Days</div>
                                                                        </div>
                                                                
                                                                        <div class="neet-countdown-colon">:</div>
                                                                
                                                                        <div class="neet-countdown-box">
                                                                            <div class="neet-countdown-time" id="hoursNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Hours</div>
                                                                        </div>
                                                                
                                                                        <div class="neet-countdown-colon">:</div>
                                                                
                                                                        <div class="neet-countdown-box">
                                                                            <div class="neet-countdown-time" id="minutesNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Minutes</div>
                                                                        </div>
                                                                
                                                                        <div class="neet-countdown-colon d-none">:</div>
                                                                
                                                                        <div class="neet-countdown-box d-none">
                                                                            <div class="neet-countdown-time" id="secondsNeet_{{$value->id}}">00</div>
                                                                            <div class="neet-countdown-label">Seconds</div>
                                                                        </div>
                                                                    </div>
                                                                  </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else

                                    @endif
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </section>
    <!-- dashboard end -->


    
    @include('site.include.call_to_action')
    @include('site.include.how_to_use')


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
    <script src="{{ asset('') }}web/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('') }}web/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('') }}web/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <script src="{{ asset('') }}web/js/jquery.bxslider.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>

    <script>
      $(document).ready(function(){
        $(".menuBarBtn.menuBarBtnOpen").click(function(){
            $(".dashboardLeft").removeClass("dashboardLeftOff");
        });
        $(".menuBarBtn.menuBarBtnClose").click(function(){
            $(".dashboardLeft").addClass("dashboardLeftOff");
        });
      });
    
    </script>

    @if(count($upcoming_exam_list) > 0)
        @foreach($upcoming_exam_list as $value)

        <script>
            (function() {

                const examDate = new Date("{{$value->exam_date}}T{{$value->exam_time}}").getTime();
                const startDate = new Date("{{$value->created_at}}").getTime();
                const totalDuration = examDate - startDate;

                function updateCountdown() {

                    const now = new Date().getTime();
                    const distance = examDate - now;

                    if (distance <= 0) {
                        document.getElementById("daysNeet_{{$value->id}}").innerText = "00";
                        document.getElementById("hoursNeet_{{$value->id}}").innerText = "00";
                        document.getElementById("minutesNeet_{{$value->id}}").innerText = "00";
                        document.getElementById("secondsNeet_{{$value->id}}").innerText = "00";
                        document.getElementById("progressNeet_{{$value->id}}").style.width = "100%";
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("daysNeet_{{$value->id}}").innerText = days;
                    document.getElementById("hoursNeet_{{$value->id}}").innerText = String(hours).padStart(2, "0");
                    document.getElementById("minutesNeet_{{$value->id}}").innerText = String(minutes).padStart(2, "0");
                    document.getElementById("secondsNeet_{{$value->id}}").innerText = String(seconds).padStart(2, "0");

                    // Progress bar
                    const elapsed = now - startDate;
                    const progressPercent = Math.min((elapsed / totalDuration) * 100, 100);

                    document.getElementById("progressNeet_{{$value->id}}").style.width = progressPercent + "%";
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);

            })();
        </script>

        @endforeach
    @endif

</body>

</html>