@php
    $classOptions = student_class_options();
    $selectedClass = (string) old('class_name', $selected ?? '');
    $isRequired = !empty($required);
@endphp
<select class="form-control{{ !empty($selectClass) ? ' '.$selectClass : '' }}" id="{{ $id ?? 'class_name' }}" name="class_name" @if($isRequired) required @endif>
    <option value="">Select class</option>
    @foreach($classOptions as $value)
        <option value="{{ $value }}" @selected($selectedClass === (string) $value)>{{ $value }}</option>
    @endforeach
    @if($selectedClass !== '' && !in_array($selectedClass, $classOptions, true))
        <option value="{{ $selectedClass }}" selected>{{ $selectedClass }}</option>
    @endif
</select>
