<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => $package->name.' | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/redesign.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/course-packages.css" rel="stylesheet">
    @include('site.include.head_meta')
</head>
<body>
@include('site.include.body_meta')

<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>

@include('site.include.header')

@php
    $checkSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $preselectMode = $preselectMode ?? null;
@endphp

<main class="cpg">
    <div class="cpg__inner cpg-detail">
        <a class="cpg-back" href="{{ route('course_packages') }}"><i class="fas fa-arrow-left"></i> All packages</a>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="cpg-detail__layout">
            <div class="cpg-detail__main">
                <div class="cpg-card__badges mb-3">
                    @if($package->is_free)<span class="cpg-chip cpg-chip--free">Free</span>@endif
                    @if($package->includes_online)<span class="cpg-chip">Online</span>@endif
                    @if($package->includes_omr)<span class="cpg-chip cpg-chip--omr">OMR</span>@endif
                    @if($package->mode_selection === 'choose_one')<span class="cpg-chip cpg-chip--choose">Centre or Home</span>@endif
                </div>
                <h1 class="cpg-detail__name">{{ $package->name }}</h1>
                <p class="cpg-detail__tag">{{ $package->tagline }}</p>
                @if($package->description)
                    <p style="color:#3a4260;line-height:1.6;">{{ $package->description }}</p>
                @endif

                <h2 class="cpg-detail__section-title">What's included</h2>
                <ul class="cpg-detail__feats">
                    @foreach($package->features as $f)
                        <li>{!! $checkSvg !!}<span>{{ $f->text }}</span></li>
                    @endforeach
                </ul>

                @if($schedules->count())
                    <h2 class="cpg-detail__section-title">Test schedule</h2>
                    <div class="table-responsive">
                        <table class="cpg-sched">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Timing</th>
                                    <th>Coverage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules->take(12) as $s)
                                    <tr>
                                        <td>{{ optional($s->test_date)->format('d M Y') }}</td>
                                        <td>{{ $s->test_type }}</td>
                                        <td>{{ $s->timing }}</td>
                                        <td>
                                            @foreach(($s->subjects ?? []) as $sub)
                                                <div><strong>{{ $sub['subject'] ?? '' }}</strong> {{ $sub['topics'] ?? '' }}</div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($schedules->count() > 12)
                        <p class="small text-muted mt-2 mb-0">Showing first 12 of {{ $schedules->count() }} scheduled tests.</p>
                    @endif
                @endif
            </div>

            <aside class="cpg-buy">
                <div class="cpg-buy__price" id="cpPriceLabel">{{ $package->formattedPrice() }}</div>
                <div class="cpg-buy__note">
                    @if($package->is_free)
                        Free Custom Test segment — non-premium questions.
                    @else
                        Premium segment · secure Razorpay checkout
                    @endif
                </div>

                <form action="{{ route('course_packages.checkout', $package->slug) }}" method="get" id="cpBuyForm">
                    @if($package->mode_selection === 'choose_one')
                        <div class="fw-semibold mb-2" style="font-size:14px;">Choose fulfilment</div>
                        @foreach($package->activeModes as $i => $mode)
                            @php
                                $isChecked = $preselectMode
                                    ? ($mode->mode_code === $preselectMode)
                                    : ($i === 0);
                            @endphp
                            <label class="cpg-mode {{ $isChecked ? 'is-on' : '' }}">
                                <input type="radio" name="mode_id" value="{{ $mode->id }}" {{ $isChecked ? 'checked' : '' }}
                                       data-price="{{ $mode->price }}"
                                       onchange="
                                            document.getElementById('cpPriceLabel').innerText='₹'+Number(this.dataset.price).toLocaleString('en-IN');
                                            document.querySelectorAll('.cpg-mode').forEach(function(el){el.classList.remove('is-on')});
                                            this.closest('.cpg-mode').classList.add('is-on');
                                       ">
                                <strong>{{ $mode->label }}</strong> — ₹{{ number_format($mode->price, 0) }}
                                @if($mode->help_text)<small>{{ $mode->help_text }}</small>@endif
                            </label>
                        @endforeach
                        @php $selected = $package->activeModes->firstWhere('mode_code', $preselectMode) ?: $package->activeModes->first(); @endphp
                        @if($selected)
                            <script>document.addEventListener('DOMContentLoaded',function(){var el=document.getElementById('cpPriceLabel'); if(el) el.innerText='₹'+Number({{ $selected->price }}).toLocaleString('en-IN');});</script>
                        @endif
                    @else
                        @php $mode = $package->activeModes->first(); @endphp
                        @if($mode)
                            <input type="hidden" name="mode_id" value="{{ $mode->id }}">
                            <div class="cpg-mode is-on" style="cursor:default;margin-bottom:16px;">
                                <strong>{{ $mode->label }}</strong>
                                @if($mode->help_text)<small>{{ $mode->help_text }}</small>@endif
                            </div>
                        @endif
                    @endif

                    @if(Auth::check())
                        @if($package->code === 'chapter_master' && \App\Support\CoursePackageAccess::hasChapterMaster())
                            <a href="{{ route('chapter_master') }}" class="cpg-btn">Open Chapter Master exams</a>
                            <p class="small text-muted text-center mb-2">You already own this package.</p>
                        @elseif(in_array($package->code, \App\Support\CoursePackageAccess::seriesPackageCodes(), true) && \App\Support\CoursePackageAccess::hasSeriesPackage($package->code))
                            <a href="{{ route('package_series.show', $package->slug) }}" class="cpg-btn">Open series tests</a>
                            <p class="small text-muted text-center mb-2">You already own this package — start online exams from your schedule.</p>
                        @elseif($package->is_free)
                            <a href="{{ route('custom_test') }}" class="cpg-btn">Give exam</a>
                        @else
                            <button type="submit" class="cpg-btn">Proceed to payment</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="cpg-btn">{{ $package->is_free ? 'Login to give exam' : 'Login to purchase' }}</a>
                    @endif
                    @unless($package->is_free)
                        <div class="cpg-secure"><i class="fas fa-lock"></i> Secure payment via Razorpay</div>
                    @endunless
                </form>
            </aside>
        </div>
    </div>
</main>

@include('site.include.footer')
@include('site.include.call_to_action')
@include('site.include.back_to_top')

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
</body>
</html>
