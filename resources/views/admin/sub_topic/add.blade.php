@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Sub Topic</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.sub_topic.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="subject_id">Subject <span class="text-danger">*</span></label>
                            <select class="form-control" id="subject_id" name="subject_id" onchange="changeSubject();">
                                <option value="">Select Subject</option>
                                @foreach($subject_list as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="chapter_id">Chapter <span class="text-danger">*</span></label>
                            <select class="form-select" name="chapter_id" id="chapter_id" onchange="changeChapter();">
                                <option value="">Select Chapter</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="topic_id">Topic <span class="text-danger">*</span></label>
                            <select class="form-select" name="topic_id" id="topic_id">
                                <option value="">Select Topic</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
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
        }
        function changeChapter() {
            var data = {};
            data.subject_id = document.getElementById('subject_id').value;
            data.chapter_id = document.getElementById('chapter_id').value;

            $.get("{{route('admin.common.topic')}}", data)
              .done(function( response ) {
                console.log(response);
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

            data.topic_id = document.getElementById('topic_id').value;
            if(data.topic_id){
                document.getElementById('topic_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('topic_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.name = document.getElementById('name').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection