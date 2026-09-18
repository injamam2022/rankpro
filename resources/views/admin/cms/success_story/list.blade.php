@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Success Story</h1>
            </div>
            <div>
                <a href="{{route('admin.success_story.add')}}" style="display: flex;font-size: 20px;text-decoration: none !important;">
                    <i class="material-icons">add</i>
                    Add
                </a>
            </div>
        </div>
        <!-- Simple DataTables example-->
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Description</th>
                    {{-- <th>Image/Video</th> --}}
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($list)>0)
                    @foreach ($list as $key=>$row)
                        <tr>
                            <td>
                                {{$row->name}}
                            </td>
                            <td>
                                {{$row->address}}
                            </td>
                            <td>
                                @if($row->descriptions->isNotEmpty())
                                    {!! $row->descriptions->first()->text !!}
                                @else
                                    No Banner description available
                                @endif
                            </td>
                            {{-- <td>
                                @if($row->image)
                                    @if(Str::endsWith($row->image, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                        <img src="{{ asset('uploads/success_story/thumbnail/'.$row->image) }}" style="width:50px;">
                                    @else
                                        {{ $row->image }}
                                    @endif
                                @endif
                            </td> --}}
                            <td>
                                <a href="{{route('admin.success_story.change',['id'=>$row->id])}}" title="Click to change status" style="text-decoration: none;" onclick="return confirm('Do you realy want to change status?');">
                                    @if($row->status == 1)
                                        <span class="btn btn-success btn-sm">Active</span>
                                    @else
                                        <span class="btn btn-danger btn-sm">Inactive</span>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.success_story.edit',['id'=>$row->id])}}" class="btn btn-primary btn-sm mr-1"><i class="material-icons">edit</i></a>
                                <a href="{{route('admin.success_story.delete',['id'=>$row->id])}}" class="btn btn-danger btn-sm mr-1" onclick="return confirm('Do you realy want to delete?');"><i class="material-icons">close</i></a>
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
