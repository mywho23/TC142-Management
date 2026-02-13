@extends('layouts/template')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Inventory</h1>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Stok Barang</h3>

                <div class="card-tools d-flex">
                    <form action="{{ route('inventory.index') }}" method="GET" class="mr-2">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari barang, SN, Device..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">
                        Tambah Barang
                    </a>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Serial Number</th>
                            <th>Part Number</th>
                            <th>Device</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Gambar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory as $item)
                        <tr>
                            <td><strong>{{ $item->nama_barang }}</strong></td>
                            <td><small class="badge badge-dark">SN: {{ $item->sn_barang }}</small></td>
                            <td><small class="badge badge-dark">PN: {{ $item->pn_barang }}</small></td>
                            <td>
                                <span class="badge badge-info">{{ $item->device->device_name ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $item->stok }}</td>
                            <td><small>{{ $item->satuan }}</small></td>
                            <td>
                                @php
                                $badge = [
                                'new' => 'primary',
                                'baik' => 'success',
                                'rusak' => 'danger',
                                'service' => 'warning',
                                'diluar' => 'secondary'
                                ][$item->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ strtoupper($item->status) }}</span>
                            </td>
                            <td>{{ $item->lokasi }}</td>
                            <td style="min-width: 200px; max-width: 300px;" class="text-wrap">
                                <small>{{ $item->keterangan ?? '-' }}</small>
                            </td>
                            <td>
                                <img src="{{ $item->gambar ? asset('template/assets/images/inventory/' . $item->gambar) : asset('template/assets/images/tc.png') }}"
                                    alt="Gambar"
                                    style="width: 45px; height: 45px; object-fit: cover;"
                                    class="rounded border">
                            </td>
                            <td class="text-center">
                                <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-xs btn-success">Edit</a>
                                <form action="{{ route('inventory.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus Data ini ?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-5">
                                @if(request('search'))
                                Hasil pencarian "<strong>{{ request('search') }}</strong>" tidak ditemukan.
                                <br><a href="{{ route('inventory.index') }}" class="btn btn-xs btn-default mt-2">Reset Pencarian</a>
                                @else
                                Data masih kosong, bre!
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($inventory->hasPages())
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $inventory->appends(['search' => request('search')])->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection {{-- Jangan lupa ditutup! --}}