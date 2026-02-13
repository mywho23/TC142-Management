@extends('layouts.template')

@section('content')
<div class="container">

    <h4>
        Timer Maintenance - Device: {{ $device->device_name ?? $device->device_name }}
    </h4>

    <hr>

    {{-- Timer Display --}}
    <div class="text-center my-4">
        <h2 id="timer-display">00:00:00</h2>
    </div>

    {{-- Tombol Kontrol --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <input type="hidden" id="logbook_id" value="{{ $logbook->id ?? '' }}">
    <input type="hidden" id="timer_status" value="{{ $activeTimer->status ?? '' }}">
    <input type="hidden" id="timer_duration" value="{{ $activeTimer->duration_second ?? 0 }}">
    <input type="hidden" id="timer_id" value="{{ $activeTimer->id ?? '' }}">


    <div class="mb-3">
        <button type="button" id="btnStart" class="btn btn-success">Start</button>
        <button type="button" id="btnPause" class="btn btn-warning" disabled>Pause</button>
        <button type="button" id="btnResume" class="btn btn-info" disabled>Resume</button>
        <button type="button" id="btnStop" class="btn btn-danger" disabled>Stop</button>
        <button type="button" id="btnSave" class="btn btn-primary" disabled>Simpan</button>
    </div>

    <div>
        <strong>Status:</strong> <span id="timerStatus">idle</span><br>
        <strong>Duration:</strong> <span id="timerDuration">0</span> detik
    </div>

    <hr>

    <h5 class="mt-4">Riwayat Timer Maintenance</h5>

    <div class="table-responsive">
        <table class="table table-bordered table-sm" id="timerTable">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Durasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="timerTableBody">
                @forelse ($timerlogs as $index => $timer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $timer->start_time ? $timer->start_time->format('d/m/Y H:i:s') : '-' }}</td>
                    <td>{{ optional($timer->end_time)->format('d/m/Y H:i:s') ?? '-' }}</td>
                    <td>
                        {{ gmdate('H:i:s', $timer->duration_second) }}
                    </td>
                    <td>
                        @php
                        $badge = match($timer->status) {
                        'finished' => 'success',
                        'stopped' => 'secondary',
                        'paused' => 'warning',
                        default => 'info',
                        };
                        @endphp
                        <span class="badge bg-{{ $badge }}">
                            {{ $timer->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada data timer
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection