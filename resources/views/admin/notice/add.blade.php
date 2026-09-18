@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Notice</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.notice.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">

                        <div class="col-sm-6">
                            <label class="form-label" for="class_id">Class</label>
                            <select class="form-control" id="class_id" name="class_id">
                                <option value="0">All</option>
                                <option value="11">11th</option>
                                <option value="12">12th</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_type">Mode</label>
                            <select class="form-control" id="exam_type" name="exam_type">
                                <option value="0">All</option>
                                <option value="1">Online</option>
                                <option value="2">Offline</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_id">Exam </label>
                            <select class="form-control" id="exam_id" name="exam_id">
                                <option value="">Select</option>
                                @foreach($exam_list as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="test_series">Test series</label>
                            <select class="form-control" id="test_series" name="test_series">
                                <option value="0">All</option>
                                <option value="1">RNS</option>
                                <option value="2">RPS</option>
                                <option value="3">SNT</option>
                            </select>
                        </div>

                        <!-- 
                        <div class="col-sm-6">
                            <label class="form-label" for="subject_id">Subject </label>
                            <select class="form-control" id="subject_id" name="subject_id">
                                <option value="">Select</option>
                                @foreach($subject_list as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="user_id">Student </label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">Select</option>
                                @foreach($student_list as $value)
                                    <option value="{{$value->id}}">{{$value->first_name}} {{$value->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label" for="is_urgent">Is Urgent</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="is_urgent_active" type="radio" name="is_urgent"  value="1" />
                                    <label class="form-check-label" for="is_urgent_active">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="is_urgent_inactive" type="radio" name="is_urgent" value="0" checked />
                                    <label class="form-check-label" for="is_urgent_inactive">No</label>
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
        $('#user_id').select2({

        });
        function formValidation(){
            var data = {};
            var successFlag = true;

            data.name = document.getElementById('name').value;
            data.description = document.getElementById('description').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }
            if(data.description){
                document.getElementById('description').classList.remove('dangerBoader');
            }else{
                document.getElementById('description').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection