<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mengirim notifikasi ke semua user yang berstatus ADMIN atau SUPER_ADMIN.
     */
    public static function kirimKeAdmin($judul, $pesan, $url = null, $jenis = 'info')
    {
        $adminUsers = User::whereHas('peran', function ($q) {
            $q->whereIn('kode_peran', ['ADMIN', 'SUPER_ADMIN']);
        })->get();

        foreach ($adminUsers as $admin) {
            self::create([
                'user_id' => $admin->id,
                'judul'   => $judul,
                'pesan'   => $pesan,
                'url'     => $url,
                'jenis'   => $jenis,
            ]);
        }
    }

    /**
     * Mengirim notifikasi personal ke user tertentu (berdasarkan user_id).
     */
    public static function kirim($userId, $judul, $pesan, $url = null, $jenis = 'info')
    {
        self::create([
            'user_id' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'url'     => $url,
            'jenis'   => $jenis,
        ]);
    }
}