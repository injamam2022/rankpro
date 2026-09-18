
@extends('layouts.backend')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
        <div class="container-xl px-5">
            <div class="d-flex mt-10 mb-4 align-items-center">
                <h1 class="page-header mb-0">Add Country</h1>
            </div>
            <div class="row gx-5">
                <div class="col-lg-9">
                    <form action="{{route('admin.country.save')}}" method="post" enctype="multipart/form-data" onsubmit="return formValidation();">
                        @csrf
                        <div id="material-textfield">
                            <div class="mb-3">
                                <label class="form-label" for="exampleFormControlInput">Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="name" name="name" type="text" placeholder=""  value="" />
                                @error('name')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="sortname">Sort Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="sortname" name="sortname" type="text" placeholder=""  value="" />
                                @error('sortname')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('sortname') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="phonecode">Phone Code <span class="text-danger">*</span></label>
                                <input class="form-control" id="phonecode" name="phonecode" type="text" placeholder=""  value="" />
                                @error('phonecode')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('phonecode') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="exampleFormControlInput">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" checked value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
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

        function formValidation(){
            var data = {};
            var successFlag = true;

            data.name = document.getElementById('name').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }

            data.sortname = document.getElementById('sortname').value;
            if(data.sortname){
                document.getElementById('sortname').classList.remove('dangerBoader');
            }else{
                document.getElementById('sortname').classList.add('dangerBoader');
                successFlag = false;
            }

            data.phonecode = document.getElementById('phonecode').value;
            if(data.phonecode){
                document.getElementById('phonecode').classList.remove('dangerBoader');
            }else{
                document.getElementById('phonecode').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection