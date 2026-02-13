@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3 fw-bold">Checklist Device</h3>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @php
    $order = ['daily', 'weekly', 'biweekly', 'monthly', 'quarterly', 'halfyearly', 'yearly'];

    $devices = [
    'ffs' => 'Checklist FFS',
    'ftd' => 'Checklist FTD'
    ];
    @endphp

    <div class="row">
        @foreach($devices as $devKey => $devTitle)

        @php
        $filteredNotes = $notes->where('device', $devKey)->sortBy(function ($note) use ($order) {
        return array_search($note->tipe, $order);
        });
        @endphp

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header fw-semibold text-primary">{{ $devTitle }}</div>
                <div class="card-body">

                    @forelse($filteredNotes as $note)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="fw-semibold text-capitalize">{{ $note->tipe }}</span>

                        <input type="checkbox"
                            class="form-check-input note-checkbox"
                            data-id="{{ $note->id }}"
                            {{ $note->status ? 'checked' : '' }}
                            {{ $note->can_check ? '' : 'disabled' }}>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Belum ada data checklist untuk {{ strtoupper($devKey) }}</p>
                    @endforelse

                </div>
            </div>
        </div>

        @endforeach
    </div>

    <div class="alert alert-info small mt-3">
        <i class="bi bi-info-circle"></i> Aturan ceklis:
        <ul class="mb-0 mt-1">
            <li><strong>Daily</strong> — hanya bisa dicentang di akhir bulan</li>
            <li><strong>Weekly</strong> — bisa dicentang kapan saja dalam minggu berjalan</li>
            <li><strong>Biweekly</strong> — aktif di minggu ke 1 dan minggu ke 3 tiap bulan</li>
            <li><strong>Monthly</strong> — aktif di akhir bulan</li>
            <li><strong>Quarterly</strong> — aktif di akhir kuartal (Mar, Jun, Sep, Des)</li>
            <li><strong>Halfyearly</strong> — aktif di akhir semester (Jun & Des)</li>
            <li><strong>Yearly</strong> — aktif di akhir tahun (31 Desember)</li>
        </ul>
    </div>

    <hr class="my-4">

    <h3 class="mb-3 fw-bold">Riwayat Checklist</h3>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center" id="tableHistory">
            <thead class="table-light">
                <tr>
                    <th>Tipe Ceklis</th>
                    <th>Device</th>
                    <th>Tanggal</th>
                    <th>Teknisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ceklisDone as $done)
                <tr>
                    <td>{{ ucfirst($done->item->tipe->nama ?? '-') }}</td>
                    <td>{{ strtoupper(str_replace('_',' ',$done->device->device_code ?? '-')) }}</td>
                    <td>{{ \Carbon\Carbon::parse($done->tanggal_cek)->format('d M Y') }}</td>
                    <td>{{ $done->nama_teknisi }}</td>
                    <td>
                        <a href="{{ route('ceklis.print', $done->id) }}" class="btn btn-sm btn-secondary" target="_blank">Print</a>
                        <form action="{{ route('ceklis.delete', [$done->device_id, $done->id]) }}" method="POST" style="display: inline; margin-left: 10px;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[type=checkbox][data-id]').forEach(cb => {
        cb.addEventListener('change', async e => {
            const id = e.target.dataset.id;
            const status = e.target.checked ? 1 : 0;

            await fetch('/note/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id,
                    status
                })
            });
        });
    });
</script>


@endpush