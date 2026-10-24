@php
    $n = function ($row, $key) {
        return (int) round((float) ($row->{$key} ?? 0));
    };
@endphp
<table class="aa-table">
    <thead>
        <tr>
            <th>Category</th>
            <th>Questions</th>
            <th class="aa-right">Right</th>
            <th class="aa-wrong">Wrong</th>
            <th>Skipped</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        <tr class="aa-top">
            <td class="aa-cat">Topper</td>
            <td>{{ $n($top ?? null, 'total_question') }}</td>
            <td class="aa-right">{{ $n($top ?? null, 'right_answer') }}</td>
            <td class="aa-wrong">{{ $n($top ?? null, 'wrong_answer') }}</td>
            <td>{{ $n($top ?? null, 'skip_answer') }}</td>
            <td class="aa-score">{{ $n($top ?? null, 'total_mark') }}</td>
        </tr>
        <tr class="aa-avg">
            <td class="aa-cat">Average</td>
            <td>{{ $n($avg ?? null, 'total_question') }}</td>
            <td class="aa-right">{{ $n($avg ?? null, 'right_answer') }}</td>
            <td class="aa-wrong">{{ $n($avg ?? null, 'wrong_answer') }}</td>
            <td>{{ $n($avg ?? null, 'skip_answer') }}</td>
            <td class="aa-score">{{ $n($avg ?? null, 'total_mark') }}</td>
        </tr>
        <tr class="aa-you">
            <td class="aa-cat">Your</td>
            <td class="aa-num">{{ $n($you ?? null, 'total_question') }}</td>
            <td class="aa-right">{{ $n($you ?? null, 'right_answer') }}</td>
            <td class="aa-wrong">{{ $n($you ?? null, 'wrong_answer') }}</td>
            <td>{{ $n($you ?? null, 'skip_answer') }}</td>
            <td class="aa-score">{{ $n($you ?? null, 'total_mark') }}</td>
        </tr>
    </tbody>
</table>
