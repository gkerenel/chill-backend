<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $username)
 */
class Gourmet extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'photo',
    ];

    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    public function delights(): HasMany
    {
        return $this->hasMany(Delight::class);
    }

    public function nibbles(): HasMany
    {
        return $this->hasMany(Nibble::class);
    }

    public function eats(): HasMany
    {
        return $this->hasMany(Eat::class);
    }

    public function tasting(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'tastes', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function tasters(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'tastes', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function isTasting(Gourmet $gourmet): bool
    {
        return $this->tasting()->where('taster_id', $gourmet->id)->exists();
    }
}
