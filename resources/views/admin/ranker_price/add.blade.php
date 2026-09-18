@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Meeting</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.ranker_price.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
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
                            <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                            <input class="form-control" id="title" type="text" name="title" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                            <textarea id="editor" name="description"class="form-control" rows="5"></textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                            <input class="form-control" id="type" type="text" name="type" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
                            <input class="form-control" id="price" type="text" name="price" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="price">Discount Price</label>
                            <input class="form-control" id="dis_price" type="text" name="dis_price" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="time">Time (In Min) <span class="text-danger">*</span></label>
                            <input class="form-control" id="time" type="text" name="time" placeholder=""  value="" />
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

            data.title = document.getElementById('title').value;
            if(data.title){
                document.getElementById('title').classList.remove('dangerBoader');
            }else{
                document.getElementById('title').classList.add('dangerBoader');
                successFlag = false;
            }

            data.editor = editorInstance.getData();
            if(data.editor){
                document.querySelector('.ck-editor__main').classList.remove('dangerBoader');
            }else{
                document.querySelector('.ck-editor__main').classList.add('dangerBoader');
                successFlag = false;
            }

            data.type = document.getElementById('type').value;
            if(data.type){
                document.getElementById('type').classList.remove('dangerBoader');
            }else{
                document.getElementById('type').classList.add('dangerBoader');
                successFlag = false;
            }

            data.price = document.getElementById('price').value;
            if(data.price){
                document.getElementById('price').classList.remove('dangerBoader');
            }else{
                document.getElementById('price').classList.add('dangerBoader');
                successFlag = false;
            }

            data.time = document.getElementById('time').value;
            if(data.time){
                document.getElementById('time').classList.remove('dangerBoader');
            }else{
                document.getElementById('time').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection
