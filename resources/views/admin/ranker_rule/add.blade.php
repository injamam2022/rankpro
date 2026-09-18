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
            <h1 class="page-header mb-0">Add Ranker Rule</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{ route('admin.ranker_rule.save') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @foreach($languages as $language)
                            <div class="col-sm-12 alert alert-success" style="padding:30px;margin-top:20px;">
                                    <h4 class="" style="font-weight: blod;">
                                        Language : {{$language->name}}
                                    </h4>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="Rule" class="custom-label">Rule</label>
                                                <div class="form-group">
                                                        <label for="name" class="col-sm-2 col-md-3 control-label"></label>
                                                        <div class="col-sm-12">
                                                            <textarea id="editor<?=$language->id?>" name="rule_<?=$language->id?>"class="form-control" rows="5"></textarea>
                                                        </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>
                        @endforeach

                        <div class="col-sm-12">
                            <label class="form-label">Status</label>
                            <div class="form-control d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" checked value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check ms-3">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-5 text-center">
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js_after')
    <script>

        @foreach($languages as $language)
            ClassicEditor
                .create(document.querySelector('#editor{{ $language->id }}'))
                .catch(error => {
                    console.error(error);
                });
        @endforeach
    </script>
    <script type="text/javascript">
        
        function formValidation(){
            var data = {};
            var successFlag = true;

            data.name = document.getElementById('name').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }

            console.log(data);

            return successFlag;
        }
    </script>
@endsection
