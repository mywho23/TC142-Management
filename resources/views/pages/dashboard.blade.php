@extends('layouts.template')

@section('title', 'Dashboard')

@section('content')
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

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">Devices</h5>
                        @if (hasRole(['Administrator']))
                        <button class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#createDeviceModal">
                            + Add Device
                        </button>
                        @endif
                    </div>

                    {{-- BADGE TOTAL DEVICES --}}
                    <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                        {{ $totalDevices }} Devices
                    </span>

                    <p class="text-muted mb-0">
                        Manage registered devices
                    </p>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            @forelse ($devices as $device)
            @php
            // bikin nama file image dari nama device
            $imageFile = Str::slug($device->device_name) . '.jpg';

            // path image
            $imagePath = public_path('template/assets/images/device/' . $imageFile);

            // fallback image
            $imageUrl = file_exists($imagePath)
            ? asset('template/assets/images/device/' . $imageFile)
            : asset('template/assets/images/device/default.JPG');
            @endphp

            <div class="col-sm-4 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body p-0">
                        <img class="img-fluid w-100"
                            src="{{ $imageUrl }}"
                            alt="{{ $device->device_name }}">
                    </div>

                    <div class="card-body px-3 text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-muted font-13 mb-0">
                                Status: <strong>{{ strtoupper($device->status) }}</strong>
                            </p>
                            @if (hasRole(['Administrator', 'Engineer']))
                            <button type="button"
                                class="btn btn-sm btn-edit-device"
                                data-id="{{ $device->id }}"
                                data-name="{{ $device->device_name }}"
                                data-deskripsi="{{ $device->deskripsi }}"
                                data-status="{{ $device->status }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editDeviceModal">
                                <i class="fa fa-pencil-square-o"></i>
                            </button>
                            @endif
                        </div>

                        <h5 class="fw-semibold">
                            {{ $device->deskripsi }}
                        </h5>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Tidak ada device tersedia.
                </div>
            </div>
            @endforelse
        </div>

        <div class="modal fade" id="createDeviceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Create Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form method="POST" action="{{ route('device.save') }}">
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Device Name</label>
                                <input type="text" name="device_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" name="deskripsi" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="inactive">Inactive</option>
                                    <option value="rft">Ready for Flight</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="modal fade" id="editDeviceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalTitle">Edit Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form method="POST" id="editDeviceForm">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Device Name</label>
                                <input type="text" name="device_name" id="edit_device_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" name="deskripsi" id="edit_deskripsi" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" id="edit_status" class="form-control" required>
                                    <option value="" disabled>-- Select Status --</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="rft">Ready for Flight</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-edit-device').forEach(btn => {
            btn.addEventListener('click', function() {

                const id = this.dataset.id;

                document.getElementById('editModalTitle').innerText =
                    'Edit Device - ' + this.dataset.name;

                document.getElementById('edit_device_name').value = this.dataset.name;
                document.getElementById('edit_deskripsi').value = this.dataset.deskripsi;
                document.getElementById('edit_status').value = this.dataset.status;

                document.getElementById('editDeviceForm').action =
                    `/device/${id}`;
            });
        });
    });
</script>

@endsection