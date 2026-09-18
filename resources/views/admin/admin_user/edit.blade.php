@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Admin User</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.admin_user.update')}}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="user_name" type="text" name="user_name" placeholder=""  value="{{$details->user_name}}" />
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-8">
                                    <label class="form-label" for="profile_icon">Profile Icon <span class="text-danger">*</span></label>
                                    <input class="form-control" id="profile_icon" type="file" name="profile_icon" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-4">
                                    @if($details->profile_icon)
                                        <img src="{{ asset('') }}uploads/admin_user/thumbnail/{{$details->profile_icon}}" style="width:50px;">
                                    @else

                                    @endif
                                </div>
                            </div>
                                    
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="login_email">Email ID <span class="text-danger">*</span></label>
                            <input class="form-control" id="login_email" type="text" name="login_email" placeholder=""  value="{{$details->login_email}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <input class="form-control" id="password" type="text" name="password" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="phone_number">Phone Number</label>
                            <input class="form-control" id="phone_number" type="text" name="phone_number" placeholder=""  value="{{$details->phone_number}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="admin_role_role_id">Admin Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="admin_role_role_id" name="admin_role_role_id">
                                <option value="">Select</option>
                                @foreach($admin_user_role_list as $value)
                                    <option value="{{$value->id}}" @if($details->admin_role_role_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
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
