@extends('layouts.counsellor')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Student</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('counsellor.student.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="user_tag_id">Tag</label>
                            <select class="form-control" id="user_tag_id" name="user_tag_id">
                                <option value="">Select Tag</option>
                                @foreach($user_tag_list as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="first_name" type="text" name="first_name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="last_name" type="text" name="last_name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="image">Profile Icon (Image Size: W- 193 px, H- 193 px) (Type: PNG,JPG,JPEG)</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="profile_img" type="file" name="profile_img" >
                            </div>
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
                            <label class="form-label" for="mobile_number">Phone Number <span class="text-danger">*</span></label>
                            <input class="form-control" id="mobile_number" type="text" name="mobile_number" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="qualification_details">Qualification Details <span class="text-danger">*</span></label>
                            <input class="form-control" id="qualification_details" type="text" name="qualification_details" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="school_name">School Name </label>
                            <input class="form-control" id="school_name" type="text" name="school_name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="class_name">Class Name </label>
                            <input class="form-control" id="class_name" type="text" name="class_name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="section_name">Section Name </label>
                            <input class="form-control" id="section_name" type="text" name="section_name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                            <input class="form-control" id="address" type="text" name="address" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="father_full_name">Father's Full Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="father_full_name" type="text" name="father_full_name" placeholder=""  value="" />
                        </div>
                        <!-- <div class="col-sm-6">
                            <label class="form-label" for="father_occupation">Father's Occupation <span class="text-danger">*</span></label>
                            <input class="form-control" id="father_occupation" type="text" name="father_occupation" placeholder=""  value="" />
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="father_mobile_number">Father's Contact No<span class="text-danger">*</span></label>
                            <input class="form-control" id="father_mobile_number" type="text" name="father_mobile_number" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="father_qualification">Father's Qualification</label>
                            <input class="form-control" id="father_qualification" type="text" name="father_qualification" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="mother_full_name">Mother's Full Name</label>
                            <input class="form-control" id="mother_full_name" type="text" name="mother_full_name" placeholder=""  value="" />
                        </div>
                        <!-- <div class="col-sm-6">
                            <label class="form-label" for="mother_occupation">Mother's Occupation</label>
                            <input class="form-control" id="mother_occupation" type="text" name="mother_occupation" placeholder=""  value="" />
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="mother_mobile_number">Mother's Contact No</label>
                            <input class="form-control" id="mother_mobile_number" type="text" name="mother_mobile_number" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="mother_qualification">Mother's Qualification</label>
                            <input class="form-control" id="mother_qualification" type="text" name="mother_qualification" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="certificate">Certificate</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="certificate" type="file" name="certificate">
                            </div>
                        </div>
                        <!-- <div class="col-sm-6">
                            <label class="form-label" for="guardian_signature">Guardian Signature</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="guardian_signature" type="file" name="guardian_signature">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="student_signature">Student Signature</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="student_signature" type="file" name="student_signature">
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" id="status_active" type="radio" name="status" value="1" checked />
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
    <script>

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
            
            data.password = document.getElementById('password').value;
            if(data.password){
                document.getElementById('password').classList.remove('dangerBoader');
            }else{
                document.getElementById('password').classList.add('dangerBoader');
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
