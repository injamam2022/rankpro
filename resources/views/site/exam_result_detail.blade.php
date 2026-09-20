<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
    <!-- <link rel="stylesheet" href="css/jquery.bxslider.css"> -->

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/dashboard.css" rel="stylesheet">
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
                  <div class="dashboardRightBody resultOmrBody">
                      <div class="row">
                          <div class="col-10 resultOmr-col">
                              <div class="dashboardBlock">
                                @if(($offline_exam->proctoring_status ?? '') === 'cancelled')
                                  <div style="background:#fdecea;color:#b71c1c;border:1px solid #f5c2c0;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-weight:600;">
                                    This exam was cancelled due to webcam proctoring violations (face not visible, camera covered, or another person detected).
                                  </div>
                                @elseif(($offline_exam->proctoring_status ?? '') === 'auto_submitted')
                                  <div style="background:#fff8e1;color:#8a5a00;border:1px solid #ffe082;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-weight:600;">
                                    This exam was submitted automatically after repeated proctoring warnings.
                                  </div>
                                @endif
                                  <div class="resultOmrTitle">{{$offline_exam->result_title}}</div>
                                  <div class="resultOmrText">{{$offline_exam->result_description}}</div>
                                  <div class="resultOmrSubject">{{$offline_exam->name}}</div>

                                  <div class="omrSheetDetails">
                                    <div class="row">
                                      <div class="col-4">
                                        <div class="omrSheetIdInfo_scroll">
                                          <div class="omrSheetIdInfo">
                                            <div class="omrSheetId_candidate">
                                              <div class="omrSheetDetailsTitle">CANDIDATE ID</div>
                                              <div class="omrSheetIdField">
                                                <div class="answerFillBoxAll">
                                                  <div class="answerFillBox">{{$user_id[0]}}</div>
                                                  <div class="answerFillBox">{{$user_id[1]}}</div>
                                                  <div class="answerFillBox">{{$user_id[2]}}</div>
                                                  <div class="answerFillBox">{{$user_id[3]}}</div>
                                                  <div class="answerFillBox">{{$user_id[4]}}</div>
                                                  <div class="answerFillBox">{{$user_id[5]}}</div>
                                                  <div class="answerFillBox">{{$user_id[6]}}</div>
                                                  <div class="answerFillBox">{{$user_id[7]}}</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($user_id[1] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($user_id[2] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($user_id[3] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($user_id[4] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($user_id[5] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($user_id[6] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($user_id[7] == 0) candidateId_fill @endif">0</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($user_id[1] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($user_id[2] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($user_id[3] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($user_id[4] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($user_id[5] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($user_id[6] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($user_id[7] == 1) candidateId_fill @endif">1</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($user_id[1] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($user_id[2] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($user_id[3] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($user_id[4] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($user_id[5] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($user_id[6] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($user_id[7] == 2) candidateId_fill @endif">2</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($user_id[1] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($user_id[2] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($user_id[3] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($user_id[4] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($user_id[5] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($user_id[6] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($user_id[7] == 3) candidateId_fill @endif">3</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($user_id[1] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($user_id[2] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($user_id[3] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($user_id[4] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($user_id[5] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($user_id[6] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($user_id[7] == 4) candidateId_fill @endif">4</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($user_id[1] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($user_id[2] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($user_id[3] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($user_id[4] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($user_id[5] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($user_id[6] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($user_id[7] == 5) candidateId_fill @endif">5</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($user_id[1] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($user_id[2] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($user_id[3] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($user_id[4] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($user_id[5] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($user_id[6] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($user_id[7] == 6) candidateId_fill @endif">6</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($user_id[1] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($user_id[2] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($user_id[3] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($user_id[4] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($user_id[5] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($user_id[6] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($user_id[7] == 7) candidateId_fill @endif">7</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($user_id[1] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($user_id[2] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($user_id[3] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($user_id[4] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($user_id[5] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($user_id[6] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($user_id[7] == 8) candidateId_fill @endif">8</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($user_id[0] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($user_id[1] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($user_id[2] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($user_id[3] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($user_id[4] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($user_id[5] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($user_id[6] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($user_id[7] == 9) candidateId_fill @endif">9</div>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="omrSheetId_test">
                                              <div class="omrSheetDetailsTitle">TEST ID</div>
                                              <div class="omrSheetIdField">
                                                <div class="answerFillBoxAll">
                                                  <div class="answerFillBox">{{$exam_id[0]}}</div>
                                                  <div class="answerFillBox">{{$exam_id[1]}}</div>
                                                  <div class="answerFillBox">{{$exam_id[2]}}</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($exam_id[1] == 0) candidateId_fill @endif">0</div>
                                                  <div class="bubble @if($exam_id[2] == 0) candidateId_fill @endif">0</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($exam_id[1] == 1) candidateId_fill @endif">1</div>
                                                  <div class="bubble @if($exam_id[2] == 1) candidateId_fill @endif">1</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($exam_id[1] == 2) candidateId_fill @endif">2</div>
                                                  <div class="bubble @if($exam_id[2] == 2) candidateId_fill @endif">2</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($exam_id[1] == 3) candidateId_fill @endif">3</div>
                                                  <div class="bubble @if($exam_id[2] == 3) candidateId_fill @endif">3</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($exam_id[1] == 4) candidateId_fill @endif">4</div>
                                                  <div class="bubble @if($exam_id[2] == 4) candidateId_fill @endif">4</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($exam_id[1] == 5) candidateId_fill @endif">5</div>
                                                  <div class="bubble @if($exam_id[2] == 5) candidateId_fill @endif">5</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($exam_id[1] == 6) candidateId_fill @endif">6</div>
                                                  <div class="bubble @if($exam_id[2] == 6) candidateId_fill @endif">6</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($exam_id[1] == 7) candidateId_fill @endif">7</div>
                                                  <div class="bubble @if($exam_id[2] == 7) candidateId_fill @endif">7</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($exam_id[1] == 8) candidateId_fill @endif">8</div>
                                                  <div class="bubble @if($exam_id[2] == 8) candidateId_fill @endif">8</div>
                                                </div>
                                                <div class="answer-bubbles">
                                                  <div class="bubble @if($exam_id[0] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($exam_id[1] == 9) candidateId_fill @endif">9</div>
                                                  <div class="bubble @if($exam_id[2] == 9) candidateId_fill @endif">9</div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch">
                                          <div class="omrSheetDetailsTitle">Candidate Batch</div>
                                          <div class="omrSheetIdField"></div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch">
                                          <div class="omrSheetDetailsTitle">DECLARATION BY THE CANDIDATE</div>
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_batchText">{{$offline_exam->result_declaration}} </div>
                                            <div class="omrSheetId_batchText pt-5" style="font-weight: 600;">Signature with time (in running handwriting)</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch">
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_batchText pt-5" style="font-weight: 600;">Candidate’s Name (In running handwriting)</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch">
                                          <div class="omrSheetIdField"></div>
                                        </div>
                                      </div>
                                      <div class="col-8">
                                        <div class="omrSheet_scroll">
                                          <div class="omrSheet_ansAll">
                                            <div class="omrSheet_ruleText">SECTION-A Attempt all 35 Questions in each subject</div>
                                            <div class="omrSheet_ans">
                                              <div class="omrSheetIdInfo">
                                                <div class="omrSheetId_test">
                                                  <div class="omrSheetDetailsTitle">Q.NO.</div>
                                                  <div class="omrSheetIdField">
                                                    @foreach($answer_list1 as $value)
                                                      <div class="answer-bubbles">
                                                        <div class="bubble bubble_count">{{$value->question_count}}</div>
                                                      </div>
                                                    @endforeach
                                                    
                                                  </div>
                                                </div>
                                                <div class="omrSheetId_candidate">
                                                  <div class="omrSheetDetailsTitle">Answer</div>
                                                  <div class="omrSheetIdField">
                                                    @foreach($answer_list1 as $value)
                                                      <div class="answer-bubbles">
                                                        @if($value->class_name1 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name1}}" @if($value->answer == 1 || $value->user_answer == 1) onclick="openModal('{{$value->video_link}}');" @endif>1</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name1}}">1</a>
                                                        @endif
                                                        @if($value->class_name2 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name2}}" @if($value->answer == 2 || $value->user_answer == 2) onclick="openModal('{{$value->video_link}}');" @endif>2</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name2}}">2</a>
                                                        @endif
                                                        @if($value->class_name3 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name3}}" @if($value->answer == 3 || $value->user_answer == 3) onclick="openModal('{{$value->video_link}}');" @endif>3</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name3}}">3</a>
                                                        @endif
                                                        @if($value->class_name4 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name4}}" @if($value->answer == 4 || $value->user_answer == 4) onclick="openModal('{{$value->video_link}}');" @endif>4</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name4}}">4</a>
                                                        @endif
                                                      </div>
                                                    @endforeach
                                                    
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="omrSheetIdInfo">
                                                <div class="omrSheetId_test">
                                                  <div class="omrSheetDetailsTitle">Q.NO.</div>
                                                  <div class="omrSheetIdField">
                                                    @foreach($answer_list2 as $value)
                                                      <div class="answer-bubbles">
                                                        <div class="bubble bubble_count">{{$value->question_count}}</div>
                                                      </div>
                                                    @endforeach
                                                    
                                                  </div>
                                                </div>
                                                <div class="omrSheetId_candidate">
                                                  <div class="omrSheetDetailsTitle">Answer</div>
                                                  <div class="omrSheetIdField">
                                                    @foreach($answer_list2 as $value)
                                                      <div class="answer-bubbles">
                                                        @if($value->class_name1 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name1}}" @if($value->answer == 1 || $value->user_answer == 1) onclick="openModal('{{$value->video_link}}');" @endif>1</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name1}}">1</a>
                                                        @endif
                                                        @if($value->class_name2 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name2}}" @if($value->answer == 2 || $value->user_answer == 2) onclick="openModal('{{$value->video_link}}');" @endif>2</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name2}}">2</a>
                                                        @endif
                                                        @if($value->class_name3 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name3}}" @if($value->answer == 3 || $value->user_answer == 3) onclick="openModal('{{$value->video_link}}');" @endif>3</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name3}}">3</a>
                                                        @endif
                                                        @if($value->class_name4 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name4}}" @if($value->answer == 4 || $value->user_answer == 4) onclick="openModal('{{$value->video_link}}');" @endif>4</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name4}}">4</a>
                                                        @endif
                                                      </div>
                                                    @endforeach
                                                    
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="omrSheetIdInfo">
                                                <div class="omrSheetId_test">
                                                  <div class="omrSheetDetailsTitle">Q.NO.</div>
                                                  <div class="omrSheetIdField">
                                                    @foreach($answer_list3 as $value)
                                                      <div class="answer-bubbles">
                                                        <div class="bubble bubble_count">{{$value->question_count}}</div>
                                                      </div>
                                                    @endforeach
                                                    
                                                  </div>
                                                </div>
                                                <div class="omrSheetId_candidate">
                                                  <div class="omrSheetDetailsTitle">Answer</div>
                                                  <div class="omrSheetIdField">
                                                    @foreach($answer_list3 as $value)
                                                      <div class="answer-bubbles">
                                                        @if($value->class_name1 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name1}}" @if($value->answer == 1 || $value->user_answer == 1) onclick="openModal('{{$value->video_link}}');" @endif>1</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name1}}">1</a>
                                                        @endif
                                                        @if($value->class_name2 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name2}}" @if($value->answer == 2 || $value->user_answer == 2) onclick="openModal('{{$value->video_link}}');" @endif>2</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name2}}">2</a>
                                                        @endif
                                                        @if($value->class_name3 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name3}}" @if($value->answer == 3 || $value->user_answer == 3) onclick="openModal('{{$value->video_link}}');" @endif>3</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name3}}">3</a>
                                                        @endif
                                                        @if($value->class_name4 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name4}}" @if($value->answer == 4 || $value->user_answer == 4) onclick="openModal('{{$value->video_link}}');" @endif>4</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name4}}">4</a>
                                                        @endif
                                                      </div>
                                                    @endforeach
                                                    
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="omrSheetIdInfo">
                                                <div class="omrSheetId_test">
                                                  <div class="omrSheetDetailsTitle">Q.NO.</div>
                                                  <div class="omrSheetIdField">
                                                    @foreach($answer_list4 as $value)
                                                      <div class="answer-bubbles">
                                                        <div class="bubble bubble_count">{{$value->question_count}}</div>
                                                      </div>
                                                    @endforeach
                                                    
                                                  </div>
                                                </div>
                                                <div class="omrSheetId_candidate">
                                                  <div class="omrSheetDetailsTitle">Answer</div>
                                                  <div class="omrSheetIdField">

                                                    @foreach($answer_list4 as $value)
                                                      <div class="answer-bubbles">
                                                        @if($value->class_name1 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name1}}" @if($value->answer == 1 || $value->user_answer == 1) onclick="openModal('{{$value->video_link}}');" @endif>1</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name1}}">1</a>
                                                        @endif
                                                        @if($value->class_name2 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name2}}" @if($value->answer == 2 || $value->user_answer == 2) onclick="openModal('{{$value->video_link}}');" @endif>2</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name2}}">2</a>
                                                        @endif
                                                        @if($value->class_name3 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name3}}" @if($value->answer == 3 || $value->user_answer == 3) onclick="openModal('{{$value->video_link}}');" @endif>3</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name3}}">3</a>
                                                        @endif
                                                        @if($value->class_name4 != 'candidateId_fill_red')
                                                          <div class="bubble {{$value->class_name4}}" @if($value->answer == 4 || $value->user_answer == 4) onclick="openModal('{{$value->video_link}}');" @endif>4</div>
                                                        @else
                                                          <a href="{{route('mistake_monitor_input',['user_exam_id'=>$offline_exam->user_exam_id,'id'=>$value->exam_result_id])}}" class="bubble {{$value->class_name4}}">4</a>
                                                        @endif
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
                  </div>
              </div>
          </div>
      </div>
    </section>
    <!-- dashboard end -->


    <!-- call to action -->
    <div class="calltoaction">
        <a href="#"><img src="{{ asset('') }}web/images/icons/call_calltoa.png" alt="" class="img-fluid"></a>
        <a href="#"><img src="{{ asset('') }}web/images/icons/calender_calltoa.png" alt="" class="img-fluid"></a>
        <a href="#"><img src="{{ asset('') }}web/images/icons/whatsapa_calltoa.png" alt="" class="img-fluid"></a>
    </div>
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i
            class="bi bi-arrow-up"></i></a>


    <!-- popup -->
    <div class="modal fade" id="examVideo">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <img src="images/examVideoClose_ic.png" class="img-fluid examVideoClose_ic" data-bs-dismiss="modal" alt="">
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
          <iframe id="modal_video" width="100%" height="420" src=""></iframe>
        </div>
      </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
    <script src="{{ asset('') }}web/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('') }}web/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('') }}web/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <!-- <script src="{{ asset('') }}web/js/jquery.bxslider.js"></script> -->

    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>

    <script>
      $('#examVideo').on('hidden.bs.modal', function () {
        $("#examVideo iframe").attr("src", $("#examVideo iframe").attr("src"));
      });
    </script>
    
    

    <script type="text/javascript">
      function openModal(video_link) {
        document.getElementById("modal_video").src = video_link;
        $("#examVideo").modal("show");
      }
    </script>
</body>

</html>