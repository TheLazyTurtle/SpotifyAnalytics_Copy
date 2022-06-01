<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\ArtistHasSong;
use App\Models\Played;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    // Show all
    public function index()
    {
        $artists = Artist::all();

        return response()->json([
            'success' => true,
            'data' => $artists
        ], 200);
    }

    // Show one
    public function show($artist_id)
    {
        // TODO: validate data
        $artist = Artist::where('artist_id', $artist_id)->first();

        if (!$artist) {
            return response()->json([
                'success' => false,
                'data' => 'Artist not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $artist
        ], 200);
    }

    // Create
    public function store(Request $request)
    {
        $this->validate($request, [
            'artist_id' => 'requred',
            'name' => 'required',
            'url' => 'required',
            'img_url' => 'required',
        ]);

        $artist = new Artist();
        $artist->artist_id = $request->artist_id;
        $artist->name = $request->name;
        $artist->url = $request->url;
        $artist->img_url = $request->img_url;

        if ($artist->save()) {
            return response()->json([
                'success' => true,
                'data' => $artist->toArray()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to add artist'
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $artist_id)
    {
        $artist = Artist::where('artist_id', $artist_id)->first();

        if (!$artist) {
            return response()->json([
                'success' => false,
                'data' => 'Artist not found'
            ], 400);
        }

        // TODO: Validate input??
        $updated = $artist->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to update artist'
            ], 500);
        }
    }

    // Destroy
    public function destroy($artist_id)
    {
        $artist = Artist::where('artist_id', $artist_id)->first();

        if (!$artist) {
            return response()->json([
                'success' => false,
                'data' => 'Artist not found'
            ], 400);
        }

        if ($artist->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Artist can not be delted'
            ], 500);
        }
    }

    // Get an artists albums
    public function albums(Request $request)
    {
        // TODO: Valiadte input
        $albums = Album::where('primary_artist_id', $request->artist_id)->get();
        $res = array();

        // Also gonna need album artist (primary album artist)
        foreach ($albums as $album) {
            $album_songs = Song::where('album_id', $album->album_id)->get();
            $album_artist = Artist::where('artist_id', $album->primary_artist_id)->first();

            foreach ($album_songs as $song) {
                $song_artists = ArtistHasSong::where('song_id', $song->song_id)
                    ->join('artists', 'artists.artist_id', 'artist_has_song.artist_id')
                    ->get('artists.*');
                $song->artists = $song_artists;
            }

            $album->album_artist = $album_artist;
            $album->songs = $album_songs;
            array_push($res, $album);
        }

        if (!$albums) {
            return response()->json([
                'success' => false,
                'data' => 'Albums not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            // 'data' => $albums
            'data' => $res
        ], 200);
    }

    // Get the top songs of an artist
    public function topSongs(Request $request)
    {
        $user_id = Auth()->user()->id;

        $songs = Played::join('songs', 'songs.song_id', 'played.song_id')
            ->join('artist_has_song', 'artist_has_song.song_id', 'songs.song_id')
            ->join('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->where('artists.artist_id', $request->artist_id)
            ->where('artist_has_song.artist_id', $request->artist_id)
            ->select(DB::raw('COUNT(*) as count'), 'songs.*')
            ->groupBy('played.song_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        foreach ($songs as $song) {
            $user_count = Played::where('played_by', $user_id)
                ->where('song_id', $song->song_id)
                ->select(DB::raw('COUNT(*) as user_count'))
                ->first();

            $song->user_count = $user_count->user_count;
        }

        return response()->json([
            'success' => true,
            'data' => $songs
        ], 200);
    }
}
