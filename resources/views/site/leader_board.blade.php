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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('leader_board') }}?subject_id={{$subject_id}}&exam_type=1&exam_types={{$exam_types}}">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('leader_board') }}?subject_id={{$subject_id}}&exam_type=2&exam_types={{$exam_types}}">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('leader_board') }}?subject_id={{$subject_id}}&exam_type=&exam_types={{$exam_types}}">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('leader_board') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}&exam_types={{$exam_types}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock pb-0">
                                <div class="textTitle_viewAllText">
                                    <div class="dashboardTitle dashboardTitle3">Leader Board: <span>{{$user_name}}</span></div>
                                    
                                    <div class="top-bar-ScoreExam scoreExam_drop">
                                        <!-- Dropdown -->
                                        <div class="dropdown" onclick="toggleDropdown()">
                                            <div class="dropdown-label">
                                                <span>Exams</span>
                                                <span class="arrow"></span>
                                            </div>
                                            
                                            <div class="dropdown-menu" id="dropdownMenu">
                                                <a href="{{ route('leader_board') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_types=1">
                                                    <div class="@if($exam_types == 1) active @endif">RNS</div>
                                                </a>
                                                <a href="{{ route('leader_board') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_types=2">
                                                    <div class="@if($exam_types == 2) active @endif">RPS</div>
                                                </a>
                                                <a href="{{ route('leader_board') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_types=3">
                                                    <div class="@if($exam_types == 3) active @endif">SNT</div>
                                                </a>
                                                <a href="{{ route('leader_board') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_types=4">
                                                    <div class="@if($exam_types == 4) active @endif">OTS</div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show active">
                                      <div class="table-responsive exam-table leaderBoard-table">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Name</th>
                                                    <th>Overall Score</th>
                                                    <th>Overall Percentile</th>
                                                    @foreach($subject_list as $value)
                                                        <th>{{$value->name}}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                
                                            <tbody>
                                                @foreach($exam_list as $key => $value)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$value->first_name}} {{$value->last_name}}</td>
                                                        <td>{{(int) (($value->total_result))}}</td>
                                                        <td>
                                                            @if($value->total_mark)
                                                                {{number_format(($value->total_result/$value->total_mark*100),2)}}
                                                            @endif
                                                        </td>
                                                        @foreach($subject_list as $value1)
                                                            <td>{{$value->subject_list[$value1->id]}}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
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