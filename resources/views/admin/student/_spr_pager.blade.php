@if(isset($list) && method_exists($list, 'hasPages') && $list->hasPages())
    <div class="spr-pager">
        <div class="spr-pager__meta">Showing {{ $list->firstItem() }}–{{ $list->lastItem() }} of {{ $list->total() }}</div>
        <div class="spr-pager__links">
            {{ $list->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
