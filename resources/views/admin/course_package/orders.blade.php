@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4">
        <h1 class="page-header mb-0">Course Package Orders</h1>
    </div>
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Student</th>
                <th>Package</th>
                <th>Mode</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Fulfilment</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $row)
                <tr>
                    <td>{{ $row->order_number }}</td>
                    <td>{{ trim(($row->user->first_name ?? '').' '.($row->user->last_name ?? '')) }}<br><small>{{ $row->user->email_id ?? '' }}</small></td>
                    <td>{{ $row->package->name ?? '—' }}</td>
                    <td>{{ $row->modeBadge() }}</td>
                    <td>₹{{ number_format($row->final_amount, 2) }}</td>
                    <td>{{ $row->payment_status }}</td>
                    <td>{{ $row->fulfillment_status }}</td>
                    <td>{{ $row->created_at }}</td>
                    <td><a href="{{ route('admin.course_package.order_detail', ['id' => $row->id]) }}" class="btn btn-success btn-sm">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
