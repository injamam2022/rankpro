<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => $subject->name.' — Chapter Master']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    <style>
        .calltoaction{display:none}
        .cm-wrap{max-width:920px;margin:0 auto;padding-bottom:2.5rem}
        .cm-back{display:inline-flex;align-items:center;gap:.4rem;color:#344054;text-decoration:none;font-weight:500;margin-bottom:.85rem}
        .cm-search{display:flex;align-items:center;gap:.6rem;border:1px solid #d0d5dd;border-radius:.75rem;padding:.65rem .9rem;background:#fff;margin-bottom:1rem}
        .cm-search input{border:0;outline:0;width:100%;background:transparent}
        .cm-ch{border:1px solid #eaecf0;border-radius:.9rem;background:#fff;margin-bottom:.55rem;overflow:hidden}
        .cm-ch-row{display:grid;grid-template-columns:1fr auto auto;gap:.75rem;align-items:center;padding:.9rem 1rem;cursor:pointer}
        .cm-ch-name{font-weight:600;color:#101828}
        .cm-ch-meta{font-size:.78rem;color:#98a2b3}
        .cm-badge-soon{font-size:.7rem;font-weight:600;color:#b54708;background:#fffaeb;border:1px solid #fedf89;border-radius:999px;padding:.18rem .5rem}
        .cm-badge-ok{font-size:.7rem;font-weight:600;color:#027a48;background:#ecfdf3;border:1px solid #abefc6;border-radius:999px;padding:.18rem .5rem}
        .cm-btn{border:0;border-radius:.65rem;padding:.45rem .85rem;font-size:.8rem;font-weight:600;background:#5b4bb7;color:#fff}
        .cm-btn:disabled,.cm-btn.is-soon{background:#e4e7ec;color:#98a2b3;cursor:not-allowed}
        .cm-topics{display:none;border-top:1px solid #f2f4f7;background:#fcfcfd}
        .cm-ch.open .cm-topics{display:block}
        .cm-topic{display:grid;grid-template-columns:1fr auto auto;gap:.65rem;align-items:center;padding:.65rem 1rem .65rem 1.25rem;border-top:1px solid #f2f4f7}
        .cm-topic-name{font-size:.88rem;color:#344054}
        .cm-empty{text-align:center;color:#98a2b3;padding:2rem 1rem}
        .cm-expand{border:0;background:transparent;color:#98a2b3;width:28px;height:28px;border-radius:50%}
    </style>
    @include('site.include.head_meta')
</head>
<body class="rp-dash-body">
@include('site.include.body_meta')
@include('site.include.student_dashboard_header')
<section id="dashboard">
  <div class="container-fluid">
    <div class="dashboardAll dashboardPh">
      <div class="menuBarBtn menuBarBtnOpen"><i class="fas fa-bars"></i></div>
      <div class="dashboardLeft dashboardLeftOff">@include('site.include.student_left_menu')</div>
      <div class="dashboardRight"><div class="dashboardRightBody">
        <div class="cm-wrap">
          @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
          <a class="cm-back" href="{{ route('chapter_master') }}"><i class="fas fa-arrow-left"></i> Subjects</a>
          <div class="d-flex align-items-center gap-2 mb-2">
            <div class="rounded-3 d-grid place-items-center text-white" style="width:42px;height:42px;background:{{ $meta['color'] }};place-items:center;display:grid"><i class="{{ $meta['icon'] }}"></i></div>
            <div>
              <div class="fw-semibold" style="font-size:1.2rem">{{ $subject->name }}</div>
              <div class="small text-muted">Premium questions only · up to {{ $questionsPerChapter }} Qs / chapter</div>
            </div>
          </div>

          <div class="cm-search">
            <i class="fas fa-search text-muted"></i>
            <input type="search" id="cm-search" placeholder="Search chapters or topics" autocomplete="off">
          </div>

          <div id="cm-list"><div class="cm-empty">Loading chapters…</div></div>
        </div>
      </div></div>
    </div>
  </div>
</section>

<form id="cm-start-form" method="post" action="{{ route('chapter_master.start') }}" class="d-none">
  @csrf
  <input type="hidden" name="subject_id" value="{{ $subject->id }}">
  <input type="hidden" name="chapter_id" id="cm-chapter-id">
  <input type="hidden" name="topic_id" id="cm-topic-id">
</form>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('web/bootstrap-5.0.2/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('web/js/main.js') }}"></script>
<script>
(function () {
  var listEl = document.getElementById('cm-list');
  var searchEl = document.getElementById('cm-search');
  var formEl = document.getElementById('cm-start-form');
  var chapterInput = document.getElementById('cm-chapter-id');
  var topicInput = document.getElementById('cm-topic-id');
  var chapters = [];
  var targetQs = {{ (int) $questionsPerChapter }};

  function esc(s) {
    return String(s || '').replace(/[&<>"']/g, function (c) {
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]);
    });
  }

  function startTest(chapterId, topicId) {
    chapterInput.value = chapterId || '';
    topicInput.value = topicId || '';
    formEl.submit();
  }

  function render(filter) {
    var q = (filter || '').toLowerCase().trim();
    var html = '';
    var shown = 0;
    chapters.forEach(function (ch) {
      var topics = ch.topics || [];
      var matchCh = !q || ch.name.toLowerCase().indexOf(q) !== -1;
      var matchTopics = topics.filter(function (t) { return !q || t.name.toLowerCase().indexOf(q) !== -1; });
      if (!matchCh && matchTopics.length === 0) return;
      shown++;
      var soon = !!ch.coming_soon;
      html += '<div class="cm-ch" data-id="'+ch.id+'">';
      html += '<div class="cm-ch-row" data-toggle="1">';
      html += '<div><div class="cm-ch-name">'+esc(ch.name)+'</div>';
      html += '<div class="cm-ch-meta">'+(soon ? 'Premium bank not ready' : (ch.questions + ' premium Qs · ' + topics.length + ' topics'))+'</div></div>';
      html += soon ? '<span class="cm-badge-soon">Coming soon</span>' : '<span class="cm-badge-ok">Ready</span>';
      html += '<div class="d-flex align-items-center gap-2">';
      if (soon) {
        html += '<button type="button" class="cm-btn is-soon" disabled>Coming soon</button>';
      } else {
        html += '<button type="button" class="cm-btn" data-start-chapter="'+ch.id+'">Start chapter test</button>';
      }
      html += '<button type="button" class="cm-expand" data-toggle="1"><i class="fas fa-chevron-down"></i></button>';
      html += '</div></div>';
      html += '<div class="cm-topics">';
      if (!topics.length) {
        html += '<div class="cm-topic"><div class="cm-topic-name text-muted">No topics listed</div></div>';
      } else {
        (matchCh ? topics : matchTopics).forEach(function (t) {
          var tSoon = !!t.coming_soon;
          html += '<div class="cm-topic">';
          html += '<div class="cm-topic-name">'+esc(t.name)+'<div class="cm-ch-meta">'+(tSoon ? 'No premium questions yet' : (t.questions + ' premium Qs'))+'</div></div>';
          html += tSoon ? '<span class="cm-badge-soon">Coming soon</span>' : '<span class="cm-badge-ok">'+t.questions+' Q</span>';
          if (tSoon) {
            html += '<button type="button" class="cm-btn is-soon" disabled>Coming soon</button>';
          } else {
            html += '<button type="button" class="cm-btn" data-start-topic="'+t.id+'" data-chapter="'+ch.id+'">Start topic test</button>';
          }
          html += '</div>';
        });
      }
      html += '</div></div>';
    });
    listEl.innerHTML = shown ? html : '<div class="cm-empty">No chapters match your search.</div>';
  }

  listEl.addEventListener('click', function (e) {
    var startCh = e.target.closest('[data-start-chapter]');
    if (startCh) {
      e.preventDefault();
      e.stopPropagation();
      startTest(startCh.getAttribute('data-start-chapter'), '');
      return;
    }
    var startTopic = e.target.closest('[data-start-topic]');
    if (startTopic) {
      e.preventDefault();
      e.stopPropagation();
      startTest(startTopic.getAttribute('data-chapter'), startTopic.getAttribute('data-start-topic'));
      return;
    }
    var toggle = e.target.closest('[data-toggle]');
    if (toggle) {
      var box = toggle.closest('.cm-ch');
      if (box) box.classList.toggle('open');
    }
  });

  searchEl.addEventListener('input', function () { render(searchEl.value); });

  fetch(@json(route('chapter_master.chapters_data', $subject->id)), {
    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
  }).then(function (r) { return r.json(); }).then(function (data) {
    chapters = data.chapters || [];
    if (data.target_questions) targetQs = data.target_questions;
    render('');
  }).catch(function () {
    listEl.innerHTML = '<div class="cm-empty">Could not load chapters. Please refresh.</div>';
  });
})();
</script>
</body>
</html>
