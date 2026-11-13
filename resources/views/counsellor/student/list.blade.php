@extends('layouts.counsellor')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center" style="display:flex;">
            <div class="" style="width: calc(100% - 220px);">
                <h1 class="page-header mb-0">Student</h1>
            </div>
            <div style="display: flex; gap: 20px;">
                <a href="{{route('counsellor.student.download')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">download</i>
                    Excel
                </a>
                <a href="{{route('counsellor.student.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i>
                    Add
                </a>
            </div>
        </div>
        <!-- Simple DataTables example-->

        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th data-type="number" data-sort="desc">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Icon</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td>{{$row->first_name}} {{$row->last_name}}</td>
                            <td>{{$row->email_id}}</td>
                            <td>{{$row->mobile_number}}</td>
                            <td>{{$row->address}}</td>
                            <td>
                                @if($row->profile_img)
                                    <img src="{{asset('')}}uploads/profileImage/{{$row->profile_img}}" style="width:50px;">
                                @else

                                @endif
                            </td>
                            <td>
                                <a href="{{route('counsellor.student.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('counsellor.student.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('counsellor.student.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1"><i class="material-icons">close</i></a>
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
