@extends('layouts.backend')

@section('css_after')
    <style type="text/css">
        .tableListAll {
            display: flex;
            border-right: 1px solid #d4dae3;
            border-left: 1px solid #d4dae3;
            border-bottom: 1px solid #d4dae3;
        }
        .tableListTitle {
            width: 100%;
            border: 1px solid #d4dae3;
            padding: 6px 6px 6px 30px;
            margin-top: -1px;
        }
        .custom-control-inline {
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            margin-right: 16px;
        }
        .tableListTitleAll {
            margin-top: 20px;
        }
        .tableListLeft {
            width: 24%;
            border-right: 1px solid #d4dae3;
        }
        .tableListRight {
            width: calc(100% - 24%);
        }
        .tableListRight .custom-control.custom-checkbox {
            margin-right: 0px;
            padding: 2px 2px 2px 34px;
            width: 14.28%;
        }
        .custom-control-label {
            vertical-align: top;
            position: relative;
            margin-bottom: 0;
            margin-left: 10px;
        }
        .custom-control-input:disabled ~ .custom-control-label {
            color: #6c757d;
        }
    </style>
@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Edit Admin User Role</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{ route('admin.admin_user_role.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{$role->id}}">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" value="{{ $role->name }}" />
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ $role->description }}</textarea>
                        </div>

                        <div class="form-group row">
                            <label class="col-12 tableListHeader">Permission List</label>
                            @foreach($list as $result)
                                <div class="col-sm-12 tableListTitleAll">
                                    <div class="custom-control custom-checkbox custom-control-inline tableListTitle">
                                        <input class="custom-control-input menucheck" type="checkbox" name="permission[{{ $result->id }}]" id="permission_{{ $result->id }}" value="{{ $result->id }}"
                                            {{ isset($permissions[$result->id]) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="permission_{{ $result->id }}">
                                            <strong style="font-weight: 700;">{{ $result->name }}</strong>
                                        </label>
                                    </div>
                                </div>

                                @foreach($result->list as $value)
                                    <div class="col-sm-12">
                                        <div class="tableListAll">
                                            <div class="tableListLeft">
                                                <div class="tableListName">
                                                    {{ $value->name }}
                                                </div>
                                            </div>
                                            <div class="tableListRight" id="submenu_{{ $result->id }}">
                                                @if($value->is_list)
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="custom-control-input" type="checkbox" name="permission_list[{{ $value->id }}]" id="permission_list_{{ $value->id }}" value="{{ $value->id }}"
                                                            {{ isset($permissions[$value->id]) && $permissions[$value->id]->is_list ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="permission_list_{{ $value->id }}">List</label>
                                                    </div>
                                                @endif
                                                @if($value->is_add)
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="custom-control-input" type="checkbox" name="permission_add[{{ $value->id }}]" id="permission_add_{{ $value->id }}" value="{{ $value->id }}"
                                                            {{ isset($permissions[$value->id]) && $permissions[$value->id]->is_add ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="permission_add_{{ $value->id }}">Add</label>
                                                    </div>
                                                @endif
                                                @if($value->is_edit)
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="custom-control-input" type="checkbox" name="permission_edit[{{ $value->id }}]" id="permission_edit_{{ $value->id }}" value="{{ $value->id }}"
                                                            {{ isset($permissions[$value->id]) && $permissions[$value->id]->is_edit ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="permission_edit_{{ $value->id }}">Edit</label>
                                                    </div>
                                                @endif
                                                @if($value->is_delete)
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="custom-control-input" type="checkbox" name="permission_delete[{{ $value->id }}]" id="permission_delete_{{ $value->id }}" value="{{ $value->id }}"
                                                            {{ isset($permissions[$value->id]) && $permissions[$value->id]->is_delete ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="permission_delete_{{ $value->id }}">Delete</label>
                                                    </div>
                                                @endif
                                                @if($value->is_reorder)
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="custom-control-input" type="checkbox" name="permission_reorder[{{ $value->id }}]" id="permission_reorder_{{ $value->id }}" value="{{ $value->id }}"
                                                            {{ isset($permissions[$value->id]) && $permissions[$value->id]->is_reorder ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="permission_reorder_{{ $value->id }}">Reorder</label>
                                                    </div>
                                                @endif
                                                @if($value->is_copy)
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="custom-control-input" type="checkbox" name="permission_copy[{{ $value->id }}]" id="permission_copy_{{ $value->id }}" value="{{ $value->id }}"
                                                            {{ isset($permissions[$value->id]) && $permissions[$value->id]->is_copy ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="permission_copy_{{ $value->id }}">Copy</label>
                                                    </div>
                                                @endif
                                                @if($value->is_export)
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input class="custom-control-input" type="checkbox" name="permission_export[{{ $value->id }}]" id="permission_export_{{ $value->id }}" value="{{ $value->id }}"
                                                            {{ isset($permissions[$value->id]) && $permissions[$value->id]->is_export ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="permission_export_{{ $value->id }}">Export</label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" value="1" {{ $role->status == 1 ? 'checked' : '' }} />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" {{ $role->status == 0 ? 'checked' : '' }} />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-5 text-center">
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-secondary cancelButton" type="button">Cancel</a>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                        <div class="mb-5">&nbsp;</div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js_after')
    <script>
        $(document).ready(function() {
            $('.tableListRight').each(function() {
                $(this).find('input[type="checkbox"]').prop('disabled', true);
            });

            $('.menucheck').each(function() {
                if ($(this).is(':checked')) {
                    var submenuId = $(this).attr('id').replace('permission_', '');
                    $('#submenu_' + submenuId + ' input[type="checkbox"]').prop('disabled', false);
                }
            });

            $('.menucheck').on('change', function() {
                var submenuId = $(this).attr('id').replace('permission_', '');
                $('#submenu_' + submenuId + ' input[type="checkbox"]').prop('disabled', !this.checked);
            });
        });
    </script>
@endsection
