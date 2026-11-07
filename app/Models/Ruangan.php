<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = [
        'nama_ruangan',
        'lokasi',
        'kapasitas',
        'kuota',
        'durasi_maksimal',
        'satuan_durasi',
        'status',
        'deskripsi',
        'foto',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'kuota' => 'integer',
        'durasi_maksimal' => 'integer',
    ];
}