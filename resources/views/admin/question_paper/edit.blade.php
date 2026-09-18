@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Question Bank</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.question_paper.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    
                    <div class="row mt-2">
                        
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="{{$details->name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="no_of_question">Total No. Of Questions <span class="text-danger">*</span></label>
                            <input class="form-control" id="no_of_question" type="number" name="no_of_question" placeholder=""  value="{{$details->no_of_question}}" onkeyup="changeTotalMarkForExam();changeTotalTimeForExam();"/>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="marks_per_question">Marks Per Question <span class="text-danger">*</span></label>
                            <input class="form-control" id="marks_per_question" type="number" name="marks_per_question" placeholder=""  value="{{$details->marks_per_question}}"  onkeyup="changeTotalMarkForExam();"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="totals_marks_for_exam">Totals Marks for Exam</label>
                            <input class="form-control" id="totals_marks_for_exam" type="number" name="totals_marks_for_exam" placeholder=""  value="{{$details->totals_marks_for_exam}}" readonly/>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="time_per_question">Time Per Question (In minutes) <span class="text-danger">*</span></label>
                            <input class="form-control" id="time_per_question" type="text" name="time_per_question" placeholder=""  value="{{$details->time_per_question}}" onkeyup="changeTotalTimeForExam();"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="total_time_for_exam">Total Time for Exam (In minutes)</label>
                            <input class="form-control" id="total_time_for_exam" type="text" name="total_time_for_exam" placeholder=""  value="{{$details->total_time_for_exam}}" readonly />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="negative_marking_applicable">Negative marking applicable</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="negative_marking_applicable_active" type="radio" name="negative_marking_applicable" @if($details->negative_marking_applicable == 1) checked @endif value="1"  onchange="changeNegativeMarkingApplicable(1)"/>
                                    <label class="form-check-label" for="negative_marking_applicable_active">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="negative_marking_applicable_inactive" type="radio" name="negative_marking_applicable" @if($details->negative_marking_applicable == 0) checked @endif  value="0" onchange="changeNegativeMarkingApplicable(0)" />
                                    <label class="form-check-label" for="negative_marking_applicable_inactive">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" id="negative_marking_per_question_div" style="display:@if($details->negative_marking_applicable == 1) block @else none  @endif;">
                            <label class="form-label" for="negative_marking_per_question">Negative marking per incorrect Answer</label>
                            <input class="form-control" id="negative_marking_per_question" type="text" name="negative_marking_per_question" placeholder=""  value="{{$details->negative_marking_per_question}}" />
                        </div>
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-6">
                            
                        </div>                            
                    </div>
                    <div class="row">

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
        var subject_count = 0;
        var globalData = {
            subject_list : <?php echo json_encode($subject_list);?>,
        }
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

          function addNewSubject(){
            subject_count = subject_count + 1;
            var iHtml = `<div class="row" id="subject_view_`+subject_count+`">
              <div class="col-sm-5">
                <label for="subject_id">Subject</label>
                <select class="form-control subject_id" id="subject_id_`+subject_count+`" name="subject_id[]">
                  <option value="">Select Subject</option>`;
                globalData.subject_list.forEach(function(val){
                  iHtml = iHtml+`<option value="`+val.id+`">`+val.name+`</option>`;
                });
                iHtml = iHtml+`
                </select>
              </div>
              <div class="col-sm-6">
                  <label for="total_no_of_question_`+subject_count+`">No Of questions per subject</label>
                  <input class="form-control" id="total_no_of_question_`+subject_count+`" type="number" name="total_no_of_question[]" value="">
              </div>
              <div class="col-sm-1" style="margin-top: 25px;">
                <img src="{{asset('')}}image/remove-box.png" alt="" onclick="removeSubject(`+subject_count+`);">
              </div>
            </div>`;
            $("#subject_list_view").append(iHtml);
          }

          function removeSubject(index) {
            document.getElementById("subject_view_"+index).remove();
          }

            
    </script>
    <script type="text/javascript">
        
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
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }

            data.exam_date = document.getElementById('exam_date').value;
            if(data.exam_date){
                document.getElementById('exam_date').classList.remove('dangerBoader');
            }else{
                document.getElementById('exam_date').classList.add('dangerBoader');
                successFlag = false;
            }

            data.exam_time = document.getElementById('exam_time').value;
            if(data.exam_time){
                document.getElementById('exam_time').classList.remove('dangerBoader');
            }else{
                document.getElementById('exam_time').classList.add('dangerBoader');
                successFlag = false;
            }

            data.location_id = document.getElementById('location_id').value;
            if(data.location_id){
                document.getElementById('location_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('location_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.no_of_question = document.getElementById('no_of_question').value;
            if(data.no_of_question){
                document.getElementById('no_of_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('no_of_question').classList.add('dangerBoader');
                successFlag = false;
            }

            data.marks_per_question = document.getElementById('marks_per_question').value;
            if(data.marks_per_question){
                document.getElementById('marks_per_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('marks_per_question').classList.add('dangerBoader');
                successFlag = false;
            }

            data.time_per_question = document.getElementById('time_per_question').value;
            if(data.time_per_question){
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

            data.price = document.getElementById('price').value;
            if(data.price){
                document.getElementById('price').classList.remove('dangerBoader');
            }else{
                document.getElementById('price').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection