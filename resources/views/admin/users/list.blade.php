<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin Users</title>
        <!-- Load Favicon-->
        <link href="{{asset('')}}admin/assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <!-- Load Material Icons from Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet" />
        <!-- Load Simple DataTables Stylesheet-->
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
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
                        <div class="d-flex mt-10 mb-4" style="display:flex;">
                            <div class="" style="width: calc(100% - 50px);">
                                <h1 class="page-header mb-0">Admin Users</h1>
                            </div>
                            <div>
                                <a href="{{route('admin.users.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                                    <i class="material-icons">add</i> 
                                    Add
                                </a>
                            </div>
                        </div>
                        <!-- Simple DataTables example-->
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th data-type="date" data-format="YYYY/MM/DD">Created Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($list as $row)
                                    <tr>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->email}}</td>
                                        <td>{{date('Y/m/d', strtotime($row->created_at))}}</td>
                                        <td>
                                            @if($row->status ==1)
                                                <button class="btn btn-raised-primary btn-xs mdc-ripple-upgraded">Active</button>
                                            @else
                                                <button class="btn btn-raised-danger btn-xs mdc-ripple-upgraded">Inactive</button>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin.users.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                            <a href="{{route('admin.ranker_assign.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        <!-- Load Simple DataTables Scripts-->
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('')}}admin/js/datatables/datatables-simple-demo.js"></script>
    </body>
</html>
