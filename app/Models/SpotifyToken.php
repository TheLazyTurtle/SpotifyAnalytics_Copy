<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'auth_token',
        'refresh_token',
        'expire_date'
    ];
}
