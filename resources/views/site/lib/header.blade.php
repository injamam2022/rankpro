<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('') }}web/css/bootstrap.min.css">

    <title>Shikkha Academia</title>

    <link rel="shortcut icon" href="{{ asset('') }}web/img/favIcon.ico" />
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}web/css/all.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}web/css/style.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}web/css/form.css" />
    <link rel="stylesheet" href="{{ asset('') }}web/css/animate.css">
    <link rel="stylesheet" href="{{ asset('') }}web/css/jquery.bxslider.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @yield('css_after')
</head>

<body>
    @yield('content')

    <div id="preloader"></div>



    <script src="{{ asset('') }}web/js/jquery.min.js"></script>
    <script src="{{ asset('') }}web/js/popper.min.js"></script>
    <script src="{{ asset('') }}web/js/bootstrap.min.js"></script>
    <script src="{{ asset('') }}web/js/jquery.bxslider.js"></script>
    <script src="{{ asset('') }}web/js/wow.js"></script>
    <script src="{{ asset('') }}web/js/myScript.js"></script>
    @yield('js_after')
</body>

</html>
