@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Scholarship</h1>
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>
                                @if($row->descriptions->isNotEmpty())
                                    {!! $row->descriptions->first()->text !!}
                                @else
                                    No Scholarship description available
                                @endif
                            </td>
                            <td>
                                {{$row->video}}
                            </td>
                            <td>
                                <a href="{{route('admin.scholarship.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.scholarship.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
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
