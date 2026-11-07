<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumentasiItem extends Model
{
    protected $fillable = [
        'category_id',
        'judul',
        'link_url',
        'keterangan'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DokumentasiCategory::class, 'category_id');
    }
}