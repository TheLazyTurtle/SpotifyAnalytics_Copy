<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'name',
        'release_date',
        'primary_artist_id',
        'url',
        'img_url',
        'type'
    ];

    // Album has many songs
    public function songs()
    {
        return $this->hasMany(Song::class, 'album_id', 'album_id');
    }

    // Album has one primary artist
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'primary_artist_id', 'artist_id');
    }
}
