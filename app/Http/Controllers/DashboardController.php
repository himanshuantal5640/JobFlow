<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function seeker()
    {
        if (Auth::user()->role === 'recruiter') {
            return redirect()->route('recruiter.dashboard');
        }

        $applications = Application::where('user_id', Auth::id())->with('jobPost')->latest()->get();
        $recommendedJobs = JobPost::where('status', 'active')->latest()->take(12)->get();
        
        $messagesCount = Message::whereHas('conversation', function ($q) {
            $q->where('user_one_id', Auth::id())->orWhere('user_two_id', Auth::id());
        })->where('sender_id', '!=', Auth::id())->where('is_read', false)->count();

        return view('dashboard.seeker', compact('applications', 'recommendedJobs', 'messagesCount'));
    }

    public function recruiter(Request $request)
    {
        if (Auth::user()->role !== 'recruiter') {
            return redirect()->route('dashboard');
        }

        $recruiterId = Auth::id();
        $search = trim((string) $request->get('q', ''));
        $jobScope = JobPost::query()->where('recruiter_id', $recruiterId);

        $activeJobsCount = (clone $jobScope)->where('status', 'active')->count();
        $draftJobsCount = (clone $jobScope)->where('status', 'draft')->count();
        $totalMyJobs = $activeJobsCount + $draftJobsCount;

        $statusFilter = $request->get('status');
        if (!$statusFilter) {
            $statusFilter = $activeJobsCount === 0 && $draftJobsCount > 0 ? 'all' : 'active';
        }
        if (! in_array($statusFilter, ['active', 'draft', 'all'], true)) {
            $statusFilter = 'active';
        }

        $applicationsBase = Application::query()->whereHas('jobPost', function ($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        });

        $totalApplicants = (clone $applicationsBase)->count();

        $shortlistedCount = (clone $applicationsBase)->where('status', 'shortlisted')->count();
        $interviewsCount = (clone $applicationsBase)->where('status', 'interview')->count();
        $offersSentCount = (clone $applicationsBase)->where('status', 'offer')->count();
        $hiredCount = (clone $applicationsBase)->where('status', 'hired')->count();

        $screenedCount = (clone $applicationsBase)->whereIn('status', ['shortlisted', 'interview', 'offer', 'hired', 'rejected'])->count();

        $messagesCount = Message::whereHas('conversation', function ($q) use ($recruiterId) {
            $q->where('user_one_id', $recruiterId)->orWhere('user_two_id', $recruiterId);
        })->where('sender_id', '!=', $recruiterId)->where('is_read', false)->count();

        $startThisMonth = now()->startOfMonth();
        $startLastMonth = now()->subMonthNoOverflow()->startOfMonth();
        $endLastMonth = now()->subMonthNoOverflow()->endOfMonth();

        $jobsThisMonth = (clone $jobScope)->where('status', 'active')->where('created_at', '>=', $startThisMonth)->count();
        $jobsLastMonth = (clone $jobScope)->where('status', 'active')->whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $jobDelta = $jobsThisMonth - $jobsLastMonth;

        $thisWeek = now()->subDays(7);
        $prevWeekStart = now()->subDays(14);

        $appsThisWeek = (clone $applicationsBase)->where('created_at', '>=', $thisWeek)->count();
        $appsPrevWeek = (clone $applicationsBase)->whereBetween('created_at', [$prevWeekStart, $thisWeek])->count();
        $appsDelta = $appsThisWeek - $appsPrevWeek;

        $shortlistThisWeek = (clone $applicationsBase)->where('status', 'shortlisted')->where('updated_at', '>=', $thisWeek)->count();
        $shortlistPrevWeek = (clone $applicationsBase)->where('status', 'shortlisted')->whereBetween('updated_at', [$prevWeekStart, $thisWeek])->count();
        $shortlistDelta = $shortlistThisWeek - $shortlistPrevWeek;

        $interviewThisWeek = (clone $applicationsBase)->where('status', 'interview')->where('updated_at', '>=', $thisWeek)->count();
        $interviewPrevWeek = (clone $applicationsBase)->where('status', 'interview')->whereBetween('updated_at', [$prevWeekStart, $thisWeek])->count();
        $interviewDelta = $interviewThisWeek - $interviewPrevWeek;

        $offerApps = (clone $applicationsBase)->where('status', 'offer')->get();
        $avgDaysToHire = $offerApps->isEmpty()
            ? null
            : (int) round($offerApps->avg(fn (Application $a) => max(1, $a->created_at->diffInDays($a->updated_at))));

        $offersLast90 = (clone $applicationsBase)->where('status', 'offer')->where('updated_at', '>=', now()->subDays(90))->get();
        $offersPrev90 = (clone $applicationsBase)->where('status', 'offer')
            ->where('updated_at', '>=', now()->subDays(180))
            ->where('updated_at', '<', now()->subDays(90))
            ->get();
        $avgLast90 = $offersLast90->isEmpty() ? null : $offersLast90->avg(fn (Application $a) => max(1, $a->created_at->diffInDays($a->updated_at)));
        $avgPrev90 = $offersPrev90->isEmpty() ? null : $offersPrev90->avg(fn (Application $a) => max(1, $a->created_at->diffInDays($a->updated_at)));
        $hireDeltaLabel = null;
        if ($avgLast90 !== null && $avgPrev90 !== null && $offersPrev90->isNotEmpty()) {
            $diff = (int) round($avgLast90 - $avgPrev90);
            $hireDeltaLabel = $diff <= 0
                ? '↓ '.abs($diff).' vs prior quarter'
                : '↑ +'.$diff.' vs prior quarter';
        }

        $jobPostingsQuery = JobPost::query()
            ->where('recruiter_id', $recruiterId)
            ->withCount([
                'applications',
                'applications as shortlisted_applications_count' => function ($q) {
                    $q->where('status', 'shortlisted');
                },
                'applications as pipeline_applications_count' => function ($q) {
                    $q->whereIn('status', ['shortlisted', 'interview', 'offer']);
                },
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('company', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            });

        if ($statusFilter === 'all') {
            $jobPostingsQuery->whereIn('status', ['active', 'draft']);
        } elseif ($statusFilter === 'draft') {
            $jobPostingsQuery->where('status', 'draft');
        } else {
            $jobPostingsQuery->where('status', 'active');
        }

        $jobPostings = $jobPostingsQuery->latest()->get();

        $appStatusFilter = $request->get('app_status');
        if ($appStatusFilter && ! in_array($appStatusFilter, ['applied', 'shortlisted', 'interview', 'offer', 'rejected'], true)) {
            $appStatusFilter = null;
        }

        $recentApplicants = Application::whereHas('jobPost', function ($query) use ($recruiterId) {
            $query->where('recruiter_id', $recruiterId);
        })
            ->with(['user', 'jobPost'])
            ->when($appStatusFilter, fn ($q) => $q->where('status', $appStatusFilter))
            ->latest()
            ->take(24)
            ->get();

        $hireByRole = [];
        foreach (JobPost::where('recruiter_id', $recruiterId)->where('status', 'active')->get() as $job) {
            $jobOffers = $job->applications()->where('status', 'offer')->get();
            if ($jobOffers->isEmpty()) {
                continue;
            }
            $avgDays = (int) round($jobOffers->avg(fn (Application $a) => max(1, $a->created_at->diffInDays($a->updated_at))));
            $hireByRole[] = [
                'label' => $job->department ?: Str::limit($job->title, 24),
                'days' => $avgDays,
                'pct' => min(100, $avgDays * 3),
            ];
        }
        $hireByRole = array_slice($hireByRole, 0, 6);

        $funnelTotal = max(1, $totalApplicants);
        $funnelScreenedPct = (int) min(100, round(($screenedCount / $funnelTotal) * 100));
        $funnelShortlistedPct = (int) min(100, round(($shortlistedCount / $funnelTotal) * 100));
        $funnelInterviewedPct = (int) min(100, round(($interviewsCount / $funnelTotal) * 100));
        $funnelOfferPct = (int) min(100, round(($offersSentCount / $funnelTotal) * 100));
        $funnelHiredPct = (int) min(100, round(($hiredCount / $funnelTotal) * 100));

        return view('dashboard.recruiter', [
            'activeJobsCount' => $activeJobsCount,
            'draftJobsCount' => $draftJobsCount,
            'totalMyJobs' => $totalMyJobs,
            'totalApplicants' => $totalApplicants,
            'shortlistedCount' => $shortlistedCount,
            'interviewsCount' => $interviewsCount,
            'offersSentCount' => $offersSentCount,
            'hiredCount' => $hiredCount,
            'messagesCount' => $messagesCount,
            'screenedCount' => $screenedCount,
            'jobDeltaLabel' => $this->formatDeltaLabel($jobDelta, 'month'),
            'applicantDeltaLabel' => $this->formatDeltaLabel($appsDelta, 'week'),
            'shortlistedDeltaLabel' => $this->formatDeltaLabel($shortlistDelta, 'week'),
            'interviewDeltaLabel' => $this->formatDeltaLabel($interviewDelta, 'week'),
            'avgDaysToHire' => $avgDaysToHire,
            'hireDeltaLabel' => $hireDeltaLabel,
            'jobPostings' => $jobPostings,
            'recentApplicants' => $recentApplicants,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'appStatusFilter' => $appStatusFilter,
            'hireByRole' => $hireByRole,
            'funnelScreenedPct' => $funnelScreenedPct,
            'funnelShortlistedPct' => $funnelShortlistedPct,
            'funnelInterviewedPct' => $funnelInterviewedPct,
            'funnelOfferPct' => $funnelOfferPct,
            'funnelHiredPct' => $funnelHiredPct,
        ]);
    }

    private function formatDeltaLabel(int $delta, string $period): string
    {
        if ($delta > 0) {
            return '↑ +'.$delta.' this '.$period;
        }
        if ($delta < 0) {
            return '↓ '.abs($delta).' this '.$period;
        }

        return 'No change this '.$period;
    }

    public function storeManualApplication(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'status' => 'required|in:applied,review,interview,offer,rejected,shortlisted',
            'salary' => 'nullable|string|max:255',
            'applied_at' => 'nullable|date',
        ]);

        $statusMap = [
            'review' => 'applied',
            'applied' => 'applied',
            'shortlisted' => 'shortlisted',
            'interview' => 'interview',
            'offer' => 'offer',
            'rejected' => 'rejected',
        ];
        $status = $statusMap[$validated['status']] ?? 'applied';

        $title = mb_strtolower(trim($validated['title']));
        $company = mb_strtolower(trim($validated['company']));

        $jobPost = JobPost::query()
            ->whereRaw('LOWER(title) = ?', [$title])
            ->where(function ($q) use ($company) {
                $q->whereRaw('LOWER(company) = ?', [$company])
                    ->orWhereRaw('LOWER(company) LIKE ?', ['%'.$company.'%']);
            })
            ->first();

        if (! $jobPost) {
            return back()
                ->withInput()
                ->withErrors([
                    'title' => 'No listing matched this title and company. Use the exact title and company from Browse Jobs, or apply from the job page.',
                ]);
        }

        Application::updateOrCreate(
            ['user_id' => Auth::id(), 'job_post_id' => $jobPost->id],
            ['status' => $status]
        );

        return back()->with('success', 'Application added to your pipeline.');
    }
}
