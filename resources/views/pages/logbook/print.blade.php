<!DOCTYPE html>
<html>

<head>
    <title>Print Logbook</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif, Helvetica, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            word-wrap: break-word;
            white-space: normal;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 4px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .no-border td {
            border: none;
        }

        .red {
            color: red;
            font-weight: bold;
            letter-spacing: 1px;
        }

        @media print {
            table {
                page-break-inside: avoid;
            }

            td {
                word-wrap: break-word;
                white-space: normal;
            }

            button {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- ================= HEADER ================= --}}
    <table class="print-table">
        <tr>
            {{-- LOGO --}}
            <td style="width: 20%;" class="center">
                <img src="{{ asset('template/assets/images/logo.png') }}"
                    style="height: 60px;">
            </td>

            {{-- NAMA INSTANSI --}}
            <td style="width: 60%; font-size:18px;" class="center bold">
                POLITEKNIK PENERBANGAN INDONESIA
            </td>

            {{-- NO LOGBOOK --}}
            <td style="width: 20%; vertical-align: top;">
                <table style="width:100%;">
                    <tr>
                        <td class="center red">
                            {{ $logbook->logbook_no }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3" class="center bold">
                FSTD OPERATION LOGBOOK
            </td>
        </tr>
    </table>


    <br>

    {{-- ================= TITLE ================= --}}
    <table class="no-border">
        <tr>
            <td class="center bold">
                {{ strtoupper($device->device_name) }} FSTD OPERATION LOGBOOK
            </td>
        </tr>
    </table>

    <br>

    {{-- ================= DATE & COMPANY ================= --}}
    <table>
        <tr>
            <td width="10%" class="bold center">Date</td>
            <td width="10%" class="bold center">Month</td>
            <td width="10%" class="bold center">Year</td>
            <td width="10%" class="bold center">Company</td>
            <td width="30%" class="bold center">Instructor</td>
            <td class="bold center">Trainees</td>
        </tr>
        <tr>
            <td height="50" class="center">
                {{ \Carbon\Carbon::parse($logbook->date)->format('d') }}
            </td>
            <td class="center">
                {{ \Carbon\Carbon::parse($logbook->date)->format('m') }}
            </td>
            <td class="center">
                {{ \Carbon\Carbon::parse($logbook->date)->format('Y') }}
            </td>
            <td class="center">
                {{ $logbook->company }}
            </td>
            <td class="center">
                {{ $logbook->instructors }}
            </td>
            <td class="center">
                {{ $logbook->trainees }}
            </td>
        </tr>
    </table>


    <br>

    {{-- ================= TIME EXERCISE ================= --}}
    <table>
        <tr>
            <td colspan="4" class="bold center">Time Exercise</td>
        </tr>
        <tr>
            <td class="bold center">Start</td>
            <td class="bold center">Finish</td>
            <td class="bold center">Time Lost</td>
            <td class="bold center">Total Time</td>
        </tr>
        <tr>
            <td height="25" class="center">
                {{ $logbook->start_time }}
            </td>
            <td class="center">
                {{ $logbook->finish_time }}
            </td>
            <td class="center">
                {{ $logbook->time_lost }}
            </td>
            <td class="center">
                {{ $logbook->total_time }}
            </td>
        </tr>
    </table>

    <br>

    {{-- ================= HAND OVER ================= --}}
    <table>
        <tr>
            <th class="bold center">Instructor Hand Over</th>
            <td class="bold center">Maintenance Release</td>
            <td class="bold center">Maintenance Acceptance</td>

        </tr>
        <tr>
            <th height="40" rowspan="2" class="center">
                {{ $logbook->sign_instructor }}
            </th>
            <td>
                Time : {{ \Carbon\Carbon::parse($logbook->maintenance_release_time)->format('d-m-Y H:i') }}
            </td>
            <td>
                Time : {{ \Carbon\Carbon::parse($logbook->maintenance_accept_time)->format('d-m-Y H:i') }}
            </td>
        </tr>
        <tr>
            <td>Sign : {{ $logbook->maintenance_release_sign }}</td>
            <td>Sign : {{ $logbook->maintenance_accept_sign }}</td>
        </tr>
    </table>

    <br>

    {{-- ================= DISCREPANCIES ================= --}}
    <table>
        <tr>
            <td width="3%" class="bold center">No</td>
            <td width="45%" class="bold center">Discrepancies</td>
            <td width="3%" class="bold center">No</td>
            <td width="30%" class="bold center">Corrective Action</td>
            <td width="15%" class="bold center">Done By</td>
        </tr>

        @php
        // Discrepancies dari logbook (boleh kosong)
        $discrepancies = preg_split(
        "/\r\n|\n|\r/",
        $logbook->diskrepansi_keterangan ?? ''
        );

        // Corrective action dari diskrepansis (boleh kosong)
        $actionsRaw = $diskrepansis->pluck('aksi_pengerjaan')->implode("\n");
        $actions = preg_split("/\r\n|\n|\r/", $actionsRaw ?? '');

        $maxRows = max(count($discrepancies), count($actions), 1);

        // DONE BY (boleh null → tampil '-')
        $doneBy = $diskrepansis->first()?->teknisi?->full_name ?? '-';
        @endphp

        @for ($i = 0; $i < $maxRows; $i++)
            <tr>
            {{-- NO DISCREPANCY --}}
            <td class="center">{{ $i + 1 }}</td>

            {{-- DISCREPANCY --}}
            <td>
                {{ isset($discrepancies[$i])
                    ? trim(preg_replace('/^\d+\.\s*/', '', $discrepancies[$i]))
                    : '' }}
            </td>

            {{-- NO ACTION --}}
            <td class="center">{{ $i + 1 }}</td>

            {{-- ACTION --}}
            <td>
                {{ isset($actions[$i])
                    ? trim(preg_replace('/^\d+\.\s*/', '', $actions[$i]))
                    : '' }}
            </td>

            {{-- DONE BY --}}
            @if ($i === 0)
            <td rowspan="{{ $maxRows }}" class="center">
                {{ $doneBy }}
            </td>
            @endif
            </tr>
            @endfor
    </table>
    <br>
    <button onclick="window.print()">Print</button>

</body>

</html>