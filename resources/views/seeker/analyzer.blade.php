@extends('layouts.dashboard')

@section('title', 'Resume Analyzer — JobFlow')

@section('styles')
    @vite(['resources/css/analyzer.css'])
    <style>
        .page-wrap { margin-left: 0; margin-top: 0; }
        .sidebar { z-index: 100; }
        .topbar { left: var(--sw); }
        .analyzer-page { min-height: 100%; color: var(--text); }
    </style>
@endsection

@section('topbar_title', 'Resume Analyzer')
@section('topbar_subtitle')
    <div style="font-size:12px;color:var(--text3);display:flex;align-items:center;gap:6px;">
        <span style="width:7px;height:7px;border-radius:50%;background:var(--c1);display:inline-block;animation:pulse-ring-cyan 2s ease infinite;"></span>
        AI-Powered · Real-time Analysis
    </div>
@endsection
@section('topbar_actions')
    <button class="btn btn-outline-cyan" style="font-size:12px;padding:7px 14px;" onclick="showToast('Downloading full report…')">
      ↓ Download Report
    </button>
    <button class="btn btn-cyan" onclick="resetAnalyzer()">
      + Analyze New Resume
    </button>
@endsection

@section('content')
<div class="analyzer-page">
  <!-- ── SECTION 0: PREVIOUS ANALYSES ── -->
  <div class="a0" style="margin-bottom:22px;" id="historySection">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
      <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:15px;font-weight:800;color:var(--text);letter-spacing:-0.3px;">Resume Versions</div>
      <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;" onclick="showToast('Opening version history…')">View All →</button>
    </div>
    <div class="history-row">
      <div class="hist-card selected" onclick="selectVersion(this,'v3')">
        <div class="hist-name">{{ $user->name ?? 'User' }}_Resume_v3.pdf</div>
        <div class="hist-meta">Uploaded Today · Current</div>
        <div class="hist-score" style="color:var(--c1);">94 <span style="font-size:12px;font-family:'Bricolage Grotesque';font-weight:400;color:var(--text3);">ATS score</span></div>
      </div>
      <div class="hist-card" onclick="selectVersion(this,'v2')">
        <div class="hist-name">{{ $user->name ?? 'User' }}_Resume_v2.pdf</div>
        <div class="hist-meta">Uploaded Last Month</div>
        <div class="hist-score" style="color:var(--amber);">78 <span style="font-size:12px;font-family:'Bricolage Grotesque';font-weight:400;color:var(--text3);">ATS score</span></div>
      </div>
      <div class="hist-card" onclick="selectVersion(this,'v1')">
        <div class="hist-name">{{ $user->name ?? 'User' }}_Resume_v1.pdf</div>
        <div class="hist-meta">Uploaded 3 Months Ago</div>
        <div class="hist-score" style="color:var(--rose);">52 <span style="font-size:12px;font-family:'Bricolage Grotesque';font-weight:400;color:var(--text3);">ATS score</span></div>
      </div>
      <div class="hist-card" style="border-style:dashed;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;min-height:90px;" onclick="triggerUpload()">
        <div style="font-size:22px;margin-bottom:6px;">+</div>
        <div style="font-size:12px;font-weight:600;color:var(--text2);">Upload New</div>
        <div style="font-size:11px;color:var(--text3);">PDF or DOCX</div>
      </div>
    </div>
  </div>

  <!-- ── SECTION 1: UPLOAD / DRAG-DROP ── -->
  <div id="uploadSection" class="a1" style="display:none;">
    <div class="upload-zone" id="dropZone"
      ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)"
      onclick="triggerUpload()">
      <div class="upload-icon-wrap">📄</div>
      <div class="upload-title">Drop your resume here</div>
      <div class="upload-sub">or click to browse files from your computer</div>
      <div class="format-pills">
        <span class="format-pill">PDF</span>
        <span class="format-pill">DOCX</span>
        <span class="format-pill">DOC</span>
        <span class="format-pill">TXT</span>
        <span class="format-pill">Max 10MB</span>
      </div>
      <div class="version-row">
        <span class="version-label">Recent:</span>
        <span class="version-pill active" onclick="event.stopPropagation();selectVersionPill(this,'v3')">📄 {{ $user->name ?? 'User' }}_Resume_v3.pdf <span style="color:var(--c1);">94</span></span>
        <span class="version-pill" onclick="event.stopPropagation();selectVersionPill(this,'v2')">📄 {{ $user->name ?? 'User' }}_Resume_v2.pdf <span style="color:var(--amber);">78</span></span>
      </div>
    </div>
    <input type="file" id="fileInput" accept=".pdf,.doc,.docx,.txt" style="display:none" onchange="handleFile(this)">
  </div>

  <!-- ── SECTION 2: SCANNING ── -->
  <div class="scan-wrap a2" id="scanSection">
    <div class="scan-doc">
      <div class="scan-beam" id="scanBeam"></div>
      <div class="scan-line-el" style="width:80%;"></div>
      <div class="scan-line-el" style="width:60%;"></div>
      <div class="scan-line-el" style="width:90%;"></div>
      <div class="scan-line-el" style="width:70%;"></div>
      <div class="scan-line-el" style="width:85%;"></div>
      <div class="scan-line-el" style="width:55%;"></div>
      <div class="scan-line-el" style="width:78%;"></div>
      <div class="scan-line-el" style="width:65%;"></div>
      <div class="scan-line-el" style="width:88%;"></div>
      <div class="scan-line-el" style="width:72%;"></div>
      <div class="scan-line-el" style="width:50%;"></div>
    </div>
    <div class="scan-title">Analyzing your resume…</div>
    <div class="scan-sub">Our AI is reading every line. This takes about 10 seconds.</div>
    <div class="scan-steps" id="scanSteps">
      <div class="scan-step" id="ss1"><div class="ss-dot"></div>Parsing document structure</div>
      <div class="scan-step" id="ss2"><div class="ss-dot"></div>Extracting skills & experience</div>
      <div class="scan-step" id="ss3"><div class="ss-dot"></div>Running ATS keyword check</div>
      <div class="scan-step" id="ss4"><div class="ss-dot"></div>Scoring formatting & layout</div>
      <div class="scan-step" id="ss5"><div class="ss-dot"></div>Matching to job market data</div>
      <div class="scan-step" id="ss6"><div class="ss-dot"></div>Generating suggestions</div>
    </div>
  </div>

  <!-- ── SECTION 3: RESULTS ── -->
  <div class="results-wrap" id="resultsSection">

    <!-- Score Hero -->
    <div class="score-hero a0" style="margin-bottom:22px;">
      <div class="score-ring-wrap">
        <svg width="120" height="120" viewBox="0 0 120 120">
          <circle cx="60" cy="60" r="50" fill="none" stroke="var(--surface3)" stroke-width="8"/>
          <circle cx="60" cy="60" r="50" fill="none"
            stroke="url(#atsGrad)" stroke-width="8"
            stroke-linecap="round"
            stroke-dasharray="314"
            stroke-dashoffset="19"
            transform="rotate(-90 60 60)"
            id="atsRingEl"/>
          <defs>
            <linearGradient id="atsGrad" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#00E5CC"/>
              <stop offset="100%" stop-color="#38BDF8"/>
            </linearGradient>
          </defs>
        </svg>
        <div class="score-ring-text">
          <span class="score-num" id="atsNumEl">0</span>
          <span class="score-label">ATS Score</span>
        </div>
      </div>

      <div class="score-info">
        <div class="score-grade" id="scoreGradeEl">Outstanding ✦</div>
        <div class="score-desc">Your resume passes <strong style="color:var(--text);">94%</strong> of ATS filters tested. Strong keyword density, clean formatting, and measurable achievements make this highly competitive. One small push can get it to <strong style="color:var(--c1);">98+</strong>.</div>
        <div style="display:flex;gap:10px;margin-top:12px;flex-wrap:wrap;">
          <span style="font-size:11px;padding:3px 10px;border-radius:20px;background:var(--emd);color:var(--emerald);border:1px solid rgba(16,185,129,0.2);">✓ 12 skills found</span>
          <span style="font-size:11px;padding:3px 10px;border-radius:20px;background:var(--rosed);color:var(--rose);border:1px solid rgba(244,63,94,0.2);">✕ 3 skill gaps</span>
          <span style="font-size:11px;padding:3px 10px;border-radius:20px;background:var(--amberd);color:var(--amber);border:1px solid rgba(251,191,36,0.2);">⚠ 4 improvements</span>
          <span style="font-size:11px;padding:3px 10px;border-radius:20px;background:var(--cd);color:var(--c1);border:1px solid rgba(0,229,204,0.2);">Top 6% of applicants</span>
        </div>
      </div>

      <div class="score-breakdown">
        <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:4px;">Score Breakdown</div>
        <div class="sb-row">
          <span class="sb-label">Keywords</span>
          <div class="sb-bar"><div class="sb-fill" style="width:96%;background:var(--c1);"></div></div>
          <span class="sb-val" style="color:var(--c1);">96</span>
        </div>
        <div class="sb-row">
          <span class="sb-label">Formatting</span>
          <div class="sb-bar"><div class="sb-fill" style="width:90%;background:var(--sky);"></div></div>
          <span class="sb-val" style="color:var(--sky);">90</span>
        </div>
        <div class="sb-row">
          <span class="sb-label">Experience</span>
          <div class="sb-bar"><div class="sb-fill" style="width:94%;background:var(--violet);"></div></div>
          <span class="sb-val" style="color:var(--violet);">94</span>
        </div>
        <div class="sb-row">
          <span class="sb-label">Impact</span>
          <div class="sb-bar"><div class="sb-fill" style="width:88%;background:var(--emerald);"></div></div>
          <span class="sb-val" style="color:var(--emerald);">88</span>
        </div>
        <div class="sb-row">
          <span class="sb-label">Readability</span>
          <div class="sb-bar"><div class="sb-fill" style="width:98%;background:var(--amber);"></div></div>
          <span class="sb-val" style="color:var(--amber);">98</span>
        </div>
        <div class="sb-row">
          <span class="sb-label">Gaps</span>
          <div class="sb-bar"><div class="sb-fill" style="width:78%;background:var(--rose);"></div></div>
          <span class="sb-val" style="color:var(--rose);">78</span>
        </div>
      </div>
    </div>

    <!-- Main grid: Skills + Improvements + Checklist -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:22px;">

      <!-- LEFT: Extracted skills -->
      <div class="analyzer-card a1">
        <div class="sec-title">
          <span class="sec-icon" style="background:var(--emd);">🧠</span>
          Extracted Skills
          <span style="margin-left:auto;font-size:11px;color:var(--text3);font-weight:500;">15 detected</span>
        </div>

        <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.09em;text-transform:uppercase;margin-bottom:8px;">Technical — Confirmed ✓</div>
        <div class="skills-grid" style="margin-bottom:16px;">
          <span class="skill-chip found">✓ React</span>
          <span class="skill-chip found">✓ TypeScript</span>
          <span class="skill-chip found">✓ Node.js</span>
          <span class="skill-chip found">✓ PostgreSQL</span>
          <span class="skill-chip found">✓ Docker</span>
          <span class="skill-chip found">✓ AWS</span>
          <span class="skill-chip found">✓ GraphQL</span>
          <span class="skill-chip found">✓ Git / GitHub</span>
          <span class="skill-chip found">✓ REST APIs</span>
          <span class="skill-chip bonus">★ Next.js</span>
          <span class="skill-chip bonus">★ Redis</span>
          <span class="skill-chip bonus">★ Tailwind</span>
        </div>

        <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.09em;text-transform:uppercase;margin-bottom:8px;">Soft Skills — Detected</div>
        <div class="skills-grid" style="margin-bottom:16px;">
          <span class="skill-chip found">✓ Leadership</span>
          <span class="skill-chip found">✓ Communication</span>
          <span class="skill-chip found">✓ Problem-solving</span>
        </div>

        <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.09em;text-transform:uppercase;margin-bottom:8px;">Skill Gaps — Missing ✕</div>
        <div class="skills-grid" style="margin-bottom:16px;">
          <span class="skill-chip missing">✕ Rust</span>
          <span class="skill-chip missing">✕ Kubernetes</span>
          <span class="skill-chip missing">✕ System Design</span>
        </div>

        <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.09em;text-transform:uppercase;margin-bottom:8px;">Recommended to Add ⚡</div>
        <div class="skills-grid">
          <span class="skill-chip recommended" onclick="showToast('Opening learning resources for Go…')">+ Go</span>
          <span class="skill-chip recommended" onclick="showToast('Opening learning resources for Kafka…')">+ Kafka</span>
          <span class="skill-chip recommended" onclick="showToast('Opening learning resources for Terraform…')">+ Terraform</span>
          <span class="skill-chip recommended" onclick="showToast('Opening learning resources for gRPC…')">+ gRPC</span>
        </div>
      </div>

      <!-- RIGHT: ATS Checklist -->
      <div class="analyzer-card a1">
        <div class="sec-title">
          <span class="sec-icon" style="background:var(--cd);">✅</span>
          ATS Compatibility Checklist
        </div>
        <div class="checklist">
          <div class="cl-item">
            <div class="cl-check pass"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Clean single-column layout</strong> — ATS can parse without errors</div>
          </div>
          <div class="cl-item">
            <div class="cl-check pass"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Standard section headers</strong> — Experience, Skills, Education detected</div>
          </div>
          <div class="cl-item">
            <div class="cl-check pass"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Contact info present</strong> — Email, phone, LinkedIn, GitHub detected</div>
          </div>
          <div class="cl-item">
            <div class="cl-check pass"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Measurable achievements</strong> — "Reduced latency by 40%", "Led team of 8" found</div>
          </div>
          <div class="cl-item">
            <div class="cl-check pass"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Education section</strong> — Degree, institution, graduation year present</div>
          </div>
          <div class="cl-item">
            <div class="cl-check pass"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>No tables or columns</strong> — Structured text throughout, no invisible boxes</div>
          </div>
          <div class="cl-item">
            <div class="cl-check warn"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M5 3V5.5M5 7.5H5.01" stroke="var(--amber)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Length</strong> — 2 pages detected. Consider trimming to 1 page for &lt;8 yrs exp.</div>
          </div>
          <div class="cl-item">
            <div class="cl-check warn"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M5 3V5.5M5 7.5H5.01" stroke="var(--amber)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Summary section</strong> — Present but generic. Tailor it to each target role.</div>
          </div>
          <div class="cl-item">
            <div class="cl-check fail"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2.5 2.5L7.5 7.5M7.5 2.5L2.5 7.5" stroke="var(--rose)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>Missing skills keywords</strong> — Rust, Kubernetes, System Design not found in resume</div>
          </div>
          <div class="cl-item">
            <div class="cl-check fail"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2.5 2.5L7.5 7.5M7.5 2.5L2.5 7.5" stroke="var(--rose)" stroke-width="1.5" stroke-linecap="round"/></svg></div>
            <div class="cl-text"><strong>No portfolio link</strong> — Consider adding a live project URL to boost visibility</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Improvement Suggestions -->
    <div class="analyzer-card a2" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--amberd);">💡</span>
        AI Improvement Suggestions
        <span style="margin-left:auto;font-size:11px;color:var(--amber);background:var(--amberd);padding:2px 9px;border-radius:20px;border:1px solid rgba(251,191,36,0.25);font-weight:600;">4 actions</span>
      </div>
      <div class="improvement-list">

        <div class="imp-card" style="border-left-color:var(--rose);" onclick="showToast('Opening keyword optimizer…')">
          <div class="imp-icon" style="background:var(--rosed);">🔑</div>
          <div style="flex:1;">
            <div class="imp-title">Add Missing Power Keywords</div>
            <div class="imp-desc">Your resume is missing "Kubernetes", "system design", and "distributed systems" — terms that appear in 78% of Sr. SWE job postings you're targeting. Adding them could boost ATS score by <strong style="color:var(--text);">+8 points</strong>.</div>
          </div>
          <span class="imp-badge badge-high">High Impact</span>
        </div>

        <div class="imp-card" style="border-left-color:var(--amber);" onclick="showToast('Opening summary editor…')">
          <div class="imp-icon" style="background:var(--amberd);">✍️</div>
          <div style="flex:1;">
            <div class="imp-title">Strengthen Your Summary</div>
            <div class="imp-desc">Your current summary is 3 sentences — too vague. Replace it with a targeted headline that mirrors the job description's language and highlights your unique value. See the Before/After example below.</div>
          </div>
          <span class="imp-badge badge-high">High Impact</span>
        </div>

        <div class="imp-card" style="border-left-color:var(--amber);" onclick="showToast('Opening bullet point optimizer…')">
          <div class="imp-icon" style="background:var(--amberd);">📊</div>
          <div style="flex:1;">
            <div class="imp-title">Quantify 3 More Bullet Points</div>
            <div class="imp-desc">3 of your experience bullets still use vague language ("helped improve performance", "worked on features"). Replace them with metrics: users impacted, percentages, timelines, or team size. <strong style="color:var(--text);">ATS + human reviewers both love numbers.</strong></div>
          </div>
          <span class="imp-badge badge-med">Med Impact</span>
        </div>

        <div class="imp-card" style="border-left-color:var(--sky);" onclick="showToast('Opening portfolio linker…')">
          <div class="imp-icon" style="background:var(--skyd);">🔗</div>
          <div style="flex:1;">
            <div class="imp-title">Add Portfolio / Project Link</div>
            <div class="imp-desc">Resumes with a live project URL receive 34% more recruiter clicks. Add your GitHub portfolio or a personal projects page in the header next to LinkedIn.</div>
          </div>
          <span class="imp-badge badge-low">Low Impact</span>
        </div>

      </div>
    </div>

    <!-- Before / After Example -->
    <div class="analyzer-card a3" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--vd);">🔄</span>
        Before vs. After — Summary Example
      </div>
      <div class="before-after">
        <div class="ba-panel">
          <div class="ba-label" style="color:var(--rose);">❌ Current (weak)</div>
          <div class="ba-line old">Experienced software engineer with 6 years of experience in web development.</div>
          <div class="ba-line old">Good at working with teams and delivering projects on time.</div>
          <div class="ba-line old">Looking for new opportunities in tech companies.</div>
        </div>
        <div class="ba-panel">
          <div class="ba-label" style="color:var(--emerald);">✅ Suggested (strong)</div>
          <div class="ba-line new">Senior Full-Stack Engineer with <strong>6+ years</strong> building high-scale distributed systems in React, TypeScript & Node.js.</div>
          <div class="ba-line new">Led <strong>3 cross-functional squads</strong>, reducing p99 latency by <strong>40%</strong> and shipping features to 2M+ users.</div>
          <div class="ba-line new">Seeking staff-level IC role at product-led companies working on <strong>developer infrastructure or FinTech</strong>.</div>
        </div>
      </div>
      <button class="btn btn-cyan" style="margin-top:14px;font-size:13px;" onclick="showToast('Copying improved summary to clipboard…')">
        Copy Improved Summary →
      </button>
    </div>

    <!-- Job Match Suggestions -->
    <div class="analyzer-card a3" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--cd);">🎯</span>
        Jobs That Match Your Resume
        <span style="margin-left:auto;font-size:11px;color:var(--c1);background:var(--cd);padding:2px 9px;border-radius:20px;border:1px solid rgba(0,229,204,0.2);font-weight:600;">AI Matched from Database</span>
      </div>
      <div class="job-match-grid">
        @forelse($matchedJobs as $job)
        <div class="jm-card" onclick="window.location.href='{{ route('jobs.show', $job->id) }}'">
          <div class="jm-top">
            <div class="jm-logo" style="background:linear-gradient(135deg,#635BFF,#9B94FF);">{{ substr($job->company, 0, 1) }}</div>
            <div><div class="jm-company">{{ $job->company }}</div><div class="jm-title">{{ $job->title }}</div></div>
          </div>
          <div class="jm-tags">
            @if($job->skills)
              @foreach(array_slice(json_decode($job->skills, true) ?? [], 0, 3) as $skill)
                <span class="jm-tag">{{ $skill }}</span>
              @endforeach
            @endif
          </div>
          <div class="jm-footer">
            <div class="jm-salary">{{ $job->salary ?? 'Negotiable' }}</div>
            <div class="jm-match-bar">
              <div class="jm-track"><div class="jm-fill" style="width:{{ $job->match ?? 80 }}%;"></div></div>
              <span class="jm-pct">{{ $job->match ?? 80 }}%</span>
            </div>
          </div>
        </div>
        @empty
          <div style="font-size:14px;color:var(--text2);padding:20px;">No jobs found in the database.</div>
        @endforelse
      </div>
      <div style="display:flex;justify-content:center;margin-top:14px;">
        <button class="btn btn-outline-cyan" onclick="window.location.href='{{ route('jobs.index') }}'">Browse All Jobs →</button>
      </div>
    </div>

    <!-- Pro Tips -->
    <div class="analyzer-card a4" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--limed);">🚀</span>
        Pro Tips to Reach 98+ Score
      </div>
      <div class="tips-grid">
        <div class="tip-card">
          <div class="tip-icon">🎯</div>
          <div class="tip-title">Tailor for Each Role</div>
          <div class="tip-desc">Mirror the exact wording of each job description. ATS systems match character-for-character — "JavaScript" ≠ "JS" to many parsers.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">📏</div>
          <div class="tip-title">One Page Rule (Usually)</div>
          <div class="tip-desc">For under 8 years of experience, a tight one-page resume outperforms two pages in recruiter surveys by 67%.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">🔢</div>
          <div class="tip-title">Quantify Everything</div>
          <div class="tip-desc">Every bullet should answer "how much?" or "how many?". Numbers make vague claims concrete and memorable to both ATS and humans.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">📋</div>
          <div class="tip-title">Use a Skills Section</div>
          <div class="tip-desc">A dedicated skills block lets ATS parse your capabilities instantly without hunting through experience bullets. List exact tool names, not abbreviations.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">🔗</div>
          <div class="tip-title">Link Everything Live</div>
          <div class="tip-desc">GitHub, portfolio, LinkedIn — all should be clickable hyperlinks, not plain text URLs. Recruiters who open PDFs will click them.</div>
        </div>
        <div class="tip-card">
          <div class="tip-icon">🤖</div>
          <div class="tip-title">Re-analyze After Every Edit</div>
          <div class="tip-desc">Use JobFlow's analyzer after every significant change. ATS scores can shift dramatically with even minor keyword additions or formatting tweaks.</div>
        </div>
      </div>
    </div>

    <!-- Download Report -->
    <div class="analyzer-card a5" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--cd);">📥</span>
        Export & Share
      </div>
      <div class="download-row">
        <button class="btn btn-cyan" onclick="showToast('Downloading PDF report…')">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="margin-right:6px;"><path d="M7 1V10M7 10L4 7M7 10L10 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M2 12H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          Download Full PDF Report
        </button>
        <button class="btn btn-ghost" onclick="showToast('Copying shareable link…')">🔗 Copy Shareable Link</button>
        <button class="btn btn-ghost" onclick="showToast('Sending to email…')">📧 Email to Myself</button>
        <button class="btn btn-ghost" onclick="showToast('Opening resume editor…')">✏️ Edit Resume with AI</button>
      </div>
      <div style="margin-top:12px;padding:12px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);font-size:12px;color:var(--text3);line-height:1.6;">
        💡 <strong style="color:var(--text2);">Pro tip:</strong> After applying suggested improvements, re-upload your resume. Users who iterate 3+ times typically achieve a score of <strong style="color:var(--c1);">95+</strong> within a week.
      </div>
    </div>

    <!-- ══ VERSION COMPARISON CHART ══ -->
    <div class="analyzer-card a5" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--vd);">📈</span>
        Score Progress Over Time
        <span style="margin-left:auto;font-size:11px;color:var(--emerald);font-weight:600;">+42 pts improvement</span>
      </div>
      <div class="version-chart" id="versionChart">
        <div class="vc-col">
          <div class="vc-bar-wrap">
            <div class="vc-bar" id="vcBar1" style="background:linear-gradient(180deg,var(--rose),rgba(244,63,94,0.4));height:56%;">
              <span class="vc-bar-val" style="color:var(--rose);">52</span>
            </div>
          </div>
          <div class="vc-label">v1</div>
          <div class="vc-date">Mar 2026</div>
        </div>
        <div style="display:flex;align-items:center;color:var(--text3);font-size:18px;padding-bottom:28px;">→</div>
        <div class="vc-col">
          <div class="vc-bar-wrap">
            <div class="vc-bar" id="vcBar2" style="background:linear-gradient(180deg,var(--amber),rgba(251,191,36,0.4));height:78%;">
              <span class="vc-bar-val" style="color:var(--amber);">78</span>
            </div>
          </div>
          <div class="vc-label">v2</div>
          <div class="vc-date">Apr 2026</div>
        </div>
        <div style="display:flex;align-items:center;color:var(--text3);font-size:18px;padding-bottom:28px;">→</div>
        <div class="vc-col">
          <div class="vc-bar-wrap">
            <div class="vc-bar" id="vcBar3" style="background:linear-gradient(180deg,var(--c1),rgba(0,229,204,0.4));height:94%;box-shadow:0 0 18px rgba(0,229,204,0.25);">
              <span class="vc-bar-val" style="color:var(--c1);">94 ✦</span>
            </div>
          </div>
          <div class="vc-label" style="color:var(--c1);font-weight:600;">v3 · Now</div>
          <div class="vc-date">May 2026</div>
        </div>
        <div style="display:flex;align-items:center;color:var(--text3);font-size:18px;padding-bottom:28px;opacity:0.4;">→</div>
        <div class="vc-col" style="opacity:0.45;">
          <div class="vc-bar-wrap">
            <div class="vc-bar" style="background:linear-gradient(180deg,rgba(0,229,204,0.3),rgba(0,229,204,0.1));height:98%;border:1.5px dashed rgba(0,229,204,0.4);box-sizing:border-box;">
              <span class="vc-bar-val" style="color:var(--text3);">98?</span>
            </div>
          </div>
          <div class="vc-label">v4 · Goal</div>
          <div class="vc-date">With fixes</div>
        </div>
      </div>

      <!-- Per-category improvement table -->
      <div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border);">
        <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.09em;text-transform:uppercase;margin-bottom:10px;">Category Improvement (v1 → v3)</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
          <div style="background:var(--surface2);border-radius:var(--radius);padding:10px;text-align:center;">
            <div style="font-size:11px;color:var(--text3);margin-bottom:4px;">Keywords</div>
            <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--emerald);">+44</div>
            <div style="font-size:10px;color:var(--text3);">52 → 96</div>
          </div>
          <div style="background:var(--surface2);border-radius:var(--radius);padding:10px;text-align:center;">
            <div style="font-size:11px;color:var(--text3);margin-bottom:4px;">Formatting</div>
            <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--emerald);">+38</div>
            <div style="font-size:10px;color:var(--text3);">52 → 90</div>
          </div>
          <div style="background:var(--surface2);border-radius:var(--radius);padding:10px;text-align:center;">
            <div style="font-size:11px;color:var(--text3);margin-bottom:4px;">Impact</div>
            <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--sky);">+31</div>
            <div style="font-size:10px;color:var(--text3);">57 → 88</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ ROLE-TARGETED KEYWORD ANALYSIS ══ -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:22px;">

      <div class="analyzer-card">
        <div class="sec-title">
          <span class="sec-icon" style="background:var(--skyd);">🎯</span>
          Role-Targeted Analysis
        </div>
        <div class="role-tab-row" id="roleTabs">
          <button class="role-tab active" onclick="switchRole(this,'swe')">Sr. SWE</button>
          <button class="role-tab" onclick="switchRole(this,'pm')">Product Mgr</button>
          <button class="role-tab" onclick="switchRole(this,'devops')">DevOps</button>
          <button class="role-tab" onclick="switchRole(this,'ml')">ML Engineer</button>
        </div>

        <div id="roleContent">
          <!-- Rendered by switchRole() -->
        </div>
      </div>

      <!-- Keyword Density -->
      <div class="analyzer-card">
        <div class="sec-title">
          <span class="sec-icon" style="background:var(--amberd);">🔑</span>
          Keyword Density
          <span style="margin-left:auto;font-size:11px;color:var(--text3);font-weight:400;">occurrences in resume</span>
        </div>
        <div id="keywordDensity">
          <!-- rendered by JS -->
        </div>
        <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
          <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.09em;text-transform:uppercase;margin-bottom:8px;">Missing High-Value Keywords</div>
          <div style="display:flex;flex-wrap:wrap;gap:6px;">
            <span class="skill-chip missing" onclick="showToast('Opening keyword guide for Kubernetes…')">✕ Kubernetes</span>
            <span class="skill-chip missing" onclick="showToast('Opening keyword guide for Rust…')">✕ Rust</span>
            <span class="skill-chip missing" onclick="showToast('Opening keyword guide for System Design…')">✕ System Design</span>
            <span class="skill-chip recommended" onclick="showToast('Opening keyword guide for Go…')">+ Go</span>
            <span class="skill-chip recommended" onclick="showToast('Opening keyword guide for Kafka…')">+ Kafka</span>
            <span class="skill-chip recommended" onclick="showToast('Opening keyword guide for gRPC…')">+ gRPC</span>
          </div>
        </div>
      </div>

    </div>

    <!-- ══ ACTION PLAN ══ -->
    <div class="analyzer-card" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--limed);">🗺</span>
        Your 7-Day Action Plan
        <span style="margin-left:auto;" id="actionProgress" style="font-size:11px;"></span>
      </div>

      <!-- Progress bar -->
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <div style="flex:1;height:6px;background:var(--surface3);border-radius:3px;overflow:hidden;">
          <div id="actionProgressBar" style="height:100%;width:0%;background:linear-gradient(90deg,var(--c1),var(--sky));border-radius:3px;transition:width 0.5s ease;"></div>
        </div>
        <span id="actionProgressText" style="font-size:12px;font-weight:700;color:var(--c1);white-space:nowrap;">0 / 7 done</span>
      </div>

      <div class="action-plan-list" id="actionPlanList">
        <!-- rendered by JS -->
      </div>
    </div>

    <!-- ══ SALARY IMPACT ESTIMATOR ══ -->
    <div class="analyzer-card" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:var(--emd);">💰</span>
        Salary Impact Estimator
        <span style="margin-left:auto;font-size:11px;color:var(--text3);font-weight:400;">Based on your resume + market data</span>
      </div>

      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;">
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:14px;text-align:center;">
          <div style="font-size:11px;color:var(--text3);margin-bottom:6px;">Current Range</div>
          <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--text);">$140K</div>
          <div style="font-size:10px;color:var(--text3);margin-top:2px;">based on v2 resume</div>
        </div>
        <div style="background:linear-gradient(135deg,rgba(0,229,204,0.1),rgba(56,189,248,0.06));border:1px solid rgba(0,229,204,0.2);border-radius:var(--radius);padding:14px;text-align:center;">
          <div style="font-size:11px;color:var(--c1);margin-bottom:6px;">With v3 Resume</div>
          <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--c1);">$165K</div>
          <div style="font-size:10px;color:var(--text3);margin-top:2px;">+$25K estimated lift</div>
        </div>
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:14px;text-align:center;">
          <div style="font-size:11px;color:var(--text3);margin-bottom:6px;">After All Fixes</div>
          <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--emerald);">$180K</div>
          <div style="font-size:10px;color:var(--text3);margin-top:2px;">+$40K from current</div>
        </div>
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:14px;text-align:center;">
          <div style="font-size:11px;color:var(--text3);margin-bottom:6px;">Market Ceiling</div>
          <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:18px;font-weight:800;color:var(--violet);">$240K</div>
          <div style="font-size:10px;color:var(--text3);margin-top:2px;">FAANG + Staff level</div>
        </div>
      </div>

      <!-- Factors table -->
      <div style="font-size:11px;font-weight:700;color:var(--text3);letter-spacing:0.09em;text-transform:uppercase;margin-bottom:10px;">What Affects Your Salary Range</div>
      <div style="display:flex;flex-direction:column;gap:7px;">
        <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);">
          <span style="font-size:14px;">⬆️</span>
          <span style="font-size:12px;color:var(--text2);flex:1;">6 years experience at scale</span>
          <span style="font-size:11px;font-weight:700;color:var(--emerald);">+$20K</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);">
          <span style="font-size:14px;">⬆️</span>
          <span style="font-size:12px;color:var(--text2);flex:1;">React + TypeScript + Node.js stack (high demand)</span>
          <span style="font-size:11px;font-weight:700;color:var(--emerald);">+$15K</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);">
          <span style="font-size:14px;">⬆️</span>
          <span style="font-size:12px;color:var(--text2);flex:1;">Measurable achievements (reduced latency 40%)</span>
          <span style="font-size:11px;font-weight:700;color:var(--emerald);">+$10K</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);">
          <span style="font-size:14px;">⬇️</span>
          <span style="font-size:12px;color:var(--text2);flex:1;">Missing Kubernetes & systems design keywords</span>
          <span style="font-size:11px;font-weight:700;color:var(--rose);">−$12K</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);">
          <span style="font-size:14px;">⬇️</span>
          <span style="font-size:12px;color:var(--text2);flex:1;">No Rust or Go listed — limits systems-layer roles</span>
          <span style="font-size:11px;font-weight:700;color:var(--rose);">−$8K</span>
        </div>
      </div>
    </div>

    <!-- ══ LINKEDIN SYNC PANEL ══ -->
    <div class="analyzer-card" style="margin-bottom:22px;">
      <div class="sec-title">
        <span class="sec-icon" style="background:rgba(0,119,181,0.15);">💼</span>
        LinkedIn Profile Sync
        <span style="margin-left:auto;font-size:11px;color:var(--sky);background:var(--skyd);padding:2px 9px;border-radius:20px;border:1px solid rgba(56,189,248,0.2);font-weight:600;">Beta</span>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:14px;">
          <div style="font-size:11px;color:var(--text3);margin-bottom:8px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;">Resume vs LinkedIn Gaps</div>
          <div style="display:flex;flex-direction:column;gap:7px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="width:7px;height:7px;border-radius:50%;background:var(--emerald);flex-shrink:0;"></span>
              <span style="font-size:12px;color:var(--text2);">Work history — matched</span>
              <span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--emerald);">✓</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="width:7px;height:7px;border-radius:50%;background:var(--emerald);flex-shrink:0;"></span>
              <span style="font-size:12px;color:var(--text2);">Education — matched</span>
              <span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--emerald);">✓</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="width:7px;height:7px;border-radius:50%;background:var(--amber);flex-shrink:0;"></span>
              <span style="font-size:12px;color:var(--text2);">Skills — 3 missing on LI</span>
              <span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--amber);">⚠</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="width:7px;height:7px;border-radius:50%;background:var(--rose);flex-shrink:0;"></span>
              <span style="font-size:12px;color:var(--text2);">Summary — outdated on LI</span>
              <span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--rose);">✕</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="width:7px;height:7px;border-radius:50%;background:var(--rose);flex-shrink:0;"></span>
              <span style="font-size:12px;color:var(--text2);">Portfolio link — missing on LI</span>
              <span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--rose);">✕</span>
            </div>
          </div>
        </div>

        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:14px;">
          <div style="font-size:11px;color:var(--text3);margin-bottom:8px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;">LinkedIn Profile Score</div>
          <div style="display:flex;align-items:center;gap:14px;margin-bottom:12px;">
            <div style="position:relative;width:60px;height:60px;flex-shrink:0;">
              <svg width="60" height="60" viewBox="0 0 60 60">
                <circle cx="30" cy="30" r="24" fill="none" stroke="var(--surface3)" stroke-width="5"/>
                <circle cx="30" cy="30" r="24" fill="none" stroke="var(--sky)" stroke-width="5"
                  stroke-linecap="round" stroke-dasharray="150" stroke-dashoffset="45"
                  transform="rotate(-90 30 30)"/>
              </svg>
              <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <span style="font-family:'Cabinet Grotesk',sans-serif;font-size:15px;font-weight:800;color:var(--sky);">71</span>
              </div>
            </div>
            <div>
              <div style="font-size:13px;font-weight:700;color:var(--sky);margin-bottom:3px;">Good Profile</div>
              <div style="font-size:11px;color:var(--text2);line-height:1.5;">Sync your resume updates to push this to <strong style="color:var(--text);">90+</strong>.</div>
            </div>
          </div>
          <button class="btn btn-outline-cyan" style="width:100%;justify-content:center;font-size:12px;" onclick="showToast('Connecting to LinkedIn…')">
            Sync to LinkedIn →
          </button>
        </div>
      </div>
    </div>

    <!-- ══ SHARE YOUR SCORE ══ -->
    <div class="share-card" style="margin-bottom:22px;">
      <div style="position:relative;z-index:1;">
        <div style="font-size:13px;color:var(--c1);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:8px;">Share Your Score</div>
        <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:28px;font-weight:900;color:var(--text);letter-spacing:-0.5px;margin-bottom:6px;">
          Your Resume Scores <span style="color:var(--c1);">94/100</span> 🎉
        </div>
        <div style="font-size:13px;color:var(--text2);margin-bottom:20px;">You're in the top 6% of all applicants analyzed on JobFlow this month.</div>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
          <button class="btn btn-cyan" onclick="showToast('Sharing to LinkedIn…')">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="margin-right:6px;"><rect x="1" y="1" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M4 6V10M4 4.5V4.51M7 10V7.5C7 6.67 7.67 6 8.5 6C9.33 6 10 6.67 10 7.5V10M7 6V10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Share on LinkedIn
          </button>
          <button class="btn btn-ghost" onclick="showToast('Copied badge to clipboard…')">📋 Copy Badge</button>
          <button class="btn btn-ghost" onclick="showToast('Downloading certificate…')">🏆 Download Certificate</button>
        </div>
      </div>
    </div>

  </div><!-- /results-wrap -->
