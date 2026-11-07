<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peran extends Model
{
    protected $table = 'peran';

    protected $fillable = [
        'kode_peran',
        'nama_peran',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_peran');
    }
}