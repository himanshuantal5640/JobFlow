<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\RecruiterController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ResumeAnalyzerController;
use App\Http\Controllers\SeekerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    $jobs = \App\Models\JobPost::where('status', 'active')->latest()->take(2)->get();
    return view('pages.home', compact('jobs'));
});

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // General Dashboard (Role based redirect)
    Route::get('/dashboard', [DashboardController::class, 'seeker'])->name('dashboard');
    
    // Recruiter Specific
    Route::get('/recruiter/dashboard', [DashboardController::class, 'recruiter'])->name('recruiter.dashboard');
    Route::get('/recruiter/interviews', [RecruiterController::class, 'interviews'])->name('recruiter.interviews');
    Route::get('/recruiter/offers', [RecruiterController::class, 'offers'])->name('recruiter.offers');
    Route::get('/recruiter/profile', [RecruiterController::class, 'profile'])->name('recruiter.profile');
    Route::post('/recruiter/profile', [RecruiterController::class, 'updateProfile'])->name('recruiter.profile.update');
    Route::post('/recruiter/applications/{application}/status', [RecruiterController::class, 'updateApplicationStatus'])->name('recruiter.applications.status');
    Route::get('/recruiter/export/jobs', [RecruiterController::class, 'exportJobs'])->name('recruiter.export.jobs');
    Route::get('/recruiter/messages/start/{user}', [RecruiterController::class, 'startConversation'])->name('recruiter.messages.start');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'store'])->name('messages.store');

    // Jobs (Seeker Actions)
    Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{id}', [JobListingController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{id}/apply', [JobListingController::class, 'apply'])->name('jobs.apply');
    
    // Job Management (Recruiter)
    Route::post('/jobs-store', [JobController::class, 'store'])->name('jobs.store');

    // Tools
    Route::get('/resume-analyzer', [ResumeAnalyzerController::class, 'index'])->name('seeker.analyzer');
    Route::post('/resume-analyzer', [ResumeAnalyzerController::class, 'store'])->name('seeker.analyzer.store');
    Route::get('/seeker/interviews', [SeekerController::class, 'interviews'])->name('seeker.interviews');
    Route::get('/seeker/profile', [SeekerController::class, 'profile'])->name('seeker.profile');
    Route::post('/seeker/profile', [SeekerController::class, 'updateProfile'])->name('seeker.profile.update');

    // Profile Updates
    Route::post('/user/update-email', function (Request $request) {
        $request->validate(['email' => 'required|email|unique:users,email,'.auth()->id()]);
        $user = auth()->user();
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();
        $user->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->name('user.profile.update_email');

    Route::post('/applications/quick-add', [DashboardController::class, 'storeManualApplication'])->name('applications.quick-add');
});
