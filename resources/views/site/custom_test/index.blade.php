<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Custom Test - RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/dashboard.css" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    @include('site.include.head_meta')
    <style>
        .ct-wrap { max-width: 760px; margin: 0 auto; padding-bottom: 7rem; }
        .ct-title { font-size: 1.25rem; font-weight: 600; color: #101828; margin-bottom: .15rem; }
        .ct-sub { color: #667085; font-size: .875rem; margin-bottom: 1.1rem; }
        .ct-tabs { display: flex; gap: .4rem; background: #f2f4f7; padding: .3rem; border-radius: 999px; width: fit-content; margin-bottom: 1.25rem; }
        .ct-tab {
            border: 0; background: transparent; color: #475467; padding: .5rem 1.1rem;
            border-radius: 999px; font-weight: 500; font-size: .85rem; text-decoration: none;
        }
        .ct-tab.active { background: #1f1b4d; color: #fff; }
        .ct-step-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
        .ct-step-label { font-size: .78rem; color: #667085; }
        .ct-step-label strong { color: #101828; display: block; font-size: .92rem; font-weight: 600; }
        .ct-progress { display: flex; gap: .3rem; width: 72px; }
        .ct-progress span { height: 5px; flex: 1; border-radius: 999px; background: #e4e7ec; }
        .ct-progress span.on { background: #2f6fed; }
        .ct-card {
            border: 1px solid #eaecf0; border-radius: 1rem; background: #fff;
            padding: .95rem 1.1rem; margin-bottom: .75rem;
        }
        .ct-card.is-open { padding-bottom: 1.05rem; }
        .ct-card-head { display: flex; align-items: center; gap: .85rem; }
        .ct-card.is-open .ct-card-head { margin-bottom: .85rem; }
        .ct-subject-icon {
            width: 44px; height: 44px; border-radius: .8rem; display: grid; place-items: center;
            color: #fff; font-size: 1.05rem; flex-shrink: 0;
        }
        .ct-subject-name { font-weight: 600; font-size: 1rem; color: #101828; flex: 1; }
        .ct-show-chapters {
            color: #2f6fed; font-size: .85rem; font-weight: 500; text-decoration: none; white-space: nowrap;
        }
        .ct-show-chapters:hover { text-decoration: underline; color: #1d4ed8; }
        .ct-card-body { display: none; }
        .ct-card.is-open .ct-card-body { display: block; }
        .ct-presets { display: flex; flex-wrap: wrap; gap: .45rem; margin-bottom: .65rem; }
        .ct-preset {
            border: 1px solid #d0d5dd; background: #fff; color: #344054; border-radius: 999px;
            padding: .4rem .85rem; font-size: .8rem; font-weight: 500; cursor: pointer;
        }
        .ct-preset.active { background: #fff; border-color: #5b4bb7; color: #5b4bb7; box-shadow: 0 0 0 1px #5b4bb7; }
        .ct-manual {
            width: 100%; border: 1px solid #d0d5dd; border-radius: .55rem; padding: .55rem .75rem;
            font-size: .875rem; outline: 0; background: #fff; color: #101828;
        }
        .ct-manual:focus { border-color: #5b4bb7; }
        .ct-card-meta { font-size: .75rem; color: #98a2b3; margin-top: .45rem; }
        .ct-card-meta strong { color: #6941c6; font-weight: 500; }
        .ct-hint {
            font-size: .8rem; color: #667085; background: #f9fafb; border: 1px dashed #d0d5dd;
            border-radius: .75rem; padding: .75rem .9rem; margin-bottom: 1rem;
        }
        .ct-alert { border-radius: .75rem; padding: .75rem 1rem; margin-bottom: 1rem; font-size: .875rem; }
        .ct-alert-err { background: #fef3f2; color: #b42318; border: 1px solid #fecdca; }
        .ct-alert-ok { background: #ecfdf3; color: #067647; border: 1px solid #abefc6; }
        .ct-empty { color: #98a2b3; padding: 2rem 1rem; text-align: center; }
        .ct-attempt-list { display: flex; flex-direction: column; gap: .75rem; }
        .ct-attempt {
            display: flex; align-items: center; gap: .9rem;
            border: 1px solid #eaecf0; border-radius: 1rem; padding: .95rem 1.05rem;
            background: #fff; text-decoration: none; color: inherit;
            box-shadow: 0 6px 18px rgba(16,24,40,.04); transition: border-color .15s, box-shadow .15s;
        }
        .ct-attempt:hover { border-color: #d0d5dd; box-shadow: 0 8px 22px rgba(16,24,40,.07); color: inherit; }
        .ct-ring {
            --pct: 0; width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
            background: conic-gradient(#ef4444 calc(var(--pct) * 1%), #e4e7ec 0);
            display: grid; place-items: center; position: relative;
        }
        .ct-ring::before {
            content: ""; position: absolute; inset: 5px; border-radius: 50%; background: #fff;
        }
        .ct-ring-val {
            position: relative; z-index: 1; font-size: .62rem; font-weight: 600; color: #344054;
            line-height: 1; text-align: center;
        }
        .ct-attempt-name { font-weight: 600; color: #101828; font-size: .98rem; flex: 1; min-width: 0; }
        .ct-attempt-date { font-size: .82rem; color: #98a2b3; white-space: nowrap; margin-left: auto; }
        .ct-btn {
            border: 0; border-radius: .65rem; padding: .55rem 1rem; font-weight: 500; font-size: .875rem;
            text-decoration: none; display: inline-flex; align-items: center; gap: .35rem; cursor: pointer;
        }
        .ct-btn-soft { background: #f4ebff; color: #5b4bb7; }
        .ct-btn-soft:hover { background: #ebe0ff; color: #5b4bb7; }
        .ct-footer {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 40;
            background: rgba(255,255,255,.94); backdrop-filter: blur(8px);
            border-top: 1px solid #eaecf0; padding: .85rem 1rem;
        }
        .ct-footer-inner { max-width: 760px; margin: 0 auto; }
        .ct-next {
            width: 100%; border: 0; border-radius: .85rem; padding: .95rem 1rem; font-weight: 600;
            background: #1f1b4d; color: #fff; font-size: .95rem; cursor: pointer;
        }
        .ct-next:disabled { background: #d0d5dd; cursor: not-allowed; }
        .ct-next-meta { text-align: center; font-size: .78rem; color: #667085; margin-top: .4rem; }
        @media (min-width: 992px) {
            .ct-footer { left: 280px; }
        }
    </style>
</head>
<body class="rp-dash-body">
@include('site.include.body_meta')
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
@include('site.include.student_dashboard_header')

    <section id="dashboard">
    <div class="container-fluid">
        <div class="dashboardAll dashboardPh">
              <div class="menuBarBtn menuBarBtnOpen">
                  <i class="fas fa-bars"></i>
              </div>
              

            <div class="dashboardLeft dashboardLeftOff">
                @include('site.include.student_left_menu')
            </div>
            <div class="dashboardRight">
                <div class="dashboardRightBody">
                    <div class="ct-wrap">
                        <div class="ct-title">Custom Practice</div>
                        <div class="ct-sub">One subject or multiple — choose chapters, set question counts, then start.</div>

                        @if(session('error'))
                            <div class="ct-alert ct-alert-err">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="ct-alert ct-alert-ok">{{ session('success') }}</div>
                        @endif

                        <div class="ct-tabs">
                            <a class="ct-tab {{ $tab === 'create' ? 'active' : '' }}" href="{{ route('custom_test', ['tab' => 'create']) }}">Create New</a>
                            <a class="ct-tab {{ $tab === 'attempted' ? 'active' : '' }}" href="{{ route('custom_test', ['tab' => 'attempted']) }}">Attempted tests</a>
                        </div>

                        @if($tab === 'attempted')
                            <div class="ct-attempt-list">
                                @forelse($attempted as $item)
                                    @php
                                        $pct = (float) ($item->progress_pct ?? 0);
                                        $ringColor = $pct >= 60 ? '#12b76a' : ($pct >= 30 ? '#f79009' : '#ef4444');
                                    @endphp
                                    <a class="ct-attempt" href="{{ $item->list_href }}">
                                        <span class="ct-ring" style="--pct: {{ $pct }}; background: conic-gradient({{ $ringColor }} calc(var(--pct) * 1%), #e4e7ec 0);">
                                            <span class="ct-ring-val">{{ number_format($pct, 1) }}%</span>
                                        </span>
                                        <span class="ct-attempt-name">{{ $item->name }}</span>
                                        <span class="ct-attempt-date">{{ $item->created_at->format('d M Y') }}</span>
                                    </a>
                                @empty
                                    <div class="ct-empty">No custom tests yet. Create one from the Create New tab.</div>
                                @endforelse
                            </div>
                        @else
                            <div class="ct-step-row">
                                <div class="ct-step-label">
                                    <strong>Step 1 of 2</strong>
                                    Select subject / chapters / topics
                                </div>
                                <div class="ct-progress"><span class="on"></span><span></span></div>
                            </div>

                            <div class="ct-hint">Tap <strong>Show Chapters</strong> on a subject to pick chapters/topics. Question counts appear only for subjects you select.</div>

                            <form method="post" action="{{ route('custom_test.configure') }}" id="ct-step1-form">
                                @csrf
                                @forelse($subjectCards as $card)
                                    @php
                                        $subject = $card['subject'];
                                        $count = (int) $card['question_count'];
                                        $isOpen = !empty($card['has_selection']);
                                    @endphp
                                    <div class="ct-card {{ $isOpen ? 'is-open' : '' }}"
                                         data-subject-card="{{ $subject->id }}"
                                         data-available="{{ $card['available'] }}"
                                         data-selected="{{ $isOpen ? '1' : '0' }}">
                                        <div class="ct-card-head">
                                            <span class="ct-subject-icon" style="background: {{ $card['meta']['color'] }};">
                                                <i class="{{ $card['meta']['icon'] }}"></i>
                                            </span>
                                            <span class="ct-subject-name">{{ $subject->name }}</span>
                                            <a class="ct-show-chapters" href="{{ route('custom_test.chapters', $subject->id) }}">
                                                {{ $isOpen ? 'Edit Chapters' : 'Show Chapters' }}
                                            </a>
                                        </div>
                                        @if($isOpen)
                                            <div class="ct-card-body">
                                                <div class="ct-presets">
                                                    @foreach($card['presets'] as $preset)
                                                        <button type="button" class="ct-preset {{ $count === (int) $preset ? 'active' : '' }}"
                                                                data-preset="{{ $preset }}">{{ $preset }} Qs</button>
                                                    @endforeach
                                                </div>
                                                <input type="number" class="ct-manual" name="counts[{{ $subject->id }}]"
                                                       min="1" max="{{ max(1, $card['available']) }}"
                                                       value="{{ $count > 0 ? $count : '' }}"
                                                       placeholder="Enter questions manually" inputmode="numeric" required>
                                                <div class="ct-card-meta">
                                                    <strong>{{ number_format($card['available']) }}</strong> Qs available
                                                    · {{ $card['selection_label'] }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="ct-empty">No subjects available.</div>
                                @endforelse
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($tab !== 'attempted')
<div class="ct-footer">
    <div class="ct-footer-inner">
        <button type="submit" form="ct-step1-form" class="ct-next" id="ct-next" disabled>Next ></button>
        <div class="ct-next-meta" id="ct-next-meta">Show Chapters on a subject to begin</div>
    </div>
</div>
@endif

@include('site.include.call_to_action')
@include('site.include.back_to_top')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
@if($tab !== 'attempted')
<script>
(function () {
    var form = document.getElementById('ct-step1-form');
    var nextBtn = document.getElementById('ct-next');
    var nextMeta = document.getElementById('ct-next-meta');
    if (!form) return;

    var selectedCards = form.querySelectorAll('[data-subject-card][data-selected="1"]');

    function sync() {
        var total = 0;
        var subjects = 0;
        selectedCards.forEach(function (card) {
            var input = card.querySelector('.ct-manual');
            if (!input) return;
            var available = parseInt(card.getAttribute('data-available') || '0', 10);
            var val = parseInt(input.value || '0', 10);
            if (isNaN(val) || val < 0) val = 0;
            if (val > available) {
                val = available;
                input.value = available > 0 ? String(available) : '';
            }
            card.querySelectorAll('.ct-preset').forEach(function (btn) {
                btn.classList.toggle('active', parseInt(btn.getAttribute('data-preset'), 10) === val && val > 0);
            });
            if (val > 0) {
                subjects++;
                total += val;
            }
        });
        nextBtn.disabled = total < 1;
        if (!selectedCards.length) {
            nextMeta.textContent = 'Show Chapters on a subject to begin';
        } else if (total < 1) {
            nextMeta.textContent = 'Choose how many questions for your selected subject(s)';
        } else {
            nextMeta.textContent = subjects + ' subject' + (subjects === 1 ? '' : 's') + ' · ' + total + ' questions';
        }
    }

    selectedCards.forEach(function (card) {
        var input = card.querySelector('.ct-manual');
        if (!input) return;
        card.querySelectorAll('.ct-preset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var available = parseInt(card.getAttribute('data-available') || '0', 10);
                var preset = parseInt(btn.getAttribute('data-preset'), 10);
                input.value = String(Math.min(preset, available));
                sync();
            });
        });
        input.addEventListener('input', sync);
    });

    sync();
})();
</script>
@endif
</body>
</html>
