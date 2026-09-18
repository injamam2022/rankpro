<?php 
    $auth_data = Auth::user();
    
    $user_name = "";
    
    if(session()->get('parant_login_type') == "P"){
        $user_name = $auth_data->father_full_name;
    }else{
        $user_name = $auth_data->first_name." ".$auth_data->last_name;
    }
    
?>

    <div class="logo dashboardMenuPL">
      <a href="{{ route('index') }}">
        <img src="{{ asset('') }}web/images/logo-dashboard.png" class="img-fluid dashboardLogo_dx" alt="">
        <img src="{{ asset('') }}web/images/logo-dashboard-ph.png" class="img-fluid dashboardLogo_ph" alt="">
      </a>
    </div>
    <div class="dashboardAvatarBlock dashboardMenuPL">
        <div class="dashboardAvatar">
            <img src="{{asset('')}}uploads/profileImage/{{$auth_data->profile_img}}" class="img-fluid" alt="">
        </div>
        <div class="dashboardName">{{$user_name}}</div>
        <img src="{{ asset('') }}web/images/dashboardCheck.png" class="img-fluid dashboardCheck" alt="">
    </div>
    <ul class="dashboardMenu dashboardMenuMain dashboardMenuPL list-unstyled mb-0">
        <li class="{{ request()->is('strong-areas') ? ' active' : '' }} {{ request()->is('strong-areas/*') ? ' active' : '' }}">
            <a href="{{ route('strong_areas') }}"><img src="{{ asset('') }}web/images/d_sa_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Strong Areas</span></a>
        </li>
        <li class="{{ request()->is('can-improve') ? ' active' : '' }} {{ request()->is('can-improve/*') ? ' active' : '' }}">
            <a href="{{ route('can_improve') }}"><img src="{{ asset('') }}web/images/d_ci_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Can Improve</span></a>
        </li>
        <li class="{{ request()->is('need-to-work-hard') ? ' active' : '' }} {{ request()->is('need-to-work-hard/*') ? ' active' : '' }}">
            <a href="{{ route('need_to_work_hard') }}"><img src="{{ asset('') }}web/images/d_hw_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Need to work hard</span></a>
        </li>

        <li class="{{ request()->is('notice') ? ' active' : '' }} {{ request()->is('notice/*') ? ' active' : '' }}">
            <a href="{{ route('notice') }}"><img src="{{ asset('') }}web/images/d_layout_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Notice</span></a>
        </li>

        <li class="{{ request()->is('blank') ? ' active' : '' }} {{ request()->is('blank/*') ? ' active' : '' }}">
            <a href="{{ route('blank') }}"><img src="{{ asset('') }}web/images/d_layout_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Blank</span></a>
        </li>

        <li class="{{ request()->is('blank_two') ? ' active' : '' }} {{ request()->is('blank_two/*') ? ' active' : '' }}">
            <a href="{{ route('blank_two') }}"><img src="{{ asset('') }}web/images/d_layout_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Blank Two</span></a>
        </li>

        <li class="{{ request()->is('blank_three') ? ' active' : '' }} {{ request()->is('blank_three/*') ? ' active' : '' }}">
            <a href="{{ route('blank_three') }}"><img src="{{ asset('') }}web/images/d_layout_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Blank Three</span></a>
        </li>

        <li class="{{ request()->is('dashboard') ? ' active' : '' }} {{ request()->is('dashboard/*') ? ' active' : '' }}">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('') }}web/images/d_layout_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Dashboard</span></a>
        </li>
        <li class="{{ request()->is('profile') ? ' active' : '' }} {{ request()->is('profile/*') ? ' active' : '' }}">
            <a href="{{ route('profile') }}"><img src="{{ asset('') }}web/images/d_user_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Profile</span></a>
        </li>
        <!-- <li class="{{ request()->is('my-courses') ? ' active' : '' }} {{ request()->is('my-courses/*') ? ' active' : '' }}">
            <a href="{{ route('my_courses') }}"><img src="{{ asset('') }}web/images/d_onlineLearning_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>My Courses</span></a>
        </li> -->
        <!-- <li class="{{ request()->is('schedule') ? ' active' : '' }} {{ request()->is('schedule/*') ? ' active' : '' }}">
            <a href="{{ route('schedule') }}"><img src="{{ asset('') }}web/images/d_presentation_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Schedule</span></a>
        </li> -->
        <li class="{{ request()->is('result') ? ' active' : '' }} {{ request()->is('result/*') ? ' active' : '' }}">
            <a href="{{ route('result') }}"><img src="{{ asset('') }}web/images/d_mission_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Result</span></a>
        </li>
        <li class="{{ request()->is('rankers-for-rankers') ? ' active' : '' }} {{ request()->is('rankers-for-rankers/*') ? ' active' : '' }}">
            <a href="{{ route('rankers_for_rankers') }}"><img src="{{ asset('') }}web/images/d_ranking_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Rankers for Rankers</span></a>
        </li>
        <!--<li class="{{ request()->is('plans') ? ' active' : '' }} {{ request()->is('plans/*') ? ' active' : '' }}">-->
        <!--    <a href="{{ route('plans') }}"><img src="{{ asset('') }}web/images/d_planning_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Plans</span></a>-->
        <!--</li>-->
       <!--  <li class="{{ request()->is('terms-and-condition') ? ' active' : '' }} {{ request()->is('terms-and-condition/*') ? ' active' : '' }}">
            <a href="{{ route('terms_and_condition') }}"><img src="{{ asset('') }}web/images/d_terms_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Terms & Conditions</span></a>
        </li> -->
        <li class="{{ request()->is('report-problem') ? ' active' : '' }} {{ request()->is('report-problem/*') ? ' active' : '' }}">
            <a href="{{ route('report_problem') }}"><img src="{{ asset('') }}web/images/d_headset_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Report a problem</span></a>
        </li>
        <li class="{{ request()->is('logout') ? ' active' : '' }} {{ request()->is('logout/*') ? ' active' : '' }}">
            <a href="{{ route('logout') }}"><img src="{{ asset('') }}web/images/d_exit_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Logout</span></a>
        </li>
    </ul>