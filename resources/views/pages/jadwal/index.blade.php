@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="table-responsive">
            <h2>Jadwal - {{ $device->device_name }}</h2>
            @if (hasRole(['Administrator', 'Operation']))
                <a href="{{ route('jadwal.create', $device->id) }}" class="btn btn-primary mb-3">
                    Tambah Jadwal
                </a>
            @endif

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
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Customer</th>
                        <th>Status</th>
                        @if (hasRole(['Administrator', 'Operation']))
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                            <td>{{ $item->customer }}</td>
                            <td>
                                @if ($item->status == 'booked')
                                    <span class="badge bg-success">Booked</span>
                                @elseif($item->status == 'standby')
                                    <span class="badge bg-warning">Standby</span>
                                @elseif($item->status == 'in flight')
                                    <span class="badge bg-primary">In Flight</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            @if (hasRole(['Administrator', 'Operation']))
                                <td>{{ $item->keterangan }}</td>
                                <td>

                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-warning btn-sm edit-btn"
                                        data-id="{{ $item->id }}" data-device="{{ $item->device_id }}"
                                        data-tanggal="{{ $item->tanggal }}" data-jam_mulai="{{ $item->jam_mulai }}"
                                        data-jam_selesai="{{ $item->jam_selesai }}" data-customer="{{ $item->customer }}"
                                        data-status="{{ $item->status }}" data-keterangan="{{ $item->keterangan }}"
                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('jadwal.hapus', [$item->device_id, $item->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin mau hapus data ini?')">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada jadwal untuk device ini</td>
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
                                <h5>Edit Jadwal</h5>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="editId">
                                <div class="mb-3">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" id="editTanggal" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Jam Mulai</label>
                                    <input type="time" name="jam_mulai" id="editJamMulai" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Jam Selesai</label>
                                    <input type="time" name="jam_selesai" id="editJamSelesai" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Customer</label>
                                    <input type="text" name="customer" id="editCustomer" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Status</label>
                                    <select name="status" id="editStatus" class="form-control">
                                        <option value="booked">Booked</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" id="editKeterangan" class="form-control"></textarea>
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
                            document.getElementById('editForm').action = `/jadwal/${deviceId}/menu/${id}`;
                            document.getElementById('editTanggal').value = this.dataset.tanggal;
                            document.getElementById('editJamMulai').value = this.dataset.jam_mulai;
                            document.getElementById('editJamSelesai').value = this.dataset.jam_selesai;
                            document.getElementById('editCustomer').value = this.dataset.customer;
                            document.getElementById('editStatus').value = this.dataset.status;
                            document.getElementById('editKeterangan').value = this.dataset.keterangan;
                        });
                    });
                });
            </script>
        </div>
    </div>
@endsection
