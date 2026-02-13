<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogbookTimerLog extends Model
{
    protected $table = 'tb_logbook_timer_logs';
    protected $fillable = [
        'logbook_id',
        'jadwal_id',
        'start_time',
        'end_time',
        'duration_second',
        'status'
    ];

    public $timestamps = true;

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
