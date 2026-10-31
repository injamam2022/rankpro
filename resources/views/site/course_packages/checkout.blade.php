<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @include('site.include.seo', ['override' => ['title' => 'Checkout | '.$package->name]])
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
    $amount = (float) $mode->price;
    $tax = (float) ($package->tax ?? 0);
    $final = $amount + $tax;
@endphp

<main class="cpg">
    <div class="cpg__inner cpg-check">
        <a class="cpg-back" href="{{ route('course_packages.show', $package->slug) }}"><i class="fas fa-arrow-left"></i> Back to package</a>
        <div class="cpg__eyebrow"><i></i> Checkout</div>
        <h1 class="cpg__title" style="font-size:clamp(26px,3vw,40px);margin-bottom:28px;">Complete your <span class="cpg__grad">purchase</span></h1>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="cpg-box mb-3">
                    <h5>{{ $package->name }}</h5>
                    <p class="mb-1" style="color:#3a4260;"><strong>Mode:</strong> {{ $mode->label }}</p>
                    @if($mode->help_text)<p class="mb-0" style="color:#6b6577;font-size:13.5px;">{{ $mode->help_text }}</p>@endif
                </div>

                <form id="cpOrderForm" class="cpg-box">
                    <input type="hidden" name="mode_id" value="{{ $mode->id }}">
                    @if($mode->requires_location)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select centre *</label>
                            <select class="form-control" name="location_id" required>
                                <option value="">Choose centre</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->location_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($mode->requires_address)
                        <h6 class="mb-3 fw-bold">Delivery address</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full name *</label>
                                <input class="form-control" name="shipping_name" value="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input class="form-control" name="shipping_phone" value="{{ Auth::user()->mobile_number }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="shipping_address" rows="2" required></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City *</label>
                                <input class="form-control" name="shipping_city" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State *</label>
                                <input class="form-control" name="shipping_state" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PIN code *</label>
                                <input class="form-control" name="shipping_pincode" required>
                            </div>
                        </div>
                    @endif

                    @unless($mode->requires_location || $mode->requires_address)
                        <p class="mb-0" style="color:#6b6577;">No extra details needed for this mode. Continue to pay.</p>
                    @endunless
                </form>
            </div>

            <div class="col-lg-5">
                <div class="cpg-buy" style="position:static;">
                    <div class="cpg-sum-row"><span>Package</span><span>₹{{ number_format($amount, 2) }}</span></div>
                    <div class="cpg-sum-row"><span>Tax</span><span>₹{{ number_format($tax, 2) }}</span></div>
                    <div class="cpg-sum-total"><span>Total</span><span id="priceFinal">₹{{ number_format($final, 2) }}</span></div>
                    <button type="button" class="cpg-btn" id="payBtn" onclick="startPay()">Pay ₹{{ number_format($final, 2) }}</button>
                    <a href="{{ route('course_packages.show', $package->slug) }}" class="d-block text-center mt-3" style="color:#6b6577;text-decoration:none;font-weight:600;font-size:13px;">Cancel</a>
                    <div class="cpg-secure"><i class="fas fa-lock"></i> Secure payment via Razorpay</div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="successPopup" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 text-center" style="border-radius:18px;border:0;">
            <h5 style="font-family:Space Grotesk,sans-serif;font-weight:700;">Payment successful</h5>
            <p style="color:#6b6577;">Your order is ready in My Orders.</p>
            <a href="{{ route('my_orders') }}" class="cpg-btn">View My Orders</a>
        </div>
    </div>
</div>

@include('site.include.footer')
@include('site.include.call_to_action')
@include('site.include.back_to_top')

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('') }}web/bootstrap-5.0.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
<script src="{{ asset('') }}web/js/main.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
const finalPrice = {{ $final }};
const placeUrl = @json(route('course_packages.place_order', $package->slug));

function collectForm() {
    const form = document.getElementById('cpOrderForm');
    const data = {};
    new FormData(form).forEach((v, k) => data[k] = v);
    data.final_price = finalPrice;
    data.total_discount = 0;
    data.coupon_discount = 0;
    data._token = @json(csrf_token());
    return data;
}

function startPay() {
    const form = document.getElementById('cpOrderForm');
    if (!form.reportValidity()) return;

    if (finalPrice <= 0) {
        saveOrder(collectForm());
        return;
    }

    const options = {
        key: @json(env('RAZORPAY_KEY')),
        amount: Math.round(finalPrice * 100),
        currency: "INR",
        name: @json(env('RAZORPAY_NAME')),
        description: @json($package->name),
        handler: function (response) {
            const data = collectForm();
            data.razorpay_payment_id = response.razorpay_payment_id;
            data.razorpay_order_id = response.razorpay_order_id;
            data.razorpay_signature = response.razorpay_signature;
            saveOrder(data);
        },
        prefill: {
            name: @json(trim((Auth::user()->first_name ?? '').' '.(Auth::user()->last_name ?? ''))),
            email: @json(Auth::user()->email_id ?? ''),
            contact: @json(Auth::user()->mobile_number ?? ''),
        },
        theme: { color: "#f20b81" }
    };
    new Razorpay(options).open();
}

function saveOrder(data) {
    $('#payBtn').prop('disabled', true).text('Processing...');
    $.ajax({
        url: placeUrl,
        type: 'POST',
        data: data,
        success: function (res) {
            if (res.success) {
                if (res.redirect) {
                    window.location.href = res.redirect;
                    return;
                }
                $('#successPopup').modal('show');
            } else {
                alert(res.message || 'Could not place order');
                $('#payBtn').prop('disabled', false).text('Pay ₹{{ number_format($final, 2) }}');
            }
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Something went wrong';
            alert(msg);
            $('#payBtn').prop('disabled', false).text('Pay ₹{{ number_format($final, 2) }}');
        }
    });
}
</script>
</body>
</html>
