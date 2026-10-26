@php
    $pageTitle = trim((string) ($title ?? ''));
    $pageDescription = trim((string) ($meta_description ?? ''));
@endphp
<title>{{ $pageTitle !== '' ? $pageTitle . ' | Rank Pro' : 'Rank Pro' }}</title>
@if($pageDescription !== '')
<meta name="description" content="{{ $pageDescription }}">
@endif
<meta name="robots" content="index,follow">
