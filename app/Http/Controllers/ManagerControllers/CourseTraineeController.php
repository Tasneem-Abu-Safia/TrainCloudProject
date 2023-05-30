<?php

namespace App\Http\Controllers\ManagerControllers;

use App\Http\Controllers\Controller;
use App\Mail\AcceeptCourse;
use App\Models\Course;
use App\Models\CourseTrainee;
use App\Models\Trainee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class CourseTraineeController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CourseTrainee::with(['course', 'trainee' => function ($q) {
                return $q->with('user');
            }])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($courseTrainee) {
                    $buttons = '<div class="btn-group" role="group">';

                    $buttons .= '<a href="' . route('trainees.show', $courseTrainee->trainee_id) . '" class="btn btn-light-primary viewCourse" data-course-id="' . $courseTrainee->course_id . '"><i class="fas fa-user-graduate"></i></a>';
                    $buttons .= '<a href="' . route('courses.show', $courseTrainee->course_id) . '" class="btn btn-light-primary viewTrainee" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-book"></i></a>';

                    // Active Trainee button
                    if ($courseTrainee->status === 'inactive') {
                        $buttons .= '<a href="#" class="btn btn-light-success btn-activate" data-course-id="' . $courseTrainee->course_id . '" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-check"></i></a>';
                    } else {
                        $buttons .= '<a href="#" class="btn btn-light-danger btn-deactivate" data-course-id="' . $courseTrainee->course_id . '" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-times"></i></a>';
                    }
                    // Delete button
                    $buttons .= '<a href="#" class="btn btn-light-danger deleteCourseTrainee" data-course-id="' . $courseTrainee->course_id . '" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-trash"></i></a>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.course-trainees.index');
    }


    public function indexRequest(Request $request)
    {
        if ($request->ajax()) {
            $data = CourseTrainee::with(['course', 'trainee' => function ($q) {
                return $q->with('user');
            }])->where('status', 'inactive')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($courseTrainee) {
                    $buttons = '<div class="btn-group" role="group">';

                    $buttons .= '<a href="' . route('trainees.show', $courseTrainee->trainee_id) . '" class="btn btn-light-primary viewCourse" data-course-id="' . $courseTrainee->course_id . '"><i class="fas fa-user-graduate"></i></a>';
                    $buttons .= '<a href="' . route('courses.show', $courseTrainee->course_id) . '" class="btn btn-light-primary viewTrainee" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-book"></i></a>';

                    // Active Trainee button
                    if ($courseTrainee->status === 'inactive') {
                        $buttons .= '<a href="#" class="btn btn-light-success btn-activate" data-course-id="' . $courseTrainee->course_id . '" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-check"></i></a>';
                    } else {
                        $buttons .= '<a href="#" class="btn btn-light-danger btn-deactivate" data-course-id="' . $courseTrainee->course_id . '" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-times"></i></a>';
                    }
                    // Delete button
                    $buttons .= '<a href="#" class="btn btn-light-danger deleteCourseTrainee" data-course-id="' . $courseTrainee->course_id . '" data-trainee-id="' . $courseTrainee->trainee_id . '"><i class="fas fa-trash"></i></a>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.course-trainees.requests');

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    public function destroy($courseId, $traineeId)
    {
        $course = Course::findOrFail($courseId);
        $course->trainees()->detach($traineeId);

        return back()->with(['message' => 'Course trainee deleted successfully']);
    }


    public function active($course_id, $trainee_id)
    {
        $course = Course::findOrFail($course_id);

        if ($course->num_trainee >= $course->capacity) {
//            toastr()->error('The course has reached its maximum capacity.');
            return response()->json(['message' => 'The course has reached its maximum capacity.']);
        }

        DB::transaction(function () use ($course_id, $trainee_id, $course) {
            $courseTrainee = CourseTrainee::where('course_id', $course_id)
                ->where('trainee_id', $trainee_id)
                ->update(['status' => 'active']);

            $course->increment('num_trainee');

            $user = Trainee::with('user')->findOrFail($trainee_id);
            $data = [
                'course' => $course,
                'user' => $user,
            ];
//            Mail::to($user->user->email)->send(new AcceeptCourse($data));
//            toastr()->success('Trainee has been activated.');
        });
        return response()->json(['message' => 'Trainee has been activated.']);

    }


    public function inactive($course_id, $trainee_id)
    {
        $course = Course::findOrFail($course_id);

        if ($course->num_trainee <= 0) {
//            toastr()->error('No trainees to deactivate.');

            return response()->json(['message' => 'No trainees to deactivate.']);
        }

        DB::transaction(function () use ($course_id, $trainee_id, $course) {
            $courseTrainee = CourseTrainee::where('course_id', $course_id)
                ->where('trainee_id', $trainee_id)
                ->update(['status' => 'inactive']);

            $course->decrement('num_trainee');
//            toastr()->success('Trainee has been deactivated.');

        });
        return response()->json(['message' => 'Trainee has been deactivated.']);

    }


}