</div>
@endsection

@section('scripts')
<script>
/* ═══════════════════════════════════
   STATE
═══════════════════════════════════ */
let currentState = 'results'; // 'upload' | 'scanning' | 'results'
let currentVersion = 'v3';

/* ═══════════════════════════════════
   SHOW / HIDE SECTIONS
═══════════════════════════════════ */
function showState(state) {
  document.getElementById('uploadSection').style.display  = 'none';
  document.getElementById('scanSection').style.display    = 'none';
  document.getElementById('resultsSection').classList.remove('show');

  if (state === 'upload') {
    document.getElementById('uploadSection').style.display = 'block';
  } else if (state === 'scanning') {
    const s = document.getElementById('scanSection');
    s.style.display = 'flex';
  } else {
    document.getElementById('resultsSection').classList.add('show');
    animateScore();
  }
  currentState = state;
}

/* ═══════════════════════════════════
   SCORE ANIMATION
═══════════════════════════════════ */
function animateScore() {
  const target = 94;
  const circumference = 314;
  let val = 0;
  const el  = document.getElementById('atsNumEl');
  const ring = document.getElementById('atsRingEl');
  if (!el || !ring) return;

  const timer = setInterval(() => {
    val = Math.min(val + 2, target);
    el.textContent = val;
    ring.style.strokeDashoffset = circumference * (1 - val / 100);
    if (val >= target) clearInterval(timer);
  }, 20);
}

