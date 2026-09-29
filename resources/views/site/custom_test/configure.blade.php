<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Configure Custom Test - RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/dashboard.css" rel="stylesheet">
    @include('site.include.head_meta')
    <style>
        .ct-wrap { max-width: 640px; margin: 0 auto; padding-bottom: 3rem; }
        .ct-back { display: inline-flex; align-items: center; gap: .45rem; color: #344054; text-decoration: none; font-weight: 500; margin-bottom: .85rem; }
        .ct-back:hover { color: #5b4bb7; }
        .ct-title { font-size: 1.35rem; font-weight: 600; color: #101828; margin-bottom: .25rem; }
        .ct-sub { color: #667085; font-size: .9rem; margin-bottom: 1.25rem; }
        .ct-step-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
        .ct-step-label { font-size: .8rem; color: #667085; }
        .ct-step-label strong { color: #101828; display: block; font-size: .95rem; font-weight: 600; }
        .ct-progress { display: flex; gap: .35rem; width: 88px; }
        .ct-progress span { height: 6px; flex: 1; border-radius: 999px; background: #e4e7ec; }
        .ct-progress span.on { background: #2f6fed; }
        .ct-card { border: 1px solid #eaecf0; border-radius: 1rem; background: #fff; padding: 1.15rem; }
        .ct-label { display: block; font-size: .8125rem; font-weight: 500; color: #344054; margin-bottom: .4rem; }
        .ct-input, .ct-select {
            width: 100%; border: 1px solid #d0d5dd; border-radius: .65rem; padding: .7rem .85rem;
            font-size: .9rem; margin-bottom: 1rem; background: #fff;
        }
        .ct-check { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.25rem; font-size: .875rem; color: #344054; }
        .ct-check input { accent-color: #5b4bb7; width: 1rem; height: 1rem; }
        .ct-btn {
            width: 100%; border: 0; border-radius: .85rem; padding: .95rem 1rem; font-weight: 600;
            background: #1f1b4d; color: #fff; font-size: .95rem; cursor: pointer;
        }
        .ct-btn:hover { background: #16133a; }
        .ct-alert { border-radius: .75rem; padding: .75rem 1rem; margin-bottom: 1rem; font-size: .875rem; background: #fef3f2; color: #b42318; border: 1px solid #fecdca; }
        .ct-summary {
            background: #f5f8ff; border: 1px solid #dbe7ff; border-radius: .85rem; padding: .85rem 1rem;
            margin-bottom: 1rem; font-size: .875rem; color: #1d4ed8;
        }
        .ct-summary ul { margin: .5rem 0 0; padding-left: 1.1rem; color: #344054; }
        .ct-summary li { margin-bottom: .2rem; }
    </style>
</head>
<body>
@include('site.include.body_meta')
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<section id="dashboard">
    <div class="container-fluid">
        <div class="dashboardAll dashboardPh">
            <div class="dashboardLeft">
                @include('site.include.student_left_menu')
            </div>
            <div class="dashboardRight">
                <div class="dashboardRightBody">
                    <div class="ct-wrap">
                        <a class="ct-back" href="{{ route('custom_test') }}"><i class="fas fa-arrow-left"></i> Back</a>
                        <div class="ct-title">Configure test</div>
                        <div class="ct-sub">{{ $subjectNames }} · {{ $totalQuestions }} questions</div>

                        <div class="ct-step-row">
                            <div class="ct-step-label">
                                <strong>Step 2 of 2</strong>
                                Set duration and start
                            </div>
                            <div class="ct-progress"><span class="on"></span><span class="on"></span></div>
                        </div>

                        @if(session('error'))
                            <div class="ct-alert">{{ session('error') }}</div>
                        @endif

                        <div class="ct-summary">
                            Your selection
                            <ul>
                                @foreach($plan as $item)
                                    <li>{{ $item['name'] }} — {{ $item['question_count'] }} Qs
                                        @if(!empty($item['selection']))
                                            (filtered chapters)
                                        @else
                                            (all chapters)
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <form method="post" action="{{ route('custom_test.generate') }}" class="ct-card">
                            @csrf
                            <label class="ct-label" for="test_name">Test name</label>
                            <input class="ct-input" type="text" id="test_name" name="name" maxlength="80"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Physics revision">

                            <label class="ct-label" for="duration_minutes">Duration (minutes)</label>
                            <input class="ct-input" type="number" id="duration_minutes" name="duration_minutes"
                                   min="5" max="300" value="{{ old('duration_minutes', $defaultDuration) }}" required>

                            <label class="ct-label" for="difficulty">Difficulty</label>
                            <select class="ct-select" id="difficulty" name="difficulty">
                                <option value="">Mixed</option>
                                <option value="1" @selected(old('difficulty') == '1')>Easy</option>
                                <option value="2" @selected(old('difficulty') == '2')>Medium</option>
                                <option value="3" @selected(old('difficulty') == '3')>Hard</option>
                            </select>

                            <label class="ct-check">
                                <input type="checkbox" name="negative_marking" value="1" @checked(old('negative_marking'))>
                                Apply negative marking (−1)
                            </label>

                            <button type="submit" class="ct-btn">Create test</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('site.include.call_to_action')
@include('site.include.back_to_top')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
</body>
</html>
