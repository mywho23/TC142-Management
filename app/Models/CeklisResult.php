<?php

namespace App\Models;

use Illuminate\Support\Facades\DB; // <--- ini bre!

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCeklisResult
 */
class CeklisResult extends Model
{
    protected $table = 'tb_ceklis_result';

    protected $fillable = [
        'ceklis_item_id',
        'device_id',
        'result',
        'notes',
        'tanggal_cek',
        'nama_teknisi',
        'created_at',
        'updated_at'
    ];

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(CeklisItem::class, 'ceklis_item_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
