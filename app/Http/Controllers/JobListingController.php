<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class JobListingController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPost::where('status', 'active');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('job_type')) {
            $query->whereIn('job_type', (array)$request->job_type);
        }

        if ($request->has('work_mode')) {
            $query->whereIn('work_mode', (array)$request->work_mode);
        }

        if ($request->has('experience')) {
            $query->whereIn('experience', (array)$request->experience);
        }

        if ($request->has('department')) {
            $query->whereIn('department', (array)$request->department);
        }

        // Salary Filter (assuming $request->salary is the minimum)
        if ($request->has('salary_min')) {
            // This is a bit tricky if salary is a string like "$130K - $170K"
            // For now, let's just filter if we have a numeric value
            // Ideally salary should be numeric in DB.
        }

        // Sorting
        $sort = $request->get('sort', 'match');
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

        return view('jobs.index', compact('jobs', 'companies'));
    }

    public function show($id)
    {
        $job = JobPost::findOrFail($id);
        return response()->json($job);
    }
}
