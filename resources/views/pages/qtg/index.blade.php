@extends('layouts.template')

@section('content')

<div class="container mt-4">

    <h2>QTG - {{ $device->device_name }}</h2>
    <hr>

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

    <form id="formPilihTahun" action="{{ route('qtg.chapter.tahun', ['device_id' => $device->id]) }}" method="POST" class="d-flex mb-3">
        @csrf
        <select name="year" class="form-select w-auto me-2">
            @foreach ($years as $y)
            <option value="{{ $y }}" {{ ($y == $current_year) ? 'selected' : '' }}>
                {{ $y }}
            </option>
            @endforeach
        </select>
        <button class="btn btn-primary">Pilih Tahun</button>
    </form>


    <form id="formTambahTahun" action="{{ route('qtg.chapter.addyear', $device->id) }}" method="POST">
        @csrf
        <button class="btn btn-success btn-sm">+ Tambah Tahun Baru</button>
    </form>
    <br>
    <div class="mb-3">
        <a href="{{ route('qtg.chapter.print', ['device_id' => $device->id, 'year' => $current_year]) }}"
            class="btn btn-secondary"
            target="_blank">
            Print Result ({{ $current_year }})
        </a>
    </div>
    <br>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Chapter Code</th>
                <th>Chapter Name</th>
                <th>Status</th>
                <th width="200">Action</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($chapters as $chap)
            @php
            $upload = $uploads[$chap->id] ?? null;
            @endphp

            <tr>
                <td>{{ $chap->chapter_code }}</td>
                <td>{{ $chap->chapter_name ?? '-' }}</td>

                <td>
                    @if ($upload)
                    <span class="{{ $upload->result == 'passed' ? 'text-success' : 'text-danger' }}">
                        {{ ucfirst($upload->result) }}
                    </span>
                    @else
                    <span class="text-secondary">Belum upload</span>
                    @endif
                </td>

                <td>
                    @if ($upload)
                    <button class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#viewPdfModal{{ $upload->id }}">
                        View PDF
                    </button>

                    <button class="btn btn-sm btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadModal{{ $chap->id }}">
                        Reupload
                    </button>
                    @else
                    <button class="btn btn-sm btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadModal{{ $chap->id }}">
                        Upload
                    </button>
                    @endif
                    @if($upload)
                    <button class="btn btn-danger btn-sm"
                        onclick="event.preventDefault(); document.getElementById('deleteForm{{ $upload->id }}').submit();">
                        Hapus
                    </button>

                    <form id="deleteForm{{ $upload->id }}"
                        action="{{ route('qtg.chapter.delete', [$device->id, $upload->id]) }}"
                        method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </td>

            </tr>

            @endforeach

        </tbody>
    </table>

    @foreach ($chapters as $chap)
    <div class="modal fade" id="uploadModal{{ $chap->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('qtg.chapter.upload', $device->id) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="device_id" value="{{ $device->id }}">
                <input type="hidden" name="chapter_id" value="{{ $chap->id }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Upload QTG - {{ $chap->chapter_code }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Tahun QTG</label>
                            <input type="number" class="form-control" name="year" required value="{{ $current_year }}">
                        </div>

                        <div class="mb-3">
                            <label>File PDF</label>
                            <input type="file" class="form-control" name="filepath" accept="application/filepath" required>
                        </div>

                        <div class="mb-3">
                            <label>Result</label>
                            <select class="form-control" name="result" required>
                                <option value="">-- Pilih --</option>
                                <option value="passed">PASSED</option>
                                <option value="failed">FAILED</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Catatan (opsional)</label>
                            <textarea class="form-control" name="note"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endforeach

    @foreach ($uploads as $upload)
    <div class="modal fade" id="viewPdfModal{{ $upload->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">View PDF - {{ $upload->chapter->chapter_code }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="height: 80vh; padding:0;">
                    <iframe
                        src="{{ asset($upload->filepath) }}"
                        style="width:100%; height:100%; border:none;">
                    </iframe>
                </div>


            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Saat pilih tahun
        $('#formPilihTahun').on('submit', function() {
            Swal.fire({
                title: 'Mengganti Tahun...',
                text: 'Data sedang dimuat, mohon tunggu.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
        });

        // Saat tambah tahun
        $('#formTambahTahun').on('submit', function() {
            Swal.fire({
                title: 'Menambahkan Tahun...',
                text: 'Sedang memproses...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
        });

    });
</script>


@endsection