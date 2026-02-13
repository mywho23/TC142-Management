<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCeklisItem
 */
class CeklisItem extends Model
{
    protected $table = 'tb_ceklis_item';

    protected $fillable = [
        'tipe_ceklis_id',
        'device_grup',
        'maintenance_manual',
        'table_ref',
        'subjek',
        'action'
    ];

    protected $guarded = [];

    public function result()
    {
        return $this->hasMany(CeklisResult::class, 'ceklis_item_id', 'id');
    }

    public function tipe()
    {
        return $this->belongsTo(TipeCeklis::class, 'tipe_ceklis_id', 'id');
    }

    // device_grup -> device.device_code
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_grup', 'device_code');
    }
}
