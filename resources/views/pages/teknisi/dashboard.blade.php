@extends('layouts.template')

@section('content')
    <div class="container mt-4">

        <h3 class="mb-4">Pilih Simulator</h3>

        <div class="row">
            @foreach ($devices as $item)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('teknisi.devicepanel', $item->id) }}" style="text-decoration: none;">
                        <div class="card shadow-sm border-0 hover-shadow"
                            style="border-radius: 12px; transition: transform .2s, box-shadow .2s;">

                            <div class="card-body text-center position-relative">
                                {{-- Indikator Visual (Dot) di Pojok Kanan Atas --}}
                                @php
                                    $statusColor = 'bg-secondary'; // Default
                                    if ($item->status == 'rft') {
                                        $statusColor = 'bg-success';
                                    } elseif ($item->status == 'maintenance') {
                                        $statusColor = 'bg-warning';
                                    } elseif ($item->status == 'inactive') {
                                        $statusColor = 'bg-danger';
                                    }
                                @endphp

                                <div class="{{ $statusColor }}"
                                    style="width: 15px; height: 15px; border-radius: 50%; position: absolute; top: 15px; right: 15px; border: 2px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.2);"
                                    title="Status: {{ strtoupper($item->status) }}">
                                </div>

                                {{-- Icon Device --}}
                                <div class="mb-3">
                                    <i class="fa fas fa-cogs" style="font-size: 42px; color: #4a76fd;"></i>
                                </div>

                                <h5 class="fw-bold text-dark">{{ $item->device_name }}</h5>

                                <span class="badge bg-primary" style="padding: 6px 12px; border-radius: 8px;">
                                    Akses Panel
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        @php
            // Gunakan Carbon agar bisa manipulasi bahasa
            $dateCarbon = \Carbon\Carbon::parse($selectDate)->locale('id');
        @endphp

        <div
            class="d-flex justify-content-between align-items-center mb-3 bg-white p-3 shadow-sm rounded border-start border-primary border-4">
            <div>
                <h5 class="text-muted mb-0 small text-uppercase fw-bold">Jadwal Simulator</h5>
                <h3 class="mb-0 fw-bold">
                    {{ $dateCarbon->isoFormat('dddd') }},
                    <span class="text-primary">{{ $dateCarbon->isoFormat('D MMMM YYYY') }}</span>
                </h3>
            </div>

            <form action="{{ url()->current() }}" method="GET" id="dateForm"
                class="d-flex align-items-center bg-light p-2 rounded">
                <div class="btn-group me-3">
                    <a href="?date={{ date('Y-m-d', strtotime($selectDate . ' -1 day')) }}"
                        class="btn btn-white border shadow-sm btn-sm">
                        <i class="fas fa fa-chevron-left"></i>
                    </a>
                    <a href="?date={{ date('Y-m-d') }}" class="btn btn-primary btn-sm px-3 shadow-sm">Hari Ini</a>
                    <a href="?date={{ date('Y-m-d', strtotime($selectDate . ' +1 day')) }}"
                        class="btn btn-white border shadow-sm btn-sm">
                        <i class="fas fa fa-chevron-right"></i>
                    </a>
                </div>

                <input type="date" name="date" class="form-control form-control-sm border-primary"
                    style="width: 150px; cursor: pointer;" value="{{ $selectDate }}" onchange="this.form.submit()">
            </form>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered mb-0" style="table-layout: fixed;">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 150px;" class="text-center align-middle">Device</th>
                        @foreach (range(0, 23) as $hour)
                            <th class="text-center small p-2" style="width: 50px;">{{ sprintf('%02d', $hour) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($devices as $device)
                        <tr>
                            <td class="fw-bold align-middle bg-light text-center small">{{ $device->device_name }}</td>

                            @foreach (range(0, 23) as $hour)
                                @php
                                    // Cek apakah di jam ini ada jadwal yang aktif
                                    $activeSchedule = null;
                                    if (isset($jadwal[$device->id])) {
                                        foreach ($jadwal[$device->id] as $j) {
                                            $start = \Carbon\Carbon::parse($j->jam_mulai);
                                            $end = \Carbon\Carbon::parse($j->jam_selesai);

                                            $startHour = $start->hour;
                                            // Trik: Kalau end jam 00, kita anggap dia jam 24 buat keperluan visual
                                            $endHour = $end->hour == 0 && $end->minute == 0 ? 24 : $end->hour;

                                            if ($hour >= $startHour && $hour < $endHour) {
                                                $activeSchedule = $j;
                                                break;
                                            }
                                        }
                                    }
                                    // Tentukan warna berdasarkan status
                                    $bgColor = '';
                                    if ($activeSchedule) {
                                        $bgColor = $activeSchedule->status == 'booked' ? 'bg-primary' : 'bg-success';
                                    }
                                @endphp

                                <td class="{{ $bgColor }} p-0 position-relative"
                                    style="height: 50px; cursor: pointer;"
                                    @if ($activeSchedule) data-bs-toggle="tooltip" 
                            data-bs-html="true"
                            title="Jam: {{ date('H:i', strtotime($activeSchedule->jam_mulai)) }} - {{ date('H:i', strtotime($activeSchedule->jam_selesai)) }} Status: {{ strtoupper($activeSchedule->status) }}
Customer: {{ strtoupper($activeSchedule->customer) }}" @endif>

                                    @php
                                        $now = now(); // Ambil waktu sekarang sesuai timezone Asia/Jakarta
                                        // Asumsi di controller lo kirim variabel $selectDate (tanggal yang lagi dibuka)
                                        // Kalau di controller namanya $selectedDate, tinggal tambahin 'ed' ya Bre
                                        $isToday = isset($selectDate) ? $selectDate == $now->format('Y-m-d') : true;
                                    @endphp

                                    @if ($isToday && $hour == $now->hour)
                                        <div
                                            style="position: absolute; left: {{ ($now->minute / 60) * 100 }}%; top: 0; bottom: 0; width: 2px; background: red; z-index: 10;">
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br>

            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Lihat Semua Jadwal</label>
                    {{-- Kasih ID biar gampang diambil JS --}}
                    <select id="selectDevice" class="form-control" required>
                        <option value="">Pilih Device</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->device_name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Klik Next langsung jalanin fungsi JS --}}
                <button type="button" onclick="goToJadwal()" class="btn btn-primary">Next</button>
            </div>

        </div>

        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
        <script>
            function goToJadwal() {
                // 1. Ambil ID dari dropdown
                const deviceId = document.getElementById('selectDevice').value;

                if (deviceId) {
                    // 2. Bentuk URL-nya sesuai prefix route lo
                    // Hasilnya bakal: /jadwal/1/menu atau /jadwal/6/menu
                    const targetUrl = `/jadwal/${deviceId}/menu`;

                    // 3. Langsung pindah halaman
                    window.location.href = targetUrl;
                } else {
                    alert('Pilih devicenya dulu dong, Bre!');
                }
            }
        </script>
    @endsection