/* ═══════════════════════════════════
   UPLOAD HANDLERS
═══════════════════════════════════ */
window.triggerUpload = function() {
  document.getElementById('fileInput')?.click();
}

window.handleFile = function(input) {
  const file = input.files[0];
  if (!file) return;
  startScan(file.name);
}

window.onDragOver = function(e) {
  e.preventDefault();
  document.getElementById('dropZone').classList.add('drag-over');
}
window.onDragLeave = function(e) {
  document.getElementById('dropZone').classList.remove('drag-over');
}
window.onDrop = function(e) {
  e.preventDefault();
  document.getElementById('dropZone').classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file) startScan(file.name);
}

/* ═══════════════════════════════════
   SCAN SEQUENCE
═══════════════════════════════════ */
function startScan(filename) {
  showState('scanning');
  document.getElementById('historySection').style.display = 'none';

  const steps = ['ss1','ss2','ss3','ss4','ss5','ss6'];
  let i = 0;

  // reset all
  steps.forEach(id => {
    const el = document.getElementById(id);
    el.classList.remove('done','active');
  });

  const interval = setInterval(() => {
    if (i > 0) {
      document.getElementById(steps[i-1]).classList.remove('active');
      document.getElementById(steps[i-1]).classList.add('done');
      document.getElementById(steps[i-1]).querySelector('.ss-dot').innerHTML = '';
      const checkMark = document.createElement('span');
      checkMark.textContent = '✓';
      checkMark.style.cssText = 'font-size:10px;color:var(--c1);font-weight:700;';
      document.getElementById(steps[i-1]).querySelector('.ss-dot').appendChild(checkMark);
    }
    if (i < steps.length) {
      document.getElementById(steps[i]).classList.add('active');
      i++;
    } else {
      clearInterval(interval);
      setTimeout(() => {
        showState('results');
        document.getElementById('historySection').style.display = 'block';
        showToast('✦ Analysis complete — Score: 94/100');
      }, 600);
    }
  }, 900);
}

