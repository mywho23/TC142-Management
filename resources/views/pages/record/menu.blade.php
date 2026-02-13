@extends('layouts/template')
@section('content')
<div class="container mt-4">
    <div class="table-responsive">
        <h2>Daftar Maintenance Record - {{ $device->device_name }}</h2>
        <a href="{{ route('record.create', $device->id) }}" class="btn btn-primary mb-3">
            Tambah Data
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

        <table id="usersTable" class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Device</th>
                    <th>Date of Issue</th>
                    <th>Issue</th>
                    <th>Date of Correction</th>
                    <th>Corrective Action</th>
                    <th>Status</th>
                    <th>Keyword</th>
                    <th>Pic</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r->device->device_name ?? '-'  }}</td>
                    <td>{{ $r->date_issue }}</td>
                    <td>{{ $r->issue }}</td>
                    <td>{{ $r->tanggal_perbaikan }}</td>
                    <td>
                        <pre style="white-space: pre-wrap; font-family: inherit;">{{ $r->aksi_perbaikan }}</pre>
                    </td>

                    <td>
                        <span class="badge {{ $r->status == 'pending' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td>{{ $r->keyword}}</td>
                    <td>
                        @if ($r->pic)
                        <img src="{{ asset('template/assets/images/record/' . $r->pic) }}"
                            style="width: 100px; height: 100px; border-radius: 0; object-fit: cover;">
                        @else
                        <div class="no-img">No Image</div>
                        @endif
                    </td>

                    <td>
                        <!-- Tombol Edit -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa fa-list"></i>
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <button type="button" class="dropdown-item edit-btn"
                                        data-id="{{ $r->id }}"
                                        data-device="{{ $r->device_id }}"
                                        data-date_issue="{{ $r->date_issue }}"
                                        data-issue="{{ $r->issue }}"
                                        data-tanggal_perbaikan="{{ $r->tanggal_perbaikan }}"
                                        data-aksi_perbaikan="{{ $r->aksi_perbaikan }}"
                                        data-status="{{ $r->status }}"
                                        data-keyword="{{ $r->keyword }}"
                                        data-pic="{{ $r->pic }}"
                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                        ✏ Edit
                                    </button>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('record.print', [$r->device_id, $r->id]) }}" target="_blank">
                                        🖨 Print
                                    </a>
                                </li>

                                <li>
                                    <form action="{{ route('record.delete', [$r->device_id, $r->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger" onclick="return confirm('Yakin hapus data ini?')">
                                            🗑 Hapus
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal global (hanya satu di bawah tabel) -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content"> {{-- <-- ini harus ada DI ATAS FORM --}}

                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5>Edit Record</h5>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id" id="editId">
                            <input type="hidden" name="device_id" id="editDeviceId">

                            <div class="mb-3">
                                <label>Date of Issue</label>
                                <input type="date" name="date_issue" id="editDateIssue" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Issue</label>
                                <input type="text" name="issue" id="editIssue" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Date of Correction</label>
                                <input type="date" name="tanggal_perbaikan" id="editTanggalPerbaikan" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Corrective Action</label>
                                <textarea name="aksi_perbaikan" id="editAksiPerbaikan" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" id="editStatus" class="form-control">
                                    <option value="done">Done</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Keyword</label>
                                <input name="keyword" id="editKeyword" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Foto Saat Ini</label><br>
                                <img id="previewOld" width="120" class="img-thumbnail mb-2" style="display:none;">
                            </div>

                            <div class="mb-3">
                                <label>Ganti Foto</label>
                                <input type="file" name="pic" id="fileInput" class="form-control">
                                <img id="previewNew" width="120" class="img-thumbnail mt-2" style="display:none;">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const editButtons = document.querySelectorAll('.edit-btn');
                editButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const deviceId = this.dataset.device;
                        document.getElementById('editForm').action = `/record/${deviceId}/maintenance/edit/${id}`;
                        document.getElementById('editDateIssue').value = this.dataset.date_issue;
                        document.getElementById('editIssue').value = this.dataset.issue;
                        document.getElementById('editTanggalPerbaikan').value = this.dataset.tanggal_perbaikan;
                        document.getElementById('editAksiPerbaikan').value = this.dataset.aksi_perbaikan;
                        document.getElementById('editStatus').value = this.dataset.status;
                        document.getElementById('editKeyword').value = this.dataset.keyword;
                        // Preview foto lama
                        const previewOld = document.getElementById('previewOld');
                        if (this.dataset.pic) {
                            previewOld.src = `/template/assets/images/record/${this.dataset.pic}`;
                            previewOld.style.display = 'block';
                        } else {
                            previewOld.style.display = 'none';
                        }

                        // Reset preview baru
                        const previewNew = document.getElementById('previewNew');
                        previewNew.style.display = 'none';

                        // Event untuk preview file baru
                        document.getElementById('fileInput').onchange = function(event) {
                            const file = event.target.files[0];
                            if (file) {
                                previewNew.src = URL.createObjectURL(file);
                                previewNew.style.display = "block";
                            }
                        };

                        // Cegah Enter submit form
                        document.getElementById('editForm').addEventListener('keydown', function(e) {
                            const tag = e.target.tagName.toLowerCase();

                            if (e.key === 'Enter' && tag !== 'textarea') {
                                e.preventDefault(); // Enter di input biasa, jangan submit
                            }
                        });
                    });
                });
            });
        </script>

    </div>
</div>
@endsection