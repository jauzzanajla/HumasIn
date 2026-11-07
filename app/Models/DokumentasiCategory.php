<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DokumentasiCategory extends Model
{
    protected $fillable = ['nama_kategori', 'slug', 'deskripsi'];

    public function items(): HasMany
    {
        return $this->hasMany(DokumentasiItem::class, 'category_id');
    }
}