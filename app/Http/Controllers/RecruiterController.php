<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruiterController extends Controller
{
    public function profile()
    {
        if (Auth::user()->role !== 'recruiter') {
            return redirect()->route('dashboard');
        }

        return view('recruiter.profile', ['user' => Auth::user()]);
    }

    public function interviews()
    {
        if (Auth::user()->role !== 'recruiter') abort(403);
        $interviews = Application::whereHas('jobPost', function($q) {
            $q->where('recruiter_id', Auth::id());
        })->where('status', 'interview')->with(['user', 'jobPost'])->latest()->get();
        
        return view('recruiter.interviews', compact('interviews'));
    }

    public function offers()
    {
        if (Auth::user()->role !== 'recruiter') abort(403);
        $offers = Application::whereHas('jobPost', function($q) {
            $q->where('recruiter_id', Auth::id());
        })->where('status', 'offer')->with(['user', 'jobPost'])->latest()->get();
        
        return view('recruiter.offers', compact('offers'));
    }

    public function startConversation(Request $request, User $user)
    {
        if (Auth::user()->role !== 'recruiter') abort(403);
        
        $conversation = \App\Models\Conversation::firstOrCreate([
            'user_one_id' => min(Auth::id(), $user->id),
            'user_two_id' => max(Auth::id(), $user->id),
            'job_post_id' => $request->job_id
        ]);

        return redirect()->route('messages.show', $conversation);
    }

    public function updateProfile(Request $request)
    {
        if (Auth::user()->role !== 'recruiter') {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
    public function updateApplicationStatus(Request $request, Application $application)
    {
        if (Auth::user()->role !== 'recruiter') {
            abort(403);
        }

        $this->authorizeApplication($application);

        $validated = $request->validate([
            'status' => 'required|in:applied,shortlisted,interview,offer,rejected,hired',
        ]);

        $application->update(['status' => $validated['status']]);

        return back()->with('success', 'Application status updated.');
    }

    public function exportJobs(Request $request): StreamedResponse
    {
        if (Auth::user()->role !== 'recruiter') {
            abort(403);
        }

        $recruiterId = Auth::id();

        $filename = 'jobflow-jobs-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($recruiterId) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Title', 'Company', 'Department', 'Work mode', 'Location', 'Status', 'Salary', 'Applicants', 'Posted']);

            JobPost::query()
                ->where('recruiter_id', $recruiterId)
                ->withCount('applications')
                ->orderByDesc('created_at')
                ->cursor()
                ->each(function (JobPost $job) use ($out) {
                    fputcsv($out, [
                        $job->title,
                        $job->company,
                        $job->department ?? '',
                        $job->work_mode ?? '',
                        $job->location ?? '',
                        $job->status,
                        $job->salary ?? '',
                        $job->applications_count,
                        $job->created_at?->toDateTimeString() ?? '',
                    ]);
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function authorizeApplication(Application $application): void
    {
        $application->loadMissing('jobPost');
        if (! $application->jobPost || (int) $application->jobPost->recruiter_id !== (int) Auth::id()) {
            abort(403);
        }
    }
}
