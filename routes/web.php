<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobListingController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/dashboard', [DashboardController::class, 'seeker'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/recruiter/dashboard', [DashboardController::class, 'recruiter'])
    ->middleware(['auth'])
    ->name('recruiter.dashboard');

// Auth Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Job Routes
Route::post('/jobs', [JobController::class, 'store'])->middleware('auth')->name('jobs.store');
Route::get('/jobs', [JobListingController::class, 'index'])->middleware('auth')->name('jobs.index');
Route::get('/jobs/{id}', [JobListingController::class, 'show'])->middleware('auth')->name('jobs.show');
Route::post('/jobs/{id}/apply', [JobListingController::class, 'apply'])->middleware('auth')->name('jobs.apply');
Route::get('/resume-analyzer', [\App\Http\Controllers\ResumeAnalyzerController::class, 'index'])->middleware('auth')->name('seeker.analyzer');

// Forgot Password (Simplified)
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/user/update-email', function (Request $request) {
    $request->validate(['email' => 'required|email|unique:users,email,' . auth()->id()]);
    $user = auth()->user();
    $user->email = $request->email;
    $user->email_verified_at = null; // Mark as unverified again
    $user->save();
    $user->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware('auth')->name('user.profile.update_email');

Route::get('/', function () {

$jobs = [
(object)[
'title'=>'Software Engineer',
'company'=>'Google',
'skills'=>json_encode(['React','Node']),
'match'=>87
],
(object)[
'title'=>'Designer',
'company'=>'Stripe',
'skills'=>json_encode(['Figma']),
'match'=>73
]
];

return view('pages.home', compact('jobs'));
});