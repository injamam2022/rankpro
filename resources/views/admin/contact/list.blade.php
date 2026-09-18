@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Contact</h1>
            </div>
            <div>
                
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Location Name</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->location_name}}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->email}}</td>
                            <td>{{$row->mobile_number}}</td>
                            <td>{{$row->message}}</td>
                            <td>
                                <a href="{{route('admin.contact.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.contact.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('admin.contact.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

@endsection


@section('js_after')

@endsection