@php
    $q = $q ?? request('q');
    $searchPlaceholder = $search_placeholder ?? 'Search...';
    $hasFilters = ($q !== '' && $q !== null)
        || request()->filled('subject_id')
        || request()->filled('chapter_id')
        || request()->filled('topic_id')
        || request()->filled('sub_topic_id')
        || request()->filled('class_level')
        || request()->filled('is_premium');
@endphp
<div class="admin-list-field">
    <label for="list_q">Search</label>
    <input class="form-control" type="search" id="list_q" name="q" value="{{ $q }}" placeholder="{{ $searchPlaceholder }}">
</div>
<div class="admin-list-actions">
    <button class="rp-act rp-act-add" type="submit">
        <i class="material-icons">search</i>
        Search
    </button>
    @if($hasFilters)
        <a class="rp-act rp-act-ghost" href="{{ $clear_url ?? url()->current() }}">
            <i class="material-icons">close</i>
            Clear
        </a>
    @endif
</div>
