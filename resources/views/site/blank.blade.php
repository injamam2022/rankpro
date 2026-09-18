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
                              <div class="tab-content dashboardBlock pb-0">
                                <div id="onlineExam" class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">Online Examination <span>Physics</span></div>
                                    </div>
                                      <div class="table-responsive exam-table">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Exam Id</th>
                                                    <th>Exam</th>
                                                    <th>Topics</th>
                                                    <th>Rank</th>
                                                    <th>Score</th>
                                                    <th>OMR</th>
                                                </tr>
                                            </thead>
                                
                                            <tbody>
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Doppler Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Photoelectric Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Electromagnetic induction</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon omr-icon-gray"></div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Doppler Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Photoelectric Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Electromagnetic induction</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon omr-icon-gray"></div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Doppler Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Photoelectric Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Electromagnetic induction</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon omr-icon-gray"></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                
                                </div>
                                <div id="offlineExam" class="tab-pane fade">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">Offline Examination <span>Physics</span></div>
                                    </div>
                                      <div class="table-responsive exam-table">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Exam Id</th>
                                                    <th>Exam</th>
                                                    <th>Topics</th>
                                                    <th>Rank</th>
                                                    <th>Score</th>
                                                    <th>OMR</th>
                                                </tr>
                                            </thead>
                                
                                            <tbody>
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Doppler Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Photoelectric Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Electromagnetic induction</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon omr-icon-gray"></div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Doppler Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Photoelectric Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Electromagnetic induction</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon omr-icon-gray"></div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Photoelectric Effect</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon"></div>
                                                    </td>
                                                </tr>
                                
                                                <tr>
                                                    <td>12 Nov 2025</td>
                                                    <td>RP45781-245</td>
                                                    <td>Score Booster Test (Science)</td>
                                                    <td>Electromagnetic induction</td>
                                                    <td>2002</td>
                                                    <td>51 / 257</td>
                                                    <td>
                                                        <div class="omr-icon omr-icon-gray"></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
        $(".menuBarBtn.menuBarBtnOpen").click(function(){
            $(".dashboardLeft").removeClass("dashboardLeftOff");
        });
        $(".menuBarBtn.menuBarBtnClose").click(function(){
            $(".dashboardLeft").addClass("dashboardLeftOff");
        });
      });
    </script>
</body>

</html>