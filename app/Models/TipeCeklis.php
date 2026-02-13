<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTipeCeklis
 */
class TipeCeklis extends Model
{
    protected $table = 'tb_tipe_ceklis';

    protected $fillable = [
        'nama'
    ];

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(CeklisItem::class, 'tipe_ceklis_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
