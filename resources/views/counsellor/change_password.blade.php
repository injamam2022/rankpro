
@extends('layouts.counsellor')


@section('css_after')
	<style type="text/css">
		
	</style>
@endsection

@section('content')
		
        <div class="container-xl px-5">
            <div class="d-flex mt-10 mb-4 align-items-center">
                <h1 class="page-header mb-0">Change Password</h1>
            </div>
            <div class="row gx-5">
                <div class="col-lg-9">
                    <form action="{{url('/')}}/counsellor/profile/update-password" method="post">
                        @csrf
                        <div id="material-textfield">
                            <div class="mb-3">
                                <label class="form-label" for="current_password">Current Password</label>
                                <input class="form-control" id="current_password" name="current_password" type="password" placeholder="" />
                                @error('current_password')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">New Password</label>
                                <input class="form-control" id="password" name="password" type="password" placeholder="" />
                                @error('password')
                                    <div class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                <input class="form-control" id="confirm_password" name="confirm_password" type="password" placeholder="" />
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