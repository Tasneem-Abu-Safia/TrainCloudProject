<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvisorRegistrationRequest;
use App\Http\Requests\TraineeRegistrationRequest;
use App\Models\Advisor;
use App\Models\Field;
use App\Models\Trainee;
use App\Models\User;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use FileUploadTrait;

    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($credentials['userName'], FILTER_VALIDATE_EMAIL) ? 'email' : 'unique_id';

        $loginData = [
            $field => $credentials['userName'],
            'password' => $credentials['password'],
            'status' => 'active',
        ];

        if (Auth::attempt($loginData)) {
            $request->session()->regenerate();

            return redirect()->route('home');
        } else {
            return back()->with('error', 'These credentials do not match our records.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Log Out Successfully');
    }


    public function getSignup()
    {
        $fields = Field::all();
        return view('auth.register', compact('fields'));
    }

    public function postTrainee(TraineeRegistrationRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'guard' => 'trainee',
                'status' => 'inactive',
            ]);
            // Upload files
            $uploadedFiles = $this->uploadFiles($request, 'trainee_files');

            $trainee = Trainee::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'degree' => $request->degree,
                'status' => 'inactive',
                'files' => $uploadedFiles,
            ]);
            DB::commit();

            // Redirect to a success page or perform any additional actions
            return redirect()->route('login')->with('success', 'Trainee registered successfully. Please wait for approval.');
        } catch (\Exception $e) {
            DB::rollback();

            // Redirect back with an error message
            return back()->with('error', 'Failed to register Trainee. Please try again.');
        }
    }

    public function postAdvisor(AdvisorRegistrationRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'guard' => 'advisor',
                    'status' => 'inactive',
                ]);

                $fields = $request->input('fields');
                $uploadedFiles = $this->uploadFiles($request, 'trainee_files');

                $advisor = Advisor::create([
                    'user_id' => $user->id,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'degree' => $request->degree,
                    'status' => 'inactive',
                    'files' => $uploadedFiles,
                ]);

                $advisor->fields()->attach($fields);
            });

            return redirect()->route('login')->with('success', 'Advisor registered successfully. Please wait for approval.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
//            return back()->with('error', 'Failed to register advisor. Please try again.');
        }
    }
}
