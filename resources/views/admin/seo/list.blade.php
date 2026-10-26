@extends('layouts.backend')

@section('css_after')
<style>
    .ws-card { background:#fff; border-radius:16px; box-shadow:0 8px 24px rgba(12,10,40,.06); padding:24px; margin-bottom:24px; }
    .seo-title { font-weight:600; color:#0c0a14; }
    .seo-desc { font-size:13px; color:#6b6577; max-width:520px; }
    .seo-badge { font-size:11px; font-weight:600; padding:3px 8px; border-radius:999px; }
    .seo-badge--custom { background:#eef2ff; color:#345ff4; }
    .seo-badge--default { background:#f3f3f6; color:#6b6577; }
</style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center justify-content-between">
            <div>
                <h1 class="page-header mb-1">SEO</h1>
                <p class="text-muted mb-0">Meta title, description, keywords and schema for each public page. Pages you haven't edited use the built-in defaults. Logged-in student pages are always hidden from search engines.</p>
            </div>
        </div>

        <div class="ws-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Page</th>
                            <th>Title &amp; description</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $route => $page)
                            @php
                                $row = $overrides[$route] ?? [];
                                $title = !empty($row['title']) ? $row['title'] : $page['title'];
                                $description = !empty($row['description']) ? $row['description'] : $page['description'];
                            @endphp
                            <tr>
                                <td><strong>{{ $page['label'] }}</strong><div class="seo-desc">{{ $route }}</div></td>
                                <td>
                                    <div class="seo-title">{{ $title }}</div>
                                    <div class="seo-desc">{{ \Illuminate\Support\Str::limit($description, 140) }}</div>
                                </td>
                                <td>
                                    @if($row)
                                        <span class="seo-badge seo-badge--custom">Customised</span>
                                    @else
                                        <span class="seo-badge seo-badge--default">Default</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.seo.edit', ['route_name' => $route]) }}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js_after')
@endsection
