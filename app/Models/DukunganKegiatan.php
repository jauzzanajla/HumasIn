<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukunganKegiatan extends Model
{
    use HasFactory;

    protected $table = 'dukungan_kegiatans';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    protected $casts = [
    'admin_ids' => 'array',
];

// Helper untuk mengambil daftar user admin berdasarkan array id
public function getPetugasAttribute()
{
    if (empty($this->admin_ids)) {
        return collect();
    }
    return \App\Models\User::whereIn('id', $this->admin_ids)->get();
}
}