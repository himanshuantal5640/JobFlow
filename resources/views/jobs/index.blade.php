@extends('layouts.dashboard')

@section('title', 'Browse Jobs')

@section('styles')
    @vite(['resources/css/jobs.css', 'resources/css/job-details.css'])
    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--text); }
        .jobs-page { display: grid; grid-template-columns: 280px 1fr; gap: 32px; }
        .filter-sidebar { background: var(--s1); border-radius: 24px; padding: 24px; border: 1px solid var(--border); height: fit-content; position: sticky; top: 0; }
        .job-card { background: var(--s1); border-radius: 24px; padding: 24px; border: 1px solid var(--border); transition: all 0.3s; }
        .job-card:hover { border-color: var(--teal); transform: translateY(-5px); }
        .trending-pill { background: var(--s2); color: var(--text2); padding: 6px 16px; border-radius: 20px; font-size: 13px; text-decoration: none; transition: all 0.2s; border: 1px solid var(--border); }
        .trending-pill:hover { background: var(--s3); color: var(--teal); border-color: var(--teal); }
        .company-chip { background: var(--s1); border: 1px solid var(--border); border-radius: 16px; padding: 12px 20px; text-decoration: none; color: inherit; display: flex; align-items: center; gap: 12px; transition: all 0.2s; }
        .company-chip:hover, .company-chip.active { background: var(--s2); border-color: var(--teal); }
    </style>
@endsection

@section('content')
<header class="header" style="margin-bottom: 32px;">
    <div>
        <h1 class="page-title">Browse Opportunities</h1>
        <p class="page-sub">Explore jobs that match your expertise and career goals.</p>
    </div>
    <div class="header-actions">
        <div class="icon-btn" style="background:var(--s2); padding:8px; border-radius:10px; cursor:pointer;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <div class="view-toggle" style="display: flex; background: var(--s2); border-radius: 12px; padding: 4px;">
            <button class="icon-btn active" id="gridBtn" style="border:none; background:transparent;">⊞</button>
            <button class="icon-btn" id="listBtn" style="border:none; background:transparent;">☰</button>
        </div>
    </div>
</header>

