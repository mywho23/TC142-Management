@extends('layouts.template')
@section('title', 'Tambah Inventory Baru')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Tambah Barang Baru</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Input Inventory</h3>
            </div>

            <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        {{-- Nama Barang --}}
                        <div class="col-md-6 form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" value="{{ old('nama_barang') }}" placeholder="Misal: Battery UPS 12V">
                            @error('nama_barang') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- Device / Kategori (Dinamis dari Database) --}}
                        <div class="col-md-6 form-group">
                            <label for="device_id">Device / Kategori</label>
                            <select name="device_id" class="form-control select2 @error('device_id') is-invalid @enderror">
                                <option value="">-- Pilih Device --</option>
                                @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ old('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->device_name }}
                                </option>
                                @endforeach
                            </select>
                            @error('device_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- SN Barang --}}
                        <div class="col-md-6 form-group">
                            <label for="sn_barang">Serial Number (SN)</label>
                            <input type="text" name="sn_barang" class="form-control @error('sn_barang') is-invalid @enderror" value="{{ old('sn_barang') }}" placeholder="Masukkan SN">
                            @error('sn_barang') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- PN Barang --}}
                        <div class="col-md-6 form-group">
                            <label for="pn_barang">Part Number (PN)</label>
                            <input type="text" name="pn_barang" class="form-control @error('pn_barang') is-invalid @enderror" value="{{ old('pn_barang') }}" placeholder="Masukkan PN">
                            @error('pn_barang') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="stok">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="satuan">Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}" placeholder="Pcs / Set / Unit">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="status">Status Awal</label>
                            <select name="status" class="form-control">
                                <option value="baik">Baik</option>
                                <option value="new">Baru</option>
                                <option value="rusak">Rusak</option>
                                <option value="service">Service</option>
                                <option value="diluar">Diluar</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Contoh: Rak A-1">
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <!-- Tempat preview -->
                    <div class="mb-3">
                        <img id="preview" src="#" alt="Preview Foto" style="max-width: 150px; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection