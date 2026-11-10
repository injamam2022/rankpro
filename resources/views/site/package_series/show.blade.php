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
        .ps-badge--expired{background:#fef2f2;color:#b91c1c}
        .ps-badge--waiting{background:#fff7ed;color:#c2410c}
        .ps-cov{margin-top:.9rem}
        .ps-cov__block{margin:0 0 .85rem}
        .ps-cov__head{font-weight:700;color:#101828;margin:0 0 .35rem;font-size:.92rem}
        .ps-cov__body{margin:0;color:#475467;font-size:.84rem;line-height:1.6}
        .ps-actions{display:flex;flex-wrap:wrap;gap:.4rem;align-items:center}
        .ps-tabs{display:flex;flex-wrap:wrap;gap:.5rem;margin:0 0 1rem}
        .ps-tab{display:inline-flex;align-items:center;gap:.35rem;padding:.45rem .9rem;border-radius:999px;border:1px solid #e5e7eb;background:#fff;color:#334155;font-size:.82rem;font-weight:600;text-decoration:none}
        .ps-tab:hover{border-color:#c7d2fe;color:#4338ca}
        .ps-tab.is-on{background:#5b4bb7;border-color:#5b4bb7;color:#fff}
        .ps-tab.is-locked{color:#667085}
        .ps-tab.is-on.is-locked{background:#ede9fe;border-color:#c4b5fd;color:#5b21b6}
        .ps-tab .fa-lock{font-size:.65rem}
        .ps-pay{border:1px solid #eaecf0;border-radius:1.1rem;background:#fff;padding:1.6rem 1.4rem;text-align:center;max-width:520px;margin:0 auto}
        .ps-pay__lock{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#5b4bb7,#7c3aed);color:#fff;display:grid;place-items:center;margin:0 auto 1rem}
        .ps-pay__price{font-size:1.7rem;font-weight:800;color:#5b4bb7;margin-bottom:.85rem}
        .ps-pay__feats{list-style:none;padding:0;margin:0 0 1.1rem;text-align:left}
        .ps-pay__feats li{font-size:.88rem;color:#334155;margin-bottom:.4rem;display:flex;gap:.5rem}
        .ps-pay__feats i{color:#16a34a;margin-top:.2rem}
        .ps-cov strong{color:#101828;font-weight:700}
        .ps-wrap .pagination{justify-content:center;margin-top:1.1rem}
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

          <div class="dashboardTitle mb-1">Rank Pro <span>Series</span></div>
          @include('site.include.series_package_tabs')
          <h2 class="h5 mb-1">{{ $package->name }}</h2>
          @if(empty($unlocked))
            <p class="text-muted mb-3">This tab stays visible for every student. Pay below, or get admin access, to start these tests.</p>
            @include('site.include.series_pay_panel', ['payPackage' => $package, 'payMode' => $payMode ?? null])
          @else
          <p class="text-muted mb-2">{{ $package->tagline }} · Series exams start only after admin assigns the paper.
            @if($package->code === 'mission_720')
              Rank Pro Mission 720 also unlocks <strong>entire Rank Chapter Master</strong> — open that tab for every chapter exam.
            @endif
          </p>

          <div class="ps-meta">
            @if($package->includes_online)<span class="ps-chip ps-chip--on"><i class="fas fa-laptop mr-1"></i> Online CBT unlocked</span>@endif
            @if($package->includes_omr)<span class="ps-chip ps-chip--omr"><i class="fas fa-file-alt mr-1"></i> Offline included</span>@endif
            @if($order)
              <span class="ps-chip">{{ $order->modeBadge() }}</span>
              <span class="ps-chip">Fulfillment: {{ str_replace('_', ' ', $order->fulfillment_status) }}</span>
            @endif
          </div>

          @if($package->includes_omr)
            <div class="alert alert-light border small mb-3">
              <strong>Offline:</strong>
              @if(($order->mode_code ?? '') === 'centre')
                Appear at your selected centre on the scheduled date/time.
              @elseif(($order->mode_code ?? '') === 'delivery')
                Physical papers ship to your address — track status under My Orders.
              @else
                Online + Offline both included. Use <strong>Start online test</strong> below for CBT; Offline follows the same schedule at centre / as arranged by RankPro.
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
                  @if(!empty($row['has_assigned_paper']))
                    <div class="small text-muted mt-1"><span class="ps-chip ps-chip--on">Paper ready</span></div>
                  @endif
                  @if(!empty($row['coverage']))
                    <div class="ps-cov">
                      @foreach($row['coverage'] as $line)
                        @php
                          $subjectHeading = is_array($line) ? trim((string) ($line['subject'] ?? '')) : '';
                          $topicBody = is_array($line) ? trim((string) ($line['topics'] ?? '')) : trim((string) $line);
                        @endphp
                        <div class="ps-cov__block">
                          @if($subjectHeading !== '')
                            <div class="ps-cov__head">{{ $subjectHeading }}</div>
                          @endif
                          @if($topicBody !== '')
                            <p class="ps-cov__body">{{ $topicBody }}</p>
                          @endif
                        </div>
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
                    <a href="{{ route('custom_test.download', $row['attempt']->id) }}" class="btn btn-sm btn-primary" target="_blank">Download paper</a>
                    <a href="{{ route('custom_test.analysis', $row['attempt']->id) }}" class="btn btn-sm btn-outline-secondary">View analysis</a>
                  @elseif($row['can_start_online'])
                    <form method="post" action="{{ route('package_series.start', $package->slug) }}" class="d-inline">
                      @csrf
                      <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                      <button type="submit" class="btn btn-sm btn-primary">Start online test</button>
                    </form>
                  @elseif($row['status'] === 'waiting')
                    <span class="btn btn-sm btn-outline-secondary disabled">Waiting for assigned paper</span>
                  @elseif($row['status'] === 'upcoming')
                    <span class="btn btn-sm btn-outline-secondary disabled">Opens {{ optional($s->test_date)->format('d M Y') }}</span>
                  @endif
                  @if($package->includes_omr)
                    <span class="small text-muted">Offline on schedule</span>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <div class="alert alert-light border">No tests on the schedule yet. Contact RankPro admin.</div>
          @endforelse

          @if(method_exists($rows, 'hasPages') && $rows->hasPages())
            <div class="d-flex justify-content-center">{{ $rows->links() }}</div>
          @endif

          <div class="mt-3">
            <a href="{{ route('my_orders') }}" class="small">← My Orders</a>
          </div>
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
