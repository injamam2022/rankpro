<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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

    <!-- bxslider -->
    <link rel="stylesheet" href="{{ asset('') }}web/css/jquery.bxslider.css">

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}web/css/style.css" rel="stylesheet">
    <link href="{{ asset('') }}web/css/dashboard.css" rel="stylesheet">
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

    <!-- dashboard -->
    <section id="dashboard">
      <div class="container-fluid">
          <div class="dashboardAll dashboardPh">
              <div class="dashboardLeft">
                  @include('site.include.student_left_menu')
              </div>
              <div class="dashboardRight">
                  <div class="dashboardRightBody">
                      <div class="row">
                          <div class="col-xl-12">
                              <div class="dashboardBlock">
                                  <div class="dashboardTitle">Report a problem</div>

                                  
                              </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-8">
                          <div class="testseriesDetailsRight testseriesBoxShadow">
                            <div class="feesBelowBlock">
                                <form action="{{ route('report_problem_save') }}" method="post" onsubmit="return validationFun()">
                                    @csrf
                                    
                                    <div class="subjectsTitle mt-4">
                                        <label class="form-label" for="name">Type</label>
                                        <select  class="form-control" id="type" name="type">
                                            <option value="">Select Type</option>
                                            @foreach($type_list as $value)
                                              <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="subjectsTitle mt-4">
                                        <label class="form-label" for="message">Message</label>
                                        <textarea class="form-control" id="message" name="message" rows="5"></textarea>
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

                                    <div class="subjectsTitle mt-4 text-center">
                                        <button type="submit" class="btn btn-buyNow">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </section>
    <!-- dashboard end -->


    
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
    <script src="{{ asset('') }}web/js/jquery.bxslider.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('') }}web/js/main.js"></script>

    <script>
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

      function validationFun(){

            var successFlag = true;
            var data = {};

            data.type = document.getElementById('type').value;
            if(!data.type){
              document.getElementById('type').classList.add('dangerBoader');
              successFlag = false;
            }else{
              document.getElementById('type').classList.remove('dangerBoader');
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