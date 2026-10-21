@extends('layouts.backend')


@section('css_after')
    
    <style type="text/css">
      .dangerBoader{
        border: 1px solid red !important;
      }
    </style>
@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Online Exam</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.online_exam.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_code">Exam Code <span class="text-danger">*</span></label>
                            <input class="form-control" id="exam_code" type="number" name="exam_code" placeholder=""  value="{{ old('exam_code') }}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Exam Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="{{ old('name') }}" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_logo">Exam Icon (Image Size: W- 161 px, H- 260 px) (Type: PNG,JPG,JPEG)</label>
                            <input class="form-control" id="exam_logo" type="file" name="exam_logo" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="landing_icon">Landing Exam Icon (Image Size: W- 258 px, H- 340 px) (Type: PNG,JPG,JPEG)</label>
                            <input class="form-control" id="landing_icon" type="file" name="landing_icon" placeholder=""  value="" />
                        </div>
                    </div>
                    @include('admin.online_exam._schedule_batch_fields')
                    <div class="row mt-2">

                        <div class="col-sm-6">
                            <label class="form-label" for="price">Annual Fee <span class="text-danger">*</span></label>
                            <input class="form-control" id="price" type="text" name="price" placeholder="" value="{{ old('price') }}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="dis_price">Annual Fee Discount</label>
                            <input class="form-control" id="dis_price" type="text" name="dis_price" placeholder="" value="{{ old('dis_price') }}" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="tax">Tax</label>
                            <input class="form-control" id="tax" type="text" name="tax" placeholder="" value="{{ old('tax') }}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="batch_id">Batch <span class="text-danger">*</span></label>
                            @include('admin.online_exam._batch_select')
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="question_paper_id">Question Paper <span class="text-danger">*</span></label>
                            <select class="form-control" id="question_paper_id" name="question_paper_id">
                                <option value="">Select</option>
                                @foreach($question_paper_list as $value)
                                    <option value="{{$value->id}}" @selected((string) old('question_paper_id') === (string) $value->id)>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="exam_instructions">Instructions</label>
                            <textarea class="form-control" id="exam_instructions" name="exam_instructions">{{ old('exam_instructions') }}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="result_title">OMR Title</label>
                            <textarea class="form-control" id="result_title" name="result_title">{{ old('result_title') }}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="result_description">OMR Description</label>
                            <textarea class="form-control" id="result_description" name="result_description">{{ old('result_description') }}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="result_declaration">OMR Declaration</label>
                            <textarea class="form-control" id="result_declaration" name="result_declaration">{{ old('result_declaration') }}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="is_trending">Is Trending</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="is_trending" type="radio" name="is_trending" checked value="1" />
                                    <label class="form-check-label" for="is_trending">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="is_trending" type="radio" name="is_trending" value="0" />
                                    <label class="form-check-label" for="is_trending">No</label>
                                </div>
                            </div>
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
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label">Enable Proctoring</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="is_proctored_yes" type="radio" name="is_proctored" value="1" />
                                    <label class="form-check-label" for="is_proctored_yes">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="is_proctored_no" type="radio" name="is_proctored" value="0" checked />
                                    <label class="form-check-label" for="is_proctored_no">No</label>
                                </div>
                            </div>
                            <small class="text-muted">Requires webcam, fullscreen, and flags tab switching during the exam.</small>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="proctoring_max_violations">Max Proctoring Warnings</label>
                            <input class="form-control" id="proctoring_max_violations" type="number" min="1" max="20" name="proctoring_max_violations" value="5" />
                            <small class="text-muted">Exam auto-submits after this many tab/fullscreen violations.</small>
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
    @include('admin.online_exam._form_validation')
@endsection