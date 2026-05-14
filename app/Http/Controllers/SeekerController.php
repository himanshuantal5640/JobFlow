<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeekerController extends Controller
{
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
