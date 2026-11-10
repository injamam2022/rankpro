<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => $package->name.' | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/redesign.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/course-packages.css" rel="stylesheet">
    @include('site.include.head_meta')
</head>
<body>
@include('site.include.body_meta')

<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>

@include('site.include.header')

@php
    $checkSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $preselectMode = $preselectMode ?? null;
@endphp

<main class="cpg">
    <div class="cpg__inner cpg-detail">
        <a class="cpg-back" href="{{ route('course_packages') }}"><i class="fas fa-arrow-left"></i> All packages</a>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="cpg-detail__layout">
            <div class="cpg-detail__main">
                <div class="cpg-card__badges mb-3">
                    @if($package->is_free)<span class="cpg-chip cpg-chip--free">Free</span>@endif
                    @if($package->includes_online)<span class="cpg-chip">Online</span>@endif
                    @if($package->includes_omr)<span class="cpg-chip cpg-chip--omr">Offline</span>@endif
                    @if($package->mode_selection === 'choose_one')<span class="cpg-chip cpg-chip--choose">Centre or Home</span>@endif
                </div>
                <h1 class="cpg-detail__name">{{ $package->name }}</h1>
                <p class="cpg-detail__tag">{{ $package->tagline }}</p>
                @if($package->description)
                    <p style="color:#3a4260;line-height:1.6;">{{ $package->description }}</p>
                @endif

                <h2 class="cpg-detail__section-title">What's included</h2>
                <ul class="cpg-detail__feats">
                    @foreach($package->features as $f)
                        <li>{!! $checkSvg !!}<span>{{ $f->text }}</span></li>
                    @endforeach
                </ul>

                @if($package->code === 'mission_720')
                    <p class="cpg-cart-hint">This package unlocks <strong>entire Rank Chapter Master</strong> — every Physics, Chemistry and Biology chapter exam — plus the Mission 720 series tests.</p>
                @endif

                @if($package->code === 'chapter_master' && !$chapterMasterLegacy)
                    <h2 class="cpg-detail__section-title">Choose chapters</h2>
                    <p class="cpg-cart-hint">₹{{ number_format($chapterUnitPrice, 0) }} per chapter. Select up to {{ \App\Support\CoursePackageAccess::CHAPTER_PICK_LIMIT }} chapters to give exam. After payment those chapters open on your dashboard and on the admin order automatically.</p>
                    @if(count($chapterGroups))
                        <div class="cpg-cart-search">
                            <i class="fas fa-search"></i>
                            <input type="search" id="cpChapterSearch" placeholder="Search chapters" autocomplete="off">
                        </div>
                        <div id="cpChapterPick">
                            @foreach($chapterGroups as $group)
                                <div class="cpg-ch-group {{ $loop->first ? 'is-open' : '' }}">
                                    <button type="button" class="cpg-ch-group__head" data-group-toggle>
                                        <span>{{ $group['name'] }}</span>
                                        <span>{{ count($group['chapters']) }}</span>
                                    </button>
                                    <div class="cpg-ch-group__body">
                                        @foreach($group['chapters'] as $chapter)
                                            <label class="cpg-ch-row {{ $chapter['owned'] ? 'is-owned' : '' }}" data-search="{{ $group['name'] }} {{ $chapter['name'] }}">
                                                @if($chapter['owned'])
                                                    <input type="checkbox" checked disabled>
                                                    <span class="cpg-ch-row__name">{{ $chapter['name'] }}</span>
                                                    <span class="cpg-own">Owned</span>
                                                @else
                                                    <input class="cm-pick" type="checkbox" name="chapter_ids[]" value="{{ $chapter['id'] }}" form="cpBuyForm"
                                                           data-subject="{{ $group['name'] }}" data-name="{{ $chapter['name'] }}">
                                                    <span class="cpg-ch-row__name">{{ $chapter['name'] }}</span>
                                                    <span class="cpg-ch-row__meta">{{ $chapter['questions'] > 0 ? $chapter['questions'].' Qs' : 'Coming soon' }}</span>
                                                    <span class="cpg-ch-row__price">₹{{ number_format($chapterUnitPrice, 0) }}</span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="cpg-cart-hint">No chapters are available to purchase yet.</p>
                    @endif
                @endif

                @if($schedules->count())
                    <h2 class="cpg-detail__section-title">Test schedule</h2>
                    <div class="table-responsive">
                        <table class="cpg-sched">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Timing</th>
                                    <th>Coverage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules->take(12) as $s)
                                    <tr>
                                        <td>{{ optional($s->test_date)->format('d M Y') }}</td>
                                        <td>{{ $s->test_type }}</td>
                                        <td>{{ $s->timing }}</td>
                                        <td>
                                            @foreach(($s->subjects ?? []) as $sub)
                                                <div><strong>{{ $sub['subject'] ?? '' }}</strong> {{ $sub['topics'] ?? '' }}</div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($schedules->count() > 12)
                        <p class="small text-muted mt-2 mb-0">Showing first 12 of {{ $schedules->count() }} scheduled tests.</p>
                    @endif
                @endif
            </div>

            <aside class="cpg-buy">
                @if($package->code === 'chapter_master' && !$chapterMasterLegacy)
                    <div class="cpg-buy__price" id="cpPriceLabel">₹{{ number_format($chapterUnitPrice, 0) }}</div>
                    <div class="cpg-buy__note" id="cpPriceNote">Per chapter · choose up to {{ \App\Support\CoursePackageAccess::CHAPTER_PICK_LIMIT }}</div>
                @else
                    <div class="cpg-buy__price" id="cpPriceLabel">{{ $package->formattedPrice() }}</div>
                    <div class="cpg-buy__note">
                        @if($package->is_free)
                            Free Custom Test segment — non-premium questions.
                        @else
                            Premium segment · secure ICICI Bank checkout
                        @endif
                    </div>
                @endif
                <p class="cpg-powered-by"><strong>Rank Pro<br>Powered by Shikkha Educational Advisory Solutions LLP</strong></p>

                <form action="{{ route('course_packages.checkout', $package->slug) }}" method="get" id="cpBuyForm">
                    @if($package->mode_selection === 'choose_one')
                        <div class="fw-semibold mb-2" style="font-size:14px;">Choose fulfilment</div>
                        @foreach($package->activeModes as $i => $mode)
                            @php
                                $isChecked = $preselectMode
                                    ? ($mode->mode_code === $preselectMode)
                                    : ($i === 0);
                            @endphp
                            <label class="cpg-mode {{ $isChecked ? 'is-on' : '' }}">
                                <input type="radio" name="mode_id" value="{{ $mode->id }}" {{ $isChecked ? 'checked' : '' }}
                                       data-price="{{ $mode->price }}"
                                       onchange="
                                            document.getElementById('cpPriceLabel').innerText='₹'+Number(this.dataset.price).toLocaleString('en-IN');
                                            document.querySelectorAll('.cpg-mode').forEach(function(el){el.classList.remove('is-on')});
                                            this.closest('.cpg-mode').classList.add('is-on');
                                       ">
                                <strong>{{ $mode->label }}</strong> — ₹{{ number_format($mode->price, 0) }}
                                @if($mode->help_text)<small>{{ $mode->help_text }}</small>@endif
                            </label>
                        @endforeach
                        @php $selected = $package->activeModes->firstWhere('mode_code', $preselectMode) ?: $package->activeModes->first(); @endphp
                        @if($selected)
                            <script>document.addEventListener('DOMContentLoaded',function(){var el=document.getElementById('cpPriceLabel'); if(el) el.innerText='₹'+Number({{ $selected->price }}).toLocaleString('en-IN');});</script>
                        @endif
                    @else
                        @php $mode = $package->activeModes->first(); @endphp
                        @if($mode)
                            <input type="hidden" name="mode_id" value="{{ $mode->id }}">
                            <div class="cpg-mode is-on" style="cursor:default;margin-bottom:16px;">
                                <strong>{{ $mode->label }}</strong>
                                @if($mode->help_text)<small>{{ $mode->help_text }}</small>@endif
                            </div>
                        @endif
                    @endif

                    @if($package->code === 'chapter_master' && !$chapterMasterLegacy)
                        <div id="cpCartBox">
                            <ul class="cpg-cart-lines" id="cpCartLines"></ul>
                            <p class="cpg-cart-empty" id="cpCartEmpty">No chapters selected yet.</p>
                            <p class="cpg-cart-limit" id="cpCartLimit" hidden>Maximum {{ \App\Support\CoursePackageAccess::CHAPTER_PICK_LIMIT }} chapters in one payment. Remove one to add another.</p>
                        </div>
                        @if(\App\Support\CoursePackageAccess::hasChapterMaster())
                            <a href="{{ route('chapter_master') }}" class="cpg-btn cpg-btn--soft">Open my chapters</a>
                        @endif
                    @endif

                    @if(Auth::check())
                        @if($package->code === 'chapter_master' && $chapterMasterLegacy)
                            <a href="{{ route('chapter_master') }}" class="cpg-btn">Open Chapter Master exams</a>
                            <p class="small text-muted text-center mb-2">You already own every chapter.</p>
                        @elseif($package->code === 'chapter_master' && !$chapterMasterLegacy)
                            @if($chapterUnownedCount > 0)
                                <button type="submit" class="cpg-btn" id="cpCartPay" disabled>Select chapters to pay</button>
                            @else
                                <p class="small text-muted text-center mb-2">You already own every listed chapter.</p>
                            @endif
                        @elseif(in_array($package->code, \App\Support\CoursePackageAccess::seriesPackageCodes(), true) && \App\Support\CoursePackageAccess::hasSeriesPackage($package->code))
                            <a href="{{ route('package_series.show', $package->slug) }}" class="cpg-btn">Open series tests</a>
                            <p class="small text-muted text-center mb-2">You already own this package — start online exams from your schedule.</p>
                        @elseif($package->is_free)
                            <a href="{{ route('custom_test') }}" class="cpg-btn">Give exam</a>
                        @else
                            <button type="submit" class="cpg-btn">Proceed to payment</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="cpg-btn" @if($package->code === 'chapter_master' && !$chapterMasterLegacy) id="cpCartLogin" @endif>{{ $package->is_free ? 'Login to give exam' : 'Login to purchase' }}</a>
                    @endif
                    @unless($package->is_free)
                        <div class="cpg-secure"><i class="fas fa-lock"></i> Secure payment via ICICI Bank</div>
                    @endunless
                </form>
            </aside>
        </div>
    </div>
