@extends('layouts.template')
@section('title', 'Tambah Inventory Baru')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Data Barang</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Edit Inventory</h3>
            </div>

            <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- WAJIB ADA --}}

                <div class="card-body">
                    <div class="row">
                        {{-- Nama Barang --}}
                        <div class="col-md-6 form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" value="{{ old('nama_barang', $inventory->nama_barang) }}" placeholder="Misal: Battery UPS 12V">
                            @error('nama_barang') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- Device / Kategori --}}
                        <div class="col-md-6 form-group">
                            <label for="device_id">Device / Kategori</label>
                            <select name="device_id" class="form-control select2 @error('device_id') is-invalid @enderror">
                                <option value="">-- Pilih Device --</option>
                                @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ old('device_id', $inventory->device_id) == $device->id ? 'selected' : '' }}>
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
                            <input type="text" name="sn_barang" class="form-control @error('sn_barang') is-invalid @enderror"
                                value="{{ old('sn_barang', $inventory->sn_barang) }}" placeholder="Masukkan SN">
                            @error('sn_barang') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- PN Barang --}}
                        <div class="col-md-6 form-group">
                            <label for="pn_barang">Part Number (PN)</label>
                            <input type="text" name="pn_barang" class="form-control @error('pn_barang') is-invalid @enderror"
                                value="{{ old('pn_barang', $inventory->pn_barang) }}" placeholder="Masukkan PN">
                            @error('pn_barang') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok', $inventory->stok) }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="satuan">Satuan</label>
                            <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $inventory->satuan) }}" placeholder="Pcs / Set / Unit">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                @foreach(['baik', 'new', 'rusak', 'service', 'diluar'] as $stat)
                                <option value="{{ $stat }}" {{ old('status', $inventory->status) == $stat ? 'selected' : '' }}>
                                    {{ ucfirst($stat) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi Penyimpanan</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $inventory->lokasi) }}" placeholder="Contoh: Rak A-1">
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu">{{ old('keterangan', $inventory->keterangan) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Ganti Gambar (Kosongkan jika tidak diubah)</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" onchange="previewImage(event)">
                    </div>

                    {{-- Preview Foto Lama & Baru --}}
                    <div class="mb-3">
                        <label class="d-block">Preview Foto:</label>
                        @if($inventory->gambar)
                        <img id="preview" src="{{ asset('template/assets/images/inventory/' . $inventory->gambar) }}" alt="Preview Foto" style="max-width: 150px; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                        @else
                        <img id="preview" src="#" alt="Preview Foto" style="max-width: 150px; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection