<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeekerController extends Controller
{
    public function applications()
    {
        $user = Auth::user();
        $applications = Application::where('user_id', $user->id)
            ->with('jobPost')
            ->latest()
            ->get();

        return view('seeker.applications', compact('applications'));
    }

    public function showOffer(Application $application)
    {
        if ($application->user_id !== Auth::id() || $application->status !== 'offer') {
            abort(403);
        }
        return view('seeker.offer', compact('application'));
    }

    public function offerDecision(Request $request, Application $application)
    {
        if ($application->user_id !== Auth::id() || $application->status !== 'offer') {
            abort(403);
        }

        $request->validate([
            'decision' => 'required|in:accepted,rejected',
            'reason' => 'nullable|string|max:500'
        ]);
        
        $status = $request->decision === 'accepted' ? 'hired' : 'rejected';
        $updateData = ['status' => $status];
        
        if ($request->decision === 'rejected') {
            $updateData['rejection_reason'] = $request->reason;
        }

        $application->update($updateData);

        $msg = $request->decision === 'accepted' ? 'Congratulations! You have accepted the offer.' : 'You have declined the offer.';
        return redirect()->route('dashboard')->with('success', $msg);
    }

    public function interviews()
    {
        $user = Auth::user();
        $interviews = Application::where('user_id', $user->id)
            ->where('status', 'interview')
            ->with('jobPost')
            ->latest()
            ->get();

        return view('seeker.interviews', compact('interviews'));
    }

    public function profile()
    {
        return view('seeker.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
