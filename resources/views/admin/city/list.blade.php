
@extends('layouts.backend')


@section('css_after')
    <style type="text/css">
        
    </style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">City</h1>
            </div>
            <div>
                <a href="{{route('admin.city.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i> 
                    Add
                </a>
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Country Name</th>
                    <th>State Name</th>
                    <th>City Name</th>
                    <th data-type="date" data-format="YYYY/MM/DD">Created Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->country_name}}</td>
                            <td>{{$row->state_name}}</td>
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
                                <a href="{{route('admin.city.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
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