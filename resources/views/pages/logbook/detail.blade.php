@extends('layouts.template')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">Detail Logbook - {{ $device->device_name }}</h2>

    <div class="d-flex mb-3">
        <a href="{{ route('teknisi.device', $device->id) }}" class="btn btn-secondary me-2">Kembali</a>
        <a href="{{ route('logbook.print', ['device_id' => $device->id,'logbook' => $logbook->id]) }}"
            target="_blank"
            class="btn btn-primary">
            Print
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            Informasi Logbook
        </div>

        <div class="card-body">

            <h5 class="mt-3 mb-2">Release Info</h5>
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Release Time</th>
                    <td>{{ \Carbon\Carbon::parse($logbook->maintenance_release_time)->format('d-m-Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Release Sign</th>
                    <td>{{ $logbook->maintenance_release_sign }}</td>
                </tr>
            </table>

            <h5 class="mt-4 mb-2">Customer Info</h5>
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Date</th>
                    <td>{{ $logbook->date }}</td>
                </tr>
                <tr>
                    <th>Company</th>
                    <td>{{ $logbook->company }}</td>
                </tr>
                <tr>
                    <th>Training Subject</th>
                    <td>{{ $logbook->training_subject }}</td>
                </tr>
                <tr>
                    <th>Instructors</th>
                    <td>{{ $logbook->instructors }}</td>
                </tr>
                <tr>
                    <th>Trainees</th>
                    <td>{{ $logbook->trainees }}</td>
                </tr>
                <tr>
                    <th>Start Time</th>
                    <td>{{ $logbook->start_time }}</td>
                </tr>
                <tr>
                    <th>Finish Time</th>
                    <td>{{ $logbook->finish_time }}</td>
                </tr>
                <tr>
                    <th>Total Time</th>
                    <td>{{ $logbook->total_time }}</td>
                </tr>
                <tr>
                    <th>Time Lost</th>
                    <td>{{ $logbook->time_lost }}</td>
                </tr>
                <tr>
                    <th>Diskrepansi</th>
                    <td>{{ $logbook->diskrepansi_keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Sign Instructor</th>
                    <td>{{ $logbook->sign_instructor }}</td>
                </tr>
            </table>

            <h5 class="mt-4 mb-2">Acceptance Info</h5>
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Accept Time</th>
                    <td>
                        {{ $logbook->maintenance_accept_time
                ? \Carbon\Carbon::parse($logbook->maintenance_accept_time)->format('d-m-Y H:i')
                : '-' }}
                    </td>
                </tr>
                <tr>
                    <th>Accept Sign</th>
                    <td>{{ $logbook->maintenance_accept_sign ?? '-' }}</td>
                </tr>
            </table>

            {{-- Technician Response --}}
            <h5 class="mt-4 mb-2">Technician Response</h5>

            @php
            $status = $teknisiLog->status ?? 'nill';

            $badgeColor = [
            'done' => 'bg-success',
            'progress' => 'bg-warning',
            'pending' => 'bg-danger',
            'nill' => 'bg-primary',
            ][$status] ?? 'bg-secondary';
            @endphp

            <table class="table table-bordered">
                <tr>
                    <th width="30%">Status</th>
                    <td>
                        <span class="badge {{ $badgeColor }}" style="font-size: 14px; padding: 6px 10px;">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Pengerjaan</th>
                    <td>{{ $teknisiLog->tanggal_pengerjaan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Aksi Pengerjaan</th>
                    <td>{{ $teknisiLog->aksi_pengerjaan ?? '-' }}</td>
                </tr>
            </table>
            <br>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                Hapus Logbook
            </button>
        </div>
    </div>

</div>
<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>
                    Yakin mau hapus <strong>Logbook {{ $device->device_name }}</strong>?
                </p>
                <p class="text-danger mb-0">
                    Data logbook dan seluruh diskrepansi terkait akan ikut terhapus.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>

                <form action="{{ route('teknisi.logbook.delete', $logbook->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection