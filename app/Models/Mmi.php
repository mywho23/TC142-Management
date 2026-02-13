<?php

namespace App\Models;

use illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMmi
 */
class Mmi extends Model
{
    protected $table = 'tb_mmi';

    protected $fillable = [
        'subjek',
        'tanggal_temuan',
        'reporter',
        'aksi_perbaikan',
        'tanggal_perbaikan',
        'device_id',
        'executor_id',
        'status',
    ];

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
