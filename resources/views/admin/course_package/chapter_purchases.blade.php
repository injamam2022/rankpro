@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4">
        <h1 class="page-header mb-0">Chapter-wise Purchase Details</h1>
    </div>
    <p class="text-muted">Which student purchased which chapter, with the purchase date, payment status and transaction.</p>
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>Student</th>
                <th>RankPro ID</th>
                <th>Subject</th>
                <th>Chapter</th>
                <th>Package</th>
                <th>Purchase date</th>
                <th>Payment status</th>
                <th>Amount</th>
                <th>Transaction</th>
                <th>Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $line)
                @php
                    $order = $line->order;
                    $user = $order->user ?? null;
                    $chapter = $line->chapter;
                    $subject = ($chapter && $chapter->subject) ? $chapter->subject->name : '—';
                @endphp
                <tr>
                    <td>
                        {{ trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: '—' }}
                        <br><small>{{ $user->email_id ?? '' }}</small>
                    </td>
                    <td>{{ $user->rankpro_id ?? '—' }}</td>
                    <td>{{ $subject }}</td>
                    <td>{{ $chapter->name ?? ('Chapter #'.$line->chapter_id) }}</td>
                    <td>{{ $order->package->name ?? '—' }}</td>
                    <td>{{ optional($order->created_at)->format('d M Y, h:i A') }}</td>
                    <td>{{ $order->payment_status ?? '—' }}</td>
                    <td>₹{{ number_format((float) ($line->price ?: $order->final_amount), 2) }}</td>
                    <td>{{ $order->transaction_id ?: ($order->gateway_order_id ?: '—') }}</td>
                    <td>
                        <a href="{{ route('admin.course_package.order_detail', ['id' => $order->id]) }}">{{ $order->order_number }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
