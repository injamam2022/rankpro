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
                                          <div class="dashboardTitle">Charlotte B, (<span>75184690</span>)</div>
                                          <div class="dashboardEmail">bloomcharlotte123@gmail.com</div>
                                        </div>
                                    </div>
                              </div>
                          </div>
                          <div class="col-12">
                              <div class="dashboardBlock dashboardBlockShadow">
                                  <ul class="nav nav-tabs examTab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link active" data-bs-toggle="tab" href="#onlineExam">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" data-bs-toggle="tab" href="#offlineExam">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" data-bs-toggle="tab" href="#overall">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    <li class="nav-item">
                                      <a class="nav-link active" href="#">Physics</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" href="#">Chemistry</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" href="#">Biology</a>
                                    </li>
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock">
                                <div class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">Weakness</div>
                                    </div>
                                    <div class="row strengthRow">
                                        <div class="col-0 strengthCol">
                                          <div class="result-card chapter-card-all">
                                        
                                            <div class="result-card-title">Chapters</div>
                                        
                                            <div class="table-wrapper chapter-card">
                                              <ul class="list-group list-group-flush pt-0">
                                                <li class="list-group-item">
                                                  <div class="num">1.</div>
                                                  <div class="text">Mechanics</div>
                                                </li>
                                        
                                                <li class="list-group-item active-item">
                                                  <div class="num">2.</div>
                                                  <div class="text">Physical and Thermal Propertiesof Bulk Matter</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">3.</div>
                                                  <div class="text">Heat and Thermodynamics</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">4.</div>
                                                  <div class="text">Oscillations and Waves</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">5.</div>
                                                  <div class="text">Electricity and Magnetism</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">6.</div>
                                                  <div class="text">Optics</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">7.</div>
                                                  <div class="text">Modern Physics</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">8.</div>
                                                  <div class="text">Practical Physics</div>
                                                </li>
                                              </ul>
                                            </div>
                                        
                                          </div>
                                        </div>
                                        
                                        <div class="col-0 strengthCol">
                                          <div class="result-card chapter-card-all">
                                        
                                            <div class="result-card-title">Topics</div>
                                        
                                            <div class="table-wrapper chapter-card topics-card">
                                              <ul class="list-group list-group-flush pt-0">
                                                <li class="list-group-item">
                                                  <div class="num">2.1.</div>
                                                  <div class="text">Physics and Measurement</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.2.</div>
                                                  <div class="text">Ginematics</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.3.</div>
                                                  <div class="text">Laws of Motion</div>
                                                </li>
                                        
                                                <li class="list-group-item active-item">
                                                  <div class="num">2.4.</div>
                                                  <div class="text">Work, Energy and Power</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.5.</div>
                                                  <div class="text">Rotational Motion</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.6.</div>
                                                  <div class="text">Gravitation</div>
                                                </li>
                                              </ul>
                                            </div>
                                        
                                          </div>
                                        </div>
                                        
                                        <div class="col-0 strengthCol">
                                          <div class="result-card chapter-card-all">
                                        
                                            <div class="result-card-title">Subtopics</div>
                                        
                                            <div class="table-wrapper chapter-card subtopics-card">
                                              <ul class="list-group list-group-flush pt-0">
                                                <li class="list-group-item">
                                                  <div class="num">2.4.1.</div>
                                                  <div class="text">Force and Inertia</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.4.2.</div>
                                                  <div class="text">Momentum</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.4.3.</div>
                                                  <div class="text">Newton's Laws of Motion</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.4.4.</div>
                                                  <div class="text">Equilibrium of Forces</div>
                                                </li>
                                        
                                                <li class="list-group-item active-item">
                                                  <div class="num">2.4.5.</div>
                                                  <div class="text">Inertial and Non Inertial Frame of Reference</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.4.6.</div>
                                                  <div class="text">Impulse</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.4.7.</div>
                                                  <div class="text">Friction</div>
                                                </li>
                                        
                                                <li class="list-group-item">
                                                  <div class="num">2.4.8.</div>
                                                  <div class="text">Dynamics of Uniform</div>
                                                </li>
                                              </ul>
                                            </div>
                                        
                                          </div>
                                        </div>
                                        
                                        <div class="col-0 strengthCol">
                                          <div class="result-card chapter-card-all">
                                        
                                            <div class="result-card-title">&nbsp;</div>
                                        
                                            <div class="table-wrapper chapter-card question-cardAll">
                                                <a href="#" class="question-card">
                                                    <div class="question-img">
                                                      <img src="https://loremflickr.com/200/200/physics" class="img-fluid" alt="">
                                                    </div>
                                                
                                                    <div class="question-content">
                                                      <div class="question-title">
                                                        Q1. Force and Inertia It has roots in a piece of classical Latin literature from you.
                                                      </div>
                                                      <div class="question-meta">
                                                        <span class="timespan">56 sec.</span>
                                                        <span class="difficulty">Easy</span>
                                                      </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="question-card">
                                                    <div class="question-img">
                                                      <img src="https://loremflickr.com/200/200/technology" class="img-fluid" alt="">
                                                    </div>
                                                
                                                    <div class="question-content">
                                                      <div class="question-title">
                                                        Q1. Force and Inertia It has roots in a piece of classical Latin literature from you.
                                                      </div>
                                                      <div class="question-meta">
                                                        <span class="timespan">56 sec.</span>
                                                        <span class="difficulty">Hard</span>
                                                      </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="question-card">
                                                    <div class="question-img">
                                                      <img src="https://picsum.photos/200/200" class="img-fluid" alt="">
                                                    </div>
                                                
                                                    <div class="question-content">
                                                      <div class="question-title">
                                                        Q1. Force and Inertia It has roots in a piece of classical Latin literature from you.
                                                      </div>
                                                      <div class="question-meta">
                                                        <span class="timespan">56 sec.</span>
                                                        <span class="difficulty">Medum</span>
                                                      </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="question-card">
                                                    <div class="question-img">
                                                      <img src="https://loremflickr.com/200/200/science" class="img-fluid" alt="">
                                                    </div>
                                                
                                                    <div class="question-content">
                                                      <div class="question-title">
                                                        Q1. Force and Inertia It has roots in a piece of classical Latin literature from you.
                                                      </div>
                                                      <div class="question-meta">
                                                        <span class="timespan">56 sec.</span>
                                                        <span class="difficulty">Hard</span>
                                                      </div>
                                                    </div>
                                                </a>
                                            </div>
                                        
                                          </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="answer-summary answer-summary2">
                                                <div>
                                                    <div class="answer-stats">
                                                      <span>Physics--> Work, Energy and Power--> Inertial and Non Inertial Frame of Reference</span>
                                                    </div>
                                                    <div class="answer-title">
                                                      <span class="fw-semibold" style="color: #3561ff;">16</span> Answers out of <span class="fw-semibold" style="color: #f20b81;">20</span> Questions
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <ul class="nav nav-tabs examTab examTabSub ansTab">
                                                <li class="nav-item">
                                                  <a class="nav-link" href="#">Answer Type</a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link active" href="#">Question Type</a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link" href="#">Question Level</a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link" href="#">Mistake Monitar</a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link" href="#">PDF Download</a>
                                                </li>
                                            </ul>
                                      </div>
                                        
                                    </div>
                                    <div class="row resultCardRow" style="margin-bottom: -26px;">
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Answer Type Format</div>
                                            
                                                <div class="table-wrapper topper-chart">
                                                  <img src="{{ asset('') }}web/images/ansChart1.png" class="img-fluid topperPieImg" alt="">
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Question Type</div>
                                            
                                                <div class="table-wrapper topper-chart">
                                                  <img src="{{ asset('') }}web/images/ansChart2.png" class="img-fluid topperPieImg" alt="">
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Question Level</div>
                                            
                                                <div class="table-wrapper topper-chart">
                                                  <img src="{{ asset('') }}web/images/ansChart3.png" class="img-fluid topperPieImg" alt="">
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