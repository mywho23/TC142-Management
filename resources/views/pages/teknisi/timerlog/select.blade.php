@extends('layouts.template')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <strong>Timer Maintenance</strong>
        </div>

        <div class="card-body">
            {{-- Info Device --}}
            <div class="mb-3">
                <label class="form-label">Device</label>
                <input type="text"
                    class="form-control"
                    value="{{ $device->nama ?? $device->device_name }}"
                    disabled>
            </div>

            {{-- Pilih Logbook --}}
            <div class="mb-3">
                <label class="form-label">Pilih Logbook</label>
                <select id="logbook_id" class="form-control">
                    <option value="">-- Pilih Logbook --</option>
                    @foreach ($logbooks as $logbook)
                    <option value="{{ $logbook->id }}">
                        {{ $logbook->logbook_no ?? 'Logbook #' . $logbook->id }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Button Next --}}
            <button id="btnNext" class="btn btn-primary">
                Next
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnNext').addEventListener('click', function() {
        const logbookId = document.getElementById('logbook_id').value;

        if (!logbookId) {
            alert('Silakan pilih logbook terlebih dahulu');
            return;
        }

        window.location.href = `/timer/create/${logbookId}`;
    });
</script>

@endsection