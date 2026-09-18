@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Online Exam</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.online_exam.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_code">Exam Code <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_code" type="number" name="exam_code" placeholder=""  value="{{$details->exam_code}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Exam Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="{{$details->name}}" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_logo">Exam Icon (Image Size: W- 161 px, H- 260 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <input class="form-control" id="exam_logo" type="file" name="exam_logo" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-2">
                                    @if($details->exam_logo)
                                        <img src="{{asset('')}}uploads/exam/thumbnail/{{$details->exam_logo}}" style="width: 50px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="landing_icon">Landing Exam Icon (Image Size: W- 258 px, H- 340 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <input class="form-control" id="landing_icon" type="file" name="landing_icon" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-2">
                                    @if($details->landing_icon)
                                        <img src="{{asset('')}}uploads/exam/thumbnail/{{$details->landing_icon}}" style="width: 50px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_date">Exam Date <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_date" type="date" name="exam_date" placeholder=""  value="{{$details->exam_date}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_time">Exam Time <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_time" type="time" name="exam_time" placeholder=""  value="{{$details->exam_time}}" />
                        </div>
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-6">
                            <label class="form-label" for="price">Annual Fee <span class="text-danger">*</span></label>
                            <input class="form-control" id="price" type="text" name="price" placeholder="" value="{{$details->price}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="dis_price">Annual Fee Discount</label>
                            <input class="form-control" id="dis_price" type="text" name="dis_price" placeholder="" value="{{$details->dis_price}}" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="tax">Tax</label>
                            <input class="form-control" id="tax" type="text" name="tax" placeholder="" value="{{$details->tax}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="question_paper_id">Question Paper <span class="text-danger">*</span> </label>
                            <select class="form-control" id="question_paper_id" name="question_paper_id">
                                <option value="">Select</option>
                                @foreach($question_paper_list as $value)
                                    <option value="{{$value->id}}" @if($details->question_paper_id == $value->id) selected  @endif >{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_instructions">Instructions</label>
                            <textarea class="form-control" id="exam_instructions" name="exam_instructions">{{$details->exam_instructions}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{$details->description}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="result_title">OMR Title <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="result_title" name="result_title">{{$details->result_title}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="result_description">OMR Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="result_description" name="result_description">{{$details->result_description}}</textarea>
                        </div>
                    </div>
                    
                        <div class="col-sm-6">
                            <label class="form-label" for="result_declaration">OMR Declaration <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="result_declaration" name="result_declaration">{{$details->result_declaration}}</textarea>
                        </div>
                    </div>



                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Is Trending</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="is_trending" type="radio" name="is_trending" @if($details->is_trending == 1) checked  @endif value="1" />
                                    <label class="form-check-label" for="is_trending">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="is_trending" type="radio" name="is_trending" value="0" @if($details->is_trending == 0) checked  @endif />
                                    <label class="form-check-label" for="is_trending">No</label>
                                </div>
                            </div>
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
         function changeTotalMarkForExam(){
            var no_of_question = document.getElementById('no_of_question').value;
            var marks_per_question = document.getElementById('marks_per_question').value;
            if(no_of_question && marks_per_question){
              document.getElementById('totals_marks_for_exam').value = no_of_question * marks_per_question;
            }else{
              document.getElementById('totals_marks_for_exam').value = "";
            }
          }

          function changeTotalTimeForExam(){
            var no_of_question = document.getElementById('no_of_question').value;
            var time_per_question = document.getElementById('time_per_question').value;
            if(no_of_question && time_per_question){
              document.getElementById('total_time_for_exam').value = no_of_question * time_per_question;
            }else{
              document.getElementById('total_time_for_exam').value = "";
            }
          }

          function changeNegativeMarkingApplicable(type){
            if(type){
              document.getElementById('negative_marking_per_question_div').style.display = "block";
            }else{
              document.getElementById('negative_marking_per_question_div').style.display = "none";
            }
          }

        function formValidation(){
            var data = {};
            var successFlag = true;

            data.exam_code = document.getElementById('exam_code').value;
            if(data.exam_code){
                document.getElementById('exam_code').classList.remove('dangerBoader');
            }else{
                document.getElementById('exam_code').classList.add('dangerBoader');
                successFlag = false;
            }

            data.name = document.getElementById('name').value;
            if(data.exam_code){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }

            data.exam_date = document.getElementById('exam_date').value;
            if(data.exam_code){
                document.getElementById('exam_date').classList.remove('dangerBoader');
            }else{
                document.getElementById('exam_date').classList.add('dangerBoader');
                successFlag = false;
            }

            data.exam_time = document.getElementById('exam_time').value;
            if(data.exam_code){
                document.getElementById('exam_time').classList.remove('dangerBoader');
            }else{
                document.getElementById('exam_time').classList.add('dangerBoader');
                successFlag = false;
            }

            data.price = document.getElementById('price').value;
            if(data.exam_code){
                document.getElementById('price').classList.remove('dangerBoader');
            }else{
                document.getElementById('price').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.no_of_question = document.getElementById('no_of_question').value;
            if(data.exam_code){
                document.getElementById('no_of_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('no_of_question').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.marks_per_question = document.getElementById('marks_per_question').value;
            if(data.exam_code){
                document.getElementById('marks_per_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('marks_per_question').classList.add('dangerBoader');
                successFlag = false;
            }
            
            // data.totals_marks_for_exam = document.getElementById('totals_marks_for_exam').value;
            data.time_per_question = document.getElementById('time_per_question').value;
            if(data.exam_code){
                document.getElementById('time_per_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('time_per_question').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.exam_instructions = document.getElementById('exam_instructions').value;
            if(data.exam_instructions){
                document.getElementById('exam_instructions').classList.remove('dangerBoader');
            }else{
                document.getElementById('exam_instructions').classList.add('dangerBoader');
                successFlag = false;
            }

            data.description = document.getElementById('description').value;
            if(data.description){
                document.getElementById('description').classList.remove('dangerBoader');
            }else{
                document.getElementById('description').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.result_title = document.getElementById('result_title').value;
            if(data.result_title){
                document.getElementById('result_title').classList.remove('dangerBoader');
            }else{
                document.getElementById('result_title').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.result_description = document.getElementById('result_description').value;
            if(data.result_description){
                document.getElementById('result_description').classList.remove('dangerBoader');
            }else{
                document.getElementById('result_description').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.result_declaration = document.getElementById('result_declaration').value;
            if(data.result_declaration){
                document.getElementById('result_declaration').classList.remove('dangerBoader');
            }else{
                document.getElementById('result_declaration').classList.add('dangerBoader');
                successFlag = false;
            }
            
            return successFlag;
        }
    </script>
@endsection