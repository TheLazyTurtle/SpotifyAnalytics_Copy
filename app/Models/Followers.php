<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Followers extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_user_id',
        'following_user_id'
    ];

    static function isFollowing($user_id, $external_user_id)
    {
        $following = Followers::where('follower_user_id', $user_id)
            ->where('following_user_id', $external_user_id)
            ->first();

        if (!$following) {
            return false;
        }
        return true;
    }

    static function followerCount($user_id)
    {
        return Followers::where('following_user_id', $user_id)
            ->select(DB::raw('COUNT(*) as count'))
            ->first()->count;
    }

    static function followingCount($user_id)
    {
        return Followers::where('follower_user_id', $user_id)
            ->select(DB::raw('COUNT(*) as count'))
            ->first()->count;
    }

    static function follow($user_id, $follower_user_id)
    {
        $follow = new Followers();
        $follow->following_user_id = $user_id;
        $follow->follower_user_id = $follower_user_id;

        if ($follow->save()) {
            return true;
        } else {
            return false;
        }
    }

    static function unFollow($user_id, $follower_user_id)
    {
        $following_entry = Followers::where('follower_user_id', $follower_user_id)
            ->where('following_user_id', $user_id);

        if ($following_entry->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
