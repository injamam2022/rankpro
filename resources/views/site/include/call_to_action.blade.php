@if(request()->routeIs('index'))
@php
    $ctaRows = \App\Models\Footer::where('status', 1)->pluck('value', 'key_name');
    $ctaPhoneSource = $ctaRows['contact_us'] ?? $ctaRows['toll_free'] ?? '+919830009430';
    $ctaPhoneFirst = preg_split('/[\/,;|]/', (string) $ctaPhoneSource)[0] ?? $ctaPhoneSource;
    $ctaPhoneDigits = preg_replace('/\D+/', '', $ctaPhoneFirst);
    if (strlen($ctaPhoneDigits) === 10) {
        $ctaPhoneDigits = '91' . $ctaPhoneDigits;
    }
    if ($ctaPhoneDigits === '') {
        $ctaPhoneDigits = '919830009430';
    }
@endphp
<!-- call to action: home page only -->
<div class="calltoaction">
    <a href="tel:+{{ $ctaPhoneDigits }}" aria-label="Call us">
        <img src="{{ asset('web/images/icons/call_calltoa.png') }}" alt="Call" class="img-fluid">
    </a>
    <a href="https://wa.me/{{ $ctaPhoneDigits }}" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
        <img src="{{ asset('web/images/icons/whatsapa_calltoa.png') }}" alt="WhatsApp" class="img-fluid">
    </a>
</div>
@endif
