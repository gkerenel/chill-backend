<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eat extends Model
{
    use HasFactory;

    protected $fillable = [
        'delight_id',
        'gourmet_id',
    ];
}
