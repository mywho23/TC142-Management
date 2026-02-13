<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\LogbookTimerLog;
use App\Models\Diskrepansi;
use App\Models\Device;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index($device_id)
    {
        // Ambil semua logbook + relasi diskrepansi
        $device = Device::FindOrFail($device_id);
        $logbooks = Logbook::with('diskrepansi')
            ->where('device_id', $device_id)
            ->latest()
            ->get();

        return view('pages.logbook.index', [
            'device_id' => $device_id,
            'device' => $device,
            'logbooks' => $logbooks
        ]);
    }

    public function create($device_id)
    {
        // Ambil device
        $device = Device::findOrFail($device_id);

        // Ambil jadwal yang sesuai device
        $jadwals = Jadwal::where('device_id', $device_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pages.logbook.create', compact('device', 'jadwals'));
    }


    public function store(Request $request, $device_id)
    {
        $request->validate([
            'jadwal_id' => 'nullable|exists:tb_jadwal,id',
            'date' => 'required|date',
            'company' => 'required|string',
            'training_subject' => 'required|string',
            'instructors' => 'required',
            'trainees' => 'required',
            'start_time' => 'nullable',
            'finish_time' => 'nullable',
            'total_time' => 'nullable',
            'time_lost' => 'nullable|string',
            'diskrepansi_keterangan' => 'nullable|string',
            'sign_instructor' => 'nullable|string'
        ]);

        // Ambil id terakhir sebagai dasar nomor logbook
        $latest = Logbook::max('id') ?? 0;
        $logbook_no = 'NO: ' . str_pad($latest + 1, 4, '0', STR_PAD_LEFT);

        $logbook = Logbook::create([
            'logbook_no' => $logbook_no,
            'device_id' => $device_id,
            'jadwal_id' => $request->jadwal_id,
            'date' => $request->date,
            'company' => $request->company,
            'training_subject' => $request->training_subject,
            'instructors' => $request->instructors,
            'trainees' => $request->trainees,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'total_time' => $request->total_time,
            'time_lost' => $request->time_lost,
            'diskrepansi_keterangan' => $request->diskrepansi_keterangan,
            'sign_instructor' => $request->sign_instructor,

            // Timer auto nanti diisi dari dashboard teknisi
            'start_time_auto' => null,
            'finish_time_auto' => null,
            'total_time_auto' => null,

            // Masih draft sampai teknisi release/accept
            'status' => 'draft',
        ]);

        return redirect()
            ->route('logbook.index', $device_id)
            ->with('success', 'Draft logbook berhasil disimpan! Nomor: ' . $logbook_no);
    }

    // Tampilkan daftar logbook yang sudah di-release untuk customer
    public function indexCustomer($device_id)
    {
        $device = Device::findOrFail($device_id);

        // Ambil logbook yang sudah di-release tapi belum diisi customer
        $logbooks = Logbook::where('device_id', $device_id)
            ->whereNotNull('maintenance_release_time')
            ->whereNull('company') // artinya customer belum submit
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.logbook.index', [
            'device' => $device,
            'device_id' => $device_id,
            'logbooks' => $logbooks
        ]);
    }

    // Tampilkan form customer logbook
    public function editCustomer($device_id, $logbook_id)
    {
        $logbook = Logbook::findOrFail($logbook_id);
        // Ambil device
        $device = Device::findOrFail($device_id);

        // Ambil jadwal yang sesuai device
        $jadwals = Jadwal::where('device_id', $device_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pages.logbook.create', [
            'logbook' => $logbook,
            'device_id' => $device_id,
            'device' => $device,
            'jadwals' => $jadwals,
        ]);
    }

    // Submit logbook customer
    public function updateCustomer(Request $request, $device_id, $logbook_id)
    {
        $request->validate([
            'date' => 'required|date',
            'company' => 'required|string',
            'training_subject' => 'required|string',
            'instructors' => 'required',
            'trainees' => 'required',
            'start_time' => 'required',
            'finish_time' => 'required',
            'total_time' => 'required',
            'time_lost' => 'required',
            'diskrepansi_keterangan' => 'nullable|string',
            'sign_instructor' => 'nullable|string'
        ]);

        $logbook = Logbook::findOrFail($logbook_id);

        $logbook->update([
            'date' => $request->date,
            'company' => $request->company,
            'training_subject' => $request->training_subject,
            'instructors' => $request->instructors,
            'trainees' => $request->trainees,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'total_time' => $request->total_time,
            'time_lost' => $request->time_lost,
            'diskrepansi_keterangan' => $request->diskrepansi_keterangan,
            'sign_instructor' => $request->sign_instructor,
            'status' => 'filled' // update status setelah customer submit
        ]);

        // Redirect ke halaman index customer logbook dengan device_id
        return redirect()->route('logbook.indexCustomer', $device_id)
            ->with('success', 'Logbook berhasil diisi!');
    }

    public function detail($id)
    {
        $logbook = Logbook::findOrFail($id);
        $device = Device::find($logbook->device_id);

        $timerLogs = LogbookTimerLog::where('logbook_id', $logbook->id)
            ->orderBy('id', 'asc')
            ->get();


        // Ambil jawaban teknisi, 1 logbook = 1 respon teknisi (asumsi)
        $teknisiLog = \App\Models\Diskrepansi::where('logbook_id', $logbook->id)->first();

        return view('pages.logbook.detail', compact('logbook', 'device', 'teknisiLog', 'timerLogs'));
    }

    public function print($device_id, $logbook)
    {
        $logbook = Logbook::with(['diskrepansi', 'device'])
            ->where('id', $logbook)
            ->where('device_id', $device_id)
            ->firstOrFail();
        $diskrepansis = Diskrepansi::with('teknisi')
            ->where('logbook_id', $logbook->id)
            ->get();
        $device = Device::findOrFail($logbook->device_id);

        return view('pages.logbook.print', compact('logbook', 'diskrepansis', 'device'));
    }
}
