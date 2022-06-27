<?php

namespace App\Http\Controllers;

use App\Models\SpotifyToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SpotifyWebAPI;

class SpotifyTokenController extends Controller
{
    public function check(Request $request)
    {
        $user = $request->user();
        Validator::validate([$user->id], [0 => 'required|max:10']);

        $tokens = SpotifyToken::where('user_id', $user->id)->first();

        if (!$tokens) {
            return response()->json([
                'data' => 'You do not have spotify tokens yet'
            ], 400);
        } else {
            return response()->json([
                'data' => 'You do have spotify tokens'
            ], 200);
        }
    }

    public function create(Request $request)
    {
        $user = $request->user();
        Validator::validate([$user->id], [0 => 'required|max:10']);

        $session = new SpotifyWebAPI\Session(
            env('SPOTIFY_CLIENT_ID'),
            env('SPOTIFY_CLIENT_SECRET'),
            env('SPOTIFY_CALLBACK_URL')
        );

        $session->requestAccessToken($request->code);

        $tokens = new SpotifyToken();
        $tokens->user_id = $user->id;
        $tokens->auth_token = $session->getAccessToken();
        $tokens->refresh_token = $session->getRefreshToken();
        $tokens->expire_date = $session->getTokenExpiration();

        if ($tokens->save()) {
            $api = new SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($session->getAccessToken());

            $userToUpdate = User::where('id', $user->id)->first();
            $userToUpdate->fill(['spotify_id' => $api->me()->id])->save();

            return response()->json([
                'data' => 'success'
            ], 200);
        } else {
            return response()->json([
                'data' => 'failed'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $user = $request->user();
        Validator::validate([$user->id], [0 => 'required|max:10']);

        $tokens = SpotifyToken::where('user_id', $user->id)->first();

        if (!$tokens) {
            return response()->json([
                'data' => 'No tokens found'
            ], 400);
        }

        $updated = $tokens->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'data' => 'Successfully updated tokens'
            ]);
        } else {
            return response()->json([
                'data' => 'Failed to updated tokens'
            ], 500);
        }
    }
}
