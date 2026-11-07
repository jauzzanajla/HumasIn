<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';

    protected $fillable = [
        'nama_aset',
        'foto',
        'deskripsi',
        'jumlah',
        'status',
        'kondisi',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];
}