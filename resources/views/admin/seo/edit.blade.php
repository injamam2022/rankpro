@extends('layouts.backend')

@section('css_after')
<style>
    .ws-card { background:#fff; border-radius:16px; box-shadow:0 8px 24px rgba(12,10,40,.06); padding:24px; margin-bottom:24px; }
    .ws-card h2 { font-size:18px; margin:0 0 18px; }
    .ws-hint { font-size:13px; color:#6b6577; margin-top:4px; }
    .seo-count { font-size:12px; color:#6b6577; float:right; }
    .seo-count.is-over { color:#d63384; font-weight:600; }
</style>
@endsection

@section('content')
    @php
        $hasPlaceholder = str_contains($page['title'] . $page['description'], '{name}');
    @endphp
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center justify-content-between">
            <div>
                <h1 class="page-header mb-1">SEO: {{ $page['label'] }}</h1>
                <p class="text-muted mb-0">
                    Leave a field empty to use the default shown as its placeholder.
                    @if($url) Page: <a href="{{ $url }}" target="_blank" rel="noopener">{{ $url }}</a> @endif
                </p>
            </div>
            <a class="btn btn-light" href="{{ route('admin.seo') }}">Back</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form action="{{ route('admin.seo.update') }}" method="post">
            @csrf
            <input type="hidden" name="route_name" value="{{ $route_name }}">

            <div class="ws-card">
                <h2>Search result</h2>
                @if($hasPlaceholder)
                    <div class="alert alert-info py-2">This is a template for every item of this type. <code>{name}</code> is replaced with the item's name.</div>
                @endif
                <div class="mb-3">
                    <label class="form-label" for="title">Meta title <span class="seo-count" data-for="title" data-max="60"></span></label>
                    <input class="form-control" id="title" type="text" name="title" value="{{ old('title', $details->title ?? '') }}" placeholder="{{ $page['title'] }}">
                    <div class="ws-hint">Aim for 50–60 characters.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Meta description <span class="seo-count" data-for="description" data-max="160"></span></label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="{{ $page['description'] }}">{{ old('description', $details->description ?? '') }}</textarea>
                    <div class="ws-hint">Aim for 140–160 characters.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="keywords">Meta keywords</label>
                    <textarea class="form-control" id="keywords" name="keywords" rows="2" placeholder="{{ $page['keywords'] }}">{{ old('keywords', $details->keywords ?? '') }}</textarea>
                    <div class="ws-hint">Comma separated.</div>
                </div>
            </div>

            <div class="ws-card">
                <h2>Sharing &amp; indexing</h2>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="og_image">Share image URL</label>
                        <input class="form-control" id="og_image" type="url" name="og_image" value="{{ old('og_image', $details->og_image ?? '') }}" placeholder="{{ asset('web/images/logo.png') }}">
                        <div class="ws-hint">Shown on WhatsApp, Facebook, LinkedIn and X previews. 1200×630 works best.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="robots">Search engines</label>
                        @php $robots = old('robots', $details->robots ?? ''); @endphp
                        <select class="form-select" id="robots" name="robots">
                            <option value="" @selected($robots === '')>Default ({{ $page['robots'] ?? 'index, follow' }})</option>
                            <option value="index, follow" @selected($robots === 'index, follow')>Show in search (index, follow)</option>
                            <option value="noindex, follow" @selected($robots === 'noindex, follow')>Hide from search (noindex, follow)</option>
                            <option value="noindex, nofollow" @selected($robots === 'noindex, nofollow')>Hide completely (noindex, nofollow)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <h2>Custom schema (optional)</h2>
                <p class="ws-hint mt-0">Organization, WebSite, WebPage and breadcrumb schema are added automatically. Paste extra JSON-LD here only if you need it, for example an Event or Review.</p>
                <textarea class="form-control font-monospace" name="schema_json" rows="8" placeholder='{"@context": "https://schema.org", "@type": "Event", "name": "..."}'>{{ old('schema_json', $details->schema_json ?? '') }}</textarea>
            </div>

            <div class="mb-5 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Save SEO</button>
                @if($details)
                    <a class="btn btn-outline-danger" href="{{ route('admin.seo.reset', ['route_name' => $route_name]) }}" onclick="return confirm('Reset this page to the default SEO?')">Reset to default</a>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('js_after')
<script>
    document.querySelectorAll('.seo-count').forEach(function (el) {
        var field = document.getElementById(el.dataset.for);
        var max = parseInt(el.dataset.max, 10);
        function update() {
            var len = (field.value || field.placeholder).length;
            el.textContent = len + ' / ' + max;
            el.classList.toggle('is-over', len > max);
        }
        field.addEventListener('input', update);
        update();
    });
</script>
@endsection
