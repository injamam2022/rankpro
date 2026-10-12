@php
    use App\Support\WebsiteSettings;
    $ctaTel = WebsiteSettings::telHref('phone');
    $ctaWa = WebsiteSettings::whatsappHref();
@endphp
<div class="calltoaction rp-cta">
    <a class="rp-cta__card" href="{{ $ctaWa }}" target="_blank" rel="noopener noreferrer">
        <span class="rp-cta__icon rp-cta__icon--wa" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.7L1 23l5.4-1.1A11 11 0 0 0 20.5 3.5zm-8.5 17a9.1 9.1 0 0 1-4.6-1.3l-.3-.2-3.2.7.7-3.1-.2-.3A9.1 9.1 0 1 1 12 20.5zm5-6.8c-.3-.1-1.6-.8-1.8-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.4.3-.5c.1-.2 0-.3 0-.5l-.9-2.1c-.2-.6-.5-.5-.6-.5h-.5c-.2 0-.5.1-.7.3s-1 1-1 2.4 1 2.8 1.2 3a12.6 12.6 0 0 0 4.8 4.3c1.9.8 2.6.7 3.1.6s1.5-.6 1.7-1.2.2-1.1.1-1.2c-.1-.1-.3-.2-.6-.3z"/></svg>
        </span>
        <span class="rp-cta__label">WhatsApp</span>
    </a>
    <a class="rp-cta__card" id="rpCtaCall" href="{{ $ctaTel }}">
        <span class="rp-cta__icon rp-cta__icon--call" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6.6 3.8c.4-.4 1-.5 1.5-.3l2.1.7c.6.2 1 .7 1.1 1.3l.3 2.1c.1.6-.1 1.2-.6 1.6l-1 1c.8 1.5 2 2.7 3.5 3.5l1-1c.4-.4 1.1-.7 1.6-.6l2.1.3c.6.1 1.1.5 1.3 1.1l.7 2.1c.2.5.1 1.1-.3 1.5l-1.2 1.2c-.5.5-1.2.8-1.9.7-3.4-.4-6.6-2.2-9-4.6s-4.2-5.6-4.6-9c-.1-.7.2-1.4.7-1.9l1.2-1.2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <span class="rp-cta__label">Call Us</span>
    </a>
    <div class="rp-cta__pop" id="rpCtaPop" hidden>
        <span class="rp-cta__pop-label">Call us at</span>
        <a class="rp-cta__pop-num" href="{{ $ctaTel }}">{{ WebsiteSettings::displayPhone() }}</a>
        <button type="button" class="rp-cta__pop-copy" data-number="{{ WebsiteSettings::displayPhone() }}">Copy number</button>
    </div>
</div>
<script>
    (function () {
        var call = document.getElementById('rpCtaCall');
        var pop = document.getElementById('rpCtaPop');
        if (!call || !pop) return;
        {{-- Desktop browsers silently ignore tel: links when no calling app is installed. --}}
        if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            call.addEventListener('click', function () { pop.hidden = !pop.hidden; });
            document.addEventListener('click', function (e) {
                if (!pop.hidden && !pop.contains(e.target) && !call.contains(e.target)) pop.hidden = true;
            });
        }
        pop.querySelector('.rp-cta__pop-copy').addEventListener('click', function () {
            var btn = this;
            var done = function () { btn.textContent = 'Copied!'; setTimeout(function () { btn.textContent = 'Copy number'; }, 1500); };
            if (navigator.clipboard) navigator.clipboard.writeText(btn.dataset.number).then(done);
            else { var t = document.createElement('textarea'); t.value = btn.dataset.number; document.body.appendChild(t); t.select(); document.execCommand('copy'); t.remove(); done(); }
        });
    })();
</script>
