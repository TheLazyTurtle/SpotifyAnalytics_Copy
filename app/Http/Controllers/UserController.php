<?php

namespace App\Http\Controllers;

use App\Models\Followers;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'Please log in'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    // Get a user by it's username
    public function show($username)
    {
        $authUser = Auth()->user();

        // TODO: Validate data
        $user = User::where('username', $username)
            ->select('id', 'username', 'img_url', 'private')
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'User not found'
            ], 400);
        }

        $user->hasFollowingRequest = Notification::hasFollowingRequestOpen($authUser->id, $user->id);
        $user->following = Followers::isFollowing($authUser->id, $user->id);
        $user->following_count = Followers::followingCount($user->id);
        $user->followers_count = Followers::followerCount($user->id);

        // if ($authUser->is_admin == false && !$user->following) {
        //     // TODO: IF YOU ARE NOT FOLLOWING WE STILL WANT A NAME AND AN IMG
        //     return response()->json([
        //         'success' => false,
        //         'data' => 'You are not following this user'
        //     ], 400);
        // }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function showGuest($username)
    {
        // TODO: Validate data
        $user = User::where('username', $username)
            ->select('id', 'username', 'img_url', 'private')
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'User not found'
            ], 400);
        }

        // Get the following / follower count of the user
        $user->following_count = Followers::where('follower_user_id', $user->user_id)
            ->select(DB::raw('COUNT(*) as count'))
            ->first()->count;
        $user->followers_count = Followers::where('following_user_id', $user->user_id)
            ->select(DB::raw('COUNT(*) as count'))
            ->first()->count;

        if ($user->private) {
            $user->following = false;

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    // Make new user
    public function store(Request $request)
    {
        $this->validate($request, [
            'spotify_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
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
        $authUser = Auth()->user();
        $user = User::where('id', $authUser->id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'data' => 'Please login'
            ], 400);
        }

        // TODO: validate input
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
    // TODO: Make it remove all data from a user
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
        $authUser = Auth()->user();

        // TODO: Validate input
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
