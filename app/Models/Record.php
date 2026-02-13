<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRecord
 */
class Record extends Model
{
    protected $table = 'tb_record';

    protected $fillable = [
        'device_id',
        'date_issue',
        'issue',
        'tanggal_perbaikan',
        'aksi_perbaikan',
        'status',
        'keyword',
        'pic',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
