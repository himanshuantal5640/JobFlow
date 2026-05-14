<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'recruiter') {
            abort(403);
        }

        $intent = $request->input('intent', 'publish');
        if (! in_array($intent, ['draft', 'publish'], true)) {
            $intent = 'publish';
        }

        $isDraft = $intent === 'draft';

        $rules = [
            'intent' => 'required|in:draft,publish',
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'work_mode' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'description' => $isDraft ? 'nullable|string' : 'required|string|min:10',
            'skills' => 'nullable|string|max:2000',
            'job_type' => 'nullable|string|max:255',
        ];

        if ($isDraft) {
            $rules['min_salary'] = 'nullable|integer|min:0';
            $rules['max_salary'] = 'nullable|integer|min:0';
        } else {
            $rules['min_salary'] = 'required|integer|min:1000';
            $rules['max_salary'] = 'required|integer|min:1000|gte:min_salary';
        }

        $validated = $request->validate($rules);

        $min = array_key_exists('min_salary', $validated) && $validated['min_salary'] !== null
            ? (int) $validated['min_salary']
            : null;
        $max = array_key_exists('max_salary', $validated) && $validated['max_salary'] !== null
            ? (int) $validated['max_salary']
            : null;

        if ($min !== null && $max !== null && $max < $min) {
            return back()->withInput()->withErrors([
                'max_salary' => 'Maximum salary must be greater than or equal to minimum salary.',
            ]);
        }

        if ($min !== null xor $max !== null) {
            return back()->withInput()->withErrors([
                'min_salary' => 'Enter both minimum and maximum salary, or leave both empty for drafts.',
            ]);
        }

        $skillsRaw = trim((string) ($validated['skills'] ?? ''));
        $skills = $skillsRaw === ''
            ? []
            : array_values(array_filter(array_map('trim', explode(',', $skillsRaw))));

        $location = $validated['work_mode'];
        $salaryDisplay = ($min && $max) ? JobPost::formatSalaryRange($min, $max) : null;

        JobPost::create([
            'title' => $validated['title'],
            'company' => Auth::user()->company ?? Auth::user()->name,
            'department' => $validated['department'],
            'location' => $location,
            'work_mode' => $validated['work_mode'],
            'min_salary' => $min,
            'max_salary' => $max,
            'salary' => $salaryDisplay,
            'description' => $validated['description'] ?? '',
            'experience' => $validated['experience'],
            'job_type' => $validated['job_type'] ?? 'Full-time',
            'recruiter_id' => Auth::id(),
            'status' => $isDraft ? 'draft' : 'active',
            'match' => 0,
            'skills' => $skills,
        ]);

        $message = $isDraft ? 'Job saved as draft.' : 'Job published successfully.';

        return back()->with('success', $message);
    }

    public function publish(JobPost $job)
    {
        if ($job->recruiter_id !== Auth::id()) {
            abort(403);
        }

        $job->update(['status' => 'active']);

        return back()->with('success', 'Job published successfully!');
    }
}
