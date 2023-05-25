<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(10);
        return view('layouts.notifications', compact('notifications'));

    }

    public function getNotifications()
    {
        $notifications = Notification::latest()->limit(10)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = Carbon::now();
        $notification->save();
        if (strcmp($notification->type, "register_Advisor") == 0) {
            return redirect()->route('advisors.show', json_decode($notification, true)['data']['register_id']);
        } else if (strcmp($notification->type, "register_Trainee") == 0) {
            return redirect()->route('trainees.show', json_decode($notification, true)['data']['register_id']);
        }
    }
}
