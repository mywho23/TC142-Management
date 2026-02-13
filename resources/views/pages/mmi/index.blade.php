@extends('layouts/template')
@section('content')
<div class="container mt-4">
    <div class="table-responsive">
        <h2>Daftar MMI - {{ $device->device_name }}</h2>
        <a href="{{ route('mmi.create', $device->id) }}" class="btn btn-primary mb-3">
            Tambah Data
        </a>
        <a href="{{ route('mmi.print', $device->id) }}" class="btn btn-danger mb-3">
            Print Data
        </a>

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

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>MMI Subject</th>
                    <th>Date of Record</th>
                    <th>Reporter</th>
                    <th>Corrective Action</th>
                    <th>Date of Correction</th>
                    <th>Executor</th>
                    <th>Device</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mmi as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->subjek }}</td>
                    <td>{{ $m->tanggal_temuan }}</td>
                    <td>{{ $m->reporter }}</td>
                    <td>{{ $m->aksi_perbaikan }}</td>
                    <td>{{ $m->tanggal_perbaikan}}</td>
                    <td>{{ $m->executor->full_name ?? '-'  }}</td>
                    <td>{{ $m->device->device_name ?? '-'  }}</td>
                    <td>
                        <span class="badge {{ $m->status == 'open' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($m->status) }}
                        </span>
                    </td>
                    <td>
                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $m->id }}"
                            data-device="{{ $m->device_id }}"
                            data-subjek="{{ $m->subjek }}"
                            data-tanggal_temuan="{{ $m->tanggal_temuan }}"
                            data-reporter="{{ $m->reporter }}"
                            data-aksi_perbaikan="{{ $m->aksi_perbaikan }}"
                            data-executor_id="{{ $m->executor_id }}"
                            data-status="{{ $m->status }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('mmi.hapus', [$m->device_id, $m->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus user ini?')">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data MMI</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modal global (hanya satu di bawah tabel) -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Data</h5>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editId">
                            <div class="mb-3">
                                <label>Subjek</label>
                                <input type="text" name="subjek" id="editSubjek" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Date of Record</label>
                                <input type="date" name="tanggal_temuan" id="editTemuan" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Reporter</label>
                                <select name="reporter" id="editReporter" class="form-control">
                                    <option value="pti">PTI</option>
                                    <option value="sqm">SQM</option>
                                    <option value="operation">Operation</option>
                                    <option value="customer">Customer</option>
                                    <option value="engineer">Engineer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Corrective Action</label>
                                <textarea name="aksi_perbaikan" id="editAksi" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Date of Correction</label>
                                <input type="date" name="tanggal_perbaikan" id="editPerbaikan" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="executor_id" class="form-label">Executor (Engineer)</label>
                                <select name="executor_id" id="editExecutor" class="form-select form-control">
                                    <option value="">-- Belum Ditugaskan --</option>
                                    @foreach ($executor as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" id="editStatus" class="form-control">
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const editButtons = document.querySelectorAll('.edit-btn');
                editButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const deviceId = this.dataset.device;
                        document.getElementById('editForm').action = `/mmi/${deviceId}/index/${id}`;
                        document.getElementById('editSubjek').value = this.dataset.subjek;
                        document.getElementById('editTemuan').value = this.dataset.tanggal_temuan;
                        document.getElementById('editReporter').value = this.dataset.reporter;
                        document.getElementById('editAksi').value = this.dataset.aksi_perbaikan;
                        document.getElementById('editPerbaikan').value = this.dataset.tanggal_perbaikan;
                        document.getElementById('editExecutor').value = this.dataset.executor_id;
                        document.getElementById('editStatus').value = this.dataset.status;
                    });
                });
            });
        </script>

    </div>
</div>
@endsection