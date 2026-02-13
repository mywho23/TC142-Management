<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperNote
 */
class Note extends Model
{
    protected $table = 'tb_note';

    protected $fillable = [
        'judul',
        'device',
        'tipe',
        'status',
        'date',
        'tanggal',
    ];
}
