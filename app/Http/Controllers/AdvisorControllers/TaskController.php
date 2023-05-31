<?php

namespace App\Http\Controllers\AdvisorControllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdvisorMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Traits\FileUploadTrait;
use App\Models\Course;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    use FileUploadTrait;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->guard == 'manager' || Auth::user()->guard == 'advisor') {
                return $next($request);
            }
            abort(403); // Unauthorized access
        })->only(['index', 'show', 'getTaskSubmissions']);
        $this->middleware(AdvisorMiddleware::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Task::with(['course', 'advisor'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($task) {
                    $buttons = '<div class="btn-group" role="group">';
                    if (Auth::user()->guard == 'advisor') {
                        $buttons .= '<a href="' . route('tasks.edit', $task) . '" class="btn btn-light-info"><i class="fas fa-edit"></i></a>
            <a class="mainDelete btn btn-light-danger" data-id="' . $task->id . '"><i class="fas fa-trash"></i></a>';
                    }
                    $buttons .= '<a href="' . route('tasks.submissions', $task) . '" class="btn btn-light-primary">View Submissions</a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->addColumn('file', function ($task) {
                    if ($task->file) {
                        return '<a href="' . $this->getUploadedFireBase($task->file) . '" class="btn btn-light-primary" target="_blank">Show File</a>';
                    } else {
                        return '<p>No files uploaded.</p>';
                    }
                })
                ->rawColumns(['action', 'file'])
                ->make(true);
        }

        return view('layouts.tasks.index');

    }


    public function create()
    {
        $courses = Course::where([
            'advisor_id' => Auth::user()->advisor->id,
        ])->where('end_date', '>=', date('Y-m-d'))->get();
        return view('layouts.tasks.create', compact('courses'));
    }

    public function store(CreateTaskRequest $request)
    {
        DB::transaction(function () use ($request) {
            $path = $this->uploadFilesFireBase($request);
            $request['advisor_id'] = Auth::user()->advisor->id;
            $task = Task::create(array_merge($request->all(), [
                'file' => $path,
            ]));
        });
        return redirect()->route('tasks.index')->with('msg', 'Task created successfully');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('layouts.tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $task->file = $this->getUploadedFireBase($task->file);
        $courses = Course::where([
            'advisor_id' => Auth::user()->advisor->id,
        ])->where('end_date', '>=', date('Y-m-d'))->get();
        return view('layouts.tasks.edit', compact('task', 'courses'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'mark' => 'nullable|integer',
        ]);
        $request->file = $this->uploadFilesFireBase($request);
        $task->update($request->all());
        // Redirect to the course details page
        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return back()->with('msg', 'Deleted Done');
    }

    public function getTaskSubmissions(Task $task)
    {
        $submissions = $task->submissions()->with('trainee')->get();
        if (request()->ajax()) {
            return DataTables::of($submissions)
                ->addIndexColumn()
                ->addColumn('trainee_name', function ($submission) {
                    return $submission->trainee->user->name;
                })
                ->addColumn('file', function ($submission) {
                    if ($submission->file) {
                        return '<a href="' . $this->getUploadedFireBase($submission->file) . '" class="btn btn-light-primary" target="_blank">Show File</a>';
                    } else {
                        return '<p>No file uploaded.</p>';
                    }
                })
                ->addColumn('mark', function ($submission) {
                    return $submission->mark ?? '-';
                })
                ->addColumn('status', function ($submission) {
                    return ucfirst($submission->status);
                })
                ->addColumn('action', function ($submission) {
                    if (Auth::user()->guard == 'advisor') {
                        $buttons = '<div class="btn-group" role="group">';
                        // Add mark button
                        $buttons .= '<button class="btn btn-light-primary btn-mark" data-submission-id="' . $submission->id . '" data-toggle="modal" data-target="#markModal"><i class="fas fa-check"></i> Mark</button>';
                        // Add delete button
                        $buttons .= '<button class="btn btn-light-danger mainDelete" data-id="' . $submission->id . '"><i class="fas fa-trash"></i> Delete</button>';
                        $buttons .= '</div>';
                        return $buttons;
                    }
                })
                ->rawColumns(['file', 'action', 'trainee_name', 'status'])
                ->make(true);
        }
        return view('layouts.tasks.allSubmissions', compact('task'));
    }


}
