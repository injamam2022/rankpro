@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">View Question Paper</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.question_paper.update')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    
                    <div class="row mt-2">
                        
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name </label>
                            <div class="form-control">
                                {{$details->name}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="no_of_question">Total No. Of Questions </label>
                            <div class="form-control">
                                {{$details->no_of_question}}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="marks_per_question">Marks Per Question </label>
                            <div class="form-control">
                                {{$details->marks_per_question}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="totals_marks_for_exam">Totals Marks for Exam</label>
                            <div class="form-control">
                                {{$details->totals_marks_for_exam}}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="time_per_question">Time Per Question (In minutes) </label>
                            <div class="form-control">
                                {{$details->total_time_for_exam}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="total_time_for_exam">Total Time for Exam (In minutes)</label>
                            <div class="form-control">
                                {{$details->total_time_for_exam}}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="negative_marking_applicable">Negative marking applicable</label>
                            <div class="form-control" style="display:flex;">
                                @if($details->negative_marking_applicable == 1) 
                                    Yes 
                                @else
                                    No
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6" id="negative_marking_per_question_div" style="display:@if($details->negative_marking_applicable == 1) block @else none  @endif;">
                            <label class="form-label" for="negative_marking_per_question">Negative marking per incorrect Answer</label>
                            <div class="form-control">
                                {{$details->negative_marking_per_question}}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">

                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="form-label" for="hard_level">Hard %</label>
                                    <div class="form-control">
                                        {{$details->hard_level}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label" for="medium_level">Medium %</label>
                                    <div class="form-control">
                                        {{$details->medium_level}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label" for="easy_level">Easy %</label>
                                    <div class="form-control">
                                        {{$details->easy_level}}
                                    </div>
                                </div>
                            </div>
                        </div>                            
                    </div>
                    
                    <div class="row mt-2">

                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5>Question Type</h5>
                                </div>
                                @foreach($question_paper_question_type as $value)
                                    <div class="col-sm-2">
                                        <label class="form-label" for="hard_level">{{$value->name}}</label>
                                        <div class="form-control">
                                            {{$value->value}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>                            
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-12 alert alert-success" style="padding:30px;margin-top:20px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="" style="font-weight: blod;">
                                        Subject List
                                    </h4>
                                </div>
                            </div>
                            <div id="subject_list_view">
                                @foreach($question_paper_subject as $value)
                                    <div class="row mt-2" id="subject_view_0">
                                        <div class="col-sm-2">
                                            <label class="form-label" for="subject_id_0">Subject </label>
                                            <div class="form-control">
                                                {{$value->subject_name}} &nbsp;
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label" for="chapter_id_0">Chapter</label>
                                            <div class="form-control">
                                                {{$value->chapter_name}} &nbsp;
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label" for="topic_id_0">Topic</label>
                                            <div class="form-control">
                                                {{$value->topic_name}} &nbsp;
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label" for="sub_topic_id_0">Sub Topic</label>
                                            <div class="form-control">
                                                {{$value->sub_topic_name}} &nbsp;
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label" for="total_no_of_question_0">No Of questions <span class="text-danger">*</span></label>
                                            <div class="form-control">
                                                {{$value->total_no_of_question}} &nbsp;
                                            </div>
                                        </div>
                                        <div class="col-sm-1" style="margin-top: 25px;">
                                            
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">

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

@endsection