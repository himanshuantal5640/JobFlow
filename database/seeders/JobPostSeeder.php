<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;
use App\Models\User;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        $recruiter = User::where('role', 'recruiter')->first();
        if (!$recruiter) {
            $recruiter = User::create([
                'name' => 'John Doe',
                'email' => 'recruiter@jobflow.com',
                'password' => bcrypt('password'),
                'role' => 'recruiter',
                'company' => 'Google',
                'is_verified' => true,
            ]);
        }

        $jobs = [
            [
                'title' => 'Senior Software Engineer',
                'company' => 'Google',
                'department' => 'Engineering',
                'location' => 'Mountain View, CA',
                'work_mode' => 'Hybrid',
                'salary' => '$180K – $240K',
                'experience' => 'Senior',
                'job_type' => 'Full-time',
                'description' => 'Build next-gen search infrastructure at scale.',
                'skills' => json_encode(['React', 'TypeScript', 'GCP', 'Node.js']),
                'match' => 91,
                'status' => 'active',
                'recruiter_id' => $recruiter->id,
            ],
            [
                'title' => 'Product Designer',
                'company' => 'Stripe',
                'department' => 'Design',
                'location' => 'Remote',
                'work_mode' => 'Remote',
                'salary' => '$130K – $170K',
                'experience' => 'Mid',
                'job_type' => 'Full-time',
                'description' => 'Shape the future of payments UX for millions of businesses.',
                'skills' => json_encode(['Figma', 'Design Systems', 'Prototyping']),
                'match' => 78,
                'status' => 'active',
                'recruiter_id' => $recruiter->id,
            ],
            [
                'title' => 'DX Engineer',
                'company' => 'Vercel',
                'department' => 'Engineering',
                'location' => 'Remote',
                'work_mode' => 'Remote',
                'salary' => '$140K – $185K',
                'experience' => 'Mid',
                'job_type' => 'Full-time',
                'description' => 'Empower developers worldwide with great DX and edge infrastructure.',
                'skills' => json_encode(['Next.js', 'TypeScript', 'DevRel']),
                'match' => 87,
                'status' => 'active',
                'recruiter_id' => $recruiter->id,
            ],
            [
                'title' => 'ML Engineer',
                'company' => 'OpenAI',
                'department' => 'AI / ML',
                'location' => 'San Francisco, CA',
                'work_mode' => 'Hybrid',
                'salary' => '$200K – $320K',
                'experience' => 'Senior',
                'job_type' => 'Full-time',
                'description' => 'Train and deploy frontier models at scale for the benefit of humanity.',
                'skills' => json_encode(['Python', 'PyTorch', 'LLMs', 'CUDA']),
                'match' => 69,
                'status' => 'active',
                'recruiter_id' => $recruiter->id,
            ],
            [
                'title' => 'Frontend Architect',
                'company' => 'Airbnb',
                'department' => 'Engineering',
                'location' => 'Remote',
                'work_mode' => 'Remote',
                'salary' => '$175K – $220K',
                'experience' => 'Senior',
                'job_type' => 'Full-time',
                'description' => 'Lead the frontend architecture for our global hosting platform.',
                'skills' => json_encode(['React', 'GraphQL', 'TypeScript']),
                'match' => 94,
                'status' => 'active',
                'recruiter_id' => $recruiter->id,
            ]
        ];

        foreach ($jobs as $job) {
            JobPost::create($job);
        }
    }
}
