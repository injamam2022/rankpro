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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('common_confusion') }}?subject_id={{$subject_id}}&exam_type=1&reason_id={{$reason_id}}">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('common_confusion') }}?subject_id={{$subject_id}}&exam_type=2&reason_id={{$reason_id}}">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('common_confusion') }}?subject_id={{$subject_id}}&exam_type=&reason_id={{$reason_id}}">Overall</a>
                                    </li>
                                  </ul>
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('common_confusion') }}?subject_id={{$value->id}}&exam_type={{$exam_type}}&reason_id={{$reason_id}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock">
                                <div class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">Mistake Monitor</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="reason-btnAll position-relative justify-content-start">
                                                <!-- Trigger -->
                                                <button class="reason-btn" data-bs-toggle="dropdown">
                                                    Input Reason <span class="caret">▼</span>
                                                </button>
                                                                
                                                <!-- Dropdown -->
                                                <div class="dropdown-menu reason-dropdown question-box">
                                                  @foreach($mistake_input_list as $value)
                                                    <a href="{{ route('common_confusion') }}?subject_id={{$subject_id}}&exam_type={{$exam_type}}&reason_id={{$value->id}}" class="form-check option reason-item">
                                                        <input class="form-check-input" type="radio" name="reason" id="resn{{$value->id}}">
                                                        <label class="form-check-label" for="resn{{$value->id}}">
                                                          {{$value->name}}
                                                        </label>
                                                    </a>
                                                  @endforeach
                                                                    
                                                </div>
                                            </div>
                                        </div>
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
                                    

                                    
                                    <div class="qstnBoxAll qstnBoxAll2">
                                        <div class="textTitle_viewAllText">
                                            <div class="dashboardTitle dashboardTitle4"><span>Question Summary</span></div>
                                            <div class="qstnFilterBubbleAll">
                                                <div class="qstnFilterBubble"></div>
                                                <div class="qstnFilterBubble" style="background: #3561ff;"></div>
                                                <div class="qstnFilterBubble" style="background: #f20b81;"></div>
                                            </div>
                                        </div>
                                        <div class="dashboardBar qstnBar"></div>
                                        
                                        @foreach($question_list as $key => $value)
                                          <!-- question-summary-active -->
                                          <div class="question-summary @if($value->difficulty_level == 1) question-summary-easy @elseif($value->difficulty_level == 2) question-summary-medium @elseif($value->difficulty_level == 3) question-summary-hard @endif">
                                              <div class="row">
                                                  <div class="col-12">
                                                      <div class="question-box">
              
                                                          <div class="question-text">
                                                            <div class="q-no">Q {{$key+1}}:</div>
                                                            <div class="q-only">{!!$value->question_text!!}</div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-12">
                                                      <div class="qstnBoxRight">
                                                          <ul class="nav nav-tabs examTab examTabSub ansTab question-summaryTab">
                                                              <li class="nav-item summary-time-with-btn">
                                                                <a class="nav-link" href="#">Difficulty: &nbsp; 
                                                                  <span>
                                                                    @if($value->difficulty_level == 1)
                                                                      Easy
                                                                    @elseif($value->difficulty_level == 2)
                                                                      Medium
                                                                    @elseif($value->difficulty_level == 3)
                                                                      Hard
                                                                    @endif
                                                                  </span>
                                                                </a>
                                                                <div class="question-summary-time">
                                                                  @if($value->time)
                                                                    Time Taken: {{$value->time}}min
                                                                  @endif
                                                                </div>
                                                              </li>
                                                              <li class="nav-item">
                                                                <a class="nav-link text-white border-0" href="javascript:void(0);" onclick="openVideoSolutionModal('{{$value->video_link}}');" style="background: #2e318b;">Video Solution</a>
                                                              </li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                        @endforeach
                                        <!-- <div class="question-summary question-summary-hard ">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="question-box">
            
                                                        <div class="question-text">
                                                          <div class="q-no">Q 07:</div>
                                                          <div class="q-only">The temperature of gas is -50°C. To what temperature the gas should be heated so that the rms speed is increased by 3 times? The temperature of gas is -50°C. The gas should be heated so that the rms speed is increased by 3 times? The temperature of gas is -50°C. The gas should be heated so that the rms speed is increased by 3 times?</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="qstnBoxRight">
                                                        <ul class="nav nav-tabs examTab examTabSub ansTab question-summaryTab">
                                                            <li class="nav-item summary-time-with-btn">
                                                              <a class="nav-link" href="#">Difficult: &nbsp; <span>Hard</span></a>
                                                              <div class="question-summary-time">Time Taken: 9min</div>
                                                            </li>
                                                            <li class="nav-item">
                                                              <a class="nav-link text-white border-0" href="#" style="background: #2e318b;">Video Solution</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="question-summary question-summary-medium">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="question-box">
            
                                                        <div class="question-text">
                                                          <div class="q-no">Q 07:</div>
                                                          <div class="q-only">The temperature of gas is -50°C. To what temperature the gas? The temperature of gas is -50°C. To what temperature the gas should be heated so that the rms speed is increased by 3 times?</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="qstnBoxRight">
                                                        <ul class="nav nav-tabs examTab examTabSub ansTab question-summaryTab">
                                                            <li class="nav-item summary-time-with-btn">
                                                              <a class="nav-link" href="#">Difficult: &nbsp; <span>Medium</span></a>
                                                              <div class="question-summary-time">Time Taken: 9min</div>
                                                            </li>
                                                            <li class="nav-item">
                                                              <a class="nav-link text-white border-0" href="#" style="background: #2e318b;">Video Solution</a>
                                                            </li>
                                                        </ul>
                                                    </div>
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
    </section>
    <!-- dashboard end -->

    <div class="modal fade" id="videoSolutionModal">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <img src="images/examVideoClose_ic.png" class="img-fluid examVideoClose_ic" data-bs-dismiss="modal" alt="">
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
          <iframe id="modal_video" width="100%" height="420" src=""></iframe>
        </div>
      </div>
    </div>


    
    @include('site.include.call_to_action')
    @include('site.include.how_to_use')


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

          $.get("{{route('common_confusion.topics')}}", data)
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

          $.get("{{route('common_confusion.subtopics')}}", data)
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

          $.get("{{route('common_confusion.question')}}", data)
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

      function openVideoSolutionModal(video_link) {
        document.getElementById("modal_video").src = video_link;
        $("#videoSolutionModal").modal("show");
      }

      $(document).ready(function(){
        
      });
    </script>
</body>

</html>