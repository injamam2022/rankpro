@extends('layouts.backend')


@section('css_after')

@endsection

@section('content')
    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4" style="display:flex;">
            <div class="" style="width: calc(100% - 50px);">
                <h1 class="page-header mb-0">Questions tagged to {{ $details->name }}</h1>
            </div>
            <div>
                <a href="{{ route('admin.question_source') }}" class="rp-act rp-act-ghost">
                    <i class="material-icons">arrow_back</i>
                    Back
                </a>
            </div>
        </div>
        <form method="get" class="admin-list-bar">
            <input type="hidden" name="id" value="{{ $details->id }}">
            @include('admin.include.list_toolbar', [
                'search_placeholder' => 'Search question, subject, chapter...',
                'clear_url' => route('admin.question_source.view', ['id' => $details->id]),
            ])
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Chapter</th>
                    <th>Question</th>
                    <th>Added</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $row)
                    @php
                        $plain = trim(html_entity_decode(strip_tags((string) $row->question_text), ENT_QUOTES, 'UTF-8'));
                        $plain = preg_replace('/\s+/u', ' ', $plain);
                        if (mb_strlen($plain) > 180) {
                            $plain = mb_substr($plain, 0, 180) . '…';
                        }
                    @endphp
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->subject_name }}</td>
                        <td>{{ $row->chapter_name }}</td>
                        <td>
                            {{ $plain !== '' ? $plain : '—' }}
                            @if($row->question_image)
                                <div>
                                    <img src="{{ question_media_url($row->question_image) }}" style="width:50px;">
                                </div>
                            @endif
                        </td>
                        <td class="admin-when">{{ \App\Support\AdminListQuery::when($row->created_at) }}</td>
                        <td>
                            @if($row->status == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td>
                            <div class="rp-actions">
                                @include('admin.include.icon_btn', [
                                    'href' => route('admin.question.edit', ['id' => $row->id]),
                                    'variant' => 'edit',
                                    'icon' => 'edit',
                                    'title' => 'Edit question',
                                ])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No questions are tagged to this source.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('admin.include.list_pager')
    </div>

@endsection


@section('js_after')

@endsection
