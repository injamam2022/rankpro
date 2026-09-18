@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Coupon</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.coupon.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="{{$details->name}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="code">Code <span class="text-danger">*</span></label>
                            <input class="form-control" id="code" type="text" name="code" placeholder=""  value="{{$details->code}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="Fixed" @if($details->type == 'Fixed') selected  @endif>Fixed Amount</option>
                                <option value="Percentage" @if($details->type == 'Percentage') selected  @endif>Percentage Amount</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="value">Value <span class="text-danger">*</span></label>
                            <input class="form-control" id="value" type="text" name="value" placeholder=""  value="{{$details->value}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="start_date">Start Date <span class="text-danger">*</span></label>
                            <input class="form-control" id="start_date" type="date" name="start_date" placeholder=""  value="{{$details->start_date}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="end_date">End Date <span class="text-danger">*</span></label>
                            <input class="form-control" id="end_date" type="date" name="end_date" placeholder=""  value="{{$details->end_date}}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" @if($details->status == 1) checked  @endif value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" @if($details->status == 0) checked  @endif value="0" />
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

            data.code = document.getElementById('code').value;
            if(data.code){
                document.getElementById('code').classList.remove('dangerBoader');
            }else{
                document.getElementById('code').classList.add('dangerBoader');
                successFlag = false;
            }

            data.type = document.getElementById('type').value;
            if(data.type){
                document.getElementById('type').classList.remove('dangerBoader');
            }else{
                document.getElementById('type').classList.add('dangerBoader');
                successFlag = false;
            }

            data.value = document.getElementById('value').value;
            if(data.value){
                document.getElementById('value').classList.remove('dangerBoader');
            }else{
                document.getElementById('value').classList.add('dangerBoader');
                successFlag = false;
            }

            data.start_date = document.getElementById('start_date').value;
            if(data.start_date){
                document.getElementById('start_date').classList.remove('dangerBoader');
            }else{
                document.getElementById('start_date').classList.add('dangerBoader');
                successFlag = false;
            }

            data.end_date = document.getElementById('end_date').value;
            if(data.end_date){
                document.getElementById('end_date').classList.remove('dangerBoader');
            }else{
                document.getElementById('end_date').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>

@endsection