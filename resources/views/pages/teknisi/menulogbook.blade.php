@extends('layouts.template')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">Menu Logbook - Device: {{ $device->device_name }}</h2>

    {{-- ALERT --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    {{-- RELEASE FORM --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Maintenance Release
        </div>

        <div class="card-body">

            @if (!$latest)
            {{-- Belum ada logbook sama sekali: boleh release --}}
            @include('pages.teknisi.partials.release_form')

            @elseif (!$latest->maintenance_accept_time)
            {{-- Sesi sebelumnya belum accept --}}
            <p class="text-warning fw-bold">Sesi sebelumnya belum selesai, menunggu ACCEPT.</p>
            <p>Release baru tersedia setelah sesi sebelumnya sudah di-ACCEPT.</p>

            @else
            {{-- Sesi sebelumnya sudah complete: boleh release baru --}}
            @include('pages.teknisi.partials.release_form')
            @endif

        </div>
    </div>

    {{-- SELECT FORM --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            Maintenance Timer Log
        </div>

        <div class="card-body">
            <a href="{{ route('timer.select', $device->id) }}"
                class="btn btn-primary">
                Timer Maintenance
            </a>
        </div>
    </div>

    {{-- ACCEPT FORM --}}
    @if ($latest && $latest->maintenance_release_time)
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            Maintenance Acceptance
        </div>

        <div class="card-body">

            {{-- Jika sudah ACCEPT --}}
            @if ($latest->maintenance_accept_time)
            <p class="text-success fw-bold">Sesi ini sudah selesai dan ACCEPTED.</p>
            <p><strong>Accept Time:</strong> {{ \Carbon\Carbon::parse($latest->maintenance_accept_time)->format('d-m-Y H:i') }} </p>
            <p><strong>Accept Sign:</strong> {{ $latest->maintenance_accept_sign }}</p>

            {{-- Jika BELUM diisi customer --}}
            @elseif (empty($latest->sign_instructor))
            <p class="text-warning fw-bold">
                Menunggu customer mengisi logbook terlebih dahulu.
            </p>
            <p>Form acceptance akan muncul setelah customer mengisi logbook.</p>

            {{-- Jika customer SUDAH isi --}}
            @else
            <form action="{{ route('teknisi.accept', $device->id) }}" method="POST">
                @csrf

                <input type="hidden" name="logbook_id" value="{{ $latest->id }}">

                <div class="mb-3">
                    <label class="form-label">Accept Time</label>
                    <input type="datetime-local" name="maintenance_accept_time" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Accept Signature</label>
                    <input type="text" name="maintenance_accept_sign" class="form-control" required>
                </div>

                <button class="btn btn-success">Submit Acceptance</button>
            </form>
            @endif

        </div>
    </div>
    @endif

    {{-- HISTORY --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="historyTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Release</th>
                            <th>Customer</th>
                            <th>Accept</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $i => $row)
                        <tr id="history-row-{{ $row->id }}">
                            <td>{{ $i + 1 }}</td>

                            {{-- RELEASE --}}
                            <td>
                                {{ $row->maintenance_release_time
                ? \Carbon\Carbon::parse($row->maintenance_release_time)->format('d-m-Y H:i')
                : '-' }}
                            </td>

                            {{-- CUSTOMER --}}
                            <td>{{ $row->company ?? '-' }}</td>

                            {{-- ACCEPT --}}
                            <td>
                                {{ $row->maintenance_accept_time
                ? \Carbon\Carbon::parse($row->maintenance_accept_time)->format('d-m-Y H:i')
                : '-' }}
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <a href="{{ route('teknisi.logbook.detail', $row->id) }}"
                                    class="btn btn-sm btn-dark">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>


@endsection