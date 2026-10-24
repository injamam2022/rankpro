@if(!empty($paginator) && method_exists($paginator, 'total'))
@php
    $page = (int) $paginator->currentPage();
    $last = (int) $paginator->lastPage();
    $total = (int) $paginator->total();
    $from = (int) $paginator->firstItem();
    $to = (int) $paginator->lastItem();
    $pages = [];
    if ($last <= 7) {
        $pages = range(1, max(1, $last));
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
<div class="nt-pager">
    <span class="nt-pager__meta">Showing {{ $from }}–{{ $to }} of {{ $total }}</span>
    <div class="nt-pager__btns">
        @if($paginator->onFirstPage())
            <span class="nt-page-btn is-disabled">Prev</span>
        @else
            <a class="nt-page-btn" href="{{ $paginator->previousPageUrl() }}">Prev</a>
        @endif
        @foreach($pages as $p)
            @if($p === '...')
                <span class="nt-pager__gap">…</span>
            @elseif((int) $p === $page)
                <span class="nt-page-btn is-on">{{ $p }}</span>
            @else
                <a class="nt-page-btn" href="{{ $paginator->url($p) }}">{{ $p }}</a>
            @endif
        @endforeach
        @if($page >= $last)
            <span class="nt-page-btn is-disabled">Next</span>
        @else
            <a class="nt-page-btn" href="{{ $paginator->nextPageUrl() }}">Next</a>
        @endif
    </div>
</div>
@elseif($total > 0)
<div class="nt-pager">
    <span class="nt-pager__meta">Showing {{ $from }}–{{ $to }} of {{ $total }}</span>
</div>
@endif
@endif
