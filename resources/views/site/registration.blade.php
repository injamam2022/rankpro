<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta name="title" content="NEET AI Platform – Unlimited Free Mock Tests, Test Series & Find Your Mentor">
    <meta name="description" content="Prepare for NEET with AI-powered tools. Access unlimited free mock tests and full test series based on the latest NEET exam pattern. Get expert guidance, detailed analysis, and connect with top NEET mentors to boost your score.">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('') }}web/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('') }}web/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" rel="stylesheet">



    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('') }}web/bootstrap-5.0.2/css/bootstrap.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/style_a.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/form.css" rel="stylesheet">
    @include('site.include.head_meta')
</head>

<!-- <body style="background: url(images/fullindex.jpg) no-repeat center top;"> -->

<body>
    @include('site.include.body_meta')
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start px-lg-5 -->
    @include('site.include.header')
    <!-- Navbar End -->

    <section id="formAll" class="topbannerA">
        <div class="container authContainer">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="formBlock formBlockLogin authShell formBlockSignup">
                <div class="row align-items-stretch g-4">
                  <div class="col-lg-6">
                    <div class="loginLeft signupLeft">
                      <div class="formTitle text-left">Create an account</div>
                      <div class="formTexts text-left">Already have an account? <a href="{{ route('login') }}">Log in</a></div>
                      <div class="required-note text-start"><span class="req">*</span> Required fields</div>

                      <form class="registrationForm" id="registerForm" action="" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="formAvatar">
                          <div class="formAvatarImg">
                            <img src="{{ asset('web/images/form/signup-avatar.jpg') }}" class="img-fluid avatar-placeholder" alt="Upload profile photo" id="avatarPreview">
                          </div>
                          <img src="{{ asset('') }}web/images/form/cam_ic.png" class="img-fluid cam_ic" alt="" id="uploadTrigger">
                        </div>
                        <input type="file" name="profileImage" id="profileImage" accept="image/*" style="display: none;">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="first_name">First name <span class="req" aria-hidden="true">*</span></label>
                              <input type="text" class="form-control" id="first_name" placeholder="First name" name="first_name" autocomplete="given-name" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="last_name">Last name <span class="req" aria-hidden="true">*</span></label>
                              <input type="text" class="form-control" id="last_name" placeholder="Last name" name="last_name" autocomplete="family-name" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="mobile_number">Phone <span class="req" aria-hidden="true">*</span></label>
                              <input type="tel" class="form-control" id="mobile_number" placeholder="10-digit phone number" name="mobile_number" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" autocomplete="tel" required>
                              <div class="field-hint">We'll send an OTP to this number for verification.</div>
                              <label class="wa-check" for="is_whatsapp">
                                  <input class="form-check-input" type="checkbox" id="is_whatsapp" name="is_whatsapp">
                                  <span>WhatsApp is available on this number</span>
                              </label>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="email_id">Email <span class="req" aria-hidden="true">*</span></label>
                              <input type="email" class="form-control" id="email_id" placeholder="Email" name="email_id" autocomplete="email" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="password">Password <span class="req" aria-hidden="true">*</span></label>
                              <div class="auth-input has-toggle">
                                <span class="field-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" placeholder="At least 4 characters" name="password" minlength="4" autocomplete="new-password" required>
                                <button type="button" class="pw-toggle" data-target="password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="password_confirmation">Confirm password <span class="req" aria-hidden="true">*</span></label>
                              <div class="auth-input has-toggle">
                                <span class="field-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm password" name="password_confirmation" minlength="4" autocomplete="new-password" required>
                                <button type="button" class="pw-toggle" data-target="password_confirmation" aria-label="Show password"><i class="bi bi-eye"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="father_full_name">Father's name</label>
                              <input type="text" class="form-control" id="father_full_name" placeholder="Father's name" name="father_full_name">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="father_mobile_number">Father's contact no</label>
                              <input type="tel" class="form-control" id="father_mobile_number" placeholder="Father's contact no" name="father_mobile_number" inputmode="numeric">
                            </div>
                          </div>
                        </div>
                        <div class="text-center">
                          <button type="button" class="btn btnRegister" id="registerBtn">Register</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="loginRight">
                      <div class="authVisual authVisualSignup">
                        <img src="{{ asset('web/images/form/neet-auth-side.jpg') }}" class="img-fluid" alt="NEET exam preparation">
                        <div class="authVisualMsg">
                          <span class="authVisualTag">Join RankPro</span>
                          <h3>Start your NEET journey today</h3>
                          <p>Get mock tests, detailed analysis, and mentor support built for NEET aspirants.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>


    @include('site.include.footer')
    @include('site.include.call_to_action')
    @include('site.include.back_to_top')

    <div class="modal otpPopupModal fade" id="otpPopup" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form>
              <div class="otpPopupTitle">RankPro</div>
              <div class="otpPopupSubTitle">Enter OTP sent to <span id="otpPhoneNumber"></span></div>
              <button type="button" class="Btn changeNumberBtn"  data-bs-dismiss="modal">Change Number</button>

              <div class="otpBlock">
                <input type="text" maxlength="1" class="otp-input" />
                <input type="text" maxlength="1" class="otp-input" />
                <input type="text" maxlength="1" class="otp-input" />
                <input type="text" maxlength="1" class="otp-input" />
              </div>

              <div class="otpresendText" id="otpResendText">Resend OTP in 58s</div>

              <div class="text-center">
                <button class="btn btnRegister mt-0 w-100" id="verifyOtpBtn">Verify</button>
              </div>
              <small id="otpError"></small>

              <div class="otpText">By continuing, you agree to RankPro’s <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></div>
            </form>
          </div>
        </div>
    </div>

    <div class="modal otpPopupModal fade" id="successPopup" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form>
              <div class="check_success">
                <img src="{{ asset('') }}web/images/form/check_success.png" class="img-fluid" alt="">
              </div>
              <div class="otpPopupTitle">Awesome!</div>
              <div class="otpPopupSubTitle">Congratulations, your account has been successfully created.</div>

              <div class="text-center">
                <a href="{{ route('login') }}" class="btn btnRegister mt-0">Continue</a>
              </div>
            </form>
          </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}web/lib/wow/wow.min.js"></script>
    <script src="{{ asset('') }}web/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('') }}web/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('') }}web/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>
    <script>
        $(document).ready(function () {

            var sectionIds = $('a.list-group-item');

            $(document).scroll(function () {
                sectionIds.each(function () {

                    var container = $(this).attr('href');
                    var containerOffset = $(container).offset().top;
                    var containerHeight = $(container).outerHeight();
                    var containerBottom = containerOffset + containerHeight;
                    var scrollPosition = $(document).scrollTop();

                    if (scrollPosition < containerBottom - 20 && scrollPosition >= containerOffset - 20) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
            });
        });
    </script>

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
            var g_user_id = "";

            $(document).on("click", ".pw-toggle", function () {
                var input = $("#" + $(this).data("target"));
                var icon = $(this).find("i");
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("bi-eye").addClass("bi-eye-slash");
                    $(this).attr("aria-label", "Hide password");
                } else {
                    input.attr("type", "password");
                    icon.removeClass("bi-eye-slash").addClass("bi-eye");
                    $(this).attr("aria-label", "Show password");
                }
            });

            function showFieldError(selector, message) {
                var field = $(selector);
                field.addClass("is-invalid");
                var target = field.closest(".auth-input");
                if (!target.length) {
                    target = field;
                }
                target.siblings(".invalid-feedback").remove();
                target.after('<div class="invalid-feedback d-block">' + message + '</div>');
            }

            $("#registerBtn").click(function(e) {
                e.preventDefault();
                var form = document.getElementById("registerForm");
                $(".registrationForm .is-invalid").removeClass("is-invalid");
                $(".registrationForm .invalid-feedback").remove();

                if (!form.checkValidity()) {
                    if (!$("#first_name").val().trim()) {
                        showFieldError("#first_name", "First name is required.");
                    }
                    if (!$("#last_name").val().trim()) {
                        showFieldError("#last_name", "Last name is required.");
                    }
                    if (!/^[0-9]{10}$/.test($("#mobile_number").val().trim())) {
                        showFieldError("#mobile_number", "Enter a valid 10-digit phone number.");
                    }
                    if (!$("#email_id").val().trim() || !$("#email_id")[0].checkValidity()) {
                        showFieldError("#email_id", "Enter a valid email address.");
                    }
                    if ($("#password").val().length < 4) {
                        showFieldError("#password", "Password must be at least 4 characters.");
                    }
                    if (!$("#password_confirmation").val()) {
                        showFieldError("#password_confirmation", "Confirm your password.");
                    }
                    var firstInvalid = $(".registrationForm .is-invalid").first();
                    if (firstInvalid.length) {
                        firstInvalid.trigger("focus");
                    }
                    return;
                }

                if ($("#password").val() !== $("#password_confirmation").val()) {
                    showFieldError("#password_confirmation", "Passwords do not match.");
                    $("#password_confirmation").trigger("focus");
                    return;
                }

                emailId = $("#email_id").val();
                $("#otpPhoneNumber").text($("#mobile_number").val());
                let formData = new FormData(form);
            
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
                        console.log(response);
                        if (response.success) {
                            $("#otpPopup").modal("show");
                            $("#otpEmail").text(emailId);
                            g_user_id = response.user_id;
                        } else {
                            alert("Something went wrong. Please try again.");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message === "This email address is already registered.") {
                                showFieldError("#email_id", xhr.responseJSON.message);
                            } else if (xhr.responseJSON.message === "This phone number is already registered.") {
                                showFieldError("#mobile_number", xhr.responseJSON.message);
                            } else if (xhr.responseJSON.errors) {
                                $(".registrationForm .form-control").each(function() {
                                    let fieldName = $(this).attr("name");
                                    let error = xhr.responseJSON.errors[fieldName];
                                    if (error) {
                                        showFieldError(this, error[0]);
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
                        user_id: g_user_id,
                        otp: otp,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // console.log(response);

                        if (response.success) {
                            $("#otpPopup").modal("hide");
                            $("#successPopup").modal("show");
                            // window.location.href = "{{ route('login') }}";
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
                    $('#avatarPreview').attr('src', e.target.result).removeClass('avatar-placeholder');
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
</body>

</html>