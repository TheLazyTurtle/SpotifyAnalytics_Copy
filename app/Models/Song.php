<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'name',
        'length',
        'url',
        'img_url',
        'preview_url',
        'album_id',
        'track_number',
        'explicit'
    ];

    // Song has many played
    public function played($user_id)
    {
        return $this->hasMany(Played::class, 'song_id', 'song_id')->where('user_id', $user_id);
    }

    // Song has many artists
    public function artists()
    {
        return $this->hasManyThrough(Artist::class, ArtistHasSong::class, 'artist_id', 'artist_id');
    }

    // Song has one album
    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id', 'album_id');
    }
}
