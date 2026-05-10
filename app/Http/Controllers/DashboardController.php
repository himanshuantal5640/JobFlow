<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;
use App\Models\Application;

class DashboardController extends Controller
{
    public function seeker()
    {
        // For seeker, show their applications and recommended jobs
        $applications = Application::where('user_id', Auth::id())->with('jobPost')->latest()->get();
        $recommendedJobs = JobPost::latest()->take(5)->get();

        return view('dashboard.seeker', compact('applications', 'recommendedJobs'));
    }

    public function recruiter()
    {
        $recruiterId = Auth::id();
        
        // Stats
        $activeJobsCount = JobPost::where('recruiter_id', $recruiterId)->count();
        $totalApplicants = Application::whereHas('jobPost', function($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })->count();
        
        $shortlistedCount = Application::where('status', 'shortlisted')
            ->whereHas('jobPost', function($query) use ($recruiterId) {
                $query->where('recruiter_id', $recruiterId);
            })->count();
            
        $interviewsCount = Application::where('status', 'interview')
            ->whereHas('jobPost', function($query) use ($recruiterId) {
                $query->where('recruiter_id', $recruiterId);
            })->count();

        // Job Postings
        $jobPostings = JobPost::where('recruiter_id', $recruiterId)
            ->withCount('applications')
            ->latest()
            ->get();

        // Recent Applicants
        $recentApplicants = Application::whereHas('jobPost', function($query) use ($recruiterId) {
                $query->where('recruiter_id', $recruiterId);
            })
            ->with(['user', 'jobPost'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.recruiter', compact(
            'activeJobsCount', 
            'totalApplicants', 
            'shortlistedCount', 
            'interviewsCount',
            'jobPostings',
            'recentApplicants'
        ));
    }
}
