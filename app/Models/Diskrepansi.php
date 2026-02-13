<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diskrepansi extends Model
{
    protected $table = 'tb_diskrepansi';
    protected $fillable = [
        'logbook_id',
        'device_id',
        'teknisi_id',
        'tanggal_pengerjaan',
        'aksi_pengerjaan',
        'status'
    ];

    public function logbook()
    {
        return $this->hasOne(Logbook::class, 'dikrepansi_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
