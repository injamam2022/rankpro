
@extends('layouts.counsellor')


@section('css_after')
	<style type="text/css">
		
	</style>
@endsection

@section('content')
		<div class="container-xl px-5">
            <div class="d-flex mt-10 mb-4 align-items-center">
                <h1 class="page-header mb-0">Counsellor Profile</h1>
            </div>
            <div class="row gx-5">
                <div class="col-lg-9">
                    <form action="{{url('/')}}/counsellor/update-profile" method="post" enctype="multipart/form-data">
                        @csrf
                        <div id="material-textfield">
                            <div class="mb-3">
                                <label class="form-label" for="code">Code</label>
                                <div class="form-control" style="background-color: #e2eeed;">
                                    {{$details->code}}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <div class="form-control"  style="background-color: #e2eeed;">
                                    {{$details->email}}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" id="name" name="name" type="text" placeholder=""  value="{{$details->name}}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="phone_number">Phone Number</label>
                                <input class="form-control" id="phone_number" type="text" name="phone_number" placeholder=""  value="{{$details->phone_number}}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="address">Address</label>
                                <input class="form-control" id="address" type="text" name="address" placeholder=""  value="{{$details->address}}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="profile_icon">profile_icon</label>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input class="form-control" id="profile_icon" type="file" name="profile_icon" placeholder=""  value="{{$details->profile_icon}}" />
                                    </div>
                                    <div class="col-sm-4">
                                        @if($details->profile_icon)
                                            <img src="{{asset('')}}uploads/counsellor/thumbnail/{{$details->profile_icon}}" style="width:100px;">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
                            <div class="mb-10">
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