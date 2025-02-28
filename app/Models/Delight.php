<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delight extends Model
{
    use HasFactory;

    protected $fillable = [
        'gourmet_id',
        'title',
        'content',
        'public',
    ];

    public function gourmet(): BelongsTo
    {
        return $this->belongsTo(Gourmet::class);
    }

    public function nibbles(): HasMany
    {
        return $this->hasMany(Nibble::class);
    }

    public function eats(): HasMany
    {
        return $this->hasMany(Eat::class);
    }

    public function isEatenBy(int $gourmet_id): bool
    {
        return $this->eats()->where('gourmet_id', $gourmet_id)->exists();
    }
}
