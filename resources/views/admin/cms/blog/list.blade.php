@extends('layouts.backend')

@section('css_after')
@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Blog</h1>
            </div>
            <div>
                <a href="{{ route('admin.blog.add') }}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i>
                    Add
                </a>
            </div>
        </div>
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title / URL</th>
                    <th>Description</th>
                    <th>Published</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list) > 0)
                    @foreach ($list as $row)
                        <tr>
                            <td>
                                @if($row->image)
                                    <img src="{{ $row->thumbnail_url }}" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <strong>{{ $row->title }}</strong>
                                <div style="font-size:12px;color:#6b7280;">/blog/{{ $row->slug }}</div>
                            </td>
                            <td style="max-width:280px;">
                                {{ \Illuminate\Support\Str::limit(strip_tags((string) $row->excerpt), 90) ?: '—' }}
                            </td>
                            <td>
                                {{ $row->published_at ? $row->published_at->format('d M Y') : '—' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.blog.change', ['id' => $row->id]) }}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you really want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('blog.show', $row->slug) }}" target="_blank" class="btn btn-secondary btn-sm mr-1" title="View"><i class="material-icons">visibility</i></a>
                                <a href="{{ route('admin.blog.edit', ['id' => $row->id]) }}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{ route('admin.blog.delete', ['id' => $row->id]) }}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you really want to delete?');"><i class="material-icons">close</i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection

@section('js_after')
@endsection
