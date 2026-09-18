@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Frequently Asked Questions</h1>
            </div>
            <div>
                <a href="{{route('admin.asked_question.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i>
                    Add
                </a>
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Questions</th>
                    <th>Answers</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>
                                {{ $row->type }}
                            </td>
                            <td>
                                {!! $row->text !!}
                            </td>
                            <td>
                                {!! $row->text2 !!}
                            </td>
                            <td>
                                <a href="{{route('admin.asked_question.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.asked_question.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('admin.asked_question.delete',['id'=>$row->uniq_id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
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
