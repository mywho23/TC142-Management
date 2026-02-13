@extends('layouts/template')
@section('content')
<div class="container mt-4">
    <h2>Tambah Data MMI</h2>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Ups!</strong> Form belum terisi dengan benar:
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('mmi.store', $device->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="device_id" class="form-label">Device</label>
            <input type="hidden" name="device_id" value="{{ $device->id }}">
            <input type="text" class="form-control" value="{{ $device->device_name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="subjek" class="form-label">Subjek</label>
            <input type="text" name="subjek" class="form-control" value="{{ old('subjek') }}">
        </div>

        <div class="mb-3">
            <label for="tanggal_temuan" class="form-label">Date of Record</label>
            <input type="date" name="tanggal_temuan" class="form-control" value="{{ old('tanggal_temuan') }}">
        </div>

        <div class="mb-3">
            <label for="reporter" class="form-label">Reporter</label>
            <select name="reporter" id="reporter" class="form-control">
                <option value="" disabled selected>-- Pilih Reporter --</option>
                <option value="pti">PTI</option>
                <option value="sqm">SQM</option>
                <option value="operation">Operation</option>
                <option value="customer">Customer</option>
                <option value="engineer">Engineer</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="aksi_perbaikan" class="form-label">Corrective Action</label>
            <textarea name="aksi_perbaikan" id="aksi_perbaikan" class="form-control" rows="4" placeholder="Masukkan keterangan perbaikan..."></textarea>
        </div>

        <div class="mb-3">
            <label for="tanggal_perbaikan" class="form-label">Date of Correction</label>
            <input type="date" name="tanggal_perbaikan" class="form-control" value="{{ old('tanggal_perbaikan') }}">
        </div>

        <div class="mb-3">
            <label for="executor_id" class="form-label">Executor (Engineer)</label>
            <select name="executor_id" id="executor_id" class="form-select form-control">
                <option value="">-- Belum Ditugaskan --</option>
                @foreach ($executor as $user)
                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="" disabled selected>-- Pilih Status --</option>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('mmi.index', $device->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection