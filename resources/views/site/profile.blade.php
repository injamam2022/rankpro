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
    <link href="{{ asset('') }}web/css/form.css" rel="stylesheet">

    <style type="text/css">
      .dangerBoader{
        border: 1px solid red !important;
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
                                  <div class="dashboardTitle">Profile</div>
                                  <div class="dashboardEmail">{{$user->email_id}}</div>

                                  <form class="registrationForm" action="{{route('update_profile')}}" method="POST" enctype="multipart/form-data" onsubmit="return validationFun();">
                                    @csrf
                                    <div class="formAvatar text-center">
                                      <div class="formAvatarImg">
                                          @if($user->profile_img)
                                            <img src="{{asset('')}}uploads/profileImage/{{$user->profile_img}}" class="img-fluid" alt="" id="avatarPreview">
                                          @else
                                            <img src="https://randomuser.me/api/portraits/women/2.jpg" class="img-fluid" alt="" id="avatarPreview">
                                          @endif
                                        
                                      </div>
                                      <img src="{{ asset('') }}web/images/form/cam_ic.png" class="img-fluid cam_ic" alt="" id="uploadTrigger">
                                    </div>
                                    <input type="file" name="profileImage" id="profileImage" accept="image/*"
                                                  style="display: none;">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="first_name">First Name <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{$user->first_name}}">
                                          <!-- <small class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Last Name <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="{{$user->last_name}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="number">Phone</label>
                                          <div class="form-control">
                                            {{$user->mobile_number}}
                                          </div>
                                          <label class="form-check-label">
                                              <input class="form-check-input" type="checkbox" id="is_whatsapp" name="is_whatsapp"> Is Whatsapp available on this number?
                                          </label>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="email">Email</label>
                                          <div class="form-control">
                                            {{$user->email_id}}
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text">Address (as per Adhaar) <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="{{$user->address}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Facebook Link</label>
                                          <input type="text" class="form-control" id="facebook_link" placeholder="Facebook Link" name="facebook_link" value="{{$user->facebook_link}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Instagram Link</label>
                                          <input type="text" class="form-control" id="instagram_link" placeholder="Instagram Link" name="instagram_link" value="{{$user->instagram_link}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Youtube Link</label>
                                          <input type="text" class="form-control" id="youtube_link" placeholder="Youtube Link" name="youtube_link" value="{{$user->youtube_link}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">X Link</label>
                                          <input type="text" class="form-control" id="twitter_link" placeholder="X Link" name="twitter_link" value="{{$user->twitter_link}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Whats App Link</label>
                                          <input type="text" class="form-control" id="whats_app_link" placeholder="Whats App Link" name="whats_app_link" value="{{$user->whats_app_link}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Linkedin Link</label>
                                          <input type="text" class="form-control" id="linkedin_link" placeholder="Linkedin Link" name="linkedin_link" value="{{$user->linkedin_link}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Father's Name</label>
                                          <div class="form-control">
                                            {{$user->father_full_name}}
                                          </div>
                                        </div>
                                      </div>
                                     <!--  <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Father's Occupation</label>
                                          <input type="text" class="form-control" id="father_occupation" placeholder="Father's Occupation" name="father_occupation" value="{{$user->father_occupation}}">
                                        </div>
                                      </div> -->
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Father's Contact No</label>
                                          <div class="form-control">
                                            {{$user->father_mobile_number}}
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Father's Qualification</label>
                                          <input type="text" class="form-control" id="father_qualification" placeholder="Father's Qualification" name="father_qualification" value="{{$user->father_qualification}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Mother's Name</label>
                                          <input type="text" class="form-control" id="mother_full_name" placeholder="Mother's Name" name="mother_full_name" value="{{$user->mother_full_name}}">
                                        </div>
                                      </div>
                                      <!-- <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Mother's Occupation</label>
                                          <input type="text" class="form-control" id="mother_occupation" placeholder="Mother's Occupation" name="mother_occupation" value="{{$user->mother_occupation}}">
                                        </div>
                                      </div> -->
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Mother's Contact No</label>
                                          <input type="text" class="form-control" id="mother_mobile_number" placeholder="Mother's Contact No" name="mother_mobile_number" value="{{$user->mother_mobile_number}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Mother's Qualification</label>
                                          <input type="text" class="form-control" id="mother_qualification" placeholder="Mother's Qualification" name="mother_qualification" value="{{$user->mother_qualification}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Qualification Details</label>
                                          <input type="text" class="form-control" id="qualification_details" placeholder="Qualification Details" name="qualification_details" value="{{$user->qualification_details}}">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Certificate</label>
                                          <div class="row">
                                            <div class="col-md-10">
                                                <input type="file" class="form-control" id="certificate" placeholder="Drag and Drop files here" name="certificate" accept=".pdf, .jpg, application/pdf, image/jpg">
                                                <label>PDF or JPG max upload file size: 1mb</label>
                                            </div>
                                            <div class="col-md-2">
                                              @if($user->certificate)
                                                <a href="{{ asset('' . $user->certificate) }}" target="_blank" style="margin-left: 10px;">
                                                    View
                                                </a>
                                              @endif
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <!-- <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Guardian Signature</label>
                                          <div class="row">
                                            <div class="col-md-10">
                                                <input type="file" class="form-control" id="guardian_signature" placeholder="Drag and Drop files here" name="guardian_signature" accept=".pdf, .jpg, application/pdf, image/jpg">
                                                <label>PDF or JPG max upload file size: 50kb</label>
                                            </div>
                                            <div class="col-md-2">
                                              @if($user->guardian_signature)
                                                <a href="{{ asset('' . $user->guardian_signature) }}" target="_blank" style="margin-left: 10px;">
                                                    View
                                                </a>
                                              @endif
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="text">Student Signature</label>
                                          <div class="row">
                                            <div class="col-md-10">
                                                <input type="file" class="form-control" id="student_signature" placeholder="Drag and Drop files here" name="student_signature" accept=".pdf, .jpg, application/pdf, image/jpg">
                                                <label>PDF or JPG max upload file size: 50kb</label>
                                            </div>
                                            <div class="col-md-2">
                                              @if($user->student_signature)
                                                <a href="{{ asset('' . $user->student_signature) }}" target="_blank" style="margin-left: 10px;">
                                                    View
                                                </a>
                                              @endif
                                            </div>
                                          </div>
                                        </div>
                                      </div> -->
                                    </div>
                                    <div class="text-center">
                                      <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                  </form>
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
      
      const allowedTypes = ["application/pdf", "image/jpeg"];
      const maxSize1 = 1024 * 1024; // 2MB in bytes
      const maxSize2 = 512 * 1024; // 2MB in bytes
      $('#uploadTrigger').on('click', function () {
          $('#profileImage').click();
      });

      var fileFlag = {
        "certificate":{{($user->certificate)?1:0}},
        "guardian_signature":{{($user->guardian_signature)?1:0}},
        "student_signature":{{($user->student_signature)?1:0}}
      }

      function validationFun(){
        const pattern = /^[6-9]\d{9}$/;

        var successFlag = true;
        var data = {};

        data.first_name = document.getElementById('first_name').value;
        if(!data.first_name){
          document.getElementById('first_name').classList.add('dangerBoader');
          successFlag = false;
        }else{
          document.getElementById('first_name').classList.remove('dangerBoader');
        }
        data.last_name = document.getElementById('last_name').value;
        if(!data.last_name){
          document.getElementById('last_name').classList.add('dangerBoader');
          successFlag = false;
        }else{
          document.getElementById('last_name').classList.remove('dangerBoader');
        }
        data.address = document.getElementById('address').value;
        if(!data.address){
          document.getElementById('address').classList.add('dangerBoader');
          successFlag = false;
        }else{
          document.getElementById('address').classList.remove('dangerBoader');
        }
        // data.father_full_name = document.getElementById('father_full_name').value;
        // if(!data.father_full_name){
        //   document.getElementById('father_full_name').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   document.getElementById('father_full_name').classList.remove('dangerBoader');
        // }
        // data.father_occupation = document.getElementById('father_occupation').value;
        // if(!data.father_occupation){
        //   document.getElementById('father_occupation').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   document.getElementById('father_occupation').classList.remove('dangerBoader');
        // }
        // data.father_mobile_number = document.getElementById('father_mobile_number').value;
        // if(!data.father_mobile_number){
        //   document.getElementById('father_mobile_number').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   if (pattern.test(data.father_mobile_number)) {
        //     document.getElementById('father_mobile_number').classList.remove('dangerBoader');
        //   }else{
        //     document.getElementById('father_mobile_number').classList.add('dangerBoader');
        //     successFlag = false;
        //   }
        // }
        // data.father_qualification = document.getElementById('father_qualification').value;
        // if(!data.father_qualification){
        //   document.getElementById('father_qualification').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   document.getElementById('father_qualification').classList.remove('dangerBoader');
        // }
        // data.mother_full_name = document.getElementById('mother_full_name').value;
        // if(!data.mother_full_name){
        //   document.getElementById('mother_full_name').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   document.getElementById('mother_full_name').classList.remove('dangerBoader');
        // }
        // data.mother_occupation = document.getElementById('mother_occupation').value;
        // if(!data.mother_occupation){
        //   document.getElementById('mother_occupation').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   document.getElementById('mother_occupation').classList.remove('dangerBoader');
        // }
        // data.mother_mobile_number = document.getElementById('mother_mobile_number').value;
        // if(!data.mother_mobile_number){
        //   document.getElementById('mother_mobile_number').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   if (pattern.test(data.mother_mobile_number)) {
        //     document.getElementById('mother_mobile_number').classList.remove('dangerBoader');
        //   }else{
        //     document.getElementById('mother_mobile_number').classList.add('dangerBoader');
        //     successFlag = false;
        //   }
        // }
        // data.mother_qualification = document.getElementById('mother_qualification').value;
        // if(!data.mother_qualification){
        //   document.getElementById('mother_qualification').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   document.getElementById('mother_qualification').classList.remove('dangerBoader');
        // }
        // data.qualification_details = document.getElementById('qualification_details').value;
        // if(!data.qualification_details){
        //   document.getElementById('qualification_details').classList.add('dangerBoader');
        //   successFlag = false;
        // }else{
        //   document.getElementById('qualification_details').classList.remove('dangerBoader');
        // }

        // var certificate = document.getElementById("certificate");
        // var file = certificate.files[0];

        // if(fileFlag.certificate){
        //   if(file){
        //     if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
        //       document.getElementById('certificate').classList.add('dangerBoader');
        //       successFlag = false;
        //     }else{
        //       document.getElementById('certificate').classList.remove('dangerBoader');
        //     }
        //   }else{
        //     document.getElementById('certificate').classList.remove('dangerBoader');
        //   }
        // }else{
        //   if(file){
        //     if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
        //       document.getElementById('certificate').classList.add('dangerBoader');
        //       successFlag = false;
        //     }else{
        //       document.getElementById('certificate').classList.remove('dangerBoader');
        //     }
        //   }else{
        //     document.getElementById('certificate').classList.add('dangerBoader');
        //     successFlag = false;
        //   }          
        // }

        /*var guardian_signature = document.getElementById("guardian_signature");
        var file = guardian_signature.files[0];

        if(fileFlag.guardian_signature){
          if(file){
            if (!allowedTypes.includes(file.type) || file.size > maxSize2) {
              document.getElementById('guardian_signature').classList.add('dangerBoader');
              successFlag = false;
            }else{
              document.getElementById('guardian_signature').classList.remove('dangerBoader');
            }
          }else{
            document.getElementById('guardian_signature').classList.remove('dangerBoader');
          }
        }else{
          if(file){
            if (!allowedTypes.includes(file.type) || file.size > maxSize2) {
              document.getElementById('guardian_signature').classList.add('dangerBoader');
              successFlag = false;
            }else{
              document.getElementById('guardian_signature').classList.remove('dangerBoader');
            }
          }else{
            document.getElementById('guardian_signature').classList.add('dangerBoader');
            successFlag = false;
          }
        }
        

        var student_signature = document.getElementById("student_signature");
        var file = student_signature.files[0];

        if(fileFlag.student_signature){
          if(file){
            if (!allowedTypes.includes(file.type) || file.size > maxSize2) {
              document.getElementById('student_signature').classList.add('dangerBoader');
              successFlag = false;
            }else{
              document.getElementById('student_signature').classList.remove('dangerBoader');
            }
          }else{
            document.getElementById('student_signature').classList.remove('dangerBoader');
          }
        }else{
          if(file){
            if (!allowedTypes.includes(file.type) || file.size > maxSize2) {
              document.getElementById('student_signature').classList.add('dangerBoader');
              successFlag = false;
            }else{
              document.getElementById('student_signature').classList.remove('dangerBoader');
            }
          }else{
            document.getElementById('student_signature').classList.add('dangerBoader');
            successFlag = false;
          }
        }*/
        

        return successFlag;
      }
    </script>
</body>

</html>