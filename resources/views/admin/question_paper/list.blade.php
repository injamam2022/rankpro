@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Question Bank</h1>
            </div>
            <div>
                <a href="{{route('admin.question_paper.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
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
                    <th>No of question</th>
                    <th>Totals marks for exam</th>
                    <th>Status</th>
                    <th>Question</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->name}}</td>
                            <td>{{$row->no_of_question}}</td>
                            <td>{{$row->totals_marks_for_exam}}</td>
                            <td>
                                <a href="{{route('admin.question_paper.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.question_paper.question',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;">
                                    <span class="btn btn-success btn-sm">View</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.question_paper.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('admin.question_paper.view',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">visibility</i></a>
                                <a href="{{route('admin.question_paper.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
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