<?php

namespace App\Http\Controllers\ManagerControllers;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use App\Models\Advisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AdvisorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Advisor::with('user')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($advisor) {
                    $buttons = '
        <div class="btn-group" role="group">
            <a href="' . route('advisors.show', $advisor) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';

                    if ($advisor->status === 'inactive') {
                        $buttons .= '<a class="advisorActive btn btn-light-success" data-id="' . $advisor->id . '" title="Active"><i class="fas fa-arrow-up"></i> Active</a>';
                    } else {
                        $buttons .= '<a class="advisorDeActive btn btn-light-danger" data-id="' . $advisor->id . '" title="Inactive"><i class="fas fa-arrow-down"></i> Inactive</a>';
                    }

                    $buttons .= '<a class="mainDelete btn btn-light-danger" data-id="' . $advisor->id . '"><i class="fas fa-trash"></i> Delete</a>
        </div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.advisor.index');
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

    public function show(Advisor $advisor)
    {
        return view('layouts.advisor.show', compact('advisor'));
    }

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
        Advisor::destroy($id);
        return back()->with('msg', 'Deleted Done');
    }

    public function active($id)
    {
        $advisor = Advisor::find($id);
        $user = User::find($advisor->user_id);
        // Check if the user already has a unique_id
        if ($user->unique_id) {
            // User already has a unique_id, update the status to active
            $advisor->status = 'active';
            $user->status = 'active';
            $user->save();
            $advisor->save();
            return response()->json(['msg' => 'Advisor Active']);

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
        $advisor->status = 'active';
        $user->status = 'active';
        $user->save();
        $advisor->save();

        return response()->json(['msg' => 'Advisor Activated and Email Sent']);

    }

    public function deActive($id)
    {
        $advisor = Advisor::find($id);
        $advisor->status = 'inactive';
        $advisor->user->status = 'inactive';
        $advisor->user->save();
        $advisor->save();
        return response()->json(['msg' => 'Advisor Inactive']);
    }

    public function advisorRequests(Request $request)
    {
        if ($request->ajax()) {
            $data = Advisor::where(['status' => 'inactive'])->with(['user' => function ($q) {
                $q->where(['status' => 'inactive']);
            }])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($advisor) {
                    $buttons = '
        <div class="btn-group" role="group">
            <a href="' . route('advisors.show', $advisor) . '" class="btn btn-light-primary"><i class="fas fa-eye"></i> View</a>';

                    if ($advisor->status === 'inactive') {
                        $buttons .= '<a class="advisorActive btn btn-light-success" data-id="' . $advisor->id . '" title="Active"><i class="fas fa-arrow-up"></i> Active</a>';
                    } else {
                        $buttons .= '<a class="advisorDeActive btn btn-light-danger" data-id="' . $advisor->id . '" title="Inactive"><i class="fas fa-arrow-down"></i> Inactive</a>';
                    }

                    $buttons .= '<a class="mainDelete btn btn-light-danger" data-id="' . $advisor->id . '"><i class="fas fa-trash"></i> Delete</a>
        </div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.advisor.requests');

    }

}
