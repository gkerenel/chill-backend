<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nibble extends Model
{
    use HasFactory;

    protected $fillable = [
        'delight_id',
        'gourmet_id',
        'content',
    ];

    public function delight(): BelongsTo
    {
        return $this->belongsTo(Delight::class);
    }

    public function gourmet(): BelongsTo
    {
        return $this->belongsTo(Gourmet::class);
    }
}
