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
            <h1 class="page-header mb-0">Add Dashboard Banner</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.dashboard_banner.save')}}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="icon">Banner Image (Image Size: W- 438 px, H- 246 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="icon" type="file" name="icon" placeholder="" required onchange="previewImage(event)">
                                <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="video_link">Video Link</label>
                            <input class="form-control" id="video_link" type="text" name="video_link" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" checked value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
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
    // function previewImage(event) {
    //     var reader = new FileReader();
    //     reader.onload = function () {
    //         var output = document.getElementById('image-preview');
    //         output.src = reader.result;
    //         output.style.display = 'block';
    //     }
    //     reader.readAsDataURL(event.target.files[0]);
    // }

    @foreach($languages as $language)
        ClassicEditor
            .create(document.querySelector('#editor{{ $language->id }}'))
            .catch(error => {
                console.error(error);
            });
    @endforeach
</script>
@endsection
