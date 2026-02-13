<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Print Checklist</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        body {
            font-family: "Times New Roman", sans-serif;
            font-size: 12px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .sub-title {
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 5px;
            vertical-align: middle;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        .data-table td.subject,
        .data-table td.action,
        .data-table td.table-ref {
            text-align: left !important;
        }

        .text-left {
            text-align: left;
        }

        .checkmark {
            font-size: 16px;
            font-weight: bold;
        }

        .logo {
            height: 60px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .meta-table {
            width: 300px;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        .meta-table td {
            border: 1px solid #000;
            padding: 4px 6px;
        }

        .meta-table .label {
            width: 100px;
            font-weight: bold;
        }

        .meta-table .separator {
            width: 10px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    @php
    // safe defaults (controller should pass deviceCode & tipeName)
    $deviceCode = $deviceCode ?? ($first->device->device_code ?? ($first->item->device->device_code ?? '-'));
    $tipeName = $tipeName ?? ($first->item->tipe->nama ?? '-');
    $deviceNameFormatted = strtoupper(str_replace('_',' ', $deviceCode));
    $tipeNameFormatted = ucfirst($tipeName);
    @endphp

    <table style="width:100%; border-collapse: collapse; border:1px solid #000; margin-bottom:0;">
        <tr>
            <td style="width:50%; border-right:1px solid #000; text-align:center; padding:10px;">
                <img src="{{ asset('template/assets/images/logo.png') }}" style="height:70px;">
            </td>
            <td style="width:50%; text-align:center; font-weight:bold; font-size:16px; padding:10px;">
                POLITEKNIK PENERBANGAN INDONESIA - CURUG
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center; font-weight:bold; font-size:15px; padding:8px; border-top:1px solid #000;">
                MAINTENANCE PROGRAM CHECKLIST
            </td>
        </tr>
    </table>

    <div style="text-align:center; font-size:15px; font-weight:bold; margin:12px 0;">
        <div class="header-title">{{ $deviceNameFormatted }} {{ $tipeNameFormatted }} Maintenance Checklist</div>
    </div>

    <table style="width: 40%; border-collapse: collapse; margin-bottom: 15px; font-size: 13px;">
        <tr>
            <td style="width: 90px;">Date</td>
            <td style="width: 10px; text-align:center;">:</td>
            <td>{{ \Carbon\Carbon::parse($first->tanggal_cek)->locale('en')->translatedFormat('l, F d, Y') }}
            </td>
        </tr>
        <tr>
            <td>Location</td>
            <td style="text-align:center;">:</td>
            <td>
                <input type="text" style="width: 100%; border: none; outline: none;">
            </td>
        </tr>
        <tr>
            <td>Technician</td>
            <td style="text-align:center;">:</td>
            <td>{{ $first->nama_teknisi }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px;" rowspan="2">No</th>
                <th rowspan="2">Maintenance Manual</th>
                <th rowspan="2">Table</th>
                <th rowspan="2">Subject</th>
                <th rowspan="2">Action</th>
                <th colspan="2">Result</th>
            </tr>
            <tr>
                <th style="width: 70px;">Done</th>
                <th style="width: 70px;">Pending</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ceklis as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->item->maintenance_manual ?? '-' }}</td>
                <td>{{ $row->item->table_ref ?? '-' }}</td>
                <td class="subject">{{ $row->item->subjek ?? '-' }}</td>
                <td class="action">{{ $row->item->action ?? '-' }}</td>

                {{-- Result --}}
                <td class="checkmark">{{ $row->result == 'done' ? '✓' : '' }}</td>
                <td class="checkmark">{{ $row->result == 'pending' ? '✓' : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="meta-table">
        <tr>
            <td class="label">Document No</td>
            <td class="separator">:</td>
            <td>CL.TSA.12/PPI/I/2020</td>
        </tr>
        <tr>
            <td class="label">Rev</td>
            <td class="separator">:</td>
            <td></td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="separator">:</td>
            <td>2020-01-01</td>
        </tr>
    </table>


    <br><br>

    <button class="no-print" onclick="window.print()">Print</button>

</body>

</html>