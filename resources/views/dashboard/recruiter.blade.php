<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JobFlow — Recruiter Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;0,900;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/recruiter.css', 'resources/css/animations.css', 'resources/js/app.js'])
</head>
<body class="recruiter-dashboard-body">

<!-- ═══════════════ SIDEBAR ═══════════════ -->
<aside class="sidebar">
  <a href="/" class="sidebar-logo">
    <div class="logo-gem">J</div>
    <span class="logo-word">Job<em>Flow</em></span>
  </a>

  <div class="sidebar-role-tag">Recruiter Portal</div>

  <div class="nav-group">
    <div class="nav-group-label">Overview</div>
    <a class="nav-item active" href="#">
      <div class="nav-icon">⊞</div> Dashboard
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">📋</div> My Job Posts
      <span class="nav-badge">{{ $activeJobsCount }}</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">👥</div> All Applicants
      <span class="nav-badge amber">{{ $totalApplicants }}</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">⭐</div> Shortlisted
      <span class="nav-badge">{{ $shortlistedCount }}</span>
    </a>
  </div>

  <div class="nav-group">
    <div class="nav-group-label">Hiring</div>
    <a class="nav-item" href="#">
      <div class="nav-icon">📅</div> Interviews
      <span class="nav-badge amber">{{ $interviewsCount }}</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">🎯</div> Offers Sent
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">💬</div> Messages
      <span class="nav-badge rose">7</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">📊</div> Analytics
    </a>
  </div>

  <div class="nav-group">
    <div class="nav-group-label">Settings</div>
    <a class="nav-item" href="#">
      <div class="nav-icon">🏢</div> Company Profile
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">👤</div> Team Members
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">⚙</div> Preferences
    </a>
  </div>

  <div class="sidebar-company">
    <div class="company-row">
      <div class="company-logo">{{ substr(auth()->user()->company ?? auth()->user()->name, 0, 1) }}</div>
      <div>
        <div class="company-name">{{ auth()->user()->company ?? auth()->user()->name }}</div>
        <div class="company-sub">Recruiter · Pro Plan</div>
      </div>
    </div>
    <div class="company-stats">
      <div class="co-stat">
        <div class="co-stat-val">{{ $activeJobsCount }}</div>
        <div class="co-stat-label">Active</div>
      </div>
      <div class="co-stat">
        <div class="co-stat-val">{{ $totalApplicants }}</div>
        <div class="co-stat-label">Applied</div>
      </div>
      <div class="co-stat">
        <div class="co-stat-val">{{ $shortlistedCount }}</div>
        <div class="co-stat-label">Shortlist</div>
      </div>
    </div>
  </div>
</aside>

<!-- ═══════════════ TOPBAR ═══════════════ -->
<header class="topbar">
  <div>
    <div class="page-title">Recruiter Dashboard</div>
    <div class="page-sub">{{ date('l, d F Y') }} · {{ auth()->user()->company ?? 'Your Company' }}</div>
  </div>
  <div class="topbar-search">
    <svg class="topbar-search-icon" width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="6" cy="6" r="4.5" stroke="currentColor" stroke-width="1.3"/><path d="M9.5 9.5L13 13" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
    <input type="text" placeholder="Search candidates, jobs…">
  </div>
  <div class="topbar-right">
    <div class="icon-btn" style="position:relative;">
      <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M7.5 1.5C5 1.5 3 3.5 3 6V9.5L1.5 11H13.5L12 9.5V6C12 3.5 10 1.5 7.5 1.5Z" stroke="currentColor" stroke-width="1.3"/><path d="M6 11C6 11.83 6.67 12.5 7.5 12.5S9 11.83 9 11" stroke="currentColor" stroke-width="1.3"/></svg>
      <div class="notif-dot"></div>
    </div>
    <div class="icon-btn">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><circle cx="7.5" cy="5" r="2.5" stroke="currentColor" stroke-width="1.3"/><path d="M2 13c0-2.76 2.46-5 5.5-5s5.5 2.24 5.5 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        </button>
      </form>
    </div>
    <button class="btn btn-teal" onclick="openModal()">
      <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M6.5 1.5V11.5M1.5 6.5H11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
      Post New Job
    </button>
  </div>
</header>

