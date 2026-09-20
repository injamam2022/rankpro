<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RankPro - Online Examination</title>
    <link rel="shortcut icon" href="{{ asset('') }}exam/img/fav-icon.png">
    <link rel="stylesheet" href="{{ asset('') }}exam/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/vendor/animate/animate.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/css/util.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/css/main.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/css/modalanimate.css">
    <link rel="stylesheet" href="{{ asset('') }}exam/css/style.css">
    <link rel="stylesheet" href="{{ asset('') }}web/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto+Condensed:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('site.include.head_meta')
    <style>
        /* File 2 visual language, while retaining File 1 functionality/IDs */
        html, body { margin:0 !important; padding:0 !important; }
        html { margin-top:0 !important; padding-top:0 !important; }
        body#mainBody { margin-top:0 !important; padding-top:0 !important; }
        body#mainBody > #header { margin-top:0 !important; padding-top:0 !important; top:0 !important; }
        body#mainBody {
            background:#f6f8fc !important;
            font-family:'Poppins',sans-serif;
            color:#17233b;
        }
        #header {
            height:78px;
            margin:0 !important;
            padding:0 !important;
            background:#fff;
            border-bottom:1px solid #e8ecf3;
            box-shadow:0 1px 8px rgba(20,35,60,.04);
            position:relative;
            z-index:1000;
        }
        #header .container-fluid { height:100%; }
        .mainHeader {
            height:100%;
            display:flex;
            align-items:center;
            padding:0 32px;
            gap:24px;
        }
        #mainLogo { max-width:145px; max-height:42px; }
        .logo { flex:0 0 185px; }
        .questionCount {
            order:2;
            font-size:14px;
            color:#707b91;
            white-space:nowrap;
        }
        .questionCount b { color:#17233b; }
        .exampTime {
            order:3;
            display:flex;
            align-items:center;
            gap:10px;
            font-size:14px;
            color:#7a8498;
            margin-left:10px;
        }
        .blueTime {
            position:relative;
            width:182px;
            height:35px;
            border-radius:10px;
            background:#dfe8ff;
            overflow:hidden;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .blueTime > div {
            position:absolute;
            inset:0 auto 0 0;
            background:#d3e0ff;
            z-index:1;
        }
        .blueTime span {
            position:relative;
            z-index:2;
            color:#3561ff;
            font-size:18px;
            letter-spacing:2px;
        }
        .topExampButtons { order:4; margin-left:auto; }
        .topExampButtonsResponsive { display:flex; gap:28px; align-items:center; }
        .topExampButtons a {
            min-width:165px;
            height:35px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:9px;
            font-size:15px;
            font-weight:700;
            text-decoration:none !important;
            text-transform:uppercase;
        }
        .topExampButtons .redButton {
            background:#ff1010;
            color:#fff;
            border:1px solid #ff1010;
        }
        .topExampButtons .whiteButton {
            background:#fff;
            color:#151515;
            border:1px solid #3561ff;
        }
        .avtar_demo { order:5; margin-left:8px; }
        .avtar_profile {
            width:58px;
            height:58px;
            border-radius:50%;
            overflow:hidden;
            border:1px solid #dce2ec;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }
        .avtar_profile img { width:100%; height:100%; object-fit:cover; }

        #dashboardBody {
            background:#f5f7fb !important;
            padding:42px 0 50px !important;
            min-height:calc(100vh - 78px);
            margin-top: 0px;
        }
        .exam-layout {
            width:100%;
            max-width:1440px;
            margin:0 auto;
            padding:0 20px;
            position:relative;
        }
        .exam-main {
            width:100%;
            padding-right:340px;
            position:relative;
        }
        .exam-content {
            width:100%;
            background:#fff;
            border:1px solid #e5e9f0;
            border-radius:12px;
            padding:28px 30px 26px;
            box-shadow:0 3px 18px rgba(20,34,60,.05);
        }
        .exam-heading {
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:25px;
        }
        .exam-heading h5 {
            margin:0;
            font-size:22px;
            font-weight:700;
            color:#12233d;
        }
        .exam-heading .subject {
            background:#f4f6fa;
            color:#7b8598;
            padding:10px 17px;
            border-radius:24px;
            font-size:14px;
        }
        /* Question box follows File 2's question-text/q-no/q-only structure */
        .questionDiv {
            margin:0 0 20px;
            padding:26px 28px 27px;
            min-height:145px;
            border:1px solid #e1e6ee;
            border-radius:12px;
            background:#fff;
        }
        .question-meta { display:none; }
        .questionDiv .question-text {
            display:flex;
            align-items:flex-start;
            gap:8px;
            width:100%;
        }
        .questionDiv .q-no {
            flex:0 0 auto;
            font-size:17px;
            line-height:1.75;
            font-weight:600;
            color:#17243a;
        }
        #question_div {
            margin:0;
            font-size:17px;
            line-height:1.75;
            font-weight:500;
            color:#17243a;
            word-break:break-word;
            width:100%;
        }
        #question_div img { max-width:100%; height:auto; }
        .optionsDiv { margin:0; }
        .optionsDiv > .row { margin:0 -7px; }
        .optionsDiv .col-lg-6 { padding:0 7px; }
        .option-card {
            position:relative;
            min-height:94px;
            margin-bottom:14px;
            padding:18px 20px;
            display:flex;
            align-items:flex-start;
            gap:13px;
            border:1px solid #e0e5ed;
            border-radius:12px;
            background:#fff;
            cursor:pointer;
            transition:border-color .15s, background .15s, box-shadow .15s;
        }
        .option-card:hover { border-color:#a9b9ec; background:#fafbff; }
        .option-card > input {
            flex:0 0 auto;
            width:25px;
            height:25px;
            margin:2px 0 0;
            appearance:none;
            -webkit-appearance:none;
            border:2px solid #cbd4e1;
            border-radius:50%;
            background:#fff;
            cursor:pointer;
            position:relative;
        }
        .option-card > input:checked {
            border-color:#3561ff;
            background:#3561ff;
        }
        .option-card > input:checked:after {
            content:'';
            position:absolute;
            width:9px;
            height:9px;
            border-radius:50%;
            background:#fff;
            left:6px;
            top:6px;
        }
        .option-card:has(> input:checked) {
            border-color:#3561ff;
            background:#f7f9ff;
            box-shadow:0 0 0 1px #3561ff inset;
        }
        .option-card .option-label {
            flex:1;
            min-width:0;
            margin:0;
            cursor:pointer;
            font-size:16px;
            line-height:1.55;
            color:#17243b;
            font-weight:400;
        }
        .option-card .option-label span { display:block; }
        .option-card .option-label img { max-width:100%; height:auto; }
        .exam-footer-actions {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:15px;
            margin-top:8px;
            padding-top:4px;
        }
        .buttonSetBottom { display:flex; align-items:center; gap:12px; }
        .buttonSetBottom a {
            min-width:120px;
            height:44px;
            padding:0 22px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:7px;
            border-radius:7px;
            text-decoration:none !important;
            font-size:14px;
            font-weight:600;
        }
        .buttonSetBottom .yellow { background:#fff05a; color:#111; border:1px solid #e7d93e; }
        .buttonSetBottom .saffron { background:#ffe1c7; color:#111; border:1px solid #f2c69f; }
        .buttonSetBottom .blue { background:#06366b; color:#fff; border:1px solid #06366b; }
        .buttonSetBottom .grey { background:#fff; color:#333; border:1px solid #d8dee8; }
        .buttonSetBottom .green { background:#08aa58; color:#fff; border:1px solid #08aa58; }

        .sideExampOptions {
            position:absolute !important;
            top:0 !important;
            right:20px !important;
            width:320px !important;
            min-height:100%;
            background:#fff !important;
            border:1px solid #e4e8ef !important;
            border-radius:12px !important;
            box-shadow:0 3px 18px rgba(20,34,60,.05) !important;
            overflow:hidden;
            z-index:10;
        }
        .sideExampOptions .optionOpener { display:none !important; }
        .sideExampOptions > ul {
            list-style:none;
            grid-template-columns:1fr 1fr;
            margin:0 !important;
            padding:0 !important;
            border-bottom:1px solid #dfe4eb;
        }
        .sideExampOptions > ul li { margin:0 !important; min-width:0; }
        .sideExampOptions > ul li a {
            min-height:62px;
            display:flex !important;
            align-items:center;
            justify-content:flex-start;
            gap:12px;
            padding:5px 16px !important;
            border:0 !important;
            border-bottom:1px solid #edf0f4 !important;
            background:#fff !important;
            color:#5f6879 !important;
            font-size:12px !important;
            font-weight:600;
            text-transform:uppercase;
            text-decoration:none !important;
        }
       
        .sideExampOptions > ul li.whiteLink a.active { color:#315eff !important; border-left:4px solid #55efad !important; }
        .sideExampOptions .greenLink a span { color:#fff; }
        .sideExampOptions .graylink a span { color:#596273; }
        .sideExampOptions .redlink a span { color:#fff; }
        .sideExampOptions .yellowlink a span { color:#222; }
        .questionBankList {
            height:auto !important;
            max-height:520px !important;
            overflow-y:auto;
            padding:26px 22px 22px !important;
        }
        .questionBankList:before {
            content:'QUESTION BANK';
            display:block;
            margin:0 0 16px;
            color:#17243a;
            font-size:14px;
            font-weight:700;
        }
        .questionBankList a {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:42px;
            height:38px;
            margin:0 6px 8px 0;
            border:1px solid #bfe8d1;
            border-radius:7px;
            background:#e7f9ef;
            color:#15925d;
            font-size:12px;
            font-weight:600;
            text-decoration:none !important;
        }
        .questionBankList a.active,
        .questionBankList a.current { background:#3561ff; border-color:#3561ff; color:#fff; }
        .questionBankList a.gray { background:#eef0f4; border-color:#dfe3e8; color:#697286; }
        footer { display:none; }
        @media (max-width:1200px) {
            .exam-layout { padding:0 15px; }
            .exam-main { padding-right:300px; }
            .sideExampOptions { width:285px !important; right:15px !important; }
        }
        @media (max-width:991px) {
            #dashboardBody { padding:25px 0 35px !important; }
            .exam-main { padding-right:0; }
            .sideExampOptions {
                position:relative !important;
                top:auto !important;
                right:auto !important;
                width:100% !important;
                min-height:0;
                margin-bottom:18px;
            }
            .questionBankList { max-height:220px !important; }
        }
        @media (max-width:767px) {
            .exam-layout { padding:0 10px; }
            .exam-content { padding:18px 14px; }
            .exam-heading h5 { font-size:19px; }
            .questionDiv { padding:18px; }
            .questionDiv .q-no, #question_div { font-size:15px; }
            .option-card { min-height:72px; padding:14px; }
            .option-card .option-label { font-size:14px; }
            .exam-footer-actions { flex-direction:column; align-items:stretch; }
            .exam-footer-actions .buttonSetBottom { justify-content:center; flex-wrap:wrap; }
            .buttonSetBottom a { min-width:105px; }
        }
        .proctoring-gate, .proctoring-warning {
            position:fixed; inset:0; z-index:99999;
            background:rgba(12,18,32,.94);
            display:flex; align-items:center; justify-content:center;
            padding:24px;
            pointer-events:auto;
        }
        .proctoring-card {
            width:100%; max-width:560px; background:#fff; border-radius:16px;
            padding:28px 28px 24px; text-align:center;
            box-shadow:0 18px 50px rgba(0,0,0,.25);
        }
        .proctoring-card h3 { font-size:22px; margin-bottom:8px; color:#17233b; }
        .proctoring-card p, .proctoring-card li { color:#4d5a73; font-size:14px; text-align:left; }
        .proctoring-card ul { padding-left:18px; margin:14px 0 18px; }
        .proctoring-actions { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
        .proctoring-actions button {
            min-width:160px; height:42px; border:0; border-radius:10px;
            font-weight:700; color:#fff; background:#3561ff; cursor:pointer;
        }
        .proctoring-actions button:disabled { background:#9aa7c7; cursor:not-allowed; }
        .proctoring-actions .secondary { background:#17233b; }
        .proctoring-pip {
            display:none; position:fixed; right:18px; bottom:18px; z-index:100000;
            width:168px; height:126px; border-radius:12px; overflow:hidden;
            border:3px solid #fff; box-shadow:0 8px 24px rgba(0,0,0,.25); background:#000;
        }
        body.exam-proctored-active .proctoring-pip { display:block; }
        body.exam-proctored-active { user-select:none; }
        #proctoringVideo { width:100%; height:100%; object-fit:cover; }
        #proctoringFaceStatus {
            position:absolute; left:0; right:0; bottom:0;
            background:#2e7d32; color:#fff; font-size:11px;
            padding:4px 6px; text-align:center; font-weight:600;
        }
        .proctoring-badge {
            display:none; align-items:center; gap:8px; margin-left:12px;
            background:#fff3e0; color:#c2410c; border:1px solid #ffd7b0;
            border-radius:8px; padding:6px 10px; font-size:12px; font-weight:600;
        }
        body.exam-proctored-active .proctoring-badge { display:inline-flex; }
    </style>
</head>
<body id="mainBody" @if(!empty($exam_detail->is_proctored)) class="exam-proctored" @endif>
@include('site.include.body_meta')
@if(!empty($exam_detail->is_proctored))
<div id="proctoringGate" class="proctoring-gate">
  <div class="proctoring-card">
    <h3>This exam is proctored</h3>
    <p>Before you start, allow camera access and stay in fullscreen for the full duration.</p>
    <ul>
      <li>Keep the camera on and stay in your seat. Looking at the questions is fine.</li>
      <li>If you leave the seat or cover the camera, a warning appears. Sit back down to continue.</li>
      <li>Sitting normally will not cancel the exam.</li>
      <li>Do not switch tabs, windows, or leave fullscreen. Esc exits fullscreen and locks the exam until you click Return to Exam.</li>
      <li>Copy, paste, and right-click are disabled.</li>
      <li>After {{ $exam_detail->proctoring_max_violations ?? 5 }} other warnings, the exam is submitted automatically.</li>
    </ul>
    <p id="proctoringCameraStatus">Camera is not connected yet.</p>
    <div class="proctoring-actions">
      <button type="button" id="proctoringCameraBtn" class="secondary">Allow Camera</button>
      <button type="button" id="proctoringStartBtn" disabled>Enter Fullscreen &amp; Start</button>
    </div>
  </div>
</div>
<div id="proctoringWarning" class="proctoring-warning" style="display:none;">
  <div class="proctoring-card">
    <h3>Proctoring warning</h3>
    <p id="proctoringWarningText"></p>
    <div class="proctoring-actions">
      <button type="button" id="proctoringResumeBtn">Return to Exam</button>
    </div>
  </div>
</div>
<div class="proctoring-pip">
  <video id="proctoringVideo" autoplay playsinline muted></video>
  <div id="proctoringFaceStatus">Checking face...</div>
</div>
<canvas id="proctoringCanvas" style="display:none;"></canvas>
@endif
<header id="header">
  <div class="container-fluid h-100">
    <div class="mainHeader">
      <div class="logo dashboardMenuPL" id="logoContain"><a href="javascript:void(0);"><img src="{{ asset('') }}web/images/logo.png" class="img-fluid" alt="RankPro" id="mainLogo"></a></div>
      <div class="questionCount"><b id="current_question_view"></b> questions left of <b><?php echo count($question_list);?></b></div>
      <div class="exampTime" id="clockdiv"><div class="blueTime"><div id="main_timer_div" style="width:0%;"></div><span><b class="main_hours"></b><b class="main_minutes"></b><b class="main_seconds"></b></span></div><span>of <?php echo $exam_detail->total_time_for_exam;?> min</span></div>
      @if(!empty($exam_detail->is_proctored))
      <div class="proctoring-badge">Proctored · warnings <span id="proctoringViolationCount">0</span>/{{ $exam_detail->proctoring_max_violations ?? 5 }}</div>
      @endif
      <div class="topExampButtons"><div class="topExampButtonsResponsive"><a href="javascript:void(0);" class="redButton button" onclick="examExamTimer(1);"><i class="fa fa-power-off"></i> &nbsp;&nbsp;&nbsp;End Test</a><a href="javascript:void(0);" class="whiteButton" onclick="clickReported();"><i class="fa fa-flag"></i><i class="fa fa-check" aria-hidden="true" id="reported_tick_icon_id" style="display:none;"></i> &nbsp;&nbsp;&nbsp;Report</a></div></div>
      <!-- <div class="avtar_demo"><a href="javascript:void(0);"><div class="avtar_profile" id="avtarProfile"><img src="{{ asset('') }}exam/img/avtar_demo.png" class="img-fluid" alt=""></div></a></div> -->
    </div>
  </div>
</header>
<section id="dashboardBody">
  <div class="container-fluid exam-layout">
    <div class="exam-main">
      <aside class="sideExampOptions iamClose">
        <a href="javascript:void(0);" class="optionOpener" aria-label="Question palette"></a>
        <ul>
          <li class="whiteLink active" id="total_question_parent_div">
            <a href="javascript:void(0);" onclick="changeRightMenuQuestion('all')" class="active" id="total_question_child_div">
              <span id="total_question_count">&nbsp;</span>All
            </a>
          </li>
          <li class="greenLink" id="total_answered_parent_div">
            <a href="javascript:void(0);" onclick="changeRightMenuQuestion('answered')" id="total_answered_child_div">
              <span id="total_answered_count">&nbsp;</span>Answered
            </a>
          </li>
          <li class="graylink" id="total_skipped_parent_div">
            <a href="javascript:void(0);" onclick="changeRightMenuQuestion('skipped')" id="total_skipped_child_div">
              <span id="total_skipped_count">&nbsp;</span>Skipped
            </a>
          </li>
          <li class="redlink" id="total_reported_parent_div">
            <a href="javascript:void(0);" onclick="changeRightMenuQuestion('reported')" id="total_reported_child_div">
              <span id="total_reported_count">&nbsp;</span>Reported
            </a>
          </li>
          <li class="yellowlink" id="total_review_later_parent_div">
            <a href="javascript:void(0);" onclick="changeRightMenuQuestion('review_later')" id="total_review_later_child_div">
              <span id="total_review_later_count">&nbsp;</span>Review Later
            </a>
          </li>
        </ul>
        <div class="questionBankList" id="question_list"></div>
      </aside>
      <main class="exam-content">
        <div class="exam-heading"><h5>Online Examination</h5><span class="subject">Question &amp; Answer</span></div>
        <div class="exampTime" id="sclockdiv" style="display:none"><div class="blueTime"><div style="width:50%;"></div><span><b class="minutes"></b><b class="seconds"></b></span></div></div>
        <div class="questionDiv">
          <div class="question-text">
            <div class="q-no"></div>
            <div class="q-only" id="question_div"></div>
          </div>
        </div>
        <div class="optionsDiv"><div class="row">
          <div class="col-lg-6 col-md-6">
            <div class="form-check option-card" id="option1_card">
              <input class="form-check-input" type="radio" name="radio" id="option1" onchange="selectAnswerOption(1)">
              <label class="form-check-label option-label" for="option1"><span id="option1_div"></span></label>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="form-check option-card" id="option2_card">
              <input class="form-check-input" type="radio" name="radio" id="option2" onchange="selectAnswerOption(2)">
              <label class="form-check-label option-label" for="option2"><span id="option2_div"></span></label>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="form-check option-card" id="option3_card">
              <input class="form-check-input" type="radio" name="radio" id="option3" onchange="selectAnswerOption(3)">
              <label class="form-check-label option-label" for="option3"><span id="option3_div"></span></label>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="form-check option-card" id="option4_card">
              <input class="form-check-input" type="radio" name="radio" id="option4" onchange="selectAnswerOption(4)">
              <label class="form-check-label option-label" for="option4"><span id="option4_div"></span></label>
            </div>
          </div>
        </div></div>
        <div class="exam-footer-actions">
          <div class="buttonSetBottom"><a href="javascript:void(0);" class="yellow" onclick="clickReviewLater();"><i class="fa fa-check" id="review_later_tick_icon_id" style="display:none"></i> Review Later</a></div>
          <div class="buttonSetBottom"><a href="javascript:void(0);" class="saffron" id="previous_button" onclick="clickPreviousButton();"><i class="fa fa-long-arrow-left"></i> Previous</a><a href="javascript:void(0);" class="blue" id="save_button" onclick="clickSaveButton();"><i class="fa fa-check" id="save_tick_icon_id" style="display:none"></i> Save</a><a href="javascript:void(0);" class="grey" id="skip_button" onclick="clickSkipButton();" style="display:block;">Skip <i class="fa fa-long-arrow-right"></i></a><a href="javascript:void(0);" class="green" id="next_button" onclick="clickNextButton();" style="display:none;">Next <i class="fa fa-long-arrow-right"></i></a></div>
        </div>
      </main>
    </div>
  </div>
</section>
<footer><div class="container-fluid"><ul class="bottom-footer list-unstyled mb-0"><li class="d-inline-block">&copy; Copyright <span id="footerYear"></span> RankPro. All Rights Reserved.</li><li class="d-inline-block mx-4">|</li><li class="d-inline-block">Developed by <a href="http://zabingo.com/" target="_blank">Zabingo Softwares (www.zabingo.com)</a></li></ul></div></footer>
<div id="retest" class="modalStyle"><div class="modal-background"><div class="login_card retest"><div class="card modal"><div class="card-header greenHeader">END TEST <img class="closeModel img-fluid" onclick="cancalTestModalClick();" src="{{ asset('') }}exam/img/close.png" alt="Close"></div><div class="card-body whiteBody"><ul><li>Questions may have negative marks, be careful while attempting a question.</li><li>Keep an eye on timer, you need to finish it before time ends if there is time limit.</li><li>Questions can be multiple choice or single choice, need to answer appropriately.</li><li>Time is calculated once you start the test, if you leave in middle that time will be considered as well.</li></ul>@if(empty($exam_detail->is_proctored))<a href="javascript:void(0);" class="orengeButton" onclick="saveTestModalClick();">Save</a>@endif<a href="javascript:void(0);" class="orengeButton" style="background-color:#ff0000;" onclick="endTestModalClick();">End Test</a></div></div></div></div></div>
<div id="exam_end_timer" class="modalStyle"><div class="modal-background"><div class="login_card retest"><div class="card modal"><div class="card-header greenHeader">END TEST</div><div class="card-body whiteBody"><ul><li>Questions may have negative marks, be careful while attempting a question.</li><li>Keep an eye on timer, you need to finish it before time ends if there is time limit.</li><li>Questions can be multiple choice or single choice, need to answer appropriately.</li><li>Time is calculated once you start the test, if you leave in middle that time will be considered as well.</li></ul><a href="javascript:void(0);" class="orengeButton" onclick="endTestModalClick();">End Test</a></div></div></div></div></div>

    <script type="text/javascript">

      function renderQuestionTime(){
        return globalData.exam_detail.total_time_for_exam * 60 * 1000;
      }

      var globalData = {
        "question_list" : <?php echo json_encode($question_list);?>,
        "glb_question_list" : <?php echo json_encode($question_list);?>,
        "exam_detail" : <?php echo json_encode($exam_detail);?>,
        "question_index": <?php echo ($user_exam_detail->question_number)?$user_exam_detail->question_number:0;?>,
        "current_question_time":0,
        "is_current_option_selected":0,
        "questionTimeInterval":'',
      }

      globalData.exam_time = renderQuestionTime();
      globalData.time_per_question = globalData.exam_time/globalData.exam_detail.no_of_question;
      globalData.total_time = <?php echo ($user_exam_detail->total_time)?$user_exam_detail->total_time:0;?>;

      console.log(globalData);
    </script>

   <!--===============================================================================================-->
     <script src="{{ asset('') }}exam/vendor/jquery/jquery-3.2.1.min.js"></script>
     <!-- <script src="js/jquery-3.2.1.slim.min.js"></script> -->
     <!--===============================================================================================-->
       <script src="{{ asset('') }}exam/vendor/animsition/js/animsition.min.js"></script>
     <!--===============================================================================================-->
       <script src="{{ asset('') }}exam/vendor/bootstrap/js/popper.js"></script>
       <script src="{{ asset('') }}exam/vendor/bootstrap/js/bootstrap.min.js"></script>
     <!--===============================================================================================-->
       <script src="{{ asset('') }}exam/vendor/select2/select2.min.js"></script>
     <!--===============================================================================================-->
       <script src="{{ asset('') }}exam/vendor/daterangepicker/moment.min.js"></script>
       <script src="{{ asset('') }}exam/vendor/daterangepicker/daterangepicker.js"></script>
     <!--===============================================================================================-->
     <script src="{{ asset('') }}exam/js/modalAnimate.js"></script>

    
    <script>
      $(document).ready(function(){
        $('.optionOpener').click(function(e) {
          e.preventDefault();
          $('.sideExampOptions').toggleClass('iamOpen');
          $('.sideExampOptions').toggleClass('iamClose');
        });

        renderQuestionList(globalData.question_list,true);
        renderOuestionCount();
        renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);

        
        var winHeight = $(document).height();
        console.log(winHeight);
        //$('.setHtight').css('min-height',winHeight-180);
        var ulHeight = $('.sideExampOptions ul').height();
        console.log(ulHeight);
        $('.questionBankList').css('height',winHeight-ulHeight-200);

      });
    </script>
   

    <script>
      function onclickQuestionList(question_index){
        globalData.question_index = question_index;
        renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
      }

      function changeRightMenuQuestion(type){
        document.getElementById('total_question_parent_div').classList.remove('active');
        document.getElementById('total_question_child_div').classList.remove('active');
        document.getElementById('total_answered_parent_div').classList.remove('active');
        document.getElementById('total_answered_child_div').classList.remove('active');
        document.getElementById('total_skipped_parent_div').classList.remove('active');
        document.getElementById('total_skipped_child_div').classList.remove('active');
        document.getElementById('total_reported_parent_div').classList.remove('active');
        document.getElementById('total_reported_child_div').classList.remove('active');
        document.getElementById('total_review_later_parent_div').classList.remove('active');
        document.getElementById('total_review_later_child_div').classList.remove('active');
        if(type == "all"){
          document.getElementById('total_question_parent_div').classList.add('active');
          document.getElementById('total_question_child_div').classList.add('active');
        }else if(type == "answered"){
          document.getElementById('total_answered_parent_div').classList.add('active');
          document.getElementById('total_answered_child_div').classList.add('active');
        }else if(type == "skipped"){
          document.getElementById('total_skipped_parent_div').classList.add('active');
          document.getElementById('total_skipped_child_div').classList.add('active');
        }else if(type == "reported"){
          document.getElementById('total_reported_parent_div').classList.add('active');
          document.getElementById('total_reported_child_div').classList.add('active');
        }else if(type == "review_later"){
          document.getElementById('total_review_later_parent_div').classList.add('active');
          document.getElementById('total_review_later_child_div').classList.add('active');
        }

        globalData.temp_question_list = globalData.question_list.filter(function(item) {
          var condtion;
          if(type == "all"){
            condtion = true;
          }else if(type == "answered"){
            console.log(item.answer);
            if(item.answer){
              condtion = true;
            }else{
              condtion = false;
            }
          }else if(type == "skipped"){
            if(item.answer == 0){
              condtion = true;
            }else{
              condtion = false;
            }
          }else if(type == "reported"){
            if(item.reported){
              condtion = true;
            }else{
              condtion = false;
            }
          }else if(type == "review_later"){
            if(item.review_later){
              condtion = true;
            }else{
              condtion = false;
            }
          }
          console.log(type);
          console.log(condtion);
          return condtion;
        });
        renderQuestionList(globalData.temp_question_list,0);
      }

      function renderOuestionCount(){
        var total_question_count = 0;
        var total_answered_count = 0;
        var total_skipped_count = 0;
        var total_reported_count = 0;
        var total_review_later_count = 0;
        globalData.question_list.forEach(function(val){
          total_question_count = total_question_count + 1;
          if(val.answer !== ""){
            if(val.answer === 0){
              total_skipped_count = total_skipped_count + 1;
            }else if(val.answer !== 0){
              total_answered_count = total_answered_count + 1;
            }
          }

          if(val.reported == 1){
            total_reported_count = total_reported_count + 1;
          }
          if(val.review_later == 1){
            total_review_later_count = total_review_later_count + 1;
          }
        });

        document.getElementById('total_question_count').innerHTML = total_question_count;
        document.getElementById('total_answered_count').innerHTML = total_answered_count;
        document.getElementById('total_skipped_count').innerHTML = total_skipped_count;
        document.getElementById('total_reported_count').innerHTML = total_reported_count;
        document.getElementById('total_review_later_count').innerHTML = total_review_later_count;
      }

      function renderQuestionAndOption(data,number){
        console.log(data);
        var question_image = "";
        if(data.question_image){
          question_image = `<div class="questionImg"><img src="{{ asset('') }}uploads/question/`+data.question_image+`"></div>`;
        }
        document.getElementById('question_div').innerHTML = number+') '+data.question_text+''+question_image;
        if(data.is_option1_image == 1){
          document.getElementById('option1_div').innerHTML = `<div class="answerImg"><img src="{{ asset('') }}uploads/option/`+data.option1+`"></div>`;
        }else{
          document.getElementById('option1_div').innerHTML = data.option1;
        }
        if(data.is_option2_image == 1){
          document.getElementById('option2_div').innerHTML = `<div class="answerImg"><img src="{{ asset('') }}uploads/option/`+data.option2+`"></div>`;
        }else{
          document.getElementById('option2_div').innerHTML = data.option2;
        }
        if(data.is_option3_image == 1){
          document.getElementById('option3_div').innerHTML = `<div class="answerImg"><img src="{{ asset('') }}uploads/option/`+data.option3+`"></div>`;
        }else{
          document.getElementById('option3_div').innerHTML = data.option3;
        }
        if(data.is_option4_image == 1){
          document.getElementById('option4_div').innerHTML = `<div class="answerImg"><img src="{{ asset('') }}uploads/option/`+data.option4+`"></div>`;
        }else{
          document.getElementById('option4_div').innerHTML = data.option4;
        }
        $('#option1').prop('checked', false);
        $('#option2').prop('checked', false);
        $('#option3').prop('checked', false);
        $('#option4').prop('checked', false);
        if(data.answer == 1){
          $('#option1').prop('checked', true);
        }else if(data.answer == 2){
          $('#option2').prop('checked', true);
        }else if(data.answer == 3){
          $('#option3').prop('checked', true);
        }else if(data.answer == 4){
          $('#option4').prop('checked', true);
        }
        if(data.answer){
          globalData.is_current_option_selected = data.answer;
        }else{
          globalData.is_current_option_selected = 0;
        }
        if(data.time){
          var deadline1 = new Date();
          deadline1.setSeconds(deadline1.getSeconds() - data.time);
          initializeTotalQuestionTime('sclockdiv', deadline1, globalData.time_per_question);
        }else{
          var deadline1 = new Date();
          initializeTotalQuestionTime('sclockdiv', deadline1, globalData.time_per_question);
        }
        if(data.answer){
          if(data.answer != 0){
            document.getElementById('skip_button').style.display = "none";
            document.getElementById('next_button').style.display = "block";
          }else{
            document.getElementById('skip_button').style.display = "block";
            document.getElementById('next_button').style.display = "none";
          }
        }else{
          document.getElementById('skip_button').style.display = "block";
          document.getElementById('next_button').style.display = "none";
        }

        if(data.review_later == 1){
          document.getElementById('review_later_tick_icon_id').style.display = "block";
        }else{
          document.getElementById('review_later_tick_icon_id').style.display = "none";
        }

        if(data.reported == 1){
          document.getElementById('reported_tick_icon_id').style.display = "block";
        }else{
          document.getElementById('reported_tick_icon_id').style.display = "none";
        }

        if(data.type == "save"){
          document.getElementById('save_tick_icon_id').style.display = "block";
        }else{
          document.getElementById('save_tick_icon_id').style.display = "none";
        }

        document.getElementById('current_question_view').innerHTML = number;
      }

      function renderQuestionList(data,type){
        var iHtml = `<h6>QUESTION BANK</h6>`;
        var className = "";
        var keyName;
        data.forEach(function(res,index){
          className = "";
          if(res.answer == 0){
            className = "gray";
          }else if(res.answer){
            className = "green";
          }
          if(res.index){
            keyName = res.index;
          }else{
            keyName = index;
          }
          iHtml = iHtml + `<a href="javascript:void(0);" class="`+className+`" id="question_list_`+keyName+`" onclick="onclickQuestionList(`+keyName+`)">Q`+(keyName+1)+`</a>`;
          if(type){
            globalData.question_list[index].index = index;
          }
        });
        document.getElementById("question_list").innerHTML = iHtml;
      }

      function selectAnswerOption(type){
        document.getElementById('skip_button').style.display = "none";
        document.getElementById('next_button').style.display = "block";
        globalData.is_current_option_selected = type;
        globalData.question_list[globalData.question_index].answer = type;
        globalData.question_list[globalData.question_index].type = "answer";
        globalData.question_list[globalData.question_index].time = globalData.current_question_time.total/1000;
        var question_detail = globalData.question_list[globalData.question_index];
        var requestData = {
          "id":question_detail.id,
          "question_paper_question_id":question_detail.question_paper_question_id,
          "answer":question_detail.answer,
          "time":question_detail.time,
          "type":"answer"
        };
        // saveQuestionAnswer(requestData);
      }

      function clickPreviousButton(){
        if(globalData.question_index){
          globalData.question_index = globalData.question_index - 1;
        }
        renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
        renderQuestionList(globalData.question_list,0);
      }

      function clickReviewLater(){
        if(globalData.question_list[globalData.question_index].review_later == 1){
          globalData.question_list[globalData.question_index].review_later = "0";
        }else{
          globalData.question_list[globalData.question_index].review_later = "1";
        }

        renderOuestionCount();
        var question_detail = globalData.question_list[globalData.question_index];
        var requestData = {
          "id":question_detail.id,
          "question_paper_question_id":question_detail.question_paper_question_id,
          "answer":question_detail.answer,
          "time":question_detail.time,
          "review_later":question_detail.review_later,
          "type":"review"
        };
        saveQuestionAnswer(requestData);
        renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
        // if(globalData.question_list[globalData.question_index+1]){
        //   globalData.question_index = globalData.question_index + 1;
        //   renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
        //   renderQuestionList(globalData.question_list,0);
        //   renderOuestionCount();
        // }else{
        //   // alert("question End 1");
        //   examExamTimer(1);
        // }
      }

      function clickReported(){
        if(globalData.question_list[globalData.question_index].reported == 1){
          globalData.question_list[globalData.question_index].reported = "0";
        }else{
          globalData.question_list[globalData.question_index].reported = "1";
        }

        renderOuestionCount();
        var question_detail = globalData.question_list[globalData.question_index];
        var requestData = {
          "id":question_detail.id,
          "question_paper_question_id":question_detail.question_paper_question_id,
          "answer":question_detail.answer,
          "time":question_detail.time,
          "reported":question_detail.reported,
          "type":"report"
        };
        saveQuestionAnswer(requestData);
        renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
      }

      function clickSaveButton(){
        if(globalData.question_list[globalData.question_index].is_saved == 1){
          globalData.question_list[globalData.question_index].is_saved = "0";
        }else{
          globalData.question_list[globalData.question_index].is_saved = "1";
        }

        var question_detail = globalData.question_list[globalData.question_index];
        var requestData = {
          "id":question_detail.id,
          "question_paper_question_id":question_detail.question_paper_question_id,
          "answer":question_detail.answer,
          "time":question_detail.time,
          "is_saved":question_detail.is_saved,
          "review_later":(question_detail.review_later)?1:0,
          "reported":(question_detail.reported)?1:0,
          "type":"save"
        };
        saveQuestionAnswer(requestData);
        renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
      }

      function clickSkipButton(){
        if(globalData.question_list.length > globalData.question_index){
          globalData.question_list[globalData.question_index].answer = 0;
          globalData.question_list[globalData.question_index].time = globalData.current_question_time.total/1000;
          var question_detail = globalData.question_list[globalData.question_index];
          var requestData = {
            "id":question_detail.id,
            "question_paper_question_id":question_detail.question_paper_question_id,
            "answer":"0",
            "time":question_detail.time,
            "review_later":(question_detail.review_later)?1:0,
            "reported":(question_detail.reported)?1:0,
            "type":"skip"
          };
          saveQuestionAnswer(requestData);
          if(globalData.question_list[globalData.question_index+1]){
            globalData.question_index = globalData.question_index + 1;
            renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
            renderQuestionList(globalData.question_list,0);
            renderOuestionCount();
          }else{
            examExamTimer(1);
          }
        }else{
          globalData.question_list[globalData.question_index].answer = globalData.is_current_option_selected;
          globalData.question_list[globalData.question_index].time = globalData.current_question_time.total/1000;
          var question_detail = globalData.question_list[globalData.question_index];
          var requestData = {
            "id":question_detail.id,
            "question_paper_question_id":question_detail.question_paper_question_id,
            "answer":"0",
            "time":question_detail.time,
            "review_later":(question_detail.review_later)?1:0,
            "reported":(question_detail.reported)?1:0,
            "type":"skip"
          };
          saveQuestionAnswer(requestData);
          examExamTimer(1);
        }
      }

      function clickNextButton(){
        if(globalData.question_list.length-1 > globalData.question_index){
          globalData.question_list[globalData.question_index].answer = globalData.is_current_option_selected;
          globalData.question_list[globalData.question_index].time = globalData.current_question_time.total/1000;
          var question_detail = globalData.question_list[globalData.question_index];
          var requestData = {
            "id":question_detail.id,
            "question_paper_question_id":question_detail.question_paper_question_id,
            "answer":question_detail.answer,
            "time":question_detail.time,
            "review_later":(question_detail.review_later)?1:0,
            "reported":(question_detail.reported)?1:0,
            "type":"next"
          };
          saveQuestionAnswer(requestData);
          globalData.question_index = globalData.question_index + 1;
          renderQuestionAndOption(globalData.question_list[globalData.question_index],globalData.question_index+1);
          renderQuestionList(globalData.question_list,0);
          renderOuestionCount();
        }else{
          globalData.question_list[globalData.question_index].answer = globalData.is_current_option_selected;
          globalData.question_list[globalData.question_index].time = globalData.current_question_time.total/1000;
          var question_detail = globalData.question_list[globalData.question_index];
          var requestData = {
            "id":question_detail.id,
            "question_paper_question_id":question_detail.question_paper_question_id,
            "answer":question_detail.answer,
            "time":question_detail.time,
            "review_later":(question_detail.review_later)?1:0,
            "reported":(question_detail.reported)?1:0,
            "type":"next"
          };
          saveQuestionAnswer(requestData);
          examExamTimer(1);
        }
      }

      function saveQuestionAnswer(requestData){
        requestData.exam_user_id = {{$user_exam_id}};
        requestData.exam_id = {{$user_exam_detail->exam_id}};
        $.ajax({
          url:"{{route('update_user_exam_question')}}",
          method:"POST",
          data:requestData,
          success:function(responseData){
            console.log(responseData);
          }
        });
      }

      function endTestExam(){
        $('#endExamModal').modal('show');
      }

      function endTestModalClick(mode){
        var requestData = {};
        requestData.exam_user_id = {{$user_exam_id}};
        requestData.exam_id = {{$user_exam_detail->exam_id}};
        if(mode === 'cancel'){
          requestData.proctoring_cancelled = 1;
        } else if(mode){
          requestData.proctoring_auto_submit = 1;
        }
        $.ajax({
          url:"{{route('end_exam')}}",
          method:"POST",
          data:requestData,
          success:function(responseData){
            window.location.href = "{{route('exam_result_detail',['id'=>$user_exam_id])}}";
          }
        });
      }

      function saveTestModalClick(){
        var requestData = {};
        requestData.exam_user_id = {{$user_exam_id}};
        requestData.exam_id = {{$user_exam_detail->exam_id}};
        $.ajax({
          url:"{{route('save_exam')}}",
          method:"POST",
          data:requestData,
          success:function(responseData){

            window.location.href = "{{route('upcoming_exam')}}";
          }
        });
      }

      function updateTime(){
        var question_detail = globalData.question_list[globalData.question_index];
        var requestData = {};
        requestData.exam_user_id = {{$user_exam_id}};
        requestData.exam_id = {{$user_exam_detail->exam_id}};
        requestData.id = question_detail.id;
        requestData.question_paper_question_id = question_detail.question_paper_question_id;
        $.ajax({
          url:"{{route('update_exam_time')}}",
          method:"POST",
          data:requestData,
          success:function(responseData){
            // console.log(responseData);
          }
        });
      }

      function examExamTimer(type){
        if(type){
          var closeBt = $('#retest');
          closeBt.removeClass('out').addClass('myFade');
          $('body').addClass('modal-active');
        }else{
          var closeBt = $('#exam_end_timer');
          closeBt.removeClass('out').addClass('myFade');
          $('body').addClass('modal-active');
        }
      }

      function cancalTestModalClick(){
        var closeBt = $('#retest');
        closeBt.removeClass('myFade').addClass('out');
        $('body').removeClass('modal-active');
      }
    </script>
    <script>
      function getTimeRemaining(endtime) {
        var t =  Date.parse(new Date()) - Date.parse(endtime);
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);

        return {
          'total': t,
          'hours': hours,
          'minutes': minutes,
          'seconds': seconds
        };
      }

      function initializeTotalExamTime(startTime) {
        var hoursSpan   = $('#clockdiv .main_hours');
        var minutesSpan = $('#clockdiv .main_minutes');
        var secondsSpan = $('#clockdiv .main_seconds');
        var progressBar = $('#main_timer_div');

        var examTimeInterval = null;
        var lastUpdateTime = 0;
        var lastUpdateCount =0;

        function updateExamClock() {
            var t = getTimeRemaining(startTime);
            console.log(t);
            hoursSpan.text(('0' + t.hours).slice(-2));
            minutesSpan.text(('0' + t.minutes).slice(-2));
            secondsSpan.text(('0' + t.seconds).slice(-2));

            var percentage = 0;
            var totalTime = globalData.exam_time;

            if (totalTime > 0) {
                percentage = (t.total / totalTime) * 100;
            }

            percentage = Math.min(100, Math.max(0, percentage));

            progressBar.css('width', percentage + '%');
            if ((t.total - lastUpdateTime >= 5000) && lastUpdateCount ) {

                lastUpdateTime = t.total;
                lastUpdateCount = 1;

                updateTime();
            }else{
              lastUpdateCount = 1;
            }
            if (t.total >= totalTime) {

                clearInterval(examTimeInterval);

                progressBar.css('width', '100%');

                examExamTimer(0);

                return;
            }
        }


        // Run immediately
        updateExamClock();


        // Then every second
        examTimeInterval = setInterval(updateExamClock, 1000);
      }

      function initializeTotalQuestionTime(id, endtime,total_time) {
        var hoursSpan = $('#'+id+' .hours');
        var minutesSpan = $('#'+id+' .minutes');
        var secondsSpan = $('#'+id+' .seconds');

        function updateQuestionClock() {
          var t = getTimeRemaining(endtime);
          globalData.current_question_time = t;
          hoursSpan.html(('0' + t.hours).slice(-2));
          minutesSpan.html(('0' + t.minutes).slice(-2));
          secondsSpan.html(('0' + t.seconds).slice(-2));
        }
        updateQuestionClock();
        if(globalData.questionTimeInterval){
          clearInterval(globalData.questionTimeInterval);
          globalData.questionTimeInterval = setInterval(updateQuestionClock, 1000);
        }else{
          globalData.questionTimeInterval = setInterval(updateQuestionClock, 1000);
        }
      }
      function beginExamTimer() {
        var examStartTime = new Date();
        examStartTime.setSeconds(examStartTime.getSeconds() - globalData.total_time);
        initializeTotalExamTime(examStartTime);
      }
      @if(empty($exam_detail->is_proctored))
      beginExamTimer();
      @else
      window.beginExamTimer = beginExamTimer;
      @endif
    </script>
    @if(!empty($exam_detail->is_proctored))
    <script src="{{ asset('exam/vendor/face-api/face-api.min.js') }}?v=9"></script>
    <script src="{{ asset('exam/js/proctoring.js') }}?v=9"></script>
    <script>
      RankProProctoring.init({
        enabled: true,
        examUserId: {{ $user_exam_id }},
        examId: {{ $user_exam_detail->exam_id }},
        maxViolations: {{ (int)($exam_detail->proctoring_max_violations ?? 5) }},
        maxWebcamStrikes: 3,
        modelUrl: "{{ asset('exam/vendor/face-api') }}",
        eventUrl: "{{ route('log_proctoring_event') }}",
        snapshotUrl: "{{ route('save_proctoring_snapshot') }}",
        snapshotInterval: 45000,
        onForceEnd: function(){ endTestModalClick(1); },
        onForceCancel: function(){ endTestModalClick('cancel'); }
      });
    </script>
    @endif

</body>
</html>
