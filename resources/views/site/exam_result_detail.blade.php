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
                                  <div class="resultOmrSheetBrand">
                                    <img src="{{ asset('') }}web/images/logo-dashboard.png" alt="Rank Pro" class="resultOmrSheetLogo">
                                    @if(!empty($omr_scorecard))
                                      <div class="omrScoreCard" aria-label="Score card">
                                        <div class="omrScoreCard__score">
                                          <span class="omrScoreCard__scoreLabel">Score</span>
                                          <span class="omrScoreCard__scoreValue">{{ $omr_scorecard['score'] }}<small>/{{ $omr_scorecard['total_mark'] }}</small></span>
                                          @if($omr_scorecard['percentage'] !== null && $omr_scorecard['percentage'] !== '')
                                            <span class="omrScoreCard__pct">{{ rtrim(rtrim(number_format((float) $omr_scorecard['percentage'], 2, '.', ''), '0'), '.') }}%</span>
                                          @endif
                                        </div>
                                        <div class="omrScoreCard__stats">
                                          <div class="omrScoreCard__stat omrScoreCard__stat--answered">
                                            <span class="omrScoreCard__num">{{ $omr_scorecard['answered'] }}</span>
                                            <span class="omrScoreCard__lbl">Answered</span>
                                          </div>
                                          <div class="omrScoreCard__stat omrScoreCard__stat--correct">
                                            <span class="omrScoreCard__num">{{ $omr_scorecard['correct'] }}</span>
                                            <span class="omrScoreCard__lbl">Correct</span>
                                          </div>
                                          <div class="omrScoreCard__stat omrScoreCard__stat--wrong">
                                            <span class="omrScoreCard__num">{{ $omr_scorecard['wrong'] }}</span>
                                            <span class="omrScoreCard__lbl">Wrong</span>
                                          </div>
                                          <div class="omrScoreCard__stat omrScoreCard__stat--skipped">
                                            <span class="omrScoreCard__num">{{ $omr_scorecard['skipped'] }}</span>
                                            <span class="omrScoreCard__lbl">Skipped</span>
                                          </div>
                                          <div class="omrScoreCard__stat omrScoreCard__stat--reported">
                                            <span class="omrScoreCard__num">{{ $omr_scorecard['reported'] }}</span>
                                            <span class="omrScoreCard__lbl">Reported</span>
                                          </div>
                                        </div>
                                      </div>
                                    @endif
                                  </div>
                                  <div class="resultOmrTitle">{{$offline_exam->result_title}}</div>
                                  <div class="resultOmrText">{{$offline_exam->result_description}}</div>
                                  <div class="resultOmrSubject">{{$offline_exam->name}}</div>

                                  <div class="omrSheetDetails">
                                    <div class="row">
                                      <div class="col-4">
                                        <div class="omrSheetIdInfo_scroll">
                                          <div class="omrSheetIdInfo {{ !empty($is_custom_test) ? 'omrSheetIdInfo--noTestId' : '' }}">
                                            <div class="omrSheetId_candidate">
                                              <div class="omrSheetDetailsTitle">CANDIDATE ID</div>
                                              @include('site.include.omr_digit_grid', ['code' => $user_id, 'length' => 10])
                                            </div>
                                            @if(empty($is_custom_test))
                                              <div class="omrSheetId_test">
                                                <div class="omrSheetDetailsTitle">TEST ID</div>
                                                @include('site.include.omr_digit_grid', ['code' => $exam_id, 'length' => 3])
                                              </div>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch">
                                          <div class="omrSheetDetailsTitle">Candidate's Batch</div>
                                          <div class="omrSheetIdField omrSheetId_batchValue">{{ $student_batch ?? '' }}</div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch">
                                          <div class="omrSheetDetailsTitle">DECLARATION BY THE CANDIDATE</div>
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_batchText">{{ $declaration_text }}</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch omrSheetId_sign">
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_signLine"></div>
                                            <div class="omrSheetId_batchText omrFieldLabel">Signature with time (in running handwriting)</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch omrSheetId_sign">
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_filledValue">{{ trim(($student->first_name ?? '').' '.($student->last_name ?? '')) }}</div>
                                            <div class="omrSheetId_batchText omrFieldLabel">Candidate's Name (In running handwriting)</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch omrSheetId_sign">
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_filledValue">{{ $student->address ?? '' }}</div>
                                            <div class="omrSheetId_batchText omrFieldLabel">City's Name</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch omrSheetId_sign">
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_signLine"></div>
                                            <div class="omrSheetId_batchText omrFieldLabel">Hall No./Room</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch omrSheetId_sign">
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_filledValue">{{ $student->father_full_name ?? '' }}</div>
                                            <div class="omrSheetId_batchText omrFieldLabel">Father's Name (In running handwriting)</div>
                                          </div>
                                        </div>

                                        <div class="omrSheetId_candidate omrSheetId_batch omrSheetId_sign">
                                          <div class="omrSheetIdField">
                                            <div class="omrSheetId_signLine"></div>
                                            <div class="omrSheetId_batchText omrFieldLabel">Signature of the Invigilator's with time</div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-8">
                                        <div class="omrSheet_scroll">
                                          <div class="omrSheet_ansAll">
                                            <div class="omrSheet_ans">
                                              @include('site.include.omr_answer_column', ['answers' => $answer_list1, 'userExamId' => $offline_exam->user_exam_id])
                                              @include('site.include.omr_answer_column', ['answers' => $answer_list2, 'userExamId' => $offline_exam->user_exam_id])
                                              @include('site.include.omr_answer_column', ['answers' => $answer_list3, 'userExamId' => $offline_exam->user_exam_id])
                                              @include('site.include.omr_answer_column', ['answers' => $answer_list4, 'userExamId' => $offline_exam->user_exam_id])
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
