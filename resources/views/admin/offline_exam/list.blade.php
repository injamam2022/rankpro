@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Offline Exam</h1>
            </div>
            <div>
                <a href="{{route('admin.offline_exam.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i> 
                    Add
                </a>
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Exam Code</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Exam Date - Time</th>
                    <!-- <th>No of question</th> -->
                    <!-- <th>Totals marks for exam</th> -->
                    <th>Status</th>
                    <th>Question</th>
                    <th>Result</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td><img src="{{asset('')}}uploads/exam/thumbnail/{{$row->exam_logo}}" style="width: 50px;"></td>
                            <td>{{$row->exam_code}}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->location_name}}</td>
                            <td>{{$row->exam_date}} - {{$row->exam_time}}</td>
                            <!-- <td>{{$row->no_of_question}}</td> -->
                            <!-- <td>{{$row->totals_marks_for_exam}}</td> -->
                            <td>
                                <a href="{{route('admin.offline_exam.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.offline_exam.question',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;">
                                    <span class="btn btn-success btn-sm">View</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.offline_exam.result',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;">
                                    <span class="btn btn-success btn-sm">View</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.offline_exam.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('admin.offline_exam.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
                                @if($row->is_ended == 0)
                                
                                    <a href="{{route('admin.offline_exam.end',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to end exam?');"><i class="material-icons">stop</i></a>
                                @endif
                                
                                    <a href="{{route('admin.offline_exam.view',['id'=>$row->id])}}" class="btn btn-success btn-sm mr-1"><i class="material-icons">visibility</i></a>
                                
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