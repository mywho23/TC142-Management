@extends('layouts/template')
@section('content')
<div class="card card-outline card-{{ $dataGreasing['color'] }}">
    <div class="card-header">
        <h3 class="card-title fw-bold">Status Pelumasan (Greasing)</h3>
    </div>
    <div class="card-body text-center">
        @if($dataGreasing['status'] == 'active')
        <div class="mb-3">
            @if($dataGreasing['days_left'] > 0)
            {{-- Tampilan kalau masih ada sisa hari --}}
            <h2 class="display-4 fw-bold text-{{ $dataGreasing['color'] }}">
                {{ $dataGreasing['days_left'] }} <small class="fs-5">Hari Lagi</small>
            </h2>
            @else
            {{-- Tampilan kalau sudah jatuh tempo atau telat --}}
            <h2 class="display-4 fw-bold text-danger animate__animated animate__pulse animate__infinite">
                DUE!
            </h2>
            <p class="text-danger fw-bold mb-0">Sudah Waktunya Pelumasan</p>
            @if($dataGreasing['days_left'] < 0)
                <small class="text-danger">(Terlewat {{ abs($dataGreasing['days_left']) }} Hari)</small>
                @endif
                @endif

                <p class="text-muted small mt-2">Terakhir dilakukan: <strong>{{ $dataGreasing['last_date'] }}</strong></p>
        </div>

        <div class="progress mb-2" style="height: 30px; border-radius: 15px; background-color: #e9ecef;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $dataGreasing['color'] }}"
                style="width: {{ $dataGreasing['percent'] }}%;">
                <small class="fw-bold">{{ round($dataGreasing['percent']) }}%</small>
            </div>
        </div>

        <span class="badge bg-{{ $dataGreasing['color'] }} px-3 py-2">
            <i class="fas fa-sync-alt mr-1"></i> Interval: 6 Bulan Sekali
        </span>

        @else
        {{-- Tampilan kalau data tidak ditemukan --}}
        <div class="py-4">
            <i class="fas fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
            <h5 class="fw-bold">Data Greasing Belum Ada</h5>
            <p class="text-muted">Silakan input maintenance record dengan keyword <strong>"Greasing"</strong> untuk memulai hitung mundur.</p>
        </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <h4 class="mb-3 fw-bold">Main Menu</h4>
    </div>
    
    {{-- Card Logbook Release --}}
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('teknisi.menulogbook', $device->id) }}" class="text-decoration-none">
            <div class="card card-outline card-primary shadow-sm h-100 hover-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="icon-circle bg-primary text-white mb-3 shadow">
                        <i class="fas fa-file-signature fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Menu Logbook</h5>
                    <p class="text-muted text-center small px-3">
                        Lakukan Logbook Release dan Logbook Acceptance.
                    </p>
                    <span class="btn btn-primary btn-sm rounded-pill mt-2">Buka Sekarang</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-4">
        <a href="/" class="text-decoration-none">
            <div class="card card-outline card-primary shadow-sm h-100 hover-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="icon-circle bg-primary text-white mb-3 shadow">
                        <i class="fas fa-file-signature fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Menu Device</h5>
                    <p class="text-muted text-center small px-3">
                        Update Status Simulator.
                    </p>
                    <span class="btn btn-primary btn-sm rounded-pill mt-2">Buka Sekarang</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-4">
        <a href="{{ route('qtg.chapter.index', $device->id) }}" class="text-decoration-none">
            <div class="card card-outline card-primary shadow-sm h-100 hover-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="icon-circle bg-primary text-white mb-3 shadow">
                        <i class="fas fa-file-signature fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Menu QTG</h5>
                    <p class="text-muted text-center small px-3">
                        Lakukan Upload QTG Simulator.
                    </p>
                    <span class="btn btn-primary btn-sm rounded-pill mt-2">Buka Sekarang</span>
                </div>
            </div>
        </a>
    </div>

    {{-- Lo bisa tambah Card lain di sini nanti, misal Inventory atau Manual Book --}}
</div>

<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection