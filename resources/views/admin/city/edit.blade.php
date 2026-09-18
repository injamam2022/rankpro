
@extends('layouts.backend')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
        
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit City</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-9">
                <form action="{{route('admin.city.update')}}" method="post" enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <input type="hidden" name="id" value="{{$details->id}}">
                    <div id="material-textfield">
                        <div class="mb-3">
                            <label class="form-label" for="country_id">Country <span class="text-danger">*</span></label>
                            <select class="form-select" name="country_id" id="country_id" aria-label="Large select example" onchange="changeCountry();">
                                <option value="">Select Country</option>
                                @foreach($country_list as $value)
                                    <option value="{{$value->id}}" @if($value->id == $details->country_id) selected @endif>{{$value->name}}</option>
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
                                    <option value="{{$value->id}}" @if($value->id == $details->state_id) selected @endif>{{$value->name}}</option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="invalid-feedback" role="alert" style="display:block;">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" name="name" type="text" placeholder=""  value="{{$details->name}}" />
                            @error('name')
                                <div class="invalid-feedback" role="alert" style="display:block;">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" id="status_active" type="radio" name="status" value="1" @if($details->status == 1) checked  @endif  />
                                <label class="form-check-label" for="status_active">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0"  @if($details->status == 0) checked  @endif />
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

            // console.log(data);

            return successFlag;
        }
    </script>
@endsection