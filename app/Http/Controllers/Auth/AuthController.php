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
use App\Models\Notification;
use App\Models\Trainee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Pusher\Pusher;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->guard == 'trainee' || Auth::user()->guard == 'advisor') {
                return $next($request);
            }
            abort(403); // Unauthorized access
        })->only(['updateProfile']);
    }

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

        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'guard' => 'trainee',
            'status' => 'inactive',
        ]);
        // Upload files
        $uploadedFiles = $this->uploadFilesFireBase($request);

        $trainee = Trainee::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'degree' => $request->degree,
            'status' => 'inactive',
            'files' => $uploadedFiles,
        ]);
        $fields = $request->input('fields');
        $trainee->fields()->attach($fields);
        $this->pushNotificationManager($trainee->id, 'Trainee');
        DB::commit();
        // Redirect to a success page or perform any additional actions
        return redirect()->route('login')->with('success', 'Trainee registered successfully. Please wait for approval.');

    }

    public function postAdvisor(AdvisorRegistrationRequest $request)
    {

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'guard' => 'advisor',
                'status' => 'inactive',
            ]);

            $fields = $request->input('fields');
            $uploadedFiles = $this->uploadFilesFireBase($request);

            $advisor = Advisor::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'degree' => $request->degree,
                'status' => 'inactive',
                'files' => $uploadedFiles,
            ]);

            $advisor->fields()->attach($fields);
            $this->pushNotificationManager($advisor->id, 'Advisor');
        });

        return redirect()->route('login')->with('success', 'Advisor registered successfully. Please wait for approval.');
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

    public function pushNotificationManager($register_id, $type)
    {
        $manager = User::where(['guard' => 'manager'])->first();
        $notification = Notification::create([
            'type' => 'register_' . $type,
            'notifiable_type' => 'App\User',
            'notifiable_id' => $manager->id,
            'data' => json_encode([
                'register_id' => $register_id,
                'title' => 'New Message',
                'body' => 'New ' . $type . ' Register #' . $register_id,
            ]),
        ]);

        $pusher = new Pusher('1e58abe6fe45f3bd2e73', 'c4f5a1132840e7111aba', '1607529', [
            'cluster' => 'ap3'
        ]);
        $pusher->trigger('newRegister', 'new-register', [
            'title' => 'New Message',
            'body' => 'New ' . $type . ' Register #' . $register_id,
            'Notification_id' => $notification->id,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        // Validate the form data
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:25',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|regex:/^\+?[0-9]{1,3}[-. ]?\(?[0-9]{1,}\)?[-. ]?[0-9]{1,}[-. ]?[0-9]{1,}$/',
            'address' => 'required|string|max:255',
            'degree' => 'required|in:bachelor,master,phd',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'fields' => 'required|array',
            'fields.*' => 'exists:fields,id',
        ]);
        if ($validatedData->fails()) {
            return redirect()->route('getEditProfile')->with('error', $validatedData->errors()->first());
        }
        // Update the user details
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->save();

        // Update the profile details based on the user's guard
        $guard = null;
        if ($user->guard == 'trainee') {
            $guard = $user->trainee;
        } else if ($user->guard == 'advisor') {
            $guard = $user->advisor;
        }
        if ($guard) {
            $guard->phone = $request['phone'];
            $guard->address = $request['address'];
            $guard->degree = $request['degree'];
            $guard->fields()->attach($request['fields']);
            // Handle file upload if provided
            if ($request->hasFile('files')) {
                $file = $request->file('files');
                $filePath = $this->uploadFilesFireBase($request);
                $guard->files = $filePath;
            }
            $guard->save();
            return redirect()->route('getEditProfile')->with('success', 'Profile Update Successfully.');
        }
    }
}
