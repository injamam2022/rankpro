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
            <img src="{{ asset('web/images/logo.png') }}" alt="RankPro" class="rp-topbar__logo">
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
            <div class="rp-bell-wrap" id="rpBellWrap"
                 data-unread="{{ (int) ($notif_unread ?? 0) }}"
                 data-count-url="{{ route('notifications.unread_count') }}">
                <button type="button" class="rp-bell rp-bell-mute" id="rpNotifMuteBtn" aria-label="Mute notification sound" title="Mute notification sound">
                    <i class="fas fa-volume-up" id="rpNotifMuteIcon"></i>
                </button>
                <button type="button" class="rp-bell" id="rpBellBtn" aria-label="Notifications">
                    <i class="far fa-bell"></i>
                    <span class="rp-bell__badge" id="rpBellBadge" @if(empty($notif_unread)) hidden @endif>{{ !empty($notif_unread) && $notif_unread > 99 ? '99+' : (int) ($notif_unread ?? 0) }}</span>
                </button>
                <div class="rp-bell-panel" role="menu">
                    <div class="rp-bell-panel__head">
                        <span>Notifications</span>
                        <div class="rp-bell-panel__head-actions">
                            @if(!empty($notif_unread))
                                <form method="post" action="{{ route('notifications.read_all') }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="rp-link-sm" style="border:0;background:none;color:#2563eb;font-size:12px;">Mark all read</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="rp-bell-panel__tabs">
                        <a href="{{ route('notice') }}">All</a>
                        <a href="{{ route('notice', ['tab' => 'upcoming', 'type' => 5]) }}">Upcoming exams</a>
                        <a href="{{ route('notice', ['tab' => 'important', 'type' => 6]) }}">Important</a>
                    </div>
                    @forelse(($notif_preview ?? []) as $n)
                        <form method="post" action="{{ route('notifications.read') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $n->id }}">
                            <input type="hidden" name="to" value="{{ $n->action_url ?: route('notice') }}">
                            <button type="submit" class="rp-bell-item {{ $n->read_at ? '' : 'is-unread' }}" style="width:100%;text-align:left;border:0;background:transparent;cursor:pointer;">
                                <div class="rp-bell-item__title">{{ $n->title }}</div>
                                <div class="rp-bell-item__meta">{{ $n->created_at ? $n->created_at->diffForHumans() : '' }}</div>
                            </button>
                        </form>
                    @empty
                        <div class="rp-bell-item__meta" style="padding:16px 12px;">No notifications yet.</div>
                    @endforelse
                    <div class="rp-bell-panel__foot">
                        <a href="{{ route('notice') }}">View all</a>
                    </div>
                </div>
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
@php
    $hideScopeFilter = request()->is('online-exam*', 'start-online-exam*', 'start-offline-exam*', 'start-exam*', 'profile*', 'report-problem*')
        || request()->routeIs('start_online_exam', 'start_exam', 'online_exam', 'profile', 'report_problem');
    $hideScopeMode = request()->is('custom-test*') || request()->routeIs('custom_test*');
@endphp
@unless($hideScopeFilter)
<div id="rpScopeFilterSlot" class="rp-scope-filter-slot">
    @include('site.include.scope_filter_bar', ['hideScopeMode' => $hideScopeMode])
