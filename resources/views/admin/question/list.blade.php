@extends('layouts.backend')


@section('css_after')
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin: 20px 0;
            list-style: none;
            padding: 0;
        }
        
        .pagination svg {
            width: 16px;
            height: 16px;
        }
        
        .pagination .page-item {
            display: inline-block;
        }
        
        .pagination .page-link {
            display: block;
            padding: 8px 14px;
            color: #0d6efd;
            background: #fff;
            border: 1px solid #dee2e6;
            text-decoration: none;
            border-radius: 4px;
            transition: all .2s;
        }
        
        .pagination .page-link:hover {
            background: #0d6efd;
            color: #fff;
        }
        
        .pagination .page-item.active .page-link {
            background: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background: #e9ecef;
            border-color: #dee2e6;
            cursor: not-allowed;
        }
        
        .pagination p {
            margin: 0;
        }
        
        .pagination nav {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Question</h1>
            </div>
            <div>
                <a href="{{route('admin.question.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i> 
                    Add
                </a>
            </div>
        </div>
        <form action="" method="get">
            <div class="row mb-4">
                <div class="col-sm-3">
                    <label class="form-label" for="subject_id">Subject</label>
                    <select class="form-control" id="subject_id" name="subject_id" onchange="changeSubject();">
                        <option value="">Select Subject</option>
                        @foreach($subject_list as $key => $value)
                            <option value="{{$value->id}}" @if($value->id == $subject_id) selected @endif>{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <label class="form-label" for="chapter_id">Chapter</label>
                    <select class="form-control" id="chapter_id" name="chapter_id" onchange="changeChapter();">
                        <option value="">Select Chapter</option>
                        @foreach($chapter_list as $key => $value)
                            <option value="{{$value->id}}" @if($value->id == $chapter_id) selected @endif>{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <label class="form-label" for="topic_id">Topic  <span class="text-danger">*</span></label>
                    <select class="form-control" id="topic_id" name="topic_id" onchange="changeTopic();">
                        <option value="">Select Topic</option>
                        @foreach($topic_list as $key => $value)
                            <option value="{{$value->id}}" @if($value->id == $topic_id) selected @endif>{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label" for="sub_topic_id">Sub Topic  <span class="text-danger">*</span></label>
                    <select class="form-control" id="sub_topic_id" name="sub_topic_id">
                        <option value="">Select Sub Topic</option>
                        @foreach($sub_topic_list as $key => $value)
                            <option value="{{$value->id}}" @if($value->id == $sub_topic_id) selected @endif>{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Search</button>
                </div>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Chapter</th>
                    <th>Source</th>
                    <th>Question</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->subject_name}}</td>
                            <td>{{$row->chapter_name}}</td>
                            <td>{{$row->source_name}}</td>
                            <td>
                                {!!$row->question_text!!}


                                @if($row->question_image)
                                    <div>
                                        <img src="{{ asset('') }}uploads/question/{{$row->question_image}}" style="width:50px;">
                                    </div>
                                @endif
                                
                                <div style="margin:10px;">
                                    <div style="display:flex;">
                                        (1) 
                                        @if($row->is_option1_image)
                                            <div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$row->option1}}" style="width:100px;"></div>
                                        @else
                                            {!!$row->option1!!}
                                        @endif
                                    </div>
                                    <div style="display:flex;">
                                        (2) 
                                        @if($row->is_option2_image)
                                            <div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$row->option2}}" style="width:100px;"></div>
                                        @else
                                            {!!$row->option2!!}
                                        @endif
                                    </div>
                                    <div style="display:flex;">
                                        (3) 
                                        @if($row->is_option3_image)
                                            <div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$row->option3}}" style="width:100px;"></div>
                                        @else
                                            {!!$row->option3!!}
                                        @endif
                                    </div>
                                    <div style="display:flex;">
                                        (4) 
                                        @if($row->is_option4_image)
                                            <div style="margin: -15px 0px 10px 25px;"><img src="{{ asset('') }}uploads/question/{{$row->option4}}" style="width:100px;"></div>
                                        @else
                                            {!!$row->option4!!}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('admin.question.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.question.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('admin.question.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="pagination">
            {{ $list->links() }}
        </div>
    </div>

@endsection


@section('js_after')
    <script>
        

        function changeSubject() {
            var data = {};
            data.subject_id = document.getElementById('subject_id').value;

            $.get("{{route('admin.common.chapter')}}", data)
              .done(function( response ) {
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

    </script>

@endsection