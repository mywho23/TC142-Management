<style>
    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }

        nav,
        header,
        footer,
        .no-print,
        .sidebar {
            display: none !important;
        }

        .print-wrapper {
            /* HILANGKAN lebar tetap 800px yang ada di CSS default */
            width: 100% !important;
            /* Gunakan 100% dari ruang cetak yang tersedia */
            max-width: 100% !important;
            margin: 0 !important;
            /* Hilangkan margin horizontal */
            padding: 0 !important;
            /* Hilangkan padding yang bisa memotong */
            border: none !important;
        }

        table.print-table {
            width: 100% !important;
            /* WAJIB! Pastikan tabel benar-benar 100% */
            table-layout: fixed !important;
            /* Opsional: Kecilkan font sedikit lagi */
            font-size: 12px !important;
        }

        .img-proof {
            width: 180px !important;
            /* Perkecil gambar */
            max-width: 100% !important;
            height: auto;
        }

        .input-print {
            outline: none;
        }
    }

    table.print-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        /* penting biar gak melebar */
        font-size: 11px;
    }

    table.print-table td {
        border: 2px solid #000;
        padding: 6px;
        vertical-align: top;
    }

    .text-center {
        text-align: center;
        font-size: 15px;
        font-weight: bold
    }

    .title {
        font-size: 18px;
        font-weight: bold;
    }

    .logo {
        width: 100px;
    }

    .img-proof {
        width: 250px;
        border: 1px solid #555;
        margin-top: 5px;
    }

    .no-bottom-border {
        border-bottom: none !important;
    }

    .no-top-border {
        border-top: none !important;
    }

    .print-wrapper {
        width: 100%;
        max-width: 800px;
        /* atur sesuai selera */
        margin: 0 auto;
        background: #fff;
        padding: 20px;
    }

    .input-print:focus {
        outline: none;
    }

    .input-print:empty::before {
        content: attr(data-placeholder);
        color: #777;
        font-style: italic;
    }

    .input-print:focus::before {
        content: "";
    }
</style>

<div class="print-wrapper">
    <table class="print-table">

        <tr>
            <td style="width: 50%;" class="text-center">
                <img src="{{ asset('template/assets/images/logo.png') }}" class="logo" style="height: 60px; width: auto;">
            </td>
            <td style="width: 50%;" class="text-center">
                <strong>POLITEKNIK<br>PENERBANGAN<br>INDONESIA</strong>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="text-center title">
                MAINTENANCE RECORD
            </td>
        </tr>

        <tr>
            <td colspan="2" class="no-top-border">
                <div style="display: flex;">
                    <div style="width: 50px; font-weight: bold;">Simulator</div>
                    <div style="width: 10px; font-weight: bold;">:</div>
                    <div style="flex-grow: 1;">
                        <span style="white-space: pre-line;">{{ $record->device->device_name ?? '-'  }}</span>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div style="display: flex;">
                    <div style="width: 25px; font-weight: bold;">Date</div>
                    <div style="width: 10px; font-weight: bold;">:</div>
                    <div style="flex-grow: 1;">{{ $record->date_issue }}</div>
                </div>
            </td>

            <td>
                <div style="display: flex; align-items: center;">
                    <div style="width: 150px; font-weight: bold;">Technician Name</div>
                    <div style="width: 10px; font-weight: bold;">:</div>
                    <!-- Editable text -->
                    <span contenteditable="true" class="input-print" data-placeholder="Isi nama teknisi..." style="display: inline-block; flex-grow: 1;">
                    </span>
                </div>
            </td>

        </tr>

        <tr>
            <td colspan="2">
                <div style="display: flex;">
                    <div style="width: 25px; font-weight: bold;">Issue</div>
                    <div style="width: 10px; font-weight: bold;">:</div>
                    <div style="flex-grow: 1;">{{ $record->issue }}</div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="no-bottom-border">
                <div style="display: flex;">
                    <div style="width: 200px; font-weight: bold;">Date Corrective</div>
                    <div style="width: 10px; font-weight: bold;">:</div>
                    <div style="flex-grow: 1;">{{ $record->tanggal_perbaikan }}</div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="no-top-border">
                <div style="display: flex;">
                    <div style="width: 200px; font-weight: bold;">Corrective Action</div>
                    <div style="width: 10px; font-weight: bold;">:</div>
                    <div style="flex-grow: 1;">
                        <span style="white-space: pre-line;">{{ $record->aksi_perbaikan }}</span>
                    </div>
                </div>
            </td>
        </tr>


        <tr>
            <td colspan="2"><strong>Status</strong>: {{ ucfirst($record->status) }}</td>
        </tr>

        <tr>
            <td colspan="2">
                @if ($record->pic)
                <img src="{{ asset('template/assets/images/record/' . $record->pic) }}" class="img-proof">
                @else
                <em>No Image Available</em>
                @endif
            </td>
        </tr>

    </table>

    <br><br>

    <button class="no-print" onclick="window.print()">Print</button>
</div>