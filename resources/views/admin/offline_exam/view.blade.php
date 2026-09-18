@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Offline Exam View</h1>
            </div>
            <div>
                
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Type</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->first_name}} {{$row->last_name}}</td>
                            <td>{{$row->email_id}}</td>
                            <td>
                                @if($row->type == 1)
                                    <span class="btn btn-success btn-sm">Accept</span>
                                @else
                                    <span class="btn btn-danger btn-sm">Reject</span>
                                @endif
                            </td>
                            <td>{{$row->created_at}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

@endsection


@section('js_after')

@endsection