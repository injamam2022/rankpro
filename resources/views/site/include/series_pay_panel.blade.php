@php
    $payPackage = $payPackage ?? $package ?? null;
    $payMode = $payMode ?? null;
    $payPrice = $payPackage ? $payPackage->formattedPrice() : '';
@endphp
@if($payPackage)
<div class="ps-pay">
    <div class="ps-pay__lock"><i class="fas fa-lock"></i></div>
    <h3 class="h5 mb-1">{{ $payPackage->name }} is locked</h3>
    <p class="text-muted mb-3">{{ $payPackage->tagline }} Buy this package, or ask RankPro admin to assign it, to open the tests in this tab.</p>
    <div class="ps-pay__price">{{ $payPrice }}</div>
    @if($payPackage->features && $payPackage->features->count())
        <ul class="ps-pay__feats">
            @foreach($payPackage->features->take(5) as $f)
                <li><i class="fas fa-check"></i> {{ $f->text }}</li>
            @endforeach
        </ul>
    @endif
    @if($payPackage->code === 'chapter_master')
        <a href="{{ route('course_packages.show', $payPackage->slug) }}" class="btn btn-primary px-4">Choose chapters &amp; pay</a>
    @elseif($payMode)
        <form action="{{ route('course_packages.checkout', $payPackage->slug) }}" method="get" class="d-inline">
            <input type="hidden" name="mode_id" value="{{ $payMode->id }}">
            <button type="submit" class="btn btn-primary px-4">Pay {{ $payPrice }}</button>
        </form>
        <a href="{{ route('course_packages.show', $payPackage->slug) }}" class="btn btn-outline-secondary ms-2">View details</a>
    @else
        <a href="{{ route('course_packages.show', $payPackage->slug) }}" class="btn btn-primary px-4">View details &amp; pay</a>
    @endif
</div>
@endif
