@extends('layouts/template')
@section('content')
<div class="container my-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <img src="{{ asset('template/assets/images/logo.png') }}" alt="Logo" style="height: 60px;">
        <div class="text-center flex-grow-1">
            <h5 class="mb-1 text-uppercase fw-bold">
                <span class="border-bottom border-dark pb-1">MISSING MALFUNCTION MAINTENANCE COMPONENT LIST</span>
            </h5>
            <div class="fw-bold">POLITEKNIK PENERBANGAN INDONESIA (TCC 142-STD/60)</div>
        </div>
        <div style="width: 80px;"></div> <!-- Dummy div untuk keseimbangan -->
    </div>

    <hr>

    <table class="table table-borderless table-sm mb-3 table-compact" style="width: auto;">
        <tr>
            <td><b>Simulator Name</b></td>
            <td><b>: {{ $device->device_name }}</b></td>
        </tr>
        <tr>
            <td><b>Approval Number</b></td>
            <td>
                <b>:
                    <input type="text" class="print-input" placeholder="................" style="border:none">
                </b>
            </td>
        </tr>
        <tr>
            <td><b>Location</b></td>
            <td>
                <b>:
                    <input type="text" class="print-input" placeholder="................" style="border:none; width: 350px;">
                </b>
            </td>
        </tr>
    </table>



    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">

            <thead>
                <tr>
                    <th>No</th>
                    <th>MMI Subject</th>
                    <th>Date of Record</th>
                    <th>Reporter</th>
                    <th>Corrective Action</th>
                    <th>Date of Correction</th>
                    <th>Executor</th>
                    <th>Device</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mmi as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->subjek }}</td>
                    <td>{{ $m->tanggal_temuan }}</td>
                    <td>{{ $m->reporter }}</td>
                    <td>{{ $m->aksi_perbaikan }}</td>
                    <td>{{ $m->tanggal_perbaikan}}</td>
                    <td>{{ $m->executor->full_name ?? '-'  }}</td>
                    <td>{{ $m->device->device_name ?? '-'  }}</td>
                    <td>{{ $m->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data MMI</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Tombol print --}}
        <button class="btn btn-danger no-print mb-3 mt-3" onclick="printPage()">
            <i class="fa fa-print" style="font-size: 0.9rem; margin-right: 0.4rem;"></i>
        </button>
        <a href="{{ route('mmi.index', $device->id) }}" class="btn btn-primary no-print">
            <i class="fa fa-arrow-circle-left" style="font-size: 0.9rem; margin-right: 0.4rem;"></i>
        </a>
    </div>
</div>

<script>
    function printPage() {
        // biar value input tetep muncul pas print
        document.querySelectorAll('.print-input').forEach(input => {
            input.setAttribute('value', input.value);
        });
        window.print();
    }
</script>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        nav,
        header,
        footer,
        aside,
        .sidebar,
        .navbar,
        .no-print-global {
            display: none !important;
        }

        @page {
            margin: 0.5in;
            /* Mengurangi margin default dari 1in ke 0.5in */
        }

        .table-responsive>table {
            width: 100% !important;
            /* WAJIB! */
        }

        .table-responsive td,
        .table-responsive th {
            font-size: 10pt !important;
            /* Contoh: Mengatur font menjadi 10pt (kecil, tapi terbaca) */
            padding: 2px 4px !important;
            /* Mengurangi padding agar konten muat */
            white-space: normal;
            /* Memungkinkan teks untuk wrap */
        }

        body {
            font-size: 11pt;
        }

        /* Di file CSS kamu */
        .table-compact td,
        .table-compact th {
            padding: 0 !important;
            line-height: 1.2 !important;
        }

        /* Untuk jarak antara kolom */
        .table-compact td:nth-child(2) {
            padding-left: 5px !important;
        }

    }
</style>
@endsection