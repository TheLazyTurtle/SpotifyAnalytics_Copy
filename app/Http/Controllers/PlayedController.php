<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataWrapperResource;
use App\Http\Resources\PlayedResource;
use App\Http\Resources\SliderItemDataResource;
use App\Models\Artist;
use App\Models\Played;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayedController extends Controller
{
    // Show all for a user
    // TODO: This might not need a user_id. This just needs the user to be be authorized and then we get the user_id from that
    public function index(Request $request, $user_id)
    {
        $this->validate($request, ['user_id' => 'required']);

        $played = Played::where('played_by', $user_id)->get();

        if (!$played) {
            return response()->json([
                'data' => 'No songs found'
            ], 400);
        }

        return PlayedResource::collection($played);
    }

    // Add songs as played
    public function store(Request $request)
    {
        $this->validate($request, [
            'song_id' => 'required',
            'date_played' => 'required',
            'played_by' => 'required',
            'song_name' => 'required'
        ]);

        $played = new Played();
        $played->song_id = $request->song_id;
        $played->date_played = $request->date_played;
        $played->played_by = $request->played_by;
        $played->song_name = $request->song_name;

        if ($played->save()) {
            return new PlayedResource($played);
        } else {
            return response()->json([
                'data' => 'Failed to add song as played'
            ], 500);
        }
    }

    // All songs played of user
    public function allSongsPlayed(Request $request)
    {
        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        if ($request->user_id) {
            $user_id = $request->user_id;
        } else {
            $user_id = $request->user()->id;
        }

        $played = Played::allSongsPlayed(
            $user_id,
            $request->min_date,
            $request->max_date,
            $request->min_played,
            $request->max_played
        );

        return DataWrapperResource::collection($played);
    }

    // Top song of user
    public function topSongs(Request $request)
    {
        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        if ($request->user_id) {
            $user_id = $request->user_id;
        } else {
            $user_id = $request->user()->id;
        }

        $top_songs = Played::topSongs(
            $user_id,
            $request->min_date,
            $request->max_date,
            $request->artist_name,
            $request->amount
        );

        return DataWrapperResource::collection($top_songs);
    }

    // Top artist of user
    public function topArtists(Request $request)
    {
        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        if ($request->user_id) {
            $user_id = $request->user_id;
        } else {
            $user_id = $request->user()->id;
        }

        $top_artists = Played::topArtist(
            $user_id,
            $request->min_date,
            $request->max_date,
            $request->amount
        );

        return DataWrapperResource::collection($top_artists);
    }

    // Played per day for a user
    // TODO: resource
    public function playedPerDay(Request $request)
    {
        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        if ($request->user_id) {
            $user_id = $request->user_id;
        } else {
            $user_id = $request->user()->id;
        }

        $played_per_day = Played::playedPerDay(
            $user_id,
            $request->artist_name,
            $request->song_name,
            $request->min_date,
            $request->max_date
        );

        return DataWrapperResource::collection($played_per_day);
    }

    // Top artist search for a user
    // TODO: Resource
    public function topArtistSearch(Request $request)
    {
        if ($request->user_id) {
            $user_id = $request->user_id;
        } else {
            $user_id = $request->user()->id;
        }

        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        $topArtists = Played::where('played_by', $user_id)
            ->join('artist_has_song', 'artist_has_song.song_id', 'played.song_id')
            ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->select('artists.name')
            ->where('artists.name', 'like', $request->artist_name . '%')
            ->groupBy('artists.artist_id')
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->limit($request->amount)
            ->get();

        return response()->json([
            'data' => $topArtists
        ], 200);
    }


    // Top songs search for a user
    // TODO: Resource
    public function topSongsSearch(Request $request)
    {
        if ($request->user_id) {
            $user_id = $request->user_id;
        } else {
            $user_id = $request->user()->id;
        }

        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        $topSongs = Played::where('played_by', $user_id)
            ->select('played.song_name as name')
            ->join('artist_has_song', 'artist_has_song.song_id', 'played.song_id')
            ->rightJoin('artists', 'artists.artist_id', 'artist_has_song.artist_id')
            ->where('played.song_name', 'like', $request->song_name . '%')
            ->groupBy('played.song_id')
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->limit($request->amount)
            ->get();

        return response()->json([
            'data' => $topSongs
        ], 200);
    }

    // Get total time listend for a user
    // TODO: Resource
    public function timeListened(Request $request)
    {
        $user_id = $request->user()->id;

        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        $timeListend = Played::where('played_by', $user_id)
            ->join('songs', 'songs.song_id', 'played.song_id')
            ->select(DB::raw('SUM(songs.length) as y'))
            ->whereBetween('played.date_played', [$request->min_date, $request->max_date])
            ->first();

        return new SliderItemDataResource($timeListend);
    }

    // Amount of songs a user listend to 
    // TODO: Resource
    public function amountSongs(Request $request)
    {
        $user_id = $request->user()->id;

        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        $amountSongs = Played::where('played.played_by', $user_id)
            ->select(DB::raw('COUNT(*) as y'))
            ->whereBetween('played.date_played', [$request->min_date, $request->max_date])
            ->first();

        return new SliderItemDataResource($amountSongs);
    }

    // Amount of new songs a user listend to 
    // TODO: Resource
    public function amountNewSongs(Request $request)
    {
        $user_id = $request->user()->id;

        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        $newSongs = DB::table(function ($query) use ($request, $user_id) {
            $query->select('played.song_id')
                ->from('played')
                ->where('played.played_by', $user_id)
                ->groupBy('played.song_id')
                ->having(DB::raw('MIN(played.date_played)'), '>=', $request->min_date);
        }, 'a')
            ->join('songs', 'songs.song_id', 'a.song_id')
            ->select(DB::raw('COUNT(*) as y'), 'songs.img_url')
            ->first();

        return new SliderItemDataResource($newSongs);
    }

    // Search artists and users
    // TODO: Resource
    public function search(Request $request)
    {
        // $user = $request->user();
        // TODO: input validation and default values;
        // TODO: Authentication
        // TODO: Make the user_id go using the validation step

        $searchArtist = Artist::where('name', 'like', "%$request->name%")
            ->select('artist_id', 'name', 'img_url as imgUrl', DB::raw('concat("artist") as type'))
            ->limit(10)
            ->get()
            ->toArray();
        $searchUser = User::where('username', 'like', "%$request->name%")
            ->select('username as name', 'img_url as imgUrl', DB::raw('concat("user") as type'))
            ->limit(10)
            ->get()
            ->toArray();

        return response()->json([
            'data' => array_merge($searchUser, $searchArtist)
        ], 200);
    }
}
