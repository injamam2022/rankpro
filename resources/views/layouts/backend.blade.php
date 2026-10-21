<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
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
          .admin-list-bar {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 16px;
          }
          .admin-list-field {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 4px;
            min-width: 0;
          }
          .admin-list-field > label {
            display: block;
            font-size: 13px;
            line-height: 1.2;
            color: #64748b;
            margin: 0;
            font-weight: 400;
          }
          .admin-list-bar .form-control {
            height: 38px;
            min-width: 180px;
          }
          .admin-list-bar input.form-control[type="search"] {
            width: 260px;
            max-width: 100%;
            min-width: 220px;
          }
          .admin-list-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 38px;
          }
          .admin-list-bar .rp-act-add,
          .admin-list-bar .rp-act-ghost {
            height: 38px;
          }
          .admin-list-pager {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
          }
          .admin-list-pager .pagination { margin: 0; }
          .admin-when { white-space: nowrap; font-size: 13px; color: #475569; }
          .rp-actions {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: nowrap;
          }
          .rp-act {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            text-decoration: none !important;
            border: 1px solid transparent;
            transition: transform .12s ease, background .12s ease, color .12s ease, box-shadow .12s ease;
            flex-shrink: 0;
            line-height: 1;
            cursor: pointer;
          }
          .rp-act .material-icons {
            font-size: 18px;
            width: 18px;
            height: 18px;
            line-height: 18px;
          }
          .rp-act:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
          }
          .rp-act-edit { color: #6d28d9; background: #f3e8ff; }
          .rp-act-edit:hover { color: #fff; background: #7c3aed; }
          .rp-act-delete { color: #b91c1c; background: #fee2e2; }
          .rp-act-delete:hover { color: #fff; background: #dc2626; }
          .rp-act-view { color: #1d4ed8; background: #dbeafe; }
          .rp-act-view:hover { color: #fff; background: #2563eb; }
          .rp-act-preview { color: #0f766e; background: #ccfbf1; }
          .rp-act-preview:hover { color: #fff; background: #0d9488; }
          .rp-act-on { color: #15803d; background: #dcfce7; }
          .rp-act-on:hover { color: #fff; background: #16a34a; }
          .rp-act-off { color: #b45309; background: #ffedd5; }
          .rp-act-off:hover { color: #fff; background: #d97706; }
          .rp-act-swap { color: #c2410c; background: #ffedd5; }
          .rp-act-swap:hover { color: #fff; background: #ea580c; }
          .rp-act-download { color: #1e40af; background: #e0e7ff; }
          .rp-act-download:hover { color: #fff; background: #4f46e5; }
          .rp-act-add {
            width: auto;
            height: 36px;
            padding: 0 14px;
            gap: 4px;
            background: #7c3aed;
            color: #fff !important;
            font-weight: 600;
            font-size: 14px;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
          }
          .rp-act-add:hover { background: #6d28d9; color: #fff !important; }
          .rp-act-add .material-icons { font-size: 20px; width: 20px; height: 20px; line-height: 20px; }
          .rp-act-label {
            width: auto;
            height: 36px;
            padding: 0 12px;
            gap: 4px;
            font-weight: 600;
            font-size: 13px;
          }
          .rp-act-ghost {
            width: auto;
            height: 36px;
            padding: 0 12px;
            gap: 4px;
            background: #f1f5f9;
            color: #334155;
            font-weight: 600;
            font-size: 13px;
            border: 0;
          }
          .rp-act-ghost:hover { background: #e2e8f0; color: #0f172a; }
          .rp-diff-radios {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
          }
          .rp-diff {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            font-weight: 600;
            margin: 0;
            user-select: none;
          }
          .rp-diff input { accent-color: #7c3aed; }
          .rp-diff.is-hard { background: #fef2f2; color: #991b1b; }
          .rp-diff.is-medium { background: #fff7ed; color: #9a3412; }
          .rp-diff.is-easy { background: #f0fdf4; color: #166534; }
          .rp-diff:has(input:checked) {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.16);
          }
          .drawer-light .drawer-menu > .nav > .nav-link.active {
            color: #6d28d9;
            font-weight: 600;
          }
          .drawer-light .drawer-menu > .nav > .nav-link.active .nav-link-icon,
          .drawer-light .drawer-menu > .nav > .nav-link.active .drawer-collapse-arrow {
            color: #6d28d9;
          }
          .drawer-light .drawer-menu .drawer-menu-nested .nav-link.active {
            color: #6d28d9;
            background: #f3e8ff;
            font-weight: 600;
            border-left: 3px solid #7c3aed;
            border-radius: 0 8px 8px 0;
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
                                    {{ request()->is('admin/teacher') ? ' active' : '' }} {{ request()->is('admin/teacher/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAdminUser" aria-expanded="false" aria-controls="collapseAdminUser">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Admin User
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseAdminUser" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        <a class="nav-link {{ request()->is('admin/admin-user') ? ' active' : '' }} {{ request()->is('admin/admin-user/*') ? ' active' : '' }}" href="{{route('admin.admin_user')}}">Admin User</a>
                                        <a class="nav-link {{ request()->is('admin/teacher') ? ' active' : '' }} {{ request()->is('admin/teacher/*') ? ' active' : '' }}" href="{{route('admin.teacher')}}">Teacher</a>
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
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('notice', 'menu'))

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/notice') ? ' active' : '' }} {{ request()->is('admin/notice/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseNotice" aria-expanded="false" aria-controls="collapseNotice">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Notice
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseNotice" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        @if(isUserPermitted('notice-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/notice') ? ' active' : '' }} {{ request()->is('admin/notice/*') ? ' active' : '' }}" href="{{route('admin.notice')}}">Notice</a>
                                        @endif
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
                                        {{-- Hidden from sidebar; keep links for later use --}}
                                        @if(false && isUserPermitted('payment-test-series-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/payment/test_series') ? ' active' : '' }} {{ request()->is('admin/payment/test_series_detail') ? ' active' : '' }} " href="{{route('admin.payment.test_series')}}">Test Series</a>
                                        @endif
                                        @if(isUserPermitted('payment-ranker-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/payment/ranker') ? ' active' : '' }} {{ request()->is('admin/payment/ranker_detail') ? ' active' : '' }}" href="{{route('admin.payment.ranker')}}">Ranker</a>
                                        @endif
                                        @if(false && isUserPermitted('payment-new-light-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/payment/new_light') ? ' active' : '' }} {{ request()->is('admin/payment/new_light_detail') ? ' active' : '' }}" href="{{route('admin.payment.new_light')}}">New Light</a>
                                        @endif
                                    </nav>
                                </div>
                            @endif

                            @if(session()->get('admmin_is_super') == 'T')

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/question') ? ' active' : '' }} {{ request()->is('admin/question/*') ? ' active' : '' }}
                                    {{ request()->is('admin/question-paper') ? ' active' : '' }} {{ request()->is('admin/question-paper/*') ? ' active' : '' }}
                                    {{ request()->is('admin/question-upload') ? ' active' : '' }} {{ request()->is('admin/question-upload/*') ? ' active' : '' }}
                                    {{ request()->is('admin/custom-test-bank') ? ' active' : '' }} {{ request()->is('admin/custom-test-bank/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseQuestion" aria-expanded="false" aria-controls="collapseQuestion">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Question
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseQuestion" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        <a class="nav-link {{ request()->is('admin/question') ? ' active' : '' }} {{ request()->is('admin/question/*') ? ' active' : '' }}" href="{{route('admin.question')}}">Question</a>
                                        <a class="nav-link {{ request()->is('admin/question-upload') ? ' active' : '' }} {{ request()->is('admin/question-upload/*') ? ' active' : '' }}" href="{{route('admin.question.upload')}}">Question Upload</a>
                                        <a class="nav-link {{ request()->is('admin/question-paper') ? ' active' : '' }} {{ request()->is('admin/question-paper/*') ? ' active' : '' }}" href="{{route('admin.question_paper')}}">Question Bank</a>
                                        <a class="nav-link {{ request()->is('admin/custom-test-bank') ? ' active' : '' }} {{ request()->is('admin/custom-test-bank/*') ? ' active' : '' }}" href="{{ route('admin.custom_test_bank') }}">Custom Test Bank</a>
                                    </nav>
                                </div>
                            @endif
                            
                            @if(isUserPermitted('question', 'menu'))

                                <a class="nav-link collapsed
                                    {{ request()->is('admin/question') ? ' active' : '' }} {{ request()->is('admin/question/*') ? ' active' : '' }}
                                    {{ request()->is('admin/subject') ? ' active' : '' }} {{ request()->is('admin/subject/*') ? ' active' : '' }}
                                    {{ request()->is('admin/chapter') ? ' active' : '' }} {{ request()->is('admin/chapter/*') ? ' active' : '' }}
                                    {{ request()->is('admin/topic') ? ' active' : '' }} {{ request()->is('admin/topic/*') ? ' active' : '' }}
                                    {{ request()->is('admin/sub-topic') ? ' active' : '' }} {{ request()->is('admin/sub-topic/*') ? ' active' : '' }}
                                    {{ request()->is('admin/source') ? ' active' : '' }} {{ request()->is('admin/source/*') ? ' active' : '' }}
                                    {{ request()->is('admin/question-source') ? ' active' : '' }} {{ request()->is('admin/question-source/*') ? ' active' : '' }}
                                    {{ request()->is('admin/question-upload') ? ' active' : '' }} {{ request()->is('admin/question-upload/*') ? ' active' : '' }}
                                    {{ request()->is('admin/question-paper') ? ' active' : '' }} {{ request()->is('admin/question-paper/*') ? ' active' : '' }}
                                    {{ request()->is('admin/custom-test-bank') ? ' active' : '' }} {{ request()->is('admin/custom-test-bank/*') ? ' active' : '' }}

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
                                        @if(isUserPermitted('topic-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/topic') ? ' active' : '' }} {{ request()->is('admin/topic/*') ? ' active' : '' }}" href="{{route('admin.topic')}}">Topic</a>
                                        @endif
                                        @if(isUserPermitted('sub-topic-list', 'list') || isUserPermitted('topic-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/sub-topic') ? ' active' : '' }} {{ request()->is('admin/sub-topic/*') ? ' active' : '' }}" href="{{route('admin.sub_topic')}}">Sub Topic</a>
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
                                        @if(isUserPermitted('question-paper-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/question-paper') ? ' active' : '' }} {{ request()->is('admin/question-paper/*') ? ' active' : '' }}" href="{{route('admin.question_paper')}}">Question Bank</a>
                                            <a class="nav-link {{ request()->is('admin/custom-test-bank') ? ' active' : '' }} {{ request()->is('admin/custom-test-bank/*') ? ' active' : '' }}" href="{{ route('admin.custom_test_bank') }}">Custom Test Bank</a>
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
                            
                            <a class="nav-link {{ request()->is('admin/batches*') ? 'active' : '' }}" href="{{ route('admin.batches') }}">
                                <div class="nav-link-icon"><i class="material-icons">groups</i></div>
                                Batch Master
                            </a>
                            <a class="nav-link {{ request()->is('admin/exam-assignments*') ? 'active' : '' }}" href="{{ route('admin.exam_assignments') }}">
                                <div class="nav-link-icon"><i class="material-icons">assignment_ind</i></div>
                                Test Assignments
                            </a>
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
                                    {{ request()->is('admin/cms') ? ' active' : '' }} {{ request()->is('admin/cms/*') ? ' active' : '' }}
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
                                    {{ request()->is('admin/video_tutorial') ? ' active' : '' }} {{ request()->is('admin/video_tutorial/*') ? ' active' : '' }}
                                    {{ request()->is('admin/dashboard-banner') ? ' active' : '' }} {{ request()->is('admin/dashboard-banner/*') ? ' active' : '' }}
                                    {{ request()->is('admin/dashboard-content') ? ' active' : '' }} {{ request()->is('admin/dashboard-content/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseCms" aria-expanded="false" aria-controls="collapseCms">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        CMS
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseCms" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                       
                                            <a class="nav-link {{ request()->is('admin/cms') ? ' active' : '' }} {{ request()->is('admin/cms/*') ? ' active' : '' }}" href="{{route('admin.cms')}}">CMS</a>
                                            
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
                                        @if(isUserPermitted('dashboard-banner-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/dashboard-banner') ? ' active' : '' }} {{ request()->is('admin/dashboard-banner/*') ? ' active' : '' }}" href="{{route('admin.dashboard_banner')}}">Dashboard Banner</a>
                                        @endif
                                        @if(isUserPermitted('dashboard-content-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/dashboard-content') ? ' active' : '' }} {{ request()->is('admin/dashboard-content/*') ? ' active' : '' }}" href="{{route('admin.dashboard_content')}}">Dashboard Content </a>
                                        @endif
                                        @if(isUserPermitted('video-tutorial-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/video_tutorial') ? ' active' : '' }} {{ request()->is('admin/video_tutorial/*') ? ' active' : '' }}" href="{{route('admin.video_tutorial')}}">How To Use Video</a>
                                        @endif
                                        @if(isUserPermitted('footer-list', 'list'))
                                            <a class="nav-link {{ request()->is('admin/footer') ? ' active' : '' }} {{ request()->is('admin/footer/*') ? ' active' : '' }}" href="{{route('admin.footer')}}">Website Settings</a>
                                        @endif
                                    </nav>
                                </div>
                                {{-- Sourav --}}
                            @endif

                            @if(session()->get('admmin_is_super') == 'SA')
                                <a class="nav-link collapsed
                                    {{ request()->is('admin/subscription') ? ' active' : '' }} {{ request()->is('admin/subscription/*') ? ' active' : '' }}
                                    {{ request()->is('admin/contact') ? ' active' : '' }} {{ request()->is('admin/contact/*') ? ' active' : '' }}
                                    {{ request()->is('admin/problem_type') ? ' active' : '' }} {{ request()->is('admin/problem_type/*') ? ' active' : '' }}
                                    {{ request()->is('admin/problem') ? ' active' : '' }} {{ request()->is('admin/problem/*') ? ' active' : '' }}

                                    " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                        Report
                                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                                </a>
                                <div class="collapse" id="collapseReport" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                    <nav class="drawer-menu-nested nav">
                                        
                                            <a class="nav-link {{ request()->is('admin/subscription') ? ' active' : '' }} {{ request()->is('admin/subscription/*') ? ' active' : '' }}" href="{{route('admin.subscription')}}">Subscription</a>
                                            <a class="nav-link {{ request()->is('admin/contact') ? ' active' : '' }} {{ request()->is('admin/contact/*') ? ' active' : '' }}" href="{{route('admin.contact')}}">Contact</a>
                                            <a class="nav-link {{ request()->is('admin/problem_type') ? ' active' : '' }} {{ request()->is('admin/problem_type/*') ? ' active' : '' }}" href="{{route('admin.problem_type')}}">Problem Type</a>
                                            <a class="nav-link {{ request()->is('admin/problem') ? ' active' : '' }} {{ request()->is('admin/problem/*') ? ' active' : '' }}" href="{{route('admin.problem')}}">Problem</a>
                                    </nav>
                                </div>
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
        <script>
        window.rpConfirmPost = function (url, message) {
            if (!confirm(message || 'Are you sure?')) {
                return false;
            }
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
            return false;
        };

        window.rpTaxonomySubmit = function (form, resetIds) {
            (resetIds || []).forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.value = '';
            });
            if (form) form.submit();
        };

        (function () {
            var drawer = document.getElementById('drawerAccordion');
            if (!drawer) return;
            var nested = Array.prototype.slice.call(drawer.querySelectorAll('.drawer-menu-nested .nav-link[href]'));
            var current = nested.filter(function (a) { return a.classList.contains('active'); });

            if (!current.length) {
                var path = window.location.pathname.replace(/\/+$/, '');
                var best = null, bestLen = 0;
                nested.forEach(function (a) {
                    var p = a.pathname.replace(/\/+$/, '');
                    if (p && (path === p || path.indexOf(p + '/') === 0) && p.length > bestLen) {
                        best = a;
                        bestLen = p.length;
                    }
                });
                if (best) {
                    best.classList.add('active');
                    current = [best];
                }
            }

            current.forEach(function (link) {
                var panel = link.closest('.collapse');
                if (!panel) return;
                panel.classList.add('show');
                var toggle = panel.previousElementSibling;
                if (toggle && toggle.classList.contains('nav-link')) {
                    toggle.classList.remove('collapsed');
                    toggle.classList.add('active');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });

            if (current[0]) {
                var box = current[0].parentElement;
                while (box && box !== document.body && box.scrollHeight <= box.clientHeight) {
                    box = box.parentElement;
                }
                if (box && box !== document.body) {
                    var offset = current[0].getBoundingClientRect().top - box.getBoundingClientRect().top;
                    box.scrollTop += offset - box.clientHeight / 2;
                }
            }
        })();
        </script>
    </body>
</html>
