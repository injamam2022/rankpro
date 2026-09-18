@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Student</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.student.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="first_name" type="text" name="first_name" placeholder=""  value="{{$details->first_name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="last_name" type="text" name="last_name" placeholder=""  value="{{$details->last_name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="image">Profile Icon (Image Size: W- 193 px, H- 193 px) (Type: PNG,JPG,JPEG)</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="profile_img" type="file" name="profile_img" >

                                @if($details->profile_img)
                                    <img src="{{ asset('' . $details->profile_img) }}" id="image-preview" style="width: 50px; margin-left: 10px;">
                                @else
                                    <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label" for="email">Email ID <span class="text-danger">*</span></label>
                            <input class="form-control" id="email_id" type="text" name="email_id" placeholder=""  value="{{$details->email_id}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="mobile_number">Phone Number <span class="text-danger">*</span></label>
                            <input class="form-control" id="mobile_number" type="text" name="mobile_number" placeholder=""  value="{{$details->mobile_number}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="qualification_details">Qualification Details <span class="text-danger">*</span></label>
                            <input class="form-control" id="qualification_details" type="text" name="qualification_details" placeholder=""  value="{{$details->qualification_details}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                            <input class="form-control" id="address" type="text" name="address" placeholder=""  value="{{$details->address}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="father_full_name">Father's Full Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="father_full_name" type="text" name="father_full_name" placeholder=""  value="{{$details->father_full_name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="father_occupation">Father's Occupation <span class="text-danger">*</span></label>
                            <input class="form-control" id="father_occupation" type="text" name="father_occupation" placeholder=""  value="{{$details->father_occupation}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="father_mobile_number">Father's Contact No</label>
                            <input class="form-control" id="father_mobile_number" type="text" name="father_mobile_number" placeholder=""  value="{{$details->father_mobile_number}}" />
                        </div>
                        <!-- <div class="col-sm-6">
                            <label class="form-label" for="father_qualification">Father's Qualification</label>
                            <input class="form-control" id="father_qualification" type="text" name="father_qualification" placeholder=""  value="{{$details->father_qualification}}" />
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="mother_full_name">Mother's Full Name</label>
                            <input class="form-control" id="mother_full_name" type="text" name="mother_full_name" placeholder=""  value="{{$details->mother_full_name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="mother_occupation">Mother's Occupation</label>
                            <input class="form-control" id="mother_occupation" type="text" name="mother_occupation" placeholder=""  value="{{$details->mother_occupation}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="mother_mobile_number">Mother's Contact No</label>
                            <input class="form-control" id="mother_mobile_number" type="text" name="mother_mobile_number" placeholder=""  value="{{$details->mother_mobile_number}}" />
                        </div>
                        <!-- <div class="col-sm-6">
                            <label class="form-label" for="mother_qualification">Mother's Qualification</label>
                            <input class="form-control" id="mother_qualification" type="text" name="mother_qualification" placeholder=""  value="{{$details->mother_qualification}}" />
                        </div> -->
                        <!-- <div class="col-sm-6">
                            <label class="form-label" for="qualification_details">Qualification Details</label>
                            <input class="form-control" id="qualification_details" type="text" name="qualification_details" placeholder=""  value="{{$details->qualification_details}}" />
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="certificate">Certificate</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="certificate" type="file" name="certificate">

                                @if($details->certificate)
                                    <a href="{{ asset('' . $details->certificate) }}" target="_blank" style="margin-left: 10px;">
                                        View
                                    </a>
                                @else
                                    <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                                @endif
                            </div>
                        </div>
                        <!-- <div class="col-sm-6">
                            <label class="form-label" for="guardian_signature">Guardian Signature</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="guardian_signature" type="file" name="guardian_signature">

                                @if($details->guardian_signature)
                                    <a href="{{ asset('' . $details->guardian_signature) }}" target="_blank" style="margin-left: 10px;">
                                        View
                                    </a>
                                @else
                                    <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="student_signature">Student Signature</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="student_signature" type="file" name="student_signature">

                                @if($details->student_signature)
                                    <a href="{{ asset('' . $details->student_signature) }}" target="_blank" style="margin-left: 10px;">
                                        View
                                    </a>
                                @else
                                    <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                                @endif
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" id="status_active" type="radio" name="status" @if($details->status == 1) checked  @endif value="1" />
                                <label class="form-check-label" for="status_active">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="status_inactive" type="radio" name="status" @if($details->status == 0) checked  @endif value="0" />
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
    <script type="text/javascript">

        const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
        const maxSize1 = 1024 * 1024;

        function formValidation() {
            
            var data = {};
            var successFlag = true;

            data.first_name = document.getElementById('first_name').value;
            if(data.first_name){
                document.getElementById('first_name').classList.remove('dangerBoader');
            }else{
                document.getElementById('first_name').classList.add('dangerBoader');
                successFlag = false;
            }

            data.last_name = document.getElementById('last_name').value;
            if(data.last_name){
                document.getElementById('last_name').classList.remove('dangerBoader');
            }else{
                document.getElementById('last_name').classList.add('dangerBoader');
                successFlag = false;
            }

            data.email_id = document.getElementById('email_id').value;
            if(data.email_id){
                document.getElementById('email_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('email_id').classList.add('dangerBoader');
                successFlag = false;
            }
            

            data.mobile_number = document.getElementById('mobile_number').value;
            if(data.mobile_number){
                document.getElementById('mobile_number').classList.remove('dangerBoader');
            }else{
                document.getElementById('mobile_number').classList.add('dangerBoader');
                successFlag = false;
            }

            data.qualification_details = document.getElementById('qualification_details').value;
            if(data.qualification_details){
                document.getElementById('qualification_details').classList.remove('dangerBoader');
            }else{
                document.getElementById('qualification_details').classList.add('dangerBoader');
                successFlag = false;
            }

            data.address = document.getElementById('address').value;
            if(data.address){
                document.getElementById('address').classList.remove('dangerBoader');
            }else{
                document.getElementById('address').classList.add('dangerBoader');
                successFlag = false;
            }

            data.father_full_name = document.getElementById('father_full_name').value;
            if(data.father_full_name){
                document.getElementById('father_full_name').classList.remove('dangerBoader');
            }else{
                document.getElementById('father_full_name').classList.add('dangerBoader');
                successFlag = false;
            }

            data.father_mobile_number = document.getElementById('father_mobile_number').value;
            if(data.father_mobile_number){
                document.getElementById('father_mobile_number').classList.remove('dangerBoader');
            }else{
                document.getElementById('father_mobile_number').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>

@endsection
