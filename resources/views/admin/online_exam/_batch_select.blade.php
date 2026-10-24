@php
    $selectedBatchIds = collect(old('batches', $selected_batch_ids ?? []))
        ->filter(fn ($id) => $id !== null && $id !== '')
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values();
    if (old('batch_id')) {
        $selectedBatchIds = collect([(int) old('batch_id')]);
    }
    $useMultiBatch = $selectedBatchIds->count() > 1;
@endphp
@if($useMultiBatch)
    <select class="form-control" id="batch_id" name="batches[]" multiple size="{{ min(6, max(3, $selectedBatchIds->count())) }}">
        @foreach($batch_list as $batch)
            <option value="{{ $batch->id }}" @selected($selectedBatchIds->contains((int) $batch->id))>{{ $batch->name }}</option>
        @endforeach
    </select>
    <small class="text-muted">This exam is assigned to multiple batches. Keep the ones that should still receive it.</small>
@else
    <select class="form-control" id="batch_id" name="batch_id">
        <option value="">Select</option>
        @foreach($batch_list as $batch)
            <option value="{{ $batch->id }}" @selected($selectedBatchIds->contains((int) $batch->id))>{{ $batch->name }}</option>
        @endforeach
    </select>
@endif
