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
        /*.dashboardTopicTitle12 {*/
        /*    line-height: 20px;*/
        /*    height: 40px;*/
        /*    display: -webkit-box;*/
        /*    -webkit-line-clamp: 2;*/
        /*    -webkit-box-orient: vertical;*/
        /*    overflow: hidden;*/
        /*    text-overflow: ellipsis;*/
        /*    word-wrap: anywhere;*/
        /*}*/
        
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
                  <div class="dashboardRightBody dashboardRightBodyColAdj">
                      <div class="row">
                          @php
                            $user_name = "";
    
                            if(session()->get('parant_login_type') == "P"){
                                $user_name = $user->father_full_name;
                            }else{
                                $user_name = $user->first_name." ".$user->last_name;
                            }
                          @endphp
                          <div class="col-md-6">
                              <div class="dashboardBlock">
                                    <div class="dashboardTitleCount">
                                        <div class="">
                                          <div class="dashboardTitle">{{ $user->first_name }} {{ strtoupper(substr($user->last_name, 0, 1)) }}, (<span>{{ $user->rankpro_id }}</span>)</div>
                                          <div class="dashboardEmail">{{$user->email_id}}</div>
                                          <!--<div class="dashboardText">{!!$dashboard_content->board!!}</div>-->
                                        </div>
                                      
                                      <div class="neet-countdown-card">

                                        @if(count($upcoming_list))
                                            <div class="neet-countdown-title">
                                                Time Remaining for <span>NEET Exam</span>
                                            </div>
                                        
                                            <div class="neet-countdown-progress-bar">
                                                <div class="neet-countdown-progress" id="progressNeet"></div>
                                            </div>
                                        
                                            <div class="neet-countdown-time-boxes">
                                                <div class="neet-countdown-box">
                                                    <div class="neet-countdown-time" id="daysNeet">00</div>
                                                    <div class="neet-countdown-label">Days</div>
                                                </div>
                                        
                                                <div class="neet-countdown-colon">:</div>
                                        
                                                <div class="neet-countdown-box">
                                                    <div class="neet-countdown-time" id="hoursNeet">00</div>
                                                    <div class="neet-countdown-label">Hours</div>
                                                </div>
                                        
                                                <div class="neet-countdown-colon">:</div>
                                        
                                                <div class="neet-countdown-box">
                                                    <div class="neet-countdown-time" id="minutesNeet">00</div>
                                                    <div class="neet-countdown-label">Minutes</div>
                                                </div>
                                        
                                                <div class="neet-countdown-colon d-none">:</div>
                                        
                                                <div class="neet-countdown-box d-none">
                                                    <div class="neet-countdown-time" id="secondsNeet">00</div>
                                                    <div class="neet-countdown-label">Seconds</div>
                                                </div>
                                            </div>
                                        @endif
                                      </div>
                                    </div>
                                  <div class="dashboardBar"></div>
                                  <div class="pointBoardTitle">Point Leader Board</div>
                                  <div class="dashboardText">"Cooking up some magical reward experience"</div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="dashboardBlock leaderBoard">
                                <div class="dashboardTitleScoreExamAll">
                                    <div class="dashboardTitle dashboardTitle2">
                                        <span>Rank Pro Community</span> 
                                        @if($exam_user_type) 
                                            (<label id="leader_board_header">{{$exam_user_type}}</label>)
                                        @endif
                                    </div>
                                  <div class="top-bar-ScoreExam">

                                    <!-- Toggle -->
                                    <div class="toggle-group">
                                        <span>Your Score</span>
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                
                                    <!-- Dropdown -->
                                    <div class="dropdown" onclick="toggleDropdown()">
                                        <div class="dropdown-label">
                                            <span>Exams</span>
                                            <span class="arrow"></span>
                                        </div>
                                
                                        <div class="dropdown-menu" id="dropdownMenu">
                                            <div id="leader_board_rns" onclick="clickLeaderBoard('RNS');" class=" @if($exam_user_type=='RNS') active @endif">
                                                <a href="{{ route('dashboard') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_user_type=RNS">RNS</a>
                                            </div>
                                            <div id="leader_board_rps" onclick="clickLeaderBoard('RPS');" class=" @if($exam_user_type=='RPS') active @endif">
                                                <a href="{{ route('dashboard') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_user_type=RPS">RPS</a>
                                            </div>
                                            <div id="leader_board_snt" onclick="clickLeaderBoard('SNT');" class=" @if($exam_user_type=='SNT') active @endif">
                                                <a href="{{ route('dashboard') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_user_type=SNT">SNT</a>
                                            </div>
                                            <div id="leader_board_ots" onclick="clickLeaderBoard('OTS');" class=" @if($exam_user_type=='OTS') active @endif">
                                                <a href="{{ route('dashboard') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&exam_user_type=OTS">OTS</a>
                                            </div>
                                        </div>
                                    </div>
                                
                                  </div>
                                </div>  
                                  
                                  <!--<div class="dashboardText">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>-->
                                  <div class="dashboardText">{!!$dashboard_content->leader_board!!}</div>
                                  
                                    <div class="yourScoreOff">
                                      <div class="table-container rankTable">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>ID</th>
                                                    <th>Marks</th>
                                                    <th>Percent</th>
                                                    <th>Rank</th>
                                                </tr>
                                            </thead>
                                    
                                            <tbody id="student_list">
                                                @foreach($leader_board as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <div class="name-cell">
                                                                @if($value->profile_img)
                                                                    <img src="{{asset('')}}uploads/profileImage/{{$value->profile_img}}" class="avatar" >
                                                                @else
                                                                    <img src="https://randomuser.me/api/portraits/men/44.jpg" class="avatar">
                                                                @endif
                                                                <span class="name">{{$value->first_name}} {{$value->last_name}}</span>
                                                            </div>
                                                        </td> 
                                                        <td>{{$value->rankpro_id}}</td>
                                                        <td>{{$value->total_number}}/{{$value->total_mark}}</td>
                                                        <td>
                                                            @if($value->total_mark)
                                                                {{number_format(($value->total_number/$value->total_mark*100),2)}}%
                                                            @endif
                                                        </td>
                                                        <td class="rank">
                                                            @if($key == 0)
                                                                1st
                                                            @else
                                                                2nd
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                      </div>
                                      <a href="{{ route('leader_board') }}" class="viewStudents">View All Students</a>
                                    </div>
                                    
                                    <div class="yourScoreOn yourScoreShow">
                                      <div class="table-container rankTable yourScoreTable">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>ID</th>
                                                    <th>Marks</th>
                                                    <th>Percent</th>
                                                    <th>Rank</th>
                                                </tr>
                                            </thead>
                                    
                                            <tbody>
                                                @if($my_leader_board)
                                                    <tr>
                                                        <td>
                                                            <div class="name-cell">
                                                                @if($my_leader_board->profile_img)
                                                                    <img src="{{asset('')}}uploads/profileImage/{{$my_leader_board->profile_img}}" class="avatar" >
                                                                @else
                                                                    <img src="https://randomuser.me/api/portraits/men/44.jpg" class="avatar">
                                                                @endif
                                                                <span class="name">{{$user_name}}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{$my_leader_board->rankpro_id}}</td>
                                                        <td>
                                                            {{$my_leader_board->total_number}}/{{$my_leader_board->total_mark}} 
                                                        </td>
                                                        <td>
                                                            @if($my_leader_board->total_mark)
                                                                {{number_format(($my_leader_board->total_number/$my_leader_board->total_mark*100),2)}}%
                                                            @endif
                                                        </td>
                                                        <td class="rank">{{ $your_score }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                      </div>
                                      <div class="py-2 text-center">
                                          <div class="row">
                                            @foreach($subject_list as $value)
                                                  <div class="col-sm-4">
                                                      <div class="row">
                                                          <div class="col-sm-6">
                                                              <div style="width: 100%;height: 80px;">
                                                                  <canvas id="comparison_{{$value->id}}"></canvas>
                                                              </div>
                                                          </div>
                                                          <div class="col-sm-6">
                                                              <div>{{$value->name}}</div>
                                                              <div>{{$value->count}}</div>
                                                          </div>
                                                      </div>
                                                  </div>
                                            @endforeach
                                          </div>
                                      </div>
                                    </div>

                              </div>
                          </div>
                          
                          <div class="col-12">
                              <div class="dashboardBlock dashboardBlockShadow">
                                  <ul class="nav nav-tabs examTab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('dashboard') }}?subject_id={{$subject_id}}&exam_type=1&exam_user_type={{$exam_user_type}}">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('dashboard') }}?subject_id={{$subject_id}}&exam_type=2&exam_user_type={{$exam_user_type}}">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('dashboard') }}?subject_id={{$subject_id}}&exam_type=&exam_user_type={{$exam_user_type}}">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('dashboard') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}&exam_user_type={{$exam_user_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                        <li class="nav-item mt-2">
                                            <a class="nav-link  @if($subject_id=='') active @endif" href="{{ route('dashboard') }}?subject_id=&exam_type={{$exam_type}}&exam_user_type={{$exam_user_type}}">Combined</a>
                                        </li>
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 dashboardBannerOn">
                              <div class="dashboardBlock dashboardBanner">
                                  <img src="{{ asset('') }}web/images/dashboardBanner_new.jpeg" class="img-fluid dashboardBannerImg" alt="">
                                  <img src="{{ asset('') }}web/images/bannerClose_ic.png" class="img-fluid dashboardBannerClose" alt="">
                              </div>
                          </div>
                          <div class="col-xl-4">
                              <div class="tab-content dashboardBlock examBlock">
                                <div class="textTitle_viewAllText">
                                    <div class="dashboardTitle dashboardTitle3">Test <span>Given</span></div>
                                    <div class="viewAllText">
                                        <a href="{{ route('exam_given') }}">View All</a>
                                    </div>
                                </div>
                                <div id="onlineExam" class="tab-pane fade show active">
                                  @if(count($exam_list))
                                    @foreach($exam_list as $value)
                                      <div class="examSchedule">
                                          <div class="examSchedule-l-col">
                                              <ul class="list-unstyled mb-0 examScheduleUl">
                                                <li>{{$value->name}}</li>
                                                <li>{{\Carbon\Carbon::parse($value->exam_date)->format('d M Y')}}</li>
                                              </ul>
                                          </div>
                                          <div class="examSchedule-r-col">
                                              <ul class="list-unstyled mb-0 examScheduleUl">
                                                <a href="{{route('exam_result_detail',['id'=>$value->user_exam_id])}}">
                                                    <li class="notReleasedDate">Result</li>
                                                </a>
                                              </ul>
                                          </div>
                                      </div>
                                    @endforeach
                                  @else
                                    <div class="examSchedule">
                                          <div class="examSchedule-l-col w-100">
                                              <ul class="list-unstyled mb-0 examScheduleUl">
                                                <li class="ndf">No data found</li>
                                              </ul>
                                          </div>
                                      </div>
                                  @endif
                                </div>
                              </div>
                          </div>
                          <div class="col-xl-4">
                              <!--<div class="tabHeightAdj"></div>-->
                              <div class="dashboardBlock examBlock">
                                <div class="textTitle_viewAllText">
                                    <div class="dashboardTitle dashboardTitle3">Upcoming <span>Test</span></div>
                                    <div class="viewAllText" style="color: #3561ff;font-size: 12px;font-weight: 400;cursor: pointer;">
                                        <a href="{{ route('upcoming_exam') }}">View All</a>
                                    </div>
                                </div>
                                @if(count($upcoming_list))
                                  @foreach($upcoming_list as $value)
                                    <div class="examSchedule upcomingSchedule onlineSchedule">
                                        <div class="examSchedule-l-col">
                                            <ul class="list-unstyled mb-0 examScheduleUl">
                                                <li>{{$value->name}}</li>
                                                <li>{{\Carbon\Carbon::parse($value->exam_date)->format('d M Y')}}</li>
                                            </ul>
                                        </div>
                                        <div class="examSchedule-r-col">
                                            <ul class="list-unstyled mb-0 examScheduleUl">
                                                <li>{{$value->location_name}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                  @endforeach
                                @else
                                  <div class="examSchedule upcomingSchedule onlineSchedule">
                                        <div class="examSchedule-l-col w-100">
                                            <ul class="list-unstyled mb-0 examScheduleUl">
                                              <li class="ndf">No data found</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                
                              </div>
                          </div>
                          <div class="col-xl-4">
                              <div class="dashboardBlock">
                                <div class="textTitle_viewAllText">
                                    <div class="dashboardTitle dashboardTitle3">Trending <span>Test</span></div>
                                    <div class="viewAllText" style="color: #3561ff;font-size: 12px;font-weight: 400;cursor: pointer;">
                                        <a href="{{ route('trending_exam') }}">View All</a>
                                    </div>
                                </div>
                                <div class="tab-pane fade active show">
                                    @if(count($trending_test))
                                        @foreach($trending_test as $value)
                                            <div class="examSchedule">
                                              <div class="examSchedule-l-col">
                                                  <ul class="list-unstyled mb-0 examScheduleUl">
                                                    <li>{{$value->name}}</li>
                                                    <li>{{\Carbon\Carbon::parse($value->exam_date)->format('d M Y')}}</li>
                                                  </ul>
                                              </div>
                                              <div class="examSchedule-r-col">
                                                  <a href="#">
                                                    <ul class="list-unstyled mb-0 examScheduleUl">
                                                      <li>{{\Carbon\Carbon::parse($value->exam_date)->format('d M Y')}}</li>
                                                    </ul>
                                                  </a>
                                              </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="examSchedule">
                                          <div class="examSchedule-l-col w-100">
                                              <ul class="list-unstyled mb-0 examScheduleUl">
                                                <li class="ndf">No data found</li>
                                              </ul>
                                          </div>
                                        </div>
                                    @endif
                                </div>
                              </div>
                          </div>
                      </div>
                      @if(count($dashboard_banner))
                        <div class="dashboardSliderAll">
                          <ul class="dashboardSlider list-unstyled mb-0">
                                @foreach($dashboard_banner as $value)
                                  <li>
                                      <div class="dashboardSliderImg" @if($value->video_link) onclick="openVideoModel('{{$value->video_link}}')" @endif>
                                          <img src="{{ asset('') }}uploads/dashboard_banner/{{$value->icon}}" class="img-fluid" alt="">
                                      </div>
                                  </li>
                                @endforeach
                          </ul>
                        </div>
                      @endif
                      
                  </div>
              </div>
          </div>
      </div>
    </section>
    
    <!-- popup -->
    <div class="modal fade" id="examVideo">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <img src="{{ asset('') }}web/images/examVideoClose_ic.png" class="img-fluid examVideoClose_ic" data-bs-dismiss="modal" alt="">
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
          <iframe id="modal_video" width="100%" height="420" src=""></iframe>
        </div>
      </div>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>

    <script>
      $(document).ready(function(){
        $(".dashboardSlider").bxSlider({
          slideWidth: 342,
          minSlides: 1,
          maxSlides: 4,
          slideMargin: 15,
          controls: false,
          pager: false,
          infiniteLoop: true,
          auto: true,
          autoHover: true,
          touchEnabled: false,
          // speed: 700,
          moveSlides: 1
        });
        
        $(".menuBarBtn.menuBarBtnOpen").click(function(){
            $(".dashboardLeft").removeClass("dashboardLeftOff");
        });
        $(".menuBarBtn.menuBarBtnClose").click(function(){
            $(".dashboardLeft").addClass("dashboardLeftOff");
        });
      });
      
      $(".dashboardBannerClose").on("click", function () {
        $(".dashboardBannerOn").fadeOut("slow");   // hides slowly
      });
      
      $(function () {
        const $toggle = $(".top-bar-ScoreExam .switch input");
    
        if ($toggle.is(":checked")) {
            $(".yourScoreOff").hide();
            $(".yourScoreOn").show();
        } else {
            $(".yourScoreOn").hide();
            $(".yourScoreOff").show();
        }
    
        $toggle.on("change", function () {
            $(".yourScoreOff").slideToggle(300);
            $(".yourScoreOn").slideToggle(300);
        });
      });
        
        var max = {{$total_question}};

        @foreach($subject_list as $value)

            new Chart(document.getElementById('comparison_{{$value->id}}'), {

                type: 'doughnut',

                data: {
                    datasets: [{
                        data: [{{(int)$value->count}}, max - {{(int)$value->count}}],
                        backgroundColor: ['#4a5cff', '#e6e6e6'],
                        borderWidth: 0
                    }]
                },

                options: {
                    responsive: true,
                    cutout: '80%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false },

                        // ✅ pass dynamic value here
                        centerText: {
                            score: {{(int)$value->score}}
                        }
                    }
                },

                plugins: [{
                    id: 'centerText',
                    afterDraw(chart) {

                        const score = chart.options.plugins.centerText.score;

                        const meta = chart.getDatasetMeta(0);
                        if (!meta || !meta.data || !meta.data.length) return;

                        const ctx = chart.ctx;
                        const centerX = meta.data[0].x;
                        const centerY = meta.data[0].y;

                        ctx.save();

                        ctx.textAlign = 'center';

                        ctx.font = '10px Arial';
                        ctx.fillStyle = '#777';
                        ctx.fillText('Score', centerX, centerY - 5);

                        ctx.font = 'bold 14px Arial';
                        ctx.fillStyle = '#000';
                        ctx.fillText(score, centerX, centerY + 18);

                        ctx.restore();
                    }
                }]

            });

        @endforeach
      
      function openVideoModel (video_link){
          document.getElementById("modal_video").src = video_link;
            $("#examVideo").modal("show");
      }

      function clickLeaderBoard(type) {
          document.getElementById('leader_board_rns').classList.remove('active');
          document.getElementById('leader_board_rps').classList.remove('active');
          document.getElementById('leader_board_snt').classList.remove('active');
          document.getElementById('leader_board_ots').classList.remove('active');

          document.getElementById('leader_board_header').innerHTML = type;

          if(type == "RNS"){
            document.getElementById('leader_board_rns').classList.add('active');
          }else if(type == "RPS"){
            document.getElementById('leader_board_rps').classList.add('active');
          }else if(type == "SNT"){
            document.getElementById('leader_board_snt').classList.add('active');
          }else {
            document.getElementById('leader_board_ots').classList.add('active');
          }
      }
    </script>
    
    <script>
        // ðŸ‘‰ SET NEET EXAM DATE
        @if($neet_exam)
            const examDate = new Date("{{$neet_exam->value}}").getTime();
        @else
            const examDate = new Date("2026-03-05T00:00:00").getTime();
        @endif
        
    
        // Optional: start date for progress bar
        const startDate = new Date().getTime();
        const totalDuration = examDate - startDate;
    
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = examDate - now;
    
            if (distance <= 0) {
                document.getElementById("daysNeet").innerText = "00";
                document.getElementById("hoursNeet").innerText = "00";
                document.getElementById("minutesNeet").innerText = "00";
                document.getElementById("secondsNeet").innerText = "00";
                document.getElementById("progressNeet").style.width = "100%";
                return;
            }
    
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
            document.getElementById("daysNeet").innerText = days;
            document.getElementById("hoursNeet").innerText = String(hours).padStart(2, "0");
            document.getElementById("minutesNeet").innerText = String(minutes).padStart(2, "0");
            document.getElementById("secondsNeet").innerText = String(seconds).padStart(2, "0");
    
            // Progress bar
            const elapsed = now - startDate;
            const progressPercent = Math.min((elapsed / totalDuration) * 100, 100);
            document.getElementById("progressNeet").style.width = progressPercent + "%";
        }
        
        @if(count($upcoming_list))
            updateCountdown();
            setInterval(updateCountdown, 1000); // update every second
        @endif
        
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