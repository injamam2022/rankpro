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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('strength') }}?subject_id={{$subject_id}}&exam_id={{$exam_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('strength') }}?subject_id={{$subject_id}}&exam_id={{$exam_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('strength') }}?subject_id={{$subject_id}}&exam_id={{$exam_id}}&exam_type=3">Overall</a>
                                    </li>
                                  </ul> -->
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('exam_weakness') }}?subject_id={{$value->id}}&exam_id={{$exam_id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
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
                                              <ul class="list-group list-group-flush pt-0" id="chapter_list">
                                                @foreach($chapter_list as $key => $value)
                                                  <li class="list-group-item" id="chapter_{{$value->chapter_id}}" onclick="openChapter({{$value->chapter_id}},{{$key+1}});">
                                                    <div class="num">{{$key+1}}</div>
                                                    <div class="text">{{$value->chapter_name}}</div>
                                                  </li>
                                                @endforeach
                                              </ul>
                                            </div>
                                        
                                          </div>
                                        </div>
                                        
                                        <div class="col-0 strengthCol">
                                          <div class="result-card chapter-card-all">
                                        
                                            <div class="result-card-title">Topics</div>
                                        
                                            <div class="table-wrapper chapter-card topics-card">
                                              <ul class="list-group list-group-flush pt-0" id="topics_list">

                                              </ul>
                                            </div>
                                        
                                          </div>
                                        </div>
                                        
                                        <div class="col-0 strengthCol">
                                          <div class="result-card chapter-card-all">
                                        
                                            <div class="result-card-title">Subtopics</div>
                                        
                                            <div class="table-wrapper chapter-card subtopics-card">
                                              <ul class="list-group list-group-flush pt-0" id="subtopics_list">

                                              </ul>
                                            </div>
                                        
                                          </div>
                                        </div>
                                        
                                        <div class="col-0 strengthCol">
                                          <div class="result-card chapter-card-all">
                                        
                                            <div class="result-card-title">&nbsp;</div>
                                        
                                            <div class="table-wrapper chapter-card question-cardAll" id="question_list">
                                                
                                            </div>
                                        
                                          </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        
                                        
                                        <div class="col-12">
                                          <form id="pdfForm" method="POST" action="{{ route('downloadPdf') }}">
                                            @csrf
                                            <input type="hidden" name="chart_image" id="chart_image">
                                            <input type="hidden" name="pdf_type" id="pdf_type">
                                            <ul class="nav nav-tabs examTab examTabSub ansTab">
                                                <li class="nav-item">
                                                  <a class="nav-link" id="answer_type" href="javascript:void(0);" onclick="pdfType(1);">Answer Type</a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link active" id="question_type" href="javascript:void(0);" onclick="pdfType(2);">Question Type</a>
                                                </li>
                                                <li class="nav-item">
                                                  <a class="nav-link" id="question_level" href="javascript:void(0);" onclick="pdfType(3);">Question Level</a>
                                                </li>
                                                <li class="nav-item">
                                                  <button type="button" class="nav-link" href="#" id="downloadPdfBtn">PDF Download</button>
                                                </li>
                                            </ul>
                                          </form>
                                      </div>
                                        
                                    </div>
                                    <div class="row resultCardRow" style="margin-bottom: -26px;">
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Answer Type Format</div>
                                            
                                                <div class="table-wrapper topper-chart">
                                                  
                                                  <div class="row">
                                                    <div class="col-md-5">
                                                      <div class="chart-Info">
                                                        <div>
                                                          Silly
                                                        </div>
                                                        <div>
                                                          {{(int) ($silly_percentage)}} %
                                                        </div>
                                                      </div>
                                                      <div class="chart-Info">
                                                        <div>
                                                          Wrong
                                                        </div>
                                                        <div>
                                                          {{(int) ($wrong_percentage)}} %
                                                        </div>
                                                      </div>
                                                      <div class="chart-Info">
                                                        <div>
                                                          Irrelevant
                                                        </div>
                                                        <div>
                                                          {{(int) ($irrelevant_percentage)}} %
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-7">
                                                      <div>
                                                        <canvas id="comparisonChart" style="height: 100%; width: 100%;"></canvas>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
                                            <div class="result-card">

                                                <div class="result-card-title">Question Type</div>
                                            
                                                <div class="table-wrapper topper-chart">
                                                  @foreach($question_type as $key => $value)
                                                    <div class="row">
                                                      <div class="col-md-6">
                                                          <div class="chart-Info">
                                                            <div>
                                                              {{$value->name}}
                                                            </div>
                                                            <div>
                                                              @if($value->data)
                                                                {{$value->data->wrong_answer}} / {{$value->data->total_question}}
                                                              @endif
                                                            </div>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="chart-horiz">
                                                          @if($value->data)
                                                          <div class="progress-bar chart-horiz-bar 
                                                              @if((($key+1)%3) == 1)
                                                                bg-pink
                                                              @elseif((($key+1)%3) == 2)
                                                                bg-blue
                                                              @elseif((($key+1)%3) == 0)
                                                                bg-gray
                                                              @endif

                                                              " style="width: {{$value->data->wrong_answer/$value->data->total_question*100}}%;">{{(int) ($value->data->wrong_answer/$value->data->total_question*100)}}
                                                            </div>
                                                          @endif
                                                        </div>                                                      
                                                      </div>
                                                    </div>
                                                  @endforeach
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="col-md-4 resultCardCol">
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
      var pdf_type = 2;

      function pdfType(type){
        pdf_type = type;
        document.getElementById('pdf_type').value = pdf_type;

        document.getElementById('answer_type').classList.remove('active');
        document.getElementById('question_type').classList.remove('active');
        document.getElementById('question_level').classList.remove('active');
      }

      $(document).ready(function(){

        document.getElementById('downloadPdfBtn').addEventListener('click', function() {

            const chartCanvas = document.getElementById('comparisonChart');

            // Convert chart to base64 image
            const imageBase64 = chartCanvas.toDataURL('image/png');

            document.getElementById('chart_image').value = imageBase64;

            document.getElementById('pdfForm').submit();
        });


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

      var globalData = {
        chapter_id:"",
        chapter_index:"",
        topic_id:"",
        topic_index:"",
        subtopic_id:"",
        subtopic_index:"",
        question_id:""
      }

      function openChapter(chapter_id,index) {
          document.querySelectorAll('#chapter_list .list-group-item')
                .forEach(item => item.classList.remove('active-item'));

          document.getElementById('chapter_'+chapter_id).classList.add('active-item');

          var data = {};
          data.chapter_id = chapter_id;

          globalData.chapter_id = chapter_id;
          globalData.chapter_index = index;

          $.get("{{route('exam_weakness.topics')}}", data)
            .done(function( response ) {
              // console.log(response);
              response = JSON.parse(response);
              
              var role_modal_body = ``;
              var s_number = 1;
              response.list.forEach(function(val){
                role_modal_body = role_modal_body + `
                <li class="list-group-item" id="topic_`+val.topic_id+`"  onclick="openTopics(`+val.topic_id+`,`+s_number+`);">
                  <div class="num">`+globalData.chapter_index+`.`+s_number+`.</div>
                  <div class="text">`+val.topic_name+`</div>
                </li>`;
                s_number = s_number+1;
              });
              document.getElementById('topics_list').innerHTML = role_modal_body;
              document.getElementById('subtopics_list').innerHTML = "";
              document.getElementById('question_list').innerHTML = "";
              
            });
      }

      function openTopics(topic_id,index) {
          document.querySelectorAll('#topic_list .list-group-item')
                .forEach(item => item.classList.remove('active-item'));

          document.getElementById('topic_'+topic_id).classList.add('active-item');

          var data = {};
          data.topic_id = topic_id;
          data.chapter_id = globalData.chapter_id;

          globalData.topic_id = topic_id;
          globalData.topic_index = index;

          $.get("{{route('exam_weakness.subtopics')}}", data)
            .done(function( response ) {
              // console.log(response);
              response = JSON.parse(response);
              
              var role_modal_body = ``;
              var s_number = 1;
              response.list.forEach(function(val){
                role_modal_body = role_modal_body + `
                <li class="list-group-item" id="topic_`+val.sub_topic_id+`"  onclick="openSubTopics(`+val.sub_topic_id+`,`+s_number+`);">
                  <div class="num">`+globalData.chapter_index+`.`+globalData.topic_index+`.`+s_number+`.</div>
                  <div class="text">`+val.sub_topic_name+`</div>
                </li>`;
                s_number = s_number+1;
              });
              document.getElementById('subtopics_list').innerHTML = role_modal_body;
              document.getElementById('question_list').innerHTML = "";
              
            });
      }

      function openSubTopics(sub_topic_id,index) {
          document.querySelectorAll('#topic_list .list-group-item')
                .forEach(item => item.classList.remove('active-item'));

          document.getElementById('topic_'+sub_topic_id).classList.add('active-item');

          var data = {};
          data.sub_topic_id = sub_topic_id;
          data.topic_id = globalData.topic_id;
          data.chapter_id = globalData.chapter_id;

          globalData.sub_topic_id = sub_topic_id;
          globalData.subtopic_index = index;

          $.get("{{route('exam_weakness.question')}}", data)
            .done(function( response ) {
              // console.log(response);
              response = JSON.parse(response);
              
              var role_modal_body = ``;
              var difficulty_level = ``;
              var s_number = 1;
              response.list.forEach(function(val){
                if(val.difficulty_level == 1){
                  difficulty_level = "Easy";
                }else if(val.difficulty_level == 2){
                  difficulty_level = "Medium";
                }else{
                  difficulty_level = "Hard";
                }
                //<div class="question-img">
                  //<img src="https://loremflickr.com/200/200/physics" class="img-fluid" alt="">
                //</div>
                //<span class="timespan">56 sec.</span>
                role_modal_body = role_modal_body + `<a href="#" class="question-card">
                        
                    
                        <div class="question-content">
                          <div class="question-title">
                            Q`+s_number+`. `+val.question_text+`
                          </div>
                          <div class="question-meta">
                            
                            <span class="difficulty">`+difficulty_level+`</span>
                          </div>
                        </div>
                    </a>`;
                s_number = s_number+1;
              });
              document.getElementById('question_list').innerHTML = role_modal_body;
              
            });
      }

      
    </script>
</body>

</html>