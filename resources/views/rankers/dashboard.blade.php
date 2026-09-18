
@extends('layouts.rankers')


@section('css_after')
	<style type="text/css">
		
	</style>
@endsection

@section('content')
		<div class="container-xl p-5">
            <div class="row justify-content-between align-items-center mb-5">
                <div class="col flex-shrink-0 mb-5 mb-md-0">
                    <h1 class="display-4 mb-0">Dashboard</h1>
                    <div class="text-muted">Sales overview &amp; summary</div>
                </div>
                <div class="col-12 col-md-auto">
                    <!-- <div class="d-flex flex-column flex-sm-row gap-3">
                        <mwc-select class="mw-50 mb-2 mb-md-0" outlined label="View by">
                            <mwc-list-item selected value="0">Order type</mwc-list-item>
                            <mwc-list-item value="1">Segment</mwc-list-item>
                            <mwc-list-item value="2">Customer</mwc-list-item>
                        </mwc-select>
                        <mwc-select class="mw-50" outlined label="Sales from">
                            <mwc-list-item value="0">Last 7 days</mwc-list-item>
                            <mwc-list-item value="1">Last 30 days</mwc-list-item>
                            <mwc-list-item value="2">Last month</mwc-list-item>
                            <mwc-list-item selected value="3">Last year</mwc-list-item>
                        </mwc-select>
                    </div> -->
                </div>
            </div>
            <!-- Colored status cards-->
            <div class="row gx-5">
                <div class="col-xxl-3 col-md-6 mb-5">
                    <div class="card card-raised bg-primary text-white">
                        <a href="#"  class="card-body px-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="me-2">
                                    <div class="display-5 text-white">{{$total_booking}}</div>
                                    <div class="card-text">Total bookings</div>
                                </div>
                                <div class="icon-circle bg-white-50 text-primary"><i class="material-icons">download</i></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6 mb-5">
                    <div class="card card-raised bg-warning text-white">
                        <a href="#" class="card-body px-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="me-2">
                                    <div class="display-5 text-white">{{$total_open_booking}}</div>
                                    <div class="card-text">Open Bookings</div>
                                </div>
                                <div class="icon-circle bg-white-50 text-warning"><i class="material-icons">storefront</i></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6 mb-5">
                    <div class="card card-raised bg-secondary text-white">
                        <a href="#" class="card-body px-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="me-2">
                                    <div class="display-5 text-white">{{$total_users}}</div>
                                    <div class="card-text">Total Users</div>
                                </div>
                                <div class="icon-circle bg-white-50 text-secondary"><i class="material-icons">people</i></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6 mb-5">
                    <div class="card card-raised bg-info text-white">
                        <a href="#" class="card-body px-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="me-2">
                                    <div class="display-5 text-white">{{$total_earning}}</div>
                                    <div class="card-text">Total Earning</div>
                                </div>
                                <div class="icon-circle bg-white-50 text-info"><i class="material-icons">devices</i></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

@endsection


@section('js_after')
	<script type="text/javascript">
		
	</script>
@endsection