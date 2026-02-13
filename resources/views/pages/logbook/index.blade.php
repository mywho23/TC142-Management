@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Logbook Simulator - {{ $device->device_name }}</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Release Time</th>
                <th>Teknisi</th>
                <th width="50px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logbooks as $key => $logbook)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $logbook->maintenance_release_time }}</td>
                <td>{{ $logbook->maintenance_release_sign }}</td>
                <td>
                    @if (hasRole(['Administrator']))
                    <a href="{{ route('logbook.editCustomer', [$device_id, $logbook->id]) }}" class="btn btn-primary btn-sm">
                        Isi Logbook
                    </a>
                    @endif
                    <a href="{{ route('teknisi.device', [$device_id]) }}" class="btn btn-warning btn-sm">
                        Maintenance Release
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada logbook release untuk diisi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection