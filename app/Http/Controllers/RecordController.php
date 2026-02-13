<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Device;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function menu($device_id)
    {
        $device = Device::FindOrFail($device_id);
        $record = Record::where('device_id', $device_id)->get();

        return view('pages.record.menu', [
            'device' => $device,
            'record' => $record,
            'device_id' => $device_id
        ]);
    }

    public function create($device_id)
    {
        $device = Device::FindOrFail($device_id);
        $sim = Device::where('device_name')->get();
        return view('pages.record.create', compact('device', 'sim'));
    }

    public function save(Request $request, $device_id)
    {
        $request->validate(
            [
                'device_id' => 'required|exists:tb_device,id',
                'date_issue' => 'required|date',
                'issue' => 'required|string|max:500',
                'tanggal_perbaikan' => 'nullable|date',
                'aksi_perbaikan' => 'nullable|string|max:500',
                'status' => 'required|in:done,pending',
                'keyword' => 'required|string|max:225',
                'pic' => 'nullable|image|mimes:jpg,jpeg,png'
            ]
        );
        // upload file
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('template/assets/images/record'), $filename);

            $path = $filename;
        } else {
            $path = null; // pastikan path ter-set
        }

        \App\Models\Record::create([
            'device_id' => $device_id,
            'date_issue' => $request->date_issue,
            'issue' => $request->issue,
            'tanggal_perbaikan' => $request->tanggal_perbaikan,
            'aksi_perbaikan' => $request->aksi_perbaikan,
            'status' => $request->status,
            'keyword' => $request->keyword,
            'pic' => $path,
        ]);
        return redirect()->route('record.menu', $device_id)->with('success', 'Data Berhasil Ditambahkan.');
    }

    public function delete($device_id, $id)
    {
        $record = Record::FindOrFail($id);
        $record->delete();
        if (!empty($record->pic)) {
            $filePath = public_path('template/assets/images/record/' . $record->pic);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        return redirect()->back()->with('success', 'Data Berhasil Dihapus.');
    }

    public function update(Request $request, $device_id, $id)
    {
        $request->validate([
            'date_issue' => 'required|date',
            'issue' => 'required|string',
            'tanggal_perbaikan' => 'nullable|date',
            'aksi_perbaikan' => 'nullable|string',
            'status' => 'required|in:done,pending',
            'keyword' => 'required|string',
            'pic' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $record = Record::FindOrFail($id);
        $record->date_issue = $request->date_issue;
        $record->issue = $request->issue;
        $record->tanggal_perbaikan = $request->tanggal_perbaikan;
        $record->aksi_perbaikan = $request->aksi_perbaikan;
        $record->status = $request->status;
        $record->keyword = $request->keyword;

        // Jika user upload foto baru
        if ($request->hasFile('pic')) {

            // Hapus foto lama
            if (!empty($record->pic)) {
                $oldPath = public_path('template/assets/images/record/' . $record->pic);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Simpan foto baru
            $file = $request->file('pic');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('template/assets/images/record'), $filename);

            $record->pic = $filename;
        }

        $record->save();

        return redirect()->back()->with('success', 'Data Berhasil Diperbarui.');
    }

    public function print($device_id, $id)
    {
        $record = Record::with('device')->FindOrFail($id);
        $roleTeknisi = Role::where('nama', 'Engineer')->first();
        $teknisi = User::where('role_id', $roleTeknisi->id)->get();
        return view('pages.record.print', compact('record', 'teknisi'));
    }
}
