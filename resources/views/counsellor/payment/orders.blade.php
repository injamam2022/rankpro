@extends('layouts.counsellor')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4">
        <h1 class="page-header mb-0">Course Package Orders</h1>
    </div>
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Student</th>
                <th>Package</th>
                <th>Mode</th>
                <th>Amount</th>
                <th>Source</th>
                <th>Fulfilment</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $row)
                <tr>
                    <td>{{ $row->order_number }}</td>
                    <td>{{ trim(($row->user->first_name ?? '').' '.($row->user->last_name ?? '')) }}<br><small>{{ $row->user->email_id ?? '' }}</small></td>
                    <td>
                        {{ $row->package->name ?? '—' }}
                        @if(($row->package->code ?? '') === 'chapter_master')
                            @if($row->orderChapters->count())
                                <div class="small text-muted mt-1">
                                    {{ $row->orderChapters->count() }} chapter{{ $row->orderChapters->count() === 1 ? '' : 's' }}:
                                    {{ $row->orderChapters->map(fn ($line) => \App\Support\CoursePackageAccess::chapterExamLabel($line->chapter))->join(', ') }}
                                </div>
                            @elseif($row->isPaid())
                                <div class="small text-danger mt-1">No chapters recorded</div>
                            @endif
                        @elseif(($row->package->code ?? '') === 'mission_720' && $row->isPaid())
                            <div class="small text-muted mt-1">Includes full Chapter Master</div>
                        @endif
                    </td>
                    <td>{{ $row->modeBadge() }}</td>
                    <td>₹{{ number_format($row->displayAmount(), 2) }}</td>
                    <td>{{ $row->sourceLabel() }}</td>
                    <td>{{ $row->fulfillment_status }}</td>
                    <td>{{ $row->created_at }}</td>
                    <td><a href="{{ route('counsellor.payment.order_detail', ['id' => $row->id]) }}" class="btn btn-success btn-sm">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
