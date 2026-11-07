<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function unit()
{
    return $this->belongsTo(\App\Models\Unit::class);
}

public function subunitOrganisasi()
{
    return $this->belongsTo(\App\Models\SubunitOrganisasi::class);
}

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}