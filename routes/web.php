<?php

use App\Http\Controllers\ManagerControllers\AdvisorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ManagerControllers\CourseController;
use App\Http\Controllers\ManagerControllers\FieldController;
use App\Http\Controllers\ManagerControllers\TraineeController;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;
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
Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->name('forgotPassword');

Route::get('/register', [AuthController::class, 'getSignup'])->name('register');
Route::post('/register/trainee', [AuthController::class, 'postTrainee'])->name('postTrainee');
Route::post('/register/advisor', [AuthController::class, 'postAdvisor'])->name('postAdvisor');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
    Route::get('openChangePass', [AuthController::class, function () {
        return view('auth.changePassword');
    }])->name('getChangePassword');
    Route::put('changePass', [AuthController::class, 'changePass'])->name('changePassword');
    Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');

    Route::middleware(['manager'])->group(function () {
        // Trainee routes
        Route::get('traineeRequests', [TraineeController::class, 'traineeRequests'])->name('traineeRequests');
        Route::resource('trainees', TraineeController::class);
        Route::get('trainee-active/{trainee_id}', [TraineeController::class, 'active'])->name('traineeActive');
        Route::get('trainee-deActive/{trainee_id}', [TraineeController::class, 'deActive'])->name('traineeDeActive');

        // Advisor routes
        Route::get('advisorRequests', [AdvisorController::class, 'advisorRequests'])->name('advisorRequests');
        Route::resource('advisors', AdvisorController::class);
        Route::get('advisor-active/{advisor_id}', [AdvisorController::class, 'active'])->name('advisorActive');
        Route::get('advisor-deActive/{advisor_id}', [AdvisorController::class, 'deActive'])->name('advisorDeActive');

        // Field routes
        Route::put('fields/{field}', [FieldController::class, 'update'])->name('fields.update');
        Route::resource('fields', FieldController::class);
        Route::get('/advisor-fields', [FieldController::class, 'getAdvisors'])->name('advisorFields');

        //Course
        Route::resource('courses', CourseController::class);

        //Notification
        Route::put('notificationsRead/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('notificationsCount', function () {
            return Notification::whereNull('read_at')->count();
        })->name('notifications.count');
        Route::resource('notifications', NotificationController::class);
        Route::get('notificationsLatest', [NotificationController::class, 'getNotifications'])->name('getNotifications');
    });


    Route::middleware(['trainee'])->group(function () {
    });


    Route::middleware(['advisor'])->group(function () {
    });
});
