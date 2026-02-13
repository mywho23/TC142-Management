<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Device;
use App\Models\Jadwal;
use App\Models\LogbookTimerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogbookTimerController extends Controller
{
    public function start(Logbook $logbook)
    {
        $timer = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->whereIn('status', ['running', 'paused'])
            ->latest()
            ->first();

        if ($timer && $timer->status === 'running') {
            return response()->json([
                'message' => 'Timer masih berjalan',
                'timer_id' => $timer->id,
                'status' => $timer->status,
            ], 409);
        }

        if ($timer && $timer->status === 'paused') {
            return response()->json([
                'message' => 'Timer sedang pause, silakan resume',
                'timer_id' => $timer->id,
                'status' => $timer->status,
            ], 409);
        }

        $timer = LogbookTimerLog::create([
            'logbook_id'      => $logbook->id,
            'start_time'      => now(),
            'duration_second' => 0,
            'status'          => 'running',
        ]);

        return response()->json([
            'message'  => 'Timer dimulai',
            'timer_id' => $timer->id,
            'status'   => $timer->status,
            'duration' => 0,
        ]);
    }

    public function pause(Logbook $logbook)
    {
        $timer = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->where('status', 'running')
            ->latest()
            ->first();

        if (! $timer || ! $timer->start_time) {
            return response()->json([
                'message' => 'Timer tidak sedang berjalan'
            ], 200);
        }

        // hitung SELISIH AMAN (PASTI POSITIF)
        $elapsed = $timer->start_time->diffInSeconds(now());

        $oldDuration = (int) $timer->duration_second;

        $newDuration = $oldDuration + $elapsed;

        // HARD GUARD
        if ($newDuration < 0) {
            $newDuration = 0;
        }

        $timer->update([
            'duration_second' => $newDuration,
            'start_time'      => null,   // ⛔ PENTING
            'status'          => 'paused',
        ]);

        return response()->json([
            'message'  => 'Timer dipause',
            'status'   => 'paused',
            'duration' => $newDuration,
        ]);
    }

    public function resume(Logbook $logbook)
    {
        $timer = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->where('status', 'paused')
            ->latest()
            ->first();

        if (! $timer) {
            return response()->json([
                'message' => 'Tidak ada timer paused'
            ], 200);
        }

        $timer->update([
            'status'     => 'running',
            'start_time' => now(),   // ⬅️ INI YANG HILANG KEMARIN
        ]);

        return response()->json([
            'status'   => 'running',
            'duration' => $timer->duration_second,
        ]);
    }

    //'duration_second' => max(0, $duration), // 🛡️ safety
    public function stop(Logbook $logbook)
    {
        $timer = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->whereIn('status', ['running', 'paused'])
            ->latest()
            ->first();

        if (! $timer) {
            return response()->json([
                'message' => 'Tidak ada timer aktif'
            ], 404);
        }

        $duration = (int) $timer->duration_second;

        // HANYA HITUNG JIKA MASIH RUNNING
        if ($timer->status === 'running' && $timer->start_time) {
            $duration += now()->diffInSeconds($timer->start_time);
        }

        $timer->update([
            'duration_second' => max(0, $duration),
            'end_time'        => now(),
            'status'          => 'stopped',
            // ❌ JANGAN NULL START_TIME
        ]);

        return response()->json([
            'message'  => 'Timer dihentikan',
            'duration' => $duration
        ]);
    }


    public function select(Device $device)
    {
        $logbooks = Logbook::where('device_id', $device->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.teknisi.timerlog.select', [
            'device'   => $device,
            'logbooks' => $logbooks,
        ]);
    }

    public function create(Logbook $logbook)
    {
        // ambil device dari logbook
        $device = $logbook->device; // pastikan relasi ada

        // ambil jadwal berdasarkan device
        $jadwal = Jadwal::where('device_id', $device->id)->get();

        //ambil riwayat timer log
        $timerlogs = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeTimer = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->whereIn('status', ['running', 'paused'])
            ->latest()
            ->first();

        return view('pages.teknisi.timerlog.create', compact(
            'logbook',
            'device',
            'jadwal',
            'timerlogs',
            'activeTimer'
        ));
    }

    public function store(Request $request, Logbook $logbook)
    {
        DB::transaction(function () use ($logbook, &$timer) {

            $timer = LogbookTimerLog::where('logbook_id', $logbook->id)
                ->whereIn('status', ['stopped', 'running'])
                ->latest()
                ->lockForUpdate()
                ->firstOrFail();

            // finalisasi timer
            $timer->update([
                'status' => 'finished',
            ]);

            // simpan ke logbook utama
            $logbook->update([
                'durasi_pemakaian' => $timer->duration_second,
                'status'           => 'finished',
            ]);
        });

        // ambil ulang data untuk ditampilkan ke tabel
        $timerlogs = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->where('status', 'finished')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Timer berhasil disimpan',
            'data'    => $timerlogs
        ]);
    }
}

//public function tick(LogbookTimerLog $timerlog)
    //{
    // pastikan hanya timer running yang boleh ditambah
    //if ($timerlog->status !== 'running') {
    // return response()->json([
    //'message' => 'Timer tidak dalam status running',
    //'duration_second' => $timerlog->duration_second
    //], 200);
    //}

    // tambah durasi 10 detik
    //$timerlog->increment('duration_second', 10);

    //return response()->json([
    //'message' => 'Timer updated',
    //'duration_second' => $timerlog->duration_second
    //]);
    //}
