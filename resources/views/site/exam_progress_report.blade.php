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
                                  <!-- <ul class="nav nav-tabs examTab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('progress_report') }}?subject_id={{$subject_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('progress_report') }}?subject_id={{$subject_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('progress_report') }}?subject_id={{$subject_id}}&exam_type=3">Overall</a>
                                    </li>
                                  </ul> -->
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('exam_progress_report') }}?subject_id={{$value->id}}&exam_id={{$exam_id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                        <li class="nav-item mt-2">
                                            <a class="nav-link  @if($subject_id=='') active @endif" href="{{ route('exam_progress_report') }}?subject_id=&exam_type={{$exam_type}}&exam_id={{$exam_id}}">Combined</a>
                                        </li>
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock">
                                <div class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">
                                            Progress <span>Report</span> 
                                            <!-- <span class="dashboardTitleMute" style="color: #f20b81;">(Last 5 Exam)</span> -->
                                        </div>
                                    </div>
                                    <div class="row resultCardRow" style="margin-bottom: -26px;">
                                        <div class="col-md-6 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Answer Type Format</div>
                                            
                                                <div class="table-wrapper topper-chart" style="display:block;font-size: 13px;font-weight: 500;">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-5">
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;">
                                                        <div>
                                                          Silly
                                                        </div>
                                                        <div style="margin-left: 10px;">
                                                          {{(int) ($silly_percentage)}} %
                                                        </div>
                                                      </div>
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;margin-top: 10px;">
                                                        <div>
                                                          Wrong
                                                        </div>
                                                        <div style="margin-left: 10px;">
                                                          {{(int) ($wrong_percentage)}} %
                                                        </div>
                                                      </div>
                                                      <div style="display:flex;padding: 10px;background-color: #f4f6fa;border-radius: 10px;margin-top: 10px;">
                                                        <div>
                                                          Irrelevant
                                                        </div>
                                                        <div style="margin-left: 10px;">
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
                                        <div class="col-md-6 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Question Level</div>
                                            
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
          const ctx = document.getElementById('comparisonChart');

          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['Silly', 'Wrong', 'Irrelevant'],
              datasets: [{
                label: '',
                data: [{{$silly_percentage}}, {{$wrong_percentage}}, {{$irrelevant_percentage}} ],
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
      });
    </script>
</body>

</html>