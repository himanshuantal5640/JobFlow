@extends('layouts.dashboard')

@section('title', 'Browse Jobs — JobFlow')

@section('styles')
    @vite(['resources/css/jobs.css'])
    <style>
        /* Small overrides to fit with the dashboard layout if needed */
        .page-wrap { margin-left: 0; margin-top: 0; }
        .sidebar { z-index: 100; }
        .topbar { left: var(--sw); }
        body::before { display: none; } /* Use the one in jobs.css or layout */
    </style>
@endsection

@section('topbar_title', 'Browse Jobs')

@section('content')
<div class="jobs-page">
    <div class="page-wrap">
        <!-- Filter Sidebar -->
        <x-jobs.filter-sidebar />

        <!-- Main Content -->
        <main class="content">
            <!-- Trending -->
            <div class="trending-row a0">
                <span class="trending-label">🔥 Trending:</span>
                @foreach(['AI Engineer', 'React Developer', 'Product Manager', 'Remote DevOps'] as $trend)
                    <a href="{{ route('jobs.index', ['search' => $trend]) }}" class="trending-pill">{{ $trend }}</a>
                @endforeach
            </div>

            <!-- Companies Strip -->
            <div class="company-strip a0">
                <a href="{{ route('jobs.index') }}" class="company-chip {{ !request('company') ? 'active' : '' }}">
                    <span style="font-size:12px;font-weight:600;color:var(--text2);">All Companies</span>
                </a>
                @foreach($companies as $company)
                    <a href="{{ route('jobs.index', ['search' => $company->company]) }}" class="company-chip {{ request('search') == $company->company ? 'active' : '' }}">
                        <div class="cc-logo" style="background:var(--indigo-d); color:var(--indigo2);">{{ substr($company->company, 0, 1) }}</div>
                        <div>
                            <div class="cc-name">{{ $company->company }}</div>
                            <div class="cc-count">{{ $company->count }} openings</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Featured Banner (Static for now) -->
            <div class="featured-banner a0">
                <div class="fb-icon">⚡</div>
                <div class="fb-text">
                    <strong>AI-matched jobs for you</strong>
                    <p>Based on your profile and previous applications</p>
                </div>
                <button class="btn btn-ghost btn-sm">View Matches →</button>
            </div>

            <!-- Results Header -->
            <div class="results-header a1">
                <div class="results-count">
                    Showing <strong>{{ $jobs->total() }}</strong> jobs
                </div>
                <div class="results-controls">
                    <form action="{{ route('jobs.index') }}" method="GET" id="sortForm">
                        @foreach(request()->except('sort') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select class="sort-select" name="sort" onchange="this.form.submit()">
                            <option value="match" {{ request('sort') == 'match' ? 'selected' : '' }}>Best Match</option>
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Most Recent</option>
                            <option value="salary_h" {{ request('sort') == 'salary_h' ? 'selected' : '' }}>Salary: High → Low</option>
                            <option value="salary_l" {{ request('sort') == 'salary_l' ? 'selected' : '' }}>Salary: Low → High</option>
                        </select>
                    </form>
                    <div class="view-toggle">
                        <button class="view-btn active" id="gridBtn" title="Grid view">⊞</button>
                        <button class="view-btn" id="listBtn" title="List view">☰</button>
                    </div>
                </div>
            </div>

            <!-- Jobs Grid -->
            <div class="jobs-grid a2" id="jobsGrid">
                @forelse($jobs as $job)
                    <x-jobs.job-card :job="$job" />
                @empty
                    <div class="empty-state show">
                        <div style="font-size:48px;margin-bottom:16px;">🔍</div>
                        <div style="font-family:'Instrument Serif',serif;font-size:22px;color:var(--text);margin-bottom:8px;">No jobs found</div>
                        <div style="font-size:14px;color:var(--text2);margin-bottom:20px;">Try adjusting your filters or search query</div>
                        <a href="{{ route('jobs.index') }}" class="btn btn-ghost">Clear all filters</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="load-more-wrap">
                {{ $jobs->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>

        </main>
    </div>
</div>

<!-- Slideover Overlay (Job Details) -->
<div class="slideover-overlay" id="slideoverOverlay" onclick="closeSlideover(event)">
    <div class="slideover" id="slideover">
        <!-- Content will be loaded via AJAX -->
        <div id="slideoverContent"></div>
    </div>
</div>

<!-- Quick Apply Modal -->
<div class="modal-overlay" id="applyModal">
    <div class="modal">
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
                const content = `
                    <div class="so-header">
                        <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <div style="display:flex; align-items: center; gap: 12px;">
                                <div class="jc-logo" style="background:var(--indigo-d); color:var(--indigo2); width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:12px; font-size:20px;">${job.company[0]}</div>
                                <div>
                                    <div style="font-size:12px; color:var(--text3);">${job.company}</div>
                                    <div style="font-family:'Instrument Serif',serif; font-size:22px; color:var(--text);">${job.title}</div>
                                </div>
                            </div>
                            <div class="so-close" onclick="closeSlideover()">✕</div>
                        </div>
                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px;">
                            <span class="jc-tag">📍 ${job.location}</span>
                            <span class="jc-tag">💰 ${job.salary}</span>
                            <span class="jc-tag">⏱ ${job.job_type || 'Full-time'}</span>
                        </div>
                    </div>
                    <div class="so-body">
                        <div class="so-section">
                            <div class="so-section-title">Description</div>
                            <p class="so-desc">${job.description}</p>
                        </div>
                        <div class="so-section">
                            <div class="so-section-title">Skills Required</div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                ${JSON.parse(job.skills || '[]').map(s => `<span class="jc-tag">${s}</span>`).join('')}
                            </div>
                        </div>
                    </div>
                    <div class="so-footer">
                        <button class="btn btn-indigo" style="flex:1; justify-content:center;" onclick="openQuickApply(${job.id})">Quick Apply →</button>
                    </div>
                `;
                document.getElementById('slideoverContent').innerHTML = content;
                document.getElementById('slideoverOverlay').classList.add('open');
            });
    }

    function closeSlideover(e) {
        if (e && e.target !== document.getElementById('slideoverOverlay') && e.target.className !== 'so-close') return;
        document.getElementById('slideoverOverlay').classList.remove('open');
    }

    function openQuickApply(id) {
        // Simple modal content for now
        document.getElementById('applyModalContent').innerHTML = `
            <div class="modal-title">Apply for Job</div>
            <p class="modal-sub">You are applying for job #${id}</p>
            <form action="{{ route('jobs.store') }}" method="POST">
                @csrf
                <input type="hidden" name="job_post_id" value="${id}">
                <div class="mf">
                    <label class="ml">Your Resume</label>
                    <select class="mi" name="resume_id">
                        <option value="1">Primary_Resume.pdf</option>
                    </select>
                </div>
                <div class="mf">
                    <label class="ml">Cover Letter</label>
                    <textarea class="mi" name="cover_letter" rows="4"></textarea>
                </div>
                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="button" class="btn btn-ghost" style="flex:1;" onclick="closeApplyModal()">Cancel</button>
                    <button type="submit" class="btn btn-indigo" style="flex:1;">Submit Application</button>
                </div>
            </form>
        `;
        document.getElementById('applyModal').classList.add('open');
    }

    function closeApplyModal() {
        document.getElementById('applyModal').classList.remove('open');
    }

    function toggleBookmark(btn, id) {
        btn.classList.toggle('saved');
        // AJAX to save bookmark
    }

    // View toggle logic
    document.getElementById('gridBtn').onclick = () => {
        document.getElementById('jobsGrid').classList.remove('list-view');
        document.getElementById('gridBtn').classList.add('active');
        document.getElementById('listBtn').classList.remove('active');
    };
    document.getElementById('listBtn').onclick = () => {
        document.getElementById('jobsGrid').classList.add('list-view');
        document.getElementById('listBtn').classList.add('active');
        document.getElementById('gridBtn').classList.remove('active');
    };
</script>
@endsection