/* ═══════════════════════════════════
   VERSION SELECTION
═══════════════════════════════════ */
window.selectVersion = function(card, version) {
  document.querySelectorAll('.hist-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  currentVersion = version;

  const scores = { v3: 94, v2: 78, v1: 52 };
  const grades = { v3: 'Outstanding ✦', v2: 'Good', v1: 'Needs Work' };
  const colors = { v3: 'var(--c1)', v2: 'var(--amber)', v1: 'var(--rose)' };

  if (version === 'v3') {
    showState('results');
  } else {
    const gradeEl = document.getElementById('scoreGradeEl');
    const numEl   = document.getElementById('atsNumEl');
    const ringEl  = document.getElementById('atsRingEl');
    if (gradeEl) gradeEl.textContent = grades[version];
    if (gradeEl) gradeEl.style.color = colors[version];
    if (numEl) {
      numEl.style.color = colors[version];
      numEl.textContent = scores[version];
    }
    const circumference = 314;
    if (ringEl) ringEl.style.strokeDashoffset = circumference * (1 - scores[version] / 100);
    showState('results');
    showToast(`Loaded ${version.toUpperCase()} — Score: ${scores[version]}/100`);
  }
}

window.selectVersionPill = function(pill, version) {
  document.querySelectorAll('.version-pill').forEach(p => p.classList.remove('active'));
  pill.classList.add('active');
  showToast('Loaded ' + version.toUpperCase());
}

/* ═══════════════════════════════════
   RESET
═══════════════════════════════════ */
window.resetAnalyzer = function() {
  showState('upload');
  document.getElementById('historySection').style.display = 'block';
}

/* ═══════════════════════════════════
   ROLE-TARGETED ANALYSIS
═══════════════════════════════════ */
const roleData = {
  swe: {
    title: 'Senior Software Engineer',
    score: 87,
    color: 'var(--c1)',
    keywords: [
      { kw: 'TypeScript', found: true, importance: 'Critical' },
      { kw: 'Distributed Systems', found: false, importance: 'Critical' },
      { kw: 'React', found: true, importance: 'High' },
      { kw: 'Node.js', found: true, importance: 'High' },
      { kw: 'System Design', found: false, importance: 'High' },
      { kw: 'PostgreSQL', found: true, importance: 'Medium' },
      { kw: 'Kubernetes', found: false, importance: 'Medium' },
      { kw: 'AWS', found: true, importance: 'Medium' },
    ]
  },
  pm: {
    title: 'Product Manager',
    score: 48,
    color: 'var(--amber)',
    keywords: [
      { kw: 'Product Roadmap', found: false, importance: 'Critical' },
      { kw: 'Stakeholder Mgmt', found: false, importance: 'Critical' },
      { kw: 'User Research', found: false, importance: 'High' },
      { kw: 'A/B Testing', found: false, importance: 'High' },
      { kw: 'SQL', found: true, importance: 'Medium' },
      { kw: 'Agile / Scrum', found: true, importance: 'Medium' },
      { kw: 'OKRs', found: false, importance: 'Medium' },
    ]
  },
  devops: {
    title: 'DevOps / Platform Engineer',
    score: 62,
    color: 'var(--sky)',
    keywords: [
      { kw: 'Kubernetes', found: false, importance: 'Critical' },
      { kw: 'Terraform', found: false, importance: 'Critical' },
      { kw: 'Docker', found: true, importance: 'High' },
      { kw: 'AWS', found: true, importance: 'High' },
      { kw: 'CI/CD', found: false, importance: 'High' },
      { kw: 'Datadog / Monitoring', found: false, importance: 'Medium' },
      { kw: 'Linux / Bash', found: true, importance: 'Medium' },
    ]
  },
  ml: {
    title: 'ML Engineer',
    score: 41,
    color: 'var(--violet)',
    keywords: [
      { kw: 'Python', found: false, importance: 'Critical' },
      { kw: 'PyTorch / TensorFlow', found: false, importance: 'Critical' },
      { kw: 'Machine Learning', found: false, importance: 'Critical' },
      { kw: 'Data Pipelines', found: false, importance: 'High' },
      { kw: 'Statistics', found: false, importance: 'High' },
      { kw: 'SQL', found: true, importance: 'Medium' },
      { kw: 'Distributed Systems', found: false, importance: 'Medium' },
    ]
  }
};

window.switchRole = function(tab, role) {
  document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
  tab.classList.add('active');

  const d = roleData[role];
  const container = document.getElementById('roleContent');
  if (!container) return;

  const matched = d.keywords.filter(k => k.found).length;
  const total   = d.keywords.length;

  container.innerHTML = `
    <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:12px;">
      <div>
        <div style="font-size:12px;color:var(--text3);margin-bottom:2px;">Match Score for ${d.title}</div>
        <div style="font-family:'Cabinet Grotesk',sans-serif;font-size:22px;font-weight:900;color:${d.color};">${d.score}<span style="font-size:13px;font-weight:400;color:var(--text3);font-family:'Bricolage Grotesque',sans-serif;">/100</span></div>
      </div>
      <div style="flex:1;">
        <div style="height:6px;background:var(--surface3);border-radius:3px;overflow:hidden;">
          <div style="height:100%;width:${d.score}%;background:${d.color};border-radius:3px;transition:width 0.8s ease;"></div>
        </div>
        <div style="font-size:11px;color:var(--text3);margin-top:4px;">${matched} of ${total} key terms found</div>
      </div>
    </div>
    ${d.keywords.map(k => `
      <div class="target-row">
        <div style="width:18px;height:18px;border-radius:5px;${k.found ? 'background:var(--emd);border:1px solid rgba(16,185,129,0.3);' : 'background:var(--rosed);border:1px solid rgba(244,63,94,0.3);'}display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="9" height="9" viewBox="0 0 9 9" fill="none">
            <path d="${k.found ? 'M1.5 4.5L3.5 6.5L7.5 2.5' : 'M2 2L7 7M7 2L2 7'}" stroke="${k.found ? 'var(--emerald)' : 'var(--rose)'}" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </div>
        <span class="tr-keyword">${k.kw}</span>
        <span class="tr-found" style="color:${k.found ? 'var(--emerald)' : 'var(--rose)'};">${k.found ? '✓ Found' : '✕ Missing'}</span>
        <span class="tr-freq" style="color:${k.importance === 'Critical' ? 'var(--rose)' : k.importance === 'High' ? 'var(--amber)' : 'var(--text3);'};">${k.importance}</span>
      </div>
    `).join('')}
  `;
}

setTimeout(() => switchRole(document.querySelector('.role-tab.active') || { classList: { add: () => {}, remove: () => {} } }, 'swe'), 100);

/* ═══════════════════════════════════
   KEYWORD DENSITY
═══════════════════════════════════ */
const kwData = [
  { label: 'React',       count: 8,  max: 10, color: 'var(--c1)' },
  { label: 'TypeScript',  count: 6,  max: 10, color: 'var(--sky)' },
  { label: 'Node.js',     count: 5,  max: 10, color: 'var(--emerald)' },
  { label: 'AWS',         count: 4,  max: 10, color: 'var(--amber)' },
  { label: 'PostgreSQL',  count: 3,  max: 10, color: 'var(--violet)' },
  { label: 'GraphQL',     count: 3,  max: 10, color: 'var(--rose)' },
  { label: 'Docker',      count: 2,  max: 10, color: 'var(--sky)' },
  { label: 'Git / GitHub',count: 4,  max: 10, color: 'var(--c1)' },
];

function renderKeywordDensity() {
  const container = document.getElementById('keywordDensity');
  if (!container) return;
  container.innerHTML = kwData.map(k => `
    <div class="kd-row">
      <span class="kd-label">${k.label}</span>
      <div class="kd-track">
        <div class="kd-fill" style="width:${(k.count / k.max) * 100}%;background:${k.color};animation:barGrow 1s ease both;"></div>
      </div>
      <span class="kd-count" style="color:${k.color};">${k.count}×</span>
    </div>
  `).join('');
}

setTimeout(renderKeywordDensity, 200);

/* ═══════════════════════════════════
   ACTION PLAN
═══════════════════════════════════ */
const actionItems = [
  { title: 'Add Kubernetes to skills section',         sub: 'Copy the exact word "Kubernetes" — case matters for ATS parsers.',           impact: 'high',   done: false },
  { title: 'Rewrite professional summary',              sub: 'Use the AI-suggested version from the Before/After section above.',            impact: 'high',   done: false },
  { title: 'Quantify 3 more bullet points',             sub: 'Replace vague verbs like "helped" or "worked on" with numbers and outcomes.', impact: 'high',   done: false },
  { title: 'Add portfolio / GitHub URL to header',      sub: 'Place it next to your LinkedIn link at the top of the page.',                impact: 'medium', done: false },
  { title: 'Add "System Design" to skills',             sub: 'Appears in 81% of Sr. SWE job descriptions you have viewed.',                impact: 'medium', done: true  },
  { title: 'Trim resume to 1 page',                     sub: 'Cut oldest or least relevant bullets to meet the 1-page guideline.',         impact: 'low',    done: false },
  { title: 'Sync changes to LinkedIn profile',          sub: 'Use JobFlow LinkedIn Sync to keep both resume and profile consistent.',       impact: 'low',    done: false },
];

let doneCount = actionItems.filter(a => a.done).length;

function renderActionPlan() {
  const list = document.getElementById('actionPlanList');
  if (!list) return;
  list.innerHTML = actionItems.map((item, i) => {
    const impColor  = item.impact === 'high' ? 'var(--rose)' : item.impact === 'medium' ? 'var(--amber)' : 'var(--sky)';
    const impBg     = item.impact === 'high' ? 'var(--rosed)' : item.impact === 'medium' ? 'var(--amberd)' : 'var(--skyd)';
    const impLabel  = item.impact === 'high' ? 'High' : item.impact === 'medium' ? 'Med' : 'Low';
    return `
      <div class="ap-item ${item.done ? 'done' : ''}" id="ap-${i}">
        <div class="ap-num" style="background:${item.done ? 'var(--emd)' : impBg};color:${item.done ? 'var(--emerald)' : impColor};">
          ${item.done ? '✓' : i + 1}
        </div>
        <div class="ap-text">
          <div class="ap-title" style="${item.done ? 'text-decoration:line-through;opacity:0.6;' : ''}">${item.title}</div>
          <div class="ap-sub">${item.sub}</div>
        </div>
        <button class="ap-done-btn" style="color:${item.done ? 'var(--emerald)' : impColor};background:${item.done ? 'var(--emd)' : impBg};border-color:${item.done ? 'rgba(16,185,129,0.3)' : 'transparent'};"
          onclick="toggleActionDone(${i})">
          ${item.done ? 'Undo' : 'Mark Done'}
        </button>
      </div>
    `;
  }).join('');
  updateActionProgress();
}

window.toggleActionDone = function(idx) {
  actionItems[idx].done = !actionItems[idx].done;
  doneCount = actionItems.filter(a => a.done).length;
  renderActionPlan();
  showToast(actionItems[idx].done ? '✓ Task marked complete!' : 'Task reopened');
}

function updateActionProgress() {
  doneCount = actionItems.filter(a => a.done).length;
  const pct = Math.round((doneCount / actionItems.length) * 100);
  const bar  = document.getElementById('actionProgressBar');
  const txt  = document.getElementById('actionProgressText');
  if (bar) bar.style.width = pct + '%';
  if (txt) txt.textContent = `${doneCount} / ${actionItems.length} done`;
}

setTimeout(renderActionPlan, 300);

/* ═══════════════════════════════════
   INIT — show results by default
═══════════════════════════════════ */
window.addEventListener('load', () => {
  showState('results');
});
</script>
@endsection
