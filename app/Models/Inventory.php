<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'tb_inventory';
    protected $fillable = [
        'nama_barang',
        'sn_barang',
        'pn_barang',
        'device_id',
        'stok',
        'satuan',
        'lokasi',
        'status',
        'keterangan',
        'gambar'
    ];

    protected $guarded = [];
    public function device()
    {
        // Parameter: (NamaModelTujuan, foreign_key_di_tabel_ini, owner_key_di_tabel_tujuan)
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }
}
