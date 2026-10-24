@php
    $pager = $pager ?? ['page' => 1, 'last_page' => 1, 'total' => 0, 'from' => 0, 'to' => 0, 'per_page' => 4];
    $kind = $kind ?? 'strengths';
    $page = (int) $pager['page'];
    $last = (int) $pager['last_page'];
    $total = (int) $pager['total'];
    $pages = [];
    if ($last <= 7) {
        $pages = range(1, $last);
    } else {
        $pages[] = 1;
        $start = max(2, $page - 1);
        $end = min($last - 1, $page + 1);
        if ($start > 2) {
            $pages[] = '...';
        }
        for ($p = $start; $p <= $end; $p++) {
            $pages[] = $p;
        }
        if ($end < $last - 1) {
            $pages[] = '...';
        }
        $pages[] = $last;
    }
@endphp
@if($total > 0 && $last > 1)
<div class="sw-pager" data-kind="{{ $kind }}" data-page="{{ $page }}" data-last="{{ $last }}">
    <span class="sw-pager__meta">Showing {{ $pager['from'] }}–{{ $pager['to'] }} of {{ $total }}</span>
    <div class="sw-pager__btns">
        <button type="button" class="sw-page-btn" data-kind="{{ $kind }}" data-page="{{ max(1, $page - 1) }}" {{ $page <= 1 ? 'disabled' : '' }}>Prev</button>
        @foreach($pages as $p)
            @if($p === '...')
                <span class="sw-pager__gap">…</span>
            @else
                <button type="button" class="sw-page-btn {{ (int) $p === $page ? 'is-on' : '' }}" data-kind="{{ $kind }}" data-page="{{ $p }}">{{ $p }}</button>
            @endif
        @endforeach
        <button type="button" class="sw-page-btn" data-kind="{{ $kind }}" data-page="{{ min($last, $page + 1) }}" {{ $page >= $last ? 'disabled' : '' }}>Next</button>
    </div>
</div>
@elseif($total > 0)
<div class="sw-pager" data-kind="{{ $kind }}" data-page="1" data-last="1">
    <span class="sw-pager__meta">Showing {{ $pager['from'] }}–{{ $pager['to'] }} of {{ $total }}</span>
</div>
@endif
