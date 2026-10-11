@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Chapter</h1>
            </div>
            <div>
                @if(isUserPermitted('chapter', 'add'))
                    <a href="{{route('admin.chapter.add')}}" class="rp-act rp-act-add">
                        <i class="material-icons">add</i>
                        Add
                    </a>
                @endif
            </div>
        </div>
        <form method="get" class="admin-list-bar">
            <label class="mb-0" for="class_level">Class</label>
            <select name="class_level" id="class_level" class="form-control" style="width:180px;" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="11" @if(($class_filter ?? '') === '11') selected @endif>Class 11</option>
                <option value="12" @if(($class_filter ?? '') === '12') selected @endif>Class 12</option>
                <option value="0" @if(($class_filter ?? '') === '0') selected @endif>Both / Not set</option>
            </select>
            @include('admin.include.list_toolbar')
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Class</th>
                    <th>Name</th>
                    <th>Added</th>
                    <th>Updated</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $row)
                    <tr>
                        <td>{{$row->id}}</td>
                        <td>{{$row->subject_name}}</td>
                        <td>
                            @if((int)$row->class_level === 11) Class 11
                            @elseif((int)$row->class_level === 12) Class 12
                            @else <span class="text-muted">Not set</span>
                            @endif
                        </td>
                        <td>{{$row->name}}</td>
                        <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->created_at) }}</td>
                        <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->updated_at) }}</td>
                        <td>
                            @include('admin.include.icon_btn', [
                                'href' => route('admin.chapter.change', ['id' => $row->id]),
                                'variant' => $row->status == 1 ? 'on' : 'off',
                                'icon' => $row->status == 1 ? 'toggle_on' : 'toggle_off',
                                'title' => $row->status == 1 ? 'Active — click to deactivate' : 'Inactive — click to activate',
                                'onclick' => "return confirm('Do you really want to change status?');",
                            ])
                        </td>
                        <td>
                            <div class="rp-actions">
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.chapter.edit', ['id' => $row->id]),
                                    'variant' => 'edit',
                                    'icon' => 'edit',
                                    'title' => 'Edit',
                                ])
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.chapter.delete', ['id' => $row->id]),
                                    'variant' => 'delete',
                                    'icon' => 'delete',
                                    'title' => 'Delete',
                                    'onclick' => "return rpConfirmPost(this.href, 'Do you really want to delete?');",
                                ])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('admin.include.list_pager')
    </div>

@endsection


@section('js_after')

@endsection
