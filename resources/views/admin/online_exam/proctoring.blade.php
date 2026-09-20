@extends('layouts.backend')

@section('css_after')
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Proctoring Log - {{ $details->name ?? '' }}</h1>
            </div>
            <div>
                <a href="{{ route('admin.online_exam') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Score</th>
                    <th>Tab / Focus Warnings</th>
                    <th>Other Events</th>
                    <th>Snapshots</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list) > 0)
                    @foreach ($list as $row)
                        <tr>
                            <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                            <td>{{ $row->email_id }}</td>
                            <td>{{ $row->total_number }} / {{ $row->total_mark }}</td>
                            <td>{{ $row->tab_switch_count ?? 0 }}</td>
                            <td>{{ $row->violation_count ?? 0 }}</td>
                            <td>{{ $row->snapshot_count ?? 0 }}</td>
                            <td>{{ $row->proctoring_status ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.online_exam.proctoring_detail', ['id' => $row->id]) }}" class="btn btn-success btn-sm">View</a>
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
