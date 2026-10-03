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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">

    <style type="text/css">
      .dangerBoader{
        border: 1px solid red !important;
      }
      .profileCropModal .modal-dialog {
        max-width: 560px;
      }
      .profileCropModal .modal-content {
        border-radius: 14px;
        border: none;
        overflow: hidden;
      }
      .profileCropModal .modal-header {
        border-bottom: 1px solid #ececec;
        padding: 14px 18px;
      }
      .profileCropModal .modal-title {
        font-weight: 600;
        font-size: 18px;
      }
      .profileCropModal .modal-body {
        padding: 16px 18px 8px;
      }
      .profileCropStage {
        width: 100%;
        height: min(52vh, 360px);
        background: #1f1f1f;
        border-radius: 10px;
        overflow: hidden;
      }
      .profileCropStage img {
        display: block;
        max-width: 100%;
      }
      .profileCropTools {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-top: 14px;
      }
      .profileCropTools .btn {
        min-width: 44px;
        border-radius: 8px;
      }
      .profileCropHint {
        text-align: center;
        color: #666;
        font-size: 13px;
        margin: 10px 0 0;
      }
      .profileCropModal .modal-footer {
        border-top: 1px solid #ececec;
        padding: 12px 18px 16px;
        gap: 8px;
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
                                  <div class="dashboardEmail">
                                    RankPro ID: {{ $user->rankpro_id ?: $user->id }}
                                    @if($user->email_id) &nbsp;|&nbsp; {{ $user->email_id }} @endif
                                  </div>
                                  @if(!empty($student_batches) && $student_batches->count())
                                    <div class="mb-3" style="display:flex;flex-wrap:wrap;gap:8px;">
                                      @foreach($student_batches as $batch)
                                        <span class="badge rounded-pill" style="background:#eef2ff;color:#3b4cca;font-weight:500;padding:6px 12px;">{{ $batch->name }}</span>
                                      @endforeach
                                    </div>
                                  @endif

                                  <form class="registrationForm" action="{{route('update_profile')}}" method="POST" enctype="multipart/form-data" onsubmit="return validationFun();">
                                    @csrf
                                    <div class="formAvatar text-center">
                                      <div class="formAvatarImg" id="avatarClickArea" role="button" tabindex="0" title="Change profile picture" style="cursor:pointer;">
                                          @php
                                            $profileImgName = $user->profile_img ? basename(str_replace('\\', '/', $user->profile_img)) : '';
                                            $profileImgUrl = $profileImgName !== ''
                                              ? asset('uploads/profileImage/'.$profileImgName)
                                              : asset('web/images/dashboardCheck.png');
                                          @endphp
                                          <img src="{{ $profileImgUrl }}" class="img-fluid" alt="Profile" id="avatarPreview" @if($profileImgName === '') style="opacity:.4;object-fit:contain;padding:18px;" @endif>
                                      </div>
                                      <img src="{{ asset('') }}web/images/form/cam_ic.png" class="img-fluid cam_ic" alt="Upload" id="uploadTrigger" title="Change profile picture">
                                    </div>
                                    <input type="file" name="profileImage" id="profileImage" accept="image/png,image/jpeg,image/jpg,image/webp,image/gif" style="display: none;">
                                    @error('profileImage')
                                      <div class="text-danger text-center mb-2">{{ $message }}</div>
                                    @enderror
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
                                          <label for="mobile_number">Phone</label>
                                          <input type="text" class="form-control" id="mobile_number" value="{{ $user->mobile_number }}" placeholder="Not set" readonly disabled>
                                          <label class="form-check-label">
                                              <input class="form-check-input" type="checkbox" id="is_whatsapp" name="is_whatsapp" value="1" @checked((int) $user->is_whatsapp === 1)> Is Whatsapp available on this number?
                                          </label>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="email_id">Email</label>
                                          <input type="text" class="form-control" id="email_id" value="{{ $user->email_id }}" placeholder="Not set" readonly disabled>
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
                                          <label for="father_full_name">Father's Name</label>
                                          <input type="text" class="form-control" id="father_full_name" value="{{ $user->father_full_name }}" placeholder="Not set" readonly disabled>
                                          <small class="form-text text-muted">Contact support to update this field.</small>
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
                                          <label for="father_mobile_number">Father's Contact No</label>
                                          <input type="text" class="form-control" id="father_mobile_number" value="{{ $user->father_mobile_number }}" placeholder="Not set" readonly disabled>
                                          <small class="form-text text-muted">Used for parent login — contact support to update.</small>
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

                                    <div class="mt-4 mb-2">
                                      <div class="dashboardTitle" style="font-size:18px;">Change Password</div>
                                      <div class="dashboardEmail" style="font-size:13px;">Leave blank if you do not want to change your password.</div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="current_password">Current Password</label>
                                          <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password" autocomplete="current-password">
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="new_password">New Password</label>
                                          <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" autocomplete="new-password">
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="new_password_confirmation">Confirm New Password</label>
                                          <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm New Password" autocomplete="new-password">
                                        </div>
                                      </div>
                                    </div>
                                    @error('current_password')
                                      <div class="alert alert-danger py-2">{{ $message }}</div>
                                    @enderror
                                    @error('new_password')
                                      <div class="alert alert-danger py-2">{{ $message }}</div>
                                    @enderror

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

    <div class="modal fade profileCropModal" id="profileCropModal" tabindex="-1" aria-labelledby="profileCropModalLabel" aria-hidden="true" data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="profileCropModalLabel">Edit profile photo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="profileCropClose"></button>
          </div>
          <div class="modal-body">
            <div class="profileCropStage">
              <img id="profileCropImage" alt="Crop preview">
            </div>
            <div class="profileCropTools">
              <button type="button" class="btn btn-outline-secondary" id="profileCropZoomIn" title="Zoom in"><i class="fas fa-search-plus"></i></button>
              <button type="button" class="btn btn-outline-secondary" id="profileCropZoomOut" title="Zoom out"><i class="fas fa-search-minus"></i></button>
              <button type="button" class="btn btn-outline-secondary" id="profileCropRotateLeft" title="Rotate left"><i class="fas fa-undo"></i></button>
              <button type="button" class="btn btn-outline-secondary" id="profileCropRotateRight" title="Rotate right"><i class="fas fa-redo"></i></button>
              <button type="button" class="btn btn-outline-secondary" id="profileCropReset" title="Reset"><i class="fas fa-sync-alt"></i></button>
            </div>
            <p class="profileCropHint">Drag to reposition. Use zoom &amp; rotate to fit your face in the circle.</p>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="profileCropCancel">Cancel</button>
            <button type="button" class="btn btn-primary" id="profileCropApply">Use photo</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
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
      var profileCropper = null;
      var profileCropModal = null;
      var profileObjectUrl = null;
      var profileCropApplied = false;
      var committedProfileFile = null;

      function destroyProfileCropper() {
          if (profileCropper) {
              profileCropper.destroy();
              profileCropper = null;
          }
          if (profileObjectUrl) {
              URL.revokeObjectURL(profileObjectUrl);
              profileObjectUrl = null;
          }
          $('#profileCropImage').attr('src', '');
      }

      function setProfileInputFile(file) {
          var input = document.getElementById('profileImage');
          if (!input) return false;
          try {
              var dt = new DataTransfer();
              if (file) {
                  dt.items.add(file);
              }
              input.files = dt.files;
              return true;
          } catch (err) {
              return false;
          }
      }

      function openProfilePicker() {
          $('#profileImage').trigger('click');
      }

      $('#uploadTrigger, #avatarClickArea').on('click', openProfilePicker);
      $('#avatarClickArea').on('keydown', function (e) {
          if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              openProfilePicker();
          }
      });

      $('#profileImage').on('change', function () {
          var file = this.files && this.files[0];
          if (!file) return;
          if (!/^image\//.test(file.type)) {
              alert('Please choose an image file (JPG, PNG, WEBP, or GIF).');
              setProfileInputFile(committedProfileFile);
              return;
          }
          if (file.size > 5 * 1024 * 1024) {
              alert('Profile image must be 5MB or smaller.');
              setProfileInputFile(committedProfileFile);
              return;
          }

          // Keep picker empty until user confirms crop; restore prior file if they cancel.
          this.value = '';
          destroyProfileCropper();
          profileCropApplied = false;
          profileObjectUrl = URL.createObjectURL(file);
          $('#profileCropImage').attr('src', profileObjectUrl);

          if (!profileCropModal) {
              profileCropModal = new bootstrap.Modal(document.getElementById('profileCropModal'));
          }
          profileCropModal.show();
      });

      $('#profileCropModal').on('shown.bs.modal', function () {
          var image = document.getElementById('profileCropImage');
          if (profileCropper) {
              profileCropper.destroy();
          }
          profileCropper = new Cropper(image, {
              aspectRatio: 1,
              viewMode: 1,
              dragMode: 'move',
              autoCropArea: 0.9,
              background: false,
              responsive: true,
              restore: false,
              guides: true,
              center: true,
              highlight: false,
              cropBoxMovable: true,
              cropBoxResizable: true,
              toggleDragModeOnDblclick: false
          });
      });

      $('#profileCropModal').on('hidden.bs.modal', function () {
          destroyProfileCropper();
          if (!profileCropApplied) {
              setProfileInputFile(committedProfileFile);
          }
          profileCropApplied = false;
      });

      $('#profileCropZoomIn').on('click', function () {
          if (profileCropper) profileCropper.zoom(0.1);
      });
      $('#profileCropZoomOut').on('click', function () {
          if (profileCropper) profileCropper.zoom(-0.1);
      });
      $('#profileCropRotateLeft').on('click', function () {
          if (profileCropper) profileCropper.rotate(-90);
      });
      $('#profileCropRotateRight').on('click', function () {
          if (profileCropper) profileCropper.rotate(90);
      });
      $('#profileCropReset').on('click', function () {
          if (profileCropper) profileCropper.reset();
      });

      $('#profileCropApply').on('click', function () {
          if (!profileCropper) return;
          var canvas = profileCropper.getCroppedCanvas({
              width: 600,
              height: 600,
              imageSmoothingEnabled: true,
              imageSmoothingQuality: 'high'
          });
          if (!canvas) return;

          canvas.toBlob(function (blob) {
              if (!blob) {
                  alert('Could not process this image. Please try another photo.');
                  return;
              }
              var croppedFile = new File([blob], 'profile_' + Date.now() + '.jpg', {
                  type: 'image/jpeg',
                  lastModified: Date.now()
              });
              if (!setProfileInputFile(croppedFile)) {
                  alert('Your browser could not attach the edited photo. Please try again or use another browser.');
                  return;
              }
              committedProfileFile = croppedFile;
              profileCropApplied = true;
              $('#avatarPreview').attr('src', URL.createObjectURL(blob)).css({ opacity: 1, objectFit: 'cover', padding: 0 });
              profileCropModal.hide();
          }, 'image/jpeg', 0.92);
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
        // Address is optional — do not block profile / photo save when empty.
        var addressEl = document.getElementById('address');
        if (addressEl) {
          addressEl.classList.remove('dangerBoader');
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

        var currentPassword = document.getElementById('current_password');
        var newPassword = document.getElementById('new_password');
        var confirmPassword = document.getElementById('new_password_confirmation');
        if (currentPassword && newPassword && confirmPassword) {
          var anyPasswordFilled = currentPassword.value || newPassword.value || confirmPassword.value;
          [currentPassword, newPassword, confirmPassword].forEach(function (el) {
            el.classList.remove('dangerBoader');
          });
          if (anyPasswordFilled) {
            if (!currentPassword.value) {
              currentPassword.classList.add('dangerBoader');
              successFlag = false;
            }
            if (!newPassword.value || newPassword.value.length < 6) {
              newPassword.classList.add('dangerBoader');
              successFlag = false;
            }
            if (!confirmPassword.value || confirmPassword.value !== newPassword.value) {
              confirmPassword.classList.add('dangerBoader');
              successFlag = false;
            }
          }
        }

        return successFlag;
      }
    </script>
</body>

</html>