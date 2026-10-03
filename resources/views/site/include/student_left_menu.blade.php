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

    $menuExamId = $exam_id ?? request('exam_id');

    $onExamInsightPages = request()->is('exam-answers-analytics*')
        || request()->is('exam-strength*')
        || request()->is('exam-weakness*')
        || request()->is('exam-progress-report*')
        || request()->is('exam-personal-coach*');

    $showExamSub = !empty($menuExamId) || $onExamInsightPages;
    $examSubOpen = $showExamSub && (
        $onExamInsightPages
        || request()->is('exam-given')
        || request()->is('exam-given/*')
    );

    $insightsOpen = request()->is('answers-analytics*')
        || request()->is('strength*')
        || request()->is('weakness*')
        || request()->is('progress-report*')
        || request()->is('personal-coach*');
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
        <a href="{{ route('profile') }}" class="dashboardAvatarBlock dashboardAvatarLink" title="View &amp; edit profile">
            <div class="dashboardAvatar">
                @php
                    $menuProfileImg = !empty($auth_data->profile_img)
                        ? basename(str_replace('\\', '/', $auth_data->profile_img))
                        : '';
                @endphp
                @if($menuProfileImg !== '')
                    <img src="{{ asset('uploads/profileImage/'.$menuProfileImg) }}" class="img-fluid" alt="Profile" style="object-fit: cover;">
                @else
                    <img src="{{ asset('') }}web/images/dashboardCheck.png" class="img-fluid" alt="Profile" style="object-fit: contain; opacity:.35;">
                @endif
            </div>
            <div class="dashboardAvatarMeta">
                <div class="dashboardName" style="word-break: break-word;">{{ $user_name }}</div>
                <div class="dashboardProfileHint">My Profile</div>
            </div>
            <img src="{{ asset('') }}web/images/dashboardCheck.png" class="img-fluid dashboardCheck" alt="">
        </a>
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
        <li class="{{ request()->is('dashboard') || request()->is('dashboard/*') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('') }}web/images/d_layout_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Dashboard</span></a>
        </li>

        <li class="{{ request()->is('notice') || request()->is('notice/*') ? 'active' : '' }}">
            <a href="{{ route('notice') }}"><img src="{{ asset('') }}web/images/d_notice_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Notices</span></a>
        </li>

        <li class="{{ request()->is('custom-test') || request()->is('custom-test/*') ? 'active' : '' }}">
            <a href="{{ route('custom_test') }}"><img src="{{ asset('') }}web/images/d_ex_given_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Custom Test</span></a>
        </li>

        <li class="menuHasSub {{ ($examSubOpen || request()->is('exam-given') || request()->is('exam-given/*')) ? 'active' : '' }} {{ $examSubOpen ? 'open' : '' }}">
            <div class="menuItemRow">
                <a href="{{ route('exam_given') }}"><img src="{{ asset('') }}web/images/d_ex_given_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Exams Given</span></a>
                @if($showExamSub)
                    <button type="button" class="menuCollapseBtn" aria-label="Toggle Exams Given submenu">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                @endif
            </div>
            @if($showExamSub)
                <ul class="dashboardMenu dashboardMenuPL dashboardMenuSub list-unstyled mb-0 {{ $examSubOpen ? 'is-open' : '' }}">
                    <li class="{{ request()->is('exam-answers-analytics*') ? 'active' : '' }}">
                        <a href="{{ route('exam_answers_analytics', ['exam_id' => $menuExamId]) }}"><img src="{{ asset('') }}web/images/d_ans_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Answers Analytics</span></a>
                    </li>
                    <li class="{{ request()->is('exam-strength*') ? 'active' : '' }}">
                        <a href="{{ route('exam_strength', ['exam_id' => $menuExamId]) }}"><img src="{{ asset('') }}web/images/d_strength_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Strength</span></a>
                    </li>
                    <li class="{{ request()->is('exam-weakness*') ? 'active' : '' }}">
                        <a href="{{ route('exam_weakness', ['exam_id' => $menuExamId]) }}"><img src="{{ asset('') }}web/images/d_weakness_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Weakness</span></a>
                    </li>
                    <li class="{{ request()->is('exam-progress-report*') ? 'active' : '' }}">
                        <a href="{{ route('exam_progress_report', ['exam_id' => $menuExamId]) }}"><img src="{{ asset('') }}web/images/d_progress_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Progress Report</span></a>
                    </li>
                    <li class="{{ request()->is('exam-personal-coach*') ? 'active' : '' }}">
                        <a href="{{ route('exam_personal_coach', ['exam_id' => $menuExamId]) }}"><img src="{{ asset('') }}web/images/d_user_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Personal Coach</span></a>
                    </li>
                </ul>
            @endif
        </li>

        <li class="{{ request()->is('upcoming-exam') || request()->is('upcoming-exam/*') ? 'active' : '' }}">
            <a href="{{ route('upcoming_exam') }}"><img src="{{ asset('') }}web/images/d_upcoming_ex_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Upcoming Exam</span></a>
        </li>

        <li class="menuHasSub {{ $insightsOpen ? 'active open' : '' }}">
            <div class="menuItemRow">
                <a href="javascript:void(0)" class="menuCollapseTrigger"><img src="{{ asset('') }}web/images/d_ans_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Insights</span></a>
                <button type="button" class="menuCollapseBtn" aria-label="Toggle Insights submenu">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <ul class="dashboardMenu dashboardMenuPL dashboardMenuSub list-unstyled mb-0 {{ $insightsOpen ? 'is-open' : '' }}">
                <li class="{{ request()->is('answers-analytics*') ? 'active' : '' }}">
                    <a href="{{ route('answers_analytics') }}"><img src="{{ asset('') }}web/images/d_ans_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Answers Analytics</span></a>
                </li>
                <li class="{{ request()->is('strength*') ? 'active' : '' }}">
                    <a href="{{ route('strength') }}"><img src="{{ asset('') }}web/images/d_strength_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Strength</span></a>
                </li>
                <li class="{{ request()->is('weakness*') ? 'active' : '' }}">
                    <a href="{{ route('weakness') }}"><img src="{{ asset('') }}web/images/d_weakness_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Weakness</span></a>
                </li>
                <li class="{{ request()->is('progress-report*') ? 'active' : '' }}">
                    <a href="{{ route('progress_report') }}"><img src="{{ asset('') }}web/images/d_progress_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Progress Report</span></a>
                </li>
                <li class="{{ request()->is('personal-coach*') ? 'active' : '' }}">
                    <a href="{{ route('personal_coach') }}"><img src="{{ asset('') }}web/images/d_user_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Personal Coach</span></a>
                </li>
            </ul>
        </li>

        <li class="{{ request()->is('common_confusion') || request()->is('common_confusion/*') ? 'active' : '' }}">
            <a href="{{ route('common_confusion') }}"><img src="{{ asset('') }}web/images/d_conf_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Mistake Monitor</span></a>
        </li>

        <li class="">
            <a href="#"><img src="{{ asset('') }}web/images/d_ai_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>RankPro AI</span></a>
        </li>

        <li class="{{ request()->is('air') || request()->is('air/*') ? 'active' : '' }}">
            <a href="{{ route('air') }}"><img src="{{ asset('') }}web/images/d_air_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>AIR</span></a>
        </li>
        <li class="{{ request()->is('rankers-for-rankers') || request()->is('rankers-for-rankers/*') ? 'active' : '' }}">
            <a href="{{ route('rankers_for_rankers') }}"><img src="{{ asset('') }}web/images/d_one_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>1 to 1 Ranker Motivation</span></a>
        </li>
        <li class="{{ request()->is('report-problem') || request()->is('report-problem/*') ? 'active' : '' }}">
            <a href="{{ route('report_problem') }}"><img src="{{ asset('') }}web/images/d_headset_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Report a problem</span></a>
        </li>

        <li class="{{ request()->is('plans') || request()->is('plans/*') ? 'active' : '' }}">
            <a href="{{ route('plans') }}"><img src="{{ asset('') }}web/images/d_upgrade_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Upgrade</span></a>
        </li>

        <li class="{{ request()->is('profile') || request()->is('profile/*') ? 'active' : '' }}">
            <a href="{{ route('profile') }}"><img src="{{ asset('') }}web/images/d_user_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Profile</span></a>
        </li>

        <li class="{{ request()->is('logout') || request()->is('logout/*') ? 'active' : '' }}">
            <a href="{{ route('logout') }}"><img src="{{ asset('') }}web/images/d_exit_ic.png" class="img-fluid dashboardMenu_ic" alt=""> <span>Logout</span></a>
        </li>
    </ul>

    <script>
      (function () {
        function toggleMenuItem(li) {
          if (!li) return;
          var sub = li.querySelector(':scope > .dashboardMenuSub');
          if (!sub) return;
          var open = li.classList.toggle('open');
          sub.classList.toggle('is-open', open);
        }

        document.querySelectorAll('.dashboardMenuMain .menuCollapseBtn').forEach(function (btn) {
          btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenuItem(btn.closest('li.menuHasSub'));
          });
        });

        document.querySelectorAll('.dashboardMenuMain .menuCollapseTrigger').forEach(function (link) {
          link.addEventListener('click', function (e) {
            e.preventDefault();
            toggleMenuItem(link.closest('li.menuHasSub'));
          });
        });
      })();
    </script>
