@extends('layouts.template')
@section('content')
<div class="container mt-4">
    <h2>Tambah Maintenance Record</h2>

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

    <form action="{{ route('record.save', $device->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="device_id" class="form-label"><span style="color: red">*</span>Device</label>
            <input type="hidden" name="device_id" value="{{ $device->id }}">
            <input type="text" class="form-control" value="{{ $device->device_name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="date_issue" class="form-label"><span style="color: red">*</span>Date of Issue</label>
            <input type="date" name="date_issue" class="form-control" value="{{ old('date_issue') }}">
        </div>

        <div class="mb-3">
            <label for="issue" class="form-label"><span style="color: red">*</span>Issue</label>
            <input type="text" name="issue" class="form-control" value="{{ old('issue') }}">
        </div>

        <div class="mb-3">
            <label for="tanggal_perbaikan" class="form-label">Date of Correction</label>
            <input type="date" name="tanggal_perbaikan" class="form-control" value="{{ old('tanggal_perbaikan') }}">
        </div>

        <div class="mb-3">
            <label for="aksi_perbaikan" class="form-label">Corrective Action</label>
            <textarea name="aksi_perbaikan" id="aksi_perbaikan" class="form-control" value="{{ old('aksi_perbaikan') }}" rows=" 4" placeholder="Masukkan keterangan perbaikan..."></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label"><span style="color: red">*</span>Status</label>
            <select name="status" id="status" class="form-control">
                <option value="{{ old('status') }}" disabled selected>-- Pilih Status --</option>
                <option value="done">Done</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="keyword" class="form-label"><span style="color: red">*</span>Keyword</label>
            <input type="text" name="keyword" class="form-control" value="{{ old('keyword') }}">
        </div>

        <div class="mb-3">
            <label for="pic" class="form-label">Foto</label>
            <input type="file" name="pic" id="pic" class="form-control" accept="image/*" onchange="previewImage(event)">
        </div>
        <!-- Tempat preview -->
        <div class="mb-3">
            <img id="preview" src="#" alt="Preview Foto" style="max-width: 150px; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('record.menu', $device->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection