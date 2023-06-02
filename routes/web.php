<?php

use App\Http\Controllers\AdvisorControllers\TaskController;
use App\Http\Controllers\AdvisorControllers\TaskSubmissionController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ManagerControllers\CourseTraineeController;
use App\Http\Controllers\ManagerControllers\AdvisorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ManagerControllers\CourseController;
use App\Http\Controllers\ManagerControllers\FieldController;
use App\Http\Controllers\ManagerControllers\TraineeController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TraineeManagement\TraineeManagementController;
use App\Models\Notification;
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
    Route::get('openEditProfile', [AuthController::class, function () {
        $fields = \App\Models\Field::all();
        return view('auth.updateProfile', compact('fields'));
    }])->name('getEditProfile');
    Route::put('updateProfile', [AuthController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');

    //Notification
    Route::put('notificationsRead/{id}', [NotificationController::class, 'markAsRead'])->name('notificationsRead');
    Route::put('notifications/markAllRead', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('notificationsCount', function () {
        return Notification::ByLevel()->whereNull('read_at')->count();
    })->name('notifications.count');
    Route::resource('notifications', NotificationController::class);
    Route::get('notificationsLatest', [NotificationController::class, 'getNotifications'])->name('getNotifications');


    //Course
    Route::resource('courses', CourseController::class);
    Route::resource('tasks', TaskController::class);
    Route::get('/allTrainee/{courseId}', [CourseController::class, 'getAllTrainee'])->name('getAllTrainee');
    Route::get('/allTask/{courseId}', [CourseController::class, 'getAllTask'])->name('getAllTask');
    Route::resource('taskSubmissions', TaskSubmissionController::class);
    Route::get('/tasks/{task}/submissions', [TaskController::class, 'getTaskSubmissions'])->name('tasks.submissions');
    Route::get('/attendance/{course}', [CourseController::class, 'showAttendance'])->name('attendance.show');
    Route::get('/course-trainees/requests', [CourseTraineeController::class, 'indexRequest'])->name('course-traineesRequests');
    Route::get('/course-trainees/active/{courseId}/{traineeId}', [CourseTraineeController::class, 'active']);
    Route::get('/course-trainees/inactive/{courseId}/{traineeId}', [CourseTraineeController::class, 'inactive']);

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

        Route::get('/course-trainees', [CourseTraineeController::class, 'index'])->name('course-trainees');
        Route::delete('/course-trainees/destroy/{courseId}/{traineeId}', [CourseTraineeController::class, 'destroy']);

        //billing
        Route::get('billing-active/{billing_id}', [BillingController::class, 'active'])->name('billingActive');
        Route::get('billing-deActive/{billing_id}', [BillingController::class, 'deActive'])->name('billingDeActive');
        Route::get('billing-requests', [BillingController::class, 'requests'])->name('billings.requests');

    });


    Route::middleware(['trainee'])->group(function () {
        Route::get('/allCourses', [TraineeManagementController::class, 'getAllCourses'])->name('getAllCourses');
        Route::get('trainee/course/{course_id}/show', [TraineeManagementController::class, 'showCourse'])->name('showCourse');
        Route::post('courses/enroll/{course_id}', [TraineeManagementController::class, 'join'])->name('courses.enroll');
        Route::get('joinedCourses', [TraineeManagementController::class, 'joinedCourses'])->name('joined.courses');
        Route::get('myTasks', [TraineeManagementController::class, 'myTasks'])->name('myTasks');
        Route::get('courses/{course}/joined-details', [TraineeManagementController::class, 'courseJoinedDetails'])->name('courseJoinedDetails');
        Route::post('/trainee/submit-task/{taskId}', [TraineeManagementController::class, 'submitTask'])->name('trainee.submitTask');
        Route::get('/trainee/meetingAdvisor', [MeetingController::class, 'courseAdvisors'])->name('trainee.advisors');
        Route::put('/meetings/{meeting}/cancel', [MeetingController::class, 'cancel'])->name('meetings.cancel');


    });


    Route::middleware(['advisor'])->group(function () {
        Route::put('/submissions/{submission}/mark', [TaskSubmissionController::class, 'updateMark'])->name('submissions.update.mark');
        Route::post('/meetings/send-email', [MeetingController::class, 'sendEmail'])->name('sendEmail');
        Route::post('/meetings/updateStatus', [MeetingController::class, 'updateStatus'])->name('updateMeetingStatus');
        Route::get('/advisor/sendEmail', [AdvisorController::class, function () {
            $courses = \App\Models\Course::all();
            return view('layouts.sendEmail', compact('courses'));
        }])->name('sendEmailPage');
        Route::post('advisor/trainee/sendEmail', [AdvisorController::class, 'sendEmail'])->name('sendEmail');

    });
    Route::resource('meetings', MeetingController::class);
    Route::resource('billings', BillingController::class);

});
Route::post('/addAttendance', [TraineeManagementController::class, 'addAttendance'])->name('addAttendance');
