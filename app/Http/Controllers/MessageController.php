<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $candidates = [];
        if (Auth::user()->role === 'recruiter') {
            $company = Auth::user()->company;
            $candidates = Application::whereHas('jobPost', function ($q) use ($userId, $company) {
                if ($company) {
                    $q->where('company', $company);
                } else {
                    $q->where('recruiter_id', $userId);
                }
            })->with('user')->get()->pluck('user')->unique('id');
        }

        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'messages', 'jobPost'])
            ->orderByDesc('last_message_at')
            ->get();

        return view('messages.index', compact('conversations', 'candidates'));
    }

    public function show(Conversation $conversation)
    {
        $userId = Auth::id();
        if ($conversation->user_one_id !== $userId && $conversation->user_two_id !== $userId) {
            abort(403);
        }

        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'messages', 'jobPost'])
            ->orderByDesc('last_message_at')
            ->get();

        // Mark messages as read
        $conversation->messages()->where('sender_id', '!=', $userId)->update(['is_read' => true]);

        $candidates = [];
        if (Auth::user()->role === 'recruiter') {
            $company = Auth::user()->company;
            $candidates = Application::whereHas('jobPost', function ($q) use ($userId, $company) {
                if ($company) {
                    $q->where('company', $company);
                } else {
                    $q->where('recruiter_id', $userId);
                }
            })->with('user')->get()->pluck('user')->unique('id');
        }

        return view('messages.index', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
            'candidates' => $candidates
        ]);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $userId = Auth::id();
        if ($conversation->user_one_id !== $userId && $conversation->user_two_id !== $userId) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'content' => $validated['content'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back();
    }
}
