<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $table = 'unit';

    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'aktif',
    ];

    public function karyawan(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'unit_id');
    }
}