<!-- ═══════════════ MAIN ═══════════════ -->
<main class="main">

  @if(session('success'))
    <div style="background: rgba(0, 212, 170, 0.1); border: 1px solid var(--teal); color: var(--teal); padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        {{ session('success') }}
    </div>
  @endif

  <!-- ── STATS ROW ── -->
  <div class="stats-row a0">
    <div class="stat-card st-teal">
      <div class="stat-icon" style="background:var(--teal-d);">📋</div>
      <div class="stat-val" style="color:var(--teal);" data-target="{{ $activeJobsCount }}">0</div>
      <div class="stat-lbl">Active Postings</div>
      <div class="stat-delta up">↑ +2 this month</div>
    </div>
    <div class="stat-card st-amber">
      <div class="stat-icon" style="background:var(--amber-d);">📥</div>
      <div class="stat-val" style="color:var(--amber);" data-target="{{ $totalApplicants }}">0</div>
      <div class="stat-lbl">Total Applicants</div>
      <div class="stat-delta up">↑ +38 this week</div>
    </div>
    <div class="stat-card st-sky">
      <div class="stat-icon" style="background:var(--sky-d);">⭐</div>
      <div class="stat-val" style="color:var(--sky);" data-target="{{ $shortlistedCount }}">0</div>
      <div class="stat-lbl">Shortlisted</div>
      <div class="stat-delta up">↑ +5 this week</div>
    </div>
    <div class="stat-card st-violet">
      <div class="stat-icon" style="background:var(--violet-d);">🗓</div>
      <div class="stat-val" style="color:var(--violet);" data-target="{{ $interviewsCount }}">0</div>
      <div class="stat-lbl">Interviews Scheduled</div>
      <div class="stat-delta up">↑ +3 this week</div>
    </div>
    <div class="stat-card st-rose">
      <div class="stat-icon" style="background:var(--rose-d);">⚡</div>
      <div class="stat-val" style="color:var(--rose);" data-target="12">0</div>
      <div class="stat-lbl">Avg. Days to Hire</div>
      <div class="stat-delta up">↓ −3 vs last quarter</div>
    </div>
  </div>

  <!-- ── JOB POSTINGS TABLE ── -->
  <div class="jobs-section a1">
    <div class="sh">
      <span class="sh-title">Active Job Postings</span>
      <div class="sh-right">
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Filter ▾</button>
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Export</button>
        <button class="btn btn-teal" style="font-size:12px;padding:6px 14px;" onclick="openModal()">+ Post Job</button>
      </div>
    </div>

    <div class="jobs-table">
      <div class="table-header">
        <div class="th-cell">Job Title</div>
        <div class="th-cell">Status</div>
        <div class="th-cell">Posted</div>
        <div class="th-cell">Applicants</div>
        <div class="th-cell">Fill Rate</div>
        <div class="th-cell">Actions</div>
      </div>

      @forelse($jobPostings as $job)
      <div class="table-row tr-teal" onclick="filterByJob('{{ $job->title }}')">
        <div class="job-title-cell">
          <div class="job-logo" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);">{{ substr($job->title, 0, 1) }}</div>
          <div>
            <div class="job-name">{{ $job->title }}</div>
            <div class="job-dept">{{ $job->department ?? 'Engineering' }} · {{ $job->location ?? 'Remote' }} · {{ $job->salary ?? '$140K–$190K' }}</div>
          </div>
        </div>
        <div><span class="status-badge s-active">● {{ ucfirst($job->status ?? 'active') }}</span></div>
        <div><div class="cell-text">{{ $job->created_at->format('M d, Y') }}</div><div class="cell-sub">{{ $job->created_at->diffForHumans() }}</div></div>
        <div><div class="cell-num">{{ $job->applications_count }}</div><div class="cell-sub">0 shortlisted</div></div>
        <div class="prog-cell">
          <div class="prog-row">
            <div class="prog-bar"><div class="prog-fill" style="width:68%;background:var(--teal);"></div></div>
            <span class="prog-val">68%</span>
          </div>
        </div>
        <div class="row-actions">
          <button class="btn btn-outline-teal" style="font-size:11px;padding:5px 10px;" onclick="event.stopPropagation();showToast('Viewing applicants…')">View</button>
          <button class="btn btn-ghost" style="font-size:11px;padding:5px 8px;" onclick="event.stopPropagation();">⋯</button>
        </div>
      </div>
      @empty
      <div style="padding: 40px; text-align: center; color: var(--text3);">
        No job postings found. Click "Post New Job" to get started.
      </div>
      @endforelse

    </div>
  </div>

  <!-- ── PIPELINE + SOURCES ── -->
  <div class="pipeline-grid a2">

    <!-- Applicant Funnel -->
    <div class="card">
      <div class="sh" style="margin-bottom:14px;">
        <span class="sh-title">Hiring Funnel — All Roles</span>
        <div class="sh-right">
          <button class="btn btn-ghost" style="font-size:11px;padding:5px 10px;">This Month ▾</button>
        </div>
      </div>
      <div class="funnel">

        <div class="funnel-stage active-stage" onclick="setActiveStage(this)">
          <div class="funnel-fill" style="width:100%;background:rgba(0,212,170,0.06);"></div>
          <div class="stage-info">
            <div class="stage-name">Total Applied</div>
            <div class="stage-sub">All active roles combined</div>
          </div>
          <div class="stage-count">{{ $totalApplicants }}</div>
          <div class="stage-pct" style="color:var(--teal);">100%</div>
        </div>

        <div class="funnel-stage" onclick="setActiveStage(this)">
          <div class="funnel-fill" style="width:72%;background:rgba(56,189,248,0.05);"></div>
          <div class="stage-info">
            <div class="stage-name">Screened / Reviewed</div>
            <div class="stage-sub">Passed initial ATS filter</div>
          </div>
          <div class="stage-count">0</div>
          <div class="stage-pct" style="color:var(--sky);">0%</div>
        </div>

        <div class="funnel-stage" onclick="setActiveStage(this)">
          <div class="funnel-fill" style="width:44%;background:rgba(251,191,36,0.05);"></div>
          <div class="stage-info">
            <div class="stage-name">Shortlisted</div>
            <div class="stage-sub">Manually reviewed & approved</div>
          </div>
          <div class="stage-count">{{ $shortlistedCount }}</div>
          <div class="stage-pct" style="color:var(--amber);">0%</div>
        </div>

      </div>
    </div>

    <!-- Traffic Sources -->
    <div class="card">
      <div class="sh" style="margin-bottom:16px;">
        <span class="sh-title">Top Sources</span>
        <button class="btn btn-ghost" style="font-size:11px;padding:5px 10px;">Details →</button>
      </div>
      <div class="source-list">
        <div class="source-item">
          <div class="source-icon" style="background:var(--teal-d);">🌐</div>
          <span class="source-name">JobFlow Platform</span>
          <div class="source-bar"><div class="source-fill" style="width:90%;background:var(--teal);"></div></div>
          <span class="source-count" style="color:var(--teal);">{{ $totalApplicants }}</span>
        </div>
      </div>

      <!-- Divider -->
      <div style="border-top:1px solid var(--border);margin:16px 0;"></div>

      <!-- Time-to-hire -->
      <div class="sh-title" style="margin-bottom:12px;">Avg. Time to Hire by Role</div>
      <div style="display:flex;flex-direction:column;gap:8px;">
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="font-size:11px;color:var(--text2);width:110px;flex-shrink:0;">Full-Stack Eng.</span>
          <div class="prog-bar" style="flex:1;height:5px;"><div class="prog-fill" style="width:60%;background:var(--teal);height:5px;border-radius:3px;"></div></div>
          <span style="font-size:11px;font-weight:600;color:var(--teal);width:36px;text-align:right;">18d</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ── APPLICANTS ── -->
  <div class="applicants-section a3">
    <div class="sh" style="margin-bottom:12px;">
      <span class="sh-title">
        Applicants
        <span id="jobFilterLabel" style="font-size:12px;color:var(--teal);background:var(--teal-d);border:1px solid rgba(0,212,170,0.2);border-radius:5px;padding:2px 8px;margin-left:8px;font-weight:500;">Recent Applications</span>
      </span>
      <div class="sh-right">
        <div class="filter-tabs" id="filterTabs">
          <button class="filter-tab active" onclick="setFilter(this,'all')">All ({{ $totalApplicants }})</button>
          <button class="filter-tab" onclick="setFilter(this,'shortlisted')">Shortlisted ({{ $shortlistedCount }})</button>
        </div>
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Sort ↕</button>
      </div>
    </div>

    <div class="applicants-grid" id="applicantsGrid">

      @forelse($recentApplicants as $application)
      <div class="applicant-card @if($application->status == 'shortlisted') shortlisted @endif" id="ac-{{ $application->id }}">
        <div class="ac-top">
          <div class="ac-info">
            <div class="ac-avatar" style="background:linear-gradient(135deg,var(--teal),var(--sky));">
              {{ substr($application->user->name, 0, 1) }}
              <div class="ac-online"></div>
            </div>
            <div>
              <div class="ac-name">{{ $application->user->name }}</div>
              <div class="ac-role">{{ $application->jobPost->title }}</div>
            </div>
          </div>
          <div class="ac-actions">
            <div class="ac-btn approve" onclick="shortlist('ac-{{ $application->id }}')" title="Shortlist">⭐</div>
            <div class="ac-btn" onclick="showToast('Opening profile…')" title="View Profile">👤</div>
            <div class="ac-btn reject" onclick="rejectCard('ac-{{ $application->id }}')" title="Reject">✕</div>
          </div>
        </div>
        <div class="ac-tags">
          @foreach(json_decode($application->jobPost->skills ?? '[]') as $skill)
          <span class="ac-tag">{{ $skill }}</span>
          @endforeach
        </div>
        <div class="ac-match">
          <span class="ac-match-lbl">Match</span>
          <div class="ac-match-bar"><div class="ac-match-fill" style="width:{{ $application->jobPost->match }}%;"></div></div>
          <span class="ac-match-pct">{{ $application->jobPost->match }}%</span>
        </div>
        <div class="ac-footer">
          <span class="ac-exp">{{ $application->user->location ?? 'Remote' }}</span>
          <span class="ac-stage-pill" style="color:var(--teal);background:var(--teal-d);border-color:rgba(0,212,170,0.25);">{{ ucfirst($application->status) }}</span>
        </div>
      </div>
      @empty
      <div style="grid-column: span 3; padding: 40px; text-align: center; color: var(--text3);">
        No recent applications found.
      </div>
      @endforelse

    </div>
  </div>

