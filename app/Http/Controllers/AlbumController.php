<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    // Show all
    public function index()
    {
        $albums = Album::all();
        return AlbumResource::collection($albums);
    }

    // Show one
    public function show($album_id)
    {
        Validator::validate([$album_id], [0 => 'required|min:22|max:23']);

        $album = Album::where('album_id', $album_id)->first();

        if (!$album) {
            return response()->json([
                'data' => 'Album not found'
            ], 400);
        }
        return new AlbumResource($album);
    }

    // Create
    public function store(Request $request)
    {
        $this->validate($request, [
            'album_id' => 'required|min:22|max:23',
            'name' => 'required',
            'release_date' => 'required',
            'primary_artist_id' => 'required|min:22|max:23',
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
            return new AlbumResource($album);
        } else {
            return response()->json([
                'data' => 'Failed to add album'
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $album_id)
    {
        Validator::validate([$album_id], [0 => 'required|min:22|max:23']);
        $this->validate($request, [
            'album_id' => 'min:22|max:23',
            'primary_artist_id' => 'min:22|max:23',
            'type' => 'max:10'
        ]);

        $album = Album::where('album_id', $album_id)->first();

        if (!$album) {
            return response()->json([
                'data' => 'Album not found'
            ], 400);
        }

        $updated = $album->fill($request->all())->save();

        if ($updated) {
            return response();
        } else {
            return response()->json([
                'data' => 'Failed to update album'
            ], 500);
        }
    }

    // Destroy
    public function destroy($album_id)
    {
        Validator::validate([$album_id], [0 => 'required|min:22|max:23']);

        $album = Album::where('album_id', $album_id)->first();

        if (!$album) {
            return response()->json([
                'data' => 'Album not found'
            ], 400);
        }

        if ($album->delete()) {
            return response();
        } else {
            return response()->json([
                'data' => 'Album can not be deleted'
            ], 500);
        }
    }
}
