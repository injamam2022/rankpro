@if($q)
<div class="q-block">
    <table class="q-head-table">
        <tr>
            <td class="q-num">{{ $num }}.</td>
            <td class="q-content">
                {!! $q->question_text !!}
                @php $qImgSrc = !empty($q->question_image) ? exam_pdf_image_src($q->question_image) : ''; @endphp
                @if($qImgSrc !== '')
                    <img class="q-diagram" src="{{ $qImgSrc }}" alt="">
                @endif
            </td>
        </tr>
    </table>
    <table class="options-grid">
        @foreach([[1, 2], [3, 4]] as $pair)
            <tr>
                @foreach($pair as $opt)
                    @php
                        $optKey = 'option'.$opt;
                        $isImgKey = 'is_option'.$opt.'_image';
                        $raw = $q->$optKey ?? '';
                        $optSrc = !empty($q->$isImgKey) ? exam_pdf_image_src($raw) : '';
                        $hasText = trim(html_entity_decode(strip_tags((string) $raw))) !== '';
                    @endphp
                    <td class="opt-cell">
                        <table class="opt-inner">
                            <tr>
                                <td class="opt-num">({{ $opt }})</td>
                                <td class="opt-body">
                                    @if($optSrc !== '')
                                        <img class="opt-diagram" src="{{ $optSrc }}" alt="">
                                    @elseif($hasText || $raw !== '')
                                        {!! $raw !!}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</div>
@endif
