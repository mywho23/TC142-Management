<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QTG Result {{ $year }} - {{ $device->device_name }}</title>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif, sans-serif;
            margin: 20px;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }

        .summary-box {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #555;
            border-radius: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        table th,
        table td {
            border: 1px solid #444;
            padding: 6px;
        }

        table th {
            background: #eaeaea;
        }

        .pass {
            color: green;
            font-weight: bold;
        }

        .fail {
            color: red;
            font-weight: bold;
        }

        .not-uploaded {
            color: #888;
            font-style: italic;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px;">Print</button>
        <a href="{{ url()->previous() }}" style="padding: 8px 15px; text-decoration:none;">Kembali</a>
    </div>

    <div class="header">
        <h2>QTG RESULT REPORT</h2>
        <div style="font-size: 12px; margin-top: 5px;">
            {{ $device->device_name }} - Tahun {{ $year }}
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-row">
            <strong>Total Chapter:</strong> <span>{{ $chapters->count() }}</span>
        </div>
        <div class="summary-row">
            <strong>Completed (Uploaded):</strong> <span>{{ $totalcompleted }}</span>
        </div>
        <div class="summary-row">
            <strong>Passed:</strong> <span>{{ $passed }}</span>
        </div>
        <div class="summary-row">
            <strong>Failed:</strong> <span>{{ $failed }}</span>
        </div>
        <div class="summary-row">
            <strong>Progress:</strong> <span>{{ $progress }}%</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Chapter Code</th>
                <th style="width: 40%;">Chapter Name</th>
                <th style="width: 15%;">Result</th>
                <th style="width: 30%;">Note</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($chapters as $c)
            @php
            $up = $uploads[$c->id] ?? null;
            @endphp

            <tr>
                <td>{{ $c->chapter_code }}</td>
                <td>{{ $c->chapter_name }}</td>

                <td>
                    @if ($up)
                    <span class="{{ $up->result == 'passed' ? 'pass' : 'fail' }}">
                        {{ strtoupper($up->result) }}
                    </span>
                    @else
                    <span class="not-uploaded">Not Uploaded</span>
                    @endif
                </td>

                <td>{{ $up->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>