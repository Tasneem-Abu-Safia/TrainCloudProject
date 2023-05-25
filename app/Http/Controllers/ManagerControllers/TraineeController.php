<?php

namespace App\Http\Controllers\ManagerControllers;

use App\Mail\MailNotify;
use App\Models\Trainee;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class TraineeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Trainee::with('user')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($trainee) {
                    $buttons = '
        <div class="btn-group" role="group">
            <a href="' . route('trainees.show', $trainee) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';

                    if ($trainee->status === 'inactive') {
                        $buttons .= '<a class="traineeActive btn btn-light-success" data-id="' . $trainee->id . '" title="Active"><i class="fas fa-arrow-up"></i> Active</a>';
                    } else {
                        $buttons .= '<a class="traineeDeActive btn btn-light-danger" data-id="' . $trainee->id . '" title="Inactive"><i class="fas fa-arrow-down"></i> Inactive</a>';
                    }

                    $buttons .= '<a class="mainDelete btn btn-light-danger" data-id="' . $trainee->id . '"><i class="fas fa-trash"></i> Delete</a>
        </div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.trainee.index');
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
    public function show(Trainee $trainee)
    {
        return view('layouts.trainee.show', compact('trainee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy($id)
    {
        Trainee::destroy($id);
        return back()->with('msg', 'Deleted Done');
    }

    public function active($id)
    {
        $trainee = Trainee::find($id);
        $user = User::find($trainee->user_id);
        // Check if the user already has a unique_id
        if ($user->unique_id) {
            // User already has a unique_id, update the status to active
            $trainee->status = 'active';
            $user->status = 'active';
            $user->save();
            $trainee->save();
            return response()->json(['msg' => 'Trainee Active']);

        }

        // Generate a unique_id and password for the user
        $uniqueId = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        while (User::where('unique_id', $uniqueId)->exists()) {
            // Regenerate the unique ID if it already exists
            $uniqueId = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        }
        $password = Str::random(9); // Generate a random password
        $data = [
            'user' => $user->name,
            'uniqueId' => $uniqueId,
            'password' => $password,
        ];
        // Send activation email to the user
        Mail::to($user->email)->send(new MailNotify($data));

        // Update user's unique_id, password, and status
        $user->unique_id = $uniqueId;
        $user->password = Hash::make($password); // Hash the password before storing it
        $trainee->status = 'active';
        $user->status = 'active';
        $user->save();
        $trainee->save();

        return response()->json(['msg' => 'Trainee Activated and Email Sent']);

    }

    public function deActive($id)
    {
        $trainee = Trainee::find($id);
        $trainee->status = 'inactive';
        $trainee->user->status = 'inactive';
        $trainee->user->save();
        $trainee->save();
        return response()->json(['msg' => 'Trainee Inactive']);
    }


    public function traineeRequests(Request $request)
    {
        if ($request->ajax()) {
            $data = Trainee::where(['status' => 'inactive'])->with(['user' => function ($q) {
                $q->where(['status' => 'inactive']);
            }])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($trainee) {
                    $buttons = '
        <div class="btn-group" role="group">
            <a href="' . route('trainees.show', $trainee) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';

                    if ($trainee->status === 'inactive') {
                        $buttons .= '<a class="traineeActive btn btn-light-success" data-id="' . $trainee->id . '" title="Active"><i class="fas fa-arrow-up"></i> Active</a>';
                    } else {
                        $buttons .= '<a class="traineeDeActive btn btn-light-danger" data-id="' . $trainee->id . '" title="Inactive"><i class="fas fa-arrow-down"></i> Inactive</a>';
                    }

                    $buttons .= '<a class="mainDelete btn btn-light-danger" data-id="' . $trainee->id . '"><i class="fas fa-trash"></i> Delete</a>
        </div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.trainee.requests');

    }
}
