<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opini extends Model
{
    protected $table = 'opinis';

    protected $guarded = ['id'];

    /**
     * Relasi ke model User (pembuat opini).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}