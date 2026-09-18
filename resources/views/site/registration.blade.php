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
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-10">
              <div class="formBlock">
                <div class="formTitle">Create an account</div>
                <div class="formTexts">Already Have an Account? <a href="{{ route('login') }}">Log in</a></div>

                <form class="registrationForm" action="" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="formAvatar">
                    <div class="formAvatarImg">
                      <img src="https://randomuser.me/api/portraits/women/2.jpg" class="img-fluid" alt="" id="avatarPreview">
                    </div>
                    <img src="{{ asset('') }}web/images/form/cam_ic.png" class="img-fluid cam_ic" alt="" id="uploadTrigger">
                  </div>
                  <input type="file" name="profileImage" id="profileImage" accept="image/*"
                                style="display: none;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">First Name</label>
                        <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Last Name</label>
                        <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="number">Phone (we will send you OTP for verification)</label>
                        <input type="number" class="form-control" id="mobile_number" placeholder="Phone" name="mobile_number">
                        
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" id="is_whatsapp" name="is_whatsapp"> Is Whatsapp available on this number?
                        </label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email_id" placeholder="Email" name="email_id">
                      </div>
                    </div>
                    <!-- <div class="col-md-12">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Address (as per Adhaar)</label>
                        <input type="text" class="form-control" id="address" placeholder="Address" name="address">
                      </div>
                    </div> -->
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="password">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Father's Name</label>
                        <input type="text" class="form-control" id="father_full_name" placeholder="Father's Name" name="father_full_name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Father's Contact No</label>
                        <input type="text" class="form-control" id="father_mobile_number" placeholder="Father's Contact No" name="father_mobile_number">
                      </div>
                    </div>
                    <!-- <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Father's Occupation</label>
                        <input type="text" class="form-control" id="father_occupation" placeholder="Father's Occupation" name="father_occupation">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Father's Contact No</label>
                        <input type="text" class="form-control" id="father_mobile_number" placeholder="Father's Contact No" name="father_mobile_number">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Father's Qualification</label>
                        <input type="text" class="form-control" id="father_qualification" placeholder="Father's Qualification" name="father_qualification">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Mother's Name</label>
                        <input type="text" class="form-control" id="mother_full_name" placeholder="Mother's Name" name="mother_full_name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Mother's Occupation</label>
                        <input type="text" class="form-control" id="mother_occupation" placeholder="Mother's Occupation" name="mother_occupation">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Mother's Contact No</label>
                        <input type="text" class="form-control" id="mother_mobile_number" placeholder="Mother's Contact No" name="mother_mobile_number">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Mother's Qualification</label>
                        <input type="text" class="form-control" id="mother_qualification" placeholder="Mother's Qualification" name="mother_qualification">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group wow fadeInUp">
                        <label class="labelText">Read <a href="#">Terms & Conditions of RankPro</a></label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Qualification Details</label>
                        <input type="text" class="form-control" id="qualification_details" placeholder="Qualification Details" name="qualification_details">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Certificate</label>
                        <input type="file" class="form-control" id="certificate" placeholder="Drag and Drop files here" name="certificate" style="height: auto;">
                        <label>PDF or JPG max upload file size: 1mb</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Guardian Signature</label>
                        <input type="file" class="form-control" id="guardian_signature" placeholder="Drag and Drop files here" name="guardian_signature" style="height: auto;">
                        <label>PDF or JPG max upload file size: 50kb</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group wow fadeInUp">
                        <label for="text">Student Signature</label>
                        <input type="file" class="form-control" id="student_signature" placeholder="Drag and Drop files here" name="student_signature" style="height: auto;">
                        <label>PDF or JPG max upload file size: 50kb</label>
                      </div>
                    </div> -->
                  </div>
                  <div class="text-center wow fadeInUp">
                    <div class="btn btnRegister" id="registerBtn">Register</div>
                  </div>
                </form>
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

            $("#registerBtn").click(function(e) {
                e.preventDefault();
                emailId = $("#email_id").val();
                $("#otpPhoneNumber").text($("#mobile_number").val());
                // $("#hiddenEmail").val(emailId);
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
                                $("#email_id").addClass("is-invalid");
                                $("#email_id").siblings(".invalid-feedback").remove();
                                $("#email_id").after('<div class="invalid-feedback">' + xhr.responseJSON.message + '</div>');
                            }else if (xhr.responseJSON.message === "This phone number is already registered.") {
                                $("#mobile_number").addClass("is-invalid");
                                $("#mobile_number").siblings(".invalid-feedback").remove();
                                $("#mobile_number").after('<div class="invalid-feedback">' + xhr.responseJSON.message + '</div>');
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
</body>

</html>