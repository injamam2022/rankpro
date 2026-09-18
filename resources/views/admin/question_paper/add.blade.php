@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Question Bank</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.question_paper.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="no_of_question">Total No. Of Questions <span class="text-danger">*</span></label>
                            <input class="form-control" id="no_of_question" type="number" name="no_of_question" placeholder=""  value="" onkeyup="changeTotalMarkForExam();changeTotalTimeForExam();"/>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="marks_per_question">Marks Per Question <span class="text-danger">*</span></label>
                            <input class="form-control" id="marks_per_question" type="number" name="marks_per_question" placeholder=""  value=""  onkeyup="changeTotalMarkForExam();"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="totals_marks_for_exam">Totals Marks for Exam</label>
                            <input class="form-control" id="totals_marks_for_exam" type="number" name="totals_marks_for_exam" placeholder=""  value="" readonly/>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="time_per_question">Time Per Question (In minutes) <span class="text-danger">*</span></label>
                            <input class="form-control" id="time_per_question" type="text" name="time_per_question" placeholder=""  value="" onkeyup="changeTotalTimeForExam();"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="total_time_for_exam">Total Time for Exam (In minutes)</label>
                            <input class="form-control" id="total_time_for_exam" type="text" name="total_time_for_exam" placeholder=""  value="" readonly />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="negative_marking_applicable">Negative marking applicable</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="negative_marking_applicable_active" type="radio" name="negative_marking_applicable" checked value="1"  onchange="changeNegativeMarkingApplicable(1)"/>
                                    <label class="form-check-label" for="negative_marking_applicable_active">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="negative_marking_applicable_inactive" type="radio" name="negative_marking_applicable" value="0" onchange="changeNegativeMarkingApplicable(0)" />
                                    <label class="form-check-label" for="negative_marking_applicable_inactive">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" id="negative_marking_per_question_div">
                            <label class="form-label" for="negative_marking_per_question">Negative marking per incorrect Answer</label>
                            <input class="form-control" id="negative_marking_per_question" type="text" name="negative_marking_per_question" placeholder=""  value="" />
                        </div>
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="form-label" for="hard_level">Hard %</label>
                                    <input class="form-control" id="hard_level" type="number" name="hard_level" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label" for="medium_level">Medium %</label>
                                    <input class="form-control" id="medium_level" type="text" name="medium_level" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label" for="easy_level">Easy %</label>
                                    <input class="form-control" id="easy_level" type="text" name="easy_level" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-12">
                                    <small class="text-danger" id="level_message">&nbsp;</small>
                                </div>
                            </div>
                        </div>                            
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5>Question Type</h5>
                                </div>
                                @foreach($question_type_list as $value)
                                    <div class="col-sm-2">
                                        <label class="form-label" for="question_type">{{$value->name}}</label>
                                        <input class="form-control question_type_list" id="question_type_{{$value->id}}" type="number" name="question_type[{{$value->id}}]" placeholder=""  value="" />
                                    </div>
                                @endforeach
                                <div class="col-sm-12">
                                    <small class="text-danger" id="question_type_message">&nbsp;</small>
                                </div>
                            </div>
                        </div>                            
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-12 alert alert-success" style="padding:30px;margin-top:20px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="" style="font-weight: blod;">
                                        Subject List
                                    </h4>
                                </div>
                                <div class="col-sm-6" style="text-align:right;">
                                    <a href="javascript:void(0);">
                                        <img src="{{asset('')}}image/add-box.png" style="" onclick="addNewSubject();">
                                    </a>
                                </div>
                            </div>
                            <div id="subject_list_view">
                                <div class="row mt-2" id="subject_view_0">
                                    <div class="col-sm-2">
                                        <label class="form-label" for="subject_id_0">Subject <span class="text-danger">*</span></label>
                                        <select class="form-control subject_id" id="subject_id_0" name="subject_id[0]" onchange="changeSubject(0);">
                                            <option value="">Select Subject</option>
                                            @foreach($subject_list as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="chapter_id_0">Chapter</label>
                                        <select class="form-control chapter_id" id="chapter_id_0" name="chapter_id[0]" onchange="changeChapter(0);">
                                            <option value="">Select Chapter</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="topic_id_0">Topic</label>
                                        <select class="form-control topic_id" id="topic_id_0" name="topic_id[0]" onchange="changeTopic(0);">
                                            <option value="">Select Topic</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="sub_topic_id_0">Sub Topic</label>
                                        <select class="form-control sub_topic_id" id="sub_topic_id_0" name="sub_topic_id[0]">
                                            <option value="">Select Sub Topic</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="total_no_of_question_0">No Of questions <span class="text-danger">*</span></label>
                                        <input class="form-control total_no_of_question" id="total_no_of_question_0" type="number" name="total_no_of_question[]" placeholder=""  value="" />
                                    </div>
                                    <div class="col-sm-1" style="margin-top: 25px;">
                                        
                                    </div>
                                </div>
                            </div>
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
            var iHtml = `<div class="row mt-2" id="subject_view_`+subject_count+`">
                    <div class="col-sm-2">
                    <label for="subject_id">Subject</label>
                    <select class="form-control subject_id" id="subject_id_`+subject_count+`" name="subject_id[`+subject_count+`]" onchange="changeSubject(`+subject_count+`);">
                    <option value="">Select Subject</option>`;
            globalData.subject_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.id+`">`+val.name+`</option>`;
            });
            iHtml = iHtml+`
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label" for="chapter_id_`+subject_count+`">Chapter</label>
                    <select class="form-control chapter_id" id="chapter_id_`+subject_count+`" name="chapter_id[`+subject_count+`]" onchange="changeChapter(`+subject_count+`);">
                        <option value="">Select Chapter</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label" for="topic_id_`+subject_count+`">Topic</label>
                    <select class="form-control topic_id" id="topic_id_`+subject_count+`" name="topic_id[`+subject_count+`]" onchange="changeTopic(`+subject_count+`);">
                        <option value="">Select Topic</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label" for="sub_topic_id_`+subject_count+`">Sub Topic</label>
                    <select class="form-control sub_topic_id" id="sub_topic_id_`+subject_count+`" name="sub_topic_id[`+subject_count+`]">
                        <option value="">Select Sub Topic</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="total_no_of_question_`+subject_count+`">No Of questions per subject</label>
                    <input class="form-control total_no_of_question" id="total_no_of_question_`+subject_count+`" type="number" name="total_no_of_question[`+subject_count+`]" value="">
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

        function changeSubject(indd) {
            var data = {};
            data.subject_id = document.getElementById('subject_id_'+indd).value;

            $.get("{{route('admin.common.chapter')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Chapter</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('chapter_id_'+indd).innerHTML = role_modal_body;
              });
        }

        function changeChapter(indd){

            var data = {};
            data.subject_id = document.getElementById('subject_id_'+indd).value;
            data.chapter_id = document.getElementById('chapter_id_'+indd).value;

            $.get("{{route('admin.common.topic')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Topic</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('topic_id_'+indd).innerHTML = role_modal_body;
              });
        }

        function changeTopic(indd){

            var data = {};
            data.subject_id = document.getElementById('subject_id_'+indd).value;
            data.chapter_id = document.getElementById('chapter_id_'+indd).value;
            data.topic_id = document.getElementById('topic_id_'+indd).value;

            $.get("{{route('admin.common.sub_topic')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Sub Topic</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('sub_topic_id_'+indd).innerHTML = role_modal_body;
              });
        }

        const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
        const maxSize1 = 1024 * 1024;

        function formValidation(){
            var data = {};
            var successFlag = true;
             document.getElementById('question_type_message').innerHTML = "";
            var question_type_list = document.querySelectorAll('input.question_type_list');
            console.log(question_type_list);

            var question_type_total = 0;
            question_type_list.forEach(input => {
                console.log(input.value);
                if(input.value){
                    question_type_total = question_type_total + parseInt(input.value);   
                }
            });

            if(question_type_total != 100){
                document.getElementById('question_type_message').innerHTML = "Question type total 100% ";
                successFlag = false;
            }

            data.name = document.getElementById('name').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
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

            data.hard_level = document.getElementById('hard_level').value;
            data.medium_level = document.getElementById('medium_level').value;
            data.easy_level = document.getElementById('easy_level').value;
            
            data.hard_level = (data.hard_level)?data.hard_level:0;
            data.medium_level = (data.medium_level)?data.medium_level:0;
            data.easy_level = (data.easy_level)?data.easy_level:0;
            // console.log(data);
            var total_level = parseInt(data.hard_level) + parseInt(data.medium_level) + parseInt(data.easy_level);
            // console.log(total_level);
            document.getElementById('level_message').innerHTML = "&nbsp;";
            if(total_level != 100){
                document.getElementById('level_message').innerHTML = "Difficulty level total 100%";
                successFlag = false;
            }
            
            var inputs = document.querySelectorAll('input.total_no_of_question');
            // console.log(inputs);
            inputs.forEach(input => {
                if(input.value){
                    input.classList.remove('dangerBoader');
                }else{
                    input.classList.add('dangerBoader');
                    successFlag = false;
                }
              // console.log(input.id); // Get each textbox's value
            });
            
            var subject_id = document.querySelectorAll('select.subject_id');
            console.log(subject_id);
            subject_id.forEach(input => {
                console.log(input.value);
                if(input.value){
                    input.classList.remove('dangerBoader');
                }else{
                    input.classList.add('dangerBoader');
                    successFlag = false;
                }
            });
            
            return successFlag;
        }
    </script>
@endsection