<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdvisorMiddleware;
use App\Http\Middleware\TraineeMiddleware;
use App\Mail\MeetingEmail;
use App\Models\Advisor;
use App\Models\CourseTrainee;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->guard == 'trainee' || Auth::user()->guard == 'advisor') {
                return $next($request);
            }
            abort(403);
        })->only(['index']);
        $this->middleware(TraineeMiddleware::class)->only(['create', 'store', 'courseAdvisors']);
        $this->middleware(AdvisorMiddleware::class)->only(['edit', 'update', 'destroy', 'sendEmail', 'updateStatus']);

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Meeting::ByLevel()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('trainee', function ($meeting) {
                    return $meeting->trainee->user->name;
                })
                ->addColumn('advisor', function ($meeting) {
                    return $meeting->advisor->user->name;
                })
                ->addColumn('action', function ($meeting) {
                    $buttons = '<div class="btn-group" role="group">';

                    // Trainee actions
                    if (Auth::user()->guard == 'trainee' && $meeting->status == 'requested') {
                        $buttons .= '<form method="POST" action="' . route('meetings.cancel', $meeting->id) . '">
            ' . csrf_field() . '
            ' . method_field('PUT') . '
            <button type="submit" class="btn btn-light-primary"><i class="fas fa-times"></i> Cancel Meeting</button>
        </form>';
                    }

                    // Advisor actions
                    if (Auth::user()->guard == 'advisor') {
                        $buttons .= '<button class="btn btn-light-primary sendEmail" data-id="' . $meeting->id . '"><i class="fas fa-envelope"></i> Send Email</button>';
                        $buttons .= '<button class="btn btn-light-primary updateStatus" data-id="' . $meeting->id . '"><i class="fas fa-check"></i> Update Status</button>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action', 'advisor', 'trainee'])
                ->make(true);
        }

        return view('layouts.meetings');
    }

    public function courseAdvisors()
    {
        $traineeId = auth()->user()->trainee->id;

        $advisors = CourseTrainee::where('trainee_id', $traineeId)
            ->join('advisors', 'course_trainee.advisor_id', '=', 'advisors.id')
            ->select('advisors.*')
            ->with('advisor.user')
            ->pluck('id');

        $advisors = Advisor::with('user')->whereIn('id', $advisors)->get();
        return response()->json($advisors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'advisor_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'details' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Combine the date and time inputs into a single DateTime object
        $dateTime = $request->date . ' ' . $request->time;

        // Check if the advisor already has a meeting at the selected datetime
        $existingMeeting = Meeting::where('advisor_id', $request->advisor_id)
            ->where('datetime', $dateTime)
            ->where('status', '<>', 'requested')
            ->exists();

        if ($existingMeeting) {
            return redirect()->back()->with('error', 'The advisor already has a meeting at the selected datetime.');
        }

        // Store the meeting with the selected advisor and combined date/time
        Meeting::create([
            'trainee_id' => Auth::user()->trainee->id,
            'advisor_id' => $request->advisor_id,
            'datetime' => $dateTime,
            'details' => $request->details,
        ]);

        return redirect()->back()->with('success', 'Meeting created successfully.');
    }


    public function cancel(Meeting $meeting)
    {
        $meeting->update([
            'status' => 'declined',
        ]);
        return redirect()->back();
    }

    public function sendEmail(Request $request)
    {

        $meetingId = $request->meeting_id;
        $emailContent = $request->email_content;
        $meeting = Meeting::findOrfail($meetingId);
        $email = $meeting->trainee->user->email;
        $data = [
            'emailContent' => $emailContent,
            'meetingDate' => $meeting->datetime,
            'advisor' => Auth::user()->name,
        ];
        Mail::to($email)->send(new MeetingEmail($data));
        return response()->json(['msg' => 'Done']);
    }

    public function updateStatus(Request $request)
    {
        // Retrieve meeting ID from the request
        $meetingId = $request->meetingId;
        // Retrieve status from the request
        $status = $request->status;

        $meeting = Meeting::findOrfail($meetingId);
        $meeting->update(['status' => $status]);
        return response()->json(['success' => 'Done']);
    }

}
