@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Contact</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.contact.update')}}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="{{$details->name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" type="text" name="email" placeholder=""  value="{{$details->email}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="mobile_number">Mobile Number</label>
                            <input class="form-control" id="mobile_number" type="text" name="mobile_number" placeholder=""  value="{{$details->mobile_number}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="message">Message</label>
                            <input class="form-control" id="message" type="text" name="message" placeholder=""  value="{{$details->message}}" />
                        </div>
                        
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" @if($details->status == 1) checked  @endif value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" @if($details->status == 0) checked  @endif value="0" />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-12 mt-5 text-center">
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-secondary cancelButton" type="button">Cancel</a>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                        <div class="mb-5">
                            &nbsp;
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js_after')

@endsection