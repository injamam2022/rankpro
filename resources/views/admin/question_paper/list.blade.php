@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Question Bank</h1>
            </div>
            <div>
                <a href="{{route('admin.question_paper.add')}}" class="rp-act rp-act-add">
                    <i class="material-icons">add</i>
                    Add
                </a>
            </div>
        </div>
        <form method="get" class="admin-list-bar">
            @include('admin.include.list_toolbar')
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>No of question</th>
                    <th>Totals marks for exam</th>
                    <th>Added</th>
                    <th>Updated</th>
                    <th>Status</th>
                    <th>Question</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $row)
                    <tr>
                        <td>{{$row->name}}</td>
                        <td>{{$row->no_of_question}}</td>
                        <td>{{$row->totals_marks_for_exam}}</td>
                        <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->created_at) }}</td>
                        <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->updated_at) }}</td>
                        <td>
                            @include('admin.include.icon_btn', [
                                'href' => route('admin.question_paper.change', ['id' => $row->id]),
                                'variant' => $row->status == 1 ? 'on' : 'off',
                                'icon' => $row->status == 1 ? 'toggle_on' : 'toggle_off',
                                'title' => $row->status == 1 ? 'Active — click to deactivate' : 'Inactive — click to activate',
                                'onclick' => "return rpConfirmPost(this.href, 'Do you really want to change status?');",
                            ])
                        </td>
                        <td>
                            @include('admin.include.icon_btn', [
                                'href' => route('admin.question_paper.question', ['id' => $row->id]),
                                'variant' => 'view',
                                'icon' => 'library_books',
                                'title' => 'View questions',
                            ])
                        </td>
                        <td>
                            <div class="rp-actions">
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.question_paper.edit', ['id' => $row->id]),
                                    'variant' => 'edit',
                                    'icon' => 'edit',
                                    'title' => 'Edit',
                                ])
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.question_paper.view', ['id' => $row->id]),
                                    'variant' => 'preview',
                                    'icon' => 'visibility',
                                    'title' => 'Preview paper',
                                ])
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.question_paper.delete', ['id' => $row->id]),
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
