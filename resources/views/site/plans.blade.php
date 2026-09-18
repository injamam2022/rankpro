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
              <div class="dashboardLeft">
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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('plans') }}?subject_id={{$subject_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('plans') }}?subject_id={{$subject_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('plans') }}?subject_id={{$subject_id}}&exam_type=">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('plans') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock dashboardBlockShadow pb-0 overflow-hidden">
                                <div class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">Choose Your <span>Learning Journey</span> <span class="dashboardTitleMute" style="color: #000;">(Unlock to complete <span style="color: #f20b81;">NEET UG</span> experience)</span></div>
                                    </div>
                                    <div class="plan-details">
                                      <div class="row plan-table">
                                    
                                        <!-- Benefits -->
                                        <div class="col-md-3 plan-col benefits">
                                          <div class="plan-head">Benefits</div>
                                          <div class="plan-row">India's best educators</div>
                                          <div class="plan-row">Interactive live classes</div>
                                          <div class="plan-row">Structured courses & PDFs</div>
                                          <div class="plan-row">Live tests & quizzes</div>
                                          <div class="plan-row">Free access to UAITs online</div>
                                          <div class="plan-row">Access to curated test series</div>
                                          <div class="plan-row">Digital notes & study material</div>
                                          <div class="plan-row">Physical notes</div>
                                          <div class="plan-row">1:1 Live mentorship</div>
                                          <div class="plan-row">Access to classes conducted at Unacademy Centres</div>
                                        </div>
                                    
                                        <!-- Plus -->
                                        <div class="col-md-3 plan-col plus">
                                          <div class="plan-head blue">Plus</div>
                                          <div class="plan-row">Most Popular</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row price"><span>Rs.1,299</span>/month (billed annually)</div>
                                          <div class="plan-row">
                                            <button class="btn btn-primary rounded-pill">Subscribe Now</button>
                                          </div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                        </div>
                                    
                                        <!-- Iconic -->
                                        <div class="col-md-3 plan-col iconic">
                                          <div class="plan-head pink">Iconic</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row price"><span>Rs.1,999</span>/month (billed annually)</div>
                                          <div class="plan-row">
                                            <button class="btn btn-pink rounded-pill">Go Iconic</button>
                                          </div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                        </div>
                                    
                                        <!-- Centre -->
                                        <div class="col-md-3 plan-col centre">
                                          <div class="plan-head gray">Centre</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row price"><span>Rs.13,698</span>/year</div>
                                          <div class="plan-row">
                                            <button class="btn btn-secondary rounded-pill">Enroll Now</button>
                                          </div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                          <div class="plan-row">--</div>
                                        </div>
                                    
                                      </div>
                                    </div>
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
        
      });
    </script>
</body>

</html>