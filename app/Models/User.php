<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'karyawan_id',
    'id_peran',
    'name',
    'email',
    'password',
    'foto_profil',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function peran(): BelongsTo
    {
        return $this->belongsTo(Peran::class, 'id_peran');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'user_id', 'id')->latest();
    }

    public function notifikasiBelumDibaca()
    {
        return $this->hasMany(Notifikasi::class, 'user_id', 'id')->whereNull('dibaca_pada');
    }
}