<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'My Orders | RankPro']])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    <style>.calltoaction{display:none}</style>
    @include('site.include.head_meta')
</head>
<body class="rp-dash-body">
@include('site.include.body_meta')
@include('site.include.student_dashboard_header')

<section id="dashboard">
  <div class="container-fluid">
      <div class="dashboardAll dashboardPh">
          <div class="menuBarBtn menuBarBtnOpen"><i class="fas fa-bars"></i></div>
          <div class="dashboardLeft dashboardLeftOff">
              @include('site.include.student_left_menu')
          </div>
          <div class="dashboardRight">
              <div class="dashboardRightBody">
                  <div class="dashboardBlock dashboardBlockShadow p-4">
                      <div class="dashboardTitle mb-3">My <span>Orders</span></div>
                      @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                      @endif
                      @forelse($orders as $order)
                        <div class="border rounded-3 p-3 mb-3 bg-white">
                            <div class="d-flex flex-wrap justify-content-between gap-2">
                                <div>
                                    <div class="fw-semibold">{{ $order->package->name ?? 'Package' }}</div>
                                    <div class="small text-muted">{{ $order->order_number }} · {{ $order->created_at->format('d M Y, h:i A') }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">₹{{ number_format($order->final_amount, 2) }}</div>
                                    <span class="badge bg-{{ $order->isPaid() ? 'success' : 'warning' }}">{{ $order->payment_status }}</span>
                                </div>
                            </div>
                            <div class="mt-2 small">
                                <strong>Mode:</strong> {{ $order->modeBadge() }}
                                · <strong>Fulfilment:</strong> {{ str_replace('_', ' ', $order->fulfillment_status) }}
                            </div>
                            @if($order->location)
                                <div class="small mt-1"><strong>Centre:</strong> {{ $order->location->location_name }}</div>
                            @endif
                            @if($order->shipping_address)
                                <div class="small mt-1">
                                    <strong>Delivery:</strong>
                                    {{ $order->shipping_name }}, {{ $order->shipping_address }},
                                    {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                                </div>
                            @endif
                            @if($order->package && $order->package->code === 'free_custom')
                                <a href="{{ route('custom_test') }}" class="btn btn-sm btn-primary mt-2">Open Custom Test</a>
                            @elseif($order->package && $order->package->code === 'chapter_master' && $order->isPaid())
                                <a href="{{ route('chapter_master') }}" class="btn btn-sm btn-primary mt-2">Start Chapter Master exams</a>
                            @elseif($order->package && $order->isPaid() && in_array($order->package->code, \App\Support\CoursePackageAccess::seriesPackageCodes(), true))
                                <a href="{{ route('package_series.show', $order->package->slug) }}" class="btn btn-sm btn-primary mt-2">Open series tests</a>
                            @elseif($order->package)
                                <a href="{{ route('course_packages.show', $order->package->slug) }}" class="btn btn-sm btn-outline-primary mt-2">View package</a>
                            @endif
                        </div>
                      @empty
                        <div class="alert alert-light border">No package orders yet.
                            <a href="{{ route('course_packages') }}">Browse course packages</a>
                        </div>
                      @endforelse
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('web/bootstrap-5.0.2/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('web/js/main.js') }}"></script>
</body>
</html>
