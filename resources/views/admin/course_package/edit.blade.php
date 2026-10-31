@extends('layouts.backend')

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4 align-items-center">
        <h1 class="page-header mb-0">Edit Course Package</h1>
    </div>
    <form action="{{ route('admin.course_package.update') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $details->id }}">
        @include('admin.course_package._form')
        <div class="col-lg-12 mt-5 text-center">
            <a href="{{ route('admin.course_package') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
        <div class="mb-5">&nbsp;</div>
    </form>
</div>
@endsection

@section('js_after')
    @stack('js_after')
@endsection
