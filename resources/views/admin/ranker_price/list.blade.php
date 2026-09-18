@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Ranker Meeting</h1>
            </div>
            <div>
                <a href="{{route('admin.ranker_price.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i>
                    Add
                </a>
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Ranker Name</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Discount Price</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->ranker_name}}</td>
                            <td>{{$row->title}}</td>
                            <td>{{$row->description}}</td>
                            <td>{{$row->price}}</td>
                            <td>{{$row->dis_price}}</td>
                            <td>{{$row->time}}</td>
                            <td>
                                <a href="{{route('admin.ranker_price.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.ranker_price.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('admin.ranker_price.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
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
