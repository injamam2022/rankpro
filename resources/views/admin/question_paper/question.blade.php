@extends('layouts.backend')


@section('css_after')
    <style>
        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-dropdown { z-index: 2000; }
        .qb-qtext { max-width: 720px; }
        .qb-qtext img { max-width: 180px; height: auto; }
    </style>
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="flex:1;">
                <h1 class="page-header mb-0">Question Paper Question</h1>
                <p class="text-muted mb-0 mt-1" style="font-size:14px;">{{ $details->name }}</p>
            </div>
            <div class="rp-actions">
                <a href="{{route('admin.question_paper')}}" class="rp-act rp-act-ghost rp-act-label" title="Back to Question Bank">
                    <i class="material-icons">arrow_back</i>
                    Back
                </a>
                <a href="{{route('admin.question_paper.download_question',['id'=>$details->id])}}" class="rp-act rp-act-download rp-act-label" title="Download question PDF">
                    <i class="material-icons">download</i>
                    Question PDF
                </a>
                <a href="{{route('admin.question_paper.download_answer',['id'=>$details->id])}}" class="rp-act rp-act-download rp-act-label" title="Download answer PDF">
                    <i class="material-icons">download</i>
                    Answer PDF
                </a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Video Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="qb-qtext">{!! $row->question_text !!}</td>
                        <td>{{ $row->answer }}</td>
                        <td>{{ $row->solution_video_link }}</td>
                        <td>
                            @include('admin.include.icon_btn', [
                                'href' => 'javascript:void(0);',
                                'variant' => 'swap',
                                'icon' => 'swap_horiz',
                                'title' => 'Change question',
                                'onclick' => 'changeQuestion('.(int)$row->question_paper_question_id.');',
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No questions in this paper yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Question</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('admin.question_paper.change_question')}}" method="get" onsubmit="return qbChangeSubmit();">
                <input type="hidden" name="question_paper_id" id="question_paper_id" value="{{$details->id}}">
                <input type="hidden" name="question_paper_question_id" id="question_paper_question_id" value="">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="form-label" for="question_id">Question <span class="text-danger">*</span></label>
                        <select class="form-control" id="question_id" name="question_id">
                            <option value="">Type to search questions…</option>
                        </select>
                        <div class="form-text mt-2">Type at least 2 characters. Only matching questions are loaded — the full bank is not fetched.</div>
                    </div>
                    <div class="col-lg-12 mt-5 text-center">
                        <button class="rp-act rp-act-ghost cancelButton" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                            Cancel
                        </button>
                        <button class="rp-act rp-act-add" type="submit">
                            <i class="material-icons">check</i>
                            Submit
                        </button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

@endsection


@section('js_after')
    <script type="text/javascript">
        $(function () {
            $('#question_id').select2({
                dropdownParent: $('#exampleModal'),
                placeholder: 'Type to search questions…',
                allowClear: true,
                minimumInputLength: 2,
                width: '100%',
                ajax: {
                    url: "{{ route('admin.question_paper.search_questions') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            question_paper_id: $('#question_paper_id').val(),
                            question_paper_question_id: $('#question_paper_question_id').val()
                        };
                    },
                    processResults: function (data) {
                        return data;
                    }
                }
            });
        });

        function changeQuestion(question_paper_question_id){
            document.getElementById('question_paper_question_id').value = question_paper_question_id;
            $('#question_id').val(null).trigger('change');
            $('#exampleModal').modal('show');
        }

        function qbChangeSubmit(){
            if (!$('#question_id').val()) {
                alert('Please search and select a question.');
                return false;
            }
            return true;
        }
    </script>
@endsection
