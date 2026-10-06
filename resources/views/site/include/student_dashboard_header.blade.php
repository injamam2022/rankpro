@php
    $hdrUser = Auth::user();
    $hdrName = session()->get('parant_login_type') == 'P'
        ? ($hdrUser->father_full_name ?? 'Parent')
        : trim(($hdrUser->first_name ?? '') . ' ' . ($hdrUser->last_name ?? ''));
    $hdrImg = !empty($hdrUser->profile_img)
        ? basename(str_replace('\\', '/', $hdrUser->profile_img))
        : '';
    $neetLabel = 'NEET Timer';
    $neetDateLabel = '';
    $neetTargetIso = '2027-05-02T14:00:00+05:30';
    if (!empty($neet_exam) && !empty($neet_exam->value)) {
        try {
            $neetCarbon = \Carbon\Carbon::parse($neet_exam->value);
            $neetDateLabel = $neetCarbon->format('M j, Y');
            $neetTargetIso = $neetCarbon->toIso8601String();
        } catch (\Exception $e) {
            $neetDateLabel = '';
        }
    }
@endphp
<header class="rp-topbar">
    <div class="rp-topbar__inner">
        <a href="{{ route('dashboard') }}" class="rp-topbar__brand">
            <span class="rp-topbar__mark">R</span>
            <span class="rp-topbar__name">Rank<span>Pro</span></span>
        </a>

        <div class="rp-topbar__actions">
            <div class="rp-timer" id="rpNeetTimer" aria-label="Exam countdown" data-target="{{ $neetTargetIso }}">
                <div class="rp-timer__label">
                    <i class="far fa-clock"></i>
                    <span>{{ $neetLabel }}</span>
                </div>
                <div class="rp-timer__unit">
                    <span class="rp-timer__num" id="rpDays">00</span>
                    <span class="rp-timer__suf">d</span>
                </div>
                <span class="rp-timer__sep">:</span>
                <div class="rp-timer__unit">
                    <span class="rp-timer__num" id="rpHours">00</span>
                    <span class="rp-timer__suf">h</span>
                </div>
                <span class="rp-timer__sep">:</span>
                <div class="rp-timer__unit">
                    <span class="rp-timer__num" id="rpMinutes">00</span>
                    <span class="rp-timer__suf">m</span>
                </div>
                <span class="rp-timer__sep">:</span>
                <div class="rp-timer__unit">
                    <span class="rp-timer__num is-sec" id="rpSeconds">00</span>
                    <span class="rp-timer__suf">s</span>
                </div>
                @if($neetDateLabel)
                    <span class="rp-timer__date">{{ $neetDateLabel }}</span>
                @endif
            </div>
            <a href="{{ route('upcoming_exam') }}" class="rp-btn-pink">
                <i class="fas fa-bolt"></i>
                Give Exam
            </a>
            <a href="{{ route('index') }}" class="rp-btn-ghost" target="_blank" rel="noopener">
                <i class="fas fa-external-link-alt"></i>
                Landing
            </a>
            <a href="{{ route('profile') }}" class="rp-profile-pill" title="Profile settings">
                <div class="rp-profile-pill__avatar">
                    @if($hdrImg !== '')
                        <img src="{{ asset('uploads/profileImage/'.$hdrImg) }}" alt="">
                    @else
                        <img src="{{ asset('web/images/dashboardCheck.png') }}" alt="" style="object-fit:contain;opacity:.4;padding:4px;">
                    @endif
                    <span class="rp-profile-pill__dot"></span>
                </div>
                <div class="rp-profile-pill__meta">
                    <div class="rp-profile-pill__name">{{ $hdrName }}</div>
                    <div class="rp-profile-pill__target">Target: <strong>720</strong></div>
                </div>
            </a>
        </div>
    </div>
</header>
<script>
(function () {
    if (window.__rpNeetTimerStarted) return;
    window.__rpNeetTimerStarted = true;

    var timerEl = document.getElementById('rpNeetTimer');
    if (!timerEl) return;

    var targetRaw = timerEl.getAttribute('data-target') || '2027-05-02T14:00:00+05:30';
    var examDate = Date.parse(targetRaw);
    if (isNaN(examDate)) {
        examDate = new Date(2027, 4, 2, 14, 0, 0).getTime();
    }

    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function updateRpCountdown() {
        var now = Date.now();
        var distance = examDate - now;
        var days = 0, hours = 0, minutes = 0, seconds = 0;

        if (distance > 0) {
            days = Math.floor(distance / (1000 * 60 * 60 * 24));
            hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            seconds = Math.floor((distance % (1000 * 60)) / 1000);
        }

        var d = document.getElementById('rpDays');
        var h = document.getElementById('rpHours');
        var m = document.getElementById('rpMinutes');
        var s = document.getElementById('rpSeconds');
        if (!d || !h || !m || !s) return;

        d.textContent = days;
        h.textContent = pad(hours);
        m.textContent = pad(minutes);
        s.textContent = pad(seconds);
    }

    updateRpCountdown();
    setInterval(updateRpCountdown, 1000);
})();
</script>
