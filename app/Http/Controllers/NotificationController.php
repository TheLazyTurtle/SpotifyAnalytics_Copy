<?php

namespace App\Http\Controllers;

use App\Models\Followers;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    // Get all notifications for one user
    public function index()
    {
        $user_id = Auth()->user()->id;
        $notifications = Notification::where('receiver_user_id', $user_id)->select('id', 'sender_user_id', 'notification_type_id')->get();

        foreach ($notifications as $notification) {
            $sender_user = User::where('id', $notification->sender_user_id)->select('username', 'img_url')->first();

            unset($notification['sender_user_id']);
            $notification->username = $sender_user->username;
            $notification->img_url = $sender_user->img_url;
        }

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ], 200);
    }

    // Create
    public function store(Request $request)
    {
        $authUser = Auth()->user();

        $this->validate($request, [
            'notification_type_id',
            'receiver_user_id',
        ]);

        // Check if notification already exists
        // TODO: Should this check not be a function of it self
        $notification = Notification::where('receiver_user_id', $request->receiver_user_id)->where('sender_user_id', $authUser->id)->first();

        if ($notification) {
            return response()->json([
                'success' => false,
                'data' => 'You already have a request'
            ], 400);
        }

        $notification = new Notification();
        $notification->notification_type_id = $request->notification_type_id;
        $notification->receiver_user_id = $request->receiver_user_id;
        $notification->sender_user_id = $authUser->id;

        if ($notification->save()) {
            return response()->json([
                'success' => true,
                'data' => $notification->toArray()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Failed to add notification'
            ], 500);
        }
    }

    // Delete
    public function destroy(Request $request)
    {
        // TODO: input validation on whole controller
        $authUser = Auth()->user();
        $this->validate($request, [
            'receiver_user_id'
        ]);

        $notification = Notification::where('receiver_user_id', $request->receiver_user_id)->where('sender_user_id', $authUser->id)->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'data' => 'No notification found'
            ], 400);
        }

        if ($notification->delete()) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Notification can not be deleted'
            ], 500);
        }
    }

    // Handles a notification
    public function handle(Request $request)
    {
        $authUser = Auth()->user();
        $notification = Notification::where('id', $request->notification_id)->first();

        // If the person sending the request is not in the notificaiton kill the request because it is invalid
        if ($notification->receiver_user_id != $authUser->id) {
            return response([
                'data' => 'Please log in'
            ], 400);
        }

        if ($notification->notification_type_id == 0 && $request->response) {
            Followers::follow($notification->receiver_user_id, $notification->sender_user_id);
        }

        // Delete the notification after handeling it
        if ($notification->delete()) {
            return response([
                'data' => 'Handled notification'
            ], 200);
        } else {
            return response([
                'data' => 'Failed to handle notification'
            ], 500);
        }
    }
}
