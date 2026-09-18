@extends('layouts.backend')


@section('css_after')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            <h1 class="page-header mb-0">Edit New Light Test Series</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.test_series.save')}}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="image">Banner Image (Image Size: W- 258 px, H- 340 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="image" type="file" name="image"  accept="image/png, image/jpeg, image/jpg">

                                <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="icon">Icon (Image Size: W- 258 px, H- 340 px) (Type: PNG,JPG,JPEG) <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" id="icon" type="file" name="icon" accept="image/png, image/jpeg, image/jpg">

                                <img id="image-preview" style="width: 50px; margin-left: 10px; display: none;">
                            </div>
                        </div>
                        {{-- <div class="col-sm-12">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder="" value=""  required/>
                        </div> --}}

                        <div class="col-sm-12 mt-3">
                            <label class="form-label" for="subjects">Subjects</label>
                            <select class="form-control" name="subjects[]" id="subjects" multiple="multiple" tabindex="-1">
                                @php
                                    $selectedSubject = [];
                                    $selectedSubject = array_merge($selectedSubject, explode(',', ''));
                                    $selectedSubject = array_unique($selectedSubject);
                                @endphp
                                @foreach ($subjects as $data)
                                    @php
                                        $selected = in_array($data->id, $selectedSubject) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $data->id }}" {{ $selected }}>{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @foreach($languages as $language)
                            <div class="col-sm-12 alert alert-success" style="padding:30px;margin-top:20px;">
                                <h4 class="" style="font-weight: blod;">
                                    Language : {{$language->name}}
                                </h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="Heading" class="custom-label">Heading</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor1<?=$language->id?>" name="heading_<?=$language->id?>"class="form-control" rows="5">
                                                            
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="About" class="custom-label">About</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor2<?=$language->id?>" name="about_<?=$language->id?>"class="form-control" rows="5">
                                                        
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="description" class="custom-label">Banner Description</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor3<?=$language->id?>" name="text_<?=$language->id?>"class="form-control" rows="5">
                                                       
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="Overview" class="custom-label">Quick Overview</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor4<?=$language->id?>" name="overview_<?=$language->id?>"class="form-control" rows="5">
                                                        
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="Exam" class="custom-label">Exam Description</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor5<?=$language->id?>" name="examdesc_<?=$language->id?>"class="form-control" rows="5">
                                                        
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="Marking" class="custom-label">Marking Scheme</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor6<?=$language->id?>" name="markscheme_<?=$language->id?>"class="form-control" rows="5">
                                                        
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-sm-6">
                            <label class="form-label" for="price">Annual Fee</label>
                            <input class="form-control" id="price" type="text" name="price" placeholder="" value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="dis_price">Annual Fee Discount</label>
                            <input class="form-control" id="dis_price" type="text" name="dis_price" placeholder="" value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="tax">Tax</label>
                            <input class="form-control" id="tax" type="text" name="tax" placeholder="" value="" />
                        </div>

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="language_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Language</h4>
                                <a href="javascript:void(0);" onclick="addNewLanguage()"><img src="{{ asset('image/add-box.png') }}" alt="Add Language"></a>
                            </div>

                            <div class="row mb-3" id="language_view_0">
                                <div class="col-11">
                                    <input type="text" class="form-control" name="language_name[]" placeholder="Enter name" value="" id="language_name_0">
                                </div>
                                <div class="col-1 d-flex align-items-center">
                                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLanguage(0)">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="location_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Location</h4>
                                <a href="javascript:void(0);" onclick="addNewLocation()"><img src="{{ asset('image/add-box.png') }}" alt="Add Location"></a>
                            </div>

                            <div class="row mb-3" id="location_view_0">
                                <div class="col-11">
                                    <input type="text" class="form-control" name="location_name[]" placeholder="Enter name" value="" id="location_name_0">
                                </div>
                                <div class="col-1 d-flex align-items-center">
                                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLocation(0)">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 alert alert-success p-4 mt-4" id="date_list_view">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold">Starting Date</h4>
                                <a href="javascript:void(0);" onclick="addNewDate()"><img src="{{ asset('image/add-box.png') }}" alt="Add Location"></a>
                            </div>

                            <div class="row mb-3" id="date_view_0">
                                <div class="col-11">
                                    <input type="text" class="form-control" name="date_name[]" placeholder="Enter starting date" value="" id="date_name_0">
                                </div>
                                <div class="col-1 d-flex align-items-center">
                                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeDate(0)">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" value="1" />
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

    @php
        $prefixes = ['editor1', 'editor2', 'editor3', 'editor4', 'editor5', 'editor6'];
    @endphp

    @foreach ($prefixes as $prefix)
        @foreach ($languages as $language)
            ClassicEditor
                .create(document.querySelector('#{{ $prefix }}{{ $language->id }}'))
                .catch(error => {
                    console.error('{{ $prefix }}{{ $language->id }}', error);
                });
        @endforeach
    @endforeach

    let language_count = 1;

    function addNewLanguage() {
        const html = `
            <div class="row mb-3" id="language_view_${language_count}">
                <div class="col-11">
                    <input type="text" class="form-control" name="language_name[]" placeholder="Enter name" id="language_name_${language_count}">
                </div>
                <div class="col-1 d-flex align-items-center">
                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLanguage(${language_count})">
                </div>
            </div>
        `;
        document.getElementById('language_list_view').insertAdjacentHTML('beforeend', html);
        language_count++;
    }

    function removeLanguage(index) {
        const elem = document.getElementById(`language_view_${index}`);
        if (elem) elem.remove();
    }

    let location_count = 1;

    function addNewLocation() {
        const html = `
            <div class="row mb-3" id="location_view_${location_count}">
                <div class="col-11">
                    <input type="text" class="form-control" name="location_name[]" placeholder="Enter name" id="location_name_${location_count}">
                </div>
                <div class="col-1 d-flex align-items-center">
                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeLocation(${location_count})">
                </div>
            </div>
        `;
        document.getElementById('location_list_view').insertAdjacentHTML('beforeend', html);
        location_count++;
    }

    function removeLocation(index) {
        const elem = document.getElementById(`location_view_${index}`);
        if (elem) elem.remove();
    }

    let date_count = 1;

    function addNewDate() {
        const html = `
            <div class="row mb-3" id="date_view_${date_count}">
                <div class="col-11">
                    <input type="text" class="form-control" name="date_name[]" placeholder="Enter starting date" id="date_name_${date_count}">
                </div>
                <div class="col-1 d-flex align-items-center">
                    <img src="{{ asset('image/remove-box.png') }}" alt="Remove" class="cursor-pointer" onclick="removeDate(${date_count})">
                </div>
            </div>
        `;
        document.getElementById('date_list_view').insertAdjacentHTML('beforeend', html);
        date_count++;
    }

    function removeDate(index) {
        const elem = document.getElementById(`date_view_${index}`);
        if (elem) elem.remove();
    }

    setTimeout(function () {
        $('#subjects').select2({
            placeholder: 'Assign Subject',
            width: '100%'
        });
    }, 500);
</script>
@endsection
