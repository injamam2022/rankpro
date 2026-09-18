@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Payment New Light Details</h1>
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
                        <label class="form-label" for="chapter_id">Test Series</label>
                        <div class="form-control">
                            {!! $details->heading !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Language </label>
                        <div class="form-control">
                            {{$details->language_name}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Location  </label>
                        <div class="form-control">
                            {{$details->location_name}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Start Date </label>
                        <div class="form-control">
                            {{$details->date_name}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Name </label>
                        <div class="form-control">
                            {{$details->name}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Email </label>
                        <div class="form-control">
                            {{$details->email}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="source_id">Mobile Number </label>
                        <div class="form-control">
                            {{$details->mobile_number}}
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