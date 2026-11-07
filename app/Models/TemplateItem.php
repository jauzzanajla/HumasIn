<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateItem extends Model
{
    protected $fillable = [
        'category_id',
        'judul',
        'file_path',
        'tipe_file'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TemplateCategory::class, 'category_id');
    }
}