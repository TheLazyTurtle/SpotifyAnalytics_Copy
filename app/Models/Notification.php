<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_type_id',
        'receiver_user_id',
        'sender_user_id'
    ];

    static function hasFollowingRequestOpen($user_id, $external_user_id)
    {
        $notification = Notification::where('receiver_user_id', $external_user_id)->where('sender_user_id', $user_id)->first();

        if (!$notification) {
            return false;
        }
        return true;
    }
}
