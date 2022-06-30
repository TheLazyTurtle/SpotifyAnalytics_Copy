<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Followers;
use App\Models\Notification;
use App\Models\Played;
use App\Models\SpotifyToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Get the current logged in user
    public function getCurrentUser()
    {
        $authUser = Auth()->user();
        $user = User::where('id', $authUser->id)->first();

        $user->following_count = Followers::where('follower_user_id', $authUser->id)
            ->select(DB::raw('COUNT(*) as count'))
            ->first()->count;
        $user->followers_count = Followers::where('following_user_id', $authUser->id)
            ->select(DB::raw('COUNT(*) as count'))
            ->first()->count;
        $user->following = false;
        $user->is_own_account = true;

        if (!$user) {
            return response()->json([
                'data' => 'Please log in'
            ], 400);
        }

        return new UserResource($user);
    }

    // Get a user by it's username
    public function show(Request $request, $username)
    {
        Validator::validate([$username], [0 => 'required|max:20|alpha_dash']);

        $authUser = null;

        if (auth('web')->check()) {
            $authUser = $request->user();
        }

        $user = User::where('username', $username)
            ->select('id', 'username', 'img_url', 'private')
            ->first();

        if (!$user) {
            return response()->json([
                'data' => 'User not found'
            ], 400);
        }

        if ($authUser == null) {
            $user->has_following_request = false;
            $user->following = false;
        } else {
            $user->has_following_request = Notification::hasFollowingRequestOpen($authUser->id, $user->id);
            $user->following = Followers::isFollowing($authUser->id, $user->id);
        }

        $user->is_own_account = false;
        $user->following_count = Followers::followingCount($user->id);
        $user->followers_count = Followers::followerCount($user->id);

        return new UserResource($user);
    }

    // Make new user
    public function store(Request $request)
    {
        $this->validate($request, [
            'spotify_id' => 'required|max:100',
            'first_name' => 'required|alpha|max:20',
            'last_name' => 'required|alpha|max:50',
            'username' => 'required|alpha_dash|max:20',
            'email' => 'required|email',
            'password' => 'required',
            'repeatPassword' => 'required|same:password'
        ]);

        $user = new user();
        $user->spotify_id = $request->spotify_id;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'data' => $user->toArray()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to add user'
            ], 500);
        }
    }

    // Update
    public function update(Request $request)
    {
        $this->validate($request, [
            'spotify_id' => 'max:100',
            'first_name' => 'max:20|alpha',
            'last_name' => 'max:50|alpha',
            'username' => 'max:20|alpha_dash',
            'email' => 'email',
        ]);

        $authUser = Auth()->user();
        $user = User::where('id', $authUser->id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'Please login'
            ], 400);
        }

        $updated = $user->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'data' => 'Failed to update user'
            ], 500);
        }
    }

    // Delete user
    public function destroy()
    {
        $authUser = Auth()->user();
        $user = User::where('id', $authUser->id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'Please login'
            ], 400);
        }

        if ($user->delete()) {
            Played::where('played_by', $authUser->id)->delete();
            SpotifyToken::where('user_id', $authUser->id)->delete();
            Followers::where('follower_user_id', $authUser->id)->delete();
            Followers::where('following_user_id', $authUser->id)->delete();

            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'User can not be deleted'
            ], 500);
        }
    }

    // (un)Follow a user
    public function follow(Request $request)
    {
        $this->validate($request, [
            'following_user_id' => 'required|max:10',
        ]);

        $authUser = Auth()->user();

        $following = Followers::isFollowing($authUser->id, $request->following_user_id);

        if (!$following) {
            $follow_entry = Followers::follow($request->following_user_id, $authUser->id);

            if ($follow_entry) {
                return response()->json([
                    'success' => true,
                    'data' => 'You now follow this user'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'data' => 'Failed to follow user'
            ], 500);
        }

        if (Followers::unFollow($request->following_user_id, $authUser->id)) {
            return response()->json([
                'success' => true,
                'data' => 'Successfully unfollowed user'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => 'Failed to unfollow user'
        ], 500);
    }
}
