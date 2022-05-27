<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'name',
        'url',
        'img_url',
    ];

    // Artist has many songs
    public function songs()
    {
        return $this->belongsToMany(Song::class, 'artist_has_song', 'artist_id', 'song_id', 'artist_id', 'song_id');
    }

    // Artist has many albums
    public function albums()
    {
        return $this->hasMany(Album::class, 'primary_artist_id', 'artist_id');
    }
}