</main>

@include('site.include.footer')
@include('site.include.call_to_action')
@include('site.include.back_to_top')

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
@if($package->code === 'chapter_master' && !$chapterMasterLegacy)
<script>
(function () {
    var max = {{ (int) \App\Support\CoursePackageAccess::CHAPTER_PICK_LIMIT }};
    var unit = {{ (float) $chapterUnitPrice }};
    var preselect = @json((string) ($preselectChapterId ?: ''));
    var pick = document.getElementById('cpChapterPick');
    var linesEl = document.getElementById('cpCartLines');
    var emptyEl = document.getElementById('cpCartEmpty');
    var limitEl = document.getElementById('cpCartLimit');
    var priceEl = document.getElementById('cpPriceLabel');
    var noteEl = document.getElementById('cpPriceNote');
    var payBtn = document.getElementById('cpCartPay');
    var loginBtn = document.getElementById('cpCartLogin');
    var searchEl = document.getElementById('cpChapterSearch');
    var formEl = document.getElementById('cpBuyForm');

    function money(n) {
        return '₹' + Number(n).toLocaleString('en-IN');
    }

    function selectedBoxes() {
        return Array.prototype.slice.call(document.querySelectorAll('.cm-pick:checked'));
    }

    function syncCart(changed) {
        var boxes = selectedBoxes();
        if (changed && boxes.length > max) {
            changed.checked = false;
            boxes = selectedBoxes();
            if (limitEl) limitEl.hidden = false;
        } else if (limitEl && boxes.length < max) {
            limitEl.hidden = true;
        }

        document.querySelectorAll('.cm-pick').forEach(function (box) {
            box.disabled = !box.checked && boxes.length >= max;
        });

        if (linesEl) {
            linesEl.innerHTML = '';
            boxes.forEach(function (box) {
                var li = document.createElement('li');
                var name = document.createElement('span');
                name.textContent = (box.dataset.subject || 'Subject') + ' · ' + (box.dataset.name || 'Chapter');
                var side = document.createElement('span');
                side.className = 'cpg-cart-line__side';
                var price = document.createElement('span');
                price.textContent = money(unit);
                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'cpg-cart-x';
                remove.setAttribute('data-remove', box.value);
                remove.setAttribute('aria-label', 'Remove chapter');
                remove.textContent = '×';
                side.appendChild(price);
                side.appendChild(remove);
                li.appendChild(name);
                li.appendChild(side);
                linesEl.appendChild(li);
            });
        }

        if (emptyEl) emptyEl.hidden = boxes.length > 0;
        var total = boxes.length * unit;
        if (priceEl) priceEl.textContent = boxes.length ? money(total) : money(unit);
        if (noteEl) {
            noteEl.textContent = boxes.length
                ? (boxes.length + (boxes.length === 1 ? ' chapter' : ' chapters') + ' × ' + money(unit))
                : ('Per chapter · choose up to ' + max);
        }
        if (payBtn) {
            payBtn.disabled = boxes.length < 1;
            payBtn.textContent = boxes.length ? ('Proceed to pay ' + money(total)) : 'Select chapters to pay';
        }
        if (loginBtn) {
            loginBtn.textContent = boxes.length ? ('Login to pay ' + money(total)) : 'Login to purchase';
        }
    }

    if (pick) {
        pick.addEventListener('click', function (e) {
            var head = e.target.closest('[data-group-toggle]');
            if (head) head.parentElement.classList.toggle('is-open');
        });
        pick.addEventListener('change', function (e) {
            if (e.target.classList.contains('cm-pick')) syncCart(e.target);
        });
    }

    if (linesEl) {
        linesEl.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-remove]');
            if (!btn) return;
            var id = btn.getAttribute('data-remove');
            var box = document.querySelector('.cm-pick[value="' + id + '"]');
            if (box && /^\d+$/.test(id)) {
                box.checked = false;
                syncCart(null);
            }
        });
    }

    if (searchEl && pick) {
        searchEl.addEventListener('input', function () {
            var q = searchEl.value.toLowerCase().trim();
            pick.querySelectorAll('.cpg-ch-group').forEach(function (group) {
                var any = false;
                group.querySelectorAll('.cpg-ch-row').forEach(function (row) {
                    var text = (row.getAttribute('data-search') || '').toLowerCase();
                    var show = !q || text.indexOf(q) !== -1;
                    row.style.display = show ? '' : 'none';
                    if (show) any = true;
                });
                group.style.display = any ? '' : 'none';
                if (q && any) group.classList.add('is-open');
            });
        });
    }

    if (formEl) {
        formEl.addEventListener('submit', function (e) {
            if (selectedBoxes().length < 1) e.preventDefault();
        });
    }

    if (preselect && /^\d+$/.test(preselect)) {
        var preset = document.querySelector('.cm-pick[value="' + preselect + '"]');
        if (preset) {
            preset.checked = true;
            var group = preset.closest('.cpg-ch-group');
            if (group) group.classList.add('is-open');
        }
    }

    syncCart(null);
})();
</script>
@endif
</body>
</html>
