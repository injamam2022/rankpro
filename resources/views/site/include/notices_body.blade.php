@php
    $type = $type ?? '';
    $notifTab = $notif_tab ?? 'all';
    $isNotifTab = in_array((string) $type, ['5', '6'], true) || in_array($notifTab, ['upcoming', 'important'], true);

    $noticeUrl = function ($params = []) {
        return route('notice', array_filter($params, function ($v) {
            return $v !== null && $v !== '';
        }));
    };

    $noticeMeta = function ($title, $urgent = 0, $notifType = null, $important = 0) {
        $t = strtolower((string) $title);
        $urgent = (int) $urgent === 1 || (int) $important === 1;
        $cat = 'RankPro Schedule';
        $tone = 'schedule';
        $examTypes = ['exam_assigned', 'exam_upcoming', 'exam_live', 'result_ready'];
        if ($notifType && in_array($notifType, $examTypes, true)) {
            $cat = 'Test Series';
            $tone = 'series';
        } elseif (str_contains($t, 'nta') || str_contains($t, 'nmc') || str_contains($t, 'circular') || str_contains($t, 'syllabus')) {
            $cat = 'NTA Official';
            $tone = 'official';
        } elseif (str_contains($t, 'admission') || str_contains($t, 'batch') || str_contains($t, 'mentorship') || str_contains($t, 'scholarship')) {
            $cat = 'Admissions';
            $tone = 'admissions';
        } elseif (str_contains($t, 'mock') || str_contains($t, 'test series') || str_contains($t, 'cbt') || str_contains($t, 'exam')) {
            $cat = 'Test Series';
            $tone = 'series';
        } elseif ($urgent) {
            $cat = 'NTA Official';
            $tone = 'official';
        }

        return ['category' => $cat, 'tone' => $tone, 'urgent' => $urgent];
    };

    $noticeDate = function ($value) {
        try {
            return \Carbon\Carbon::parse($value)->format('M d, Y');
        } catch (\Throwable $e) {
            return '';
        }
    };

    $allOn = !$isNotifTab && !in_array((string) $type, ['2', '3', '4'], true);
    $upcomingOn = (string) $type === '5' || $notifTab === 'upcoming';
    $importantOn = (string) $type === '6' || $notifTab === 'important';
@endphp

