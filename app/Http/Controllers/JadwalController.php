<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Device;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index($device_id)
    {
        $device = Device::FindOrFail($device_id);
        $jadwal = Jadwal::where('device_id', $device_id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();
        return view('pages.jadwal.index', [
            'jadwal' => $jadwal,
            'device_id' => $device_id,
            'device' => $device
        ]);
    }

    public function create($device_id)
    {
        $selectedDevice = Device::findOrFail($device_id);
        $device = Device::orderBy('device_name')->get();
        return view('pages.jadwal.create', compact('device', 'selectedDevice'));
    }

    public function store(Request $request, $device_id)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:tb_device,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'customer' => 'required|string|max:255',
            'status' => 'required|string|in:booked,cancelled',
            'keterangan' => 'nullable|string|max:225',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'customer.required' => 'Nama customer wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);
        Jadwal::create($validated);
        return redirect()->route('jadwal.index', $device_id)->with('success', 'Data Berhasil Ditambahkan.');
    }

    public function hapus($device_id, $id)
    {
        $jadwal = Jadwal::FindOrFail($id);
        $jadwal->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'customer' => 'required|string',
            'status' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->back()->with('success', 'Jadwal Berhasil Diperbarui.');
    }
}
