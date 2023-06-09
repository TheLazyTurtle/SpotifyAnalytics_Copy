<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'spotify_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'img_url',
        'is_admin',
        'is_active',
        'private',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public static function allActiveWithTokens()
    {
        return User::where('is_active', 1)
            ->join('spotify_tokens', 'spotify_tokens.user_id', 'users.id')
            ->select('users.id', 'users.username', 'spotify_tokens.refresh_token')
            ->get();
    }
}
