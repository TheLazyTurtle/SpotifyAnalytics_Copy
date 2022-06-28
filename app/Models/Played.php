<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Played extends Model
{
    use HasFactory;
    protected $table = 'played';
    protected $fillable = [
        'song_id',
        'date_played',
        'played_by',
        'song_name'
    ];

    public static function allSongsPlayed($user_id, $min_date, $max_date, $min_played, $max_played)
    {
        return Played::where('played_by', $user_id)
            ->distinct()
            ->select(DB::raw('COUNT(*) as y'), 'played.song_name as x')
            ->whereBetween('date_played', [$min_date, $max_date])
            ->groupBy('played.song_id')
            ->havingBetween('y', [$min_played, $max_played])
            ->orderBy('played.song_name')
            ->get();
    }

    public static function topSongs($user_id, $min_date, $max_date, $artist_name, $amount)
    {
        return Played::where('played_by', $user_id)
            ->distinct()
            ->join('artist_has_song', 'artist_has_song.song_id', 'played.song_id')
            ->join('songs', 'artist_has_song.song_id', 'songs.song_id')
            ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->select(DB::raw('COUNT(*) as y'), 'played.song_name as x', 'songs.img_url')
            ->whereBetween('date_played', [$min_date, $max_date])
            ->where('artists.name', 'like', $artist_name)
            ->groupBy('played.song_id')
            ->orderBy('y', 'desc')
            ->limit($amount)
            ->get();
    }

    public static function topArtist($user_id, $min_date, $max_date, $amount)
    {
        return Played::where('played_by', $user_id)
            ->join('artist_has_song', 'artist_has_song.song_id', 'played.song_id')
            ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->select(DB::raw('COUNT(*) as y'), 'artists.name as x', 'artists.img_url')
            ->whereBetween('played.date_played', [$min_date, $max_date])
            ->groupBy('artists.artist_id')
            ->orderBy('y', 'desc')
            ->limit($amount)
            ->get();
    }

    public static function playedPerDay($user_id, $artist_name, $song_name, $min_date, $max_date)
    {
        return Played::where('played_by', $user_id)
            ->select(DB::raw('unix_timestamp(played.date_played) * 1000 as x'), DB::raw('COUNT(*) as y'))
            ->join('songs', 'played.song_id', 'songs.song_id')
            ->whereIn('songs.song_id', function ($query) use ($artist_name, $song_name) {
                $query->select('songs.song_id')
                    ->from('songs')
                    ->join('artist_has_song', 'artist_has_song.song_id', 'songs.song_id')
                    ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
                    ->where('artists.name', 'like', $artist_name)
                    ->where('songs.name', 'like', $song_name);
            })
            ->whereBetween('played.date_played', [$min_date, $max_date])
            ->groupBy(DB::raw('MONTH(played.date_played)'), DB::raw('YEAR(played.date_played)'))
            ->orderBy('x', 'desc')
            ->get();
    }

    public static function topArtistSearch($user_id, $artist_name, $amount)
    {
        return Played::where('played_by', 'like',  $user_id)
            ->join('artist_has_song', 'artist_has_song.song_id', 'played.song_id')
            ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->select('artists.name', 'artists.img_url as imgUrl', DB::raw('concat("artist") as type'))
            ->where('artists.name', 'like', $artist_name . '%')
            ->groupBy('artists.artist_id')
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->limit($amount)
            ->get();
    }

    public static function topSongsSearch($user_id, $song_name, $amount)
    {
        return Played::where('played_by', $user_id)
            ->select('played.song_name as name')
            ->join('artist_has_song', 'artist_has_song.song_id', 'played.song_id')
            ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->where('played.song_name', 'like', $song_name . '%')
            ->groupBy('played.song_id')
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->limit($amount)
            ->get();
    }
}
