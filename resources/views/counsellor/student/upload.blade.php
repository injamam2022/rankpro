@extends('layouts.counsellor')


@section('css_after')

@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            
            <div class="" style="width: calc(100% - 140px);">
                <h1 class="page-header mb-0">Bulk Student Upload</h1>
            </div>
            <div style="display: flex;">
                <a href="javascript:void(0);" style="display: flex;font-size: 20px;text-decoration: none !important;padding-right:20px;" onclick="openInstruction();">
                    <i class="material-icons">accessibility</i> 
                    Instruction
                </a>
                <a href="{{asset('')}}uploads/student.xlsx" style="display: flex;font-size: 20px;text-decoration: none !important;padding-right:0px;">
                    <i class="material-icons">download</i> 
                    Download
                </a>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('counsellor.student.upload_save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row">
                        
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
                  <td>Tag </td>
                  <td>1 - RNS - Rank Pro NEET Summit, 2 - Rank Pro HTS - Home Test Series, 3 - RPS - Rank Pro For Schools, 4 - HTS / SNT - Shikkha NEET Test Series (Home Test Series), 5 - OTS - Online Test Series</td>
                </tr>
                <tr>
                  <td>Column C</td>
                  <td>Profile Icon</td>
                  <td>If Student have any profile icon then put image name here. (Ex : 123456.png)</td>
                </tr>
                <tr>
                  <td>Column D</td>
                  <td>First Name</td>
                  <td>First Name</td>
                </tr>
                <tr>
                  <td>Column E</td>
                  <td>Last Name</td>
                  <td>Last Name</td>
                </tr>
                <tr>
                  <td>Column F</td>
                  <td>Phone Number</td>
                  <td>Phone Number</td>
                </tr>
                <tr>
                <tr>
                  <td>Column G</td>
                  <td>Email</td>
                  <td>Email</td>
                </tr>
                <tr>
                  <td>Column H</td>
                  <td>Is Whatsapp</td>
                  <td>If Is Whatsapp available on this number then put 1 Else put 0</td>
                </tr>
                <tr>
                  <td>Column I</td>
                  <td>Address (as per Adhaar)</td>
                  <td>Address (as per Adhaar)</td>
                </tr>
                <tr>
                  <td>Column J</td>
                  <td>Password</td>
                  <td>Password</td>
                </tr>
                <tr>
                  <td>Column K</td>
                  <td>School Name</td>
                  <td>School Name</td>
                </tr>
                <tr>
                  <td>Column L</td>
                  <td>Class Name</td>
                  <td>Class Name</td>
                </tr>
                <tr>
                  <td>Column M</td>
                  <td>Section Name</td>
                  <td>Section Name</td>
                </tr>
                <tr>
                  <td>Column N</td>
                  <td>Father's Name</td>
                  <td>Father's Name</td>
                </tr>
                <tr>
                  <td>Column O</td>
                  <td>Father's Contact No</td>
                  <td>Father's Contact No</td>
                </tr>
                <tr>
                  <td>Column P</td>
                  <td>Father's Qualification</td>
                  <td>Father's Qualification</td>
                </tr>
                <tr>
                  <td>Column Q</td>
                  <td>Mother's Name</td>
                  <td>Mother's Name</td>
                </tr>
                <tr>
                  <td>Column R</td>
                  <td>Mother's Contact No</td>
                  <td>Mother's Contact No</td>
                </tr>
                <tr>
                  <td>Column S</td>
                  <td>Mother's Qualification</td>
                  <td>Mother's Qualification</td>
                </tr>
                <tr>
                  <td>Column T</td>
                  <td>Qualification Details</td>
                  <td>Qualification Details</td>
                </tr>
                <tr>
                  <td>Column U</td>
                  <td>Certificate</td>
                  <td>If Student have any certificate then put name here. (Ex : 123456.pdf)</td>
                </tr>
                <tr>
                  <td colspan="2">Image Zip Folder Instruction</td>
                  <td>Create a Zip file containg files of all images/pdf/doc of profile icon and certificate with same name which are mentioned in excel file</td>
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
                Student uploaded successfully.
            </div>
            @if(session('message123'))
              <div class="text-danger mt-4 mb-2" style="font-size: 20px;text-align: center;">
                  But sl. no. {{ session('message123') }} already uploaded
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