@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4">
        <h1 class="page-header mb-0">Course Test Schedule (2026–27)</h1>
    </div>
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>Section</th>
                <th>#</th>
                <th>Date</th>
                <th>Type</th>
                <th>Timing</th>
                <th>Subjects / Topics</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $row)
                <tr>
                    <td>{{ $row->section }}</td>
                    <td>{{ $row->seq }}</td>
                    <td>{{ optional($row->test_date)->format('d M Y') }}</td>
                    <td>{{ $row->test_type }}</td>
                    <td>{{ $row->timing }}</td>
                    <td>
                        @foreach(($row->subjects ?? []) as $s)
                            <div><strong>{{ $s['subject'] ?? '' }}:</strong> {{ $s['topics'] ?? '' }}</div>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.course_schedule.change', ['id' => $row->id]) }}" onclick="return confirm('Change status?');">
                            @if($row->status)
                                <span class="btn btn-success btn-sm">Active</span>
                            @else
                                <span class="btn btn-danger btn-sm">Inactive</span>
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
