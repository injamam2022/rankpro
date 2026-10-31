@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4" style="display:flex;">
        <div style="width: calc(100% - 50px);">
            <h1 class="page-header mb-0">Course Packages</h1>
            <p class="text-muted mb-0">Customize packages from Pricing.docx (modes, prices, features). New Light is not used.</p>
        </div>
        <div>
            <a href="{{ route('admin.course_package.add') }}" style="display:flex;font-size:20px;text-decoration:none !important;">
                <i class="material-icons">add</i> Add
            </a>
        </div>
    </div>
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>Order</th>
                <th>Name</th>
                <th>Code</th>
                <th>Price</th>
                <th>Mode select</th>
                <th>Features</th>
                <th>Orders</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $row)
                <tr>
                    <td>{{ $row->sort_order }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->code }}</td>
                    <td>{{ $row->is_free ? 'Free' : '₹'.number_format($row->display_price, 0) }}</td>
                    <td>{{ $row->mode_selection }}</td>
                    <td>{{ $row->features_count }}</td>
                    <td>{{ $row->orders_count }}</td>
                    <td>
                        <a href="{{ route('admin.course_package.change', ['id' => $row->id]) }}" onclick="return confirm('Change status?');">
                            @if($row->status == 1)
                                <span class="btn btn-success btn-sm">Active</span>
                            @else
                                <span class="btn btn-danger btn-sm">Inactive</span>
                            @endif
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.course_package.edit', ['id' => $row->id]) }}" class="btn btn-primary btn-sm"><i class="material-icons">edit</i></a>
                        <a href="{{ route('admin.course_package.delete', ['id' => $row->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Delete package?');"><i class="material-icons">close</i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
