@extends('site.lib.header')


@section('css_after')
@endsection

@section('content')
    <section id="formAll">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="formBlock">
                        <div class="formTitle">Create an account</div>
                        <div class="formText">Already Have an Account? <a href="{{ route('login') }}">Log in</a></div>

                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="formAvatar">
                                <div class="formAvatarImg">
                                    <img id="avatarPreview" src="https://randomuser.me/api/portraits/women/2.jpg"
                                        class="img-fluid" alt="">
                                    <img src="{{ asset('') }}web/img/cam_ic.png" class="img-fluid cam_ic"
                                        alt="" id="uploadTrigger">
                                </div>
                            </div>

                            <input type="file" name="profileImage" id="profileImage" accept="image/*"
                                style="display: none;">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInLeft">
                                        <label for="text">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name" placeholder="First Name"
                                            name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInRight">
                                        <label for="text">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name" placeholder="Last Name"
                                            name="last_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInLeft">
                                        <label for="number">Phone</label>
                                        <input type="number" class="form-control" id="student_mobile_number"
                                            placeholder="Phone" name="student_mobile_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInRight">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email_id" placeholder="Email"
                                            name="email_id" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group wow fadeInUp">
                                        <label for="text">Address</label>
                                        <input type="text" class="form-control" id="address" placeholder="Address"
                                            name="address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInLeft">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" placeholder="Password"
                                            name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInRight">
                                        <label for="password">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            placeholder="Confirm Password" name="password_confirmation" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInLeft">
                                        <label for="text">Father's Name</label>
                                        <input type="text" class="form-control" id="father_full_name"
                                            placeholder="Father's Name" name="father_full_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInRight">
                                        <label for="text">Father's Occupation</label>
                                        <input type="text" class="form-control" id="father_occupation"
                                            placeholder="Father's Occupation" name="father_occupation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInLeft">
                                        <label for="text">Mother's Name</label>
                                        <input type="text" class="form-control" id="mother_full_name"
                                            placeholder="Mother's Name" name="mother_full_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wow fadeInRight">
                                        <label for="text">Mother's Occupation</label>
                                        <input type="text" class="form-control" id="mother_occupation"
                                            placeholder="Mother's Occupation" name="mother_occupation">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="hiddenEmail" name="email_id" value="">
                            <div class="text-center wow fadeInUp">
                                <button type="button" class="btn btnRegister" id="registerBtn">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal otpPopupModal fade" id="otpPopup">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    <div class="otpPopupTitle">Shikkha</div>
                    <div class="otpPopupSubTitle">Enter OTP sent to <span id="otpEmail"></span></div>
                    {{-- <button class="Btn changeNumberBtn">Change Number</button> --}}

                    <div class="otpBlock">
                        <input type="text" maxlength="1" class="otp-input" />
                        <input type="text" maxlength="1" class="otp-input" />
                        <input type="text" maxlength="1" class="otp-input" />
                        <input type="text" maxlength="1" class="otp-input" />
                        <input type="text" maxlength="1" class="otp-input" />
                        <input type="text" maxlength="1" class="otp-input" />
                    </div>
                    <div class="otpError"></div>

                    <div class="otpresendText" id="otpResendText">Resend OTP in <span id="countdown">59</span>s</div>
                    <button class="Btn changeNumberBtn" id="resendOtpBtn" style="display: none;">Resend OTP</button>

                    <div class="text-center">
                        <button class="btn btnRegister mt-0" id="verifyOtpBtn">Verify OTP</button>
                    </div>

                    <div class="otpText">By continuing, you agree to Shikkha’s <a href="#">Terms &
                            Conditions</a> and <a href="#">Privacy Policy</a></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js_after')
    <script>
        $(document).ready(function() {
            $(".otp-input").on("keyup", function(e) {
                if ($(this).val().length === 1) {
                    $(this).next("input").focus();
                }
            });
            $(".otp-input").on("input", function() {
                if ($(this).val().length > 1) {
                    $(this).val($(this).val().charAt(0));
                }
            });
            $(".otp-input").on("keydown", function(e) {
                if (e.key === "Backspace") {
                    if ($(this).val().length === 0) {
                        $(this).prev("input").focus();
                    }
                }
            });

            let countdown = 59;
            let countdownTimer;

            function startCountdown() {
                countdownTimer = setInterval(function() {
                    countdown--;
                    $("#countdown").text(countdown);
                    if (countdown <= 0) {
                        clearInterval(countdownTimer);
                        $("#otpResendText").hide();
                        $("#resendOtpBtn").show();
                    }
                }, 1000);
            }

            startCountdown();
            let emailId = "";

            $("#registerBtn").click(function(e) {
                e.preventDefault();
                emailId = $("#email_id").val();
                $("#hiddenEmail").val(emailId);
                let formData = new FormData($("form")[0]);
            
                $.ajax({
                    url: "{{ route('admissionstore') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#registerBtn").text("Processing...").prop("disabled", true);
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#otpPopup").modal("show");
                            $("#otpEmail").text(emailId);
                        } else {
                            alert("Something went wrong. Please try again.");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message === "This email address is already registered.") {
                                $("#email_id").addClass("is-invalid");
                                $("#email_id").siblings(".invalid-feedback").remove();
                                $("#email_id").after('<div class="invalid-feedback">' + xhr.responseJSON.message + '</div>');
                            } else if (xhr.responseJSON.errors) {
                                $(".form-control").each(function() {
                                    let fieldName = $(this).attr("name");
                                    let error = xhr.responseJSON.errors[fieldName];
                                    if (error) {
                                        $(this).addClass("is-invalid");
                                        $(this).siblings(".invalid-feedback").remove();
                                        $(this).after('<div class="invalid-feedback">' + error[0] + '</div>');
                                    } else {
                                        $(this).removeClass("is-invalid");
                                    }
                                });
                            }
                        }
                        // Restore the button text and enable it again on error
                        $("#registerBtn").text("Register").prop("disabled", false);
                    },
                    complete: function() {
                        $("#registerBtn").text("Register").prop("disabled", false);
                    }
                });
            });

            $("#verifyOtpBtn").click(function(e) {
                e.preventDefault();
                let otp = $(".otpBlock input").map(function() {
                    return $(this).val();
                }).get().join('');

                $.ajax({
                    url: "{{ route('verifyOtp') }}",
                    type: "POST",
                    data: {
                        email_id: emailId,
                        otp: otp,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // console.log(response);

                        if (response.success) {
                            alert("OTP verified successfully!");
                            window.location.href = "{{ route('login') }}";
                        } else {
                            alert("Invalid OTP, please try again.");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $(".otpError").append('<div class="error-message" style="color: red; margin-top: 10px;">' + xhr.responseJSON.errors.otp[0] + '</div>');
                        } else {
                            $(".otpError").append('<div class="error-message" style="color: red; margin-top: 10px;">Invalid OTP, please try again.</div>');
                        }
                    }
                });
            });

            $('#uploadTrigger').on('click', function () {
                $('#profileImage').click();
            });

            $('#profileImage').on('change', function (event) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#avatarPreview').attr('src', e.target.result);
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            $("#resendOtpBtn").click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('resendOtp') }}",
                    type: "POST",
                    data: {
                        email_id: emailId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // console.log(response);

                        if (response.success) {
                            alert("OTP sent successfully!");
                            countdown = 58;
                            startCountdown();
                            $("#resendOtpBtn").hide();
                            $("#otpResendText").show();
                        } else {
                            alert("Error resending OTP, please try again.");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
@endsection
