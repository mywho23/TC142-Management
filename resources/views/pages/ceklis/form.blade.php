@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>{{ $tipe->nama_tipe }} Checklist — {{ $device->device_name }}</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('ceklis.store') }}" method="POST">
            @csrf

            <input type="hidden" name="device_id" value="{{ $device->id }}">
            <input type="hidden" name="tipe_ceklis_id" value="{{ $tipe->id }}">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Subject</th>
                        <th>Action</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $item->subjek }}</td>
                        <td>{{ $item->action }}</td>
                        <td>
                            <select name="result[{{ $item->id }}]" class="form-control" required>
                                <option value="">Choose</option>
                                <option value="done">Done</option>
                                <option value="pending">Pending</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-success w-100">Submit Checklist</button>
        </form>
    </div>
</div>
@endsection