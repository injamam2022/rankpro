@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            
            <div class="" style="width: calc(100% - 140px);">
                <h1 class="page-header mb-0">Bulk Question Upload</h1>
            </div>
            <div style="display: flex;">
                <a href="javascript:void(0);" style="display: flex;font-size: 20px;text-decoration: none !important;padding-right:20px;" onclick="openInstruction();">
                    <i class="material-icons">accessibility</i> 
                    Instruction
                </a>
                <a href="{{asset('')}}csv/question.xlsx" style="display: flex;font-size: 20px;text-decoration: none !important;padding-right:0px;">
                    <i class="material-icons">download</i> 
                    Download
                </a>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.question.upload_save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="subject_id">Subject <span class="text-danger">*</span></label>
                            <select class="form-control" id="subject_id" name="subject_id">
                                <option value="">Select</option>
                                @foreach($subject_list as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="csv_file">XLSX <span class="text-danger">*</span></label>
                            <input class="form-control" id="csv_file" type="file" name="csv_file" placeholder=""  value="" />
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label" for="zip_file">Image Zip</label>
                            <input class="form-control" id="zip_file" type="file" name="zip_file" placeholder=""  value="" />
                        </div>
                        
                        
                        <div class="col-lg-12 mt-5 text-center">
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
                  <td style="width:20%;">Sl No</td>
                  <td style="width:70%;">Serial Number</td>
                </tr>
                <tr>
                  <td>Column B</td>
                  <td>Chapter </td>
                  <td>Put Chapter id here (Get it from Admin > Quesition > Chapter > 1st Column)</td>
                </tr>
                <tr>
                  <td>Column C</td>
                  <td>Topic</td>
                  <td>Put Topic id here</td>
                </tr>
                <tr>
                  <td>Column D</td>
                  <td>Sub Topic</td>
                  <td>Put Sub Topic id here</td>
                </tr>
                <tr>
                  <td>Column E</td>
                  <td>Difficulty Level</td>
                  <td>"Put 1,2,or 3 (1 = Easy; 2 = Medium; 3 = Hard)"</td>
                </tr>
                <tr>
                  <td>Column F</td>
                  <td>Question Type</td>
                  <td>Put Question Type id here (Get it from Admin > Quesition > Question Type > 1st Column)</td>
                </tr>
                <tr>
                  <td>Column G</td>
                  <td>Source</td>
                  <td>Put Source id here</td>
                </tr>
                <tr>
                <tr>
                  <td>Column H</td>
                  <td>Question Source</td>
                  <td>Put Quesition Source id here</td>
                </tr>
                <tr>
                  <td>Column I</td>
                  <td>Question Text</td>
                  <td>Put Question Text here</td>
                </tr>
                <tr>
                  <td>Column J</td>
                  <td>Question Image</td>
                  <td>If Question have any Image then put image name here. (Ex : Image.png)</td>
                </tr>
                <tr>
                  <td>Column K</td>
                  <td>Option1</td>
                  <td>Put Option1 Text. If Option1 is image then put image name here (like 1.png)</td>
                </tr>
                <tr>
                  <td>Column L</td>
                  <td>Is Option1 Image</td>
                  <td>If Option1 is image then put 1 Else put 0</td>
                </tr>
                <tr>
                  <td>Column M</td>
                  <td>Ans Behavior Tag 1</td>
                  <td>Put 1,2,3 or 4 (1 = Correct Answer; 2 = Close Call; 3 = Irrelevant; 4 = Silly Mistake)</td>
                </tr>
                <tr>
                  <td>Column N</td>
                  <td>Option2</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column O</td>
                  <td>Is Option2 Image</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column P</td>
                  <td>Ans Behavior Tag 2</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column Q</td>
                  <td>Option3</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column R</td>
                  <td>Is Option3 Image</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column S</td>
                  <td>Ans Behavior Tag 3</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column T</td>
                  <td>Option4</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column U</td>
                  <td>Is Option4 Image</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column V</td>
                  <td>Ans Behavior Tag 4</td>
                  <td>Same as option 1</td>
                </tr>
                <tr>
                  <td>Column W</td>
                  <td>Answer</td>
                  <td>Put correct option here 1,2,3, or 4</td>
                </tr>
                <tr>
                  <td>Column X</td>
                  <td>Solution Video Link</td>
                  <td>Put Video Link here (Ex : youtube video link : www.youtube.com/embed/Ee5_Sh_HcP8)</td>
                </tr>
                <tr>
                  <td>Column Y</td>
                  <td>Solution</td>
                  <td>Put Solution here</td>
                </tr>
                <tr>
                  <td>Column Z</td>
                  <td>Status</td>
                  <td>Put 1 or 0 (1 = Active; 0 = Inactive)</td>
                </tr>
                <tr>
                  <td colspan="2">Image Zip Folder Instruction</td>
                  <td>Create a Zip file containg png files of all images of quesition and options with same name which are mentioned in excel file</td>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Success</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="text-success" style="font-size: 20px;text-align: center;">
                Question uploaded successfully.
            </div>
            @if(session('message123'))
              <div class="text-danger mt-4 mb-2" style="font-size: 20px;text-align: center;">
                  But sl. no. {{ session('message123') }} question already uploaded
              </div>
            @endif
          </div>
          <div class="modal-footer mb-4" style="justify-content: center;">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
@endsection


@section('js_after')
    <script type="text/javascript">

        const allowedTypes = ["text/csv","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];
        const maxSize1 = 2048 * 2048;

        function formValidation(){
            var data = {};
            var successFlag = true;

            data.subject_id = document.getElementById('subject_id').value;
            if(data.subject_id){
                document.getElementById('subject_id').classList.remove('dangerBoader');
            }else{
                document.getElementById('subject_id').classList.add('dangerBoader');
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


        function openInstruction(){
            $('#instructionModal').modal('show');
        }

        @if(session('success123'))
            $(document).ready(function(){
                $('#exampleModal').modal('show');
            });
        @endif

    </script>

@endsection