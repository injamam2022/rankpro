
@extends('layouts.counsellor')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
        
        <div class="container-xl px-5">
            <div class="d-flex mt-10 mb-4 align-items-center">
                <h1 class="page-header mb-0">Edit Setting</h1>
            </div>
            <div class="row gx-5">
                <div class="col-lg-9">
                    <form action="{{route('admin.updateSetting')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$details->id}}">
                        <div id="material-textfield">
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
                                <label class="form-label" for="exampleFormControlInput">Key Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="key_name" type="text" name="key_name" placeholder=""  value="{{$details->key_name}}" />
                                @error('key_name')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('key_name') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="exampleFormControlInput">Value <span class="text-danger">*</span></label>
                                <input class="form-control" id="value" type="text" name="value" placeholder=""  value="{{$details->value}}" />
                                @error('value')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('value') }}</strong>
                                    </div>
                                @endif
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
        
    </script>
@endsection