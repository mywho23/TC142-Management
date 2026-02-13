<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperQtgChapter
 */
class QtgChapter extends Model
{
    protected $table = 'tb_qtg_chapter';
    protected $fillable = [
        'device_id',
        'chapter_code',
        'chapter_name',
        'chapter_group',
        'order_number',
        'active',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function uploads()
    {
        return $this->hasMany(QtgUpload::class, 'chapter_id', 'id');
    }
}
