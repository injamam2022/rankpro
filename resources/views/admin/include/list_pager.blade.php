@if(isset($list) && method_exists($list, 'total') && $list->total() > 0)
    <div class="admin-list-pager">
        <div class="text-muted" style="font-size:13px;">
            Showing {{ $list->firstItem() }}–{{ $list->lastItem() }} of {{ $list->total() }}
        </div>
        <div>
            {{ $list->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
