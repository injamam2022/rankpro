@extends('layouts.counsellor')

@section('content')
@php
    $isChapterMaster = $details->package && $details->package->code === 'chapter_master';
    $isMission720 = $details->package && $details->package->code === 'mission_720';
    $purchasedBySubject = $isChapterMaster
        ? $details->orderChapters->groupBy(fn ($line) => $line->chapter->subject->name ?? 'Subject')
        : collect();
@endphp
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4 align-items-center justify-content-between">
        <h1 class="page-header mb-0">Order {{ $details->order_number }}</h1>
        <a href="{{ route('counsellor.payment.orders') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Student:</strong> {{ trim(($details->user->first_name ?? '').' '.($details->user->last_name ?? '')) }} ({{ $details->user->email_id ?? '' }})</p>
                    <p><strong>Package:</strong> {{ $details->package->name ?? '—' }}</p>
                    <p><strong>Mode:</strong> {{ $details->modeBadge() }}</p>
                    <p><strong>Amount:</strong> ₹{{ number_format($details->final_amount, 2) }} ({{ $details->payment_status }})</p>
                    <p class="mb-0"><strong>Txn:</strong> {{ $details->transaction_id ?: '—' }}</p>
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
                    <p><strong>Fulfilment:</strong> {{ $details->fulfillment_status }}</p>
                    @if($isMission720 && $details->isPaid())
                        <div class="alert alert-info mb-0">
                            Rank Pro Mission 720 unlocks <strong>entire Rank Chapter Master</strong> for this student (all PCB chapters).
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($isChapterMaster)
        <div class="card mb-4">
            <div class="card-header fw-semibold">Chapters selected at payment</div>
            <div class="card-body">
                @if($details->orderChapters->count())
                    <div class="row g-3">
                        @foreach($purchasedBySubject as $subjectName => $lines)
                            <div class="col-md-4">
                                <div class="fw-semibold mb-2">{{ $subjectName }}</div>
                                <ul class="mb-0 ps-3">
                                    @foreach($lines as $line)
                                        <li>{{ $line->chapter->name ?? 'Chapter' }} — ₹{{ number_format($line->price, 0) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No chapters were stored with this payment.</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
