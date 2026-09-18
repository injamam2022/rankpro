<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>RankPro</title>
        <!-- Load Favicon-->
        <link href="{{asset('')}}admin/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <!-- Load Material Icons from Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet" />
        <!-- Load Simple DataTables Stylesheet-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <!-- Roboto and Roboto Mono fonts from Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,500" rel="stylesheet" />
        <!-- Load main stylesheet-->
        <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/style.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="{{asset('')}}admin/css/styles.css" rel="stylesheet" />
        @yield('css_after')

        <style>

          .dangerBoader{
            border: 1px solid red !important;
          }
        </style>

    </head>
    <body class="nav-fixed">
        <!-- Top app bar navigation menu-->

        <nav class="top-app-bar navbar navbar-expand navbar-dark bg-dark">
            <div class="container-fluid px-4">
                <!-- Drawer toggle button-->
                <button class="btn btn-lg btn-icon order-1 order-lg-0" id="drawerToggle" href="javascript:void(0);"><i class="material-icons">menu</i></button>
                <!-- Navbar brand-->
                <a class="navbar-brand me-auto" href="{{url('admin/dashboard')}}"><div class="text-uppercase font-monospace">RankPro</div></a>
                <!-- Navbar items-->
                <div class="d-flex align-items-center mx-3 me-lg-0">
                    <div class="d-flex">
                        <div class="dropdown">
                            <button class="btn btn-lg btn-icon dropdown-toggle" id="dropdownMenuProfile" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">person</i></button>
                            <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="dropdownMenuProfile">
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.profile')}}">
                                        <i class="material-icons leading-icon">person</i>
                                        <div class="me-3">Profile</div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.change_password')}}">
                                        <i class="material-icons leading-icon">password</i>
                                        <div class="me-3">Change Password</div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.setting')}}">
                                        <i class="material-icons leading-icon">settings</i>
                                        <div class="me-3">Settings</div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" /></li>
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.logout')}}">
                                        <i class="material-icons leading-icon">logout</i>
                                        <div class="me-3">Logout</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Layout wrapper-->
        <div id="layoutDrawer">
            <!-- Layout navigation-->
            <div id="layoutDrawer_nav">
                <!-- Drawer navigation-->
                <nav class="drawer accordion drawer-light bg-white" id="drawerAccordion">
                    <div class="drawer-menu">
                        <div class="nav">
                            <div class="drawer-menu-divider d-sm-none"></div>

                            <a class="nav-link  {{ request()->is('admin/dashboard') ? ' active' : '' }}" href="{{route('admin.dashboard')}}">
                                <div class="nav-link-icon"><i class="material-icons">dashboard</i></div>
                                Dashboard
                            </a>
                            @if(session()->get('admmin_is_super') == 'SA')
                                <a class="nav-link collapsed
                                    {{ request()->is('admin/admin-user') ? ' active' : '' }} {{ request()->is('admin/admin-user/*') ? ' active' : '' }}
                                    {{ request()->is('admin/admin-user-role') ? ' active' : '' }} {{ request()->is('admin/admin-user-role/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAdminUser" aria-expanded="false" aria-controls="collapseAdminUser">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Admin User
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseAdminUser" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        <a class="nav-link {{ request()->is('admin/admin-user') ? ' active' : '' }} {{ request()->is('admin/admin-user/*') ? ' active' : '' }}" href="{{route('admin.admin_user')}}">Admin User</a>
                                        <a class="nav-link {{ request()->is('admin/admin-user-role') ? ' active' : '' }} {{ request()->is('admin/admin-user-role/*') ? ' active' : '' }}" href="{{route('admin.admin_user_role')}}">Admin User Role</a>
                                    </nav>
                                </div>
                            @endif
                            @if(session()->get('admmin_is_super') == 'SA')

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/counsellor') ? ' active' : '' }} {{ request()->is('admin/counsellor/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCounsellor" aria-expanded="false" aria-controls="collapseCounsellor">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Counsellor
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseCounsellor" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        <a class="nav-link {{ request()->is('admin/counsellor') ? ' active' : '' }} {{ request()->is('admin/counsellor/*') ? ' active' : '' }}" href="{{route('admin.counsellor')}}">Counsellor</a>
                                    </nav>
                                </div>
                            @endif

                            @if(isUserPermitted('location', 'menu'))
                                <a class="nav-link collapsed
                                    {{ request()->is('admin/country') ? ' active' : '' }} {{ request()->is('admin/country/*') ? ' active' : '' }}
                                    {{ request()->is('admin/city') ? ' active' : '' }} {{ request()->is('admin/city/*') ? ' active' : '' }}
                                    {{ request()->is('admin/state') ? ' active' : '' }} {{ request()->is('admin/state/*') ? ' active' : '' }}
                                    {{ request()->is('admin/location') ? ' active' : '' }} {{ request()->is('admin/location/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLocation" aria-expanded="false" aria-controls="collapseLocation">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Location
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseLocation" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('country-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/country') ? ' active' : '' }} {{ request()->is('admin/country/*') ? ' active' : '' }}" href="{{route('admin.country')}}">Country</a>
                                        @endif
                                        @if(isUserPermitted('state-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/state') ? ' active' : '' }} {{ request()->is('admin/state/*') ? ' active' : '' }}" href="{{route('admin.state')}}">State</a>
                                        @endif
                                        @if(isUserPermitted('city-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/city') ? ' active' : '' }} {{ request()->is('admin/city/*') ? ' active' : '' }}" href="{{route('admin.city')}}">City</a>
                                        @endif
                                        @if(isUserPermitted('location-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/location') ? ' active' : '' }} {{ request()->is('admin/location/*') ? ' active' : '' }}" href="{{route('admin.location')}}">Location</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif

                            @if(isUserPermitted('ranker', 'menu'))

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/ranker') ? ' active' : '' }} {{ request()->is('admin/ranker/*') ? ' active' : '' }}
                                    {{ request()->is('admin/course') ? ' active' : '' }} {{ request()->is('admin/course/*') ? ' active' : '' }}
                                    {{ request()->is('admin/ranker_assign') ? ' active' : '' }} {{ request()->is('admin/ranker_assign/*') ? ' active' : '' }}
                                    {{ request()->is('admin/ranker_appointment') ? ' active' : '' }} {{ request()->is('admin/ranker_appointment/*') ? ' active' : '' }}
                                    {{ request()->is('admin/ranker_price') ? ' active' : '' }} {{ request()->is('admin/ranker_price/*') ? ' active' : '' }}
                                    {{ request()->is('admin/ranker_rule') ? ' active' : '' }} {{ request()->is('admin/ranker_rule/*') ? ' active' : '' }}
                                     {{ request()->is('admin/ranker_feedback') ? ' active' : '' }} {{ request()->is('admin/ranker_feedback/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseRanker" aria-expanded="false" aria-controls="collapseRanker">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Ranker
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>

                                <div class="collapse" id="collapseRanker" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('course-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/course') ? ' active' : '' }} {{ request()->is('admin/course/*') ? ' active' : '' }}" href="{{route('admin.course')}}">Course</a>
                                        @endif
                                        @if(isUserPermitted('ranker-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/ranker') ? ' active' : '' }} {{ request()->is('admin/ranker/*') ? ' active' : '' }}" href="{{route('admin.ranker')}}">Ranker</a>
                                        @endif
                                        @if(isUserPermitted('ranker-assign-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/ranker_assign') ? ' active' : '' }} {{ request()->is('admin/ranker_assign/*') ? ' active' : '' }}" href="{{route('admin.ranker_assign')}}">Student Request</a>
                                        @endif
                                        @if(isUserPermitted('ranker-appointment-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/ranker_appointment') ? ' active' : '' }} {{ request()->is('admin/ranker_appointment/*') ? ' active' : '' }}" href="{{route('admin.ranker_appointment')}}">Appointment</a>
                                        @endif
                                        @if(isUserPermitted('ranker-price-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/ranker_price') ? ' active' : '' }} {{ request()->is('admin/ranker_price/*') ? ' active' : '' }}" href="{{route('admin.ranker_price')}}">Meeting</a>
                                        @endif
                                        @if(isUserPermitted('ranker-rule-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/ranker_rule') ? ' active' : '' }} {{ request()->is('admin/ranker_rule/*') ? ' active' : '' }}" href="{{route('admin.ranker_rule')}}">Rules</a>
                                        @endif
                                        @if(isUserPermitted('ranker-feedback-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/ranker_feedback') ? ' active' : '' }} {{ request()->is('admin/ranker_feedback/*') ? ' active' : '' }}" href="{{route('admin.ranker_feedback')}}">Feedback</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif

                            @if(isUserPermitted('student', 'menu'))
                                <a class="nav-link collapsed
                                    {{ request()->is('admin/student') ? ' active' : '' }} {{ request()->is('admin/student/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseStudent" aria-expanded="false" aria-controls="collapseStudent">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Student
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseStudent" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('student-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/student') ? ' active' : '' }} {{ request()->is('admin/student/*') ? ' active' : '' }}" href="{{route('admin.student')}}">Student List</a>
                                        @endif
                                        @if(isUserPermitted('student-request-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/ranker_assign') ? ' active' : '' }} {{ request()->is('admin/ranker_assign/*') ? ' active' : '' }}" href="{{route('admin.ranker_assign')}}">Student Request</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('coupon', 'menu'))

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/coupon') ? ' active' : '' }} {{ request()->is('admin/coupon/*') ? ' active' : '' }}
                                    {{ request()->is('admin/subscription') ? ' active' : '' }} {{ request()->is('admin/subscription/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCoupon" aria-expanded="false" aria-controls="collapseCoupon">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Coupon
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseCoupon" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('coupon-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/coupon') ? ' active' : '' }} {{ request()->is('admin/coupon/*') ? ' active' : '' }}" href="{{route('admin.coupon')}}">Coupon</a>
                                        @endif
                                            <a class="nav-link {{ request()->is('admin/subscription') ? ' active' : '' }} {{ request()->is('admin/subscription/*') ? ' active' : '' }}" href="{{route('admin.subscription')}}">Subscription</a>
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('payment', 'menu'))

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/payment') ? ' active' : '' }} {{ request()->is('admin/payment/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePayment" aria-expanded="false" aria-controls="collapsePayment">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Payment
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapsePayment" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('payment-test-series-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/payment/test_series') ? ' active' : '' }} {{ request()->is('admin/payment/test_series_detail') ? ' active' : '' }} " href="{{route('admin.payment.test_series')}}">Test Series</a>
                                        @endif
                                        @if(isUserPermitted('payment-ranker-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/payment/ranker') ? ' active' : '' }} {{ request()->is('admin/payment/ranker_detail') ? ' active' : '' }}" href="{{route('admin.payment.ranker')}}">Ranker</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('question', 'menu'))

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/question') ? ' active' : '' }} {{ request()->is('admin/question/*') ? ' active' : '' }}
                                    {{ request()->is('admin/subject') ? ' active' : '' }} {{ request()->is('admin/subject/*') ? ' active' : '' }}
                                    {{ request()->is('admin/chapter') ? ' active' : '' }} {{ request()->is('admin/chapter/*') ? ' active' : '' }}
                                    {{ request()->is('admin/source') ? ' active' : '' }} {{ request()->is('admin/source/*') ? ' active' : '' }}
                                    {{ request()->is('admin/question-source') ? ' active' : '' }} {{ request()->is('admin/question-source/*') ? ' active' : '' }}
                                    {{ request()->is('admin/question-upload') ? ' active' : '' }} {{ request()->is('admin/question-upload/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseQuestion" aria-expanded="false" aria-controls="collapseQuestion">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Question
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseQuestion" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('subject-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/subject') ? ' active' : '' }} {{ request()->is('admin/subject/*') ? ' active' : '' }}" href="{{route('admin.subject')}}">Subject</a>
                                        @endif
                                        @if(isUserPermitted('chapter-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/chapter') ? ' active' : '' }} {{ request()->is('admin/chapter/*') ? ' active' : '' }}" href="{{route('admin.chapter')}}">Chapter</a>
                                        @endif
                                        @if(isUserPermitted('source-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/source') ? ' active' : '' }} {{ request()->is('admin/source/*') ? ' active' : '' }}" href="{{route('admin.source')}}">Source</a>
                                        @endif
                                        @if(isUserPermitted('question-source-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/question-source') ? ' active' : '' }} {{ request()->is('admin/question-source/*') ? ' active' : '' }}" href="{{route('admin.question_source')}}">Question source</a>
                                        @endif
                                        @if(isUserPermitted('question-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/question') ? ' active' : '' }} {{ request()->is('admin/question/*') ? ' active' : '' }}" href="{{route('admin.question')}}">Question</a>
                                        @endif
                                        @if(isUserPermitted('question-upload-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/question-upload') ? ' active' : '' }} {{ request()->is('admin/question-upload/*') ? ' active' : '' }}" href="{{route('admin.question.upload')}}">Question Upload</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('offline-exam', 'menu'))
                                <a class="nav-link collapsed
                                    {{ request()->is('admin/offline_exam') ? ' active' : '' }} {{ request()->is('admin/offline_exam/*') ? ' active' : '' }}
                                    {{ request()->is('admin/offline_exam_result') ? ' active' : '' }} {{ request()->is('admin/offline_exam_result/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseOfflineExam" aria-expanded="false" aria-controls="collapseOfflineExam">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Offline Exam
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseOfflineExam" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('offline-exam-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/offline_exam') ? ' active' : '' }} {{ request()->is('admin/offline_exam/*') ? ' active' : '' }}" href="{{route('admin.offline_exam')}}">Offline Exam</a>
                                        @endif
                                        @if(isUserPermitted('offline-exam-question-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/offline_exam_result/question') ? ' active' : '' }}" href="{{route('admin.offline_exam_result.question')}}">Offline Exam Question</a>
                                        @endif
                                        @if(isUserPermitted('offline-exam-result-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/offline_exam_result/upload') ? ' active' : '' }}" href="{{route('admin.offline_exam_result.upload')}}">Offline Exam Result</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('online-exam', 'menu'))
                            
                                <a class="nav-link collapsed
                                
                                    {{ request()->is('admin/online_exam') ? ' active' : '' }} {{ request()->is('admin/online_exam/*') ? ' active' : '' }}
                                    {{ request()->is('admin/exam_location') ? ' active' : '' }} {{ request()->is('admin/exam_location/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseOnlineExam" aria-expanded="false" aria-controls="collapseOnlineExam">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Online Exam
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseOnlineExam" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('online-exam-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/online_exam') ? ' active' : '' }} {{ request()->is('admin/online_exam/*') ? ' active' : '' }}" href="{{route('admin.online_exam')}}">Online Exam</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('cms', 'menu'))

                                {{-- Sourav --}}
                                <a class="nav-link collapsed
                                    {{ request()->is('admin/banner') ? ' active' : '' }} {{ request()->is('admin/banner/*') ? ' active' : '' }}
                                    {{ request()->is('admin/scholarship') ? ' active' : '' }} {{ request()->is('admin/scholarship/*') ? ' active' : '' }}
                                    {{ request()->is('admin/test_series') ? ' active' : '' }} {{ request()->is('admin/test_series/*') ? ' active' : '' }}
                                    {{ request()->is('admin/interest') ? ' active' : '' }} {{ request()->is('admin/interest/*') ? ' active' : '' }}
                                    {{ request()->is('admin/head_quater') ? ' active' : '' }} {{ request()->is('admin/head_quater/*') ? ' active' : '' }}
                                    {{ request()->is('admin/real_story') ? ' active' : '' }} {{ request()->is('admin/real_story/*') ? ' active' : '' }}
                                    {{ request()->is('admin/success_story') ? ' active' : '' }} {{ request()->is('admin/success_story/*') ? ' active' : '' }}
                                    {{ request()->is('admin/hurry_now') ? ' active' : '' }} {{ request()->is('admin/hurry_now/*') ? ' active' : '' }}
                                    {{ request()->is('admin/learning') ? ' active' : '' }} {{ request()->is('admin/learning/*') ? ' active' : '' }}
                                    {{ request()->is('admin/asked_question') ? ' active' : '' }} {{ request()->is('admin/asked_question/*') ? ' active' : '' }}
                                    {{ request()->is('admin/gallery') ? ' active' : '' }} {{ request()->is('admin/gallery/*') ? ' active' : '' }}
                                    {{ request()->is('admin/mention') ? ' active' : '' }} {{ request()->is('admin/mention/*') ? ' active' : '' }}
                                    {{ request()->is('admin/footer') ? ' active' : '' }} {{ request()->is('admin/footer/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCms" aria-expanded="false" aria-controls="collapseCms">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        CMS
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseCms" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('banner-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/banner') ? ' active' : '' }} {{ request()->is('admin/banner/*') ? ' active' : '' }}" href="{{route('admin.banner')}}">Banners</a>
                                        @endif
                                        @if(isUserPermitted('scholarship-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/scholarship') ? ' active' : '' }} {{ request()->is('admin/scholarship/*') ? ' active' : '' }}" href="{{route('admin.scholarship')}}">Scholarship</a>
                                        @endif
                                        @if(isUserPermitted('test-series-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/test_series') ? ' active' : '' }} {{ request()->is('admin/test_series/*') ? ' active' : '' }}" href="{{route('admin.test_series')}}">New Light Test Series</a>
                                        @endif
                                        @if(isUserPermitted('interest-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/interest') ? ' active' : '' }} {{ request()->is('admin/interest/*') ? ' active' : '' }}" href="{{route('admin.interest')}}">Interest</a>
                                        @endif
                                        @if(isUserPermitted('head-quater-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/head_quater') ? ' active' : '' }} {{ request()->is('admin/head_quater/*') ? ' active' : '' }}" href="{{route('admin.head_quater')}}">Head Quater Video</a>
                                        @endif
                                        @if(isUserPermitted('real-story-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/real_story') ? ' active' : '' }} {{ request()->is('admin/real_story/*') ? ' active' : '' }}" href="{{route('admin.real_story')}}">Real Story</a>
                                        @endif
                                        @if(isUserPermitted('success-story-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/success_story') ? ' active' : '' }} {{ request()->is('admin/success_story/*') ? ' active' : '' }}" href="{{route('admin.success_story')}}">Success Story</a>
                                        @endif
                                        @if(isUserPermitted('hurry-now-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/hurry_now') ? ' active' : '' }} {{ request()->is('admin/hurry_now/*') ? ' active' : '' }}" href="{{route('admin.hurry_now')}}">Hurry Now</a>
                                        @endif
                                        @if(isUserPermitted('learning-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/learning') ? ' active' : '' }} {{ request()->is('admin/learning/*') ? ' active' : '' }}" href="{{route('admin.learning')}}">Comprehensive Learning</a>
                                        @endif
                                        @if(isUserPermitted('asked-question-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/asked_question') ? ' active' : '' }} {{ request()->is('admin/asked_question/*') ? ' active' : '' }}" href="{{route('admin.asked_question')}}">Frequently Asked Questions</a>
                                        @endif
                                        @if(isUserPermitted('gallery-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/gallery') ? ' active' : '' }} {{ request()->is('admin/gallery/*') ? ' active' : '' }}" href="{{route('admin.gallery')}}">Gallery</a>
                                        @endif
                                        @if(isUserPermitted('mention-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/mention') ? ' active' : '' }} {{ request()->is('admin/mention/*') ? ' active' : '' }}" href="{{route('admin.mention')}}">As Mentioned</a>
                                        @endif
                                        @if(isUserPermitted('footer-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/footer') ? ' active' : '' }} {{ request()->is('admin/footer/*') ? ' active' : '' }}" href="{{route('admin.footer')}}">Footer</a>
                                        @endif
                                    </nav>
                                </div>
                                {{-- Sourav --}}
                            @endif
                            <div class="drawer-menu-divider d-sm-none"></div>


                        </div>
                    </div>
                    <!-- Drawer footer        -->
                    <div class="drawer-footer border-top">
                        <div class="d-flex align-items-center">
                            <i class="material-icons text-muted">account_circle</i>
                            <div class="ms-3">
                                <div class="caption">Logged in as:</div>
                                <div class="small fw-500">{{session()->get('adminName')}}</div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- Layout content-->
            <div id="layoutDrawer_content">
                <!-- Main page content-->
                <main>
                    @yield('content')
                </main>
                <!-- Footer-->


            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- Load global scripts-->
        <script type="module" src="{{asset('')}}admin/js/material.js"></script>
        <script src="{{asset('')}}admin/js/scripts.js"></script>
        <!--  Load Chart.js via CDN-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.2/chart.min.js" crossorigin="anonymous"></script>
        <!--  Load Chart.js customized defaults-->
        <script src="{{asset('')}}admin/js/charts/chart-defaults.js"></script>
        <!--  Load chart demos for this page-->
        <script src="{{asset('')}}admin/js/charts/demos/chart-pie-demo.js"></script>
        <script src="{{asset('')}}admin/js/charts/demos/dashboard-chart-bar-grouped-demo.js"></script>
        <!-- Load Simple DataTables Scripts-->
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('')}}admin/js/datatables/datatables-simple-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.nocss.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{asset('')}}admin/ckeditor5/ckeditor.js"></script>

        <style type="text/css">
            .select2-dropdown {
                z-index: 99999;
            }
        </style>
        @yield('js_after')
    </body>
</html>
