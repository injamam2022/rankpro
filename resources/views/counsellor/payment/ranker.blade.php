@extends('layouts.counsellor')

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4">
            <h1 class="page-header mb-0">Payment Ranker</h1>
        </div>
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Student Email</th>
                    <th>Ranker Name</th>
                    <th>Ranker Meeting</th>
                    <th>Amount</th>
                    <th>Coupon</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $row)
                    <tr>
                        <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                        <td>{{ $row->email_id }}</td>
                        <td>{{ $row->ranker_name }}</td>
                        <td>{{ $row->title }}</td>
                        <td>{{ $row->amount }}</td>
                        <td>{{ $row->coupon_code }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td>
                            <a href="{{ route('counsellor.payment.ranker_detail', ['id' => $row->id]) }}" class="btn btn-success btn-sm">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
