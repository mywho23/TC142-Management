<?php

namespace App\Http\Controllers;

use App\Models\Mmi;
use App\Models\Device;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class MmiController extends Controller
{
    public function index($device_id)
    {
        $device = Device::FindOrFail($device_id);
        $mmi = Mmi::where('device_id', $device_id)->get();
        $roleEngineer = Role::where('nama', 'Engineer')->first();
        $executor = User::where('role_id', $roleEngineer->id)
            ->where('status', 'active')
            ->get();
        return view('pages.mmi.index', [
            'mmi' => $mmi,
            'device' => $device,
            'device_id' => $device_id,
            'executor' => $executor,
        ]);
    }

    public function create($device_id)
    {
        $device = Device::FindOrFail($device_id);
        //$device_name = Device::orderBy('device_name')->get();
        $roleEngineer = Role::where('nama', 'Engineer')->first();
        $executor = User::where('role_id', $roleEngineer->id)
            ->where('status', 'active')
            ->get();
        return view('pages.mmi.create', compact('device', 'executor'));
    }

    public function store(Request $request, $device_id)
    {
        $validated = $request->validate([
            'subjek' => 'required|string|max:225',
            'tanggal_temuan' => 'required|date',
            'reporter' => 'required|string|in:pti,sqm,operation,customer,engineer',
            'aksi_perbaikan' => 'nullable|string|max:225',
            'tanggal_perbaikan' => 'nullable|date',
            'executor_id' => 'nullable|exists:tb_user,id',
            'status' => 'required|string|in:open,closed',
        ], [
            'subjek' => 'Subjek wajib diisi.',
            'tanggal_temuan' => 'Tanggal temuan wajib diisi.',
            'reporter' => 'Reporter wajib diisi.',
            'status' => 'Status wajib diisi.',
        ]);
        $validated['device_id'] = $device_id;
        Mmi::create($validated);
        return redirect()->route('mmi.index', $device_id)->with('success', 'Data Berhasil Ditambahkan.');
    }

    public function hapus($device_id, $id)
    {
        $mmi = Mmi::FindOrFail($id);
        $mmi->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subjek' => 'required|string',
            'tanggal_temuan' => 'required|date',
            'reporter' => 'required|string',
            'aksi_perbaikan' => 'nullable|string',
            'tanggal_perbaikan' => 'nullable|date',
            'executor_id' => 'nullable|exists:tb_user,id',
            'status' => 'required',
        ]);

        $mmi = Mmi::FindOrFail($id);
        $mmi->update($request->all());

        return redirect()->back()->with('success', 'Data Berhasil Diperbarui.');
    }

    public function print($device_id)
    {
        $device = Device::FindOrFail($device_id);
        $mmi = Mmi::where('device_id', $device_id)->get();
        return view('pages.mmi.print', [
            'mmi' => $mmi,
            'device' => $device,
            'device_id' => $device_id,
        ]);
    }
}
