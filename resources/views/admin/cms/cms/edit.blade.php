@extends('layouts.backend')


@section('css_after')
<style>
    .custom-label {
        margin-top: 0.5rem;
        margin-bottom: -0.75rem;
        display: block;
    }
</style>
@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit CMS</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.cms.update')}}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label" for="banner_header"> Banner Header <span class="text-danger">*</span> </label>
                            <textarea class="form-control" id="banner_header" name="banner_header" rows="4">{{$details->banner_header}}</textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="banner_description"> Banner Description <span class="text-danger">*</span> </label>
                            <textarea class="form-control" id="banner_description" name="banner_description" rows="4">{{$details->banner_description}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="banner_logo">Banner Image (Image Size: W- 438 px, H- 246 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="banner_logo" type="file" name="banner_logo"
                                       
                                       onchange="previewImage(event)">

                                @if($details->banner_logo)
                                    <img src="{{ asset('uploads/banner/thumbnail/' . $details->banner_logo) }}" id="image-preview" style="width: 50px; margin-left: 10px;">
                                @else
                                    <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="header"> Header <span class="text-danger">*</span> </label>
                            <textarea class="form-control" id="header" name="header" rows="4">{{$details->header}}</textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="description"> Description <span class="text-danger">*</span> </label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{$details->description}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" @if($details->status == 1) checked  @endif value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" @if($details->status == 0) checked  @endif value="0" />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 mt-5 text-center">
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-secondary cancelButton" type="button">Cancel</a>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                        <div class="mb-5">
                            &nbsp;
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js_after')
    <script>
        var banner_header = "";
        var banner_description = "";
        var header = "";
        var description = "";
        ClassicEditor
                .create(document.querySelector('#banner_header'))
                .then( editor => {
                    banner_header = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
        ClassicEditor
                .create(document.querySelector('#banner_description'))
                .then( editor => {
                    banner_description = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
        ClassicEditor
                .create(document.querySelector('#header'))
                .then( editor => {
                    header = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
        ClassicEditor
                .create(document.querySelector('#description'))
                .then( editor => {
                    description = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
    </script>
@endsection
