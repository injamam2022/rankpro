<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'Course Packages | RankPro', 'description' => 'NEET test series packages — Online, Centre OMR, and Home Delivery.']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
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
    $icons = [
        'sky' => 'fas fa-file-alt',
        'pink' => 'fas fa-book-open',
        'blue' => 'fas fa-chart-line',
        'violet' => 'fas fa-trophy',
        'orange' => 'fas fa-flask',
        'green' => 'fas fa-clipboard-check',
    ];
@endphp

<main class="cpg">
    <section class="cpg__hero">
        <div class="cpg__inner">
            <div class="cpg__eyebrow"><i></i> Course Packages</div>
            <h1 class="cpg__title">Choose your <span class="cpg__grad">NEET path</span></h1>
            <p class="cpg__sub">
                Paid packages use the premium question bank.
                Free practice stays on <a href="{{ route('custom_test') }}">Custom Test</a>.
                Pick Online, Centre, or Home Delivery where offered.
            </p>
        </div>
    </section>

    <div class="cpg__inner">
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <div class="cpg__grid">
            @forelse($packages as $package)
                @php
                    $theme = $package->theme ?: 'blue';
                    $icon = $icons[$theme] ?? 'fas fa-graduation-cap';
                @endphp
                <article class="cpg-card cpg-card--{{ $theme }} {{ $package->is_free ? 'cpg-card--free' : '' }}">
                    <div class="cpg-card__top">
                        <div class="cpg-card__icon"><i class="{{ $icon }}"></i></div>
                        <div class="cpg-card__badges">
                            @if($package->is_free)<span class="cpg-chip cpg-chip--free">Free</span>@endif
                            @if($package->includes_online)<span class="cpg-chip">Online</span>@endif
                            @if($package->includes_omr)<span class="cpg-chip cpg-chip--omr">OMR</span>@endif
                            @if($package->mode_selection === 'choose_one')<span class="cpg-chip cpg-chip--choose">Centre or Home</span>@endif
                        </div>
                    </div>
                    <h2 class="cpg-card__name">{{ $package->name }}</h2>
                    <p class="cpg-card__tag">{{ $package->tagline }}</p>
                    <div class="cpg-card__price">{{ $package->formattedPrice() }}</div>
                    <ul class="cpg-card__feats">
                        @foreach($package->features->take(5) as $f)
                            <li>{!! $checkSvg !!}<span>{{ $f->text }}</span></li>
                        @endforeach
                    </ul>
                    <a class="cpg-card__cta" href="{{ route('course_packages.show', $package->slug) }}">
                        {{ $package->is_free ? 'Get started free' : 'View details & buy' }}
                    </a>
                </article>
            @empty
                <div class="alert alert-info">No packages yet. Run CoursePackageSeeder.</div>
            @endforelse
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
