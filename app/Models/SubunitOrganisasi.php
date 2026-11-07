<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubunitOrganisasi extends Model
{
    protected $table = 'subunit_organisasi';

    protected $fillable = [
        'unit_id',
        'kode_subunit',
        'nama_subunit',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }

    public function karyawan(): HasMany
    {
        return $this->hasMany(
            Karyawan::class,
            'subunit_organisasi_id'
        );
    }
}