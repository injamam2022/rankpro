@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Payment Ranker Details</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label" for="subject_id">User Name</label>
                        <div class="form-control">
                            {{$details->first_name}} {{$details->last_name}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="subject_id">User Email</label>
                        <div class="form-control">
                            {{$details->email_id}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="chapter_id">Ranker</label>
                        <div class="form-control">
                            {{$details->ranker_name}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Ranker Meeting </label>
                        <div class="form-control">
                            {{$details->title}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Transaction ID </label>
                        <div class="form-control">
                            {{$details->transaction_id}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Coupon Discount </label>
                        <div class="form-control">
                            {{$details->coupon_discount}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Coupon Code </label>
                        <div class="form-control">
                            {{$details->coupon_code}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Amount </label>
                        <div class="form-control">
                            {{$details->amount}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Discount </label>
                        <div class="form-control">
                            {{$details->discount}}
                        </div>
                    </div>

                    <div class="col-lg-12 mt-5 text-center">
                        <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-secondary cancelButton" type="button">Cancel</a>
                    </div>
                    <div class="mb-5">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js_after')
    
@endsection