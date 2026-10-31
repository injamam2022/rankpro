<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => $package->name.' Tests | RankPro']])
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
        .ps-wrap{max-width:960px;margin:0 auto;padding-bottom:2rem}
        .ps-meta{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1.25rem}
        .ps-chip{font-size:.72rem;font-weight:600;border-radius:999px;padding:.25rem .65rem;border:1px solid #e5e7eb;background:#f8fafc;color:#334155}
        .ps-chip--on{background:#ecfdf3;border-color:#abefc6;color:#027a48}
        .ps-chip--omr{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
        .ps-row{border:1px solid #eaecf0;border-radius:1rem;background:#fff;padding:1rem 1.1rem;margin-bottom:.75rem}
        .ps-row__top{display:flex;flex-wrap:wrap;gap:.75rem;justify-content:space-between;align-items:flex-start}
        .ps-badge{font-size:.7rem;font-weight:700;border-radius:999px;padding:.2rem .55rem;text-transform:uppercase;letter-spacing:.02em}
        .ps-badge--available{background:#ecfdf3;color:#027a48}
        .ps-badge--upcoming{background:#eff6ff;color:#1d4ed8}
        .ps-badge--ready{background:#f5f3ff;color:#5b21b6}
        .ps-badge--in_progress{background:#fff7ed;color:#c2410c}
        .ps-badge--completed{background:#f1f5f9;color:#475569}
        .ps-cov{font-size:.82rem;color:#64748b;margin-top:.35rem;line-height:1.45}
        .ps-actions{display:flex;flex-wrap:wrap;gap:.4rem;align-items:center}
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
        <div class="ps-wrap">
          @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
          @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

          <div class="dashboardTitle mb-1">{{ $package->name }} <span>Tests</span></div>
          <p class="text-muted mb-2">{{ $package->tagline }} · Premium questions only. Empty syllabus shows Coming soon until Premium Bank is filled.</p>

          <div class="ps-meta">
            @if($package->includes_online)<span class="ps-chip ps-chip--on"><i class="fas fa-laptop mr-1"></i> Online CBT unlocked</span>@endif
            @if($package->includes_omr)<span class="ps-chip ps-chip--omr"><i class="fas fa-file-alt mr-1"></i> OMR included</span>@endif
            @if($order)
              <span class="ps-chip">{{ $order->modeBadge() }}</span>
              <span class="ps-chip">Fulfillment: {{ str_replace('_', ' ', $order->fulfillment_status) }}</span>
            @endif
          </div>

          @if($package->includes_omr)
            <div class="alert alert-light border small mb-3">
              <strong>OMR:</strong>
              @if(($order->mode_code ?? '') === 'centre')
                Appear at your selected centre on the scheduled date/time.
              @elseif(($order->mode_code ?? '') === 'delivery')
                Physical papers ship to your address — track status under My Orders.
              @else
                Online + OMR both included. Use <strong>Start online test</strong> below for CBT; OMR follows the same schedule at centre / as arranged by RankPro.
              @endif
            </div>
          @endif

          @forelse($rows as $row)
            @php $s = $row['schedule']; @endphp
            <div class="ps-row">
              <div class="ps-row__top">
                <div class="flex-grow-1">
                  <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="fw-semibold">{{ $s->test_type }}</div>
                    <span class="ps-badge ps-badge--{{ $row['status'] }}">{{ str_replace('_', ' ', $row['status']) }}</span>
                  </div>
                  <div class="small text-muted mt-1">
                    {{ optional($s->test_date)->format('d M Y') }} · {{ $s->timing }}
                    @if($s->section)<span>· {{ ucfirst($s->section) }} syllabus</span>@endif
                  </div>
                  @if(!empty($row['coverage']))
                    <div class="ps-cov">
                      @foreach($row['coverage'] as $line)
                        <div>{{ $line }}</div>
                      @endforeach
                    </div>
                  @endif
                </div>
                <div class="ps-actions">
                  @if($row['can_resume'] && $row['attempt'])
                    <a href="{{ route('custom_test.resume', $row['attempt']->id) }}" class="btn btn-sm btn-primary">
                      {{ $row['status'] === 'ready' ? 'Start exam' : 'Resume' }}
                    </a>
                  @elseif($row['status'] === 'completed' && $row['attempt'])
                    <a href="{{ route('custom_test.analysis', $row['attempt']->id) }}" class="btn btn-sm btn-outline-secondary">View analysis</a>
                    @if($package->includes_online)
                      <form method="post" action="{{ route('package_series.start', $package->slug) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Retake online</button>
                      </form>
                    @endif
                  @elseif($row['can_start_online'])
                    <form method="post" action="{{ route('package_series.start', $package->slug) }}" class="d-inline">
                      @csrf
                      <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                      <button type="submit" class="btn btn-sm btn-primary">Start online test</button>
                    </form>
                  @endif
                  @if($package->includes_omr)
                    <span class="small text-muted">OMR on schedule</span>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <div class="alert alert-light border">No tests on the schedule yet. Contact RankPro admin.</div>
          @endforelse

          <div class="mt-3">
            <a href="{{ route('my_orders') }}" class="small">← My Orders</a>
          </div>
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
