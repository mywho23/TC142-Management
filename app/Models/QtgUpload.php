<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperQtgUpload
 */
class QtgUpload extends Model
{
    protected $table = 'tb_qtg_upload';
    protected $fillable = [
        'device_id',
        'chapter_id',
        'year',
        'filepath',
        'result',
        'note',
        'uploaded_by',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo(QtgChapter::class, 'chapter_id', 'id');
    }
}
