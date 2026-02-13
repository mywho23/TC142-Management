<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDevice
 */
class Device extends Model
{
    protected $table = 'tb_device';

    protected $fillable = [
        'device_name',
        'device_code',
        'deskripsi',
        'status',
    ];

    protected $guarded = [];

    // reverse relation jika perlu
    public function ceklisItems()
    {
        return $this->hasMany(CeklisItem::class, 'device_grup', 'device_code');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'device_id', 'id');
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'device_id', 'id');
    }

    public function getGroupAttribute()
    {
        return explode('_', $this->device_code)[0] ?? null;
    }

    public function getFormattedCodeAttribute()
    {
        return strtoupper(str_replace('_', ' ', $this->device_code));
    }
}
