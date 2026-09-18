
@extends('layouts.counsellor')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
        
        <div class="container-xl px-5">
            <div class="d-flex mt-10 mb-4 align-items-center">
                <h1 class="page-header mb-0">Setting</h1>
            </div>
            <!-- Simple DataTables example-->
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Key Name</th>
                        <th>Value</th>
                        <th data-type="date" data-format="YYYY/MM/DD">Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($list)>0)
                        @foreach ($list as $key=>$row)
                            <tr>
                                <td>{{$row->name}}</td>
                                <td>{{$row->key_name}}</td>
                                <td>{{$row->value}}</td>
                                <td>{{date('Y/m/d', strtotime($row->created_at))}}</td>
                                <td>
                                    <a href="{{route('admin.editSetting',['id'=>$row->id])}}" class="edit btn btn-primary btn-sm mr-1">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

@endsection


@section('js_after')
    <script type="text/javascript">
        
    </script>
@endsection