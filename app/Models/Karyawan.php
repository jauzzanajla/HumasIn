<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'unit_id',
        'subunit_organisasi_id',
        'nip',
        'nama',
        'email',
        'jabatan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function subunitOrganisasi(): BelongsTo
    {
        return $this->belongsTo(SubunitOrganisasi::class, 'subunit_organisasi_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'karyawan_id');
    }
}