@extends('layouts.backend')

@section('content')
@php
    $studentName = trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: 'Student #'.$user->id;
@endphp
<div class="container-xl px-5">
    <div class="d-flex mt-10 mb-4 align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-header mb-1">Online subscriptions</h1>
            <p class="text-muted mb-0">{{ $studentName }} · {{ $user->email_id }}</p>
        </div>
        <a href="{{ route('admin.student') }}" class="btn btn-secondary btn-sm">Back to students</a>
    </div>

    <form action="{{ route('admin.student.subscriptions.save') }}" method="post" class="card">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="card-body">
            <p class="text-muted">Tick the online products this student should access. They can hold more than one. Series Tests will show a tab for each assigned series package.</p>
            <div class="row g-3">
                @forelse($packages as $package)
                    @php $state = $assigned[$package->code] ?? ['has' => false, 'label' => 'Not assigned']; @endphp
                    <div class="col-md-6">
                        <label class="d-block border rounded-3 p-3 h-100" style="cursor:pointer;">
                            <div class="d-flex align-items-start gap-2">
                                <input type="checkbox" name="packages[]" value="{{ $package->code }}" class="mt-1"
                                       @checked($state['has'] || !empty($state['included']))
                                       @disabled(!empty($state['included']) && $package->code === 'chapter_master')>
                                <div>
                                    <div class="fw-semibold">{{ $package->name }}</div>
                                    <div class="small text-muted">{{ $package->tagline }}</div>
                                    <div class="small mt-1">
                                        <span class="badge {{ ($state['has'] || !empty($state['included'])) ? 'bg-success' : 'bg-light text-dark border' }}">{{ $state['label'] }}</span>
                                    </div>
                                    @if($package->code === 'mission_720' && $state['has'])
                                        <div class="small text-muted mt-1">Unlocks entire Rank Chapter Master for this student.</div>
                                    @endif
                                    @if(!empty($state['included']))
                                        <div class="small text-muted mt-1">Entire Chapter Master is enabled with Rank Pro Mission 720.</div>
                                    @elseif(($state['chapters'] ?? collect())->count())
                                        <ul class="small mb-0 mt-2 ps-3">
                                            @foreach($state['chapters'] as $line)
                                                <li>{{ \App\Support\CoursePackageAccess::chapterExamLabel($line->chapter) }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </label>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-warning mb-0">No assignable packages found. Run CoursePackageSeeder.</div></div>
                @endforelse
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <span class="small text-muted">Unchecking removes access, including a previous paid order for that product.</span>
            <button type="submit" class="btn btn-primary">Save subscriptions</button>
        </div>
    </form>
</div>
@endsection
