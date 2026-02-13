<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Device;

use function PHPUnit\Framework\returnValue;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $inventory = Inventory::with('device')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    // Cari di tabel inventory sendiri
                    $q->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('sn_barang', 'like', "%{$search}%")
                        ->orWhere('pn_barang', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        // Cari di tabel relasi (tb_device)
                        ->orWhereHas('device', function ($queryDevice) use ($search) {
                            $queryDevice->where('device_name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10);
        return view('pages.inventory.index', compact('inventory'));
    }

    public function create()
    {
        $devices = Device::all();
        return view('pages.inventory.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'sn_barang'   => 'required|string|unique:tb_inventory,sn_barang',
            'pn_barang'   => 'required|string|unique:tb_inventory,pn_barang',
            'device_id'   => 'required|exists:tb_device,id',
            'stok'        => 'required|integer', // Ganti 'int' jadi 'integer'
            'satuan'      => 'required|string|max:20',
            'lokasi'      => 'required|string|max:100',
            'status'      => 'required|string|in:new,baik,rusak,service,diluar',
            'keterangan'  => 'nullable|string|max:255',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Tambahin max size biar server nggak meledak
        ]);

        // Upload gambar
        $filename = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('template/assets/images/inventory'), $filename);
        }

        \App\Models\Inventory::create([
            'nama_barang' => $request->nama_barang,
            'sn_barang'   => $request->sn_barang,
            'pn_barang'   => $request->pn_barang,
            'device_id'   => $request->device_id,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan,
            'lokasi'      => $request->lokasi,
            'status'      => $request->status,
            'keterangan'  => $request->keterangan,
            'gambar'      => $filename, // Pakai variabel $filename aja biar simpel
        ]);

        return redirect()->route('inventory.index')->with('success', 'Data Berhasil Ditambahkan.');
    }

    public function delete($id)
    {
        $inventory = Inventory::FindOrFail($id);
        //hapus gambar
        if (!empty($inventory->gambar)) {
            $filePath = public_path('template/assets/images/inventory/' . $inventory->gambar);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        //hapus data di DB
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Data Berhasil Dihapus.');
    }

    public function edit($id)
    {
        $devices = Device::all();
        $inventory = Inventory::FindOrFail($id);

        return view('pages.inventory.edit', compact('devices', 'inventory'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'sn_barang'   => 'required|string|unique:tb_inventory,sn_barang,' . $id,
            'pn_barang'   => 'required|string|unique:tb_inventory,pn_barang,' . $id,
            'device_id'   => 'required|exists:tb_device,id',
            'stok'        => 'required|integer', // Ganti 'int' jadi 'integer'
            'satuan'      => 'required|string|max:20',
            'lokasi'      => 'required|string|max:100',
            'status'      => 'required|string|in:new,baik,rusak,service,diluar',
            'keterangan'  => 'nullable|string|max:255',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Tambahin max size biar server nggak meledak
        ]);

        $inventory->nama_barang = $request->nama_barang;
        $inventory->sn_barang = $request->sn_barang;
        $inventory->sn_barang = $request->sn_barang;
        $inventory->device_id = $request->device_id;
        $inventory->stok = $request->stok;
        $inventory->satuan = $request->satuan;
        $inventory->status = $request->status;
        $inventory->keterangan = $request->keterangan;

        if ($request->hasFile('gambar')) {
            if (!empty($inventory->gambar)) {
                $oldFile = public_path('template/assets/images/inventory/' . $inventory->gambar);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $file = $request->file('gambar');
            $filename = time() . '-' . $file->getClientOriginalPath();
            $file->move(public_path('template/assets/images/inventory/'), $filename);
            $inventory->gambar = $filename;
        }

        $inventory->save();

        return redirect()->route('inventory.index')->with('success', 'Data Berhasil Diubah.');
    }
}
