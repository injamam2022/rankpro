@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Offline Exam</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.offline_exam.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
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
                            <div class="row">
                                <div class="col-sm-10">
                                    <label class="form-label" for="exam_logo">Exam Logo (Image Size: W- 161 px, H- 260 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                                    <input class="form-control" id="exam_logo" type="file" name="exam_logo" placeholder=""  value="" />
                                </div>
                                <div class="col-sm-2">
                                    @if($details->landing_icon)
                                        <img src="{{asset('')}}uploads/exam/thumbnail/{{$details->exam_logo}}" style="width: 50px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-10">
                                    <label class="form-label" for="landing_icon">Landing Exam Logo (Image Size: W- 367 px, H- 206 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
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
                        <div class="col-sm-3">
                            <label class="form-label" for="exam_date">Exam Date <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_date" type="date" name="exam_date" placeholder=""  value="{{$details->exam_date}}" />
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="exam_time">Exam Time <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_time" type="time" name="exam_time" placeholder=""  value="{{$details->exam_time}}" />
                        </div>
                        
                        <div class="col-sm-6" >
                            <label class="form-label" for="location_id">Location <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_id" name="location_id">
                                <option value="">Select Location</option>
                                @foreach($location_list as $val)
                                    <option value="{{$val->id}}"  @if($details->location_id == $val->id) selected @endif>{{$val->location_name}}</option>
                                @endforeach
                            </select>
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
                            <label class="form-label" for="question_type">Question Type </label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="question_type_y" type="radio" name="question_type" @if($details->question_type == 1) checked @endif value="1"  onchange="changeQuestionType(1)"/>
                                    <label class="form-check-label" for="question_type_y">Question Bank</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="question_type_n" type="radio" name="question_type" value="2" @if($details->question_type == 2) checked @endif onchange="changeQuestionType(2)"/>
                                    <label class="form-check-label" for="question_type_n">Upload Question</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="question_bank_view" style="display:@if($details->question_type == 1) block @else none  @endif;">
                        <div class="col-sm-6">
                            <label class="form-label" for="question_paper_id">Question Paper </label>
                            <select class="form-control" id="question_paper_id" name="question_paper_id">
                                <option value="">Select</option>
                                @foreach($question_paper_list as $value)
                                    <option value="{{$value->id}}" @if($details->question_paper_id == $value->id) selected @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="upload_question_view" style="display:@if($details->question_type == 2) block @else none  @endif;">
                        <div class="row">
                            
                            <div class="col-sm-4">
                                <label class="form-label" for="no_of_question">Total No. Of Questions <span class="text-danger">*</span></label>
                                <input class="form-control" id="no_of_question" type="number" name="no_of_question" placeholder=""  value="{{$details->no_of_question}}" />
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="marks_per_question">Marks Per Question <span class="text-danger">*</span></label>
                                <input class="form-control" id="marks_per_question" type="number" name="marks_per_question" placeholder=""  value="{{$details->marks_per_question}}"  />
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="time_per_question">Time Per Question (In minutes) <span class="text-danger">*</span></label>
                                <input class="form-control" id="time_per_question" type="text" name="time_per_question" placeholder=""  value="{{$details->time_per_question}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="form-label" for="negative_marking_applicable">Negative marking applicable</label>
                                <div class="form-control" style="display:flex;">
                                    <div class="form-check">
                                        <input class="form-check-input" id="negative_marking_applicable_active" type="radio" name="negative_marking_applicable"  @if($details->negative_marking_applicable == 1) checked @endif value="1"  onchange="changeNegativeMarkingApplicable(1)"/>
                                        <label class="form-check-label" for="negative_marking_applicable_active">Yes</label>
                                    </div>
                                    <div class="form-check" style="margin-left: 15px;">
                                        <input class="form-check-input" id="negative_marking_applicable_inactive" type="radio" name="negative_marking_applicable"  @if($details->negative_marking_applicable == 0) checked @endif value="0" onchange="changeNegativeMarkingApplicable(0)" />
                                        <label class="form-check-label" for="negative_marking_applicable_inactive">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" id="negative_marking_per_question_div" style="display:@if($details->negative_marking_applicable == 1) block @else none  @endif;">
                                <label class="form-label" for="negative_marking_per_question">Negative marking per incorrect Answer</label>
                                <input class="form-control" id="negative_marking_per_question" type="text" name="negative_marking_per_question" placeholder=""  value="{{$details->negative_marking_per_question}}" />
                            </div>
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
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="result_declaration">OMR Declaration <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="result_declaration" name="result_declaration">{{$details->result_declaration}}</textarea>
                        </div>

                        <div class="col-sm-6">
                            
                        </div>                            
                    </div>
                    <div class="row">

                        
                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="language_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Language</h4>
                                <a href="javascript:void(0);" onclick="addNewLanguage()"><img src="{{ asset('image/add-box.png') }}" alt="Add Language"></a>
                            </div>

                            @if(count($exam_language_list))
                                @foreach($exam_language_list as $key =>$val)
                                    <div class="row mb-3" id="language_view_{{$key}}">
                                        <div class="col-11">
                                            <select class="form-control" name="language_name[]" id="language_name_{{$key}}">
                                                <option value="">Select</option>
                                                @foreach($language_list as $value)
                                                    <option value="{{$value->id}}" @if($value->id == $val->language_id) selected @endif>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1 d-flex align-items-center">
                                            <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLanguage({{$key}})">
                                        </div>
                                    </div>
                                @endforeach
                            @else
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
                            @endif
                        </div>

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="location_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Location</h4>
                                <a href="javascript:void(0);" onclick="addNewLocation()"><img src="{{ asset('image/add-box.png') }}" alt="Add Location"></a>
                            </div>
                            @if(count($exam_location_list))
                                @foreach($exam_location_list as $key =>$val)
                                    <div class="row mb-3" id="location_view_{{$key}}">
                                        <div class="col-11">
                                            <select class="form-control" name="location_name[]" id="location_name_{{$key}}">
                                                <option value="">Select</option>
                                                @foreach($location_list as $value)
                                                    <option value="{{$value->id}}" @if($value->id == $val->location_id) selected @endif>{{$value->location_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1 d-flex align-items-center">
                                            <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLocation({{$key}})">
                                        </div>
                                    </div>
                                @endforeach
                            @else
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
                            @endif
                           
                        </div>

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="date_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Starting Date</h4>
                                <a href="javascript:void(0);" onclick="addNewDate()"><img src="{{ asset('image/add-box.png') }}" alt="Add Location"></a>
                            </div>
                            @if(count($exam_date_list))
                                @foreach($exam_date_list as $key =>$val)
                                    <div class="row mb-3" id="date_view_{{$key}}">
                                        <div class="col-11">
                                            <input type="text" class="form-control" name="date_name[]" placeholder="Enter starting date" value="{{$val->date_name}}" id="date_name_{{$key}}">
                                        </div>
                                        <div class="col-1 d-flex align-items-center">
                                            <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeDate({{$key}})">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row mb-3" id="date_view_0">
                                    <div class="col-11">
                                        <input type="text" class="form-control" name="date_name[]" placeholder="Enter starting date" value="" id="date_name_0">
                                    </div>
                                    <div class="col-1 d-flex align-items-center">
                                        <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeDate(0)">
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Is In Footer</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="is_in_footer" type="radio" name="is_in_footer" @if($details->is_in_footer == 1) checked  @endif value="1" />
                                    <label class="form-check-label" for="is_in_footer">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="is_in_footer" type="radio" name="is_in_footer" value="0" @if($details->is_in_footer == 0) checked  @endif />
                                    <label class="form-check-label" for="is_in_footer">No</label>
                                </div>
                            </div>
                        </div>
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

            let language_count = <?php echo count($exam_language_list);?>;

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

            let location_count = <?php echo count($exam_location_list);?>;

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

            let date_count = <?php echo count($exam_date_list);?>;

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

            // data.exam_instructions = document.getElementById('exam_instructions').value;
            // if(data.exam_instructions){
            //     document.getElementById('exam_instructions').classList.remove('dangerBoader');
            // }else{
            //     document.getElementById('exam_instructions').classList.add('dangerBoader');
            //     successFlag = false;
            // }

            // data.description = document.getElementById('description').value;
            // if(data.description){
            //     document.getElementById('description').classList.remove('dangerBoader');
            // }else{
            //     document.getElementById('description').classList.add('dangerBoader');
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