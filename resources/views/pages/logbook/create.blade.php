@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Isi Logbook - Simulator: {{ $logbook->device->device_name }}</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('logbook.updateCustomer', [$device_id, $logbook->id]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Release info --}}
        <div class="mb-3">
            <label class="form-label">Nomor Logbook</label>
            <input type="text" class="form-control" value="{{ $logbook->logbook_no }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Release Time</label>
            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($logbook->maintenance_release_time)->format('d-m-Y H:i') }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Release Signature</label>
            <input type="text" class="form-control" value="{{ $logbook->maintenance_release_sign }}" disabled>
        </div>

        {{-- Customer fields --}}
        <div class="mb-3">
            <label class="form-label">Date</label>
            <select name="date" class="form-control" required>
                @foreach($jadwals as $jadwal)
                <option value="{{ $jadwal->tanggal }}"
                    {{ old('date', $logbook->date) == $jadwal->tanggal ? 'selected' : '' }}>
                    {{ $jadwal->tanggal }} | {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }} | {{ $jadwal->customer }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Company</label>
            <input type="text" name="company" class="form-control" value="{{ old('company', $logbook->company) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Training Subject</label>
            <input type="text" name="training_subject" class="form-control" value="{{ old('training_subject', $logbook->training_subject) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Instructors</label>
            <input type="text" name="instructors" class="form-control" value="{{ old('instructors', $logbook->instructors) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Trainees</label>
            <input type="text" name="trainees" class="form-control" value="{{ old('trainees', $logbook->trainees) }}" required>
        </div>

        {{-- Manual Time --}}
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Start Time</label>
                <input type="time" name="start_time" id="startManual" class="form-control" value="{{ old('start_time', $logbook->start_time) }}" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Finish Time</label>
                <input type="time" name="finish_time" id="finishManual" class="form-control" value="{{ old('finish_time', $logbook->finish_time) }}" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Total Time</label>
                <input type="text" name="total_time" id="totalManual" class="form-control" value="{{ old('total_time', $logbook->total_time) }}" readonly>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Time Lost</label>
                <input type="text" name="time_lost" id="timeLostManual" class="form-control" value="{{ old('time_lost', $logbook->time_lost) }}">
            </div>
        </div>

        <div class="mb-3">
            <button type="button" id="btnHitung" class="btn btn-secondary w-100">Hitung Total Time</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Diskrepansi Keterangan</label>
            <textarea name="diskrepansi_keterangan" class="form-control">{{ old('diskrepansi_keterangan', $logbook->diskrepansi_keterangan) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Sign Instructor <span class="text-danger">*</span></label>
            <small class="d-block text-muted mb-2">
                Kolom ini wajib diisi oleh customer. Logbook tidak dapat diproses teknisi sebelum sign instructor terisi.
            </small>
            <input type="text" name="sign_instructor" class="form-control" value="{{ old('sign_instructor', $logbook->sign_instructor) }}">
        </div>

        <button type="submit" class="btn btn-success">Submit Logbook</button>
    </form>
</div>

<script>
    document.getElementById('btnHitung').addEventListener('click', function() {
        const start = document.getElementById('startManual').value;
        const finish = document.getElementById('finishManual').value;

        if (!start || !finish) {
            alert('Harap isi start dan finish time terlebih dahulu!');
            return;
        }

        const [startH, startM] = start.split(':').map(Number);
        const [finishH, finishM] = finish.split(':').map(Number);

        let diffMinutes = (finishH * 60 + finishM) - (startH * 60 + startM);
        if (diffMinutes < 0) diffMinutes += 24 * 60;

        const hours = Math.floor(diffMinutes / 60);
        const minutes = diffMinutes % 60;

        document.getElementById('totalManual').value = `${hours}h ${minutes}m`;
    });
</script>
@endsection