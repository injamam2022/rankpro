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
                          @php
                            $user_name = "";
    
                            if(session()->get('parant_login_type') == "P"){
                                $user_name = $user->father_full_name;
                            }else{
                                $user_name = $user->first_name." ".$user->last_name;
                            }
                          @endphp
                          <div class="col-xl-12">
                              <div class="dashboardBlock">
                                    <div class="dashboardTitleCount">
                                        <div class="">
                                          <div class="dashboardTitle">{{ $user->first_name }} {{ strtoupper(substr($user->last_name, 0, 1)) }}, (<span>75184690</span>)</div>
                                          <div class="dashboardEmail">{{$user->email_id}}</div>
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
                                    <div class="dashboardAirBlockAll">
                                        <div class="dashboardAirBlock">
                                            <div class="textTitle_viewAllText">
                                                <div class="dashboardTitle dashboardTitle3">AIR Report</div>
                                                
                                                <div class="top-bar-ScoreExam scoreExam_drop">
                                                    <!-- Dropdown -->
                                                    <div class="dropdown" onclick="toggleDropdown()">
                                                        <div class="dropdown-label">
                                                            <span>Exam Year</span>
                                                            <span class="arrow"></span>
                                                        </div>
                                                        
                                                        <div class="dropdown-menu" id="dropdownMenu">
                                                            <div>2020</div>
                                                            <div class="active">2021</div>
                                                            <div>2022</div>
                                                            <div>2023</div>
                                                            <div>2024</div>
                                                            <div>2025</div>
                                                            <div>2026</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="air-card air-rank-card">
                                                <div class="rank-title">
                                                    National<br>
                                                    Ranking & Score
                                                </div>
                                                <div class="rank-year">2025</div>
                                            
                                                <div class="rank-number">1</div>
                                            
                                                <div class="rank-label">All India Rank</div>
                                            
                                                <div class="rank-info">
                                                    Your Rank 01<br>
                                                    Your Score 796
                                                    <div class="rank-name">Charlotte B</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dashboardAirBlock">
                                            <div class="air-card air-score-card">

                                                <div class="score-title">Score Overview</div>
                                                
                                                <div class="section-title">Topper Vs Average Vs My Score</div>
                                                <!-- Highest -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        Highest Score
                                                        <span>850</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-pink" style="width:90%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- Average -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        Average Score
                                                        <span>830</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-blue" style="width:85%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- My -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        My Score
                                                        <span>550</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-gray" style="width:60%"></div>
                                                    </div>
                                                </div>
                                            
                                                <div class="section-title">My Score</div>
                                            
                                                <!-- My Highest -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        My Highest Score
                                                        <span>850</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-pink" style="width:90%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- My Average -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        My Average Score
                                                        <span>830</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-blue" style="width:85%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- My Lowest -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        My Lowest Score
                                                        <span>550</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-gray" style="width:60%"></div>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="dashboardAirBlock">
                                            <div class="air-card air-score-card mastery-card">

                                                <div class="score-title">Subject Mastery</div>
                                            
                                                <!-- Physics -->
                                                <div class="section-title">Physics</div>
                                            
                                                <div class="scale-row">
                                                    <div class="scale">100</div>
                                                    <div class="progress"><div class="progress-bar bar-blue" style="width:75%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">75</div>
                                                    <div class="progress"><div class="progress-bar bar-blue" style="width:45%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">50</div>
                                                    <div class="progress"><div class="progress-bar bar-blue" style="width:80%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">25</div>
                                                    <div class="progress"><div class="progress-bar bar-blue" style="width:55%"></div></div>
                                                </div>
                                            
                                                <div class="axis">
                                                    <span>0</span><span>10</span><span>20</span><span>30</span><span>40</span>
                                                    <span>50</span><span>60</span><span>70</span><span>80</span>
                                                </div>
                                            
                                                <!-- Chemistry -->
                                                <div class="section-title">Chemistry</div>
                                            
                                                <div class="scale-row">
                                                    <div class="scale">100</div>
                                                    <div class="progress"><div class="progress-bar bar-pink" style="width:70%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">75</div>
                                                    <div class="progress"><div class="progress-bar bar-pink" style="width:45%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">50</div>
                                                    <div class="progress"><div class="progress-bar bar-pink" style="width:85%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">25</div>
                                                    <div class="progress"><div class="progress-bar bar-pink" style="width:55%"></div></div>
                                                </div>
                                            
                                                <div class="axis">
                                                    <span>0</span><span>10</span><span>20</span><span>30</span><span>40</span>
                                                    <span>50</span><span>60</span><span>70</span><span>80</span>
                                                </div>
                                            
                                                <!-- Biology -->
                                                <div class="section-title">Biology</div>
                                            
                                                <div class="scale-row">
                                                    <div class="scale">100</div>
                                                    <div class="progress"><div class="progress-bar bar-gray" style="width:70%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">75</div>
                                                    <div class="progress"><div class="progress-bar bar-gray" style="width:45%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">50</div>
                                                    <div class="progress"><div class="progress-bar bar-gray" style="width:80%"></div></div>
                                                </div>
                                                <div class="scale-row">
                                                    <div class="scale">25</div>
                                                    <div class="progress"><div class="progress-bar bar-gray" style="width:55%"></div></div>
                                                </div>
                                            
                                                <div class="axis">
                                                    <span>0</span><span>10</span><span>20</span><span>30</span><span>40</span>
                                                    <span>50</span><span>60</span><span>70</span><span>80</span>
                                                </div>
                                                
                                                <div class="mastery-circle">
                                                    <img  src="{{ asset('') }}web/images/mastery-circle.png" alt="" class="img-fluid" />
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
      
      function toggleDropdown() {
            const menu = document.getElementById("dropdownMenu");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }
    
        // Close dropdown when clicking outside
        document.addEventListener("click", function(e) {
            if (!e.target.closest(".dropdown")) {
                document.getElementById("dropdownMenu").style.display = "none";
            }
        });
    </script>
</body>

</html>