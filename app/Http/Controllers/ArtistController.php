<?php

namespace App\Http\Controllers;

use App\Models\Artist;
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
}
