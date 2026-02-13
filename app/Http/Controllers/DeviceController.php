<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        return view('pages.users.index', compact('device'));
    }

    public function create()
    {
        $devices = Device::all();
        $totalDevices = Device::count();
        return view('pages.dashboard', compact('devices', 'totalDevices'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:50',
            'deskripsi' => 'required|string|max:225',
            'status' => 'required|in:inactive,rft,maintenance',
            'device_code' => 'unique:tb_device,device_code'
        ]);

        Device::create([
            'device_name' => $request->device_name,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'device_code' => Str::slug($request->device_name, '_')
        ]);
        return redirect()->back()->with('success', 'Device berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $device = Device::FindOrFail($id);
        return view('pages.dashboard', compact('device'));
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $request->validate([
            'device_name' => 'required|string|max:50',
            'deskripsi' => 'required|string|max:225',
            'status' => 'required|in:inactive,rft,maintenance',
            'device_code' => 'unique:tb_device,device_code'
        ]);

        $device->update([
            'device_name' => $request->device_name,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'device_code' => Str::slug($request->device_name, '_'),
        ]);

        return redirect()->back()->with('success', 'Device berhasil diubah.');
    }
}
