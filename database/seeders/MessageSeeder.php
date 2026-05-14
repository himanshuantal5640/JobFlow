<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $jobs = \App\Models\JobPost::all();

        if ($users->count() < 2 || $jobs->count() === 0) {
            return;
        }

        $recruiter = $users->where('role', 'recruiter')->first() ?? $users->first();
        $seeker = $users->where('role', 'seeker')->first() ?? $users->last();

        $conversation = \App\Models\Conversation::create([
            'user_one_id' => $recruiter->id,
            'user_two_id' => $seeker->id,
            'job_post_id' => $jobs->first()->id,
            'last_message_at' => now(),
        ]);

        \App\Models\Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $recruiter->id,
            'content' => "Hi {$seeker->name}, we reviewed your application for {$jobs->first()->title} and we're impressed! Would you like to chat?",
        ]);

        \App\Models\Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $seeker->id,
            'content' => "Hello! Yes, I'd love to learn more about the role. When are you available?",
        ]);

        \App\Models\Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $recruiter->id,
            'content' => "How about tomorrow at 2 PM?",
        ]);
    }
}