<div class="nt-page">
    <div class="nt-panel">
        <div class="nt-head">
            <h1 class="nt-head__title">
                <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                Official Notices &amp; Batch Announcements
            </h1>
            <div class="nt-chips">
                <a class="nt-chip {{ $allOn ? 'is-on' : '' }}" href="{{ $noticeUrl(['type' => 1]) }}">All <span>{{ (int) ($notice_count ?? 0) }}</span></a>
                <a class="nt-chip {{ $upcomingOn ? 'is-on' : '' }}" href="{{ $noticeUrl(['tab' => 'upcoming', 'type' => 5]) }}">Upcoming exams</a>
                <a class="nt-chip {{ $importantOn ? 'is-on' : '' }}" href="{{ $noticeUrl(['tab' => 'important', 'type' => 6]) }}">Important</a>
                <a class="nt-chip {{ (string) $type === '2' ? 'is-on' : '' }}" href="{{ $noticeUrl(['type' => 2]) }}">Archive <span>{{ (int) ($archive_count ?? 0) }}</span></a>
                <a class="nt-chip {{ (string) $type === '3' ? 'is-on' : '' }}" href="{{ $noticeUrl(['type' => 3]) }}">Favorite <span>{{ (int) ($favorite_count ?? 0) }}</span></a>
                <a class="nt-chip {{ (string) $type === '4' ? 'is-on' : '' }}" href="{{ $noticeUrl(['type' => 4]) }}">Urgent <span>{{ (int) ($urgent_count ?? 0) }}</span></a>
            </div>
        </div>

        <div class="nt-list">
            @if($isNotifTab)
                @forelse(($notifications ?? []) as $item)
                    @php
                        $meta = $noticeMeta($item->title, 0, $item->type ?? null, $item->is_important ?? 0);
                    @endphp
                    <article class="nt-card {{ $meta['urgent'] ? 'is-urgent' : '' }}" data-open="1">
                        <div class="nt-card__top">
                            <div class="nt-card__meta">
                                <span class="nt-cat nt-cat--{{ $meta['tone'] }}">{{ $meta['category'] }}</span>
                                <span class="nt-date">{{ $noticeDate($item->created_at) }}</span>
                            </div>
                            @if($meta['urgent'])
                                <span class="nt-priority">Priority Circular</span>
                            @endif
                        </div>
                        <h2 class="nt-card__title">{{ $item->title }}</h2>
                        @if($item->body)
                            <p class="nt-card__sum">{{ $item->body }}</p>
                        @endif
                        <div class="nt-card__full" hidden>{!! $item->body !!}</div>
                        <div class="nt-card__acts">
                            <form method="post" action="{{ route('notifications.read') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input type="hidden" name="to" value="{{ $item->action_url ?: route('notice') }}">
                                <button type="submit" class="nt-act" title="Open"><i class="fas fa-external-link-alt"></i></button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="nt-empty">No notifications in this tab.</div>
                @endforelse
            @else
                @forelse(($notice_list ?? []) as $value)
                    @php
                        $meta = $noticeMeta($value->name, $value->is_urgent ?? 0);
                        $summary = \Illuminate\Support\Str::limit(trim(strip_tags($value->description ?? '')), 220);
                    @endphp
                    <article class="nt-card {{ $meta['urgent'] ? 'is-urgent' : '' }}" data-open="1">
                        <div class="nt-card__top">
                            <div class="nt-card__meta">
                                <span class="nt-cat nt-cat--{{ $meta['tone'] }}">{{ $meta['category'] }}</span>
                                <span class="nt-date">{{ $noticeDate($value->created_at) }}</span>
                            </div>
                            @if($meta['urgent'])
                                <span class="nt-priority">Priority Circular</span>
                            @endif
                        </div>
                        <h2 class="nt-card__title">{{ $value->name }}</h2>
                        @if($summary !== '')
                            <p class="nt-card__sum">{{ $summary }}</p>
                        @endif
                        <div class="nt-card__full" hidden>{!! $value->description !!}</div>
                        <div class="nt-card__acts">
                            <a class="nt-act {{ (int) $value->is_favorite === 1 ? 'is-on' : '' }}" href="{{ route('notice.favorite', ['id' => $value->id]) }}" title="{{ (int) $value->is_favorite === 1 ? 'Remove favorite' : 'Favorite' }}">
                                <i class="{{ (int) $value->is_favorite === 1 ? 'fas' : 'far' }} fa-star"></i>
                            </a>
                            @if((string) $type === '2')
                                <a class="nt-act is-danger" href="{{ route('notice.delete', ['id' => $value->id]) }}" onclick="return confirm('Do you want to delete this notice?');" title="Delete">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            @else
                                <a class="nt-act" href="{{ route('notice.archive', ['id' => $value->id]) }}" onclick="return confirm('Do you want to archive this notice?');" title="Archive">
                                    <i class="fas fa-archive"></i>
                                </a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="nt-empty">No official notices in this view yet.</div>
                @endforelse
            @endif
        </div>
        @include('site.include.notices_pager', ['paginator' => $isNotifTab ? ($notifications ?? null) : ($notice_list ?? null)])
    </div>
</div>

<div class="nt-modal" id="ntModal" hidden>
    <div class="nt-modal__bg" data-close="1"></div>
    <div class="nt-modal__card">
        <div class="nt-modal__top">
            <div>
                <span class="nt-cat" id="ntModalCat"></span>
                <span class="nt-date" id="ntModalDate"></span>
                <h3 class="nt-modal__title" id="ntModalTitle"></h3>
            </div>
            <button type="button" class="nt-x" data-close="1" aria-label="Close">&times;</button>
        </div>
        <div class="nt-modal__body" id="ntModalBody"></div>
        <div class="nt-modal__foot">
            <button type="button" class="nt-close" data-close="1">Close</button>
        </div>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('ntModal');
    if (!modal) return;

    function closeModal() {
        modal.classList.remove('is-on');
        modal.hidden = true;
    }

    modal.querySelectorAll('[data-close]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    document.querySelectorAll('.nt-card[data-open]').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (e.target.closest('.nt-card__acts') || e.target.closest('form') || e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            var cat = card.querySelector('.nt-cat');
            var date = card.querySelector('.nt-date');
            var title = card.querySelector('.nt-card__title');
            var full = card.querySelector('.nt-card__full');
            var modalCat = document.getElementById('ntModalCat');
            modalCat.className = cat ? cat.className : 'nt-cat';
            modalCat.textContent = cat ? cat.textContent : '';
            document.getElementById('ntModalDate').textContent = date ? date.textContent : '';
            document.getElementById('ntModalTitle').textContent = title ? title.textContent : '';
            document.getElementById('ntModalBody').innerHTML = full ? full.innerHTML : '';
            modal.hidden = false;
            modal.classList.add('is-on');
        });
    });
})();
</script>
