<?php

namespace App\Models;

use App\Http\Resources\ArtistResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'explicit',
        'hash'
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

    public static function makeHash($song_id)
    {
        $sub = Artist::selectRaw("group_concat(name SEPARATOR ' ') as names, song_id")
            ->join("artist_has_song", "artists.artist_id", "artist_has_song.artist_id")
            ->where("artist_has_song.song_id", $song_id)
            ->groupBy("artist_has_song.song_id");

        $hash = Song::selectRaw("md5(group_concat(songs.name, c.names)) as hash")
            ->joinSub($sub, "c", function ($join) {
                $join->on("c.song_id", "songs.song_id");
            })
            ->groupBy("songs.song_id")
            ->first();

        return $hash;
    }

    public static function fixHashes($song_ids)
    {
        for ($i = 0; $i < count($song_ids); $i++) {
            Song::where('song_id', $song_ids[$i])
                ->update(['hash' => Song::makeHash($song_ids[$i])->hash]);
        }
    }
}
