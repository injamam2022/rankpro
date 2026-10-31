@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4 align-items-center justify-content-between">
        <h1 class="page-header mb-0">Order {{ $details->order_number }}</h1>
        <a href="{{ route('admin.course_package.orders') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Student:</strong> {{ trim(($details->user->first_name ?? '').' '.($details->user->last_name ?? '')) }} ({{ $details->user->email_id ?? '' }})</p>
                    <p><strong>Package:</strong> {{ $details->package->name ?? '—' }}</p>
                    <p><strong>Mode:</strong> {{ $details->modeBadge() }}</p>
                    <p><strong>Amount:</strong> ₹{{ number_format($details->final_amount, 2) }} ({{ $details->payment_status }})</p>
                    <p><strong>Txn:</strong> {{ $details->transaction_id ?: '—' }}</p>
                </div>
                <div class="col-md-6">
                    @if($details->location)
                        <p><strong>Centre:</strong> {{ $details->location->location_name }}</p>
                    @endif
                    @if($details->shipping_address)
                        <p><strong>Ship to:</strong><br>
                            {{ $details->shipping_name }} · {{ $details->shipping_phone }}<br>
                            {{ $details->shipping_address }}<br>
                            {{ $details->shipping_city }}, {{ $details->shipping_state }} - {{ $details->shipping_pincode }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('admin.course_package.order_fulfillment') }}" method="post" class="card">
        @csrf
        <input type="hidden" name="id" value="{{ $details->id }}">
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label class="form-label">Fulfilment status</label>
                <select class="form-control" name="fulfillment_status">
                    @foreach(['pending','confirmed','packed','shipped','delivered','centre_registered','access_granted'] as $st)
                        <option value="{{ $st }}" @selected($details->fulfillment_status === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Admin notes</label>
                <textarea class="form-control" name="admin_notes" rows="2">{{ $details->admin_notes }}</textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Update fulfilment</button>
            </div>
        </div>
    </form>
</div>
@endsection
