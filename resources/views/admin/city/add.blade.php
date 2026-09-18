
@extends('layouts.backend')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add City</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-9">
                <form action="{{route('admin.city.save')}}" method="post" enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div id="material-textfield">
                        <div class="mb-3">
                            <label class="form-label" for="country_id">Country <span class="text-danger">*</span></label>
                            <select class="form-select" name="country_id" id="country_id" aria-label="Large select example" onchange="changeCountry();">
                                <option value="">Select Country</option>
                                @foreach($country_list as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="invalid-feedback" role="alert" style="display:block;">
                                    <strong>{{ $errors->first('country_id') }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="state_id">State <span class="text-danger">*</span></label>
                            <select class="form-select" name="state_id" id="state_id" aria-label="Large select example">
                                <option value="">Select State</option>
                                @foreach($state_list as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="invalid-feedback" role="alert" style="display:block;">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" name="name" type="text" placeholder=""  value="" />
                            @error('name')
                                <div class="invalid-feedback" role="alert" style="display:block;">
                                    <strong>{{ $errors->first('name') }}</strong>
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
        function changeCountry(){
            var data = {};
            data.country_id = document.getElementById('country_id').value;

            $.get("{{route('admin.common.state')}}", data)
              .done(function( response ) {
                console.log(response);
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select State</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('state_id').innerHTML = role_modal_body;
              });
        }
    </script>
    <script type="text/javascript">
        
        function formValidation(){
            var data = {};
            var successFlag = true;

            data.country_id = document.getElementById('country_id').value;
            if(data.country_id){
                document.getElementById('country_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('country_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.state_id = document.getElementById('state_id').value;
            if(data.state_id){
                document.getElementById('state_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('state_id').classList.add('dangerBoader');
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