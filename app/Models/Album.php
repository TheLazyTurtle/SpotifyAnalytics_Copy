<?php

namespace App\Models;

use App\Http\Resources\ArtistResource;
use App\Http\Resources\SongResource;
use Illuminate\Database\Eloquent\Collection;
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
        $songs = Song::where('album_id', $album_id)->orderBy('track_number', 'asc')->get();
        return SongResource::collection($songs);
    }

    // Album has one primary artist
    public function artist($artist_id)
    {
        $artist = Artist::where('artist_id', $artist_id)->first();

        return new ArtistResource($artist);
    }

    public static function getArtistSingles($artist_id)
    {
        return Album::where('type', 'single')
            ->whereIn('album_id', function ($query) use ($artist_id) {
                $query->select('album_id')
                    ->from('songs')
                    ->whereIn('song_id', function ($query) use ($artist_id) {
                        $query->select('song_id')
                            ->from('artist_has_song')
                            ->where('artist_id', $artist_id);
                    });
            })
            ->get();
    }

    public static function getArtistAlbumsTheyOwn($artist_id)
    {
        return Album::where('primary_artist_id', $artist_id)
            ->where('type', 'album')
            ->get();
    }
}
