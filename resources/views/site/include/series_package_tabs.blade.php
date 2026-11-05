@php
    $seriesTabs = $seriesTabs ?? collect();
    $ownedTabCodes = $ownedTabCodes ?? [];
    $activePackageTab = $activePackageTab ?? null;
@endphp
@if($seriesTabs->count())
<div class="ps-tabs">
    @foreach($seriesTabs as $tabPackage)
        @php
            $owned = in_array($tabPackage->code, $ownedTabCodes, true);
            $href = $tabPackage->code === 'chapter_master'
                ? route('chapter_master')
                : route('package_series.show', $tabPackage->slug);
        @endphp
        <a class="ps-tab {{ $activePackageTab === $tabPackage->code ? 'is-on' : '' }} {{ $owned ? '' : 'is-locked' }}" href="{{ $href }}">
            @unless($owned)<i class="fas fa-lock"></i>@endunless
            <span>{{ $tabPackage->name }}</span>
        </a>
    @endforeach
</div>
@endif
