@extends('layouts.template')

@section('content')
<form action="{{ route('teknisi.diskrepansi.jawab.store', $logbook->id) }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Komplain Customer</label>
        <textarea class="form-control" disabled>{{ $logbook->diskrepansi_keterangan }}</textarea>
    </div>

    <div class="mb-3">
        <label>Tanggal Pengerjaan</label>
        <input type="date" name="tanggal_pengerjaan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Aksi Pengerjaan</label>
        <textarea type="text" name="aksi_pengerjaan" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="">Pilih</option>
            <option value="open">OPEN</option>
            <option value="progress">PROGRESS</option>
            <option value="done">DONE</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Jawaban</button>
</form>
@endsection