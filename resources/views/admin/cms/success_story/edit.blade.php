@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Success Story</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.success_story.update')}}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="{{$success_story->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" value="{{$success_story->name}}" required/>
                        </div>

                        <div class="col-sm-12">
                            <label class="form-label" for="address">Qualification <span class="text-danger">*</span></label>
                            <input class="form-control" id="address" type="text" name="address" value="{{$success_story->address}}" required/>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-10">
                                    <label class="form-label" for="image">Image (Type: PNG,JPG,JPEG)<span class="text-danger">*</span></label>
                                    <input class="form-control" id="image" type="file" name="image" placeholder=""  value="" accept="image/png, image/jpeg, image/jpg"/>
                                </div>
                                <div class="col-sm-2">
                                    <img src="{{asset('')}}uploads/success_story/thumbnail/{{$success_story->image}}" style="width: 50px;">
                                </div>
                            </div>
                        </div>

                        @foreach($languages as $language)
                            <div class="col-sm-12 alert alert-success" style="padding:30px;margin-top:20px;">
                                <h4 class="" style="font-weight: blod;">
                                    Language : {{$language->name}}
                                </h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="Description" class="custom-label">Description</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor<?=$language->id?>" name="text_<?=$language->id?>"class="form-control" rows="5">
                                                            @php
                                                                $data = $success_story->descriptions->firstWhere('language', $language->id);
                                                            @endphp
                                                            {{ $data ? $data->text : '' }}
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-sm-12">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" @if($success_story->status == 1) checked  @endif value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" @if($success_story->status == 0) checked  @endif value="0" />
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
            .create(document.querySelector('#editor<?=$language->id?>'))
            .catch(error => {
                console.error('Error initializing CKEditor for language <?=$language->id?>:', error);
            });
    @endforeach

    function changeTextImageFun(index, type) {
        let inputElement = document.getElementById('option' + index);

        if (type == 2) {
            inputElement.type = "file";
            inputElement.name = "image";
        } else {
            inputElement.type = "text";
            inputElement.name = "image";
        }
    }
</script>
@endsection
