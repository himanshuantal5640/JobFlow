@extends('layouts.dashboard')

@section('title', 'Recruiter Portal')

@section('head')
  @vite(['resources/css/recruiter.css'])
@endsection

@section('content')
    <header class="header">
      <div>
        <h1 class="page-title">Recruiter Dashboard</h1>
        <p class="page-sub">{{ now()->format('l, j F Y') }} · {{ auth()->user()->company ?? 'TechVenture Inc.' }}</p>
      </div>
      <div class="header-actions">
        <div class="icon-btn" style="background:var(--s2); padding:8px; border-radius:10px; cursor:pointer;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <div class="icon-btn" style="background:var(--s2); padding:8px; border-radius:10px; cursor:pointer; position:relative;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            @if($messagesCount > 0)<div style="position:absolute; top:8px; right:8px; width:8px; height:8px; background:var(--teal); border-radius:50%; border:2px solid var(--s2);"></div>@endif
        </div>
        <button class="btn btn-teal" onclick="openModal()">+ Post New Job</button>
      </div>
    </header>

    <!-- ── STATS GRID ── -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon-wrap" style="background:var(--teal-d); color:var(--teal);">📋</div>
        <div class="stat-value" style="color:var(--teal);" data-target="{{ $activeJobsCount }}">0</div>
        <div class="stat-label">Active Postings</div>
        <div class="stat-delta up">{{ $jobDeltaLabel }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap" style="background:var(--amber-d); color:var(--amber);">📥</div>
        <div class="stat-value" style="color:var(--amber);" data-target="{{ $totalApplicants }}">0</div>
        <div class="stat-label">Total Applicants</div>
        <div class="stat-delta up">{{ $applicantDeltaLabel }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap" style="background:var(--sky-d); color:var(--sky);">⭐</div>
        <div class="stat-value" style="color:var(--sky);" data-target="{{ $shortlistedCount }}">0</div>
        <div class="stat-label">Shortlisted</div>
        <div class="stat-delta up">{{ $shortlistedDeltaLabel }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap" style="background:var(--violet-d); color:var(--violet);">🗓</div>
        <div class="stat-value" style="color:var(--violet);" data-target="{{ $interviewsCount }}">0</div>
        <div class="stat-label">Interviews Scheduled</div>
        <div class="stat-delta up">{{ $interviewDeltaLabel }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon-wrap" style="background:var(--rose-d); color:var(--rose);">⚡</div>
        @if($avgDaysToHire !== null)
          <div class="stat-value" style="color:var(--rose);" data-target="{{ $avgDaysToHire }}">0</div>
        @else
          <div class="stat-value" style="color:var(--rose);">—</div>
        @endif
        <div class="stat-label">Avg. Days to Hire</div>
        <div class="stat-delta down">{{ $hireDeltaLabel ?? '-3 vs last quarter' }}</div>
      </div>
    </div>

    <!-- ── ACTIVE JOB POSTINGS ── -->
    <div class="section-header" id="section-job-postings">
      <h2 class="section-title">Job Postings</h2>
      <div style="display:flex; gap:10px; align-items:center;">
        <div style="display:flex; background:var(--s2); padding:4px; border-radius:10px; margin-right:12px;">
            <a href="?status=active" class="btn {{ $statusFilter === 'active' ? 'btn-teal' : 'btn-ghost' }}" style="padding:6px 12px; font-size:11px; border-radius:8px;">Active ({{ $activeJobsCount }})</a>
            <a href="?status=draft" class="btn {{ $statusFilter === 'draft' ? 'btn-teal' : 'btn-ghost' }}" style="padding:6px 12px; font-size:11px; border-radius:8px;">Drafts ({{ $draftJobsCount }})</a>
            <a href="?status=all" class="btn {{ $statusFilter === 'all' ? 'btn-teal' : 'btn-ghost' }}" style="padding:6px 12px; font-size:11px; border-radius:8px;">All</a>
        </div>
        <a href="{{ route('recruiter.export.jobs') }}" class="btn btn-ghost" style="font-size:12px;">Export</a>
        <button class="btn btn-teal" style="font-size:12px;" onclick="openModal()">+ Post Job</button>
      </div>
    </div>

    <div class="table-container">
      <div class="table-header">
        <div>Job Title</div>
        <div>Status</div>
        <div>Posted</div>
        <div>Applicants</div>
        <div>Fill Rate</div>
        <div>Actions</div>
      </div>

      @forelse($jobPostings as $job)
      @php
        $fill = $job->fillRatePercent();
        $colors = ['var(--teal)', 'var(--amber)', 'var(--violet)', 'var(--sky)'];
        $color = $colors[$loop->index % 4];
      @endphp
      <div class="table-row">
        <div class="job-cell">
          <div class="job-logo" style="background:{{ $color }};">{{ substr($job->title, 0, 1) }}</div>
          <div class="job-info">
            <div class="name">{{ $job->title }}</div>
            <div class="meta">{{ $job->department ?? 'General' }} · {{ $job->work_mode }} · {{ $job->salary ?? '$120K–$160K' }}</div>
          </div>
        </div>
        <div>
          <span class="status-badge {{ $job->status === 'active' ? 'active' : 'draft' }}">
            ● {{ ucfirst($job->status) }}
          </span>
        </div>
        <div>
          <div class="posted-date">{{ $job->created_at->format('M d, Y') }}</div>
          <div class="posted-ago">{{ $job->created_at->diffForHumans() }}</div>
        </div>
        <div>
          <div class="applicants-count">{{ $job->applications_count }}</div>
          <div class="shortlisted-sub">{{ $job->shortlisted_applications_count ?? 0 }} shortlisted</div>
        </div>
        <div class="progress-wrap">
          <div class="progress-track">
            <div class="progress-fill" style="width:{{ $fill }}%; background:{{ $color }};"></div>
          </div>
          <span class="progress-val">{{ $fill }}%</span>
        </div>
        <div>
          <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-teal" style="padding:6px 14px; font-size:12px;">View</a>
        </div>
      </div>
      @empty
      <div style="padding:40px; text-align:center; color:var(--text3);">No job postings found.</div>
      @endforelse
    </div>

    <!-- ── ANALYTICS ── -->
    <div class="pipeline-grid">
      <div class="card">
        <div class="section-header" style="margin-top:0;">
          <h2 class="section-title">Hiring Funnel — All Roles</h2>
          <button class="btn btn-ghost" style="font-size:11px; padding:4px 10px;">This Month ▾</button>
        </div>
        <div class="funnel">
          <div class="funnel-item">
            <div class="funnel-fill" style="width:100%; background:var(--teal);"></div>
            <div class="funnel-info">
              <div class="funnel-name">Total Applied</div>
              <div style="font-size:11px; color:var(--text3);">All active roles combined</div>
            </div>
            <div class="funnel-count">{{ $totalApplicants }}</div>
            <div class="funnel-pct" style="color:var(--teal);">100%</div>
          </div>
          <div class="funnel-item">
            <div class="funnel-fill" style="width:{{ $funnelScreenedPct }}%; background:var(--sky);"></div>
            <div class="funnel-info">
              <div class="funnel-name">Screened / Reviewed</div>
              <div style="font-size:11px; color:var(--text3);">Passed initial ATS filter</div>
            </div>
            <div class="funnel-count">{{ $screenedCount }}</div>
            <div class="funnel-pct" style="color:var(--sky);">{{ $funnelScreenedPct }}%</div>
          </div>
          <div class="funnel-item">
            <div class="funnel-fill" style="width:{{ $funnelShortlistedPct }}%; background:var(--amber);"></div>
            <div class="funnel-info">
              <div class="funnel-name">Shortlisted</div>
              <div style="font-size:11px; color:var(--text3);">Manually reviewed & approved</div>
            </div>
            <div class="funnel-count">{{ $shortlistedCount }}</div>
            <div class="funnel-pct" style="color:var(--amber);">{{ $funnelShortlistedPct }}%</div>
          </div>
          <div class="funnel-item">
            <div class="funnel-fill" style="width:{{ $funnelInterviewedPct }}%; background:var(--violet);"></div>
            <div class="funnel-info">
              <div class="funnel-name">Interviewed</div>
              <div style="font-size:11px; color:var(--text3);">At least one round complete</div>
            </div>
            <div class="funnel-count">{{ $interviewsCount }}</div>
            <div class="funnel-pct" style="color:var(--violet);">{{ $funnelInterviewedPct }}%</div>
          </div>
          <div class="funnel-item">
            <div class="funnel-fill" style="width:{{ $funnelOfferPct }}%; background:var(--rose);"></div>
            <div class="funnel-info">
              <div class="funnel-name">Offers Extended</div>
              <div style="font-size:11px; color:var(--text3);">Offer letter sent</div>
            </div>
            <div class="funnel-count">{{ $offersSentCount }}</div>
            <div class="funnel-pct" style="color:var(--rose);">{{ $funnelOfferPct }}%</div>
          </div>
          <div class="funnel-item">
            <div class="funnel-fill" style="width:{{ $funnelHiredPct }}%; background:var(--teal);"></div>
            <div class="funnel-info">
              <div class="funnel-name">Hired 🏆</div>
              <div style="font-size:11px; color:var(--text3);">Offer accepted & onboarding</div>
            </div>
            <div class="funnel-count">{{ $hiredCount }}</div>
            <div class="funnel-pct" style="color:var(--teal);">{{ $funnelHiredPct }}%</div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="section-header" style="margin-top:0;">
          <h2 class="section-title">Top Sources</h2>
          <button class="btn btn-ghost" style="font-size:11px; padding:4px 10px;">Details →</button>
        </div>
        <div style="display:flex; flex-direction:column; gap:16px;">
          <div class="progress-wrap" style="flex-direction:column; align-items:flex-start; gap:6px;">
            <div style="display:flex; justify-content:space-between; width:100%; font-size:12px; font-weight:600;">
              <span>JobFlow Platform</span>
              <span>98</span>
            </div>
            <div class="progress-track" style="width:100%;"><div class="progress-fill" style="width:98%; background:var(--teal);"></div></div>
          </div>
          <div class="progress-wrap" style="flex-direction:column; align-items:flex-start; gap:6px;">
            <div style="display:flex; justify-content:space-between; width:100%; font-size:12px; font-weight:600;">
              <span>LinkedIn</span>
              <span>72</span>
            </div>
            <div class="progress-track" style="width:100%;"><div class="progress-fill" style="width:72%; background:var(--sky);"></div></div>
          </div>
          <div class="progress-wrap" style="flex-direction:column; align-items:flex-start; gap:6px;">
            <div style="display:flex; justify-content:space-between; width:100%; font-size:12px; font-weight:600;">
              <span>Indeed</span>
              <span>54</span>
            </div>
            <div class="progress-track" style="width:100%;"><div class="progress-fill" style="width:54%; background:var(--amber);"></div></div>
          </div>
        </div>

        <div style="margin-top:24px; padding-top:24px; border-top:1px solid var(--border);">
            <div class="section-title" style="font-size:16px; margin-bottom:12px;">Avg. Time to Hire by Role</div>
            @foreach($hireByRole as $row)
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                <span style="font-size:11px; color:var(--text3); width:100px;">{{ $row['label'] }}</span>
                <div class="progress-track" style="flex:1;"><div class="progress-fill" style="width:{{ $row['pct'] }}%; background:var(--teal);"></div></div>
                <span style="font-size:11px; font-weight:700; color:var(--teal);">{{ $row['days'] }}d</span>
            </div>
            @endforeach
        </div>
      </div>
    </div>

    <!-- ── APPLICANTS ── -->
    <div class="section-header" id="section-applicants">
      <h2 class="section-title">Applicants</h2>
      <div style="display:flex; gap:10px;">
        <div class="btn btn-ghost" style="padding:4px; gap:0;">
            <button class="btn btn-ghost" style="border:none; background:transparent; font-size:11px; padding:6px 12px;">All ({{ $totalApplicants }})</button>
            <button class="btn btn-ghost" style="border:none; background:transparent; font-size:11px; padding:6px 12px;">Shortlisted ({{ $shortlistedCount }})</button>
        </div>
        <button class="btn btn-ghost" style="font-size:11px; padding:6px 12px;">Sort ↕</button>
      </div>
    </div>

    <div class="applicants-grid">
      @forelse($recentApplicants as $application)
      <div class="applicant-card">
        <div class="ac-header">
          <div class="ac-user">
            <div class="ac-avatar">{{ substr($application->user->name, 0, 1) }}</div>
            <div>
              <div class="ac-name">{{ $application->user->name }}</div>
              <div class="ac-role">{{ Str::limit($application->jobPost->title, 20) }}</div>
            </div>
          </div>
          <div class="ac-actions">
            @if($application->status !== 'shortlisted')
            <form method="POST" action="{{ route('recruiter.applications.status', $application) }}">
              @csrf
              <input type="hidden" name="status" value="shortlisted">
              <button type="submit" class="ac-btn approve" title="Shortlist">⭐</button>
            </form>
            @endif
            <a href="{{ route('recruiter.messages.start', ['user' => $application->user->id, 'job_id' => $application->job_post_id]) }}" class="ac-btn" style="text-decoration:none;">💬</a>
            <button class="ac-btn reject">✕</button>
          </div>
        </div>
        <div class="ac-tags">
          @foreach($application->jobPost->skillsList() as $skill)
          <span class="ac-tag">{{ $skill }}</span>
          @endforeach
        </div>
        <div class="ac-match-row">
          <div class="ac-match-label">
            <span>Match</span>
            <span style="color:var(--teal);">{{ (int)$application->jobPost->match }}%</span>
          </div>
          <div class="progress-track"><div class="progress-fill" style="width:{{ (int)$application->jobPost->match }}%; background:var(--teal);"></div></div>
        </div>
        <div class="ac-footer">
          <div class="ac-time">Applied {{ $application->created_at->diffForHumans() }}</div>
          <span class="status-badge active" style="font-size:10px; padding:2px 8px;">{{ ucfirst($application->status) }}</span>
        </div>
      </div>
      @empty
      <div style="grid-column: span 3; padding:40px; text-align:center; color:var(--text3);">No applicants found.</div>
      @endforelse
    </div>

  <!-- ── MODAL ── -->
  <div class="modal-overlay" id="postModal">
    <div class="modal">
      <div class="modal-header">
        <h2 class="modal-title">Post a New Job</h2>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>

      <form action="{{ route('jobs.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">Job Title</label>
          <input type="text" name="title" class="form-input @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Senior Backend Engineer" required>
          @error('title') <div style="color:var(--rose); font-size:11px; margin-top:4px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
          <div class="form-group">
            <label class="form-label">Department</label>
            <select name="department" class="form-input">
              <option>Engineering</option>
              <option>Product</option>
              <option>Design</option>
              <option>Marketing</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Work Mode</label>
            <select name="work_mode" class="form-input">
              <option>Remote</option>
              <option>Hybrid</option>
              <option>On-site</option>
            </select>
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
          <div class="form-group">
            <label class="form-label">Min Salary (USD)</label>
            <input type="number" name="min_salary" class="form-input @error('min_salary') is-invalid @enderror" value="{{ old('min_salary') }}" placeholder="120000">
            @error('min_salary') <div style="color:var(--rose); font-size:11px; margin-top:4px;">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Max Salary (USD)</label>
            <input type="number" name="max_salary" class="form-input @error('max_salary') is-invalid @enderror" value="{{ old('max_salary') }}" placeholder="160000">
            @error('max_salary') <div style="color:var(--rose); font-size:11px; margin-top:4px;">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Experience Required</label>
          <select name="experience" class="form-input">
            <option>0-1 years (Junior)</option>
            <option>2-3 years</option>
            <option>4-6 years</option>
            <option>7-10 years</option>
            <option>10+ years</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Skills (comma-separated)</label>
          <input type="text" name="skills" class="form-input" placeholder="React, Node.js, TypeScript">
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-input @error('description') is-invalid @enderror" rows="4" placeholder="Describe the role...">{{ old('description') }}</textarea>
          @error('description') <div style="color:var(--rose); font-size:11px; margin-top:4px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:16px; margin-top:12px;">
          <button type="submit" name="intent" value="draft" class="btn btn-ghost" style="justify-content:center;">Save as Draft</button>
          <button type="submit" name="intent" value="publish" class="btn btn-teal" style="justify-content:center;">Publish Job ✦</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  function openModal() {
    document.getElementById('postModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  
  @if($errors->any())
    openModal();
  @endif

  @if(session('success'))
    // You could add a toast notification here
  @endif
  function closeModal() {
    document.getElementById('postModal').classList.remove('open');
    document.body.style.overflow = 'auto';
  }

  // Handle clicking outside modal to close
  document.getElementById('postModal').addEventListener('click', (e) => {
    if (e.target.id === 'postModal') closeModal();
  });

  // Simple number count animation
  document.querySelectorAll('.stat-value').forEach(el => {
    const target = parseInt(el.dataset.target);
    if (isNaN(target)) return;
    let current = 0;
    const increment = Math.max(1, Math.ceil(target / 50));
    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        el.textContent = target;
        clearInterval(timer);
      } else {
        el.textContent = current;
      }
    }, 20);
  });
</script>
@endsection