</div>
<script>
(function () {
    function placeRpScopeFilter() {
        var slot = document.getElementById('rpScopeFilterSlot');
        if (!slot) return;
        var bar = slot.querySelector('.rp-filter');
        var body = document.querySelector('.dashboardRightBody');
        if (bar && body) {
            body.insertBefore(bar, body.firstChild);
        }
        slot.remove();
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', placeRpScopeFilter);
    } else {
        placeRpScopeFilter();
    }
})();
</script>
@endunless
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
(function () {
    var wrap = document.getElementById('rpBellWrap');
    var btn = document.getElementById('rpBellBtn');
    if (!wrap || !btn) return;
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        wrap.classList.toggle('is-open');
    });
    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) wrap.classList.remove('is-open');
    });
})();
(function () {
    if (window.__rpNotifSoundStarted) return;
    window.__rpNotifSoundStarted = true;

    var wrap = document.getElementById('rpBellWrap');
    var muteBtn = document.getElementById('rpNotifMuteBtn');
    var muteIcon = document.getElementById('rpNotifMuteIcon');
    var badge = document.getElementById('rpBellBadge');
    if (!wrap || !muteBtn || !muteIcon) return;

    var MUTE_KEY = 'rp_notif_muted';
    var COUNT_KEY = 'rp_notif_heard_unread';
    var countUrl = wrap.getAttribute('data-count-url') || '';
    var currentCount = parseInt(wrap.getAttribute('data-unread') || '0', 10) || 0;
    var heardCount = parseInt(localStorage.getItem(COUNT_KEY) || '0', 10);
    if (isNaN(heardCount)) heardCount = 0;

    var audioCtx = null;
    var pendingWelcomeChime = currentCount > heardCount;

    function isMuted() {
        return localStorage.getItem(MUTE_KEY) === '1';
    }

    function setMuted(muted) {
        localStorage.setItem(MUTE_KEY, muted ? '1' : '0');
        muteBtn.classList.toggle('is-muted', muted);
        muteIcon.className = muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
        muteBtn.setAttribute('aria-label', muted ? 'Unmute notification sound' : 'Mute notification sound');
        muteBtn.setAttribute('title', muted ? 'Unmute notification sound' : 'Mute notification sound');
    }

    function ensureAudio() {
        if (!audioCtx) {
            var Ctx = window.AudioContext || window.webkitAudioContext;
            if (!Ctx) return null;
            audioCtx = new Ctx();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume().catch(function () {});
        }
        return audioCtx;
    }

    function playChime() {
        if (isMuted()) return;
        var ctx = ensureAudio();
        if (!ctx) return;
        var now = ctx.currentTime;
        [880, 1174.7].forEach(function (freq, i) {
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = freq;
            gain.gain.setValueAtTime(0.0001, now);
            gain.gain.exponentialRampToValueAtTime(0.08, now + 0.02 + i * 0.05);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.28 + i * 0.08);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now + i * 0.06);
            osc.stop(now + 0.35 + i * 0.08);
        });
    }

    function markHeard(count) {
        heardCount = Math.max(0, count);
        localStorage.setItem(COUNT_KEY, String(heardCount));
    }

    function updateBadge(count) {
        if (!badge) return;
        if (count > 0) {
            badge.hidden = false;
            badge.textContent = count > 99 ? '99+' : String(count);
        } else {
            badge.hidden = true;
            badge.textContent = '0';
        }
    }

    function onCount(count) {
        count = Math.max(0, parseInt(count, 10) || 0);
        currentCount = count;
        if (count !== heardCount) {
            markHeard(count);
        }
        updateBadge(count);
    }

    function remindIfUnread() {
        if (currentCount > 0 && !isMuted()) {
            playChime();
        }
    }

    setMuted(isMuted());
    updateBadge(currentCount);

    muteBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        ensureAudio();
        var next = !isMuted();
        setMuted(next);
        if (!next) {
            playChime(); // preview when unmuting
        }
        if (pendingWelcomeChime) {
            pendingWelcomeChime = false;
            markHeard(currentCount);
        }
    });

    document.addEventListener('click', function once() {
        ensureAudio();
        if (pendingWelcomeChime) {
            pendingWelcomeChime = false;
            if (!isMuted() && currentCount > 0) {
                playChime();
            }
            markHeard(currentCount);
        }
        document.removeEventListener('click', once);
    }, { once: true, capture: true });

    function poll() {
        if (!countUrl) return;
        fetch(countUrl, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
            cache: 'no-store'
        }).then(function (r) { return r.ok ? r.json() : null; })
          .then(function (data) {
              if (!data || typeof data.unread === 'undefined') return;
              pendingWelcomeChime = false;
              onCount(data.unread);
          }).catch(function () {});
    }

    var TEN_MIN = 10 * 60 * 1000;
    // Refresh unread count and play reminder sound every 10 minutes (if unread & not muted).
    setInterval(function () {
        poll();
        remindIfUnread();
    }, TEN_MIN);
})();
</script>
