<?php

namespace App\Models;

use App\Http\Resources\ArtistResource;
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
    static function artists($song_id)
    {
        $artists = Song::where('songs.song_id', $song_id)
            ->join('artist_has_song', 'artist_has_song.song_id', 'songs.song_id')
            ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->select('artists.*')
            ->get();

        return ArtistResource::collection($artists);
    }

    // Song has one album
    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id', 'album_id');
    }
}
