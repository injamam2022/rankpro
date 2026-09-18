@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Payment Test Series</h1>
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
                    <th>Test Series</th>
                    <th>Amount</th>
                    <th>Coupon</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->first_name}} {{$row->last_name}}</td>
                            <td>{{$row->email_id}}</td>
                            <td>{!! $row->heading !!}</td>
                            <td>{{$row->amount}}</td>
                            <td>{{$row->coupon_code}}</td>
                            <td>{{$row->created_at}}</td>
                            <td>
                                <a href="{{route('admin.payment.test_series_detail',['id'=>$row->id])}}" class="btn btn-success btn-sm mr-1">View</a>
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