
@extends('layouts.login')

<div class="row justify-content-center">
    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
        <div class="card card-raised shadow-10 mt-5 mt-xl-10 mb-4">
            <div class="card-body p-5">
                <!-- Auth header with logo image-->
                <div class="text-center">
                    <img class="mb-3" src="{{asset('admin/img/icons/background.svg')}}" alt="..." style="height: 48px" />
                    <h1 class="display-5 mb-0">Ranker Login</h1>
                    <div class="subheading-1 mb-5">to continue to app</div>
                </div>
                <!-- Login submission form-->
                <form action="{{url('/')}}/rankers/dologin" method="post">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label" for="start_date">Email</label>
                        <input class="form-control" id="email" name="email" placeholder="" value="" />
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="start_date">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="" value="" />
                    </div>
                    <div class="d-flex align-items-center">
                        <mwc-formfield label="Remember password"><mwc-checkbox></mwc-checkbox></mwc-formfield>
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small fw-500 text-decoration-none" href="">Forgot Password?</a>
                        <button class="btn btn-primary" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>