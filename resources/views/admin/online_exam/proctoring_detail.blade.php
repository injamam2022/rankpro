@extends('layouts.backend')

@section('css_after')
    <style>
        .proctor-shot { width: 160px; height: auto; border-radius: 8px; border: 1px solid #e0e0e0; }
        .proctor-event { font-size: 13px; }
    </style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <div style="width: calc(100% - 80px);">
                <h1 class="page-header mb-0">
                    Proctoring Detail - {{ $user_exam->first_name ?? '' }} {{ $user_exam->last_name ?? '' }}
                </h1>
                <p class="mb-0 text-muted">{{ $details->name ?? '' }} | Warnings: {{ $user_exam->tab_switch_count ?? 0 }} | Status: {{ $user_exam->proctoring_status ?? '-' }}</p>
            </div>
            <div>
                <a href="{{ route('admin.online_exam.proctoring', ['id' => $user_exam->exam_id ?? '']) }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Event</th>
                    <th>Message</th>
                    <th>Snapshot</th>
                </tr>
            </thead>
            <tbody>
                @if(count($events) > 0)
                    @foreach ($events as $row)
                        <tr class="proctor-event">
                            <td>{{ $row->created_at }}</td>
                            <td>{{ $row->event_type }}</td>
                            <td>{{ $row->message }}</td>
                            <td>
                                @if($row->image_path)
                                    <a href="{{ asset($row->image_path) }}" target="_blank">
                                        <img src="{{ asset($row->image_path) }}" class="proctor-shot" alt="Snapshot">
                                    </a>
                                @else
                                    -
                                @endif
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
