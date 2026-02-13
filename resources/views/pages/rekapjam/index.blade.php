@extends('layouts/template')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Rekap Jam Pakai Simulator</h4>

    <div class="row">
        @foreach ($devices as $device)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $device->device_name }}</h5>

                    <a href="{{ route('jadwal.rekap.device', $device->id) }}"
                        class="btn btn-primary btn-sm mt-3">
                        Lihat Rekap
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection