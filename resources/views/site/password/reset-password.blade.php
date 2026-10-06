<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>RankPro - Educational Advisory solutions</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
</head>

<!-- <body style="background: url(images/fullindex.jpg) no-repeat center top;"> -->

<body>
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
          <div class="formBlock formBlockLogin authShell">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="loginLeft">
                  <div class="formTitle text-left">Reset password</div>
                  <p class="auth-lead">Choose a new password for your account.</p>

                  @if (session('error'))
                    <div class="auth-alert error" role="alert">{{ session('error') }}</div>
                  @endif
                  @if ($errors->any())
                    <div class="auth-alert error" role="alert">
                      @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                      @endforeach
                    </div>
                  @endif

                  <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="form-group">
                      <label for="password">New password <span class="req" aria-hidden="true">*</span></label>
                      <div class="auth-input has-toggle">
                        <span class="field-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="At least 4 characters" name="password" minlength="4" autocomplete="new-password" required>
                        <button type="button" class="pw-toggle" data-target="password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="password_confirmation">Confirm password <span class="req" aria-hidden="true">*</span></label>
                      <div class="auth-input has-toggle">
                        <span class="field-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm password" name="password_confirmation" minlength="4" autocomplete="new-password" required>
                        <button type="button" class="pw-toggle" data-target="password_confirmation" aria-label="Show password"><i class="bi bi-eye"></i></button>
                      </div>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btnRegister w-100">Update password</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-md-6">
                <div class="loginRight">
                  <div class="authVisual">
                    <img src="{{ asset('web/images/form/neet-auth-side.jpg') }}" class="img-fluid" alt="NEET exam preparation">
                    <div class="authVisualMsg">
                      <span class="authVisualTag">New Password</span>
                      <h3>Set a strong password and continue</h3>
                      <p>Once updated, you can log in and keep preparing for NEET without delay.</p>
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
    <script>
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
    </script>
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
</body>

</html>