@extends('layouts.template')
@section('content')
<div class="container mt-4">
    <h2>Tambah Jadwal Baru</h2>

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

    <form action="{{ route('jadwal.store', $selectedDevice->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="device_id" class="form-label">Device</label>
            <input type="hidden" name="device_id" value="{{ $selectedDevice->id }}">
            <input type="text" class="form-control" value="{{ $selectedDevice->device_name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}">
        </div>

        <div class="mb-3">
            <label for="jam_mulai" class="form-label">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}">
        </div>

        <div class="mb-3">
            <label for="jam_selesai" class="form-label">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}">
        </div>

        <div class="mb-3">
            <label for="customer" class="form-label">Customer</label>
            <input type="text" name="customer" class="form-control" value="{{ old('customer') }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="" disabled selected>-- Pilih Status --</option>
                <option value="booked">Booked</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="4" placeholder="Masukkan keterangan jadwal..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('jadwal.index', $selectedDevice->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection