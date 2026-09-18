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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('answers_analytics') }}?subject_id={{$subject_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('answers_analytics') }}?subject_id={{$subject_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('answers_analytics') }}?subject_id={{$subject_id}}&exam_type=">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('answers_analytics') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock">
                                <div class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">Answers <span>Analytics</span> @if($subject_details) ({{$subject_details->name}}) @endif</div>
                                    </div>
                                    <div class="row resultCardRow">
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Easy</div>
                                            
                                                <div class="table-responsive table-wrapper">
                                                  <table class="table align-middle result-table">
                                                    <thead>
                                                      <tr class="text-center">
                                                        <th></th>
                                                        <th>Questions</th>
                                                        <th>Right</th>
                                                        <th>Wrong</th>
                                                        <th>Skipped</th>
                                                        <th>Score</th>
                                                      </tr>
                                                    </thead>
                                            
                                                    <tbody>                                                      
                                                      <tr class="row-topper text-center">
                                                        <td>
                                                          <span class="label-pill">Toper</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($easy_toper_list->total_question))
                                                            {{$easy_toper_list->total_question}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($easy_toper_list->total_question))
                                                            {{$easy_toper_list->right_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($easy_toper_list->wrong_answer))
                                                            {{$easy_toper_list->wrong_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($easy_toper_list->skip_answer))
                                                            {{$easy_toper_list->skip_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($easy_toper_list->total_question))
                                                            {{$easy_toper_list->total_mark}}
                                                          @endif
                                                        </td>
                                                      </tr>
                                                      <tr class="row-average text-center">
                                                        <td>
                                                          <span class="label-pill">Average</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($avg_easy_toper_list->total_question))
                                                            {{(int) ($avg_easy_toper_list->total_question)}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($avg_easy_toper_list->right_answer))
                                                            {{(int) ($avg_easy_toper_list->right_answer)}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($avg_easy_toper_list->wrong_answer))
                                                            {{(int) ($avg_easy_toper_list->wrong_answer)}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($avg_easy_toper_list->skip_answer))
                                                            {{(int) ($avg_easy_toper_list->skip_answer)}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($avg_easy_toper_list->total_mark))
                                                            {{(int) ($avg_easy_toper_list->total_mark)}}
                                                          @endif
                                                        </td>
                                                      </tr>

                                                      <tr class="row-your text-center">
                                                        <td>
                                                          <span class="label-pill">Your</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($my_easy_toper_list->total_question))
                                                            {{$my_easy_toper_list->total_question}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($my_easy_toper_list->total_question))
                                                            {{$my_easy_toper_list->right_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($my_easy_toper_list->wrong_answer))
                                                            {{$my_easy_toper_list->wrong_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($my_easy_toper_list->skip_answer))
                                                            {{$my_easy_toper_list->skip_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($my_easy_toper_list->total_question))
                                                            {{$my_easy_toper_list->total_mark}}
                                                          @endif
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </div>
                                            
                                              </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Medium</div>
                                            
                                                <div class="table-responsive table-wrapper">
                                                  <table class="table align-middle result-table">
                                                    <thead>
                                                      <tr class="text-center">
                                                        <th></th>
                                                        <th>Questions</th>
                                                        <th>Right</th>
                                                        <th>Wrong</th>
                                                        <th>Skipped</th>
                                                        <th>Score</th>
                                                      </tr>
                                                    </thead>
                                            
                                                    <tbody>
                                                      <tr class="row-topper text-center">
                                                        <td>
                                                          <span class="label-pill">Toper</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($medium_toper_list->total_question))
                                                            {{$medium_toper_list->total_question}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($medium_toper_list->total_question))
                                                            {{$medium_toper_list->right_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($medium_toper_list->wrong_answer))
                                                            {{$medium_toper_list->wrong_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($medium_toper_list->skip_answer))
                                                            {{$medium_toper_list->skip_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($medium_toper_list->total_question))
                                                            {{$medium_toper_list->total_mark}}
                                                          @endif
                                                        </td>
                                                      </tr>
                                                      
                                                      <tr class="row-average text-center">
                                                        <td>
                                                          <span class="label-pill">Average</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($avg_medium_toper_list->total_question))
                                                            {{(int) ($avg_medium_toper_list->total_question)}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($avg_medium_toper_list->right_answer))
                                                            {{(int) ($avg_medium_toper_list->right_answer)}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($avg_medium_toper_list->wrong_answer))
                                                            {{(int) ($avg_medium_toper_list->wrong_answer)}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($avg_medium_toper_list->skip_answer))
                                                            {{(int) ($avg_medium_toper_list->skip_answer)}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($avg_medium_toper_list->total_mark))
                                                            {{(int) ($avg_medium_toper_list->total_mark)}}
                                                          @endif
                                                        </td>
                                                      </tr>

                                                      <tr class="row-your text-center">
                                                        <td>
                                                          <span class="label-pill">Your</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($my_medium_toper_list->total_question))
                                                            {{$my_medium_toper_list->total_question}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($my_medium_toper_list->total_question))
                                                            {{$my_medium_toper_list->right_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($my_medium_toper_list->wrong_answer))
                                                            {{$my_medium_toper_list->wrong_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($my_medium_toper_list->skip_answer))
                                                            {{$my_medium_toper_list->skip_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($my_medium_toper_list->total_question))
                                                            {{$my_medium_toper_list->total_mark}}
                                                          @endif
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </div>
                                            
                                              </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Hard</div>
                                            
                                                <div class="table-responsive table-wrapper">
                                                  <table class="table align-middle result-table">
                                                    <thead>
                                                      <tr class="text-center">
                                                        <th></th>
                                                        <th>Questions</th>
                                                        <th>Right</th>
                                                        <th>Wrong</th>
                                                        <th>Skipped</th>
                                                        <th>Score</th>
                                                      </tr>
                                                    </thead>
                                            
                                                    <tbody>
                                                      <tr class="row-topper text-center">
                                                        <td>
                                                          <span class="label-pill">Toper</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($hard_toper_list->total_question))
                                                            {{$hard_toper_list->total_question}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($hard_toper_list->total_question))
                                                            {{$hard_toper_list->right_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($hard_toper_list->wrong_answer))
                                                            {{$hard_toper_list->wrong_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($hard_toper_list->skip_answer))
                                                            {{$hard_toper_list->skip_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($hard_toper_list->total_question))
                                                            {{$hard_toper_list->total_mark}}
                                                          @endif
                                                        </td>
                                                      </tr>
                                              
                                                      @if($avg_hard_toper_list)
                                              
                                                        <tr class="row-average text-center">
                                                          <td>
                                                            <span class="label-pill">Average</span>
                                                          </td>
                                                          <td>
                                                            @if(isset($avg_hard_toper_list->total_question))
                                                              {{(int) ($avg_hard_toper_list->total_question)}}
                                                            @endif
                                                          </td>
                                                          <td>
                                                            @if(isset($avg_hard_toper_list->right_answer))
                                                              {{(int) ($avg_hard_toper_list->right_answer)}}
                                                            @endif
                                                          </td>
                                                          <td class="fw-semibold">
                                                            @if(isset($avg_hard_toper_list->wrong_answer))
                                                              {{(int) ($avg_hard_toper_list->wrong_answer)}}
                                                            @endif
                                                          </td>
                                                          <td>
                                                            @if(isset($avg_hard_toper_list->skip_answer))
                                                              {{(int) ($avg_hard_toper_list->skip_answer)}}
                                                            @endif
                                                          </td>
                                                          <td class="fw-semibold">
                                                            @if(isset($avg_hard_toper_list->total_mark))
                                                              {{(int) ($avg_hard_toper_list->total_mark)}}
                                                            @endif
                                                          </td>
                                                        </tr>
                                                      @endif

                                                      <tr class="row-your text-center">
                                                        <td>
                                                          <span class="label-pill">Your</span>
                                                        </td>
                                                        <td>
                                                          @if(isset($my_hard_toper_list->total_question))
                                                            {{$my_hard_toper_list->total_question}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($my_hard_toper_list->total_question))
                                                            {{$my_hard_toper_list->right_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($my_hard_toper_list->wrong_answer))
                                                            {{$my_hard_toper_list->wrong_answer}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if(isset($my_hard_toper_list->skip_answer))
                                                            {{$my_hard_toper_list->skip_answer}}
                                                          @endif
                                                        </td>
                                                        <td class="fw-semibold">
                                                          @if(isset($my_hard_toper_list->total_mark))
                                                            {{$my_hard_toper_list->total_mark}}
                                                          @endif
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </div>
                                            
                                              </div>
                                        </div>
                                    </div>
                                    <
                                    <div class="row resultCardRow">
                                        <div class="col-md-4">
                                          <div class="card shadow-sm h-100 p-3">
                                            <h6 class="fw-bold">Topper Vs Average Vs You</h6>
                                            <div class="row">
                                              <div class="col-md-6">
                                                <div class="mt-3">
                                                  <span class="badge bg-danger">Topper: @if($topper_data) {{(int) $topper_data->total_mark}}/{{(int) $topper_data->totals_marks_for_exam}} @endif</span><br>
                                                  <span class="badge bg-primary mt-2">Average: @if($topper_data) {{(int) $average_data->total_mark}}/{{(int) $average_data->totals_marks_for_exam}} @endif</span><br>
                                                  <span class="badge bg-secondary mt-2">You: @if($topper_data) {{$you_data->total_mark}}/{{(int) $you_data->totals_marks_for_exam}} @endif</span>
                                                </div>
                                              </div>
                                              <div class="col-md-6">
                                                <canvas id="comparisonChart"></canvas>
                                              </div>
                                              
                                            </div>
                                          </div>
                                        </div>

                                        <!-- Accuracy Percentage -->
                                        <div class="col-md-4">
                                          <div class="card shadow-sm h-100 p-3 text-center"
                                               style="background: linear-gradient(135deg,#6a7cff,#ff5bbd); color:#fff;">
                                            <h6 class="fw-bold">Accuracy Percentage</h6>

                                            <div class="row mt-3">
                                              <div class="col-4">
                                                <canvas id="topperAcc"></canvas>
                                                <small>Topper</small>
                                              </div>
                                              <div class="col-4">
                                                <canvas id="avgAcc"></canvas>
                                                <small>Average</small>
                                              </div>
                                              <div class="col-4">
                                                <canvas id="youAcc"></canvas>
                                                <small>You</small>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <!-- Score -->
                                        <div class="col-md-4">
                                          <div class="card shadow-sm h-100 p-3 text-center">
                                            <h6 class="fw-bold">Score</h6>
                                            <div class="row">
                                              <div class="col-md-3">
                                                
                                              </div>
                                              <div class="col-md-6">
                                                <canvas id="scoreChart"></canvas>
                                                <h5 class="mt-2 text-danger">{{$your_score}} / {{$total_user}}</h5>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: -26px;">
                                        <div class="col-md-6">
                                            <div class="answer-summary gradientOut">
                                                <div>
                                                    <div class="answer-title">
                                                      {{$total_exam_attemped}}
                                                    </div>
                                                
                                                    <div class="answer-stats">
                                                      <span>Total Test Attemped by You</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- <div class="answer-summary gradientOut">
                                                <div>
                                                    <div class="answer-title">
                                                      Question Type <span class="fw-semibold">72/80</span>
                                                    </div>
                                                
                                                    <div class="answer-stats">
                                                      <span>Easy <span>20</span></span>
                                                      <span>|</span>
                                                      <span>Medium <span style="color: #f20b81;">20</span></span>
                                                      <span>|</span>
                                                      <span>Hard <span style="color: #5a5a5a;">20</span></span>
                                                    </div>
                                                </div>
                                            </div> -->
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
      var g_data = {
        "topper_data":0,
        "average_data":0,
        "you_data":0
      };

      @if($topper_data)
        g_data.topper_data = {{(int) ($topper_data->total_mark/$topper_data->totals_marks_for_exam*100)}};
      @endif

      @if($average_data)
        @if($average_data->total_mark && $average_data->totals_marks_for_exam)
          g_data.average_data = {{(int) ($average_data->total_mark/$average_data->totals_marks_for_exam*100)}};
        @endif
      @endif

      @if($you_data)
        g_data.you_data = {{(int) ($you_data->total_mark/$you_data->totals_marks_for_exam*100)}};
      @endif

      function donutChart(id, value, color) {
        new Chart(document.getElementById(id), {
          type: 'doughnut',
          data: {
            datasets: [{
              data: [value, 100 - value],
              backgroundColor: [color, '#e9ecef'],
              borderWidth: 0
            }]
          },
          options: {
            cutout: '70%',
            plugins: { legend: { display: false } }
          }
        });
      }

      // Main comparison
      new Chart(document.getElementById('comparisonChart'), {
        type: 'doughnut',
        data: {
          datasets: [{
            data: [g_data.topper_data, g_data.average_data, g_data.you_data],
            backgroundColor: ['#ff007f', '#0d6efd', '#6c757d'],
            borderWidth: 0
          }]
        },
        options: {
          cutout: '65%',
          plugins: { legend: { position: 'bottom' } },
          legend: { display: false },
          tooltip: { enabled: false }
        }
      });

      // Accuracy charts
      donutChart('topperAcc', g_data.topper_data, '#0d6efd');
      donutChart('avgAcc', g_data.average_data, '#ff007f');
      donutChart('youAcc', g_data.you_data, '#6c757d');


      new Chart(document.getElementById('scoreChart'), {
          type: 'doughnut',
          data: {
            datasets: [{
              data: [{{$your_score}}, {{$total_user-$your_score}}],
              backgroundColor: ["#0d6efd", '#e9ecef'],
              borderWidth: 0
            }]
          },
          options: {
            cutout: '70%',
            plugins: { legend: { display: false } }
          }
        });

    </script>
</body>

</html>