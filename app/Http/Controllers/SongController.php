<?php

namespace App\Http\Controllers;

use App\Http\Resources\SongResource;
use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Validator;

class SongController extends Controller
{
    // Show all
    public function index()
    {
        $songs = Song::all();
        return SongResource::collection($songs);
    }

    // Show one
    public function show($song_id)
    {
        Validator::validate([$song_id], [0 =>  'required|min:22|max:23']);

        $song = Song::where('song_id', $song_id)->first();

        if (!$song) {
            return response()->json([
                'data' => 'Song not found'
            ], 400);
        }

        return new SongResource($song);
    }

    // Create
    public function store(Request $request)
    {
        $this->validate($request, [
            'song_id' => 'required|min:22|max:23',
            'name' => 'required',
            'length' => 'required|max:11',
            'url' => 'required',
            'img_url' => 'required',
            'preview_url' => 'required',
            'album_id' => 'required|min:22|max:23',
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
            return new SongResource($song);
        } else {
            return response()->json([
                'data' => 'Failed to add song'
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $song_id)
    {
        Validator::validate([$song_id], [0 =>  'required|min:22|max:23']);
        $this->validate($request, [
            'song_id' => 'required|min:22|max:23',
            'name' => 'required',
            'length' => 'required|max:11',
            'url' => 'required',
            'img_url' => 'required',
            'preview_url' => 'required',
            'album_id' => 'required|min:22|max:23',
            'track_number' => 'required',
            'explicit' => 'required'
        ]);

        $song = Song::where('song_id', $song_id)->first();

        if (!$song) {
            return response()->json([
                'data' => 'Song not found'
            ], 400);
        }

        $updated = $song->fill($request->all())->save();

        if ($updated) {
            return response();
        } else {
            return response()->json([
                'data' => 'Failed to update song'
            ], 500);
        }
    }

    // Destroy
    public function destroy($song_id)
    {
        Validator::validate([$song_id], [0 => 'required|min:22|max:23']);

        $song = Song::where('song_id', $song_id)->first();

        if (!$song) {
            return response()->json([
                'data' => 'Song not found'
            ], 400);
        }

        if ($song->delete()) {
            return response();
        } else {
            return response()->json([
                'data' => 'Song can not be deleted'
            ], 500);
        }
    }
}
