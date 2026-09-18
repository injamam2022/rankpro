@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Upload Offline Exam Question and Answer</h1>
            </div>
            <div>
                <a href="{{asset('')}}csv/offline_question.csv" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">download</i> 
                    Download
                </a>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.offline_exam_result.question_save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Location <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_id" name="location_id" onchange="changeCountry();">
                                <option value="">Select Location</option>
                                @foreach($location_list as $val)
                                    <option value="{{$val->id}}">{{$val->location_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Exam <span class="text-danger">*</span></label>
                            <select class="form-control" id="exam_id" name="exam_id">
                                <option value="">Select Exam</option>
                                @foreach($exam_list as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="csv_file">CSV File <span class="text-danger">*</span></label>
                            <input class="form-control" id="csv_file" type="file" name="csv_file" placeholder=""  value="" />
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
     <script type="text/javascript">
        function changeCountry(){
            var data = {};
            data.location_id = document.getElementById('location_id').value;

            $.get("{{route('admin.common.offline_exam_without_qus_paper')}}", data)
              .done(function( response ) {
                console.log(response);
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Exam</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('exam_id').innerHTML = role_modal_body;
              });
        }
    </script>
    <script type="text/javascript">
        
        function formValidation(){
            var data = {};
            var successFlag = true;

            data.location_id = document.getElementById('location_id').value;
            if(data.location_id){
                document.getElementById('location_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('location_id').classList.add('dangerBoader');
                successFlag = false;
            }

            data.exam_id = document.getElementById('exam_id').value;
            if(data.exam_id){
                document.getElementById('exam_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('exam_id').classList.add('dangerBoader');
                successFlag = false;
            }

            var csv_file = document.getElementById("csv_file");
            console.log(csv_file.files);
            if(csv_file.files.length){
                var file = csv_file.files[0];
                console.log(file);
                if(file){
                    if (!allowedTypes.includes(file.type) || file.size > maxSize1) {
                      document.getElementById('csv_file').classList.add('dangerBoader');
                      successFlag = false;
                    }else{
                      document.getElementById('csv_file').classList.remove('dangerBoader');
                    }
                }else{
                    document.getElementById('csv_file').classList.add('dangerBoader');
                    successFlag = false;
                }
            }else{
                document.getElementById('csv_file').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>

@endsection