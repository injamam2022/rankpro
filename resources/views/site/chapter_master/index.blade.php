<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'Chapter Master Practice | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    <style>
        .calltoaction{display:none}
        .cm-wrap{max-width:880px;margin:0 auto;padding-bottom:2rem}
        .cm-card{border:1px solid #eaecf0;border-radius:1rem;background:#fff;padding:1.1rem 1.2rem;display:flex;gap:1rem;align-items:center;margin-bottom:.85rem;text-decoration:none;color:inherit;transition:border-color .15s,box-shadow .15s}
        .cm-card:hover{border-color:#c7d2fe;box-shadow:0 8px 22px rgba(16,24,40,.06);color:inherit}
        .cm-icon{width:48px;height:48px;border-radius:.85rem;display:grid;place-items:center;color:#fff;flex-shrink:0}
        .cm-soon{font-size:.72rem;font-weight:600;color:#b54708;background:#fffaeb;border:1px solid #fedf89;border-radius:999px;padding:.2rem .55rem}
        .cm-ready{font-size:.72rem;font-weight:600;color:#027a48;background:#ecfdf3;border:1px solid #abefc6;border-radius:999px;padding:.2rem .55rem}
        .cm-attempt{border:1px solid #eaecf0;border-radius:.85rem;padding:.85rem 1rem;background:#fff;margin-bottom:.55rem;display:flex;justify-content:space-between;gap:.75rem;align-items:center}
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
          @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
          @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

          <div class="dashboardTitle mb-1">Rank <span>Chapter Master</span></div>
          <p class="text-muted mb-3">Paid Course Package segment — tests use <strong>premium questions only</strong> (up to {{ $questionsPerChapter }} per chapter). Free practice stays under <a href="{{ route('custom_test') }}">Custom Test</a> (non-premium). Topics without premium questions show <em>Coming soon</em>.</p>

          @foreach($subjects as $card)
            @php $s = $card['subject']; @endphp
            <a class="cm-card" href="{{ route('chapter_master.subject', $s->id) }}">
              <div class="cm-icon" style="background:{{ $card['meta']['color'] }}"><i class="{{ $card['meta']['icon'] }}"></i></div>
              <div class="flex-grow-1">
                <div class="fw-semibold">{{ $s->name }}</div>
                <div class="small text-muted">
                  {{ $card['chapters_ready'] }} chapter{{ $card['chapters_ready'] == 1 ? '' : 's' }} ready · {{ $card['available'] }} premium Qs
                </div>
              </div>
              @if($card['ready'])
                <span class="cm-ready">Available</span>
              @else
                <span class="cm-soon">Coming soon</span>
              @endif
              <i class="fas fa-chevron-right text-muted"></i>
            </a>
          @endforeach

          @if($attempts->count())
            <h5 class="mt-4 mb-3">Recent chapter tests</h5>
            @foreach($attempts as $attempt)
              <div class="cm-attempt">
                <div>
                  <div class="fw-semibold" style="font-size:.95rem">{{ $attempt->name }}</div>
                  <div class="small text-muted">{{ $attempt->created_at?->format('d M Y, h:i A') }} · {{ $attempt->question_count }} Qs</div>
                </div>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('custom_test.resume', $attempt->id) }}">
                  {{ $attempt->status === 'ready' ? 'Start' : 'Open' }}
                </a>
              </div>
            @endforeach
          @endif
        </div>
      </div></div>
    </div>
  </div>
</section>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('web/bootstrap-5.0.2/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('web/js/main.js') }}"></script>
</body>
</html>
