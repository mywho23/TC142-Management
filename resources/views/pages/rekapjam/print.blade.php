<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print Rekap Logbook - {{ $device->device_name }}</title>
    <style>
        /* CSS Khusus Print */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }

        .info {
            margin-bottom: 20px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
        }

        .footer-table {
            font-weight: bold;
            background-color: #eee !important;
        }

        .signature-wrapper {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }

        .space {
            height: 70px;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 1cm;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>REKAP JAM SIMULATOR</h2>
        <p>Training Center 142 - Sistem Monitoring Jadwal</p>
    </div>

    <table class="info" style="border: none;">
        <tr style="border: none;">
            <td style="border: none; text-align: left; width: 15%;"><strong>Device</strong></td>
            <td style="border: none; text-align: left;">: {{ $device->device_name }}</td>
            <td style="border: none; text-align: right;"><strong>Periode:</strong> {{ $bulanIndo }}
                {{ $tahun }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Start</th>
                <th>Finish</th>
                <th>Time Lost</th>
                <th>Total Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logbooks as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->start_time)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }}</td>
                    <td>{{ $log->finish_time ? \Carbon\Carbon::parse($log->finish_time)->format('H:i') : '-' }}</td>
                    <td>{{ $log->time_lost ?? 0 }}</td>
                    <td><strong>{{ $log->total_time }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data logbook</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="footer-table">
                <td colspan="5" style="text-align: right;">TOTAL PEMAKAIAN JAM:</td>
                <td>
                    {{ $totalJam }} Jam
                    @if ($sisaMenit > 0)
                        {{ $sisaMenit }} Menit
                    @endif
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
            <p>Petugas Teknisi,</p>
            <div class="space"></div>
            <p><strong>( ____________________ )</strong></p>
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer;">Print
            Ulang</button>
        <button onclick="window.close()"
            style="padding: 10px 20px; background: #dc3545; color: white; border: none; cursor: pointer;">Tutup
            Halaman</button>
    </div>

</body>

</html>
