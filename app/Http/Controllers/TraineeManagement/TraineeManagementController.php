<?php

namespace App\Http\Controllers\TraineeManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\FileUploadTrait;
use App\Models\AttendanceRecord;
use App\Models\Course;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Trainee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TraineeManagementController extends Controller
{
    use FileUploadTrait;

    public function getAllCourses(Request $request)
    {

        if ($request->ajax()) {
            $traineeFields = Auth::user()->trainee->fields->pluck('id')->toArray();

            $data = Course::with('field')
                ->where('end_date', '>=', date('Y-m-d'))
                ->where('num_trainee', '<', DB::raw('capacity'))
                ->whereIn('field_id', $traineeFields)
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($course) {
                    $buttons = '<div class="btn-group" role="group">
                     <a href="' . route('showCourse', $course->id) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i>View</a>';

                    if (Auth::user()->guard == 'trainee') {
                        $traineeJoined = $course->trainees()->wherePivot('trainee_id', Auth::user()->trainee->id)
                            ->wherePivot('status', 'active')->exists();
                        if ($traineeJoined) {
                            $buttons .= '<a href="' . route('courseJoinedDetails', $course->id) . '" class="btn btn-light-info"><i class="fas fa-info-circle"></i>Details</a>';
                        } else {
                            $buttons .= '<form class="d-inline" action="' . route('courses.enroll', $course->id) . '" method="POST">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="submit" class="btn btn-light-success joinCourse" data-id="' . $course->id . '">
                                <i class="fas fa-plus"></i> Join
                            </button>
                        </form>';
                        }
                    }
                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('trainee.courses');

    }


    public function showCourse($id)
    {
        $course = Course::findOrFail($id);
        return view('trainee.courseDetails', compact('course'));
    }

    public function join(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        $trainee = Auth::user()->trainee;

        // Check if trainee already joined the course
        if ($trainee->courses->contains($course)) {
            return back()->with('error', 'You have already joined this course.');
        }

        // Check if course is full
        if ($course->num_trainee >= $course->capacity) {
            return back()->with('error', 'Course is full. Cannot enroll.');
        }

        // Check if course has no fees, join directly
        if ($course->fees == 0) {
            $course->trainees()->attach($trainee->id, ['advisor_id' => $course->advisor_id]);
            return back()->with('success', 'You have successfully enrolled in the course.');
        }

        // Check if trainee has billing
        $billing = $trainee->billing;
        if (!$billing) {
            return redirect()->route('billings.create')->with('error', 'You need to add billing information before joining the course.');
        }

        // Check if billing is inactive
        if ($billing->payment_status == 'inactive') {
            return back()->with('error', 'Please wait for the manager to activate your billing.');
        }

        // Check if trainee has enough funds to join the course
        if ($billing->amount_due >= $course->fees) {
            $billing->amount_due -= $course->fees;
            $billing->save();
            $course->trainees()->attach($trainee->id, ['advisor_id' => $course->advisor_id]);
            return back()->with('success', 'You have successfully enrolled in the course. Wait for Manager acceptance.');
        } else {
            return back()->with('error', 'No available amount. Insufficient funds to join the course.');
        }
    }


    public function joinedCourses(Request $request)
    {
        if ($request->ajax()) {
            $trainee = Auth::user()->trainee;
            $courses = $trainee->courses()->wherePivot('status', 'active')->get();

            return DataTables::of($courses)
                ->addIndexColumn()
                ->addColumn('action', function ($course) {
                    $buttons = '<div class="btn-group" role="group">';
                    $traineeJoined = $course->trainees()->wherePivot('trainee_id', Auth::user()->trainee->id)
                        ->wherePivot('status', 'active')->exists();
                    if ($traineeJoined) {
                        $buttons .= '<a href="' . route('courseJoinedDetails', $course->id) . '" class="btn btn-light-info"><i class="fas fa-info-circle"></i>Details</a>';
                        $buttons .= '<button class="btn btn-light-primary attendance" data-id="' . $course->id . '" data-course="' . htmlentities(json_encode($course)) . '"><i class="fas fa-check"></i> Attendance</button>';
                    } else {
                        $buttons .= '<form class="d-inline" action="' . route('courses.enroll', $course->id) . '" method="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button type="submit" class="btn btn-light-success joinCourse" data-id="' . $course->id . '">
                            <i class="fas fa-plus"></i> Join
                        </button>
                    </form>';
                    }

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('trainee.coursesJoined');
    }


    public function courseJoinedDetails(Course $course)
    {
        $tasks = $course->tasks()->where('end_date', '>=', now())->get();

        foreach ($tasks as $task) {
            $file = $task->file;
            $task->file = $this->getUploadedFireBase($file);
        }
        return view('trainee.courseJoinedDetails', compact('course', 'tasks'));
    }


    public function submitTask(Request $request, $taskId)
    {
        // Validate the submitted file
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Retrieve the task by its ID
        $task = Task::findOrFail($taskId);

        // Retrieve the trainee ID
        $traineeId = Auth::user()->trainee->id;

        // Find the task submission for the trainee and task
        $taskSubmission = TaskSubmission::where('task_id', $taskId)
            ->where('trainee_id', $traineeId)
            ->first();

        // If task submission doesn't exist, create a new one
        if (!$taskSubmission) {
            $taskSubmission = new TaskSubmission();
            $taskSubmission->task_id = $taskId;
            $taskSubmission->trainee_id = $traineeId;
        }

        // Upload the file
        if ($request->hasFile('file')) {
            $filePath = $this->uploadFilesFireBase($request);
            $taskSubmission->file = $filePath;
            $taskSubmission->save();

            return redirect()->back()->with('success', 'Task submitted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to submit task.');
    }

    public function myTasks()
    {
        $courses = Course::whereHas('trainees', function ($query) {
            $query->where('trainee_id', Auth::user()->trainee->id)
                ->where('course_trainee.status', 'active');
        })->get();

        $tasks = Task::whereIn('course_id', $courses->pluck('id'))
            ->where('end_date', '>=', now())
            ->get();

        foreach ($tasks as $task) {
            $file = $task->file;
            $task->file = $this->getUploadedFireBase($file);
        }

        return view('trainee.myTasks', compact('tasks'));
    }

    public function addAttendance(Request $request)
    {
        try {
            $courseId = $request->courseId;
            $attendanceDates = $request->attendance;

            // Retrieve the course
            $course = Course::findOrFail($courseId);

            // Loop through the attendance dates
            foreach ($attendanceDates as $attendanceDate) {
                AttendanceRecord::create([
                    'course_id' => $course->id,
                    'trainee_id' => Auth::user()->trainee->id,
                    'date' => $attendanceDate,
                    'status' => 'present' // You can adjust the status as needed
                ]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Attendance is pre-registered']);
        }
    }

}
