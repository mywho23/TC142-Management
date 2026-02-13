<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Logbook;
use App\Models\Diskrepansi;
use App\Models\Device;
use App\Models\Record;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class TeknisiLogbookController extends Controller
{
    public function dashboard(Request $request)
    {
        $devices = Device::all();
        $selectDate = $request->get('date', date('Y-m-d'));
        $jadwal = Jadwal::whereDate('tanggal', $selectDate)
            ->orderBy('jam_mulai', 'asc')
            ->get()
            ->groupBy('device_id');
        return view('pages.teknisi.dashboard', [
            'devices' => $devices,
            'selectDate' => $selectDate,
            'jadwal' => $jadwal
        ]);
    }

    public function devicePanel($id)
    {
        $device = Device::findOrFail($id);

        // Logic Hitung Greasing 6 Bulan
        $lastGreasing = Record::where('device_id', $id)
            ->where(function ($q) {
                $q->where('keyword', 'like', '%greasing%')
                    ->orWhere('keyword', 'like', '%pelumas%');
            })
            ->latest('date_issue') // Pastikan kolom tanggal sesuai di DB lo
            ->first();

        $dataGreasing = [
            'status' => 'no_data',
            'days_left' => null,
            'percent' => 0,
            'color' => 'secondary'
        ];

        if ($lastGreasing) {
            $lastDate = Carbon::parse($lastGreasing->date_issue); // Pake field date_issue
            $deadline = $lastDate->copy()->addMonths(6);

            // Gunakan diffInDays tanpa parameter 'false' kalau mau angka absolut, 
            // atau tetep pake 'false' tapi kita handle di Blade.
            // Kita pake ceil() biar dibuletin ke atas
            $daysLeft = ceil(now()->diffInDays($deadline, false));

            // Persentase (Asumsi 6 bulan = 182 hari biar lebih akurat)
            $percent = ($daysLeft / 182) * 100;
            $percent = max(0, min(100, $percent));

            $dataGreasing = [
                'status' => 'active',
                'last_date' => $lastDate->format('d M Y'),
                'days_left' => (int)$daysLeft, // Pakai (int) biar gak ada .00
                'percent' => $percent,
                'color' => $daysLeft <= 30 ? 'danger' : ($daysLeft <= 60 ? 'warning' : 'success')
            ];
        }

        return view('pages.teknisi.devicepanel', compact('device', 'dataGreasing'));
    }

    public function menuLogbook($device_id)
    {
        $device = Device::findOrFail($device_id);

        // Semua logbook per device
        $logbooks = Logbook::where('device_id', $device_id)
            ->orderBy('id', 'desc')
            ->get();

        $latest = Logbook::where('device_id', $device->id)
            ->orderBy('id', 'DESC')
            ->first();

        $history = Logbook::where('device_id', $device->id)
            ->whereNotNull('maintenance_release_time')
            ->orderBy('id', 'desc')
            ->get();
        $pendingLogbook = Logbook::where('sign_instructor', 'filled')->latest()->first();

        return view('pages.teknisi.menulogbook', [
            'device' => $device,
            'device_id' => $device_id,
            'logbooks' => $logbooks,
            'latest' => $latest,
            'history' => $history,
            'pendingLogbook' => $pendingLogbook,
        ]);
    }

    public function release(Request $request, $device_id)
    {
        $request->validate([
            'maintenance_release_time' => 'required',
            'maintenance_release_sign' => 'required|string'
        ]);

        // Cek apakah masih ada sesi sebelumnya yang belum complete
        $existing = Logbook::where('device_id', $device_id)
            ->whereNull('maintenance_accept_time') // belum di-ACCEPT
            ->first();

        if ($existing) {
            return redirect()
                ->back()
                ->with('error', 'Masih ada sesi sebelumnya yang belum di-ACCEPT. Tidak bisa release baru.');
        }

        DB::transaction(function () use ($request, $device_id) {

            // Ambil id terakhir
            $device = Device::findOrFail($device_id);
            $lastSeq = Logbook::where('device_id', $device->id)
                ->max('logbook_seq') ?? 0;
            $nextSeq = $lastSeq + 1;
            $logbookNo = $device->device_name . ' - ' . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);

            Logbook::create([
                'logbook_no' => $logbookNo,
                'logbook_seq' => $nextSeq,
                'device_id' => $device_id,
                'maintenance_release_time' => $request->maintenance_release_time,
                'maintenance_release_sign' => $request->maintenance_release_sign,
                'status' => 'released',
            ]);
        });

        return redirect()
            ->route('teknisi.device', $device_id)
            ->with('success', 'Simulator berhasil release oleh teknisi!');
    }

    // ACCEPT: teknisi menutup sesi
    public function accept(Request $request, $device_id)
    {
        $request->validate([
            'logbook_id' => 'required|exists:tb_logbook,id',
            'maintenance_accept_time' => 'required',
            'maintenance_accept_sign' => 'required|string'
        ]);

        $logbook = Logbook::findOrFail($request->logbook_id);

        $logbook->update([
            'maintenance_accept_time' => $request->maintenance_accept_time,
            'maintenance_accept_sign' => $request->maintenance_accept_sign,
            'status' => 'done'
        ]);

        return redirect()
            ->route('teknisi.device', $device_id)
            ->with('success', 'Sesi Selesai, Logbook Telah di Acceptance!');
    }

    public function komplainPending($device_id)
    {
        // Ambil semua komplain customer yang belum dijawab teknisi
        $pending = Logbook::where('device_id', $device_id)
            ->whereNotNull('diskrepansi_keterangan')
            ->where('diskrepansi_keterangan', '!=', '')
            ->whereRaw("LOWER(TRIM(diskrepansi_keterangan)) NOT IN ('nill', '-', 'nil', 'none')")
            ->whereNull('diskrepansi_id') // bila pake diskrepansi_id di logbook
            ->orderBy('id', 'desc')
            ->get();

        $history = Diskrepansi::with('teknisi')
            ->where('device_id', $device_id)
            ->orderBy('tanggal_pengerjaan', 'desc')
            ->get();


        // Data device (buat judul di blade)
        $device = Device::findOrFail($device_id);

        return view('pages.teknisi.diskrepansi.pending', compact('pending', 'device', 'history'));
    }

    public function jawabForm($logbook_id)
    {
        $logbook = Logbook::findOrFail($logbook_id);

        return view('pages.teknisi.diskrepansi.jawab', compact('logbook'));
    }

    public function jawabStore(Request $request, $logbook_id)
    {
        $logbook = Logbook::findOrFail($logbook_id);

        $request->validate([
            'tanggal_pengerjaan' => 'required|date',
            'aksi_pengerjaan' => 'required',
            'status' => 'required|in:open,progress,done',
        ]);

        // 1. Simpan jawaban ke tb_diskrepansi
        $disk = Diskrepansi::create([
            'logbook_id' => $logbook_id,
            'device_id' => $logbook->device_id,
            'teknisi_id' => Auth::user()->id,
            'tanggal_pengerjaan' => $request->tanggal_pengerjaan,
            'aksi_pengerjaan' => $request->aksi_pengerjaan,
            'status' => $request->status,
        ]);

        // 2. Update tb_logbook → link ke jawaban diskrepansi
        $logbook->update([
            'diskrepansi_id' => $disk->id,
        ]);

        return redirect()->route('teknisi.diskrepansi.pending', $logbook->device_id)
            ->with('success', 'Diskrepansi telah dijawab.');
    }

    public function deleteLogbook($id)
    {
        DB::beginTransaction();

        try {
            $logbook = Logbook::findOrFail($id);
            $deviceId = $logbook->device_id; // simpan dulu

            Diskrepansi::where('logbook_id', $id)->delete();
            $logbook->delete();

            DB::commit();

            return redirect()
                ->route('teknisi.device', $deviceId)
                ->with('success', 'Logbook berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
