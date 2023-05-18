<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
Route::get('/', [AuthController::class, 'getLogin'])->name('login');
Route::post('/postLogin', [AuthController::class, 'postLogin'])->name('LoginPost');

Route::get('/register', [AuthController::class, 'getSignup'])->name('register');
Route::post('/register/trainee', [AuthController::class, 'postTrainee'])->name('postTrainee');
Route::post('/register/advisor', [AuthController::class, 'postAdvisor'])->name('postAdvisor');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');

});
