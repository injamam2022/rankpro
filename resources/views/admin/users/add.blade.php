<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin User</title>
        <!-- Load Favicon-->
        <link href="{{asset('')}}admin/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <!-- Load Material Icons from Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet" />
        <!-- Roboto and Roboto Mono fonts from Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,500" rel="stylesheet" />
        <!-- Load main stylesheet-->
        <link href="{{asset('')}}admin/css/styles.css" rel="stylesheet" />
    </head>
    <body class="nav-fixed">
        <!-- Top app bar navigation menu-->

        @include('admin.include.header')
        <!-- Layout wrapper-->
        <div id="layoutDrawer">
            <!-- Layout navigation-->
            @include('admin.include.left_menu')
            
            <!-- Layout content-->
            <div id="layoutDrawer_content">
                <!-- Main page content-->
                <main>
                    <div class="container-xl px-5">
                        <div class="d-flex mt-10 mb-4 align-items-center">
                            <h1 class="page-header mb-0">Add Admin User</h1>
                        </div>
                        <div class="row gx-5">
                            <div class="col-lg-9">
                                <form action="{{route('admin.users.save')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div id="material-textfield">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">Name</label>
                                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                                            @error('name')
                                                <div class="invalid-feedback" role="alert" style="display:block;">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control" id="email" type="text" name="email" placeholder=""  value="" />
                                            @error('email')
                                                <div class="invalid-feedback" role="alert" style="display:block;">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password</label>
                                            <input class="form-control" id="password" type="text" name="password" placeholder=""  value="" />
                                            @error('password')
                                                <div class="invalid-feedback" role="alert" style="display:block;">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </div>
                                            @endif
                                        </div>                                    
                                        <div class="mb-3">
                                            <label class="form-label" for="exampleFormControlInput">Status</label>
                                            <div class="form-check">
                                                <input class="form-check-input" id="status_active" type="radio" name="status" value="1" checked />
                                                <label class="form-check-label" for="status_active">Active</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
                                                <label class="form-check-label" for="status_inactive">Inactive</label>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <div class="mb-5">
                                            &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- Footer-->

                @include('admin.include.footer')
            </div>
        </div>
        <!-- Load Bootstrap JS bundle-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- Load global scripts-->
        <script type="module" src="{{asset('')}}admin/js/material.js"></script>
        <script src="{{asset('')}}admin/js/scripts.js"></script>
        <!-- Load Prism plugin scripts-->
        <script src="{{asset('')}}admin/js/prism.js"></script>
    </body>
</html>
