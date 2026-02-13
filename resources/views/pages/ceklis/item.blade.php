@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Checklist {{ str_replace('_', ' ', strtoupper($device->device_code)) }}</h4>
        <span class="text-muted">Tipe: {{ ucfirst($tipe->nama) }}</span>
    </div>

    <div class="card-body">
        @if($items->isEmpty())
        <div class="alert alert-warning">
            Tidak ada item checklist untuk device ini.
        </div>
        @else
        <form id="formCeklis" action="{{ route('ceklis.store', ['device' => $device->id, 'tipe' => $tipe->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="device_id" value="{{ $device->id }}">
            <input type="hidden" name="tipe_ceklis_id" value="{{ $tipe->id }}">

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <button type="button" id="doneAllBtn" class="btn btn-success btn-sm">Done All</button>
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0" style="white-space: nowrap;">Teknisi:</label>
                    <select name="nama_teknisi" class="form-select form-select-sm" style="width: auto;" required>
                        <option value="">Pilih</option>
                        <option value="Cucu Sunarya">Cucu Sunarya</option>
                        <option value="Airlangga Saputra">Airlangga Saputra</option>
                        <option value="Dede Restu Fadilah">Dede Restu Fadilah</option>
                        <option value="Eza Pramadan">Eza Pramadan</option>
                    </select>
                </div>
            </div>



            <div class="table-responsive">
                <table id="tableCeklis" class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 50px;">No</th>
                            <th>Subjek</th>
                            <th>Action</th>
                            <th style="width: 150px;">Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->subjek }}</td>
                            <td>{{ $item->action }}</td>
                            <td>
                                <select name="status[{{ $item->id }}]" class="form-control" required>
                                    <option value="">Pilih</option>
                                    <option value="done">Done</option>
                                    <option value="pending">Pending</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="keterangan[{{ $item->id }}]" class="form-control" placeholder="Opsional">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <script>
                document.getElementById('doneAllBtn').addEventListener('click', function() {
                    // ambil semua select yang namanya diawali 'status['
                    const selects = document.querySelectorAll('select[name^="status["]');

                    selects.forEach(select => {
                        select.value = 'done'; // set ke OK
                    });

                    // optionally, kasih feedback manis ke user
                    const btn = this;
                    btn.disabled = true;
                    btn.textContent = 'Done ✅';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.textContent = 'Done All ✅';
                    }, 1500);
                });
            </script>



            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Simpan Checklist</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection