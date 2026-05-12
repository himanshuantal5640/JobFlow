<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResumeAnalyzerController extends Controller
{
    public function index()
    {
        // Fetch real active jobs to display in the "Jobs That Match" section
        $matchedJobs = \App\Models\JobPost::where('status', 'active')
            ->orderBy('match', 'desc')
            ->limit(6)
            ->get();
            
        // We'll pass the user's name for the generated documents
        $user = auth()->user();

        return view('seeker.analyzer', compact('matchedJobs', 'user'));
    }
}
