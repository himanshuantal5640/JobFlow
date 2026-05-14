<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobListingController extends Controller
{
    public function index(Request $request)
    {
        // If no jobs exist in the DB, let's create some sample jobs automatically
        if (JobPost::count() == 0) {
            $this->seedSampleJobs();
        }

        $query = JobPost::where('status', 'active');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('job_type')) {
            $query->whereIn('job_type', (array) $request->job_type);
        }

        if ($request->has('work_mode')) {
            $query->whereIn('work_mode', (array) $request->work_mode);
        }

        if ($request->has('experience')) {
            $query->whereIn('experience', (array) $request->experience);
        }

        if ($request->has('department')) {
            $query->whereIn('department', (array) $request->department);
        }

        // Salary Filter (assuming $request->salary is the minimum)
        if ($request->has('salary_min')) {
            // This is a bit tricky if salary is a string like "$130K - $170K"
            // For now, let's just filter if we have a numeric value
            // Ideally salary should be numeric in DB.
        }

        // Sorting
        $sort = $request->get('sort', 'recent');
        if ($sort === 'recent') {
            $query->latest();
        } elseif ($sort === 'salary_h') {
            $query->orderByRaw('CAST(salary AS UNSIGNED) DESC'); // Basic cast, might need adjustment
        } elseif ($sort === 'salary_l') {
            $query->orderByRaw('CAST(salary AS UNSIGNED) ASC');
        } elseif ($sort === 'company') {
            $query->orderBy('company');
        } else {
            $query->orderBy('match', 'desc');
        }

        $jobs = $query->paginate(6);

        // For the company strip
        $companies = JobPost::where('status', 'active')
            ->select('company')
            ->selectRaw('count(*) as count')
            ->groupBy('company')
            ->get();

        $resumes = Auth::check() 
            ? \App\Models\Resume::where('user_id', Auth::id())->latest()->get()
            : collect([]);

        return view('jobs.index', compact('jobs', 'companies', 'resumes'));
    }

    public function show($id)
    {
        $job = JobPost::withCount('applications')->findOrFail($id);

        if ($job->status !== 'active' && (! Auth::check() || (int) Auth::id() !== (int) $job->recruiter_id)) {
            abort(404);
        }

        if (request()->wantsJson()) {
            return response()->json($job);
        }

        return view('jobs.show', compact('job'));
    }

    public function apply($id)
    {
        $job = JobPost::findOrFail($id);
        if ($job->status !== 'active') {
            abort(404);
        }

        Application::firstOrCreate([
            'user_id' => auth()->id(),
            'job_post_id' => $id,
        ], [
            'status' => 'applied',
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Application submitted successfully!');
    }

    private function seedSampleJobs()
    {
        $sampleJobs = [
            [
                'title' => 'Frontend Architect',
                'company' => 'Airbnb',
                'department' => 'Engineering',
                'location' => 'Remote',
                'salary' => '$175K - $220K',
                'experience' => 'Staff / Lead',
                'work_mode' => 'Remote',
                'job_type' => 'Full-time',
                'skills' => ['React', 'GraphQL', 'TypeScript'],
                'match' => 94,
                'status' => 'active',
                'description' => 'We are looking for a Frontend Architect to lead our core UI infrastructure...',
                'recruiter_id' => 1,
            ],
            [
                'title' => 'Senior Software Engineer',
                'company' => 'Google',
                'department' => 'Engineering',
                'location' => 'Mountain View, CA',
                'salary' => '$160K - $200K',
                'experience' => 'Senior',
                'work_mode' => 'Hybrid',
                'job_type' => 'Full-time',
                'skills' => ['React', 'TypeScript', 'GCP', 'Node.js'],
                'match' => 88,
                'status' => 'active',
                'description' => 'Join our core infrastructure team to build scalable applications...',
                'recruiter_id' => 1,
            ],
            [
                'title' => 'AI Engineer',
                'company' => 'OpenAI',
                'department' => 'AI / ML',
                'location' => 'San Francisco, CA',
                'salary' => '$190K - $250K',
                'experience' => 'Senior',
                'work_mode' => 'On-site',
                'job_type' => 'Full-time',
                'skills' => ['Python', 'PyTorch', 'LLMs'],
                'match' => 92,
                'status' => 'active',
                'description' => 'Help us push the boundaries of artificial intelligence...',
                'recruiter_id' => 1,
            ],
            [
                'title' => 'Product Manager',
                'company' => 'Stripe',
                'department' => 'Product',
                'location' => 'Remote',
                'salary' => '$150K - $180K',
                'experience' => 'Mid',
                'work_mode' => 'Remote',
                'job_type' => 'Full-time',
                'skills' => ['Agile', 'Jira', 'Fintech'],
                'match' => 85,
                'status' => 'active',
                'description' => 'Lead product development for our core payment APIs...',
                'recruiter_id' => 1,
            ],
        ];

        // Ensure user ID 1 exists as a recruiter fallback
        $user = User::firstOrCreate(
            ['email' => 'admin@jobflow.com'],
            [
                'name' => 'Admin Recruiter',
                'password' => bcrypt('password'),
                'role' => 'recruiter',
                'company' => 'JobFlow',
            ]
        );

        foreach ($sampleJobs as &$job) {
            $job['recruiter_id'] = $user->id;
            JobPost::create($job);
        }
    }
}
