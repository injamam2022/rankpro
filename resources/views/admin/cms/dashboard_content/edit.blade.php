@extends('layouts.backend')


@section('css_after')
<style>
    .custom-label {
        margin-top: 0.5rem;
        margin-bottom: -0.75rem;
        display: block;
    }

    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Dashboard Content</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.dashboard_content.update')}}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="{{$details->id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label" for="board"> User Content </label>
                            <textarea class="form-control" id="board" name="board" rows="4">{{$details->board}}</textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="leader_board">Leader Board Content</label>
                            <textarea class="form-control" id="leader_board" name="leader_board" rows="4">{{$details->leader_board}}</textarea>
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
        var board = "";
        var leader_board = "";
        ClassicEditor
                .create(document.querySelector('#board'))
                .then( editor => {
                    board = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
        ClassicEditor
                .create(document.querySelector('#leader_board'))
                .then( editor => {
                    leader_board = editor;
                } )
                .catch(error => {
                    console.error(error);
                });
    </script>
@endsection
