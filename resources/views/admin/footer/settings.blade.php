@extends('layouts.backend')

@section('css_after')
<style>
    .ws-card { background:#fff; border-radius:16px; box-shadow:0 8px 24px rgba(12,10,40,.06); padding:24px; margin-bottom:24px; }
    .ws-card h2 { font-size:18px; margin:0 0 6px; }
    .ws-intro { font-size:13px; color:#6b6577; margin:0 0 18px; }
    .ws-hint { font-size:13px; color:#6b6577; margin-top:4px; }
    .ws-note { background:#f4f1ff; border:1px solid #e4dcff; border-radius:14px; padding:14px 16px; color:#3d3470; font-size:13px; margin-bottom:24px; }
    .ws-note strong { display:block; margin-bottom:4px; }
</style>
@endsection

@section('content')
    @php
        use App\Support\WebsiteSettings;
    @endphp
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center justify-content-between">
            <div>
                <h1 class="page-header mb-1">Website Settings</h1>
                <p class="text-muted mb-0">Manage the public email, phone, WhatsApp, address and social links used across the website.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <div class="ws-note">
            <strong>These values update the live site immediately after save.</strong>
            Footer, contact page, help page, floating Call Us / WhatsApp buttons, scholarship WhatsApp link and SEO contact markup all read from here.
        </div>

        <form action="{{ route('admin.website_settings.save') }}" method="post">
            @csrf
            @foreach ($groups as $groupKey => $group)
                <div class="ws-card">
                    <h2>{{ $group['title'] }}</h2>
                    @if(!empty($group['intro']))
                        <p class="ws-intro">{{ $group['intro'] }}</p>
                    @endif
                    <div class="row g-3">
                        @foreach (WebsiteSettings::fieldsIn($groupKey) as $key => $field)
                            @php
                                $type = $field['type'] ?? 'text';
                                $col = $type === 'textarea' ? 'col-12' : 'col-md-6';
                                $value = old($key, $settings[$key] ?? ($field['value'] ?? ''));
                            @endphp
                            <div class="{{ $col }}">
                                <label class="form-label" for="{{ $key }}">{{ $field['name'] }}</label>
                                @if($type === 'textarea')
                                    <textarea class="form-control" id="{{ $key }}" name="{{ $key }}" rows="3" placeholder="{{ $field['placeholder'] ?? '' }}">{{ $value }}</textarea>
                                @else
                                    <input class="form-control" id="{{ $key }}" type="{{ $type === 'email' ? 'email' : 'text' }}" name="{{ $key }}" value="{{ $value }}" placeholder="{{ $field['placeholder'] ?? '' }}">
                                @endif
                                @if(!empty($field['hint']))
                                    <div class="ws-hint">{{ $field['hint'] }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mb-5">
                <button class="btn btn-primary" type="submit">Save website settings</button>
            </div>
        </form>
    </div>
@endsection

@section('js_after')
@endsection
