
@extends('layouts.backend')


@section('css_after')
	<style type="text/css">
		
	</style>
@endsection

@section('content')
		<div class="container-xl px-5">
            <div class="d-flex mt-10 mb-4 align-items-center">
                <h1 class="page-header mb-0">Profile</h1>
            </div>
            <div class="row gx-5">
                <div class="col-lg-9">
                    <form action="{{url('/')}}/admin/update-profile" method="post">
                        @csrf
                        <div id="material-textfield">
                            <div class="mb-3">
                                <label class="form-label" for="exampleFormControlInput">Name</label>
                                <input class="form-control" id="name" name="name" type="text" placeholder=""  value="{{$details->user_name}}" />
                                @error('name')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="exampleFormControlInput">Email</label>
                                <input class="form-control" id="email" type="email" name="email" placeholder=""  value="{{$details->login_email}}" readonly />
                                @error('email')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
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