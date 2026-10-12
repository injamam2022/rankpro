@extends('layouts.backend')

@section('css_after')
<style>
    .ws-card { background:#fff; border-radius:16px; box-shadow:0 8px 24px rgba(12,10,40,.06); padding:24px; margin-bottom:24px; }
    .ws-card h2 { font-size:18px; margin:0 0 18px; }
    .ws-hint { font-size:13px; color:#6b6577; margin-top:4px; }
</style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center justify-content-between">
            <div>
                <h1 class="page-header mb-1">Website Settings</h1>
                <p class="text-muted mb-0">Phone, WhatsApp, email and social links used in the footer and floating contact buttons.</p>
            </div>
        </div>

        <form action="{{ route('admin.footer.settings') }}" method="post">
            @csrf
            <div class="ws-card">
                <h2>Contact</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Call Us number</label>
                        <input class="form-control" id="phone" type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="8100392229">
                        <div class="ws-hint">Used by the Call Us button and tel: links.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsapp">WhatsApp number</label>
                        <input class="form-control" id="whatsapp" type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" placeholder="8100392229">
                        <div class="ws-hint">Used by the WhatsApp button. Same number is fine.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Support email</label>
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}" placeholder="support.rankproteam@gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="copyright">Copyright text</label>
                        <input class="form-control" id="copyright" type="text" name="copyright" value="{{ old('copyright', $settings['copyright'] ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="address">Location / address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Office address">{{ old('address', $settings['address'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <h2>Social media</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="instagram">Instagram URL</label>
                        <input class="form-control" id="instagram" type="url" name="instagram" value="{{ old('instagram', $settings['instagram'] ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="facebook">Facebook URL</label>
                        <input class="form-control" id="facebook" type="url" name="facebook" value="{{ old('facebook', $settings['facebook'] ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="linkedin">LinkedIn URL</label>
                        <input class="form-control" id="linkedin" type="url" name="linkedin" value="{{ old('linkedin', $settings['linkedin'] ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="youtube">YouTube URL</label>
                        <input class="form-control" id="youtube" type="url" name="youtube" value="{{ old('youtube', $settings['youtube'] ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <button class="btn btn-primary" type="submit">Save settings</button>
            </div>
        </form>
    </div>
@endsection

@section('js_after')
@endsection
