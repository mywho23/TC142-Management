@extends('layouts.template')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">
        Komplain Pending - Device: {{ $device->device_name }}
    </h3>

    {{-- ALERT --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($pending->isEmpty())
    <div class="alert alert-info">
        Tidak ada komplain customer yang menunggu jawaban teknisi.
    </div>
    @endif

    @foreach ($pending as $row)
    <div class="card mb-3">
        <div class="card-body">

            <p><strong>Customer:</strong> {{ $row->company ?? '-' }}</p>
            <p><strong>Komplain:</strong> {{ $row->diskrepansi_keterangan }}</p>
            <p><strong>Tanggal Logbook:</strong> {{ $row->created_at }}</p>

            <a href="{{ route('teknisi.diskrepansi.jawab.form', $row->id) }}"
                class="btn btn-primary btn-sm">
                Jawab Komplain
            </a>

        </div>
    </div>
    @endforeach

    <div class="card">
        <div class="card-header bg-dark text-white">
            Riwayat Diskrepansi
        </div>

        <div class="card-body p-0">
            @if ($history->isEmpty())
            <div class="p-3 text-center text-muted">
                Belum ada riwayat diskrepansi untuk device ini.
            </div>
            @else
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Tanggal Pengerjaan</th>
                        <th>Teknisi</th>
                        <th>Aksi Pengerjaan</th>
                        <th width="10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->tanggal_pengerjaan }}</td>
                        <td>{{ $row->teknisi->full_name ?? '-' }}</td>
                        <td>{{ $row->aksi_pengerjaan }}</td>
                        <td>
                            @php
                            $badge = match($row->status) {
                            'done' => 'success',
                            'progress' => 'warning',
                            default => 'danger'
                            };
                            @endphp

                            <span class="badge bg-{{ $badge }}">
                                {{ strtoupper($row->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</div>
@endsection