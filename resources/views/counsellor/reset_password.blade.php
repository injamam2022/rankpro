
@extends('layouts.login')

<div class="row justify-content-center">
    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
        <div class="card card-raised shadow-10 mt-5 mt-xl-10 mb-4">
            <div class="card-body p-5">
                <!-- Auth header with logo image-->
                <div class="text-center">
                    <img class="mb-3" src="{{asset('admin/img/icons/background.svg')}}" alt="..." style="height: 48px" />
                    <h1 class="display-5 mb-0">Reset Password</h1>
                </div>
                <!-- Login submission form-->
                <form action="{{ route('admin.updateresetpassword')}}" method="post" onsubmit="return validateForm();">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <div class="mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" name="password" placeholder="" value="" />
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                        <input class="form-control" id="confirm_password" name="confirm_password" placeholder="" value="" />
                    </div>
                    <div class="mb-4">
                        <small id="error_message"></small>
                    </div>
                    <div class="form-group d-flex align-items-center mt-4 mb-0" style="justify-content: center !important;">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function validateForm() {
        var password = document.getElementById('password').value;
        var confirm_password = document.getElementById('confirm_password').value;

        document.getElementById('error_message').innerHTML = "";

        if(password){
            document.getElementById('first_name').classList.add('dangerBoader');
        }else{
            document.getElementById('first_name').classList.add('dangerBoader');
        }

        if(confirm_password){
            
        }

        if(confirm_password != password){
            document.getElementById('error_message').innerHTML = "Password and confirm password not matched";
        }

        return true;
    }
</script>