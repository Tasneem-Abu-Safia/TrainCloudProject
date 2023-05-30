<?php

namespace App\Http\Controllers\ManagerControllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Requests\CreateCourseRequest;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\Field;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use Yajra\DataTables\DataTables;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(ManagerMiddleware::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Course::ByLevel()->with('field')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($course) {
                    $buttons = '<div class="btn-group" role="group">
                            <a href="' . route('courses.show', $course) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i> View</a>
                            <a href="' . route('getAllTrainee', $course) . '" class="btn btn-light-primary"><i class="fas fa-user-graduate"></i> View</a>';
                    if (Auth::user()->guard == 'manager') {
                        $buttons .= '<a href="' . route('courses.edit', $course) . '" class="btn btn-light-info"><i class="fas fa-edit"></i> Edit</a>
                            <a class="mainDelete btn btn-light-danger" data-id="' . $course->id . '"><i class="fas fa-trash"></i> Delete</a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.course.index');

    }


    public function create()
    {
        $fields = Field::all();
        $advisors = Advisor::all();
        return view('layouts.course.create', compact('fields', 'advisors'));
    }

    public function store(CreateCourseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $course = Course::create($request->all());
            $this->pushNotification($request->advisor_id, $course->id);
        });
        return redirect()->route('courses.index')->with('msg', 'Course created successfully');
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('layouts.course.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $fields = Field::all();
        $advisors = Advisor::all();
        return view('layouts.course.edit', compact('fields', 'advisors', 'course'));
    }

    public function update(Request $request, Course $course)
    {
        $course->update($request->all());

        // Redirect to the course details page
        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return back()->with('msg', 'Deleted Done');
    }

    public function pushNotification($advisor_id, $course_id)
    {
        $notification = Notification::create([
            'type' => 'assignCourse',
            'notifiable_type' => 'App\User',
            'notifiable_id' => $advisor_id,
            'data' => json_encode([
                'course_id' => $course_id,
                'title' => 'New Message',
                'body' => 'New Course #' . $course_id . ' Added to You',
            ]),
        ]);

        $pusher = new Pusher('1e58abe6fe45f3bd2e73', 'c4f5a1132840e7111aba', '1607529', [
            'cluster' => 'ap3'
        ]);
        $pusher->trigger('advisor', 'notify-advisor', [
            'title' => 'New Message',
            'body' => 'New Course #' . $course_id . ' Added to You',
            'Notification_id' => $notification->id,
            'notifiable_id' => $advisor_id,
            'course_id' => $course_id,
        ]);
    }

    public function getAllTrainee(Request $request)
    {
        $courseId = $request->courseId;
        $course = Course::findOrFail($courseId);
        $courseName = $course->name;
        if ($request->ajax()) {
            $trainees = $course->trainees()
                ->wherePivot('status', 'active')
                ->with('user')
                ->get();
            return DataTables::of($trainees)
                ->addIndexColumn()
                ->addColumn('action', function ($trainee) {
                    $buttons = '
        <div class="btn-group" role="group">
            <a href="' . route('trainees.show', $trainee) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';
                    if (Auth::user()->guard == 'manager') {
                        if ($trainee->status === 'inactive') {
                            $buttons .= '<a class="traineeActive btn btn-light-success" data-id="' . $trainee->id . '" title="Active"><i class="fas fa-arrow-up"></i> Active</a>';
                        } else {
                            $buttons .= '<a class="traineeDeActive btn btn-light-danger" data-id="' . $trainee->id . '" title="Inactive"><i class="fas fa-arrow-down"></i> Inactive</a>';
                        }
                        $buttons .= '<a class="mainDelete btn btn-light-danger" data-id="' . $trainee->id . '"><i class="fas fa-trash"></i> Delete</a>';
                    }
                    $buttons .= '</div > ';
                    return $buttons;
                })
                ->
                rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.course.allTrainee', compact('courseId', 'courseName'));

    }

}
