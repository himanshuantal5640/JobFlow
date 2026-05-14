<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumeAnalyzerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $matchedJobs = JobPost::where('status', 'active')
            ->orderBy('match', 'desc')
            ->limit(6)
            ->get();
            
        $resumes = Resume::where('user_id', $user->id)->latest()->get();
        $latestResume = $resumes->first();

        return view('seeker.analyzer', compact('matchedJobs', 'user', 'resumes', 'latestResume'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $user = Auth::user();
        $file = $request->file('resume');
        $fileName = $file->getClientOriginalName();
        
        // In a real app, we would use $file->store('resumes');
        // For this demo, we'll just save the name and simulate the path
        $filePath = 'resumes/' . $fileName;

        // Simulate ATS Scoring
        $score = rand(75, 98);
        $details = [
            'keywords' => rand(80, 99),
            'formatting' => rand(85, 95),
            'impact' => rand(70, 95),
            'skills_found' => ['React', 'TypeScript', 'Node.js', 'AWS', 'Docker', 'PostgreSQL'],
            'skill_gaps' => ['Kubernetes', 'Rust', 'System Design'],
        ];

        $resume = Resume::create([
            'user_id' => $user->id,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'score' => $score,
            'details' => $details,
        ]);

        return response()->json([
            'success' => true,
            'resume' => $resume,
            'message' => 'Resume analyzed and saved successfully.'
        ]);
    }
}
