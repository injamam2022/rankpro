@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Offline Exam Result Detail</h1>
            </div>
            <div>
                
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Video Link</th>
                    <th>Right Answer</th>
                    <th>User Answer</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->question_id}}</td>
                            <td>{{$row->video_link}}</td>
                            <td>{{$row->right_answer}}</td>
                            <td>
                                @if($row->right_answer == $row->answer)
                                    <span class="text-success">{{$row->answer}}</span>
                                @else
                                    <span class="text-danger">{{$row->answer}}</span>
                                @endif
                            </td>
                            <td>
                                <!-- <a href="{{route('admin.offline_exam.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a> -->
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