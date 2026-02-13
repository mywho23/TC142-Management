<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperJadwal
 */
class Jadwal extends Model
{
    protected $table = 'tb_jadwal';

    protected $fillable = [
        'device_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'customer',
        'status',
        'keterangan',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function logbook()
    {
        return $this->hasOne(Logbook::class, 'jadwal_id');
    }
}
