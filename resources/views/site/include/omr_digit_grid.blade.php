{{-- Numeric OMR ID grid (0-9 bubbles only). $code string, $length columns. --}}
@php
    $raw = (string) ($code ?? '');
    $length = (int) ($length ?? 8);
    if ($length < 1) {
        $length = 8;
    }
    // Prefer last $length chars when longer; left-pad when shorter.
    if (strlen($raw) < $length) {
        $raw = str_pad($raw, $length, '0', STR_PAD_LEFT);
    } elseif (strlen($raw) > $length) {
        $raw = substr($raw, -$length);
    }
    $chars = str_split($raw);
@endphp
<div class="omrSheetIdField omrDigitGrid" style="--omr-cols: {{ $length }};">
  <div class="answerFillBoxAll">
    @foreach($chars as $ch)
      <div class="answerFillBox">{{ $ch }}</div>
    @endforeach
  </div>
  @for($digit = 0; $digit <= 9; $digit++)
    <div class="answer-bubbles omrDigitGrid__row">
      @foreach($chars as $ch)
        <div class="bubble {{ (string) $ch === (string) $digit ? 'candidateId_fill' : '' }}">{{ $digit }}</div>
      @endforeach
    </div>
  @endfor
</div>
