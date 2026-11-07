<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory;

    protected $table = 'publikasis';

    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'deskripsi',
        'what',
        'who',
        'when_tanggal',
        'where_lokasi',
        'why',
        'how',
        'foto_kegiatan',
        'file_pendukung',
        'status',
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}