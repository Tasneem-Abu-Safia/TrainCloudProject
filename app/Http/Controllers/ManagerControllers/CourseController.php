<?php

namespace App\Http\Controllers\ManagerControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\Field;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Course::with('field')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($course) {
                    $buttons = '
                        <div class="btn-group" role="group">
                            <a href="' . route('courses.show', $course) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i> View</a>
                            <a href="' . route('courses.edit', $course) . '" class="btn btn-light-info"><i class="fas fa-edit"></i> Edit</a>
                            <a class="mainDelete btn btn-light-danger" data-id="' . $course->id . '"><i class="fas fa-trash"></i> Delete</a>
                        </div>';
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
        Course::create($request->all());

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
}
