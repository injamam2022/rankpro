@php
    $mistakes = $mistakes ?? [];
    $letters = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D'];
    $optHtml = function ($text, $isImage) {
        if ($isImage && $text) {
            return '<img src="'.e($text).'" alt="">';
        }
        return $text ?: '—';
    };
@endphp
@forelse($mistakes as $m)
    <div class="sw-error" data-subject="{{ $m['subject_id'] }}" data-id="{{ $m['id'] }}">
        <div class="sw-error__top">
            <div>
                <div class="sw-error__title">Error #{{ $m['n'] ?? 1 }}: [{{ $m['subject'] }}{{ $m['chapter'] ? ' · '.$m['chapter'] : '' }}]</div>
                <div class="sw-error__tags">
                    <span class="sw-tag sw-tag--bad">{{ $m['tag'] }}</span>
                    @if($m['exam'])<span class="sw-tag">{{ $m['exam'] }}</span>@endif
                    @if($m['subtopic'])<span class="sw-tag">{{ $m['subtopic'] }}</span>@endif
                </div>
            </div>
            <button type="button" class="sw-review" data-id="{{ $m['id'] }}">Mark as Reviewed</button>
        </div>
        <div class="sw-q">{!! $m['question'] !!}</div>
        <div class="sw-opts">
            @foreach($letters as $num => $letter)
                @php
                    $cls = '';
                    $mark = '';
                    if ((int) $m['correct'] === $num) { $cls = 'is-correct'; $mark = '+4 Correct'; }
                    if ((int) $m['yours'] === $num && (int) $m['correct'] !== $num) { $cls = 'is-wrong'; $mark = 'Your Pick (−1)'; }
                    elseif ((int) $m['yours'] === $num && (int) $m['correct'] === $num) { $mark = 'Your Pick · Correct'; }
                @endphp
                <div class="sw-opt {{ $cls }}">
                    <span><b>{{ $letter }}.</b> {!! $optHtml($m['options'][$num] ?? '', $m['option_images'][$num] ?? 0) !!}</span>
                    @if($mark)<span class="sw-opt__mark">{{ $mark }}</span>@endif
                </div>
            @endforeach
        </div>
        @if(!empty($m['solution']))
            <div class="sw-explain">
                <strong>NCERT High-Yield Explanation</strong>
                {!! $m['solution'] !!}
            </div>
        @endif
    </div>
@empty
    <div class="sw-empty">No wrong attempts in this scope.</div>
@endforelse
