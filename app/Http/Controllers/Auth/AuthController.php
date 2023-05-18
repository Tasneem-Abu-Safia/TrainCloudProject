<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvisorRegistrationRequest;
use App\Http\Requests\TraineeRegistrationRequest;
use App\Models\Field;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
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

    }

    public function postAdvisor(AdvisorRegistrationRequest $request)
    {

    }
}
