@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Ranker</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.ranker.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="profile_icon">Profile Icon (Image Size: W- 193 px, H- 193 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <input class="form-control" id="profile_icon" type="file" name="profile_icon" placeholder=""  value="" accept="image/png, image/jpeg, image/jpg"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="icon">Home Icon (Image Size: W- 564 px, H- 380 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <input class="form-control" id="icon" type="file" name="icon" placeholder=""  value="" accept="image/png, image/jpeg, image/jpg"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="landing_icon">Landing Icon (Image Size: W- 564 px, H- 380 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <input class="form-control" id="landing_icon" type="file" name="landing_icon" placeholder=""  value="" accept="image/png, image/jpeg, image/jpg"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="email">Email ID <span class="text-danger">*</span></label>
                            <input class="form-control" id="email" type="text" name="email" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <input class="form-control" id="password" type="text" name="password" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="phone_number">Phone Number <span class="text-danger">*</span></label>
                            <input class="form-control" id="phone_number" type="text" name="phone_number" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="about">About <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="about" name="about" placeholder=""></textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" placeholder=""></textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="mentorship">Mentorship <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="mentorship" name="mentorship" placeholder=""></textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="test_series">Test Series <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="test_series" name="test_series" placeholder=""></textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="location">Location <span class="text-danger">*</span></label>
                            <input class="form-control" id="location" type="text" name="location" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="score">Score <span class="text-danger">*</span></label>
                            <input class="form-control" id="score" type="text" name="score" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="college">College <span class="text-danger">*</span></label>
                            <input class="form-control" id="college" type="text" name="college" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="year">Year <span class="text-danger">*</span></label>
                            <input class="form-control" id="year" type="text" name="year" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="air">AIR <span class="text-danger">*</span></label>
                            <input class="form-control" id="air" type="text" name="air" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="video_link">Video Link <span class="text-danger">*</span></label>
                            <input class="form-control" id="video_link" type="text" name="video_link" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="subject_id">Subjects <span class="text-danger">*</span></label>
                            <select class="form-control" name="subject_id" id="subject_id">
                                <option value="">Select</option>
                                @foreach ($subject_list as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="language_id">Language <span class="text-danger">*</span></label>
                            <select class="form-control" name="language_id" id="language_id">
                                <option value="">Select</option>
                                @foreach ($language_list as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="course_id">Course <span class="text-danger">*</span></label>
                            <select class="form-control" name="course_id" id="course_id">
                                <option value="">Select</option>
                                @foreach ($course_list as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" >&nbsp;</label>
                            <div class="form-control">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="1" name="is_in_listing" id="is_in_listing">
                                  <label class="form-check-label" for="is_in_listing">
                                    Is In Banner
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" checked value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
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

        const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
        const maxSize1 = 1024 * 1024;
        
        function formValidation(){
            var data = {};
            var successFlag = true;

            data.name = document.getElementById('name').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }

            var profile_icon = document.getElementById("profile_icon");
            var file = profile_icon.files[0];
            if(file){
                if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
                  document.getElementById('profile_icon').classList.add('dangerBoader');
                  successFlag = false;
                }else{
                  document.getElementById('profile_icon').classList.remove('dangerBoader');
                }
            }else{
                document.getElementById('profile_icon').classList.add('dangerBoader');
                successFlag = false;
            }

            var icon = document.getElementById("icon");
            var file = icon.files[0];
            if(file){
                if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
                  document.getElementById('icon').classList.add('dangerBoader');
                  successFlag = false;
                }else{
                  document.getElementById('icon').classList.remove('dangerBoader');
                }
            }else{
                document.getElementById('icon').classList.add('dangerBoader');
                successFlag = false;
            }

            var landing_icon = document.getElementById("landing_icon");
            var file = landing_icon.files[0];
            if(file){
                if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
                  document.getElementById('landing_icon').classList.add('dangerBoader');
                  successFlag = false;
                }else{
                  document.getElementById('landing_icon').classList.remove('dangerBoader');
                }
            }else{
                document.getElementById('landing_icon').classList.add('dangerBoader');
                successFlag = false;
            }

            data.email = document.getElementById('email').value;
            if(data.email){
                document.getElementById('email').classList.remove('dangerBoader');
            }else{
                document.getElementById('email').classList.add('dangerBoader');
                successFlag = false;
            }

            data.password = document.getElementById('password').value;
            if(data.password){
                document.getElementById('password').classList.remove('dangerBoader');
            }else{
                document.getElementById('password').classList.add('dangerBoader');
                successFlag = false;
            }

            data.phone_number = document.getElementById('phone_number').value;
            if(data.phone_number){
                document.getElementById('phone_number').classList.remove('dangerBoader');
            }else{
                document.getElementById('phone_number').classList.add('dangerBoader');
                successFlag = false;
            }

            data.about = document.getElementById('about').value;
            if(data.about){
                document.getElementById('about').classList.remove('dangerBoader');
            }else{
                document.getElementById('about').classList.add('dangerBoader');
                successFlag = false;
            }

            data.description = document.getElementById('description').value;
            if(data.description){
                document.getElementById('description').classList.remove('dangerBoader');
            }else{
                document.getElementById('description').classList.add('dangerBoader');
                successFlag = false;
            }

            data.mentorship = document.getElementById('mentorship').value;
            if(data.mentorship){
                document.getElementById('mentorship').classList.remove('dangerBoader');
            }else{
                document.getElementById('mentorship').classList.add('dangerBoader');
                successFlag = false;
            }

            data.test_series = document.getElementById('test_series').value;
            if(data.test_series){
                document.getElementById('test_series').classList.remove('dangerBoader');
            }else{
                document.getElementById('test_series').classList.add('dangerBoader');
                successFlag = false;
            }

            data.location = document.getElementById('location').value;
            if(data.location){
                document.getElementById('location').classList.remove('dangerBoader');
            }else{
                document.getElementById('location').classList.add('dangerBoader');
                successFlag = false;
            }

            data.score = document.getElementById('score').value;
            if(data.score){
                document.getElementById('score').classList.remove('dangerBoader');
            }else{
                document.getElementById('score').classList.add('dangerBoader');
                successFlag = false;
            }

            data.college = document.getElementById('college').value;
            if(data.college){
                document.getElementById('college').classList.remove('dangerBoader');
            }else{
                document.getElementById('college').classList.add('dangerBoader');
                successFlag = false;
            }

            data.year = document.getElementById('year').value;
            if(data.year){
                document.getElementById('year').classList.remove('dangerBoader');
            }else{
                document.getElementById('year').classList.add('dangerBoader');
                successFlag = false;
            }

            data.air = document.getElementById('air').value;
            if(data.air){
                document.getElementById('air').classList.remove('dangerBoader');
            }else{
                document.getElementById('air').classList.add('dangerBoader');
                successFlag = false;
            }

            data.video_link = document.getElementById('video_link').value;
            if(data.video_link){
                document.getElementById('video_link').classList.remove('dangerBoader');
            }else{
                document.getElementById('video_link').classList.add('dangerBoader');
                successFlag = false;
            }

            data.subject_id = document.getElementById('subject_id').value;
            if(data.subject_id){
                document.getElementById('subject_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('subject_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.language_id = document.getElementById('language_id').value;
            if(data.language_id){
                document.getElementById('language_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('language_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.course_id = document.getElementById('course_id').value;
            if(data.course_id){
                document.getElementById('course_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('course_id').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>

@endsection
