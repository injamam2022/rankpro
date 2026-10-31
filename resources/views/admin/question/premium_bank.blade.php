@extends('layouts.backend')

@section('css_after')
<style>
    .pb-stat { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; }
    .pb-stat strong { display:block; font-size:1.35rem; color:#0f172a; }
    .pb-stat span { font-size:12px; color:#64748b; }
    .pb-ch { border:1px solid #eaecf0; border-radius:10px; margin-bottom:10px; background:#fff; overflow:hidden; }
    .pb-ch-head { display:flex; justify-content:space-between; gap:12px; align-items:center; padding:12px 14px; cursor:pointer; }
    .pb-topics { display:none; border-top:1px solid #f1f5f9; background:#f8fafc; }
    .pb-ch.open .pb-topics { display:block; }
    .pb-topic { display:flex; justify-content:space-between; padding:8px 14px 8px 22px; border-top:1px solid #eef2f7; font-size:13px; }
    .badge-soon { background:#fffaeb; color:#b54708; border:1px solid #fedf89; border-radius:999px; padding:2px 8px; font-size:11px; font-weight:700; }
    .badge-ok { background:#ecfdf3; color:#027a48; border:1px solid #abefc6; border-radius:999px; padding:2px 8px; font-size:11px; font-weight:700; }
</style>
@endsection

@section('content')
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-3 align-items-center justify-content-between flex-wrap" style="gap:12px;">
        <div>
            <h1 class="page-header mb-0">Premium Question Bank</h1>
            <div class="text-muted small mt-1">Course Packages / Chapter Master use premium questions. Empty chapters/topics show as Coming soon for students.</div>
        </div>
        <div class="rp-actions">
            <a href="{{ route('admin.question', ['is_premium' => 1]) }}" class="rp-act rp-act-view rp-act-label"><i class="material-icons">list</i> Premium list</a>
            <a href="{{ route('admin.question.add') }}" class="rp-act rp-act-add rp-act-label"><i class="material-icons">add</i> Add question</a>
            <a href="{{ route('admin.question.upload') }}" class="rp-act rp-act-download rp-act-label"><i class="material-icons">upload</i> Bulk upload</a>
        </div>
    </div>

    <form method="get" class="mb-3" style="max-width:360px;">
        <label class="form-label">Subject</label>
        <select class="form-control" name="subject_id" onchange="this.form.submit()">
            @foreach($subjects as $s)
                <option value="{{ $s->id }}" @selected((string)$subject_id === (string)$s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="pb-stat"><strong>{{ $stats['premium_total'] }}</strong><span>Premium questions in subject</span></div></div>
        <div class="col-md-3"><div class="pb-stat"><strong>{{ $stats['chapters_ready'] }}</strong><span>Chapters ready</span></div></div>
        <div class="col-md-3"><div class="pb-stat"><strong>{{ $stats['chapters_soon'] }}</strong><span>Chapters Coming soon</span></div></div>
        <div class="col-md-3"><div class="pb-stat"><strong>{{ $stats['topics_ready'] }} / {{ $stats['topics_ready'] + $stats['topics_soon'] }}</strong><span>Topics ready</span></div></div>
    </div>

    @forelse($chapters as $ch)
        <div class="pb-ch">
            <div class="pb-ch-head" onclick="this.parentElement.classList.toggle('open')">
                <div>
                    <div style="font-weight:600;">{{ $ch['name'] }}</div>
                    <div class="small text-muted">{{ $ch['questions'] }} premium Q · {{ count($ch['topics']) }} topics</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($ch['coming_soon'])
                        <span class="badge-soon">Coming soon</span>
                    @else
                        <span class="badge-ok">Ready</span>
                    @endif
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.question', ['subject_id' => $subject_id, 'chapter_id' => $ch['id'], 'is_premium' => 1]) }}" onclick="event.stopPropagation()">Manage</a>
                    <i class="material-icons text-muted">expand_more</i>
                </div>
            </div>
            <div class="pb-topics">
                @forelse($ch['topics'] as $t)
                    <div class="pb-topic">
                        <div>{{ $t['name'] }} <span class="text-muted">({{ $t['questions'] }} Q)</span></div>
                        @if($t['coming_soon'])
                            <span class="badge-soon">Coming soon</span>
                        @else
                            <span class="badge-ok">Ready</span>
                        @endif
                    </div>
                @empty
                    <div class="pb-topic text-muted">No topics under this chapter.</div>
                @endforelse
            </div>
        </div>
    @empty
        <div class="alert alert-light border">Select a subject to see premium coverage.</div>
    @endforelse
</div>
@endsection
