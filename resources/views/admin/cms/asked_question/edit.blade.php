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
            <h1 class="page-header mb-0">Edit Frequently Asked Questions</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.asked_question.update')}}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="{{$asked_question->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="type" class="form-label">Questions Type</label>
                            <div class="form-control" style="display: flex;">
                                <div class="form-check" style="flex: 1;">
                                    <input class="form-check-input" type="radio" name="type" id="type_online_education" value="Online Education" {{ $asked_question->type == 'Online Education' ? 'checked' : '' }} />
                                    <label class="form-check-label" for="type_online_education">Online Education</label>
                                </div>
                                <div class="form-check" style="flex: 1; margin-left: 15px;">
                                    <input class="form-check-input" type="radio" name="type" id="type_payment_method" value="Payment Method" {{ $asked_question->type == 'Payment Method' ? 'checked' : '' }} />
                                    <label class="form-check-label" for="type_payment_method">Payment Method</label>
                                </div>
                                <div class="form-check" style="flex: 1; margin-left: 15px;">
                                    <input class="form-check-input" type="radio" name="type" id="type_pricing_plan" value="Pricing Plan" {{ $asked_question->type == 'Pricing Plan' ? 'checked' : '' }} />
                                    <label class="form-check-label" for="type_pricing_plan">Pricing Plan</label>
                                </div>
                            </div>
                        </div>
                        @foreach($asked_questions as $asked_question)
                            <div class="col-sm-12 alert alert-success" style="padding:30px;margin-top:20px;">
                                <h4 class="" style="font-weight: blod;">
                                    Language : {{$asked_question->language_name}}
                                </h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="Questions" class="custom-label">Questions</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor<?=$asked_question->language?>" name="text[<?=$asked_question->language?>]"class="form-control" rows="5">
                                                            {{ $asked_question->text }}
                                                        </textarea>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="Answers" class="custom-label">Answers</label>
                                            <div class="form-group">
                                                    <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                    <div class="col-sm-12">
                                                        <textarea id="editor2<?=$asked_question->language?>" name="text2[<?=$asked_question->language?>]"class="form-control" rows="5">
                                                        {{ $asked_question->text2 }}
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
                                    <input class="form-check-input" id="status_active" type="radio" name="status" @if($asked_question->status == 1) checked  @endif value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" @if($asked_question->status == 0) checked  @endif value="0" />
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
    @foreach($asked_questions as $asked_question)
        ClassicEditor
            .create(document.querySelector('#editor{{ $asked_question->language }}'))
            .catch(error => {
                console.error('Error initializing CKEditor for Questions ({{ $asked_question->language }}):', error);
            });

        ClassicEditor
            .create(document.querySelector('#editor2{{ $asked_question->language }}'))
            .catch(error => {
                console.error('Error initializing CKEditor for Answers ({{ $asked_question->language }}):', error);
            });
    @endforeach
</script>

@endsection
