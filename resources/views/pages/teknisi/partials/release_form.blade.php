<form action="{{ route('teknisi.release', $device->id) }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Release Time</label>
        <input type="datetime-local" name="maintenance_release_time" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Release Signature</label>
        <input type="text" name="maintenance_release_sign" class="form-control" required>
    </div>

    <button class="btn btn-primary">Submit Release</button>
</form>