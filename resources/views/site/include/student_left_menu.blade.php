<?php 
    $auth_data = Auth::user();
    
    $user_name = "";
    $xp_points = "";
    
    if(session()->get('parant_login_type') == "P"){
        $user_name = $auth_data->father_full_name;
    }else{
        $user_name = $auth_data->first_name." ".$auth_data->last_name;
        $xp_points = $auth_data->xp_points;
    }
    
?>
    <div class="menuBarBtn menuBarBtnClose">
        <i class="fas fa-times"></i>
    </div>
    
    <div class="logo dashboardMenuPL">
      <a href="{{ route('index') }}">
        <img src="{{ asset('') }}web/images/logo-dashboard.png" class="img-fluid dashboardLogo_dx" alt="">
      </a>
    </div>
    <div class="dashboardAvatarBlockAll dashboardMenuPL">
        <div class="dashboardAvatarBlock">
            <div class="dashboardAvatar">
                <a href="{{ route('profile') }}">
                    <img src="{{asset('')}}uploads/profileImage/{{$auth_data->profile_img}}" class="img-fluid" alt="" style="object-fit: contain;">
                </a>
            </div>
            <div class="dashboardName" style="word-break: break-word;">{{$user_name}}</div>
            <img src="{{ asset('') }}web/images/dashboardCheck.png" class="img-fluid dashboardCheck" alt="">
        </div>
        <div>
            <div class="dashboardXp">
                <!-- Xp points: <span>{{$xp_points}}</span> -->
            </div>
            <div class="dashboardShare">
                <div class="dashboardSocial">
                    <ul class="mb-0 list-unstyled">
                        @if($auth_data->facebook_link)
                            <li><a href="{{$auth_data->facebook_link}}" target="_blank"><img src="{{ asset('') }}web/images/fb.png" class="img-fluid dashboardSocialImg" alt=""></a></li>
                        @endif
                        @if($auth_data->instagram_link)
                            <li><a href="{{$auth_data->instagram_link}}" target="_blank"><img src="{{ asset('') }}web/images/insta.png" class="img-fluid dashboardSocialImg" alt=""></a></li>
                        @endif
                        @if($auth_data->youtube_link)
                            <li><a href="{{$auth_data->youtube_link}}" target="_blank"><img src="{{ asset('') }}web/images/yt.png" class="img-fluid dashboardSocialImg" alt=""></a></li>
                        @endif
                        @if($auth_data->twitter_link)                            
                            <li><a href="{{$auth_data->twitter_link}}" target="_blank"><img src="{{ asset('') }}web/images/tw.png" class="img-fluid dashboardSocialImg" alt=""></a></li>
                        @endif
                        @if($auth_data->whats_app_link)
                            <li><a href="{{$auth_data->whats_app_link}}" target="_blank"><img src="{{ asset('') }}web/images/wp.png" class="img-fluid dashboardSocialImg" alt=""></a></li>
                        @endif
                        @if($auth_data->linkedin_link)
                            <li><a href="{{$auth_data->linkedin_link}}" target="_blank"><img src="{{ asset('') }}web/images/linkd.png" class="img-fluid dashboardSocialImg" alt=""></a></li>
                        @endif
                    </ul>
                </div>
                <div class="dashboardBatch">
                    <!-- <img src="{{ asset('') }}web/images/batch.png" class="img-fluid dashboardBatchImg" alt=""> -->
                </div>
            </div>
        </div>
    </div>
    <ul class="dashboardMenu dashboardMenuMain dashboardMenuPL list-unstyled mb-0">
        <li class="{{ request()->is('dashboard') ? ' active' : '' }} {{ request()->is('dashboard/*') ? ' active' : '' }}">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('') }}web/images/d_layout_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Dashboard</span></a>
        </li>

        <li class="{{ request()->is('notice') ? ' active' : '' }} {{ request()->is('notice/*') ? ' active' : '' }}">
            <a href="{{ route('notice') }}"><img src="{{ asset('') }}web/images/d_notice_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Notices</span></a>
        </li>

        <li class="{{ request()->is('exam-given') ? ' active' : '' }} {{ request()->is('exam-given/*') ? ' active' : '' }}">
            <a href="{{ route('exam_given') }}"><img src="{{ asset('') }}web/images/d_ex_given_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Exams Given</span></a>
            @if(request()->is('exam-answers-analytics') || request()->is('exam-strength') || request()->is('exam-weakness') || request()->is('exam-progress-report') || request()->is('exam-personal-coach'))
                <ul class="dashboardMenu dashboardMenuPL dashboardMenuSub list-unstyled mb-0">
                    <li class="{{ request()->is('exam-answers-analytics') ? ' active' : '' }} {{ request()->is('exam-answers-analytics/*') ? ' active' : '' }}">
                        <a href="{{ route('exam_answers_analytics',['exam_id'=>$exam_id]) }}"><img src="{{ asset('') }}web/images/d_ans_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Answers Analytics</span></a>
                    </li>
                    <li class="{{ request()->is('exam-strength') ? ' active' : '' }} {{ request()->is('exam-strength/*') ? ' active' : '' }}">
                        <a href="{{ route('exam_strength',['exam_id'=>$exam_id]) }}"><img src="{{ asset('') }}web/images/d_strength_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Strength</span></a>
                    </li>
                    <li class="{{ request()->is('exam-weakness') ? ' active' : '' }} {{ request()->is('exam-weakness/*') ? ' active' : '' }}">
                        <a href="{{ route('exam_weakness',['exam_id'=>$exam_id]) }}"><img src="{{ asset('') }}web/images/d_weakness_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Weakness</span></a>
                    </li>
                    <li class="{{ request()->is('exam-progress-report') ? ' active' : '' }} {{ request()->is('exam-progress-report/*') ? ' active' : '' }}">
                        <a href="{{ route('exam_progress_report',['exam_id'=>$exam_id]) }}"><img src="{{ asset('') }}web/images/d_progress_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Progress Report</span></a>
                    </li>
                    <li class="{{ request()->is('exam-personal-coach') ? ' active' : '' }} {{ request()->is('exam-personal-coach/*') ? ' active' : '' }}">
                        <a href="{{ route('exam_personal_coach',['exam_id'=>$exam_id]) }}"><img src="{{ asset('') }}web/images/d_user_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Personal Coach</span></a>
                    </li>
                </ul>
            @endif
        </li>

        <li class="{{ request()->is('upcoming-exam') ? ' active' : '' }} {{ request()->is('upcoming-exam/*') ? ' active' : '' }}">
            <a href="{{ route('upcoming_exam') }}"><img src="{{ asset('') }}web/images/d_upcoming_ex_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Upcoming Exam</span></a>
        </li>

        <li class="{{ request()->is('answers-analytics') ? ' active' : '' }} {{ request()->is('answers-analytics/*') ? ' active' : '' }}">
            <a href="{{ route('answers_analytics') }}"><img src="{{ asset('') }}web/images/d_ans_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Answers Analytics</span></a>
        </li>
        
        <li class="{{ request()->is('strength') ? ' active' : '' }} {{ request()->is('strength/*') ? ' active' : '' }}">
            <a href="{{ route('strength') }}"><img src="{{ asset('') }}web/images/d_strength_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Strength</span></a>
        </li>
        
        <li class="{{ request()->is('weakness') ? ' active' : '' }} {{ request()->is('weakness/*') ? ' active' : '' }}">
            <a href="{{ route('weakness') }}"><img src="{{ asset('') }}web/images/d_weakness_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Weakness</span></a>
        </li>
        
        <li class="{{ request()->is('progress-report') ? ' active' : '' }} {{ request()->is('progress-report/*') ? ' active' : '' }}">
            <a href="{{ route('progress_report') }}"><img src="{{ asset('') }}web/images/d_progress_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Progress Report</span></a>
        </li>
        
        <li class="{{ request()->is('personal-coach') ? ' active' : '' }} {{ request()->is('personal-coach/*') ? ' active' : '' }}">
            <a href="{{ route('personal_coach') }}"><img src="{{ asset('') }}web/images/d_user_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Personal Coach</span></a>
        </li>

        <li class="{{ request()->is('common_confusion') ? ' active' : '' }} {{ request()->is('common_confusion/*') ? ' active' : '' }}">
            <a href="{{ route('common_confusion') }}"><img src="{{ asset('') }}web/images/d_conf_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Mistake Monitor</span></a>
        </li>
        
        <li class="">
            <a href="#"><img src="{{ asset('') }}web/images/d_ai_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>RankPro AI</span></a>
        </li>

        <li class="{{ request()->is('air') ? ' active' : '' }} {{ request()->is('air/*') ? ' active' : '' }}">
            <a href="{{ route('air') }}"><img src="{{ asset('') }}web/images/d_air_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>AIR</span></a>
        </li>
        <li class="{{ request()->is('rankers-for-rankers') ? ' active' : '' }} {{ request()->is('rankers-for-rankers/*') ? ' active' : '' }}">
            <a href="{{ route('rankers_for_rankers') }}"><img src="{{ asset('') }}web/images/d_one_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>1 to 1 Ranker Motivation</span></a>
        </li>
        <li class="{{ request()->is('report-problem') ? ' active' : '' }} {{ request()->is('report-problem/*') ? ' active' : '' }}">
            <a href="{{ route('report_problem') }}"><img src="{{ asset('') }}web/images/d_headset_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Report a problem</span></a>
        </li>
        
        <li class="{{ request()->is('plans') ? ' active' : '' }} {{ request()->is('plans/*') ? ' active' : '' }}">
            <a href="{{ route('plans') }}"><img src="{{ asset('') }}web/images/d_upgrade_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Upgrade</span></a>
        </li>
        
        
        
        <!--<li class="{{ request()->is('profile') ? ' active' : '' }} {{ request()->is('profile/*') ? ' active' : '' }}">-->
        <!--    <a href="{{ route('profile') }}"><img src="{{ asset('') }}web/images/d_user_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Profile</span></a>-->
        <!--</li>-->
        <!--<li class="{{ request()->is('result') ? ' active' : '' }} {{ request()->is('result/*') ? ' active' : '' }}">-->
        <!--    <a href="{{ route('result') }}"><img src="{{ asset('') }}web/images/d_mission_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Result</span></a>-->
        <!--</li>-->
        <!-- <li class="{{ request()->is('terms-and-condition') ? ' active' : '' }} {{ request()->is('terms-and-condition/*') ? ' active' : '' }}">-->
        <!--    <a href="{{ route('terms_and_condition') }}"><img src="{{ asset('') }}web/images/d_terms_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Terms & Conditions</span></a>-->
        <!--</li>-->
        
        
        <li class="{{ request()->is('logout') ? ' active' : '' }} {{ request()->is('logout/*') ? ' active' : '' }}">
            <a href="{{ route('logout') }}"><img src="{{ asset('') }}web/images/d_exit_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Logout</span></a>
        </li>
    </ul>