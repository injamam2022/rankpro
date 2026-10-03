@php
  $answers = $answers ?? [];
  $userExamId = $userExamId ?? null;
@endphp
<div class="omrAnswerCol">
  <div class="omrAnswerCol__head">
    <div class="omrSheetDetailsTitle omrAnswerCol__qHead">Q.No.</div>
    <div class="omrSheetDetailsTitle omrAnswerCol__aHead">Answer</div>
  </div>
  <div class="omrAnswerCol__body">
    @foreach($answers as $rowIndex => $value)
      @php
        $groupEnd = (($rowIndex + 1) % 5 === 0) && ($rowIndex < count($answers) - 1);
        $band = ((int) floor($rowIndex / 5) % 2 === 1);
      @endphp
      <div class="omrAnswerRow {{ $band ? 'omrAnswerRow--band' : '' }} {{ $groupEnd ? 'omrAnswerRow--group-end' : '' }}">
        <div class="omrAnswerRow__qno">
          <span class="bubble bubble_count">{{ $value->question_count }}</span>
        </div>
        <div class="omrAnswerRow__opts answer-bubbles">
          @foreach([1, 2, 3, 4] as $opt)
            @php $classKey = 'class_name'.$opt; @endphp
            @if($value->{$classKey} != 'candidateId_fill_red')
              <div class="bubble {{ $value->{$classKey} }}" @if($value->answer == $opt || $value->user_answer == $opt) onclick="openModal('{{ $value->video_link ?? '' }}');" @endif>{{ $opt }}</div>
            @else
              <a href="{{ route('mistake_monitor_input', ['user_exam_id' => $userExamId, 'id' => $value->exam_result_id]) }}" class="bubble {{ $value->{$classKey} }}">{{ $opt }}</a>
            @endif
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</div>
