<?php

namespace App\Http\Controllers\AdvisorControllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TaskSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Task $task, Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskSubmission $taskSubmission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskSubmission $taskSubmission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskSubmission $taskSubmission)
    {
        $taskSubmission->delete();
        return redirect()->back()->with('msg', 'Task submission deleted successfully');

    }


    public function updateMark(Request $request, TaskSubmission $submission)
    {
        $request->validate([
            'mark' => 'required|integer',
        ]);

        $submission->mark = $request->input('mark');
        $submission->status = 'completed';
        $submission->save();

        return response()->json(['message' => 'Submission mark updated successfully']);
    }
}
