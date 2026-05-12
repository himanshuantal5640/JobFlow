@extends('layouts.dashboard')

@section('title', 'Browse Jobs — JobFlow')

@section('styles')
    @vite(['resources/css/jobs.css', 'resources/css/job-details.css'])
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
                    <div class="so-footer" style="display:flex; gap:10px;">
                        <a href="/jobs/${job.id}" class="btn btn-ghost" style="flex:1; justify-content:center;">Full Details ↗</a>
                        <button class="btn btn-indigo" style="flex:1.5; justify-content:center;" onclick="openQuickApply(${job.id})">Quick Apply →</button>
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

    let modalStep = 1;
    let currentApplyJobId = null;

    function openQuickApply(id) {
        currentApplyJobId = id;
        fetch(`/jobs/${id}`)
            .then(res => res.json())
            .then(job => {
                const userName = '{{ auth()->user()->name ?? "User" }}';
                const userEmail = '{{ auth()->user()->email ?? "email@example.com" }}';
                const csrfToken = '{{ csrf_token() }}';
                
                document.getElementById('applyModalContent').innerHTML = `
    <div class="modal-head">
      <div>
        <div class="modal-job-title">Apply to ${job.company || 'Company'}</div>
        <div class="modal-company">${job.title} · ${job.department || 'Engineering'} · ${job.location || 'Remote'}</div>
      </div>
      <div class="modal-x" onclick="closeApplyModal()">✕</div>
    </div>

    <div class="modal-steps" id="modalSteps">
      <div class="ms-item active" id="step1item"><div class="ms-circle">1</div><div class="ms-label">Resume</div></div>
      <div class="ms-line" id="line12"></div>
      <div class="ms-item" id="step2item"><div class="ms-circle">2</div><div class="ms-label">Details</div></div>
      <div class="ms-line" id="line23"></div>
      <div class="ms-item" id="step3item"><div class="ms-circle">3</div><div class="ms-label">Review</div></div>
    </div>

    <div id="step1content">
      <div class="mf">
        <label class="ml">Choose Resume</label>
        <select class="mi">
          <option>✓ ${userName}_Resume_v3.pdf (ATS Score: 94%)</option>
          <option>${userName}_Resume_v2.pdf (ATS Score: 78%)</option>
        </select>
      </div>
      <div class="mf">
        <div class="upload-box" onclick="alert('Opening file picker…')">
          <div class="ub-icon">📄</div>
          <p>Upload a different resume</p>
          <span>PDF or DOCX · Max 5MB</span>
        </div>
      </div>
      <div class="mf">
        <label class="ml">Cover Letter <span style="color:var(--text3);font-weight:400;">(recommended)</span></label>
        <textarea class="mi" rows="3" style="resize:vertical;" placeholder="Tell ${job.company || 'the company'} why you're a perfect fit..."></textarea>
      </div>
      <div class="ats-chip">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="var(--green)" stroke-width="1.3"/><path d="M5 8.5L7 10.5L11 6" stroke="var(--green)" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span style="font-size:12px;color:var(--green);">Your resume matches <strong>${job.match || 91}%</strong> of this role's requirements!</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost" style="flex:1;justify-content:center;" onclick="closeApplyModal()">Cancel</button>
        <button type="button" class="btn btn-primary" style="flex:1.5;justify-content:center;" onclick="goModalStep(2)">Continue →</button>
      </div>
    </div>

    <div id="step2content" style="display:none;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" class="mf">
        <div><label class="ml">LinkedIn Profile</label><input class="mi" type="url" value="linkedin.com/in/you"></div>
        <div><label class="ml">Portfolio / GitHub</label><input class="mi" type="url" value="github.com/you"></div>
      </div>
      <div class="mf"><label class="ml">Expected Salary</label><input class="mi" type="text" value="${job.salary || '$150,000'}"></div>
      <div class="mf">
        <label class="ml">Notice Period</label>
        <select class="mi"><option>2 weeks</option><option selected>4 weeks</option></select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost" style="flex:0.8;justify-content:center;" onclick="goModalStep(1)">← Back</button>
        <button type="button" class="btn btn-primary" style="flex:1.5;justify-content:center;" onclick="goModalStep(3)">Review Application →</button>
      </div>
    </div>

    <div id="step3content" style="display:none;">
      <div style="background:var(--surface2);border:1px solid var(--border2);border-radius:var(--rl);padding:16px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:11px;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border);">
          <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:white;">${userName[0]}</div>
          <div><div style="font-weight:700;">${userName}</div><div style="font-size:12px;color:var(--text3);">${userEmail}</div></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:12px;">
          <div>📄 <strong>Resume:</strong> v3</div><div>💰 <strong>Expected:</strong> ${job.salary || '$150K'}</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost" style="flex:0.8;justify-content:center;" onclick="goModalStep(2)">← Edit</button>
        <button type="button" class="btn btn-primary" style="flex:1.5;justify-content:center;" onclick="submitApplication()">Submit Application ✦</button>
      </div>
    </div>

    <div id="successContent" style="display:none;text-align:center;padding:14px 0;">
      <div style="width:64px;height:64px;border-radius:50%;background:var(--green-dim);border:2px solid var(--green);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 16px;">✓</div>
      <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:700;margin-bottom:8px;">Application Submitted!</div>
      <p style="font-size:13px;color:var(--text2);margin-bottom:20px;">The recruiting team will review your application soon.</p>
      <button type="button" class="btn btn-ghost" onclick="closeApplyModal()">Close</button>
    </div>
                `;
                document.getElementById('applyModal').classList.add('open');
                goModalStep(1);
            });
    }

    function closeApplyModal() {
        document.getElementById('applyModal').classList.remove('open');
    }

    function closeModalOutside(e) {
        if (e.target === document.getElementById('applyModal')) closeApplyModal();
    }

    function goModalStep(step) {
      modalStep = step;
      ['step1content','step2content','step3content','successContent'].forEach((id,i) => {
        if(document.getElementById(id)) document.getElementById(id).style.display = (i === step - 1) ? 'block' : 'none';
      });
      for (let i = 1; i <= 3; i++) {
        const item = document.getElementById('step' + i + 'item');
        if(!item) continue;
        const circle = item.querySelector('.ms-circle');
        item.classList.remove('active','done');
        if (i < step) { item.classList.add('done'); circle.innerHTML = '✓'; }
        else if (i === step) { item.classList.add('active'); circle.textContent = i; }
        else { circle.textContent = i; }
      }
      if (document.getElementById('line12')) {
        document.getElementById('line12').classList.toggle('done', step > 1);
        document.getElementById('line23').classList.toggle('done', step > 2);
      }
    }

    function submitApplication() {
        fetch(`/jobs/${currentApplyJobId}/apply`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                ['step1content','step2content','step3content'].forEach(id => {
                  if(document.getElementById(id)) document.getElementById(id).style.display = 'none';
                });
                document.getElementById('successContent').style.display = 'block';
                if(document.getElementById('modalSteps')) document.getElementById('modalSteps').style.display = 'none';
            }
        });
    }

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeApplyModal();
    });

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
