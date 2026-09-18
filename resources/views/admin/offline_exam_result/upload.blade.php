@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <div class="" style="width: calc(100% - 140px);">
                <h1 class="page-header mb-0">Upload Offline Exam Result</h1>
            </div>
            <div style="display: flex;">
                <a href="javascript:void(0);" style="display: flex;font-size: 20px;text-decoration: none !important;padding-right:20px;" onclick="openInstruction();">
                    <i class="material-icons">accessibility</i> 
                    Instruction
                </a>
                <a href="{{asset('')}}csv/offline_question_result.csv" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">download</i> 
                    Download
                </a>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.offline_exam_result.upload_save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
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

    <div class="modal fade bd-example-modal-lg" id="instructionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Instruction</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table1 table-bordered" style="width:100%;font-size: 12px;">
              <tbody>
                <tr>
                  <td style="width:10%;">Column A</td>
                  <td style="width:20%;">SCANNO</td>
                  <td style="width:70%;">Serial Number</td>
                </tr>
                <tr>
                  <td>Column B</td>
                  <td>CAND_ID </td>
                  <td>Put Student ID (Get it from Admin > Student > Student List > ID (1st Column) )</td>
                </tr>
                <tr>
                  <td>Column C</td>
                  <td>EXAM TYPE</td>
                  <td>Put RNS,RPS,SNT  </td>
                </tr>
                <tr>
                  <td>Column D</td>
                  <td>Question Number</td>
                  <td>Answer</td>
                </tr>
                <tr>
                  <td>Column E</td>
                  <td>Same ..</td>
                  <td>Same ..</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

@endsection


@section('js_after')
     <script type="text/javascript">



        function openInstruction(){
            $('#instructionModal').modal('show');
        }

        function changeCountry(){
            var data = {};
            data.location_id = document.getElementById('location_id').value;

            $.get("{{route('admin.common.offline_exam')}}", data)
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