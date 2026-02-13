@extends('layouts/template')
@section('content')
<div class="container-fluid">

    {{-- Page Title --}}
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold">Operation Panel</h4>
            <p class="text-muted mb-0">
                Pilih aktivitas yang ingin kamu kerjakan
            </p>
        </div>
    </div>

    {{-- Card Menu --}}
    <div class="row">

        {{-- Jadwal --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-calendar-check fs-1 text-primary"></i>
                    </div>

                    <h5 class="card-title fw-semibold">Jadwal</h5>
                    <p class="card-text text-muted flex-grow-1">
                        Buat, lihat, dan kelola jadwal simulator.
                    </p>

                    <button class="btn btn-primary mt-auto"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPilihDevice">
                        Pilih Device
                    </button>

                </div>
            </div>
        </div>

        {{-- Rekap Jam Pakai --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-clock-history fs-1 text-success"></i>
                    </div>

                    <h5 class="card-title fw-semibold">Rekap Jam Pakai</h5>
                    <p class="card-text text-muted flex-grow-1">
                        Lihat rekap jam penggunaan device berdasarkan logbook.
                    </p>

                    <a href="#"
                        class="btn btn-success mt-auto disabled">
                        Coming Soon
                    </a>
                </div>
            </div>
        </div>

        {{-- Profile (opsional) --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-person-circle fs-1 text-secondary"></i>
                    </div>

                    <h5 class="card-title fw-semibold">Profil Saya</h5>
                    <p class="card-text text-muted flex-grow-1">
                        Lihat dan perbarui informasi akun kamu.
                    </p>

                    <a href="#"
                        class="btn btn-secondary mt-auto">
                        Lihat Profil
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Pilih Device -->
<div class="modal fade" id="modalPilihDevice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    @foreach($devices as $device)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="{{ route('jadwal.index', $device->id) }}"
                            class="card text-decoration-none h-100 border">

                            <div class="card-body text-center">
                                <i class="bi bi-cpu fs-2 text-primary mb-2"></i>
                                <h6 class="fw-semibold mb-0">
                                    {{ $device->device_name ?? 'Device' }}
                                </h6>
                            </div>

                        </a>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>

@endsection