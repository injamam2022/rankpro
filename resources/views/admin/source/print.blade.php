<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Source — total questions</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 24px; }
        h1 { font-size: 22px; margin: 0 0 4px; }
        .meta { color: #444; margin: 0 0 16px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #ccc; padding: 8px 10px; text-align: left; font-size: 14px; }
        th { border-bottom: 2px solid #111; }
        td.num, th.num { text-align: right; }
        tfoot td { font-weight: 700; border-top: 2px solid #111; border-bottom: 0; }
        .toolbar { margin-bottom: 16px; }
        .toolbar button, .toolbar a { font-size: 14px; margin-right: 12px; }
        @media print {
            .toolbar { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" onclick="window.print()">Print</button>
        <a href="{{ route('admin.source', array_filter(['q' => $q, 'subject_id' => $subject_id])) }}">Back to list</a>
    </div>
    <h1>Source</h1>
    <p class="meta">
        Total questions: {{ number_format($questions_total) }}
        @if($q !== '')
            · Search: {{ $q }}
        @endif
        · {{ now()->format('d M Y, h:i A') }}
    </p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Name</th>
                <th class="num">Total questions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->subject_name }}</td>
                    <td>{{ $row->name }}</td>
                    <td class="num">{{ number_format((int) $row->questions_count) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No records found.</td>
                </tr>
            @endforelse
        </tbody>
        @if($list->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="3">Total questions</td>
                    <td class="num">{{ number_format($questions_total) }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
    <script>
        window.addEventListener('load', function () { window.print(); });
    </script>
</body>
</html>
