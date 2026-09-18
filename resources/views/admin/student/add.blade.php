@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Student</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.student.save')}}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="first_name" type="text" name="first_name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="last_name" type="text" name="last_name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="profile_img">Profile Icon (Image Size: W- 193 px, H- 193 px) (Type: PNG,JPG,JPEG)</label>
                            <input class="form-control" id="profile_img" type="file" name="profile_img" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="email">Email ID <span class="text-danger">*</span></label>
                            <input class="form-control" id="email_id" type="text" name="email_id" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <input class="form-control" id="password" type="text" name="password" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="mobile_number">Phone Number</label>
                            <input class="form-control" id="mobile_number" type="text" name="mobile_number" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="qualification_details">Qualification Details</label>
                            <input class="form-control" id="qualification_details" type="text" name="qualification_details" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="address">Location</label>
                            <input class="form-control" id="address" type="text" name="address" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" id="status_active" type="radio" name="status" checked value="1" />
                                <label class="form-check-label" for="status_active">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
                                <label class="form-check-label" for="status_inactive">Inactive</label>
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
