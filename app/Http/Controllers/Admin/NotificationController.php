<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function create()
    {
        $users=User::get();
       return view('admin.notifications.create',compact('users'));

    }

    public function send(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:0,1', // 0 = all, 1 = single user
            'user_id' => 'nullable|exists:users,id',
        ]);

        $sent = false;

        if ($request->type == 0) {
            // Send to all users
            $sent = FCMController::sendMessageToAll($request->title, $request->body);
        } elseif ($request->type == 1 && $request->user_id) {
            // Send to a specific user
            $sent = FCMController::sendMessageToUser($request->title, $request->body, $request->user_id);
        }

        // Save notification to DB
        Notification::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->type == 1 ? $request->user_id : null,
        ]);

        if ($sent) {
            return back()->with('message', '✅ Notification sent successfully');
        } else {
            return back()->with('error', '⚠️ Notification failed to send');
        }
    }


}
