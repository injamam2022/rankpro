@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 400px);">
                <h1 class="page-header mb-0">Offline Exam Question</h1>
            </div>
            <!-- <div style="width:200px;text-align: center;">
                <a href="{{route('admin.offline_exam.download_question',['id'=>$details->id])}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">download</i> 
                    Question PDF
                </a>
            </div>
            <div style="width:200px;text-align: center;">
                <a href="{{route('admin.offline_exam.download_answer',['id'=>$details->id])}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">download</i> 
                    Answer PDF
                </a>
            </div> -->
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Video Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>{{$row->question_text}}</td>
                            <td>{{$row->answer}}</td>
                            <td>{{$row->video_link}}</td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm mr-1" onclick="changeQuestion('{{$row->id}}','{{$row->question_text}}','{{$row->video_link}}','{{$row->answer}}');">Change</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Question</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('admin.offline_exam.change_question')}}" method="get">
                <input type="hidden" name="exam_id" id="exam_id" value="{{$details->id}}">
                <input type="hidden" name="offline_exam_question_id" id="offline_exam_question_id" value="">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="form-label" for="question_text">Question <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="question_text" id="question_text" value="">
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="answer">Answer <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="answer" id="answer" value="">
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="video_link">Video Link <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="video_link" id="video_link" value="">
                    </div>
                    <div class="col-lg-12 mt-5 text-center">
                        <button class="btn btn-secondary cancelButton"  type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                        <button class="btn btn-primary" type="submit">Submit</button>
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
        function changeQuestion(id,question_text,video_link,answer){
            document.getElementById('offline_exam_question_id').value = id;
            document.getElementById('question_text').value = question_text;
            document.getElementById('answer').value = answer;
            document.getElementById('video_link').value = video_link;

            $('#exampleModal').modal('show');
        }
    </script>

@endsection