
@extends('layouts.backend')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
        
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Country</h1>
            </div>
            <div>
                @if(isUserPermitted('country', 'add'))
                    <a href="{{route('admin.country.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                        <i class="material-icons">add</i> 
                        Add
                    </a>
                @endif
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Name</th>
                    <th data-type="date" data-format="YYYY/MM/DD">Created Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->name}}</td>
                            <td>{{date('Y/m/d', strtotime($row->created_at))}}</td>
                            <td>
                                @if($row->status ==1)
                                    <button class="btn btn-raised-primary btn-xs mdc-ripple-upgraded">Active</button>
                                @else
                                    <button class="btn btn-raised-danger btn-xs mdc-ripple-upgraded">Inactive</button>
                                @endif
                            </td>
                            <td>
                                @if(isUserPermitted('country', 'edit'))
                                <a href="{{route('admin.country.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                @endif
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