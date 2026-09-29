<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $subject->name }} Chapters - Custom Test</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/dashboard.css" rel="stylesheet">
    @include('site.include.head_meta')
    <style>
        .ct-wrap { max-width: 880px; margin: 0 auto; padding-bottom: 7.5rem; }
        .ct-back { display: inline-flex; align-items: center; gap: .45rem; color: #344054; text-decoration: none; font-weight: 500; margin-bottom: .85rem; }
        .ct-back:hover { color: #5b4bb7; }
        .ct-title { font-size: 1.35rem; font-weight: 600; color: #101828; margin-bottom: 1rem; }
        .ct-search {
            display: flex; align-items: center; gap: .6rem; border: 1px solid #d0d5dd; border-radius: .75rem;
            padding: .65rem .9rem; background: #fff; margin-bottom: .85rem;
        }
        .ct-search i { color: #98a2b3; }
        .ct-search input { border: 0; outline: 0; width: 100%; font-size: .9rem; background: transparent; }
        .ct-filters { display: flex; gap: .45rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .ct-chip {
            border: 1px solid #d0d5dd; background: #fff; color: #344054; border-radius: 999px;
            padding: .35rem .85rem; font-size: .8rem; font-weight: 500; cursor: pointer;
        }
        .ct-chip.active { background: #1f1b4d; border-color: #1f1b4d; color: #fff; }
        .ct-group-label { font-size: .72rem; font-weight: 600; letter-spacing: .04em; color: #98a2b3; margin: 1rem 0 .5rem; text-transform: uppercase; }
        .ct-chapter {
            border: 1px solid #eaecf0; border-radius: .85rem; background: #fff; margin-bottom: .55rem; overflow: hidden;
        }
        .ct-chapter-row {
            display: grid; grid-template-columns: auto 1fr auto auto; align-items: center; gap: .65rem;
            padding: .85rem 1rem; cursor: pointer;
        }
        .ct-chapter-row input { accent-color: #5b4bb7; width: 1.05rem; height: 1.05rem; }
        .ct-chapter-name { font-weight: 600; color: #101828; font-size: .95rem; }
        .ct-chapter-meta { font-size: .78rem; color: #98a2b3; margin-top: .1rem; }
        .ct-qcount { font-size: .8rem; color: #667085; white-space: nowrap; }
        .ct-expand {
            border: 0; background: transparent; color: #98a2b3; width: 28px; height: 28px; border-radius: 50%;
            display: grid; place-items: center; cursor: pointer;
        }
        .ct-expand:hover { background: #f2f4f7; }
        .ct-topics { display: none; border-top: 1px solid #f2f4f7; padding: .25rem 0 .35rem; background: #fcfcfd; }
        .ct-chapter.open .ct-topics { display: block; }
        .ct-topic {
        display: grid; grid-template-columns: auto 1fr auto auto; align-items: center; gap: .5rem;
        padding: .55rem 1rem .55rem 2.35rem;
    }
    .ct-topic input { accent-color: #5b4bb7; width: 1rem; height: 1rem; }
    .ct-topic-name { font-size: .875rem; color: #344054; }
    .ct-subtopics { display: none; background: #f8fafc; }
    .ct-topic-wrap.open .ct-subtopics { display: block; }
    .ct-subtopic {
        display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: .5rem;
        padding: .45rem 1rem .45rem 3.4rem;
    }
    .ct-subtopic input { accent-color: #5b4bb7; width: .95rem; height: .95rem; }
    .ct-subtopic-name { font-size: .82rem; color: #475467; }
        .ct-chapter.hidden-by-search { display: none !important; }
        .ct-footer {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 40;
            background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
            border-top: 1px solid #eaecf0; padding: .85rem 1rem;
        }
        .ct-footer-inner { max-width: 880px; margin: 0 auto; }
        .ct-continue {
            width: 100%; border: 0; border-radius: .85rem; padding: .95rem 1rem; font-weight: 600;
            background: #5b4bb7; color: #fff; font-size: .95rem; cursor: pointer;
        }
        .ct-continue:disabled { background: #d0d5dd; cursor: not-allowed; }
        .ct-continue-meta { text-align: center; font-size: .78rem; color: #667085; margin-top: .4rem; }
        @media (min-width: 992px) {
            .ct-footer { left: 280px; }
        }
    </style>
</head>
<body>
@include('site.include.body_meta')
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<section id="dashboard">
    <div class="container-fluid">
        <div class="dashboardAll dashboardPh">
            <div class="dashboardLeft">
                @include('site.include.student_left_menu')
            </div>
            <div class="dashboardRight">
                <div class="dashboardRightBody">
                    <div class="ct-wrap">
                        <a class="ct-back" href="{{ route('custom_test') }}"><i class="fas fa-arrow-left"></i> Back</a>
                        <div class="ct-title">{{ $subject->name }} Chapters</div>

                        <div class="ct-search">
                            <i class="fas fa-search"></i>
                            <input type="search" id="ct-search" placeholder="Search chapters/topics" autocomplete="off">
                        </div>
                        <div class="ct-filters">
                            <button type="button" class="ct-chip active" data-filter="all">All</button>
                        </div>

                        <div id="ct-list"><div class="ct-empty">Loading chapters...</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<form method="post" action="{{ route('custom_test.save_chapters') }}" id="ct-form">
    @csrf
    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
    <div id="ct-selection-inputs"></div>
    <div class="ct-footer">
        <div class="ct-footer-inner">
            <button type="submit" class="ct-continue" id="ct-continue" disabled>Select chapters to continue</button>
            <div class="ct-continue-meta" id="ct-continue-meta">0 chapters · 0 topics · 0 Qs available</div>
        </div>
    </div>
</form>

@include('site.include.call_to_action')
@include('site.include.back_to_top')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
<script>
(function () {
    var listEl = document.getElementById('ct-list');
    var searchEl = document.getElementById('ct-search');
    var continueBtn = document.getElementById('ct-continue');
    var continueMeta = document.getElementById('ct-continue-meta');
    var inputsWrap = document.getElementById('ct-selection-inputs');
    var formEl = document.getElementById('ct-form');
    var chapters = [];
    var chapterMap = {};
    var footerTimer = null;
    var searchTimer = null;

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]);
        });
    }

    function topicById(ch, id) {
        return (ch.topics || []).find(function (t) { return String(t.id) === String(id); });
    }

    function syncTopicFromSubs(topic) {
        var subs = topic.subtopics || [];
        if (!subs.length) return;
        var checked = 0;
        for (var i = 0; i < subs.length; i++) if (subs[i]._checked) checked++;
        topic._checked = checked === subs.length;
        topic._indeterminate = checked > 0 && checked < subs.length;
    }

    function syncChapterFromTopics(ch) {
        var topics = ch.topics || [];
        if (!topics.length) return;
        var fully = 0, any = 0;
        for (var i = 0; i < topics.length; i++) {
            var t = topics[i];
            if (t._checked && !t._indeterminate) fully++;
            if (t._checked || t._indeterminate) any++;
        }
        ch._checked = fully === topics.length && topics.length > 0;
        ch._indeterminate = any > 0 && !ch._checked;
    }

    function setCheckedTree(ch, checked) {
        ch._checked = checked;
        ch._indeterminate = false;
        (ch.topics || []).forEach(function (t) {
            t._checked = checked;
            t._indeterminate = false;
            (t.subtopics || []).forEach(function (s) { s._checked = checked; });
        });
    }

    function paintChapter(ch) {
        var root = listEl.querySelector('.ct-chapter[data-chapter-id="' + ch.id + '"]');
        if (!root) return;

        root.classList.toggle('open', !!ch._open);
        var chapterCb = root.querySelector('.ct-chapter-cb');
        if (chapterCb) {
            chapterCb.checked = !!ch._checked;
            chapterCb.indeterminate = !!ch._indeterminate;
        }
        var chapterExpandIcon = root.querySelector('.ct-chapter-expand i');
        if (chapterExpandIcon) {
            chapterExpandIcon.className = 'fas fa-chevron-' + (ch._open ? 'up' : 'down');
        }

        (ch.topics || []).forEach(function (t) {
            var topicWrap = root.querySelector('.ct-topic-wrap[data-topic-id="' + t.id + '"]');
            if (!topicWrap) return;
            topicWrap.classList.toggle('open', !!t._open);
            var topicCb = topicWrap.querySelector('.ct-topic-cb');
            if (topicCb) {
                topicCb.checked = !!t._checked;
                topicCb.indeterminate = !!t._indeterminate;
            }
            var topicExpandIcon = topicWrap.querySelector('.ct-topic-expand i');
            if (topicExpandIcon) {
                topicExpandIcon.className = 'fas fa-chevron-' + (t._open ? 'up' : 'down');
            }
            (t.subtopics || []).forEach(function (s) {
                var subCb = topicWrap.querySelector('.ct-sub-cb[data-id="' + s.id + '"]');
                if (subCb) subCb.checked = !!s._checked;
            });
        });
    }

    function buildHtml() {
        var html = '<div class="ct-group-label">Chapters</div>';
        if (!chapters.length) {
            listEl.innerHTML = '<div class="ct-empty">No chapters found.</div>';
            return;
        }
        chapters.forEach(function (ch) {
            html += '<div class="ct-chapter' + (ch._open ? ' open' : '') + '" data-chapter-id="' + ch.id + '" data-search="' + esc((ch.name || '').toLowerCase()) + '">';
            html += '<div class="ct-chapter-row">';
            html += '<input type="checkbox" class="ct-chapter-cb" data-id="' + ch.id + '"' + (ch._checked ? ' checked' : '') + '>';
            html += '<div><div class="ct-chapter-name">' + esc(ch.name) + '</div>';
            html += '<div class="ct-chapter-meta">' + (ch.topic_count || 0) + ' topic' + ((ch.topic_count || 0) === 1 ? '' : 's') + '</div></div>';
            html += '<div class="ct-qcount">' + (ch.questions || 0) + ' Qs</div>';
            html += '<button type="button" class="ct-expand ct-chapter-expand" data-id="' + ch.id + '" aria-label="Toggle topics"><i class="fas fa-chevron-' + (ch._open ? 'up' : 'down') + '"></i></button>';
            html += '</div><div class="ct-topics">';
            (ch.topics || []).forEach(function (t) {
                var hasSubs = (t.subtopics || []).length > 0;
                var topicSearch = ((t.name || '') + ' ' + (t.subtopics || []).map(function (s) { return s.name || ''; }).join(' ')).toLowerCase();
                html += '<div class="ct-topic-wrap' + (t._open ? ' open' : '') + '" data-topic-id="' + t.id + '" data-search="' + esc(topicSearch) + '">';
                html += '<div class="ct-topic">';
                html += '<input type="checkbox" class="ct-topic-cb" data-chapter="' + ch.id + '" data-id="' + t.id + '"' + (t._checked ? ' checked' : '') + '>';
                html += '<span class="ct-topic-name">' + esc(t.name) + '</span>';
                html += '<span class="ct-qcount">' + (t.questions || 0) + ' Qs</span>';
                if (hasSubs) {
                    html += '<button type="button" class="ct-expand ct-topic-expand" data-chapter="' + ch.id + '" data-id="' + t.id + '"><i class="fas fa-chevron-' + (t._open ? 'up' : 'down') + '"></i></button>';
                } else {
                    html += '<span></span>';
                }
                html += '</div>';
                if (hasSubs) {
                    html += '<div class="ct-subtopics">';
                    (t.subtopics || []).forEach(function (s) {
                        html += '<label class="ct-subtopic" data-search="' + esc((s.name || '').toLowerCase()) + '">';
                        html += '<input type="checkbox" class="ct-sub-cb" data-chapter="' + ch.id + '" data-topic="' + t.id + '" data-id="' + s.id + '"' + (s._checked ? ' checked' : '') + '>';
                        html += '<span class="ct-subtopic-name">' + esc(s.name) + '</span>';
                        html += '<span class="ct-qcount">' + (s.questions || 0) + ' Qs</span>';
                        html += '</label>';
                    });
                    html += '</div>';
                }
                html += '</div>';
            });
            html += '</div></div>';
        });
        listEl.innerHTML = html;

        // Apply indeterminate after mount (property, not attribute)
        chapters.forEach(function (ch) {
            var root = listEl.querySelector('.ct-chapter[data-chapter-id="' + ch.id + '"]');
            if (!root) return;
            var chapterCb = root.querySelector('.ct-chapter-cb');
            if (chapterCb) chapterCb.indeterminate = !!ch._indeterminate;
            (ch.topics || []).forEach(function (t) {
                var topicCb = root.querySelector('.ct-topic-cb[data-id="' + t.id + '"]');
                if (topicCb) topicCb.indeterminate = !!t._indeterminate;
            });
        });
    }

    function selectionPayload() {
        var selection = [];
        var chapterCount = 0, topicCount = 0, qCount = 0;
        for (var i = 0; i < chapters.length; i++) {
            var ch = chapters[i];
            var selectedTopics = [];
            var topics = ch.topics || [];
            for (var j = 0; j < topics.length; j++) {
                if (topics[j]._checked || topics[j]._indeterminate) selectedTopics.push(topics[j]);
            }
            if (!ch._checked && !selectedTopics.length) continue;
            chapterCount++;
            if (ch._checked && !ch._indeterminate) {
                topicCount += topics.length;
                qCount += ch.questions || 0;
                selection.push({ chapter_id: ch.id, topic_ids: [], sub_topic_ids: [] });
                continue;
            }
            var topicIds = [];
            var subIds = [];
            selectedTopics.forEach(function (t) {
                topicCount++;
                if (t._checked && !t._indeterminate) {
                    topicIds.push(t.id);
                    qCount += t.questions || 0;
                } else {
                    (t.subtopics || []).forEach(function (s) {
                        if (s._checked) {
                            subIds.push(s.id);
                            qCount += s.questions || 0;
                        }
                    });
                }
            });
            selection.push({ chapter_id: ch.id, topic_ids: topicIds, sub_topic_ids: subIds });
        }
        return { selection: selection, chapterCount: chapterCount, topicCount: topicCount, qCount: qCount };
    }

    function syncFooterMeta() {
        var info = selectionPayload();
        continueBtn.disabled = info.selection.length === 0 || info.qCount < 1;
        continueBtn.textContent = info.selection.length ? 'Save & continue' : 'Select chapters to continue';
        continueMeta.textContent = info.chapterCount + ' chapters · ' + info.topicCount + ' topics · ' + info.qCount + ' Qs available';
        return info;
    }

    function scheduleFooter() {
        if (footerTimer) clearTimeout(footerTimer);
        footerTimer = setTimeout(syncFooterMeta, 40);
    }

    function writeHiddenInputs() {
        var info = syncFooterMeta();
        var parts = [];
        info.selection.forEach(function (item, idx) {
            parts.push('<input type="hidden" name="selection[' + idx + '][chapter_id]" value="' + item.chapter_id + '">');
            (item.topic_ids || []).forEach(function (tid) {
                parts.push('<input type="hidden" name="selection[' + idx + '][topic_ids][]" value="' + tid + '">');
            });
            (item.sub_topic_ids || []).forEach(function (sid) {
                parts.push('<input type="hidden" name="selection[' + idx + '][sub_topic_ids][]" value="' + sid + '">');
            });
        });
        inputsWrap.innerHTML = parts.join('');
    }

    function applySearch(q) {
        q = (q || '').toLowerCase().trim();
        var any = false;
        listEl.querySelectorAll('.ct-chapter').forEach(function (root) {
            var chName = root.getAttribute('data-search') || '';
            var topicMatch = false;
            root.querySelectorAll('.ct-topic-wrap').forEach(function (tw) {
                var tSearch = tw.getAttribute('data-search') || '';
                var showTopic = !q || tSearch.indexOf(q) !== -1 || chName.indexOf(q) !== -1;
                tw.style.display = showTopic ? '' : 'none';
                if (showTopic) topicMatch = true;
            });
            var showChapter = !q || chName.indexOf(q) !== -1 || topicMatch;
            root.style.display = showChapter ? '' : 'none';
            if (showChapter) any = true;
        });
        var empty = listEl.querySelector('.ct-empty-search');
        if (!any) {
            if (!empty) {
                empty = document.createElement('div');
                empty.className = 'ct-empty ct-empty-search';
                empty.textContent = 'No chapters found.';
                listEl.appendChild(empty);
            }
        } else if (empty) {
            empty.remove();
        }
    }

    // One delegated listener — no rebind, no full re-render
    listEl.addEventListener('click', function (e) {
        var topicExpand = e.target.closest('.ct-topic-expand');
        if (topicExpand) {
            e.preventDefault();
            e.stopPropagation();
            var ch = chapterMap[String(topicExpand.getAttribute('data-chapter'))];
            var t = ch ? topicById(ch, topicExpand.getAttribute('data-id')) : null;
            if (!t) return;
            t._open = !t._open;
            if (!ch._open) ch._open = true;
            paintChapter(ch);
            return;
        }
        var chapterExpand = e.target.closest('.ct-chapter-expand');
        if (chapterExpand) {
            e.preventDefault();
            e.stopPropagation();
            var ch2 = chapterMap[String(chapterExpand.getAttribute('data-id'))];
            if (!ch2) return;
            ch2._open = !ch2._open;
            paintChapter(ch2);
        }
    });

    listEl.addEventListener('change', function (e) {
        var target = e.target;
        if (!target) return;

        if (target.classList.contains('ct-chapter-cb')) {
            var ch = chapterMap[String(target.getAttribute('data-id'))];
            if (!ch) return;
            setCheckedTree(ch, target.checked);
            if (target.checked && !ch._open) ch._open = true;
            paintChapter(ch);
            scheduleFooter();
            return;
        }

        if (target.classList.contains('ct-topic-cb')) {
            var chT = chapterMap[String(target.getAttribute('data-chapter'))];
            var topic = chT ? topicById(chT, target.getAttribute('data-id')) : null;
            if (!chT || !topic) return;
            topic._checked = target.checked;
            topic._indeterminate = false;
            (topic.subtopics || []).forEach(function (s) { s._checked = target.checked; });
            syncChapterFromTopics(chT);
            if (!chT._open) chT._open = true;
            paintChapter(chT);
            scheduleFooter();
            return;
        }

        if (target.classList.contains('ct-sub-cb')) {
            var chS = chapterMap[String(target.getAttribute('data-chapter'))];
            var topicS = chS ? topicById(chS, target.getAttribute('data-topic')) : null;
            if (!chS || !topicS) return;
            var sid = String(target.getAttribute('data-id'));
            (topicS.subtopics || []).forEach(function (s) {
                if (String(s.id) === sid) s._checked = target.checked;
            });
            syncTopicFromSubs(topicS);
            syncChapterFromTopics(chS);
            topicS._open = true;
            chS._open = true;
            paintChapter(chS);
            scheduleFooter();
        }
    });

    searchEl.addEventListener('input', function () {
        if (searchTimer) clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            applySearch(searchEl.value);
        }, 120);
    });

    if (formEl) {
        formEl.addEventListener('submit', function () {
            writeHiddenInputs();
        });
    }

    listEl.innerHTML = '<div class="ct-empty">Loading chapters...</div>';

    fetch(@json(route('custom_test.chapters_data', $subject->id)), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var saved = @json($savedSelection ?? []);
        var savedMap = {};
        (saved || []).forEach(function (item) {
            savedMap[String(item.chapter_id)] = item;
        });

        chapters = (data.chapters || []).map(function (ch) {
            var savedItem = savedMap[String(ch.id)];
            var savedTopics = (savedItem && savedItem.topic_ids) ? savedItem.topic_ids.map(String) : [];
            var savedSubs = (savedItem && savedItem.sub_topic_ids) ? savedItem.sub_topic_ids.map(String) : [];
            var wholeChapter = !!(savedItem && !savedTopics.length && !savedSubs.length);

            ch._checked = wholeChapter;
            ch._open = !!savedItem;
            ch._indeterminate = false;
            (ch.topics || []).forEach(function (t) {
                var topicFully = wholeChapter || savedTopics.indexOf(String(t.id)) !== -1;
                t._checked = topicFully;
                t._open = false;
                t._indeterminate = false;
                (t.subtopics || []).forEach(function (s) {
                    s._checked = topicFully || savedSubs.indexOf(String(s.id)) !== -1;
                });
                if (!topicFully && (t.subtopics || []).some(function (s) { return s._checked; })) {
                    t._indeterminate = true;
                    t._open = true;
                }
            });
            if (!wholeChapter && (ch.topics || []).some(function (t) { return t._checked || t._indeterminate; })) {
                ch._indeterminate = true;
            }
            chapterMap[String(ch.id)] = ch;
            return ch;
        });

        buildHtml();
        syncFooterMeta();
    })
    .catch(function () {
        listEl.innerHTML = '<div class="ct-empty">Failed to load chapters.</div>';
    });
})();
</script>
</body>
</html>