<div class="jobs-page">
    <!-- Filter Sidebar -->
    <x-jobs.filter-sidebar />

    <!-- Main Content -->
    <div class="jobs-content">
        <!-- Trending -->
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
            <span style="font-size:13px; font-weight:700; color:var(--text3); text-transform:uppercase;">🔥 Trending:</span>
            @foreach(['AI Engineer', 'React Developer', 'Product Manager', 'Remote DevOps'] as $trend)
                <a href="{{ route('jobs.index', ['search' => $trend]) }}" class="trending-pill">{{ $trend }}</a>
            @endforeach
        </div>

        <!-- Companies Strip -->
        <div style="display:flex; gap:12px; margin-bottom:32px; overflow-x:auto; padding-bottom:8px;">
            <a href="{{ route('jobs.index') }}" class="company-chip {{ !request('search') ? 'active' : '' }}">
                <span style="font-size:13px; font-weight:600;">All Companies</span>
            </a>
            @foreach($companies as $company)
                <a href="{{ route('jobs.index', ['search' => $company->company]) }}" class="company-chip {{ request('search') == $company->company ? 'active' : '' }}">
                    <div style="width:32px; height:32px; border-radius:8px; background:var(--teal); color:#060912; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px;">{{ substr($company->company, 0, 1) }}</div>
                    <div>
                        <div style="font-size:13px; font-weight:600;">{{ $company->company }}</div>
                        <div style="font-size:11px; color:var(--text3);">{{ $company->count }} jobs</div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Jobs Grid -->
        <div class="jobs-grid" id="jobsGrid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px;">
            @forelse($jobs as $job)
                @php
                    $colors = ['var(--teal)', 'var(--sky)', 'var(--violet)', 'var(--amber)'];
                    $color = $colors[$loop->index % 4];
                @endphp
                <div class="job-card" onclick="openJob({{ $job->id }})" style="cursor:pointer; position:relative;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
                        <div style="width:48px; height:48px; border-radius:14px; background:{{ $color }}; color:#060912; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:20px;">{{ substr($job->company, 0, 1) }}</div>
                        <div style="background:rgba(45, 212, 191, 0.1); color:var(--teal); padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;">⚡ {{ $job->match }}% Match</div>
                    </div>
                    <div style="margin-bottom:4px; font-size:12px; color:var(--text3); font-weight:600;">{{ $job->company }}</div>
                    <div style="font-family:'DM Serif Display',serif; font-size:20px; color:var(--text); margin-bottom:12px;">{{ $job->title }}</div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px;">
                        @foreach(array_slice($job->skillsList(), 0, 3) as $skill)
                            <span style="background:var(--s2); border:1px solid var(--border); padding:4px 10px; border-radius:8px; font-size:11px; color:var(--text2);">{{ $skill }}</span>
                        @endforeach
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding-top:16px; border-top:1px solid var(--border);">
                        <div style="font-weight:700; color:var(--text);">{{ $job->salary }}</div>
                        <div style="font-size:11px; color:var(--text3);">{{ $job->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; padding:60px; text-align:center; color:var(--text3);">
                    <div style="font-size:48px; margin-bottom:16px;">🔍</div>
                    <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:8px;">No jobs found</h3>
                    <p>Try adjusting your search or filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div style="margin-top:40px;">
            {{ $jobs->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Slideover Overlay (Job Details) -->
<div class="modal-overlay" id="slideoverOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(8px); z-index:1000; justify-content:flex-end;">
    <div id="slideover" style="width:600px; height:100vh; background:var(--bg); border-left:1px solid var(--border); padding:40px; overflow-y:auto; transform:translateX(100%); transition:transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
        <div id="slideoverContent"></div>
    </div>
</div>

<!-- Quick Apply Modal -->
<div class="modal-overlay" id="applyModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(8px); z-index:1100; align-items:center; justify-content:center;">
    <div class="modal" style="background:var(--s1); border:1px solid var(--border); border-radius:24px; width:500px; padding:32px; max-width:90vw;">
        <div id="applyModalContent"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openJob(id) {
        fetch(`/jobs/${id}`)
            .then(res => res.json())
            .then(job => {
                const skillsHtml = (job.skills || []).map(s => `<span style="background:var(--s2); border:1px solid var(--border); padding:6px 12px; border-radius:10px; font-size:12px;">${s}</span>`).join('');
                const content = `
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:32px;">
                        <div style="width:64px; height:64px; border-radius:18px; background:var(--teal); color:#060912; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:28px;">${job.company[0]}</div>
                        <button onclick="closeSlideover()" style="background:none; border:none; color:var(--text3); font-size:24px; cursor:pointer;">✕</button>
                    </div>
                    <div style="margin-bottom:8px; font-size:14px; color:var(--text3); font-weight:600;">${job.company}</div>
                    <h2 style="font-family:'DM Serif Display',serif; font-size:32px; color:var(--text); margin-bottom:16px;">${job.title}</h2>
                    <div style="display:flex; gap:12px; margin-bottom:32px;">
                        <span style="font-size:13px; color:var(--text2);">📍 ${job.location}</span>
                        <span style="font-size:13px; color:var(--text2);">💰 ${job.salary}</span>
                        <span style="font-size:13px; color:var(--text2);">⏱ ${job.job_type || 'Full-time'}</span>
                    </div>
                    <div style="margin-bottom:32px; padding-top:32px; border-top:1px solid var(--border);">
                        <h3 style="font-family:'DM Serif Display',serif; font-size:20px; color:var(--text); margin-bottom:12px;">About the role</h3>
                        <p style="color:var(--text2); line-height:1.7; font-size:14px;">${job.description}</p>
                    </div>
                    <div style="margin-bottom:40px;">
                        <h3 style="font-family:'DM Serif Display',serif; font-size:20px; color:var(--text); margin-bottom:16px;">Required Skills</h3>
                        <div style="display:flex; flex-wrap:wrap; gap:10px;">${skillsHtml}</div>
                    </div>
                    <div style="position:sticky; bottom:0; padding-top:20px; background:var(--bg); display:flex; gap:16px;">
                        <button class="btn btn-ghost" style="flex:1; justify-content:center;" onclick="closeSlideover()">Close</button>
                        <button class="btn btn-teal" style="flex:2; justify-content:center;" onclick="openQuickApply(${job.id})">Apply Now ✦</button>
                    </div>
                `;
                document.getElementById('slideoverContent').innerHTML = content;
                document.getElementById('slideoverOverlay').style.display = 'flex';
                setTimeout(() => document.getElementById('slideover').style.transform = 'translateX(0)', 10);
            });
    }

    function closeSlideover() {
        document.getElementById('slideover').style.transform = 'translateX(100%)';
        setTimeout(() => document.getElementById('slideoverOverlay').style.display = 'none', 400);
    }

    function openQuickApply(id) {
        currentApplyJobId = id;
        fetch(`/jobs/${id}`)
            .then(res => res.json())
            .then(job => {
                const userName = '{{ auth()->user()->name }}';
                document.getElementById('applyModalContent').innerHTML = `
                    <div style="text-align:center; margin-bottom:32px;">
                        <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:8px;">Quick Apply</h3>
                        <p style="font-size:13px; color:var(--text3);">Submit your profile to ${job.company}</p>
                    </div>
                    <div style="background:var(--s2); border:1px solid var(--border); border-radius:16px; padding:20px; margin-bottom:24px;">
                        <div style="font-size:12px; color:var(--text3); margin-bottom:4px;">Position</div>
                        <div style="font-weight:700; color:var(--text);">${job.title}</div>
                    </div>
                    <div style="margin-bottom:24px;">
                        <label style="display:block; font-size:12px; font-weight:700; color:var(--text3); text-transform:uppercase; margin-bottom:8px;">Select Resume</label>
                        <select style="width:100%; background:var(--s2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); font-family:inherit;">
                            <option>✓ ${userName}_Resume_v3.pdf (94% ATS)</option>
                            <option>${userName}_Resume_v2.pdf (78% ATS)</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:12px;">
                        <button class="btn btn-ghost" style="flex:1; justify-content:center;" onclick="closeApplyModal()">Cancel</button>
                        <button class="btn btn-teal" style="flex:2; justify-content:center;" onclick="submitApplication()">Submit Application</button>
                    </div>
                `;
                document.getElementById('applyModal').style.display = 'flex';
            });
    }

    function closeApplyModal() {
        document.getElementById('applyModal').style.display = 'none';
    }

    let currentApplyJobId = null;

    function submitApplication() {
        fetch(`/jobs/${currentApplyJobId}/apply`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById('applyModalContent').innerHTML = `
                    <div style="text-align:center; padding:20px 0;">
                        <div style="width:64px; height:64px; border-radius:50%; background:rgba(45, 212, 191, 0.1); color:var(--teal); display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 20px;">✓</div>
                        <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:8px;">Applied Successfully!</h3>
                        <p style="font-size:14px; color:var(--text3); margin-bottom:32px;">Your application has been sent to the recruiter.</p>
                        <button class="btn btn-teal" style="width:100%; justify-content:center;" onclick="window.location.reload()">Back to Dashboard</button>
                    </div>
                `;
            }
        });
    }
</script>
@endsection
