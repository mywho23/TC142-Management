<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Logbook;

class RekapJamController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy('device_name')->get();
        return view('pages.rekapjam.index', compact('devices'));
    }

    public function show(Request $request, Device $device)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $logbooks = collect();
        $totaljam = null;

        if ($bulan && $tahun) {
            $logbooks = Logbook::where('device_id', $device->id)
                ->whereNotNull('maintenance_accept_sign')
                ->whereMonth('start_time', $bulan)
                ->whereYear('start_time', $tahun)
                ->get();
        }

        $totalMenit = 0;
        foreach ($logbooks as $log) {
            if ($log->total_time) {
                preg_match('/(\d+)h\s*(\d+)m/', $log->total_time, $matches);
                $jam = isset($matches[1]) ? (int) $matches[1] : 0;
                $menit = isset($matches[2]) ? (int) $matches[2] : 0;
                $totalMenit += ($jam * 60) + $menit;
            }
        }

        // konversi ke jam
        $totalJam = floor($totalMenit / 60);
        $sisaMenit = $totalMenit % 60;

        return view('pages.rekapjam.show', compact(
            'device',
            'bulan',
            'tahun',
            'logbooks',
            'totalJam',
            'sisaMenit'
        ));
    }

    public function print(Request $request, Device $device)
    {
        // Ambil dari request yang dikirim via link button tadi
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Logic ambil data (Harus sama persis dengan method show)
        $logbooks = Logbook::where('device_id', $device->id)
            ->whereNotNull('maintenance_accept_sign')
            ->whereMonth('start_time', $bulan)
            ->whereYear('start_time', $tahun)
            ->get();

        $totalMenit = 0;
        foreach ($logbooks as $log) {
            if ($log->total_time) {
                preg_match('/(\d+)h\s*(\d+)m/', $log->total_time, $matches);
                $jam = isset($matches[1]) ? (int) $matches[1] : 0;
                $menit = isset($matches[2]) ? (int) $matches[2] : 0;
                $totalMenit += ($jam * 60) + $menit;
            }
        }

        $totalJam = floor($totalMenit / 60);
        $sisaMenit = $totalMenit % 60;

        // Nama bulan Indonesia buat judul print
        $bulanIndo = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM');

        return view('pages.rekapjam.print', compact(
            'device',
            'bulan',
            'tahun',
            'logbooks',
            'totalJam',
            'sisaMenit',
            'bulanIndo'
        ));
    }
}
