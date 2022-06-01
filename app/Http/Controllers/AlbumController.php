<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;

class AlbumController extends Controller
{
    // Show all
    public function index()
    {
        $albums = Album::all();

        return response()->json([
            'success' => true,
            'data' => $albums
        ], 200);
    }

    // Show one
    public function show($album_id)
    {
        // TODO: Validate data
        $album = Album::where('album_id', $album_id)->first();

        if (!$album) {
            return response()->json([
                'success' => false,
                'data' => 'Album not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $album
        ], 200);
    }

    // Create
    public function store(Request $request)
    {
        $this->validate($request, [
            'album_id' => 'required|max:23',
            'name' => 'required',
            'release_date' => 'required',
            'primary_artist_id' => 'required|max:23',
            'url' => 'required',
            'img_url' => 'required',
            'type' => 'required|max:10'
        ]);

        $album = new Album();
        $album->album_id = $request->album_id;
        $album->name = $request->name;
        $album->release_date = $request->release_date;
        $album->primary_artist_id = $request->primary_artist_id;
        $album->url = $request->url;
        $album->img_url = $request->img_url;
        $album->type = $request->type;

        if ($album->save()) {
            return response()->json([
                'success' => true,
                'data' => $album->toArray()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to add album'
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $album_id)
    {
        $album = Album::where('album_id', $album_id)->first();

        if (!$album) {
            return response()->json([
                'success' => false,
                'data' => 'Album not found'
            ], 400);
        }

        // TODO: Validate input??
        $updated = $album->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to update album'
            ], 500);
        }
    }

    // Destroy
    public function destroy($album_id)
    {
        $album = Album::where('album_id', $album_id)->first();

        if (!$album) {
            return response()->json([
                'success' => false,
                'data' => 'Album not found'
            ], 400);
        }

        if ($album->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Album can not be deleted'
            ], 500);
        }
    }
}
