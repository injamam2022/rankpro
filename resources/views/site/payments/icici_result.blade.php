<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'Payment | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/course-packages.css" rel="stylesheet">
    @include('site.include.head_meta')
</head>
<body>
@include('site.include.body_meta')
@include('site.include.header')

<main class="cpg">
    <div class="cpg__inner" style="max-width:640px;padding-top:48px;padding-bottom:72px;">
        <div class="cpg-box" style="text-align:center;padding:36px 28px;">
            @if($state === 'success')
                <div style="font-size:42px;color:#128a4a;"><i class="fas fa-check-circle"></i></div>
                <h1 class="h3 mt-3" style="font-family:Space Grotesk,sans-serif;">Payment successful</h1>
            @elseif($state === 'pending')
                <div style="font-size:42px;color:#b8860b;"><i class="fas fa-clock"></i></div>
                <h1 class="h3 mt-3" style="font-family:Space Grotesk,sans-serif;">Payment pending</h1>
            @else
                <div style="font-size:42px;color:#c0392b;"><i class="fas fa-times-circle"></i></div>
                <h1 class="h3 mt-3" style="font-family:Space Grotesk,sans-serif;">Payment not completed</h1>
            @endif
            <p style="color:#5c6478;">{{ $message }}</p>
            @if($reference)
                <p class="small" style="color:#6b6577;">Reference {{ $reference }}@if($amount !== null) · ₹{{ number_format($amount, 2) }}@endif</p>
            @endif
            <a href="{{ $continue }}" class="cpg-btn" style="display:inline-block;margin-top:8px;">{{ $continueLabel }}</a>
        </div>
    </div>
</main>

@include('site.include.footer')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
</body>
</html>
