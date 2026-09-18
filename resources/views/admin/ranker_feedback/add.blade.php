@extends('layouts.backend')

@section('css_after')
@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Ranker Feedback</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{ route('admin.ranker_feedback.save') }}" method="post" enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="ranker_id">Ranker <span class="text-danger">*</span></label>
                            <select class="form-control" id="ranker_id" name="ranker_id">
                                <option value="">Select</option>
                                @foreach($ranker_list as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="student_id">Student <span class="text-danger">*</span></label>
                            <select class="form-control" id="student_id" name="student_id">
                                <option value="">Select</option>
                                @foreach($user_list as $key => $value)
                                    <option value="{{$value->id}}">{{$value->first_name}} {{$value->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="rating">Rating <span class="text-danger">*</span></label>
                            <select class="form-control" id="rating" name="rating">
                                <option value="">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="description">Feedback <span class="text-danger">*</span></label>
                            <textarea id="editor" name="text"class="form-control" rows="5"></textarea>
                        </div>

                        <div class="col-sm-12">
                            <label class="form-label">Status</label>
                            <div class="form-control d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" checked value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check ms-3">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-5 text-center">
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js_after')
    <script>
        var editorInstance; 
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then( editor => {
                editorInstance = editor; // Store the editor instance
                console.log( 'Editor was initialized', editorInstance );
            })
            .catch(error => {
                console.error(error);
            }); 
    </script>
    <script type="text/javascript">
        
        function formValidation(){
            var data = {};
            var successFlag = true;

            data.ranker_id = document.getElementById('ranker_id').value;
            if(data.ranker_id){
                document.getElementById('ranker_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('ranker_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.student_id = document.getElementById('student_id').value;
            if(data.student_id){
                document.getElementById('student_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('student_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.rating = document.getElementById('rating').value;
            if(data.rating){
                document.getElementById('rating').classList.remove('dangerBoader');
            }else{
                document.getElementById('rating').classList.add('dangerBoader');
                successFlag = false;
            }

            data.editor = editorInstance.getData();
            if(data.editor){
                document.querySelector('.ck-editor__main').classList.remove('dangerBoader');
            }else{
                document.querySelector('.ck-editor__main').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection
