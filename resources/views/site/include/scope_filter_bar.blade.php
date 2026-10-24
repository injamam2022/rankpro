@php
    $examType = \App\Support\StudentScope::examType();
    $subjectId = \App\Support\StudentScope::subjectId();
    $subjects = $scope_subjects ?? collect();
    $hideScopeMode = $hideScopeMode ?? false;

    $modeLabel = 'Overall';
    if ($examType === '1') {
        $modeLabel = 'Online';
    } elseif ($examType === '2') {
        $modeLabel = 'Offline';
    }

    $subjectLabel = 'Combined';
    foreach ($subjects as $subj) {
        if ((string) $subj->id === (string) $subjectId) {
            $subjectLabel = $subj->name;
            break;
        }
    }
@endphp
<div class="rp-card rp-filter rp-filter--global {{ $hideScopeMode ? 'rp-filter--subject-only' : '' }}" role="navigation" aria-label="{{ $hideScopeMode ? 'Subject filter' : 'Mode and subject filters' }}">
    @unless($hideScopeMode)
    <div class="rp-filter__group">
        <span class="rp-filter__label"><i class="fas fa-sliders-h"></i> Mode:</span>
        <div class="rp-seg">
            <a href="{{ \App\Support\StudentScope::filterUrl('', $subjectId) }}"
               class="{{ $examType === '' ? 'is-active-pink' : '' }}">Overall</a>
            <a href="{{ \App\Support\StudentScope::filterUrl('1', $subjectId) }}"
               class="{{ $examType === '1' ? 'is-active-pink' : '' }}">Online</a>
            <a href="{{ \App\Support\StudentScope::filterUrl('2', $subjectId) }}"
               class="{{ $examType === '2' ? 'is-active-pink' : '' }}">Offline</a>
        </div>
    </div>
    @endunless
    <div class="rp-filter__group">
        <span class="rp-filter__label">Subject:</span>
        <div class="rp-seg">
            <a href="{{ \App\Support\StudentScope::filterUrl($examType, '', !$hideScopeMode) }}"
               class="{{ $subjectId === '' ? 'is-active-blue' : '' }}">Combined</a>
            @foreach($subjects as $subj)
                <a href="{{ \App\Support\StudentScope::filterUrl($examType, $subj->id, !$hideScopeMode) }}"
                   class="{{ (string) $subjectId === (string) $subj->id ? 'is-active-blue' : '' }}">{{ $subj->name }}</a>
            @endforeach
        </div>
        <div class="rp-filter__active">
            Active:
            @unless($hideScopeMode)
                <strong>{{ $modeLabel }}</strong> &middot;
            @endunless
            <em>{{ $subjectLabel }}</em>
        </div>
    </div>
</div>
