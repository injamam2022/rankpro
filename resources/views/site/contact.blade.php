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
    <link rel="shortcut icon" href="{{ asset('') }}web/images/favicon.ico" type="image/x-icon">
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
    <style type="text/css">
        .dangerBoader{
            border: 1px solid red !important;
        }
        #otp_description {
            -moz-user-select: none;  
            -webkit-user-select: none;  
            -ms-user-select: none;  
            -o-user-select: none;  
            user-select: none;
        }
    </style>
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

    <section id="testseriesDetails" class="topbannerA">
        <div class="cusContainer">
            <div class="row">
                <div class="col-lg-5">
                    <div class="testseriesDetailsLeft">
                        <div class="footercont mb-4 mt-4">
                            <h1>Contact US</h1>
                        </div>
                        <div class="footercont">
                            @if(isset($footer_detail['contact_us']))
                                <h6 style="color: black;">Contact Us</h6>
                                <span style="color: black;">{{$footer_detail['contact_us']}}</span>
                            @endif
                            @if(isset($footer_detail['toll_free']))
                                <h6 style="color: black;">Toll Free</h6>
                                <span style="color: black;">{{$footer_detail['toll_free']}}</span>
                                <img src="{{ asset('') }}web/images/icons/footer_contact.png" alt="">
                            @endif
                        </div>
                        
                        @if(isset($footer_detail['email']))
                            <div class="footeremail">
                                <h6 style="color: black;">Email</h6>
                                <a href="mailto:{{$footer_detail['email']}}" style="color: black;">{{$footer_detail['email']}}</a>
                                <img src="{{ asset('') }}web/images/icons/footer_email.png" alt="">
                            </div>
                        @endif
                        @if(isset($footer_detail['address']))
                            <div class="footeradd">
                                <h6 style="color: black;">Address</h6>
                                <span style="color: black;">{{$footer_detail['address']}}</span>
                                <img src="{{ asset('') }}web/images/icons/footer_address.png" alt="">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-6">
                    <div class="testseriesDetailsRight testseriesBoxShadow">
                        <div class="feesBelowBlock">
                            <form action="{{ route('save_contact') }}" method="post" onsubmit="return validationFun()">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{$id}}">
                                <input type="hidden" name="type" id="type" value="{{$type}}">
                                <div class="subjectsTitle mt-4">
                                    <label class="form-label" for="name">Name</label>
                                    <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                                </div>
                                <div class="subjectsTitle mt-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input class="form-control" id="email" type="text" name="email" placeholder=""  value="" />
                                </div>
                                <div class="subjectsTitle mt-4">
                                    <label class="form-label" for="mobile_number">Mobile Number</label>
                                    <input class="form-control" id="mobile_number" type="text" name="mobile_number" placeholder=""  value="" />
                                </div>
                                <div class="subjectsTitle mt-4">
                                    <label class="form-label" for="message">Message</label>
                                    <input class="form-control" id="message" type="text" name="message" placeholder=""  value="" />
                                </div>
                                <div class="subjectsTitle mt-4">
                                    <div class="row">
                                        <div class="col-sm-6" id="otp_description" style="text-align: center;
                                                font-size: 30px;
                                                font-family: fantasy;
                                                font-style: italic;">
                                          <span style="" id="captcha_id"></span>
                                          <a href="javascript:void(0);" onclick="changeCapture();" style="margin-left: 10px;">
                                            <i class="bi bi-arrow-clockwise"></i>
                                          </a>
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control" id="captcha_text" type="text" name="captcha_text" placeholder=""  value="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="subjectsTitle mt-4">
                                    <button type="submit" class="btn btn-buyNow">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal otpPopupModal fade" id="successPopup" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form>
              <div class="check_success">
                <img src="{{ asset('') }}web/images/form/check_success.png" class="img-fluid" alt="">
              </div>
              <div class="otpPopupTitle">Alert!</div>
              <div class="otpPopupSubTitle">Please login before use.</div>

              <div class="text-center">
                <a href="{{ route('login') }}" class="btn btnRegister mt-0">Continue</a>
              </div>
            </form>
          </div>
        </div>
    </div>


    @include('site.include.footer')
    @include('site.include.call_to_action')
    @include('site.include.back_to_top')

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

    <script type="text/javascript">
        var g_captcha_id = "";
        function genRandomString(length){
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

        function changeCapture(){
            g_captcha_id = genRandomString(6);

            document.getElementById("captcha_id").innerHTML = g_captcha_id;
        }

        const pattern = /^[6-9]\d{9}$/;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        function validationFun(){

            var successFlag = true;
            var data = {};

            data.name = document.getElementById('name').value;
            if(!data.name){
              document.getElementById('name').classList.add('dangerBoader');
              successFlag = false;
            }else{
              document.getElementById('name').classList.remove('dangerBoader');
            }

            data.email = document.getElementById('email').value;
            if(!data.email){
              document.getElementById('email').classList.add('dangerBoader');
              successFlag = false;
            }else{
              if (emailPattern.test(data.email)) {
                document.getElementById('email').classList.remove('dangerBoader');
              }else{
                document.getElementById('email').classList.add('dangerBoader');
                successFlag = false;
              }
            }

            data.mobile_number = document.getElementById('mobile_number').value;
            if(!data.mobile_number){
              document.getElementById('mobile_number').classList.add('dangerBoader');
              successFlag = false;
            }else{
              if (pattern.test(data.mobile_number)) {
                document.getElementById('mobile_number').classList.remove('dangerBoader');
              }else{
                document.getElementById('mobile_number').classList.add('dangerBoader');
                successFlag = false;
              }
            }

            data.message = document.getElementById('message').value;
            if(!data.message){
              document.getElementById('message').classList.add('dangerBoader');
              successFlag = false;
            }else{
              document.getElementById('message').classList.remove('dangerBoader');
            }

            data.captcha_id = document.getElementById('captcha_text').value;
            if(data.captcha_id == g_captcha_id){
              document.getElementById('captcha_text').classList.remove('dangerBoader');
            }else{
              document.getElementById('captcha_text').classList.add('dangerBoader');
              successFlag = false;
            }

            console.log(successFlag);

            return successFlag;
        }

        changeCapture();
    </script>
</body>

</html>
