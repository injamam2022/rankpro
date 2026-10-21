@php
    $details = $details ?? null;
    $examDate = old('exam_date', ($details && $details->exam_date) ? \Carbon\Carbon::parse($details->exam_date)->format('Y-m-d') : '');
    $examTime = old('exam_time', ($details && $details->exam_time) ? \Carbon\Carbon::parse($details->exam_time)->format('H:i') : '');
    $examEndDate = old('exam_end_date', ($details && $details->exam_end_date) ? \Carbon\Carbon::parse($details->exam_end_date)->format('Y-m-d') : '');
    $examEndTime = old('exam_end_time', ($details && $details->exam_end_time) ? \Carbon\Carbon::parse($details->exam_end_time)->format('H:i') : '');
@endphp
<div class="row mt-2">
    <div class="col-sm-3">
        <label class="form-label" for="exam_date">Exam Date <span class="text-danger">*</span></label>
        <input class="form-control" id="exam_date" type="date" name="exam_date" value="{{ $examDate }}" />
    </div>
    <div class="col-sm-3">
        <label class="form-label" for="exam_time">Exam Time <span class="text-danger">*</span></label>
        <input class="form-control" id="exam_time" type="time" name="exam_time" value="{{ $examTime }}" />
    </div>
    <div class="col-sm-3">
        <label class="form-label" for="exam_end_date">Exam End Date</label>
        <input class="form-control" id="exam_end_date" type="date" name="exam_end_date" value="{{ $examEndDate }}" />
    </div>
    <div class="col-sm-3">
        <label class="form-label" for="exam_end_time">Exam End Time</label>
        <input class="form-control" id="exam_end_time" type="time" name="exam_end_time" value="{{ $examEndTime }}" />
    </div>
</div>
