<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\DataWrapperResource;
use App\Http\Resources\SongResource;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Played;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    // Show all
    public function index()
    {
        $artists = Artist::all();
        return ArtistResource::collection($artists);
    }

    // Show one
    public function show($artist_id)
    {
        Validator::validate([$artist_id], [0 => 'required|min:22|max:23']);
        $artist = Artist::where('artist_id', $artist_id)->first();

        if (!$artist) {
            return response()->json([
                'data' => 'Artist not found'
            ], 400);
        }

        return new ArtistResource($artist);
    }

    // Create
    public function store(Request $request)
    {
        $this->validate($request, [
            'artist_id' => 'requred|min:22|max:23',
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
            return new ArtistResource($artist);
        } else {
            return response()->json([
                'data' => 'Failed to add artist'
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $artist_id)
    {
        Validator::validate([$artist_id], [0 => 'required|min:22|max:23']);
        $this->validate($request, [
            'artist_id' => 'min:22|max:23',
        ]);

        $artist = Artist::where('artist_id', $artist_id)->first();

        if (!$artist) {
            return response()->json([
                'data' => 'Artist not found'
            ], 400);
        }

        $updated = $artist->fill($request->all())->save();

        if ($updated) {
            return response();
        } else {
            return response()->json([
                'data' => 'Failed to update artist'
            ], 500);
        }
    }

    // Destroy
    public function destroy($artist_id)
    {
        Validator::validate([$artist_id], [0 => 'required|min:22|max:23']);

        $artist = Artist::where('artist_id', $artist_id)->first();

        if (!$artist) {
            return response()->json([
                'data' => 'Artist not found'
            ], 400);
        }

        if ($artist->delete()) {
            return response();
        } else {
            return response()->json([
                'data' => 'Artist can not be delted'
            ], 500);
        }
    }

    // Get an artists albums
    public function albums(Request $request)
    {
        $this->validate($request, [
            'artist_id' => 'min:22|max:23',
        ]);

        $singles = Album::getArtistSingles($request->artist_id);
        $albums = Album::getArtistAlbumsTheyOwn($request->artist_id);

        return AlbumResource::collection(collect($singles, $albums));
    }

    // Get the top songs of an artist
    // NOTE: This is a scuffed AF query. We first do a huge query to get the total and than do another big query to get the users
    public function topSongs(Request $request)
    {
        $this->validate($request, [
            'artist_id' => 'min:22|max:23',
        ]);

        $user_id = null;
        if (auth()->check()) {
            $user_id = $request->user();
        }

        $songs = Played::join('songs', 'songs.song_id', 'played.song_id')
            ->join('artist_has_song', 'artist_has_song.song_id', 'songs.song_id')
            ->join('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->where('artists.artist_id', $request->artist_id)
            ->where('artist_has_song.artist_id', $request->artist_id)
            ->select(DB::raw('COUNT(*) as y'), 'songs.song_id')
            ->groupBy('played.song_id')
            ->orderBy('y', 'desc')
            ->limit(10)
            ->get();

        foreach ($songs as $song) {
            if ($user_id == null) {
                $song->x = 0;
            } else {
                $user_count = Played::where('played_by', $user_id->id)
                    ->where('song_id', $song->song_id)
                    ->select(DB::raw('COUNT(*) as x'))
                    ->first();
                $song->x = $user_count->x;
            }

            $songObject = Song::where('song_id', $song->song_id)->first();

            $song->object = new SongResource($songObject);
        }

        return DataWrapperResource::collection($songs);
    }
}
