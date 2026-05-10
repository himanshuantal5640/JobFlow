<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'location' => 'required|string',
            'salary' => 'nullable|string',
            'description' => 'nullable|string',
            'experience' => 'nullable|string',
            'work_mode' => 'nullable|string',
            'job_type' => 'nullable|string',
        ]);

        JobPost::create([
            'title' => $request->title,
            'company' => Auth::user()->company ?? Auth::user()->name,
            'department' => $request->department,
            'location' => $request->location,
            'salary' => $request->salary,
            'description' => $request->description,
            'experience' => $request->experience,
            'work_mode' => $request->work_mode,
            'job_type' => $request->job_type,
            'recruiter_id' => Auth::id(),
            'status' => 'active',
            'match' => rand(70, 95),
            'skills' => json_encode(['Laravel', 'Tailwind', 'PHP']),
        ]);

        return back()->with('success', 'Job posted successfully!');
    }
}