</main>

<!-- ═══════════════ POST JOB MODAL ═══════════════ -->
<div class="modal-overlay" id="postModal" onclick="closeModalOutside(event)">
  <div class="modal">
    <div class="modal-title">
      Post a New Job
      <div class="modal-x" onclick="closeModal()">✕</div>
    </div>

    <form action="{{ route('jobs.store') }}" method="POST" id="publishJobForm">
        @csrf
        <div class="mf">
          <label class="ml">Job Title</label>
          <input class="mi" type="text" name="title" placeholder="e.g. Senior Backend Engineer" required>
        </div>
        <div class="mf-row">
          <div class="mf">
            <label class="ml">Department</label>
            <select class="mi" name="department">
              <option>Engineering</option><option>Product</option><option>Design</option>
              <option>Data / ML</option><option>DevOps</option><option>Marketing</option>
            </select>
          </div>
          <div class="mf">
            <label class="ml">Work Mode</label>
            <select class="mi" name="location">
              <option>Remote</option><option>Hybrid</option><option>On-site</option>
            </select>
          </div>
        </div>
        <div class="mf">
          <label class="ml">Salary Range</label>
          <input class="mi" type="text" name="salary" placeholder="$120,000 - $160,000">
        </div>
        <div class="mf">
          <label class="ml">Job Description</label>
          <textarea class="mi" name="description" rows="3" placeholder="Describe responsibilities…" style="resize:vertical;"></textarea>
        </div>
        <div style="display:flex;gap:10px;margin-top:4px;">
          <button type="button" class="btn btn-ghost" style="flex:1;justify-content:center;" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-teal" style="flex:1.5;justify-content:center;">
            Publish Job ✦
          </button>
        </div>
    </form>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script>
  // ── STAT COUNTERS ──
  document.querySelectorAll('.stat-val[data-target]').forEach(el => {
    const target = +el.dataset.target;
    let current = 0;
    const step = Math.max(1, Math.ceil(target / 45));
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = current;
      if (current >= target) clearInterval(timer);
    }, 28);
  });

  // ── MODAL ──
  function openModal() { document.getElementById('postModal').classList.add('open'); }
  function closeModal() { document.getElementById('postModal').classList.remove('open'); }
  function closeModalOutside(e) { if (e.target === document.getElementById('postModal')) closeModal(); }

  // ── TOAST ──
  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    t.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(t._timer);
    t._timer = setTimeout(() => {
      t.style.opacity = '0';
      t.style.transform = 'translateX(-50%) translateY(10px)';
    }, 2400);
  }
</script>
</body>
</html>
