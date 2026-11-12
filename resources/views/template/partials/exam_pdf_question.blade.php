@if($q)
<div class="q-block">
    <table class="q-head-table" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="q-num" width="6%">{{ $num }}.</td>
            <td class="q-content" width="94%">
                {!! $q->question_text !!}
                @php $qImgSrc = !empty($q->question_image) ? exam_pdf_image_src($q->question_image) : ''; @endphp
                @if($qImgSrc !== '')
                    <img class="q-diagram" src="{{ $qImgSrc }}" alt="">
                @endif
            </td>
        </tr>
    </table>
    <table class="options-grid" width="100%" cellpadding="0" cellspacing="0">
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
                    <td class="opt-cell" width="50%">
                        <table class="opt-inner" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="opt-num" width="12%">({{ $opt }})</td>
                                <td class="opt-body" width="88%">
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
