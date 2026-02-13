@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">
            Rekap Jam - {{ $device->device_name }}
        </h4>

        <div class="card mb-4">
            <div class="card-body">

                <form method="GET">
                    <div class="row align-items-end">

                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-control" required>
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-control" required>
                                <option value="">Pilih Tahun</option>
                                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                Tampilkan
                            </button>
                        </div>

                    </div>
                </form>
                <br>
                @if ($bulan && $tahun)
                    <div class="card">
                        <div class="card-body">

                            <h5 class="mb-3">
                                Total Jam Pakai:
                                <strong>
                                    {{ $totalJam }} Jam
                                    @if ($sisaMenit > 0)
                                        {{ $sisaMenit }} Menit
                                    @endif
                                </strong>
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Start</th>
                                            <th>Finish</th>
                                            <th>Time Lost</th>
                                            <th>Total Jam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($logbooks as $log)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($log->start_time)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }}</td>
                                                <td>
                                                    {{ $log->finish_time ? \Carbon\Carbon::parse($log->finish_time)->format('H:i') : '-' }}
                                                </td>
                                                <td>{{ $log->time_lost ?? 0 }}</td>
                                                <td><strong>{{ $log->total_time }}</strong></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    Tidak ada data logbook
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <a href="{{ route('jadwal.print', ['device' => $device->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                                        target="_blank" class="btn btn-danger mb-3">
                                        <i class="fas fa fa-print"></i> Print Data
                                    </a>
                                </table>
                            </div>

                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
@endsection
