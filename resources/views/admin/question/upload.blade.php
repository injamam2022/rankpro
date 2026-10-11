@extends('layouts.backend')


@section('css_after')
<style>
    .rp-upload-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 1.5rem;
    }
    .rp-upload-step {
        background: #f7f8fc;
        border: 1px solid #e6e8f0;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 13px;
        line-height: 1.4;
        color: #334155;
    }
    .rp-upload-step strong {
        display: block;
        font-size: 12px;
        letter-spacing: .02em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 4px;
    }
    .rp-upload-hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }
    .rp-upload-summary {
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 1rem;
        font-size: 14px;
    }
    .rp-upload-summary.ok { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
    .rp-upload-summary.warn { background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; }
    .rp-upload-summary.err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .rp-upload-summary ul { margin: 8px 0 0; padding-left: 18px; }
</style>
@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <div class="" style="width: calc(100% - 280px);">
                <h1 class="page-header mb-0">Bulk Question Upload</h1>
                <div class="text-muted small mt-1">Upload an XLSX file (and optional Image Zip) using the current question structure.</div>
            </div>
            <div class="rp-actions">
                <a href="javascript:void(0);" class="rp-act rp-act-view rp-act-label" title="Instructions" onclick="openInstruction();">
                    <i class="material-icons">help_outline</i>
                    Instruction
                </a>
                <a href="{{ route('admin.question.upload_sample') }}" class="rp-act rp-act-download rp-act-label" title="Download sample with recent 5 questions">
                    <i class="material-icons">download</i>
                    Download Sample
                </a>
            </div>
        </div>

        <div class="rp-upload-steps">
            <div class="rp-upload-step"><strong>Step 1</strong> Download the sample (mixed normal MCQs + 2 image question/answer examples).</div>
            <div class="rp-upload-step"><strong>Step 2</strong> Fill new rows. Use Chapter / Topic / Sub Topic IDs from the admin lists.</div>
            <div class="rp-upload-step"><strong>Step 3</strong> For images: put the file name in the cell, set Is OptionN Image = 1, and include those files in a Zip.</div>
            <div class="rp-upload-step"><strong>Step 4</strong> Select Subject, choose XLSX (+ Image Zip if needed), then Submit.</div>
        </div>

        @if(session('upload_error'))
            <div class="rp-upload-summary err">{{ session('upload_error') }}</div>
        @endif

        @if($errors->any())
            <div class="rp-upload-summary err">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success123'))
            @php
                $imported = (int) session('upload_imported', 0);
                $skipped = (int) session('upload_skipped', 0);
                $uploadErrors = session('upload_errors', []);
            @endphp
            <div class="rp-upload-summary {{ $imported > 0 ? 'ok' : 'warn' }}">
                <strong>Import finished.</strong>
                Imported: {{ $imported }} &nbsp;|&nbsp; Skipped duplicates: {{ $skipped }}
                @if(!empty($uploadErrors))
                    <div class="mt-2"><strong>Issues ({{ count($uploadErrors) }}):</strong>
                        <ul>
                            @foreach(array_slice($uploadErrors, 0, 15) as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                            @if(count($uploadErrors) > 15)
                                <li>…and {{ count($uploadErrors) - 15 }} more</li>
                            @endif
                        </ul>
                    </div>
                @endif
                @if(session('message123'))
                    <div class="mt-2">Duplicate sl. no.: {{ session('message123') }}</div>
                @endif
            </div>
        @endif

        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.question.upload_save')}}" method="post" enctype="multipart/form-data" onsubmit="return formValidation();" id="bulkUploadForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="subject_id">Subject <span class="text-danger">*</span></label>
                            <select class="form-control" id="subject_id" name="subject_id">
                                <option value="">Select</option>
                                @foreach($subject_list as $key => $value)
                                    <option value="{{$value->id}}" @selected(old('subject_id') == $value->id)>{{$value->name}}</option>
                                @endforeach
                            </select>
                            <div class="rp-upload-hint">All rows are imported under this subject. Chapter IDs must belong to it.</div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="csv_file">XLSX <span class="text-danger">*</span></label>
                            <input class="form-control" id="csv_file" type="file" name="csv_file" accept=".xlsx,.xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                            <div class="rp-upload-hint">Max 20 MB. Keep the sample header row unchanged.</div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="zip_file">Image Zip</label>
                            <input class="form-control" id="zip_file" type="file" name="zip_file" accept=".zip,application/zip" />
                            <div class="rp-upload-hint">Optional. Flat zip of png/jpg/jpeg/gif/webp named exactly as in the sheet.</div>
                        </div>

                        <div class="col-lg-12 mt-4 text-center">
                            <button class="rp-act rp-act-add" type="submit" id="bulkSubmitBtn">
                                <i class="material-icons">check</i>
                                Submit
                            </button>
                        </div>
                        <div class="mb-5">&nbsp;</div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="instructionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Bulk Upload Instructions</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="small text-muted mb-3">
              Download Sample mixes <strong>normal text MCQs</strong> with <strong>2 examples where both the question and the answer options use images</strong>.
              When images are included, the download is a zip with <code>question_sample.xlsx</code> plus an <code>images/</code> folder.
            </p>
            <table class="table1 table-bordered" style="width:100%;font-size: 12px;">
              <tbody>
                <tr>
                  <td style="width:10%;">A</td>
                  <td style="width:22%;">Sl No</td>
                  <td style="width:68%;">Serial number (for your reference / duplicate messages)</td>
                </tr>
                <tr>
                  <td>B</td>
                  <td>Chapter</td>
                  <td>Chapter ID (Admin → Question → Chapter → first column). Must belong to the Subject selected on upload.</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Topic</td>
                  <td>Topic ID under that Chapter</td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Sub Topic</td>
                  <td>Sub Topic ID under that Topic</td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Difficulty Level</td>
                  <td>Required: 1 = Easy, 2 = Medium, 3 = Hard</td>
                </tr>
                <tr>
                  <td>F</td>
                  <td>Source</td>
                  <td>Source ID</td>
                </tr>
                <tr>
                  <td>G</td>
                  <td>Question Source</td>
                  <td>Question Source ID</td>
                </tr>
                <tr>
                  <td>H</td>
                  <td>Question Text</td>
                  <td>Question text (plain text is fine). Required unless Question Image is set.</td>
                </tr>
                <tr>
                  <td>I</td>
                  <td>Question Image</td>
                  <td>Image file name only (e.g. diagram.png). Include that file in the Image Zip.</td>
                </tr>
                <tr>
                  <td>J</td>
                  <td>Option1</td>
                  <td>Option text, OR image file name when Is Option1 Image = 1</td>
                </tr>
                <tr>
                  <td>K</td>
                  <td>Is Option1 Image</td>
                  <td>1 = option cell is an image file name; 0 = text</td>
                </tr>
                <tr>
                  <td>L</td>
                  <td>Ans Behavior Tag 1</td>
                  <td>1 = Correct; 2 = Close Call; 3 = Silly Mistake; 4 = Irrelevant</td>
                </tr>
                <tr>
                  <td>M–U</td>
                  <td>Options 2–4</td>
                  <td>Same pattern as Option1 / Is Option Image / Ans Behavior Tag</td>
                </tr>
                <tr>
                  <td>V</td>
                  <td>Answer</td>
                  <td>Correct option: 1, 2, 3, or 4</td>
                </tr>
                <tr>
                  <td>W</td>
                  <td>Solution Video Link</td>
                  <td>Optional embed URL</td>
                </tr>
                <tr>
                  <td>X</td>
                  <td>Solution</td>
                  <td>Solution text</td>
                </tr>
                <tr>
                  <td>Y</td>
                  <td>Status</td>
                  <td>1 = Active; 0 = Inactive</td>
                </tr>
                <tr>
                  <td colspan="2">Image Zip</td>
                  <td>
                    Create a .zip containing image files (png/jpg/jpeg/gif/webp) with the <strong>exact names</strong> used in Question Image / Option columns.
                    Nested folders are OK — files are flattened on upload. Do not put the XLSX inside this zip when uploading (only images).
                  </td>
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
        const maxSizeXlsx = 20 * 1024 * 1024;
        const maxSizeZip = 50 * 1024 * 1024;

        function formValidation(){
            var successFlag = true;
            var subject = document.getElementById('subject_id');
            var csv_file = document.getElementById('csv_file');
            var zip_file = document.getElementById('zip_file');

            if (subject.value) {
                subject.classList.remove('dangerBoader');
            } else {
                subject.classList.add('dangerBoader');
                successFlag = false;
            }

            if (csv_file.files.length) {
                var file = csv_file.files[0];
                var name = (file.name || '').toLowerCase();
                var okExt = name.endsWith('.xlsx') || name.endsWith('.xls') || name.endsWith('.csv');
                if (!okExt || file.size > maxSizeXlsx) {
                    csv_file.classList.add('dangerBoader');
                    successFlag = false;
                } else {
                    csv_file.classList.remove('dangerBoader');
                }
            } else {
                csv_file.classList.add('dangerBoader');
                successFlag = false;
            }

            if (zip_file.files.length) {
                var z = zip_file.files[0];
                var zname = (z.name || '').toLowerCase();
                if (!zname.endsWith('.zip') || z.size > maxSizeZip) {
                    zip_file.classList.add('dangerBoader');
                    successFlag = false;
                } else {
                    zip_file.classList.remove('dangerBoader');
                }
            }

            if (successFlag) {
                var btn = document.getElementById('bulkSubmitBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="material-icons">hourglass_top</i> Uploading…';
                }
            }

            return successFlag;
        }

        function openInstruction(){
            $('#instructionModal').modal('show');
        }
    </script>
@endsection
