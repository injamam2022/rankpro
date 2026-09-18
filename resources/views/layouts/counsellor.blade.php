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
                <a class="navbar-brand me-auto" href="{{url('counsellor/dashboard')}}"><div class="text-uppercase font-monospace">RankPro</div></a>
                <!-- Navbar items-->
                <div class="d-flex align-items-center mx-3 me-lg-0">
                    <div class="d-flex">
                        <div class="dropdown">
                            <button class="btn btn-lg btn-icon dropdown-toggle" id="dropdownMenuProfile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(session()->get('counsellorProfileIcon'))
                                    <img src="{{asset('')}}uploads/counsellor/thumbnail/{{session()->get('counsellorProfileIcon')}}" style="width:50px;">
                                @else
                                    <i class="material-icons">person</i>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="dropdownMenuProfile">
                                <li>
                                    <a class="dropdown-item" href="{{route('counsellor.profile')}}">
                                        <i class="material-icons leading-icon">person</i>
                                        <div class="me-3">Profile</div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('counsellor.change_password')}}">
                                        <i class="material-icons leading-icon">password</i>
                                        <div class="me-3">Change Password</div>
                                    </a>
                                </li>
                                <!-- <li>
                                    <a class="dropdown-item" href="{{route('counsellor.setting')}}">
                                        <i class="material-icons leading-icon">settings</i>
                                        <div class="me-3">Settings</div>
                                    </a>
                                </li> -->
                                <li><hr class="dropdown-divider" /></li>
                                <li>
                                    <a class="dropdown-item" href="{{route('counsellor.logout')}}">
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

                            <a class="nav-link  {{ request()->is('counsellor/dashboard') ? ' active' : '' }}" href="{{route('counsellor.dashboard')}}">
                                <div class="nav-link-icon"><i class="material-icons">dashboard</i></div>
                                Dashboard
                            </a>

                            <a class="nav-link collapsed
                                {{ request()->is('counsellor/student') ? ' active' : '' }} {{ request()->is('counsellor/student/*') ? ' active' : '' }}

                                " href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseStudent" aria-expanded="false" aria-controls="collapseStudent">
                                    <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                                    Student
                                    <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                            </a>
                            <div class="collapse" id="collapseStudent" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                                <nav class="drawer-menu-nested nav">
                                    <a class="nav-link {{ request()->is('counsellor/student') ? ' active' : '' }} {{ request()->is('counsellor/student/*') ? ' active' : '' }}" href="{{route('counsellor.student')}}">Student List</a>
                                        <a class="nav-link {{ request()->is('counsellor/student-upload') ? ' active' : '' }} {{ request()->is('counsellor/student-upload/*') ? ' active' : '' }}" href="{{route('counsellor.student.upload')}}">Student Upload</a>
                                </nav>
                            </div>
                            
                            
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
