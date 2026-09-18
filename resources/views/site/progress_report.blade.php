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

        .chart-card {
            position: relative;
            height: 225px;   /* IMPORTANT */
            width: 100%;
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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('progress_report') }}?subject_id={{$subject_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('progress_report') }}?subject_id={{$subject_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('progress_report') }}?subject_id={{$subject_id}}&exam_type=">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('progress_report') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                        <li class="nav-item mt-2">
                                            <a class="nav-link  @if($subject_id=='') active @endif" href="{{ route('progress_report') }}?subject_id=&exam_type={{$exam_type}}">Combined</a>
                                        </li>
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock">
                                <div class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">Progress <span>Report</span> <span class="dashboardTitleMute" style="color: #f20b81;">(Last 5 Exam)</span></div>
                                    </div>
                                    <div class="row resultCardRow" style="margin-bottom: -26px;">
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Answer Type Format</div>
                                            
                                                <div class="table-wrapper topper-chart pt-3" style="display:block;font-size: 13px;font-weight: 500;">
                                                  
                                                  <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                      <canvas id="topperAcc" style="width: 110px;height:110px;"></canvas>
                                                      <small>Topper</small>
                                                    </div>
                                                    <div class="col-sm-4 text-center">
                                                      <canvas id="avgAcc" style="width: 110px;height:110px;"></canvas>
                                                      <small>Average</small>
                                                    </div>
                                                    <div class="col-sm-4 text-center">
                                                      <canvas id="youAcc" style="width: 110px;height:110px;"></canvas>
                                                      <small>You</small>
                                                    </div>
                                                    
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Accuracy By Subject</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 11px;font-weight: 400;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-5">
                                                      @foreach($subject_list as $value)
                                                        <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;margin-top: 10px;">
                                                          <div>
                                                            {{$value->name}}
                                                          </div>
                                                          <div style="margin-left: 5px;">
                                                            {{(int) ($value->percentage)}} %
                                                          </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-7">
                                                      <canvas id="comparison2Chart"></canvas>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Overall Performance</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 11px;font-weight: 400;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-5">
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;">
                                                        <div>
                                                          Topper
                                                        </div>
                                                        <div style="margin-left: 5px;">
                                                          @if($topper_data)
                                                            {{(int) ($topper_data->total_mark)}}
                                                          @endif
                                                        </div>
                                                      </div>
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;margin-top: 10px;">
                                                        <div>
                                                          Average
                                                        </div>
                                                        <div style="margin-left: 5px;">
                                                          @if($average_data)
                                                            {{(int) ($average_data->total_mark)}}
                                                          @endif
                                                        </div>
                                                      </div>
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;margin-top: 10px;">
                                                        <div>
                                                          Your
                                                        </div>
                                                        <div style="margin-left: 5px;">
                                                          @if($you_data)
                                                            {{(int) ($you_data->total_mark)}}
                                                          @endif
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-7" style="height: 150px; text-align: center;">
                                                      <canvas id="overallPerformance" ></canvas>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Topper Vs Average Vs Your</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 13px;font-weight: 500;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-12" style="width: 100%;height: 180px;">
                                                      <canvas id="topperAverageYourSubject"></canvas>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Participation Rate (as per exam)</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 13px;font-weight: 500;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-12 chart-card">
                                                      <canvas id="participationRate" style="margin-top: -50px;"></canvas>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Leaderboard Improvement</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 13px;font-weight: 500;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-12" style="width: 100%;height: 170px;">
                                                      <canvas id="leaderboardImprovement"></canvas>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol" style="display:none;">
                                            <div class="result-card">

                                                <div class="result-card-title">Goal Setting Vs Your Performance</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 13px;font-weight: 500;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-12" style="width: 150px;height: 150px;">
                                                      <canvas id="goalSettingYouPerformance" ></canvas>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Question Answer Format</div>
                                            
                                                <div class="table-wrapper topper-chart">
                                                  <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="chart-Info">
                                                          <div>
                                                            Easy
                                                          </div>
                                                          <div>
                                                            {{$easy_details['result']}}/{{$easy_details['total']}}
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <div class="chart-horiz">
                                                        <div class="progress-bar chart-horiz-bar bg-pink" style="width: {{number_format($easy_details['percentage'],2)}}%;">{{(int) ($easy_details['percentage'])}}</div>
                                                      </div>                                                      
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="chart-Info">
                                                          <div>
                                                            Medium
                                                          </div>
                                                          <div>
                                                            {{$medium_details['result']}}/{{$medium_details['total']}}
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <div class="chart-horiz">
                                                        <div class="progress-bar chart-horiz-bar bg-blue" style="width: {{(int) ($medium_details['percentage'])}}%;">{{(int) ($medium_details['percentage'])}}</div>
                                                      </div>                                                      
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="chart-Info">
                                                          <div>
                                                            Hard
                                                          </div>
                                                          <div>
                                                            {{$hard_details['result']}}/{{$hard_details['total']}}
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                      <div class="chart-horiz">
                                                        <div class="progress-bar chart-horiz-bar bg-gray" style="width: {{(int) ($hard_details['percentage'])}}%;">{{(int) ($hard_details['percentage'])}}</div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Answer Type Format</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 11px;font-weight: 400;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-5">
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;">
                                                        <div>
                                                          Silly
                                                        </div>
                                                        <div style="margin-left: 5px;">
                                                          {{(int) ($silly_percentage)}} %
                                                        </div>
                                                      </div>
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;margin-top: 10px;">
                                                        <div>
                                                          Wrong
                                                        </div>
                                                        <div style="margin-left: 5px;">
                                                          {{(int) ($wrong_percentage)}} %
                                                        </div>
                                                      </div>
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;margin-top: 10px;">
                                                        <div>
                                                          Irrelevant
                                                        </div>
                                                        <div style="margin-left: 5px;">
                                                          {{(int) ($irrelevant_percentage)}} %
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-7">
                                                      <canvas id="comparisonChart"></canvas>
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
        "you_data":0,
        "topper_data_o":0,
        "average_data_o":0,
        "you_data_o":0
      };

      @if($topper_data)
        g_data.topper_data = {{(int) ($topper_data->total_mark/$topper_data->totals_marks_for_exam*100)}};
        g_data.topper_data_o = {{(int) ($topper_data->total_mark)}};
      @endif

      @if($average_data)
        @if($average_data->total_mark && $average_data->totals_marks_for_exam)
          g_data.average_data = {{(int) ($average_data->total_mark/$average_data->totals_marks_for_exam*100)}};
          g_data.average_data_o = {{(int) ($average_data->total_mark)}};
        @endif
      @endif

      @if($you_data)
        g_data.you_data = {{(int) ($you_data->total_mark/$you_data->totals_marks_for_exam*100)}};
        g_data.you_data_o = {{(int) ($you_data->total_mark)}};
      @endif

      const score = g_data.topper_data_o;
      const total = 720;

      const overallPerformance = document.getElementById('overallPerformance');

      new Chart(overallPerformance, {
          type: 'doughnut',
          data: {
              labels: ['Topper', 'Average', 'Your'],
              datasets: [{
                  data: [score, g_data.average_data_o, g_data.you_data_o],
                  backgroundColor: [
                      '#e91e63', // pink
                      '#3f51b5', // blue
                      '#9e9e9e'  // gray
                  ],
                  borderWidth: 0
              }]
          },
          options: {
              responsive: true,
              cutout: '70%', // makes the hole bigger
              plugins: {
                  legend: {
                      display: false
                  }
              }
          },
          plugins: [{
              id: 'centerText',
              beforeDraw(chart) {
                  const { width } = chart;
                  const { height } = chart;
                  const ctx = chart.ctx;

                  ctx.restore();
                  ctx.font = "bold 20px sans-serif";
                  ctx.textBaseline = "middle";
                  ctx.fillStyle = "#333";

                  const text = score + "/" + total;
                  const textX = Math.round((width - ctx.measureText(text).width) / 2);
                  const textY = height / 2;

                  ctx.fillText(text, textX, textY);
                  ctx.save();
              }
          }]
      });
    </script>
    <script>
      $(document).ready(function(){
          const topperAverageYourSubject = document.getElementById('topperAverageYourSubject');

          new Chart(topperAverageYourSubject, {
            type: 'line',
            data: {
              labels: ['Physics', 'Chemistry', 'Biology'],
              datasets: [
                {
                  label: 'Topper',
                  data: [90, 55, 88],
                  borderColor: '#ff007f',
                  backgroundColor: '#ff007f',
                  tension: 0.3,
                  pointRadius: 6,
                  pointStyle: 'rect',
                  fill: false
                },
                {
                  label: 'Average',
                  data: [80, 35, 78],
                  borderColor: '#2962ff',
                  backgroundColor: '#2962ff',
                  tension: 0.3,
                  pointRadius: 6,
                  pointStyle: 'rect',
                  fill: false
                },
                {
                  label: 'Your',
                  data: [55, 95, 92],
                  borderColor: '#6c757d',
                  backgroundColor: '#6c757d',
                  tension: 0.3,
                  pointRadius: 6,
                  pointStyle: 'rect',
                  fill: false
                }
              ]
            },
            options: {
              responsive: true,
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: {
                    usePointStyle: true
                  }
                }
              },
              scales: {
                y: {
                  min: 0,
                  max: 100,
                  ticks: {
                    callback: value => value + '%'
                  }
                }
              }
            }
          });


          const leaderboardImprovement = document.getElementById('leaderboardImprovement');

          new Chart(leaderboardImprovement, {
              type: 'line',
              data: {
                  labels: ['200', '800', '1600'],
                  datasets: [
                      {
                          label: 'Dataset 1',
                          data: [12, 25, null], // Pink line
                          borderColor: '#ff2f92',
                          backgroundColor: '#ff2f92',
                          pointStyle: 'rect',
                          pointRadius: 6,
                          borderWidth: 2,
                          spanGaps: false
                      },
                      {
                          label: 'Dataset 2',
                          data: [null, 25, 90], // Blue line
                          borderColor: '#2f6bff',
                          backgroundColor: '#2f6bff',
                          pointStyle: 'rect',
                          pointRadius: 6,
                          borderWidth: 2,
                          spanGaps: false
                      }
                  ]
              },
              options: {
                  plugins: {
                      legend: {
                          display: false
                      },
                      tooltip: {
                          callbacks: {
                              label: function(context) {
                                  if (context.dataIndex === 2) {
                                      return "720 / 800";
                                  }
                                  return context.parsed.y + "%";
                              }
                          }
                      }
                  },
                  scales: {
                      y: {
                          min: 0,
                          max: 100,
                          ticks: {
                              callback: function(value) {
                                  return value + "%";
                              }
                          }
                      }
                  }
              }
          });

          const ctx = document.getElementById('goalSettingYouPerformance').getContext('2d');

          /* Gradient Colors */
          const blueGradient = ctx.createLinearGradient(0, 0, 0, 200);
          blueGradient.addColorStop(0, '#5b8cff');
          blueGradient.addColorStop(1, '#1e3fa3');

          const pinkGradient = ctx.createLinearGradient(0, 0, 0, 200);
          pinkGradient.addColorStop(0, '#ff2f92');
          pinkGradient.addColorStop(1, '#8a004f');


          /* Custom Plugin for Side Labels */
          const sideLabelPlugin = {
              id: 'sideLabelPlugin',
              afterDraw(chart) {
                  const {ctx, chartArea:{top, bottom, left, right, width, height}} = chart;

                  ctx.save();
                  ctx.font = "16px Arial";
                  ctx.lineWidth = 2;

                  // LEFT TEXT (Goal)
                  ctx.strokeStyle = "#3f6cff";
                  ctx.fillStyle = "#3f6cff";

                  ctx.beginPath();
                  ctx.moveTo(left + 70, height/2);
                  ctx.lineTo(left + 20, height/2);
                  ctx.lineTo(left + 20, height/2 - 30);
                  ctx.stroke();

                  ctx.fillText("Goal: 47%", left - 5, height/2 - 35);

                  // RIGHT TEXT (Your)
                  ctx.strokeStyle = "#ff2f92";
                  ctx.fillStyle = "#ff2f92";

                  ctx.beginPath();
                  ctx.moveTo(right - 70, height/2);
                  ctx.lineTo(right + 20, height/2);
                  ctx.lineTo(right + 20, height/2 - 30);
                  ctx.stroke();

                  ctx.fillText("Your: 53%", right - 30, height/2 - 35);

                  ctx.restore();
              }
          };


          new Chart(ctx, {
              type: 'doughnut',
              data: {
                  labels: ['Goal', 'Your'],
                  datasets: [{
                      data: [47, 53],
                      backgroundColor: [blueGradient, pinkGradient],
                      borderWidth: 0
                  }]
              },
              options: {
                  cutout: '60%',
                  plugins: {
                      legend: {
                          display: false
                      },
                      tooltip: {
                          enabled: false
                      }
                  }
              },
              plugins: [sideLabelPlugin]
          });

          const value = 63;

          const gaugeText = {
            id: 'gaugeText',
            afterDraw(chart) {
              const { ctx, chartArea } = chart;
              ctx.save();
              ctx.font = 'bold 28px Arial';
              ctx.fillStyle = '#ff007f';
              ctx.textAlign = 'center';
              ctx.textBaseline = 'middle';
              ctx.fillText(
                value + '%',
                (chartArea.left + chartArea.right) / 2,
                chartArea.bottom - 20
              );
            }
          };

          new Chart(document.getElementById('participationRate'), {
            type: 'doughnut',
            data: {
              datasets: [{
                data: [value, 100 - value],
                backgroundColor: ['#3b66f6', '#eeeeee'],
                borderWidth: 0,
                circumference: 180,
                rotation: 270
              }]
            },
            options: {
              responsive: true,
              cutout: '70%',
              plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
              }
            },
            plugins: [gaugeText]
          });
      });

      function createBarChart(id,data_arr,name_arr) {
          const ctx = document.getElementById(id);

          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: name_arr,
              datasets: [{
                label: '',
                data: data_arr,
                backgroundColor: ['#ff007f', '#0d6efd', '#6c757d'],
                borderWidth: 1
              }]
            },
            options: {
              scales: {
              y: {
                beginAtZero: true,
                max: 100,          // 👈 fixed top like image
                ticks: {
                  stepSize: 25    // 👈 0,20,40,60,80
                },
                grid: {
                  color: '#e9ecef'
                }
              },
              x: {
                grid: {
                  display: false
                }
              }
            },
              plugins: {
                title: {
                  display: false
                },
                tooltip: {
                  enabled: false
                },
                legend: {
                  display: false
                }
              }
            }
          });
      }

      var data_arr = [{{(int) $silly_percentage}}, {{(int) $wrong_percentage}}, {{(int) $irrelevant_percentage}}];
      createBarChart('comparisonChart',data_arr,['Silly','Wrong','Irrelevant']);
      var data_arr = [];
      var data_heder = [];
      @foreach($subject_list as $value)
        data_arr.push({{$value->percentage}});
        data_heder.push("{{$value->name}}");
      @endforeach
      console.log(data_arr);
      console.log(data_heder);
      createBarChart('comparison2Chart',data_arr,data_heder);


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
            responsive: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
          }
        });
      }

      donutChart('topperAcc', g_data.topper_data, '#0d6efd');
      donutChart('avgAcc', g_data.average_data, '#ff007f');
      donutChart('youAcc', g_data.you_data, '#6c757d');


    </script>
</body>

</html>