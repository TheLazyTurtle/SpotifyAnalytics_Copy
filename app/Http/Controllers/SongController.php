<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;

class SongController extends Controller
{
    // Show all
    public function index()
    {
        $songs = Song::all();

        return response()->json([
            'success' => true,
            'data' => $songs
        ], 200);
    }

    // Show one
    public function show($song_id)
    {
        // TODO: validate data
        $song = Song::where('song_id', $song_id)->first();

        if (!$song) {
            return response()->json([
                'success' => false,
                'data' => 'Song not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $song
        ], 200);
    }

    // Create
    public function store(Request $request)
    {
        $this->validate($request, [
            'song_id' => 'required',
            'name' => 'required',
            'length' => 'required',
            'url' => 'required',
            'img_url' => 'required',
            'preview_url' => 'required',
            'album_id' => 'required',
            'track_number' => 'required',
            'explicit' => 'required'
        ]);

        $song = new Song();
        $song->song_id = $request->song_id;
        $song->name = $request->name;
        $song->length = $request->length;
        $song->url = $request->url;
        $song->img_url = $request->img_url;
        $song->preview_url = $request->preview_url;
        $song->album_id = $request->album_id;
        $song->track_number = $request->track_number;
        $song->explicit = $request->explicit == True ? 1 : 0;

        if ($song->save()) {
            return response()->json([
                'success' => true,
                'data' => $song->toArray()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to add song'
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $song_id)
    {
        $song = Song::where('song_id', $song_id)->first();

        if (!$song) {
            return response()->json([
                'success' => false,
                'data' => 'Song not found'
            ], 400);
        }

        // TODO: Validate input???
        $updated = $song->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to update song'
            ], 500);
        }
    }

    // Destroy
    public function destroy($song_id)
    {
        $song = Song::where('song_id', $song_id)->first();

        if (!$song) {
            return response()->json([
                'success' => false,
                'data' => 'Song not found'
            ], 400);
        }

        if ($song->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Song can not be deleted'
            ], 500);
        }
    }
}
