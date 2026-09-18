@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Counsellor</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.counsellor.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="{{$details->name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="email">Email ID <span class="text-danger">*</span></label>
                            <input class="form-control" id="email" type="text" name="email" placeholder=""  value="{{$details->email}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="phone_number">Phone Number <span class="text-danger">*</span></label>
                            <input class="form-control" id="phone_number" type="text" name="phone_number" placeholder=""  value="{{$details->phone_number}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                            <input class="form-control" id="address" type="text" name="address" placeholder=""  value="{{$details->address}}" />
                        </div>
                        <div class="">
                            
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="profile_icon">Profile Icon</label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <input class="form-control" id="profile_icon" type="file" name="profile_icon" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-2">
                                    @if($details->profile_icon)
                                        <img src="{{asset('')}}uploads/counsellor/thumbnail/{{$details->profile_icon}}" style="width: 50px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control" id="password" type="text" name="password" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="code">Code <span class="text-danger">*</span></label>
                            <input class="form-control" id="code" type="text" name="code" placeholder=""  value="{{$details->code}}" />
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
    <script type="text/javascript">
        
        function formValidation(){
            var data = {};
            var successFlag = true;

            data.subject_id = document.getElementById('subject_id').value;
            if(data.subject_id){
                document.getElementById('subject_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('subject_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.name = document.getElementById('name').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>

@endsection