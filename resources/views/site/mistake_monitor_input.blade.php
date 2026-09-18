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
                                      <a class="nav-link @if($exam_type==1) active @endif" href="{{ route('answers_analytics') }}?subject_id={{$subject_id}}&exam_type=1">Online Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==2) active @endif" href="{{ route('answers_analytics') }}?subject_id={{$subject_id}}&exam_type=2">Offline Examination</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link @if($exam_type==3 || $exam_type == '') active @endif" href="{{ route('answers_analytics') }}?subject_id={{$subject_id}}&exam_type=3">Overall</a>
                                    </li>
                                  </ul> -->
                                  <ul class="nav nav-tabs examTab examTabSub">
                                    @foreach($subject_list as $value)
                                        <li class="nav-item mt-2" >
                                          <a class="nav-link  @if($subject_id==$value->id) active @endif" href="{{ route('mistake_monitor_input') }}?user_exam_id={{$user_exam_id}}&id={{$exam_result_id}}&subject_id={{$value->id}}&exam_type={{$exam_type}}">{{$value->name}}</a>
                                        </li>
                                    @endforeach
                                  </ul>
                              </div>
                          </div>
                          <div class="col-12 mb-0">
                              <div class="tab-content dashboardBlock">
                                <div class="tab-pane fade show active">
                                    <div class="textTitle_viewAllText">
                                        <div class="dashboardTitle dashboardTitle3">OMR - <span>Rank Pro NEET SUMMIT</span></div>
                                    </div>
                                    <div class="qstnBubbleAll" id="question_list">
                                        @foreach($question_list as $key => $value)
                                          <div id="question_list_{{$value->id}}" class="qstnBubble @if($value->id == $exam_result_id) qstnBubble_active @endif" onclick="clickQuestion('{{$value->id}}');">Q{{$value->question_number}}</div>
                                        @endforeach
                                    </div>
                                    <div class="qstnBoxAll">
                                        <div class="row" id="full_box_div">
                                            <div class="col-md-9">
                                                <div class="question-box">
        
                                                    <div class="question-text">
                                                      <div class="q-no" id="question_number"></div>
                                                      <div class="q-only" id="question_text"></div>
                                                    </div>
                                                
                                                    <div class="options">
                                                      <div class="form-check option" id="option1_div">
                                                        <input class="form-check-input" type="radio" name="q7" id="opt1" checked>
                                                        <label class="form-check-label" for="opt1" id="option1">
                                                          
                                                        </label>
                                                      </div>
                                                
                                                      <div class="form-check option" id="option2_div">
                                                        <input class="form-check-input" type="radio" name="q7" id="opt2">
                                                        <label class="form-check-label" for="opt2" id="option2">
                                                          
                                                        </label>
                                                      </div>
                                                
                                                      <div class="form-check option" id="option3_div">
                                                        <input class="form-check-input" type="radio" name="q7" id="opt3">
                                                        <label class="form-check-label" for="opt3" id="option3">
                                                          
                                                        </label>
                                                      </div>
                                                
                                                      <div class="form-check option" id="option4_div">
                                                        <input class="form-check-input" type="radio" name="q7" id="opt4">
                                                        <label class="form-check-label" for="opt4" id="option4">
                                                         
                                                        </label>
                                                      </div>
                                                    </div>
                                                
                                                  </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="qstnBoxRight">
                                                    <div class="reason-btnAll position-relative">
                                                      <i class="fas fa-forward" style="color: #3561ff;"></i>
                                                      <!-- Trigger -->
                                                      <button class="reason-btn" data-bs-toggle="dropdown">
                                                        Input Reason <span class="caret">▼</span>
                                                      </button>
                                                    
                                                      <!-- Dropdown -->
                                                      <div class="dropdown-menu reason-dropdown question-box question-box1">
                                                        @foreach($mistake_input_list as $value)
                                                          <div class="form-check option reason-item">
                                                              <input class="form-check-input" type="radio" name="reason" id="resn{{$value->id}}" onchange="changeInputReason('{{$value->id}}');">
                                                              <label class="form-check-label" for="resn{{$value->id}}">
                                                                {{$value->name}}
                                                              </label>
                                                          </div>
                                                        @endforeach
                                                    
                                                      </div>
                                                    </div>
                                                    <ul class="nav nav-tabs examTab examTabSub ansTab reasonTab" id="difficult_div">
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-0">
                                                <div class="answer-summary stats-wrapper">
                                                    <div class="stat-item">
                                                      <div class="stat-item-title" id="student_attempted"></div>
                                                      <div class="stat-item-text">Student Attempted</div>
                                                    </div>
                                                
                                                    <div class="divider"></div>
                                                
                                                    <div class="stat-item">
                                                      <div class="stat-item-title" id="attempted_correct"></div>
                                                      <div class="stat-item-text">Attempted Correct</div>
                                                    </div>
                                                
                                                    <div class="divider"></div>
                                                
                                                    <div class="stat-item">
                                                      <div class="stat-item-title"><span id="time_spent_by_you"></span></div>
                                                      <div class="stat-item-text">Time spent by you</div>
                                                    </div>
                                                
                                                    <div class="divider"></div>
                                                
                                                    <div class="stat-item">
                                                      <div class="stat-item-title" id="average_time_spent"></div>
                                                      <div class="stat-item-text">Average Time Spent</div>
                                                    </div>
                                                
                                                    <div class="divider"></div>
                                                
                                                    <div class="stat-item">
                                                      <div class="stat-item-title" id="time_spent_by_topper"></div>
                                                      <div class="stat-item-text">Time Spent by Topper</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="questionShift">
                                        <a href="#" class="prev-question" onclick="previousQuestion();">
                                          <span class="text">Previous Question</span>
                                          <span class="arrow">
                                            <img src="{{ asset('') }}web/images/questionShift_left.png" class="img-fluid" alt="">
                                          </span>
                                        </a>
                                        <ul class="nav nav-tabs examTab examTabSub ansTab reasonTab">
                                            <li class="nav-item">
                                              <a class="nav-link active border-0" href="{{ route('common_confusion') }}">Mistake Monitor</span></a>
                                            </li>
                                        </ul>
                                        <a href="#" class="prev-question" onclick="nextQuestion();">
                                          <span class="arrow">
                                            <img src="{{ asset('') }}web/images/questionShift_right.png" class="img-fluid" alt="">
                                          </span>
                                          <span class="text">Next Question</span>
                                        </a>
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
    <!-- popup -->
    <div class="modal fade" id="videoSolutionModal">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <img src="images/examVideoClose_ic.png" class="img-fluid examVideoClose_ic" data-bs-dismiss="modal" alt="">
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
          <iframe id="modal_video" width="100%" height="420" src=""></iframe>
        </div>
      </div>
    </div>
    <div class="modal fade" id="answerSolutionModal">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <img src="images/examVideoClose_ic.png" class="img-fluid examVideoClose_ic" data-bs-dismiss="modal" alt="">
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
          <div id="answer_solution_id">
            
          </div>
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
      var question_list = <?php echo json_encode($question_list);?>;
      var g_exam_result_id = '{{$exam_result_id}}';
      var question_detail;

      function openVideoSolutionModal(video_link) {
        document.getElementById("modal_video").src = video_link;
        $("#videoSolutionModal").modal("show");
      }

      function openAnswerSolutionModal(video_link) {
        document.getElementById("answer_solution_id").innerHTML = video_link;
        $("#examVideo").modal("show");
      }

      function changeInputReason(id) {
        var data = {};
        data.question_id = question_detail.id;
        data.mistake_input_id = id;
        data.user_exam_id = question_detail.exam_user_id;
        data.exam_id = question_detail.exam_id;

        const obj = question_list.find(item => item.id == question_detail.id);
        if (obj) {
          obj.mistake_input_id = id;
        }

        $.get("{{route('mistake_monitor_input_reason')}}", data)
            .done(function( response ) {
              
              response = JSON.parse(response);
              
              console.log(response);
              
            });
      }

      function fillQuestionData(value){
            console.log(value);
            document.getElementById('full_box_div').style.display = "flex";
    
            document.querySelectorAll('.question-box1').forEach(el => {
                  el.checked = false;
              });
    
            $('.qstnBubbleAll .qstnBubble').removeClass('qstnBubble_active');
            $('#question_list_' + value.id).addClass('qstnBubble_active');
    
            document.getElementById('question_number').innerHTML = "Q "+value.question_number+" : ";
            document.getElementById('question_text').innerHTML = value.question_text;
    
            if(value.mistake_input_id){
              document.getElementById('resn'+value.mistake_input_id).checked = true;
            }
    
            var isChecked = "";
    
            if(value.option1){
              isChecked = (value.answer == 1)?'checked':"";
              if(value.is_option1_image == 0){
                document.getElementById('option1_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      `+value.option1+`
                    </label>`;
              }else{
                document.getElementById('option1_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      <div class="answerImg"><img src="{{ asset('') }}uploads/question/`+value.option1+`"></div>
                    </label>`;
              }
            }else{
              document.getElementById('option1_div').innerHTML = "";
            }
    
            if(value.option2){
              isChecked = (value.answer == 2)?'checked':"";
              if(value.is_option2_image == 0){
                document.getElementById('option2_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      `+value.option2+`
                    </label>`;
              }else{
                document.getElementById('option2_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      <div class="answerImg"><img src="{{ asset('') }}uploads/question/`+value.option2+`"></div>
                    </label>`;
              }
            }else{
              document.getElementById('option2_div').innerHTML = "";
            }
    
            if(value.option3){
              isChecked = (value.answer == 3)?'checked':"";
              if(value.is_option3_image == 0){
                document.getElementById('option3_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      `+value.option3+`
                    </label>`;
              }else{
                document.getElementById('option3_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      <div class="answerImg"><img src="{{ asset('') }}uploads/question/`+value.option3+`"></div>
                    </label>`;
              }
            }else{
              document.getElementById('option3_div').innerHTML = "";
            }
    
            if(value.option4){
              isChecked = (value.answer == 4)?'checked':"";
              if(value.is_option4_image == 0){
                document.getElementById('option4_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      `+value.option4+`
                    </label>`;
              }else{
                document.getElementById('option4_div').innerHTML = `
                    <input class="form-check-input" type="radio" name="q7" id="opt1" `+isChecked+`>
                    <label class="form-check-label" for="opt1">
                      <div class="answerImg"><img src="{{ asset('') }}uploads/question/`+value.option4+`"></div>
                    </label>`;
              }
            }else{
              document.getElementById('option4_div').innerHTML = "";
            }
    
            document.getElementById('student_attempted').innerHTML = value.student_attempted;
            document.getElementById('attempted_correct').innerHTML = value.attempted_correct;
            document.getElementById('time_spent_by_you').innerHTML = value.time_spent_by_you;
            document.getElementById('average_time_spent').innerHTML = value.average_time_spent;
            document.getElementById('time_spent_by_topper').innerHTML = value.time_spent_by_topper;
    
            var difficult_type = "";
    
            if(value.difficulty_level == 1){
              difficult_type = "Easy";
            }else if(value.difficulty_level == 2){
              difficult_type = "Medium";
            }else if(value.difficulty_level == 3){
              difficult_type = "Hard";          
            }
    
            document.getElementById('difficult_div').innerHTML = `
                        <li class="nav-item">
                          <a class="nav-link" href="#">Difficulty: &nbsp; <span style="color: #3561ff;">`+difficult_type+`</span></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link ansSolBtn" onclick="openAnswerSolutionModal('Not provided');">Answer Solution</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link ansSolBtn" onclick="openVideoSolutionModal('`+value.video_link+`');" style="background: #2e318b;">Video Solution</a>
                        </li>`;
      }

      function clickQuestion(exam_result_id){
        g_exam_result_id = exam_result_id;
        question_detail = question_list.find(u => u.id == exam_result_id);
        
        console.log(question_detail);
        
        fillQuestionData(question_detail);
      }

      function previousQuestion(){
          const index = question_list.findIndex(user => user.id == g_exam_result_id);
          question_detail = question_list[index-1];
          if(question_detail){
            g_exam_result_id = question_detail.id;
            fillQuestionData(question_detail);
          }else{
            alert("Not more data");
          }
          
      }

      function nextQuestion(){
          const index = question_list.findIndex(user => user.id == g_exam_result_id);
          question_detail = question_list[index+1];
          if(question_detail){
            g_exam_result_id = question_detail.id;
            fillQuestionData(question_detail);
          }else{
            alert("Not more data");
          }
        
      }

      $(document).ready(function(){

        if(g_exam_result_id){
          question_detail = question_list.find(u => u.id == g_exam_result_id);
          
          //console.log(question_detail);
          if(question_detail){
              fillQuestionData(question_detail);
          }else{
              document.getElementById('full_box_div').style.display = "none";
          }
          
        }
      });
    </script>
</body>

</html>