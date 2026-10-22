@php
    $paperType = (string) old('type', isset($details) ? ($details->type ?? 1) : 1);
    if ($paperType !== '2') {
        $paperType = '1';
    }
    $selectedSource = old('question_source_id', isset($details) ? ($details->question_source_id ?? '') : '');
@endphp
<div class="row mt-2">
    <div class="col-sm-6">
        <label class="form-label" for="type">Type</label>
        <div class="form-control" style="display:flex;">
            <div class="form-check">
                <input class="form-check-input" id="type_normal" type="radio" name="type" value="1"
                    {{ $paperType == '1' ? 'checked' : '' }}
                    onchange="changeQuestionFrom(1);"/>
                <label class="form-check-label" for="type_normal">Normal</label>
            </div>
            <div class="form-check" style="margin-left: 15px;">
                <input class="form-check-input" id="type_question_source" type="radio" name="type" value="2"
                    {{ $paperType == '2' ? 'checked' : '' }}
                    onchange="changeQuestionFrom(2);" />
                <label class="form-check-label" for="type_question_source">Question Source</label>
            </div>
        </div>
    </div>
    <div class="col-sm-6" id="question_source_div" style="{{ $paperType == '2' ? 'display:block;' : 'display:none;' }}">
        <label class="form-label" for="question_source_id">Question source <span class="text-danger">*</span></label>
        <select class="form-control" id="question_source_id" name="question_source_id" onchange="qbSchedulePreview();">
            <option value="">Select Question source</option>
            @foreach(($question_source_list ?? []) as $value)
                <option value="{{ $value->id }}" {{ (string) $selectedSource === (string) $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
            @endforeach
        </select>
    </div>
</div>
