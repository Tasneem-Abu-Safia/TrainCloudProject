<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Notification::ByLevel()->orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($notification) {
                    $buttons = '<div class="btn-group" role="group">';
                    if (strcmp($notification->type, "register_Advisor") == 0) {
                        $buttons .= '<a data-notification-type="' . $notification->type . '"
                        data-id="' . $notification->id . '" data-user="' . json_decode($notification->data, true)['register_id'] . '" class="makeRead btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';
                    } else if (strcmp($notification->type, "register_Trainee") == 0) {
                        $buttons .= '<a  data-notification-type="' . $notification->type . '" data-id="' . $notification->id . '"
                            data-user="' . json_decode($notification->data, true)['register_id'] . '" class=" makeRead btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';
                    } else if (strcmp($notification->type, "assignCourse") == 0) {
                        $buttons .= '<a  data-notification-type="' . $notification->type . '" data-id="' . $notification->id . '"
                            data-user="' . json_decode($notification->data, true)['course_id'] . '" class=" makeRead btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.notifications');
    }

    public function getNotifications()
    {
        $notifications = Notification::latest()->limit(10)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = Carbon::now();
        $notification->save();
        if (!$request->ajax()) {
            if (strcmp($notification->type, "register_Advisor") == 0) {
                return redirect()->route('advisors.show', json_decode($notification->data, true)['register_id']);
            } else if (strcmp($notification->type, "register_Trainee") == 0) {
                return redirect()->route('trainees.show', json_decode($notification->data, true)['register_id']);
            } else if (strcmp($notification->type, "assignCourse") == 0) {
                return redirect()->route('courses.show', json_decode($notification->data, true)['course_id']);
            }
        }
    }

    public function markAllRead()
    {
        $userId = Auth::id();
        Notification::ByLevel()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Return a response if needed
        return response()->json(['message' => 'All notifications marked as read'], 200);
    }

}
