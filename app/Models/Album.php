<?php

namespace App\Models;

use App\Http\Resources\ArtistResource;
use App\Http\Resources\SongResource;
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
    public function songs($album_id)
    {
        $songs = Song::where('album_id', $album_id)->get();
        return SongResource::collection($songs);
    }

    // Album has one primary artist
    public function artist($artist_id)
    {
        $artist = Artist::where('artist_id', $artist_id)->first();

        return new ArtistResource($artist);
    }
}
