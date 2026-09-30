<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            height:72px;
            margin:0 !important;
            padding:0 !important;
            background:#fff;
            border-bottom:1px solid #e8ecf3;
            box-shadow:0 1px 8px rgba(20,35,60,.04);
            position:relative;
            z-index:1000;
        }
        #header .container-fluid {
            height:100%;
            padding-left:20px !important;
            padding-right:20px !important;
        }
        .mainHeader {
            height:100%;
            display:flex;
            flex-direction:row;
            align-items:center;
            justify-content:flex-start;
            flex-wrap:nowrap;
            gap:16px;
            padding:0 !important;
            margin:0 !important;
        }
        #mainLogo { max-width:130px; max-height:38px; display:block; }
        .logo,
        #logoContain,
        .logo.dashboardMenuPL,
        #header .logo.dashboardMenuPL {
            flex:0 0 auto;
            width:auto !important;
            margin:0 !important;
            padding:0 !important;
            padding-top:0 !important;
            padding-bottom:0 !important;
            padding-left:0 !important;
            text-align:left;
            height:auto !important;
        }
        .logo a,
        #logoContain a {
            display:inline-flex;
            align-items:center;
            line-height:1;
            padding:0 !important;
        }
        .proctoring-badge {
            display:none;
            flex:0 0 auto;
            align-items:center;
            justify-content:center;
            height:36px;
            margin:0 !important;
            padding:0 12px;
            background:#fff3e0;
            color:#c2410c;
            border:1px solid #ffd7b0;
            border-radius:8px;
            font-size:12px;
            font-weight:600;
            white-space:nowrap;
            line-height:1;
            order:0;
        }
        body.exam-proctored-active .proctoring-badge { display:inline-flex; }
        .questionCount {
            flex:0 0 auto;
            order:0;
            margin:0 !important;
            padding:0 !important;
            font-size:14px;
            color:#707b91;
            white-space:nowrap;
            line-height:36px;
            height:36px;
            display:inline-flex;
            align-items:center;
        }
        .questionCount b { color:#17233b; }
        .exampTime,
        #clockdiv {
            flex:0 0 auto;
            order:0;
            display:inline-flex !important;
            align-items:center;
            gap:8px;
            width:auto !important;
            margin:0 !important;
            padding:0 !important;
            font-size:14px;
            color:#7a8498;
            line-height:1;
        }
        .blueTime,
        #clockdiv .blueTime {
            position:relative;
            width:148px !important;
            height:36px !important;
            border-radius:10px;
            background:#dfe8ff;
            overflow:hidden;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:0 !important;
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
            font-size:16px;
            letter-spacing:0.5px;
            font-variant-numeric:tabular-nums;
            font-weight:700;
            line-height:1;
            top:auto !important;
            left:auto !important;
            right:auto !important;
            bottom:auto !important;
        }
        .blueTime .timer-sep {
            margin:0 1px;
            font-weight:700;
        }
        .exampTime .timer-label {
            color:#7a8498;
            font-size:13px;
            white-space:nowrap;
            line-height:1;
        }
        .topExampButtons {
            order:0;
            margin-left:auto !important;
            margin-right:0 !important;
            padding:0 !important;
            flex:0 0 auto;
        }
        .topExampButtonsResponsive {
            display:flex;
            gap:12px;
            align-items:center;
        }
        .topExampButtons a {
            min-width:132px;
            height:36px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            border-radius:9px;
            font-size:14px;
            font-weight:700;
            text-decoration:none !important;
            text-transform:uppercase;
            line-height:1;
            padding:0 14px;
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
        .avtar_demo { display:none !important; }

        @media (max-width:1100px) {
            .mainHeader { gap:10px; }
            .topExampButtons a { min-width:110px; font-size:12px; padding:0 10px; }
            .exampTime .timer-label { display:none; }
            .questionCount { font-size:12px; }
            .proctoring-badge { font-size:11px; padding:0 8px; }
            .blueTime, #clockdiv .blueTime { width:132px !important; }
            .blueTime span { font-size:14px; }
        }
        @media (max-width:767px) {
            #header { height:auto; min-height:72px; }
            .mainHeader {
                flex-wrap:wrap;
                padding:10px 0 !important;
                row-gap:10px;
            }
            .topExampButtons {
                margin-left:0 !important;
                width:100%;
            }
            .topExampButtonsResponsive {
                width:100%;
            }
            .topExampButtons a { flex:1; }
        }

        #dashboardBody {
            background:#f5f7fb !important;
            padding:42px 0 50px !important;
            min-height:calc(100vh - 72px);
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
            overflow:visible;
            position:relative;
            z-index:1;
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
            height:auto !important;
            overflow:visible !important;
            position:relative;
            z-index:1;
            border:1px solid #e1e6ee;
            border-radius:12px;
            background:#fff;
            clear:both;
        }
        .question-meta { display:none; }
        .questionDiv .question-text {
            display:block;
            width:100%;
            overflow:visible;
        }
        .questionDiv .q-no { display:none; }
        #question_div {
            margin:0;
            font-size:17px;
            line-height:1.75;
            font-weight:500;
            color:#17243a;
            word-break:break-word;
            width:100%;
            display:block;
            overflow:visible !important;
            height:auto !important;
            max-height:none !important;
            position:relative;
        }
        #question_div::after {
            content:"";
            display:table;
            clear:both;
        }
        #question_div img,
        .questionImg img,
        .answerImg img,
        .option-card img {
            max-width:100% !important;
            width:auto !important;
            height:auto !important;
            display:inline-block;
            vertical-align:middle;
        }
        #question_div table {
            width:auto !important;
            max-width:100% !important;
            height:auto !important;
            border-collapse:collapse;
            position:relative !important;
            float:none !important;
            margin:10px 0;
            display:table;
        }
        #question_div table td,
        #question_div table th {
            height:auto !important;
            vertical-align:top;
            padding:6px 8px;
        }
        #question_div p,
        #question_div div {
            position:static !important;
            float:none !important;
            max-width:100%;
            height:auto !important;
            overflow:visible !important;
        }
        .questionImg { margin-top:12px; clear:both; }
        .answerImg { width:100%; }
        .optionsDiv {
            margin:8px 0 0;
            clear:both;
            position:relative;
            z-index:1;
            overflow:visible;
        }
        .optionsDiv > .row { margin:0 -7px; }
        .optionsDiv .col-lg-6 { padding:0 7px; }
        .option-card {
            position:relative;
            min-height:94px;
            height:auto !important;
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
            overflow:visible;
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
            max-height:calc(100vh - 90px);
            min-height:0;
            background:#fff !important;
            border:1px solid #e4e8ef !important;
            border-radius:12px !important;
            box-shadow:0 3px 18px rgba(20,34,60,.05) !important;
            overflow:hidden;
            z-index:10;
            display:flex !important;
            flex-direction:column;
        }
        .sideExampOptions .optionOpener { display:none !important; }
        .sideExampOptions > ul {
            list-style:none;
            grid-template-columns:1fr 1fr;
            margin:0 !important;
            padding:0 !important;
            border-bottom:1px solid #dfe4eb;
            flex:0 0 auto;
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
            flex:1 1 auto;
            min-height:220px;
            height:auto !important;
            max-height:none !important;
            overflow-y:auto;
            overflow-x:hidden;
            padding:18px 16px 20px !important;
            -webkit-overflow-scrolling:touch;
        }
        .questionBankList:before {
            content:'QUESTION BANK';
            display:block;
            margin:0 0 12px;
            color:#17243a;
            font-size:14px;
            font-weight:700;
        }
        .questionBankSubject {
            display:block;
            width:100%;
            margin:10px 0 8px;
            padding:4px 0 2px;
            color:#596273;
            font-size:11px;
            font-weight:700;
            letter-spacing:.04em;
            text-transform:uppercase;
            border-bottom:1px solid #eef0f4;
        }
        .questionBankSubject:first-of-type { margin-top:0; }
        .questionBankGroup { display:block; margin-bottom:6px; }
        .questionBankList a {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:38px;
            height:34px;
            margin:0 5px 7px 0;
            border:1px solid #bfe8d1;
            border-radius:7px;
            background:#e7f9ef;
            color:#15925d;
            font-size:11px;
            font-weight:600;
            text-decoration:none !important;
        }
        .questionBankList a.active,
        .questionBankList a.current { background:#3561ff; border-color:#3561ff; color:#fff; }
        .questionBankList a.gray { background:#eef0f4; border-color:#dfe3e8; color:#697286; }
        .questionBankList a.green { background:#08aa58; border-color:#08aa58; color:#fff; }
        .questionBankList a.red { background:#e53935; border-color:#c62828; color:#fff; }
        .questionBankList a.yellow { background:#f6c445; border-color:#e0a800; color:#222; }
        .examHeaderName {
            flex: 1 1 auto;
            min-width: 0;
            max-width: min(420px, 36vw);
            font-size: 13px;
            font-weight: 600;
            color: #17233b;
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
            margin: 0;
            line-height: 1.25;
            word-break: break-word;
        }
        @media (max-width: 991px) {
            .examHeaderName { max-width: min(240px, 42vw); font-size: 12px; }
        }
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
                max-height:none;
                min-height:0;
                margin-bottom:18px;
            }
            .questionBankList { max-height:min(55vh, 420px) !important; }
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
        .modalStyle { pointer-events: none; }
        .modalStyle.myFade {
            z-index: 100002 !important;
            pointer-events: auto;
        }
        body.modal-active .proctoring-gate,
        body.modal-active .proctoring-warning {
            pointer-events: none;
        }
        button.orengeButton {
            border: 0;
            cursor: pointer;
            font-family: inherit;
        }
        button.orengeButton:disabled {
            opacity: .7;
            cursor: wait;
        }
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
      <li>The camera tracks your face and eyes continuously while you take the exam.</li>
      <li>Looking at the questions is fine. A warning appears if you leave the seat or cover the camera.</li>
      <li>If you stay away or keep the camera covered after warnings, the exam is cancelled.</li>
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
      @php
        $examHeaderName = trim((string) ($exam_detail->name ?? ''));
        if ($examHeaderName === '') {
            $examHeaderName = 'Online Exam';
        }
      @endphp
      <div class="examHeaderName" title="{{ $examHeaderName }}">
        {{ $examHeaderName }}
      </div>
      @if(!empty($exam_detail->is_proctored))
      <div class="proctoring-badge">Proctored · warnings <span id="proctoringViolationCount">0</span>/{{ $exam_detail->proctoring_max_violations ?? 5 }}</div>
      @endif
      <div class="questionCount"><b id="current_question_view"></b> questions left of <b><?php echo count($question_list);?></b></div>
      <div class="exampTime" id="clockdiv"><div class="blueTime"><div id="main_timer_div" style="width:0%;"></div><span><b class="main_hours">00</b><b class="timer-sep">:</b><b class="main_minutes">00</b><b class="timer-sep">:</b><b class="main_seconds">00</b></span></div><span class="timer-label">left of <?php echo $exam_detail->total_time_for_exam;?> min</span></div>
      <div class="topExampButtons"><div class="topExampButtonsResponsive"><a href="javascript:void(0);" class="redButton button" onclick="examExamTimer(1);"><i class="fa fa-power-off"></i> End Test</a><a href="javascript:void(0);" class="whiteButton" onclick="clickReported();"><i class="fa fa-flag"></i><i class="fa fa-check" aria-hidden="true" id="reported_tick_icon_id" style="display:none;"></i> Report</a></div></div>
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
        <div class="exam-heading"><h5>Online Examination</h5><span class="subject" id="current_subject_label">Question &amp; Answer</span></div>
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
<div id="retest" class="modalStyle"><div class="modal-background"><div class="login_card retest"><div class="card modal"><div class="card-header greenHeader">END TEST <img class="closeModel img-fluid" onclick="cancalTestModalClick();" src="{{ asset('') }}exam/img/close.png" alt="Close"></div><div class="card-body whiteBody"><ul><li>Questions may have negative marks, be careful while attempting a question.</li><li>Keep an eye on timer, you need to finish it before time ends if there is time limit.</li><li>Questions can be multiple choice or single choice, need to answer appropriately.</li><li>Time is calculated once you start the test, if you leave in middle that time will be considered as well.</li></ul>@if(empty($exam_detail->is_proctored))<button type="button" class="orengeButton" onclick="saveTestModalClick();">Save</button>@endif<button type="button" class="orengeButton js-end-test-btn" style="background-color:#ff0000;" onclick="endTestModalClick();">End Test</button></div></div></div></div></div>
<div id="exam_end_timer" class="modalStyle"><div class="modal-background"><div class="login_card retest"><div class="card modal"><div class="card-header greenHeader">END TEST</div><div class="card-body whiteBody"><ul><li>Questions may have negative marks, be careful while attempting a question.</li><li>Keep an eye on timer, you need to finish it before time ends if there is time limit.</li><li>Questions can be multiple choice or single choice, need to answer appropriately.</li><li>Time is calculated once you start the test, if you leave in middle that time will be considered as well.</li></ul><button type="button" class="orengeButton js-end-test-btn" onclick="endTestModalClick();">End Test</button></div></div></div></div></div>

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
      window.examCsrfToken = "{{ csrf_token() }}";
      @php
        $afterEndUrl = route('exam_result_detail', ['id' => $user_exam_id]);
        if ((string) ($user_exam_detail->exam_type ?? '') === 'CUSTOM') {
            $customId = \App\Models\CustomTest::where('exam_user_id', $user_exam_id)
                ->where('user_id', Auth::id())
                ->value('id');
            $afterEndUrl = $customId
                ? route('custom_test.analysis', $customId)
                : route('custom_test', ['tab' => 'attempted']);
        }
      @endphp
      window.examAfterEndUrl = @json($afterEndUrl);
      window.examAttemptId = {{ (int) $user_exam_id }};
      window.examEndedStorageKey = 'rankpro_exam_ended_' + window.examAttemptId;

      // If this attempt was already ended, never stay on the exam player (covers browser Back).
      if (sessionStorage.getItem(window.examEndedStorageKey) === '1') {
        window.location.replace(window.examAfterEndUrl);
      }

      window.addEventListener('pageshow', function (event) {
        if (sessionStorage.getItem(window.examEndedStorageKey) === '1') {
          window.location.replace(window.examAfterEndUrl);
          return;
        }
        // Back-forward cache can restore a mid-"Ending..." snapshot; force a fresh server check.
        if (event.persisted) {
          window.location.reload();
        }
      });
    </script>

   <!--===============================================================================================-->
     <script src="{{ asset('') }}exam/vendor/jquery/jquery-3.2.1.min.js"></script>
     <script>
       $.ajaxSetup({
         headers: { 'X-CSRF-TOKEN': window.examCsrfToken },
         data: { _token: window.examCsrfToken }
       });
     </script>
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
        resizeQuestionBank();
        $(window).on('resize', resizeQuestionBank);
      });

      function resizeQuestionBank() {
        var $sidebar = $('.sideExampOptions');
        var $list = $('.questionBankList');
        var $ul = $('.sideExampOptions > ul');
        if (!$sidebar.length || !$list.length) return;
        if (window.innerWidth <= 991) {
          $list.css({ height: '', maxHeight: '' });
          $sidebar.css({ height: '', maxHeight: '' });
          return;
        }
        var top = $sidebar.offset().top - $(window).scrollTop();
        if (top < 8) top = 8;
        var sidebarH = Math.max(360, window.innerHeight - top - 16);
        var ulH = $ul.outerHeight(true) || 0;
        var listH = Math.max(220, sidebarH - ulH);
        $sidebar.css({ height: sidebarH + 'px', maxHeight: sidebarH + 'px' });
        $list.css({ height: listH + 'px', maxHeight: listH + 'px' });
      }
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
            if(item.reported == 1 || item.reported === '1' || item.reported === true){
              condtion = true;
            }else{
              condtion = false;
            }
          }else if(type == "review_later"){
            if(item.review_later == 1 || item.review_later === '1' || item.review_later === true){
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

      function examAssetBase() {
        return @json(asset(''));
      }

      function looksLikeImageFile(value) {
        if (value == null || value === '') return false;
        var v = String(value).trim();
        if (!v || v.length > 250) return false;
        return /\.(png|jpe?g|gif|webp|bmp|svg)(\?.*)?$/i.test(v);
      }

      function isImageOption(flag, value) {
        return flag == 1 || flag === true || flag === '1' || looksLikeImageFile(value);
      }

      function mediaUrl(file) {
        if (!file) return '';
        var f = String(file).trim();
        if (/^https?:\/\//i.test(f) || /^data:/i.test(f)) return f;
        f = f.replace(/\\/g, '/');
        // Strip relative junk like ../../uploads/...
        f = f.replace(/^(\.\.\/)+/, '').replace(/^\/+/, '');
        if (f.indexOf('uploads/option/') === 0) {
          f = f.replace('uploads/option/', 'uploads/question/');
        }
        if (f.indexOf('public/uploads/') === 0) {
          f = f.replace('public/uploads/', 'uploads/');
        }
        if (f.indexOf('uploads/') === 0) return examAssetBase() + f;
        // Bare filename or nested path without uploads/
        if (f.indexOf('/') === -1 || f.indexOf('question/') === 0 || f.indexOf('option/') === 0) {
          f = f.replace(/^option\//, 'question/');
          if (f.indexOf('question/') !== 0) f = 'question/' + f.replace(/^question\//, '');
          return examAssetBase() + 'uploads/' + f;
        }
        return examAssetBase() + 'uploads/question/' + f.split('/').pop();
      }

      function mediaUrlFallback(file) {
        var primary = mediaUrl(file);
        if (!primary) return '';
        // If primary points at uploads/question, also allow uploads/option as fallback.
        return primary.replace('/uploads/question/', '/uploads/option/');
      }

      function imgTag(src, alt) {
        var primary = mediaUrl(src);
        var fallback = mediaUrlFallback(src);
        var onerr = fallback && fallback !== primary
          ? ' onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src=\'' + fallback.replace(/'/g, "\\'") + '\';}"'
          : '';
        return '<img src="' + primary + '" alt="' + (alt || '') + '"' + onerr + '>';
      }

      function fixHtmlMedia(html) {
        if (!html) return '';
        var wrap = document.createElement('div');
        wrap.innerHTML = html;
        wrap.querySelectorAll('img').forEach(function (img) {
          var src = img.getAttribute('src') || '';
          if (!src) return;
          if (/^https?:\/\//i.test(src) || /^data:/i.test(src)) {
            // keep absolute
          } else {
            var cleaned = src.replace(/\\/g, '/').replace(/^(\.\.\/)+/, '').replace(/^\/+/, '');
            if (cleaned.indexOf('uploads/option/') !== -1) {
              cleaned = cleaned.replace('uploads/option/', 'uploads/question/');
            }
            if (cleaned.indexOf('public/uploads/') === 0) {
              cleaned = cleaned.replace('public/uploads/', 'uploads/');
            }
            if (cleaned.indexOf('uploads/') === 0) {
              img.setAttribute('src', examAssetBase() + cleaned);
            } else if (cleaned.indexOf('/') === -1) {
              img.setAttribute('src', mediaUrl(cleaned));
            } else {
              img.setAttribute('src', mediaUrl(cleaned.split('/').pop()));
            }
            var fallback = (img.getAttribute('src') || '').replace('/uploads/question/', '/uploads/option/');
            if (fallback && fallback !== img.getAttribute('src')) {
              img.setAttribute('data-fallback', fallback);
              img.onerror = function () {
                if (!this.dataset.fb) {
                  this.dataset.fb = '1';
                  this.src = this.getAttribute('data-fallback');
                }
              };
            }
          }
          img.removeAttribute('width');
          img.removeAttribute('height');
          img.style.maxWidth = '100%';
          img.style.height = 'auto';
        });
        wrap.querySelectorAll('table').forEach(function (table) {
          table.removeAttribute('height');
          table.style.height = 'auto';
          table.style.maxWidth = '100%';
          table.style.position = 'relative';
          table.style.float = 'none';
        });
        wrap.querySelectorAll('[style]').forEach(function (el) {
          var style = el.getAttribute('style') || '';
          if (/position\s*:\s*absolute/i.test(style)) {
            el.style.position = 'relative';
          }
          if (/float\s*:\s*(left|right)/i.test(style)) {
            el.style.float = 'none';
          }
        });
        return wrap.innerHTML;
      }

      function renderOptionHtml(flag, value) {
        if (looksLikeImageFile(value)) {
          return '<div class="answerImg">' + imgTag(value, 'option') + '</div>';
        }
        // Flagged as image with a path-like value (no HTML)
        if ((flag == 1 || flag === true || flag === '1') && value && String(value).indexOf('<') === -1 && String(value).length > 8) {
          return '<div class="answerImg">' + imgTag(value, 'option') + '</div>';
        }
        return fixHtmlMedia(value || '');
      }

      function renderQuestionAndOption(data,number){
        var question_image = "";
        if(data.question_image){
          question_image = `<div class="questionImg">` + imgTag(data.question_image, 'question') + `</div>`;
        }
        document.getElementById('question_div').innerHTML = number+') '+fixHtmlMedia(data.question_text || '')+''+question_image;
        document.getElementById('option1_div').innerHTML = renderOptionHtml(data.is_option1_image, data.option1);
        document.getElementById('option2_div').innerHTML = renderOptionHtml(data.is_option2_image, data.option2);
        document.getElementById('option3_div').innerHTML = renderOptionHtml(data.is_option3_image, data.option3);
        document.getElementById('option4_div').innerHTML = renderOptionHtml(data.is_option4_image, data.option4);
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
        var subjectLabel = document.getElementById('current_subject_label');
        if (subjectLabel) {
          subjectLabel.textContent = data.subject_name || 'Question & Answer';
        }
        if (typeof resizeQuestionBank === 'function') {
          setTimeout(resizeQuestionBank, 50);
        }
      }

      function questionPaletteClass(res) {
        // Priority: reported > review later > answered > skipped > unanswered
        if (res.reported == 1 || res.reported === '1' || res.reported === true) {
          return 'red';
        }
        if (res.review_later == 1 || res.review_later === '1' || res.review_later === true) {
          return 'yellow';
        }
        if (res.answer == 0 || res.answer === '0') {
          return 'gray';
        }
        if (res.answer) {
          return 'green';
        }
        return '';
      }

      function renderQuestionList(data,type){
        var iHtml = '';
        var className = "";
        var keyName;
        var lastSubject = null;
        data.forEach(function(res,index){
          className = questionPaletteClass(res);
          if(typeof res.index !== 'undefined' && res.index !== null && res.index !== ''){
            keyName = res.index;
          }else{
            keyName = index;
          }
          if(type){
            globalData.question_list[index].index = index;
          }
          var subjectName = res.subject_name || 'General';
          if(subjectName !== lastSubject){
            if(lastSubject !== null){
              iHtml += `</div>`;
            }
            iHtml += `<div class="questionBankSubject">`+subjectName+`</div><div class="questionBankGroup">`;
            lastSubject = subjectName;
          }
          iHtml = iHtml + `<a href="javascript:void(0);" class="`+className+`" id="question_list_`+keyName+`" onclick="onclickQuestionList(`+keyName+`)">Q`+(keyName+1)+`</a>`;
        });
        if(lastSubject !== null){
          iHtml += `</div>`;
        }
        document.getElementById("question_list").innerHTML = iHtml;
        var current = document.getElementById("question_list_" + globalData.question_index);
        if (current) {
          current.classList.add('current');
          if (typeof current.scrollIntoView === 'function') {
            current.scrollIntoView({ block: 'nearest', inline: 'nearest' });
          }
        }
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
          "review_later":(question_detail.review_later)?1:0,
          "reported":(question_detail.reported)?1:0,
          "type":"answer"
        };
        saveQuestionAnswer(requestData);
        renderOuestionCount();
        renderQuestionList(globalData.question_list,0);
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
        renderQuestionList(globalData.question_list,0);
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
        renderQuestionList(globalData.question_list,0);
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
        requestData._token = examAjaxToken();
        return $.ajax({
          url:"{{route('update_user_exam_question')}}",
          method:"POST",
          data:requestData,
          headers: { 'X-CSRF-TOKEN': examAjaxToken() }
        });
      }

      function collectAnswersPayload(){
        var answers = [];
        (globalData.question_list || []).forEach(function(q){
          if (q.answer === '' || q.answer === null || typeof q.answer === 'undefined') {
            return;
          }
          answers.push({
            id: q.id,
            question_paper_question_id: q.question_paper_question_id,
            answer: q.answer,
            time: q.time || 0,
            type: q.type || 'answer',
            review_later: (q.review_later == 1 || q.review_later === '1') ? 1 : 0,
            reported: (q.reported == 1 || q.reported === '1') ? 1 : 0
          });
        });
        return answers;
      }

      function endTestExam(){
        $('#endExamModal').modal('show');
      }

      function examAjaxToken(){
        return window.examCsrfToken || ($('meta[name="csrf-token"]').attr('content') || '');
      }

      function endTestModalClick(mode){
        if (window.examEndingNow) {
          return;
        }
        window.examEndingNow = true;
        if (window.RankProProctoring) {
          RankProProctoring.ended = true;
        }
        $('.js-end-test-btn').prop('disabled', true).text('Ending...');

        // Persist current question selection before scoring.
        if (globalData.is_current_option_selected) {
          globalData.question_list[globalData.question_index].answer = globalData.is_current_option_selected;
          globalData.question_list[globalData.question_index].type = 'answer';
          globalData.question_list[globalData.question_index].time = globalData.current_question_time.total/1000;
        }

        var requestData = {
          _token: examAjaxToken(),
          exam_user_id: {{$user_exam_id}},
          exam_id: {{$user_exam_detail->exam_id}},
          answers: JSON.stringify(collectAnswersPayload())
        };
        if(mode === 'cancel'){
          requestData.proctoring_cancelled = 1;
        } else if(mode){
          requestData.proctoring_auto_submit = 1;
        }

        var afterEndUrl = window.examAfterEndUrl || "{{ route('exam_result_detail', ['id' => $user_exam_id]) }}";

        $.ajax({
          url:"{{route('end_exam')}}",
          method:"POST",
          data:requestData,
          headers: { 'X-CSRF-TOKEN': examAjaxToken() },
          timeout: 60000,
          success:function(responseData){
            try {
              sessionStorage.setItem(window.examEndedStorageKey, '1');
            } catch (e) {}
            if (window.opener && !window.opener.closed) {
              try { window.close(); } catch (e) {}
            }
            // replace() so browser Back cannot return to the live exam page.
            window.location.replace(afterEndUrl);
          },
          error:function(xhr){
            window.examEndingNow = false;
            if (window.RankProProctoring) {
              RankProProctoring.ended = false;
            }
            $('.js-end-test-btn').prop('disabled', false).text('End Test');
            var msg = 'Could not end the test. Please try again.';
            if (xhr && xhr.status === 419) {
              msg = 'Session expired. Refresh the page and end the test again.';
            }
            alert(msg);
          }
        });
      }

      function saveTestModalClick(){
        var requestData = {
          _token: examAjaxToken(),
          exam_user_id: {{$user_exam_id}},
          exam_id: {{$user_exam_detail->exam_id}}
        };
        $.ajax({
          url:"{{route('save_exam')}}",
          method:"POST",
          data:requestData,
          headers: { 'X-CSRF-TOKEN': examAjaxToken() },
          success:function(responseData){

            window.location.href = "{{route('upcoming_exam')}}";
          },
          error:function(){
            alert('Could not save the test. Please try again.');
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
            var totalTime = globalData.exam_time;
            var remainingMs = Math.max(0, totalTime - t.total);
            var hours = Math.floor(remainingMs / (1000 * 60 * 60));
            var minutes = Math.floor((remainingMs / (1000 * 60)) % 60);
            var seconds = Math.floor((remainingMs / 1000) % 60);

            hoursSpan.text(('0' + hours).slice(-2));
            minutesSpan.text(('0' + minutes).slice(-2));
            secondsSpan.text(('0' + seconds).slice(-2));

            var percentage = 0;

            if (totalTime > 0) {
                percentage = (t.total / totalTime) * 100;
            }

            percentage = Math.min(100, Math.max(0, percentage));

            progressBar.css('width', percentage + '%');
            if ((t.total - lastUpdateTime >= 30000) && lastUpdateCount ) {

                lastUpdateTime = t.total;
                lastUpdateCount = 1;

                updateTime();
            }else{
              lastUpdateCount = 1;
            }
            if (t.total >= totalTime) {

                clearInterval(examTimeInterval);

                progressBar.css('width', '100%');
                hoursSpan.text('00');
                minutesSpan.text('00');
                secondsSpan.text('00');

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
    <script src="{{ asset('exam/vendor/face-api/face-api.min.js') }}?v=10"></script>
    <script src="{{ asset('exam/js/proctoring.js') }}?v=14"></script>
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
        snapshotInterval: 120000,
        csrfToken: window.examCsrfToken,
        onForceEnd: function(){ endTestModalClick(1); },
        onForceCancel: function(){ endTestModalClick('cancel'); }
      });
    </script>
    @endif

</body>
</html>
