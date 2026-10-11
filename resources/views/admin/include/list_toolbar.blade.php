@php
    $q = $q ?? request('q');
    $perPage = $per_page ?? request('per_page', 10);
@endphp
<input class="form-control" type="search" name="q" value="{{ $q }}" placeholder="Search..." style="max-width:260px;">
<label class="mb-0" style="font-size:13px;color:#64748b;">Per page</label>
<select class="form-control" name="per_page" style="width:90px;" onchange="this.form.submit()">
    @foreach([10, 25, 50] as $n)
        <option value="{{ $n }}" @if((int)$perPage === $n) selected @endif>{{ $n }}</option>
    @endforeach
</select>
<button class="rp-act rp-act-add" type="submit">
    <i class="material-icons">search</i>
    Search
</button>
@if($q !== '' && $q !== null)
    <a class="rp-act rp-act-ghost" href="{{ url()->current() }}">
        <i class="material-icons">close</i>
        Clear
    </a>
@endif
