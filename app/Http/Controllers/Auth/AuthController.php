<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvisorRegistrationRequest;
use App\Http\Requests\TraineeRegistrationRequest;
use App\Http\Traits\FileUploadTrait;
use App\Mail\ForgotPass;
use App\Mail\MailNotify;
use App\Models\Advisor;
use App\Models\Field;
use App\Models\Trainee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function changePass(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('msg', 'Password changed successfully.');
    }


    public function forgotPassword(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validatedData->fails()) {
            return back()->with('error', 'You should Register.');
        }
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if ($user->status === 'inactive') {
            return back()->with('error', 'Manager not activation you');
        } else {
            $password = Str::random(9); // Generate a random password
            $user->password = Hash::make($password);
            $data = [
                'user' => $user->name,
                'uniqueId' => $user->unique_id,
                'password' => $password,
            ];
            // Send activation email to the user
            Mail::to($user->email)->send(new ForgotPass($data));
            return back()->with('success', 'Email send Successfully');

        }
    }
}
