@if($q)
<div class="q-block">
    <table class="q-head-table">
        <tr>
            <td class="q-num">{{ $num }}.</td>
            <td class="q-content">
                {!! $q->question_text !!}
                @php $qImgSrc = !empty($q->question_image) ? exam_pdf_image_src($q->question_image) : ''; @endphp
                @if($qImgSrc !== '')
                    <div><img class="q-diagram" src="{{ $qImgSrc }}" alt=""></div>
                @endif
            </td>
        </tr>
    </table>
    <table class="options-table">
        @foreach([1, 2, 3, 4] as $opt)
            @php
                $optKey = 'option'.$opt;
                $isImgKey = 'is_option'.$opt.'_image';
                $optSrc = !empty($q->$isImgKey) ? exam_pdf_image_src($q->$optKey) : '';
            @endphp
            <tr class="opt-row">
                <td class="opt-num">({{ $opt }})</td>
                <td class="opt-body">
                    @if(!empty($q->$isImgKey))
                        @if($optSrc !== '')
                            <img class="opt-diagram" src="{{ $optSrc }}" alt="">
                        @endif
                    @else
                        {!! $q->$optKey !!}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endif
