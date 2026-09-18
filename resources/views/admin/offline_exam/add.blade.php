@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Offline Exam</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.offline_exam.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_code">Exam Code <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_code" type="number" name="exam_code" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Exam Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_logo">Exam Icon (Image Size: W- 161 px, H- 260 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_logo" type="file" name="exam_logo" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="landing_icon">Landing Exam Icon (Image Size: W- 258 px, H- 340 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <input class="form-control" id="landing_icon" type="file" name="landing_icon" placeholder=""  value="" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-3">
                            <label class="form-label" for="exam_date">Exam Date <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_date" type="date" name="exam_date" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="exam_time">Exam Time <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_time" type="time" name="exam_time" placeholder=""  value="" />
                        </div>

                        <div class="col-sm-6" >
                            <label class="form-label" for="location_id">Location <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_id" name="location_id">
                                <option value="">Select Location</option>
                                @foreach($location_list as $val)
                                    <option value="{{$val->id}}">{{$val->location_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-6">
                            <label class="form-label" for="price">Annual Fee <span class="text-danger">*</span></label>
                            <input class="form-control" id="price" type="text" name="price" placeholder="" value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="dis_price">Annual Fee Discount</label>
                            <input class="form-control" id="dis_price" type="text" name="dis_price" placeholder="" value="" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="tax">Tax</label>
                            <input class="form-control" id="tax" type="text" name="tax" placeholder="" value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="question_type">Question Type </label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="question_type_y" type="radio" name="question_type" checked value="1"  onchange="changeQuestionType(1)"/>
                                    <label class="form-check-label" for="question_type_y">Question Bank</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="question_type_n" type="radio" name="question_type" value="2"  onchange="changeQuestionType(2)"/>
                                    <label class="form-check-label" for="question_type_n">Upload Question</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="question_bank_view" style="display: block;">
                        <div class="col-sm-6">
                            <label class="form-label" for="question_paper_id">Question Paper </label>
                            <select class="form-control" id="question_paper_id" name="question_paper_id">
                                <option value="">Select</option>
                                @foreach($question_paper_list as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="upload_question_view" style="display:none;">
                        <div class="row">
                            
                            <div class="col-sm-4">
                                <label class="form-label" for="no_of_question">Total No. Of Questions <span class="text-danger">*</span></label>
                                <input class="form-control" id="no_of_question" type="number" name="no_of_question" placeholder=""  value="" />
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="marks_per_question">Marks Per Question <span class="text-danger">*</span></label>
                                <input class="form-control" id="marks_per_question" type="number" name="marks_per_question" placeholder=""  value=""  />
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="time_per_question">Time Per Question (In minutes) <span class="text-danger">*</span></label>
                                <input class="form-control" id="time_per_question" type="text" name="time_per_question" placeholder=""  value=""/>
                            </div>
                        </div>
                        <div class="row">
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
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_instructions">Instructions</label>
                            <textarea class="form-control" id="exam_instructions" name="exam_instructions"></textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="result_title">OMR Title <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="result_title" name="result_title"></textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="result_description">OMR Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="result_description" name="result_description"></textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="result_declaration">OMR Declaration <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="result_declaration" name="result_declaration"></textarea>
                        </div>

                                                    
                    </div>
                    <div class="row">

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="language_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Language <span class="text-danger"></span></h4>
                                <a href="javascript:void(0);" onclick="addNewLanguage()"><img src="{{ asset('image/add-box.png') }}" alt="Add Language"></a>
                            </div>

                            <div class="row mb-3" id="language_view_0">
                                <div class="col-11">
                                    <select class="form-control" name="language_name[]" id="language_name_0">
                                        <option value="">Select</option>
                                        @foreach($language_list as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1 d-flex align-items-center">
                                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLanguage(0)">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="location_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Location <span class="text-danger"></span></h4>
                                <a href="javascript:void(0);" onclick="addNewLocation()"><img src="{{ asset('image/add-box.png') }}" alt="Add Location"></a>
                            </div>

                            <div class="row mb-3" id="location_view_0">
                                <div class="col-11">
                                    <select class="form-control" name="location_name[]" id="location_name_0">
                                        <option value="">Select</option>
                                        @foreach($location_list as $value)
                                            <option value="{{$value->id}}">{{$value->location_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1 d-flex align-items-center">
                                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLocation(0)">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="date_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Starting Date <span class="text-danger"></span></h4>
                                <a href="javascript:void(0);" onclick="addNewDate()"><img src="{{ asset('image/add-box.png') }}" alt="Add Location"></a>
                            </div>

                            <div class="row mb-3" id="date_view_0">
                                <div class="col-11">
                                    <input type="text" class="form-control" name="date_name[]" placeholder="Enter starting date" value="" id="date_name_0">
                                </div>
                                <div class="col-1 d-flex align-items-center">
                                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeDate(0)">
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-sm-12 alert alert-success" id="subject_list_view" style="padding:30px;margin-top:20px;">
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
                            <div class="row" id="subject_view_0">
                                <div class="col-sm-5">
                                    <label class="form-label" for="subject_id_0">Subject</label>
                                    <select class="form-control" id="subject_id_0" name="subject_id[]">
                                        <option>Select Subject</option>
                                        @foreach($subject_list as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="total_no_of_question_0">No Of questions per subject</label>
                                    <input class="form-control" id="total_no_of_question_0" type="number" name="total_no_of_question[]" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-1" style="margin-top: 25px;">
                                    
                                </div>
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Is In Footer</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="is_in_footer" type="radio" name="is_in_footer" checked value="1" />
                                    <label class="form-check-label" for="is_in_footer">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="is_in_footer" type="radio" name="is_in_footer" value="0" />
                                    <label class="form-check-label" for="is_in_footer">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="is_trending">Is Trending</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="is_trending" type="radio" name="is_trending" checked value="1" />
                                    <label class="form-check-label" for="is_trending">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="is_trending" type="radio" name="is_trending" value="0" />
                                    <label class="form-check-label" for="is_trending">No</label>
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
        var subject_count = 0;
        var globalData = {
            subject_list : <?php echo json_encode($subject_list);?>,
        }

        function changeQuestionType(type){
            if(type == 1){
                document.getElementById('question_bank_view').style.display = "block";
                document.getElementById('upload_question_view').style.display = "none";
            }else{
                document.getElementById('question_bank_view').style.display = "none";
                document.getElementById('upload_question_view').style.display = "block";
            }            
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

        let language_count = 1;

        function addNewLanguage() {
            const html = `
                <div class="row mb-3" id="language_view_${language_count}">
                    <div class="col-11">
                        <select class="form-control" name="language_name[]" id="language_name_${language_count}">
                            <option value="">Select</option>
                            @foreach($language_list as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1 d-flex align-items-center">
                        <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLanguage(${language_count})">
                    </div>
                </div>
            `;
            document.getElementById('language_list_view').insertAdjacentHTML('beforeend', html);
            language_count++;
        }

        function removeLanguage(index) {
            const elem = document.getElementById(`language_view_${index}`);
            if (elem) elem.remove();
        }

        let location_count = 1;

        function addNewLocation() {
            const html = `
                <div class="row mb-3" id="location_view_${location_count}">
                    <div class="col-11">
                        <select class="form-control" name="location_name[]" id="location_name_${location_count}">
                            <option value="">Select</option>
                            @foreach($location_list as $value)
                                <option value="{{$value->id}}">{{$value->location_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1 d-flex align-items-center">
                        <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLocation(${location_count})">
                    </div>
                </div>
            `;
            document.getElementById('location_list_view').insertAdjacentHTML('beforeend', html);
            location_count++;
        }

        function removeLocation(index) {
            const elem = document.getElementById(`location_view_${index}`);
            if (elem) elem.remove();
        }

        let date_count = 1;

        function addNewDate() {
            const html = `
                <div class="row mb-3" id="date_view_${date_count}">
                    <div class="col-11">
                        <input type="text" class="form-control" name="date_name[]" placeholder="Enter starting date" id="date_name_${date_count}">
                    </div>
                    <div class="col-1 d-flex align-items-center">
                        <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeDate(${date_count})">
                    </div>
                </div>
            `;
            document.getElementById('date_list_view').insertAdjacentHTML('beforeend', html);
            date_count++;
        }

        function removeDate(index) {
            const elem = document.getElementById(`date_view_${index}`);
            if (elem) elem.remove();
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

            var exam_logo = document.getElementById("exam_logo");
            var file = exam_logo.files[0];
            if(file){
                if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
                  document.getElementById('exam_logo').classList.add('dangerBoader');
                  successFlag = false;
                }else{
                  document.getElementById('exam_logo').classList.remove('dangerBoader');
                }
            }else{
                document.getElementById('exam_logo').classList.add('dangerBoader');
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
            
            // data.dis_price = document.getElementById('dis_price').value;
            // if(data.exam_code){
            //     document.getElementById('dis_price').classList.remove('dangerBoader');
            // }else{
            //     document.getElementById('dis_price').classList.add('dangerBoader');
            //     successFlag = false;
            // }
            
            // data.tax = document.getElementById('tax').value;
            // if(data.exam_code){
            //     document.getElementById('tax').classList.remove('dangerBoader');
            // }else{
            //     document.getElementById('tax').classList.add('dangerBoader');
            //     successFlag = false;
            // }
            
            // data.question_paper_id = document.getElementById('question_paper_id').value;
            // if(data.question_paper_id){
            //     document.getElementById('question_paper_id').classList.remove('dangerBoader');
            // }else{
            //     document.getElementById('question_paper_id').classList.add('dangerBoader');
            //     successFlag = false;
            // }
            
            
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