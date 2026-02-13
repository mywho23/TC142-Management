<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'tb_logbook';
    protected $fillable = [
        'logbook_no',
        'logbook_seq',
        'date',
        'company',
        'training_subject',
        'instructors',
        'trainees',
        'start_time',
        'finish_time',
        'time_lost',
        'total_time',
        'real_start_time',
        'real_finish_time',
        'real_total_second',
        'sign_instructor',
        'maintenance_release_time',
        'maintenance_release_sign',
        'maintenance_accept_time',
        'maintenance_accept_sign',
        'jadwal_id',
        'device_id',
        'diskrepansi_id',
        'diskrepansi_keterangan',
    ];

    public function diskrepansi()
    {
        return $this->hasMany(Diskrepansi::class, 'logbook_id');
    }


    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function timerlogs()
    {
        return $this->hasMany(LogbookTimerLog::class, 'logbook_id');
    }
}
