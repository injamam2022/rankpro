
@extends('layouts.backend')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Location</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-9">
                <form action="{{route('admin.location.save')}}" method="post" enctype="multipart/form-data" onsubmit="return formValidation();">
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
                            <select class="form-select" name="state_id" id="state_id" aria-label="Large select example" onchange="changeState();">
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="city_id">City <span class="text-danger">*</span></label>
                            <select class="form-select" name="city_id" id="city_id" aria-label="Large select example">
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="logo">Logo (Image Size: W- 315 px, H- 170 px) (Type: PNG,JPG,JPEG)<span class="text-danger">*</span></label>
                            <input class="form-control" id="logo" name="logo" type="file" placeholder=""  value="" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="location_name">Title <span class="text-danger">*</span></label>
                            <input class="form-control" id="location_name" name="location_name" type="text" placeholder=""  value="" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="location_description">Description <span class="text-danger">*</span></label>
                            <input class="form-control" id="location_description" name="location_description" type="text" placeholder=""  value="" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                            <input class="form-control" id="address" name="address" type="text" placeholder=""  value="" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="zip_code">Zip Code <span class="text-danger">*</span></label>
                            <input class="form-control" id="zip_code" name="zip_code" type="text" placeholder=""  value="" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone_number">Phone Number <span class="text-danger">*</span></label>
                            <input class="form-control" id="phone_number" name="phone_number" type="text" placeholder=""  value="" />
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

        function changeState(){

            var data = {};
            data.state_id = document.getElementById('state_id').value;

            $.get("{{route('admin.common.city')}}", data)
              .done(function( response ) {
                console.log(response);
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select City</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('city_id').innerHTML = role_modal_body;
              });
        }
    </script>
    <script type="text/javascript">
        
        const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
        const maxSize1 = 2048 * 2048;

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

            data.city_id = document.getElementById('city_id').value;
            if(data.city_id){
                document.getElementById('city_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('city_id').classList.add('dangerBoader');
                successFlag = false;
            }

            var logo = document.getElementById("logo");
            var file = logo.files[0];
            if(file){
                if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
                  document.getElementById('logo').classList.add('dangerBoader');
                  successFlag = false;
                }else{
                  document.getElementById('logo').classList.remove('dangerBoader');
                }
            }else{
                document.getElementById('logo').classList.add('dangerBoader');
                successFlag = false;
            } 

            data.location_name = document.getElementById('location_name').value;
            if(data.location_name){
                document.getElementById('location_name').classList.remove('dangerBoader');
            }else{
                document.getElementById('location_name').classList.add('dangerBoader');
                successFlag = false;
            }

            data.location_description = document.getElementById('location_description').value;
            if(data.location_description){
                document.getElementById('location_description').classList.remove('dangerBoader');
            }else{
                document.getElementById('location_description').classList.add('dangerBoader');
                successFlag = false;
            }

            data.address = document.getElementById('address').value;
            if(data.address){
                document.getElementById('address').classList.remove('dangerBoader');
            }else{
                document.getElementById('address').classList.add('dangerBoader');
                successFlag = false;
            }

            data.zip_code = document.getElementById('zip_code').value;
            if(data.zip_code){
                document.getElementById('zip_code').classList.remove('dangerBoader');
            }else{
                document.getElementById('zip_code').classList.add('dangerBoader');
                successFlag = false;
            }

            data.phone_number = document.getElementById('phone_number').value;
            if(data.phone_number){
                document.getElementById('phone_number').classList.remove('dangerBoader');
            }else{
                document.getElementById('phone_number').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection