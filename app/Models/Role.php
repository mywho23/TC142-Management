<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRole
 */
class Role extends Model
{
    protected $table = 'tb_role';

    protected $fillable = [
        'nama',
    ];
}
