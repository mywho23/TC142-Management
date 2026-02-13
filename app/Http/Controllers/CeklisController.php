<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\TipeCeklis;
use App\Models\CeklisItem;
use App\Models\CeklisResult;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // <--- ini bre!
use Illuminate\Http\Request;

class CeklisController extends Controller
{
    public function index()
    {
        // Riwayat — ambil CeklisResult + related item->device->tipe, grup by device+tipe terbaru
        $ceklisDone = \App\Models\CeklisResult::with(['device', 'item.tipe'])
            ->join('tb_device as d', 'tb_ceklis_result.device_id', '=', 'd.id')
            ->orderByRaw("CASE 
        WHEN d.device_code LIKE 'ffs%' THEN 1
        ELSE 2 
    END")
            ->orderBy('tanggal_cek', 'desc')
            ->select('tb_ceklis_result.*') // penting supaya model tetap normal
            ->get()
            ->unique(function ($r) {
                $device = $r->device->device_code ?? null; // pakai device_id
                $tipe = $r->item->tipe->id ?? null;
                return $device . '|' . $tipe;
            });


        $notes = Note::orderBy('device')->get();
        $today = Carbon::now();
        // helper booleans
        $isLastDayOfMonth = ($today->day == $today->daysInMonth);
        $month = $today->month;
        $day = $today->day;
        // week in month (1..5) menggunakan pembagian 7-hari
        $weekInMonth = (int) ceil($day / 7);

        foreach ($notes as $note) {
            if ($note->tipe == 'daily') {
                // aktif hanya di hari terakhir bulan
                $note->can_check = ($today->day == $today->daysInMonth);
            } elseif ($note->tipe == 'weekly') {
                // aktif sepanjang minggu berjalan
                $startOfWeek = $today->copy()->startOfWeek();
                $endOfWeek = $today->copy()->endOfWeek();
                $note->can_check = $today->between($startOfWeek, $endOfWeek);
            } elseif ($note->tipe === 'biweekly') {
                // aktif di minggu ke-1 dan ke-3 tiap bulan
                $note->can_check = in_array($weekInMonth, [1, 3]);
            } elseif ($note->tipe == 'monthly') {
                // aktif hanya hari terakhir bulan
                $note->can_check = ($today->day == $today->daysInMonth);
            } elseif ($note->tipe == 'quarterly') {
                // aktif hanya di akhir bulan 3, 6, 9, 12
                $quarterMonths = [3, 6, 9, 12];
                $note->can_check = (
                    in_array($today->month, $quarterMonths)
                    && $today->day == $today->daysInMonth
                );
            } elseif ($note->tipe == 'halfyearly') {
                // aktif hanya akhir bulan 6 & 12
                $halfYearMonths = [6, 12];
                $note->can_check = (
                    in_array($today->month, $halfYearMonths)
                    && $today->day == $today->daysInMonth
                );
            } elseif ($note->tipe == 'yearly') {
                // aktif hanya 31 Desember
                $note->can_check = ($today->month == 12 && $today->day == 31);
            } else {
                $note->can_check = false;
            }
        }

        return view('pages.ceklis.index', compact('ceklisDone', 'notes'));
    }

    public function updateStatus(Request $request)
    {
        $note = \App\Models\Note::find($request->id);

        if (!$note) {
            return response()->json(['success' => false, 'message' => 'Note tidak ditemukan']);
        }

        $note->status = !$note->status;
        $note->save();

        return response()->json([
            'success' => true,
            'status' => $note->status,
        ]);
    }

    public function create(Request $request)
    {
        $devices = Device::all();
        $tipeCeklis = TipeCeklis::all();

        if (!$request->device_id) {
            return view('pages.ceklis.create', compact('devices', 'tipeCeklis'));
        }

        $device = Device::findOrFail($request->device_id);
        $group = explode('_', $device->device_code)[0]; // ffs / ftd

        $items = CeklisItem::where('device_grup', $group)
            ->where('tipe_ceklis_id', $request->tipe_ceklis_id)
            ->orderBy('order_number')
            ->get();

        $tipe = TipeCeklis::find($request->tipe_ceklis_id);

        return view('pages.ceklis.form', compact('device', 'items', 'tipe'));
    }

    public function nextStep(Request $request)
    {
        $request->validate([
            'device_id' => 'required',
            'tipe_ceklis_id' => 'required',
        ]);

        return redirect()->route('ceklis.items', [
            'device' => $request->device_id,
            'tipe' => $request->tipe_ceklis_id
        ]);
    }

    public function showItems($device_id, $tipe_id)
    {
        $device = Device::findOrFail($device_id);

        // ambil grup device dari kode, misal "ffs" dari "ffs_a320"
        $device_grup = explode('_', strtolower($device->device_code))[0];

        // ambil data checklist item sesuai device dan tipe checklist
        $tipe = TipeCeklis::find($tipe_id);
        $items = CeklisItem::where('device_grup', $device_grup)
            ->where('tipe_ceklis_id', $tipe_id)
            ->orderBy('id')
            ->get();

        return view('pages.ceklis.item', compact('device', 'tipe', 'items'));
    }


    public function store(Request $request, $device_id, $tipe)
    {
        // misalnya lo mau looping data dari form
        foreach ($request->status as $item_id => $status) {
            CeklisResult::insert([
                'ceklis_item_id' => $item_id,
                'device_id'      => $device_id,
                'result' => $status ?? 'pending',
                'notes' => $request->notes[$item_id] ?? null,
                'tanggal_cek' => now(),
                'nama_teknisi' => $request->nama_teknisi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('ceklis.index')->with('success', "Checklist $tipe untuk device ID $device_id berhasil disimpan!");
    }

    public function print($id)
    {
        // ambil satu row sebagai "header" — eager load item + tipe + device (via device_id)
        $first = CeklisResult::with(['item.tipe', 'device'])->findOrFail($id);

        // safety: pastikan item ada
        if (!$first->item) {
            abort(404, 'Ceklis item not found for this result (id: ' . $id . ').');
        }

        // ambil batch: semua row dengan tanggal_cek + device_id yang sama
        $ceklis = CeklisResult::with(['item'])
            ->where('device_id', $first->device_id)
            ->whereHas('item', function ($q) use ($first) {
                $q->where('tipe_ceklis_id', $first->item->tipe_ceklis_id);
            })
            ->whereDate('tanggal_cek', '=', \Carbon\Carbon::parse($first->tanggal_cek)->toDateString())
            ->orderBy('ceklis_item_id')
            ->get();

        // prepare device_code fallback: prefer result->device (device_id),
        // kalau null coba ambil dari item->device (jika CeklisItem->device relasi sudah ada)
        $deviceCode = $first->device->device_code ?? ($first->item->device->device_code ?? null);
        $tipeName = $first->item->tipe->nama ?? null;

        return view('pages.ceklis.print', compact('first', 'ceklis', 'deviceCode', 'tipeName'));
    }

    public function delete($device_id, $id)
    {
        // ambil salah satu result untuk referensi batch
        $result = \App\Models\CeklisResult::with('item')->find($id);

        if (!$result) {
            return redirect()->back()->with('error', "Data dengan ID $id tidak ditemukan.");
        }

        // pastikan item-nya ada (buat ambil tipe_ceklis_id)
        if (!$result->item) {
            return redirect()->back()->with('error', "Ceklis item untuk ID $id tidak ditemukan.");
        }

        // hapus semua hasil dengan device_id + tanggal_cek + tipe_ceklis_id yang sama
        $deleted = \App\Models\CeklisResult::where('device_id', $device_id)
            ->where('tanggal_cek', $result->tanggal_cek)
            ->whereHas('item', function ($q) use ($result) {
                $q->where('tipe_ceklis_id', $result->item->tipe_ceklis_id);
            })
            ->delete();

        return redirect()->back()->with(
            'success',
            "Berhasil hapus $deleted data hasil checklist untuk tipe " .
                ucfirst($result->item->tipe->nama ?? '-') .
                " tanggal " . \Carbon\Carbon::parse($result->tanggal_cek)->format('d M Y')
        );
    }
}
