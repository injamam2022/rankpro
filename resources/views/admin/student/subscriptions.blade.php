@extends('layouts.backend')

@section('content')
@php
    $studentName = trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'Student #'.$user->id;
    $chapterPickLimit = (int) ($chapterPickLimit ?? \App\Support\CoursePackageAccess::CHAPTER_PICK_LIMIT);
    $chapterGroups = $chapterGroups ?? [];
    $ownedChapterIds = $ownedChapterIds ?? [];
@endphp
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4 align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-header mb-1">Online subscriptions</h1>
            <p class="text-muted mb-0">{{ $studentName }} · {{ $user->email_id }}</p>
        </div>
        <a href="{{ route('admin.student') }}" class="btn btn-secondary btn-sm">Back to students</a>
    </div>

    <form action="{{ route('admin.student.subscriptions.save') }}" method="post" class="card" id="studentSubscriptionsForm">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="card-body">
            <p class="text-muted">Tick the online products this student should access. They can hold more than one. Series Tests will show a tab for each assigned series package.</p>
            <div class="row g-3">
                @forelse($packages as $package)
                    @php
                        $state = $assigned[$package->code] ?? ['has' => false, 'label' => 'Not assigned'];
                        $missionIncludes = in_array($package->code, ['chapter_master', 'rank_builder', 'rank_champion'], true);
                        $lockedByMission = $missionIncludes && !empty($assigned['mission_720']['has']);
                        $cmChecked = $package->code === 'chapter_master'
                            && ($state['has'] || !empty($state['included']) || $lockedByMission
                                || old('packages') && in_array('chapter_master', (array) old('packages'), true));
                        $cmFull = $package->code === 'chapter_master' && (!empty($state['full']) || $lockedByMission);
                    @endphp
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <label class="d-flex align-items-start gap-2 mb-0" style="cursor:pointer;">
                                <input type="checkbox"
                                       name="packages[]"
                                       value="{{ $package->code }}"
                                       class="mt-1 js-sub-package"
                                       data-code="{{ $package->code }}"
                                       @checked($state['has'] || !empty($state['included']) || $lockedByMission
                                           || (is_array(old('packages')) && in_array($package->code, old('packages'), true)))
                                       @disabled(!empty($state['included']) || $lockedByMission)>
                                @if(!empty($state['included']) || $lockedByMission)
                                    <input type="hidden" name="packages[]" value="{{ $package->code }}">
                                @endif
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $package->name }}</div>
                                    <div class="small text-muted">{{ $package->tagline }}</div>
                                    <div class="small mt-1">
                                        <span class="badge {{ ($state['has'] || !empty($state['included']) || $lockedByMission) ? 'bg-success' : 'bg-light text-dark border' }}">
                                            @if($lockedByMission && empty($state['included']) && empty($state['has']))
                                                Included with Rank Pro Mission 720
                                            @else
                                                {{ $state['label'] }}
                                            @endif
                                        </span>
                                    </div>
                                    @if($package->code === 'mission_720')
                                        <div class="small text-muted mt-1">Also enables Rank Chapter Master, Rank Builder and Rank Champion.</div>
                                    @endif
                                    @if(!empty($state['included']) || ($lockedByMission && $package->code === 'chapter_master'))
                                        <div class="small text-muted mt-1">Entire Chapter Master is enabled with Rank Pro Mission 720.</div>
                                    @elseif($package->code === 'chapter_master' && !empty($state['full']))
                                        <div class="small text-muted mt-1">Full Chapter Master access — every PCB chapter.</div>
                                    @elseif($package->code === 'chapter_master' && ($state['chapters'] ?? collect())->count())
                                        <ul class="small mb-0 mt-2 ps-3">
                                            @foreach($state['chapters'] as $line)
                                                <li>{{ \App\Support\CoursePackageAccess::chapterExamLabel($line->chapter) }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </label>

                            @if($package->code === 'chapter_master')
                                <div id="cmChapterPicker"
                                     class="mt-3 pt-3 border-top"
                                     @if($cmFull || !$cmChecked) style="display:none;" @endif>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                        <div>
                                            <div class="fw-semibold">Choose chapters</div>
                                            <div class="small text-muted">Select up to {{ $chapterPickLimit }} chapters at a time to assign.</div>
                                        </div>
                                        <div class="small">
                                            <span id="cmPickCount">0</span> / {{ $chapterPickLimit }} selected
                                        </div>
                                    </div>
                                    <input type="search" id="cmChapterSearch" class="form-control form-control-sm mb-2" placeholder="Search chapters" autocomplete="off">
                                    <div id="cmChapterPick" class="border rounded-3 p-2" style="max-height:320px;overflow:auto;">
                                        @forelse($chapterGroups as $group)
                                            <div class="cm-ch-group mb-2">
                                                <div class="fw-semibold small text-uppercase text-muted mb-1">{{ $group['name'] }}</div>
                                                @foreach($group['chapters'] as $chapter)
                                                    <label class="d-flex align-items-center gap-2 py-1 px-1 rounded cm-ch-row {{ !empty($chapter['owned']) ? 'text-muted' : '' }}"
                                                           data-search="{{ $group['name'] }} {{ $chapter['name'] }}"
                                                           style="cursor:{{ !empty($chapter['owned']) ? 'default' : 'pointer' }};">
                                                        @if(!empty($chapter['owned']))
                                                            <input type="checkbox" checked disabled>
                                                            <span class="small flex-grow-1">{{ $chapter['name'] }}</span>
                                                            <span class="badge bg-success">Owned</span>
                                                        @else
                                                            <input class="js-cm-pick" type="checkbox" name="chapter_ids[]" value="{{ $chapter['id'] }}"
                                                                   @checked(is_array(old('chapter_ids')) && in_array((string) $chapter['id'], array_map('strval', (array) old('chapter_ids')), true))>
                                                            <span class="small flex-grow-1">{{ $chapter['name'] }}</span>
                                                            <span class="small text-muted">{{ $chapter['questions'] > 0 ? $chapter['questions'].' Qs' : 'Coming soon' }}</span>
                                                        @endif
                                                    </label>
                                                @endforeach
                                            </div>
                                        @empty
                                            <div class="small text-muted">No chapters available. Check subjects/chapters in admin.</div>
                                        @endforelse
                                    </div>
                                    <div class="small text-danger mt-2" id="cmPickLimitMsg" hidden>Maximum {{ $chapterPickLimit }} chapters in one save. Uncheck one to add another.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-warning mb-0">No assignable packages found. Run CoursePackageSeeder.</div></div>
                @endforelse
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <span class="small text-muted">Unchecking removes access, including a previous paid order for that product.</span>
            <button type="submit" class="btn btn-primary">Save subscriptions</button>
        </div>
    </form>
</div>
@endsection

@section('js_after')
<script>
(function () {
    var form = document.getElementById('studentSubscriptionsForm');
    if (!form) return;

    var MISSION = 'mission_720';
    var BUNDLE = ['chapter_master', 'rank_builder', 'rank_champion'];
    var MAX = {{ $chapterPickLimit }};
    var picker = document.getElementById('cmChapterPicker');
    var countEl = document.getElementById('cmPickCount');
    var limitMsg = document.getElementById('cmPickLimitMsg');
    var searchEl = document.getElementById('cmChapterSearch');

    function checkboxFor(code) {
        return form.querySelector('.js-sub-package[data-code="' + code + '"]');
    }

    function ensureHidden(code, on) {
        var existing = form.querySelector('input[type="hidden"][name="packages[]"][value="' + code + '"]');
        if (on) {
            if (!existing) {
                var h = document.createElement('input');
                h.type = 'hidden';
                h.name = 'packages[]';
                h.value = code;
                form.appendChild(h);
            }
        } else if (existing) {
            existing.remove();
        }
    }

    function picks() {
        return Array.prototype.slice.call(form.querySelectorAll('.js-cm-pick'));
    }

    function updatePickCount() {
        var n = picks().filter(function (el) { return el.checked; }).length;
        if (countEl) countEl.textContent = String(n);
        if (limitMsg) limitMsg.hidden = n < MAX;
    }

    function syncChapterPicker() {
        if (!picker) return;
        var mission = checkboxFor(MISSION);
        var cm = checkboxFor('chapter_master');
        var missionOn = !!(mission && mission.checked);
        var cmOn = !!(cm && cm.checked);
        var show = cmOn && !missionOn;
        picker.style.display = show ? '' : 'none';
        if (!show) {
            picks().forEach(function (el) { el.checked = false; });
            updatePickCount();
        }
    }

    function syncBundleFromMission() {
        var mission = checkboxFor(MISSION);
        if (!mission || !mission.checked) {
            syncChapterPicker();
            return;
        }

        BUNDLE.forEach(function (code) {
            var box = checkboxFor(code);
            if (!box) return;
            box.checked = true;
            box.disabled = true;
            ensureHidden(code, true);
        });
        syncChapterPicker();
    }

    form.addEventListener('change', function (e) {
        var t = e.target;
        if (t.classList.contains('js-cm-pick')) {
            var selected = picks().filter(function (el) { return el.checked; });
            if (selected.length > MAX) {
                t.checked = false;
                if (limitMsg) limitMsg.hidden = false;
            }
            updatePickCount();
            return;
        }

        if (!t.classList.contains('js-sub-package')) return;

        if (t.getAttribute('data-code') === MISSION) {
            if (t.checked) {
                syncBundleFromMission();
            } else {
                BUNDLE.forEach(function (code) {
                    var box = checkboxFor(code);
                    if (!box) return;
                    box.disabled = false;
                    ensureHidden(code, false);
                });
                syncChapterPicker();
            }
            return;
        }

        if (t.getAttribute('data-code') === 'chapter_master') {
            syncChapterPicker();
        }
    });

    if (searchEl) {
        searchEl.addEventListener('input', function () {
            var q = (searchEl.value || '').toLowerCase().trim();
            form.querySelectorAll('.cm-ch-row').forEach(function (row) {
                var hay = (row.getAttribute('data-search') || '').toLowerCase();
                row.style.display = !q || hay.indexOf(q) !== -1 ? '' : 'none';
            });
        });
    }

    form.addEventListener('submit', function (e) {
        var mission = checkboxFor(MISSION);
        var cm = checkboxFor('chapter_master');
        if (cm && cm.checked && !(mission && mission.checked)) {
            var n = picks().filter(function (el) { return el.checked; }).length;
            var alreadyHas = {{ !empty($assigned['chapter_master']['has']) && empty($assigned['chapter_master']['full']) ? 'true' : 'false' }};
            if (n < 1 && !alreadyHas) {
                e.preventDefault();
                alert('Select up to ' + MAX + ' chapters for Rank Chapter Master.');
                if (picker) picker.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    syncBundleFromMission();
    updatePickCount();
})();
</script>
@endsection
