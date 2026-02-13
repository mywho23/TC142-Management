@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Buat Checklist Baru</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('ceklis.next') }}" method="POST">
            @csrf

            {{-- Pilih Device --}}
            <div class="mb-3">
                <label class="form-label">Device</label>
                <select name="device_id" class="form-control" required>
                    <option value="">Pilih Device</option>
                    @foreach($devices as $device)
                    <option value="{{ $device->id }}">{{ $device->device_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pilih Tipe Checklist --}}
            <div class="mb-3">
                <label class="form-label">Tipe Checklist</label>
                <select name="tipe_ceklis_id" class="form-control" required>
                    <option value="">Pilih Tipe Checklist</option>
                    @foreach($tipeCeklis as $tipe)
                    <option value="{{ $tipe->id }}">{{ strtoupper($tipe->nama) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Next</button>
        </form>
    </div>
</div>
@endsection