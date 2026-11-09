@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Students who appeared{{ !empty($details->name) ? ' — '.$details->name : '' }}</h1>
            </div>
            <div>
                <a href="{{ route('admin.online_exam') }}" class="btn btn-secondary btn-sm">Back to online exams</a>
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Rank</th>
                    <th>Accept / Reject</th>
                    <th>Appeared on</th>
                    <th data-sortable="false" data-searchable="false">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->first_name}} {{$row->last_name}}</td>
                            <td>{{$row->email_id}}</td>
                            <td>{{ $row->total_number }} / {{ $row->total_mark }}</td>
                            <td>{{ $row->percentage !== null && $row->percentage !== '' ? $row->percentage.'%' : '—' }}</td>
                            <td>{{ $row->rank ?: '—' }}</td>
                            <td>
                                @if($row->response_type === null || $row->response_type === '')
                                    <span class="btn btn-secondary btn-sm">Appeared</span>
                                @elseif((int) $row->response_type === 1)
                                    <span class="btn btn-success btn-sm">Accept</span>
                                @else
                                    <span class="btn btn-danger btn-sm">Reject</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.student.exam_details', ['id' => $row->id, 'user_id' => $row->user_id]) }}" class="btn btn-success btn-sm">View</a>
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