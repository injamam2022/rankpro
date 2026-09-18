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

        .circle-wrap {
          width: 80px;
          height: 80px;
          background: #f1f3f6;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .circle {
          width: 76px;
          height: 76px;
          border-radius: 50%;
          background: white;
          position: relative;
        }

        .circle::before {
          content: "";
          position: absolute;
          inset: -8px;
          border-radius: 50%;
        }

        /* Colors & percentages */
        .blue .circle::before {
          background: conic-gradient(#2f6bff 36%, #e9ecef 0);
        }

        .pink .circle::before {
          background: conic-gradient(#f0128b 55%, #e9ecef 0);
        }

        .gray .circle::before {
          background: conic-gradient(#6c757d 40%, #e9ecef 0);
        }

        .inside {
          position: absolute;
          inset: 4px;
          background: #fff;
          border-radius: 50%;
          box-shadow: inset 0 0 6px rgba(0,0,0,0.05);
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
        }

        .inside small {
          font-size: 12px;
          color: #666;
        }

        .inside h6 {
          margin: 0;
          font-weight: 700;
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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('air') }}?subject_id={{$subject_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('air') }}?subject_id={{$subject_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('air') }}?subject_id={{$subject_id}}&exam_type=">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('air') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
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
                                                
                                                <!-- <div class="top-bar-ScoreExam scoreExam_drop">
                                                    <div class="dropdown" onclick="toggleDropdown()">
                                                        <div class="dropdown-label">
                                                            <span>Exam Year</span>
                                                            <span class="arrow"></span>
                                                        </div>
                                                        
                                                        <div class="dropdown-menu" id="dropdownMenu">
                                                            @for($i = date('Y'); $i >= 2020; $i--)
                                                                <div class="@if($year == $i) active @endif">{{$i}}</div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="air-card air-rank-card">
                                                <div class="rank-title">
                                                    National<br>
                                                    Ranking & Score
                                                </div>
                                                <div class="rank-year">{{$year}}</div>
                                            
                                                <div class="rank-number">{{$your_score}}</div>
                                            
                                                <div class="rank-label">All India Rank</div>
                                            
                                                <div class="rank-info">
                                                    <!-- Your Rank {{$your_score}}<br> -->
                                                    Your Score @if($my_leader_board) {{$my_leader_board->total_number}} @endif
                                                    @if($my_leader_board) 
                                                        <div class="rank-name">{{ $my_leader_board->first_name }} {{ strtoupper(substr($my_leader_board->last_name, 0, 1)) }}</div>
                                                    @endif
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
                                                        <span>
                                                            @if($highest_score)
                                                                {{$highest_score->total_mark}}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-pink" style="width:90%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- Average -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        Average Score
                                                        <span>
                                                            @if($average_score)
                                                                {{number_format(($average_score->total_mark / $average_score->total_user),2)}}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-blue" style="width:85%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- My -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        My Score
                                                        <span>@if($my_leader_board) {{$my_leader_board->total_number}}
                                                        @endif&nbsp;</span>
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
                                                        <span>
                                                            @if($my_highest_score)
                                                                {{$my_highest_score->total_number}}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-pink" style="width:90%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- My Average -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        My Average Score
                                                        <span>
                                                            @if($my_average_score)
                                                                @if($my_average_score->total_exam)
                                                                {{number_format(($my_average_score->total_number / $my_average_score->total_exam),2)}}
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bar-blue" style="width:85%"></div>
                                                    </div>
                                                </div>
                                            
                                                <!-- My Lowest -->
                                                <div class="score-row">
                                                    <div class="score-label">
                                                        My Lowest Score
                                                        <span>
                                                            @if($my_lowest_score)
                                                                {{$my_lowest_score->total_number}}
                                                            @endif
                                                        </span>
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
                                            
                                                
                                                <div class="row mt-3">
                                                    @foreach($subject_list as $value)
                                                          <div class="col-sm-12">
                                                              <div class="row">
                                                                  <div class="col-sm-6">
                                                                      <div>{{$value->name}}</div>
                                                                      <div>{{$value->count}}</div>
                                                                  </div>
                                                                  <div class="col-sm-6">
                                                                      <div style="width: 100%;height: 80px;">
                                                                          <canvas id="comparison_{{$value->id}}"></canvas>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                    @endforeach
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>

    <script>
      $(document).ready(function(){
            


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
      });
    </script>
</body>

</html>