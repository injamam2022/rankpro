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

        .question-duplicate td {
            background-color: #fff6df !important;
        }

        .duplicate-flag {
            display: inline-block;
            background: #f0ad00;
            color: #1f1600;
            font-size: 12px;
            font-weight: 700;
            border-radius: 4px;
            padding: 2px 8px;
            margin-bottom: 6px;
        }

        .duplicate-links {
            font-size: 12px;
            color: #8a5a00;
            margin-bottom: 6px;
        }

        .duplicate-links a {
            color: #8a5a00;
            font-weight: 700;
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
                <a href="{{route('admin.question.add')}}" class="rp-act rp-act-add">
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
                    <button class="rp-act rp-act-add" type="submit" style="margin-top: 25px;">
                        <i class="material-icons">search</i>
                        Search
                    </button>
                </div>
            </div>
            <div class="admin-list-bar">
                <input class="form-control" type="search" name="q" value="{{ $q ?? '' }}" placeholder="Search question, subject, chapter..." style="max-width:320px;">
                <label class="mb-0" style="font-size:13px;color:#64748b;">Per page</label>
                <select class="form-control" name="per_page" style="width:90px;" onchange="this.form.submit()">
                    @foreach([10, 25, 50] as $n)
                        <option value="{{ $n }}" @if((int)($per_page ?? 10) === $n) selected @endif>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap" style="gap:10px;">
                <label class="mb-0" style="font-weight: 600;">
                    <input type="checkbox" name="duplicates" value="1" @if(!empty($duplicates)) checked @endif onchange="this.form.submit()">
                    Show duplicates only
                </label>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    @if(!empty($duplicate_count))
                        <span style="background:#fff3cd;color:#856404;border:1px solid #ffe69c;border-radius:4px;padding:4px 10px;font-size:13px;">
                            {{ $duplicate_count }} duplicate {{ $duplicate_count == 1 ? 'question' : 'questions' }}
                        </span>
                    @endif
                    @if(!empty($duplicates) && !empty($removable_duplicate_count))
                        <button type="button"
                                class="rp-act rp-act-delete rp-act-label"
                                title="Delete duplicate copies (keeps one original each)"
                                onclick="if(confirm('Delete {{ (int)$removable_duplicate_count }} duplicate question(s)?\n\nOne original is kept for each set. Current filters still apply.')){ document.getElementById('bulkDupForm').submit(); }">
                            <i class="material-icons">delete_sweep</i>
                            Bulk delete duplicates ({{ $removable_duplicate_count }})
                        </button>
                    @endif
                </div>
            </div>
        </form>
        @if(!empty($duplicates) && !empty($removable_duplicate_count))
            <form id="bulkDupForm" method="post" action="{{ route('admin.question.bulk_delete_duplicates') }}" style="display:none;">
                @csrf
                <input type="hidden" name="duplicates" value="1">
                <input type="hidden" name="subject_id" value="{{ $subject_id }}">
                <input type="hidden" name="chapter_id" value="{{ $chapter_id }}">
                <input type="hidden" name="topic_id" value="{{ $topic_id }}">
                <input type="hidden" name="sub_topic_id" value="{{ $sub_topic_id }}">
                <input type="hidden" name="q" value="{{ $q ?? '' }}">
                <input type="hidden" name="per_page" value="{{ $per_page ?? 10 }}">
            </form>
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Chapter</th>
                    <th>Source</th>
                    <th>Question</th>
                    <th>Added</th>
                    <th>Updated</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr class="{{ !empty($row->is_duplicate) ? 'question-duplicate' : '' }}">
                            <td>{{$row->subject_name}}</td>
                            <td>{{$row->chapter_name}}</td>
                            <td>{{$row->source_name}}</td>
                            <td>
                                @if(!empty($row->is_duplicate))
                                    <div>
                                        <span class="duplicate-flag">Duplicate</span>
                                    </div>
                                    <div class="duplicate-links">
                                        Same question as
                                        @foreach(array_slice($row->duplicate_ids, 0, 6) as $dupId)
                                            <a href="{{ route('admin.question.edit', ['id' => $dupId]) }}">#{{ $dupId }}</a>@if(!$loop->last), @endif
                                        @endforeach
                                        @if(count($row->duplicate_ids) > 6)
                                            +{{ count($row->duplicate_ids) - 6 }} more
                                        @endif
                                    </div>
                                @endif
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
                            <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->created_at) }}</td>
                            <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->updated_at) }}</td>
                            <td>
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.question.change', ['id' => $row->id]),
                                    'variant' => $row->status == 1 ? 'on' : 'off',
                                    'icon' => $row->status == 1 ? 'toggle_on' : 'toggle_off',
                                    'title' => $row->status == 1 ? 'Active — click to deactivate' : 'Inactive — click to activate',
                                    'onclick' => "return confirm('Do you really want to change status?');",
                                ])
                            </td>
                            <td>
                                <div class="rp-actions">
                                    @include('admin.include.icon_btn', [
                                        'href' => route('admin.question.edit', ['id' => $row->id]),
                                        'variant' => 'edit',
                                        'icon' => 'edit',
                                        'title' => 'Edit',
                                    ])
                                    @include('admin.include.icon_btn', [
                                        'href' => route('admin.question.delete', ['id' => $row->id]),
                                        'variant' => 'delete',
                                        'icon' => 'delete',
                                        'title' => 'Delete',
                                        'onclick' => "return confirm('Do you really want to delete?');",
                                    ])
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center text-muted">No records found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @include('admin.include.list_pager')
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