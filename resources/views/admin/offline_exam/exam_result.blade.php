@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Offline Exam Result</h1>
            </div>
            <div>
                
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Student Email</th>
                    <th>Total Answer</th>
                    <th>Total Number</th>
                    <th>Rank</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->first_name}} {{$row->last_name}}</td>
                            <td>{{$row->email_id}}</td>
                            <td>{{$row->total_answer}}</td>
                            <td>{{$row->total_number}}</td>
                            <td>{{$row->rank}}</td>
                            <td>
                                <a href="{{route('admin.offline_exam.result_details',['id'=>$row->id,'exam_id'=>$row->exam_id,'user_id'=>$row->user_id])}}" class="btn btn-success btn-sm mr-1">View</a>
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