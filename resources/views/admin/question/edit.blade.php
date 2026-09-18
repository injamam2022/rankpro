@extends('layouts.backend')


@section('css_after')
    <style type="text/css">
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Question</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.question.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="subject_id">Subject <span class="text-danger">*</span></label>
                            <select class="form-control" id="subject_id" name="subject_id" onchange="changeSubject();">
                                <option>Select Subject</option>
                                @foreach($subject_list as $key => $value)
                                    <option value="{{$value->id}}" @if($details->subject_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="chapter_id">Chapter <span class="text-danger">*</span></label>
                            <select class="form-control" id="chapter_id" name="chapter_id" onchange="changeChapter();">
                                <option>Select Chapter</option>
                                @foreach($chapter_list as $key => $value)
                                    <option value="{{$value->id}}" @if($details->chapter_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="source_id">Source <span class="text-danger">*</span> </label>
                            <select class="form-control" id="source_id" name="source_id">
                                <option>Select Source</option>
                                @foreach($source_list as $key => $value)
                                    <option value="{{$value->id}}" @if($details->source_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="topic_id">Topic  <span class="text-danger">*</span></label>
                            <select class="form-control" id="topic_id" name="topic_id" onchange="changeTopic();">
                                <option value="">Select Topic</option>
                                @foreach($topic_list as $key => $value)
                                    <option value="{{$value->id}}" @if($details->topic_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="sub_topic_id">Sub Topic  <span class="text-danger">*</span></label>
                            <select class="form-control" id="sub_topic_id" name="sub_topic_id">
                                <option value="">Select Sub Topic</option>
                                @foreach($sub_topic_list as $key => $value)
                                    <option value="{{$value->id}}" @if($details->sub_topic_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label" for="difficulty_level">Difficulty Level  <span class="text-danger">*</span></label>
                            <select class="form-control" id="difficulty_level" name="difficulty_level">
                                <option value="">Select Difficulty Level</option>
                                <option value="1" @if($details->difficulty_level == 1) selected  @endif>Easy</option>
                                <option value="2" @if($details->difficulty_level == 2) selected  @endif>Medium</option>
                                <option value="3" @if($details->difficulty_level == 3) selected  @endif>Hard</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="question_type_id">Question Type  <span class="text-danger">*</span></label>
                            <select class="form-control" id="question_type_id" name="question_type_id">
                                <option value="">Select Question Type</option>
                                @foreach($question_type_list as $value)
                                    <option value="{{$value->id}}" @if($details->question_type_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>



                        @foreach($language_list as $language)
                            <div class="col-sm-12 alert alert-success" style="margin:20px;padding:30px;">
                                <h4 class="" style="font-weight: blod;">
                                    Language : {{$language->name}}
                                </h4>
                                <div class="row">
                                    <div class="col-sm-12" id="question_text_div_{{$language->id}}">
                                        <label class="form-label" for="question_text_{{$language->id}}">Question Text</label>
                                        <textarea class="form-control" id="question_text_{{$language->id}}" name="question_text[{{$language->id}}]" rows="4">{{$language->question_text}}</textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label" for="question_image_{{$language->id}}">Question Image</label>
                                        <input class="form-control" id="question_image_{{$language->id}}" type="file" name="question_image[{{$language->id}}]" placeholder=""  value="" />
                                    </div>
                                    @if($language->question_image)
                                        <div class="col-sm-6">
                                            <img src="{{asset('')}}uploads/question/{{$language->question_image}}" style="width:100px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-6" id="option1_div_{{$language->id}}">
                                        <label class="form-label" for="option1_{{$language->id}}">Option 1 <span class="text-danger">*</span></label>
                                        @if($language->is_option1_image == 0) 
                                            <div id="option1_text_{{$language->id}}" style="display: block;" id="option1_div_{{$language->id}}">
                                                <textarea class="form-control" id="option1_{{$language->id}}" name="option1_text[{{$language->id}}]">{{$language->option1}}</textarea>
                                            </div>
                                            <div id="option1_image_{{$language->id}}" style="display: none;">
                                                <input class="form-control" id="option1_file_{{$language->id}}" type="file" name="option1_image[{{$language->id}}]">
                                            </div>
                                        @else 
                                            <div id="option1_text_{{$language->id}}" style="display: none;" id="option1_div_{{$language->id}}">
                                                <textarea class="form-control" id="option1_{{$language->id}}" name="option1_text[{{$language->id}}]">{{$language->option1}}</textarea>
                                            </div>
                                            <div id="option1_image_{{$language->id}}" style="display: block;">
                                                <input class="form-control" id="option1_file_{{$language->id}}" type="file" name="option1_image[{{$language->id}}]">
                                            </div>
                                        @endif
                                        
                                    </div>
                                    <div class="col-sm-1">
                                        @if($language->is_option1_image == 1)
                                            <img src="{{asset('')}}uploads/question/{{$language->option1}}" style="width:100%;">
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="answer_behavior_tag1_{{$language->id}}">Ans Behavior Tag <span class="text-danger">*</span></label>
                                        <select class="form-control" id="answer_behavior_tag1_{{$language->id}}" name="answer_behavior_tag1[{{$language->id}}]">
                                            <option value="">Select</option>
                                            <option value="1" @if($language->answer_behavior_tag1 == 1) selected  @endif>Correct Answer</option>
                                            <option value="2" @if($language->answer_behavior_tag1 == 2) selected  @endif>Close Call</option>
                                            <option value="3" @if($language->answer_behavior_tag1 == 3) selected  @endif>Silly Mistake</option>
                                            <option value="4" @if($language->answer_behavior_tag1 == 4) selected  @endif>Irrelevant</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="Status">&nbsp;</label>
                                        <div class="form-control">
                                            <label class="radio-inline radioFirst">
                                                <input type="radio" name="is_option1_image[{{$language->id}}]" id="is_option1_image1_{{$language->id}}" value="0" onchange="changeTextImageFun(1,1,{{$language->id}})" @if($language->is_option1_image == 0) checked  @endif>
                                                    Text
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_option1_image[{{$language->id}}]" id="is_option1_image2_{{$language->id}}" value="1" onchange="changeTextImageFun(1,2,{{$language->id}})" @if($language->is_option1_image == 1) checked  @endif>
                                                Image
                                            </label>
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6" id="option2_div_{{$language->id}}">
                                        <label class="form-label" for="option2_{{$language->id}}">Option 2 <span class="text-danger">*</span></label>
                                        @if($language->is_option2_image == 0) 
                                            <div id="option2_text_{{$language->id}}" style="display: block;" id="option2_div_{{$language->id}}">
                                                <textarea class="form-control" id="option2_{{$language->id}}" name="option2_text[{{$language->id}}]">{{$language->option2}}</textarea>
                                            </div>
                                            <div id="option2_image_{{$language->id}}" style="display: none;">
                                                <input class="form-control" id="option2_file_{{$language->id}}" type="file" name="option2_image[{{$language->id}}]">
                                            </div>
                                        @else 
                                            <div id="option2_text_{{$language->id}}" style="display: none;" id="option2_div_{{$language->id}}">
                                                <textarea class="form-control" id="option2_{{$language->id}}" name="option2_text[{{$language->id}}]">{{$language->option2}}</textarea>
                                            </div>
                                            <div id="option2_image_{{$language->id}}" style="display: block;">
                                                <input class="form-control" id="option2_file_{{$language->id}}" type="file" name="option2_image[{{$language->id}}]">
                                            </div>
                                        @endif
                                            
                                    </div>
                                    <div class="col-sm-1">
                                        @if($language->is_option2_image == 1)
                                            <img src="{{asset('')}}uploads/question/{{$language->option2}}" style="width:100%;">
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="answer_behavior_tag2_{{$language->id}}">Ans Behavior Tag <span class="text-danger">*</span></label>
                                        <select class="form-control" id="answer_behavior_tag2_{{$language->id}}" name="answer_behavior_tag2[{{$language->id}}]">
                                            <option value="">Select</option>
                                            <option value="1" @if($language->answer_behavior_tag2 == 1) selected  @endif>Correct Answer</option>
                                            <option value="2" @if($language->answer_behavior_tag2 == 2) selected  @endif>Close Call</option>
                                            <option value="3" @if($language->answer_behavior_tag2 == 3) selected  @endif>Silly Mistake</option>
                                            <option value="4" @if($language->answer_behavior_tag2 == 4) selected  @endif>Irrelevant</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="Status">&nbsp;</label>
                                        <div class="form-control">
                                            <label class="radio-inline radioFirst">
                                                <input type="radio" name="is_option2_image[{{$language->id}}]" id="is_option2_image1_{{$language->id}}" value="0" onchange="changeTextImageFun(2,1,{{$language->id}})"  @if($language->is_option2_image == 0) checked  @endif>
                                                    Text
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_option2_image[{{$language->id}}]" id="is_option2_image2_{{$language->id}}" value="1" onchange="changeTextImageFun(2,2,{{$language->id}})" @if($language->is_option2_image == 1) checked  @endif>
                                                Image
                                            </label>
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6" id="option3_div_{{$language->id}}">
                                        <label class="form-label" for="option3_{{$language->id}}">Option 3 <span class="text-danger">*</span></label>
                                        
                                        @if($language->is_option3_image == 0) 
                                            <div id="option3_text_{{$language->id}}" style="display: block;" id="option3_div_{{$language->id}}">
                                                <textarea class="form-control" id="option3_{{$language->id}}" name="option3_text[{{$language->id}}]">{{$language->option3}}</textarea>
                                            </div>
                                            <div id="option3_image_{{$language->id}}" style="display: none;">
                                                <input class="form-control" id="option3_file_{{$language->id}}" type="file" name="option3_image[{{$language->id}}]">
                                            </div>
                                        @else 
                                            <div id="option3_text_{{$language->id}}" style="display: none;" id="option3_div_{{$language->id}}">
                                                <textarea class="form-control" id="option3_{{$language->id}}" name="option3_text[{{$language->id}}]">{{$language->option3}}</textarea>
                                            </div>
                                            <div id="option3_image_{{$language->id}}" style="display: block;">
                                                <input class="form-control" id="option3_file_{{$language->id}}" type="file" name="option3_image[{{$language->id}}]">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-1">
                                        @if($language->is_option3_image == 1)
                                            <img src="{{asset('')}}uploads/question/{{$language->option3}}" style="width:100%;">
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="answer_behavior_tag3_{{$language->id}}">Ans Behavior Tag <span class="text-danger">*</span></label>
                                        <select class="form-control" id="answer_behavior_tag3_{{$language->id}}" name="answer_behavior_tag3[{{$language->id}}]">
                                            <option value="">Select</option>
                                            <option value="1" @if($language->answer_behavior_tag3 == 1) selected  @endif>Correct Answer</option>
                                            <option value="2" @if($language->answer_behavior_tag3 == 2) selected  @endif>Close Call</option>
                                            <option value="3" @if($language->answer_behavior_tag3 == 3) selected  @endif>Silly Mistake</option>
                                            <option value="4" @if($language->answer_behavior_tag3 == 4) selected  @endif>Irrelevant</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="Status">&nbsp;</label>
                                        <div class="form-control">
                                            <label class="radio-inline radioFirst">
                                                <input type="radio" name="is_option3_image[{{$language->id}}]" id="is_option3_image1_{{$language->id}}" value="0" onchange="changeTextImageFun(3,1,{{$language->id}})"  @if($language->is_option3_image == 0) checked  @endif>
                                                    Text
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_option3_image[{{$language->id}}]" id="is_option3_image2_{{$language->id}}" value="1" onchange="changeTextImageFun(3,2,{{$language->id}})" @if($language->is_option3_image == 1) checked  @endif>
                                                Image
                                            </label>
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6" id="option4_div_{{$language->id}}">
                                        <label class="form-label" for="option4_{{$language->id}}">Option 4 <span class="text-danger">*</span></label>
                                        
                                        @if($language->is_option4_image == 0) 
                                            <div id="option4_text_{{$language->id}}" style="display: block;" id="option4_div_{{$language->id}}">
                                                <textarea class="form-control" id="option4_{{$language->id}}" name="option4_text[{{$language->id}}]">{{$language->option4}}</textarea>
                                            </div>
                                            <div id="option4_image_{{$language->id}}" style="display: none;">
                                                <input class="form-control" id="option4_file_{{$language->id}}" type="file" name="option4_image[{{$language->id}}]">
                                            </div>
                                        @else 
                                            <div id="option4_text_{{$language->id}}" style="display: none;" id="option4_div_{{$language->id}}">
                                                <textarea class="form-control" id="option4_{{$language->id}}" name="option4_text[{{$language->id}}]">{{$language->option4}}</textarea>
                                            </div>
                                            <div id="option4_image_{{$language->id}}" style="display: block;">
                                                <input class="form-control" id="option4_file_{{$language->id}}" type="file" name="option4_image[{{$language->id}}]">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-1">
                                        @if($language->is_option4_image == 1)
                                            <img src="{{asset('')}}uploads/question/{{$language->option4}}" style="width:100%;">
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="answer_behavior_tag4_{{$language->id}}">Ans Behavior Tag <span class="text-danger">*</span></label>
                                        <select class="form-control" id="answer_behavior_tag4_{{$language->id}}" name="answer_behavior_tag4[{{$language->id}}]">
                                            <option value="">Select</option>
                                            <option value="1" @if($language->answer_behavior_tag4 == 1) selected  @endif>Correct Answer</option>
                                            <option value="2" @if($language->answer_behavior_tag4 == 2) selected  @endif>Close Call</option>
                                            <option value="3" @if($language->answer_behavior_tag4 == 3) selected  @endif>Silly Mistake</option>
                                            <option value="4" @if($language->answer_behavior_tag4 == 4) selected  @endif>Irrelevant</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="Status">&nbsp;</label>
                                        <div class="form-control">
                                            <label class="radio-inline radioFirst">
                                                <input type="radio" name="is_option4_image[{{$language->id}}]" id="is_option4_image1_{{$language->id}}" value="0" onchange="changeTextImageFun(4,1,{{$language->id}})"  @if($language->is_option4_image == 0) checked  @endif>
                                                    Text
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_option4_image[{{$language->id}}]" id="is_option4_image2_{{$language->id}}" value="1" onchange="changeTextImageFun(4,2,{{$language->id}})" @if($language->is_option4_image == 1) checked  @endif>
                                                Image
                                            </label>
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label" for="solution_{{$language->id}}">Solution</label>
                                        <input class="form-control" id="solution_{{$language->id}}" type="text" name="solution[{{$language->id}}]" placeholder=""  value="{{$language->solution}}" />
                                    </div>
                                </div>
                            </div>

                        @endforeach
                                
                        <div class="col-sm-6">
                            <label class="form-label" for="question_source_id">Question source</label>
                            <select class="form-control" id="question_source_id" name="question_source_id">
                                <option>Select Question source</option>
                                @foreach($question_source_list as $key => $value)
                                    <option value="{{$value->id}}" @if($details->question_source_id == $value->id) selected  @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="solution_video_link">Solution Video Link </label>
                            <input type="text" class="form-control" id="solution_video_link" name="solution_video_link" value="{{$details->solution_video_link}}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="answer">Correct Answer <span class="text-danger">*</span></label>
                            <select class="form-control" id="answer" name="answer">
                                <option value="">Select Correct Answer</option>
                                <option value="1" @if($details->answer == 1) selected  @endif>Option 1</option>
                                <option value="2" @if($details->answer == 2) selected  @endif>Option 2</option>
                                <option value="3" @if($details->answer == 3) selected  @endif>Option 3</option>
                                <option value="4" @if($details->answer == 4) selected  @endif>Option 4</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" @if($details->status == 1) checked  @endif  value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" @if($details->status == 0) checked  @endif />
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
        var question_editors = [];
        var option1_editors = [];
        var option2_editors = [];
        var option3_editors = [];
        var option4_editors = [];

        @foreach($language_list as $language)

            ClassicEditor
                .create(document.querySelector('#question_text_{{ $language->id }}'))
                .then( editor => {
                    question_editors[{{ $language->id }}] = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#option1_{{ $language->id }}'))
                .then( editor => {
                    option1_editors[{{ $language->id }}] = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#option2_{{ $language->id }}'))
                .then( editor => {
                    option2_editors[{{ $language->id }}] = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#option3_{{ $language->id }}'))
                .then( editor => {
                    option3_editors[{{ $language->id }}] = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#option4_{{ $language->id }}'))
                .then( editor => {
                    option4_editors[{{ $language->id }}] = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
        @endforeach

        var reqData = {
            option1:false,
            option2:false,
            option3:false,
            option4:false,
            question_text:false,
            question_image:false,
            category_id:false
        }

        function changeSubject() {
            var data = {};
            data.subject_id = document.getElementById('subject_id').value;

            $.get("{{route('admin.common.chapter')}}", data)
              .done(function( response ) {
                console.log(response);
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Chapter</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('chapter_id').innerHTML = role_modal_body;
              });

            $.get("{{route('admin.common.source')}}", data)
              .done(function( response ) {
                console.log(response);
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Source</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('source_id').innerHTML = role_modal_body;
              });
        }

        function changeChapter(){

            var data = {};
            data.subject_id = document.getElementById('subject_id').value;
            data.chapter_id = document.getElementById('chapter_id').value;

            $.get("{{route('admin.common.topic')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Topic</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('topic_id').innerHTML = role_modal_body;
              });
        }

        function changeTopic(){

            var data = {};
            data.subject_id = document.getElementById('subject_id').value;
            data.chapter_id = document.getElementById('chapter_id').value;
            data.topic_id = document.getElementById('topic_id').value;

            $.get("{{route('admin.common.sub_topic')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Sub Topic</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('sub_topic_id').innerHTML = role_modal_body;
              });
        }

        function onKeyPressFun(index){

        }

        function onChangeFun(index){
            
        }

        function changeTextImageFun(index, type, language){
            reqData['option'+index] = false;
              
            if(type == 2){
                document.getElementById('option'+index+'_text_'+language).style.display = "none";
                document.getElementById('option'+index+'_image_'+language).style.display = "block";
            }else{
                document.getElementById('option'+index+'_text_'+language).style.display = "block";
                document.getElementById('option'+index+'_image_'+language).style.display = "none";
            }
            console.log(reqData);
        } 

        var glob_language_list = <?php echo json_encode($language_list);?>;


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

            data.chapter_id = document.getElementById('chapter_id').value;
            if(data.chapter_id){
                document.getElementById('chapter_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('chapter_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.source_id = document.getElementById('source_id').value;
            if(data.source_id){
                document.getElementById('source_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('source_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.topic_id = document.getElementById('topic_id').value;
            if(data.topic_id){
                document.getElementById('topic_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('topic_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.sub_topic_id = document.getElementById('sub_topic_id').value;
            if(data.sub_topic_id){
                document.getElementById('sub_topic_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('sub_topic_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.difficulty_level = document.getElementById('difficulty_level').value;
            if(data.difficulty_level){
                document.getElementById('difficulty_level').classList.remove('dangerBoader');
            }else{
                document.getElementById('difficulty_level').classList.add('dangerBoader');
                successFlag = false;
            }

            data.question_type_id = document.getElementById('question_type_id').value;
            if(data.question_type_id){
                document.getElementById('question_type_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('question_type_id').classList.add('dangerBoader');
                successFlag = false;
            }

            glob_language_list.forEach((val) => {
                
                var question_text = document.getElementById('question_text_div_'+val.id);
                
                var child_question_text = question_text.querySelector("div");
                console.log(child_question_text);
                if(question_editors[val.id].getData()){
                    child_question_text.classList.remove('dangerBoader');
                }else{
                    child_question_text.classList.add('dangerBoader');
                    successFlag = false;
                }
                
                let selectedGender = document.querySelector('input[name="is_option1_image['+val.id+']"]:checked').value;
                console.log(selectedGender);
                
                if(selectedGender == 0){
                    question_text = document.getElementById('option1_div_'+val.id);
                    
                    var child_question_text = question_text.querySelector("div");
                    
                    if(option1_editors[val.id].getData()){
                        child_question_text.classList.remove('dangerBoader');
                    }else{
                        child_question_text.classList.add('dangerBoader');
                        successFlag = false;
                    }
                }else{
                    // data.option1_image = document.getElementById('option1_file_'+val.id).files[0];
                    // console.log(data.option1_image);
                    // if(data.option1_image){
                    //     document.getElementById('option1_file_'+val.id).classList.remove('dangerBoader');
                    // }else{
                    //     document.getElementById('option1_file_'+val.id).classList.add('dangerBoader');
                    //     successFlag = false;
                    // }
                }
                    
                
                data.answer_behavior_tag1 = document.getElementById('answer_behavior_tag1_'+val.id).value;
                if(data.answer_behavior_tag1){
                    document.getElementById('answer_behavior_tag1_'+val.id).classList.remove('dangerBoader');
                }else{
                    document.getElementById('answer_behavior_tag1_'+val.id).classList.add('dangerBoader');
                    successFlag = false;
                }
                
                let selectedGender2 = document.querySelector('input[name="is_option2_image['+val.id+']"]:checked').value;
                
                if(selectedGender2 == 0){
                    question_text = document.getElementById('option2_div_'+val.id);
                    
                    var child_question_text = question_text.querySelector("div");
                    
                    if(option2_editors[val.id].getData()){
                        child_question_text.classList.remove('dangerBoader');
                    }else{
                        child_question_text.classList.add('dangerBoader');
                        successFlag = false;
                    }
                }else{
                    // data.option2_image = document.getElementById('option2_file_'+val.id).files[0];
                    // if(data.option2_image){
                    //     document.getElementById('option2_file_'+val.id).classList.remove('dangerBoader');
                    // }else{
                    //     document.getElementById('option2_file_'+val.id).classList.add('dangerBoader');
                    //     successFlag = false;
                    // }
                }
                
                data.answer_behavior_tag2 = document.getElementById('answer_behavior_tag2_'+val.id).value;
                if(data.answer_behavior_tag2){
                    document.getElementById('answer_behavior_tag2_'+val.id).classList.remove('dangerBoader');
                }else{
                    document.getElementById('answer_behavior_tag2_'+val.id).classList.add('dangerBoader');
                    successFlag = false;
                }
                
                let selectedGender3 = document.querySelector('input[name="is_option3_image['+val.id+']"]:checked').value;
                
                if(selectedGender3 == 0){
                    question_text = document.getElementById('option3_div_'+val.id);
                    
                    var child_question_text = question_text.querySelector("div");
                    
                    if(option3_editors[val.id].getData()){
                        child_question_text.classList.remove('dangerBoader');
                    }else{
                        child_question_text.classList.add('dangerBoader');
                        successFlag = false;
                    }
                }else{
                    // data.option3_image = document.getElementById('option3_file_'+val.id).files[0];
                    // if(data.option3_image){
                    //     document.getElementById('option3_file_'+val.id).classList.remove('dangerBoader');
                    // }else{
                    //     document.getElementById('option3_file_'+val.id).classList.add('dangerBoader');
                    //     successFlag = false;
                    // }
                }
                
                data.answer_behavior_tag3 = document.getElementById('answer_behavior_tag3_'+val.id).value;
                if(data.answer_behavior_tag3){
                    document.getElementById('answer_behavior_tag3_'+val.id).classList.remove('dangerBoader');
                }else{
                    document.getElementById('answer_behavior_tag3_'+val.id).classList.add('dangerBoader');
                    successFlag = false;
                }
                
                let selectedGender4 = document.querySelector('input[name="is_option4_image['+val.id+']"]:checked').value;
                
                if(selectedGender4 == 0){
                    question_text = document.getElementById('option4_div_'+val.id);
                    
                    var child_question_text = question_text.querySelector("div");
                    
                    if(option4_editors[val.id].getData()){
                        child_question_text.classList.remove('dangerBoader');
                    }else{
                        child_question_text.classList.add('dangerBoader');
                        successFlag = false;
                    }
                }else{
                    // data.option4_image = document.getElementById('option4_file_'+val.id).files[0];
                    // if(data.option4_image){
                    //     document.getElementById('option4_file_'+val.id).classList.remove('dangerBoader');
                    // }else{
                    //     document.getElementById('option4_file_'+val.id).classList.add('dangerBoader');
                    //     successFlag = false;
                    // }
                }
                
                data.answer_behavior_tag4 = document.getElementById('answer_behavior_tag4_'+val.id).value;
                if(data.answer_behavior_tag4){
                    document.getElementById('answer_behavior_tag4_'+val.id).classList.remove('dangerBoader');
                }else{
                    document.getElementById('answer_behavior_tag4_'+val.id).classList.add('dangerBoader');
                    successFlag = false;
                }
            })

            data.question_source_id = document.getElementById('question_source_id').value;
            if(data.question_source_id){
                document.getElementById('question_source_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('question_source_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.answer = document.getElementById('answer').value;
            if(data.answer){
                document.getElementById('answer').classList.remove('dangerBoader');
            }else{
                document.getElementById('answer').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection