@extends('layouts.counsellor')

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Payment Ranker Details</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">User Name</label>
                        <div class="form-control">{{ $details->first_name }} {{ $details->last_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">User Email</label>
                        <div class="form-control">{{ $details->email_id }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Ranker</label>
                        <div class="form-control">{{ $details->ranker_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Ranker Meeting</label>
                        <div class="form-control">{{ $details->title }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Transaction ID</label>
                        <div class="form-control">{{ $details->transaction_id }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Coupon Discount</label>
                        <div class="form-control">{{ $details->coupon_discount }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Coupon Code</label>
                        <div class="form-control">{{ $details->coupon_code }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Amount</label>
                        <div class="form-control">{{ $details->amount }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Discount</label>
                        <div class="form-control">{{ $details->discount }}</div>
                    </div>
                    <div class="col-lg-12 mt-5 text-center">
                        <a href="{{ route('counsellor.payment.ranker') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
