@extends('layouts.dashboard')

@section('title', $job->title . ' at ' . ($job->company ?? 'Company') . ' — JobFlow')

@section('styles')
    @vite(['resources/css/job-details.css'])
@endsection

@section('topbar_title')
    <div class="breadcrumb" style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text3);">
        <a href="{{ route('dashboard') }}" style="color: var(--text3); text-decoration: none;">Dashboard</a>
        <span class="sep">›</span>
        <a href="{{ route('jobs.index') }}" style="color: var(--text3); text-decoration: none;">Browse Jobs</a>
        <span class="sep">›</span>
        <span class="current" style="color: var(--text2); font-weight: 500;">{{ $job->title }}</span>
    </div>
@endsection

@section('content')
<div class="main-details-wrap">

  <!-- ═══ LEFT COLUMN ═══ -->
  <div class="left-col-details">

    <!-- JOB HERO -->
    <div class="job-hero-details a0">
      <div class="hero-top-details">
        <div class="hero-company-details">
          <div class="company-logo-lg-details" style="background: linear-gradient(135deg, var(--primary), var(--accent));">
            {{ substr($job->company ?? $job->title, 0, 1) }}
          </div>
          <div>
            <div class="company-name-lg-details">{{ $job->company ?? 'Confidential' }} · {{ $job->location ?? 'Remote' }}</div>
            <div class="job-title-lg-details">{{ $job->title }}</div>
          </div>
        </div>
        <div class="hero-actions-details">
          <button class="icon-btn" onclick="shareJob()" title="Share">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="11" cy="3" r="1.5" stroke="currentColor" stroke-width="1.3"/><circle cx="3" cy="7" r="1.5" stroke="currentColor" stroke-width="1.3"/><circle cx="11" cy="11" r="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M4.5 7.7L9.5 10.3M9.5 3.7L4.5 6.3" stroke="currentColor" stroke-width="1.3"/></svg>
          </button>
          <button class="icon-btn" onclick="copyLink()" title="Copy link">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><rect x="4" y="4" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M2 10V3C2 2.45 2.45 2 3 2H10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
          </button>
        </div>
      </div>

      <!-- Meta pills -->
      <div class="hero-meta-details">
        <span class="meta-pill-details">💼 {{ $job->job_type ?? 'Full-time' }}</span>
        <span class="meta-pill-details">🌍 {{ $job->location ?? 'Remote' }}</span>
        <span class="meta-pill-details">💰 {{ $job->salary ?? '$100K - $150K' }}</span>
        <span class="meta-pill-details">📈 {{ $job->experience ?? '3-5 yrs' }} experience</span>
        <span class="meta-pill-details">🏢 {{ $job->department ?? 'Engineering' }}</span>
        <span class="meta-pill-details">📅 Posted {{ $job->created_at->diffForHumans() }}</span>
      </div>

      <!-- AI Match score -->
      <div class="hero-match-details">
        <div class="match-ring-wrap-details">
          <svg width="64" height="64" viewBox="0 0 64 64">
            <circle cx="32" cy="32" r="26" fill="none" stroke="var(--surface3)" stroke-width="6"/>
            <circle cx="32" cy="32" r="26" fill="none"
              stroke="url(#matchGrad)" stroke-width="6"
              stroke-linecap="round"
              stroke-dasharray="163" stroke-dashoffset="{{ 163 * (1 - ($job->match_score ?? 85) / 100) }}"
              transform="rotate(-90 32 32)"/>
            <defs>
              <linearGradient id="matchGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#7C3AED"/>
                <stop offset="100%" stop-color="#38BDF8"/>
              </linearGradient>
            </defs>
          </svg>
          <div class="match-ring-text-details">
            <span class="match-num-details">{{ $job->match_score ?? 85 }}</span>
            <span class="match-sub-details">%</span>
          </div>
        </div>
        <div class="match-details-content">
          <div class="match-verdict-details">Excellent Fit ✦</div>
          <div class="match-desc-details">Your profile is a strong match for this position. Your skills in <strong>{{ implode(', ', array_slice($job->skillsList(), 0, 3)) }}</strong> align perfectly with the requirements.</div>
        </div>
      </div>
    </div>

    <!-- OVERVIEW -->
    <div class="section-details a1">
      <div class="section-title-details">
        <span class="st-icon-details" style="background:var(--v-dim);">📋</span>
        Job Overview
      </div>
      <div class="info-grid-details">
        <div class="info-box-details">
          <div class="ib-label-details">Salary</div>
          <div class="ib-val-details" style="color:var(--v3);">{{ $job->salary ?? 'Not specified' }}</div>
          <div class="ib-sub-details">+ Benefits</div>
        </div>
        <div class="info-box-details">
          <div class="ib-label-details">Job Type</div>
          <div class="ib-val-details">{{ $job->job_type ?? 'Full-time' }}</div>
          <div class="ib-sub-details">Permanent</div>
        </div>
        <div class="info-box-details">
          <div class="ib-label-details">Work Mode</div>
          <div class="ib-val-details">{{ $job->location ?? 'Remote' }}</div>
          <div class="ib-sub-details">Flexible</div>
        </div>
        <div class="info-box-details">
          <div class="ib-label-details">Department</div>
          <div class="ib-val-details">{{ $job->department ?? 'Engineering' }}</div>
          <div class="ib-sub-details">Core Team</div>
        </div>
        <div class="info-box-details">
          <div class="ib-label-details">Experience</div>
          <div class="ib-val-details">{{ $job->experience ?? '5+ years' }}</div>
          <div class="ib-sub-details">Professional</div>
        </div>
        <div class="info-box-details">
          <div class="ib-label-details">Visa Sponsor</div>
          <div class="ib-val-details" style="color:var(--teal);">✓ Yes</div>
          <div class="ib-sub-details">Available</div>
        </div>
      </div>
    </div>

    <!-- ABOUT ROLE -->
    <div class="section-details a2">
      <div class="section-title-details">
        <span class="st-icon-details" style="background:var(--sky-dim);">🎯</span>
        About the Role
      </div>
      <div class="prose-details">
        <p>{!! nl2br(e($job->description)) !!}</p>
      </div>
    </div>

    <!-- TECH STACK -->
    <div class="section-details a3">
      <div class="section-title-details">
        <span class="st-icon-details" style="background:var(--teal-dim);">⚙️</span>
        Required Tech Stack
      </div>
      <div class="tech-chips-details">
        @foreach($job->skillsList() as $skill)
            <div class="tech-chip-details">
                <div class="tc-dot" style="background: var(--primary);"></div>
                {{ $skill }}
            </div>
        @endforeach
      </div>
    </div>

    <!-- BENEFITS -->
    <div class="section-details a4">
      <div class="section-title-details">
        <span class="st-icon-details" style="background:var(--teal-dim);">🎁</span>
        Benefits & Perks
      </div>
      <div class="info-grid-details">
        <div class="info-box-details">🏥 Health & Wellness</div>
        <div class="info-box-details">💰 401K + Match</div>
        <div class="info-box-details">🏖 Unlimited PTO</div>
        <div class="info-box-details">📚 Learning Budget</div>
        <div class="info-box-details">🖥 Remote Setup</div>
        <div class="info-box-details">👶 Parental Leave</div>
      </div>
    </div>

  </div><!-- /left-col -->

  <!-- ═══ RIGHT SIDEBAR ═══ -->
  <div class="right-col-details">

    <!-- APPLY CARD -->
    <div class="apply-card-details">
      <div class="apply-salary-details">{{ explode('–', $job->salary ?? '$100K–$150K')[0] }}</div>
      <div class="apply-salary-sub-details">per year · starting range</div>

      <div class="apply-meta-row-details">
        <div class="am-item-details"><span class="am-icon">🏢</span> {{ $job->company ?? 'Company' }}</div>
        <div class="am-item-details"><span class="am-icon">🌍</span> {{ $job->location ?? 'Remote' }}</div>
        <div class="am-item-details"><span class="am-icon">💼</span> Full-time</div>
      </div>

      <div class="deadline-bar-details">
        <span class="db-icon">⏰</span>
        <div class="db-text">Closing soon! Apply today.</div>
      </div>

      <button type="button" class="apply-btn-lg-details" onclick="openApply()">
        Quick Apply ✦
      </button>

      <button class="btn btn-ghost" style="width:100%; justify-content:center;" onclick="toggleSave()">
          ★ Save Job
      </button>
    </div>

    <!-- COMPANY QUICK CARD -->
    <div class="rc-card-details">
      <div style="display: flex; gap: 11px; align-items: center; margin-bottom: 14px;">
        <div class="company-logo-lg-details" style="width:42px; height:42px; font-size:18px; background: var(--surface2);">
            {{ substr($job->company ?? 'C', 0, 1) }}
        </div>
        <div>
          <div style="font-size:15px; font-weight:700; color:var(--text); font-family:'Syne', sans-serif;">{{ $job->company ?? 'Company Name' }}</div>
          <div style="font-size:11px; color:var(--text3);">Technology · Software</div>
        </div>
      </div>
      <button class="btn btn-ghost" style="width:100%; justify-content:center; font-size:12px;">
        View Company Profile →
      </button>
    </div>

  </div><!-- /right-col -->

</div><!-- /main-wrap -->

<!-- STICKY APPLY FOOTER -->
<div id="stickyFooter" class="sticky-footer-details">
  <div style="display:flex; align-items:center; gap:11px; flex:1; min-width:0;">
    <div style="width:36px; height:36px; border-radius:10px; background:var(--surface2); display:flex; align-items:center; justify-content:center; font-family:'Syne', sans-serif; font-size:16px; color:white; flex-shrink:0;">
        {{ substr($job->company ?? 'C', 0, 1) }}
    </div>
    <div style="min-width:0;">
      <div style="font-size:14px; font-weight:700; color:var(--text); font-family:'Syne', sans-serif; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $job->title }}</div>
      <div style="font-size:11px; color:var(--text3);">{{ $job->company }} · {{ $job->salary }}</div>
    </div>
  </div>
  <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
    <button type="button" class="btn btn-primary" style="font-size:13px; padding:9px 22px;" onclick="openApply()">Apply Now ✦</button>
  </div>
</div>

<!-- ══════════ APPLY MODAL ══════════ -->
<div class="modal-overlay" id="applyModal" onclick="closeModalOutside(event)">
  <div class="modal">
    <div class="modal-head">
      <div>
        <div class="modal-job-title">Apply to {{ $job->company ?? 'Company' }}</div>
        <div class="modal-company">{{ $job->title }} · {{ $job->department ?? 'Engineering' }} · {{ $job->location ?? 'Remote' }}</div>
      </div>
      <div class="modal-x" onclick="closeApply()">✕</div>
    </div>

    <!-- Step indicator -->
    <div class="modal-steps" id="modalSteps">
      <div class="ms-item active" id="step1item">
        <div class="ms-circle">1</div>
        <div class="ms-label">Resume</div>
      </div>
      <div class="ms-line" id="line12"></div>
      <div class="ms-item" id="step2item">
        <div class="ms-circle">2</div>
        <div class="ms-label">Details</div>
      </div>
      <div class="ms-line" id="line23"></div>
      <div class="ms-item" id="step3item">
        <div class="ms-circle">3</div>
        <div class="ms-label">Review</div>
      </div>
    </div>

    <!-- Step 1 -->
    <div id="step1content">
      <div class="mf">
        <label class="ml">Choose Resume</label>
        <select class="mi">
          <option>✓ {{ auth()->user()->name ?? 'Your' }}_Resume_v3.pdf (ATS Score: 94%)</option>
          <option>{{ auth()->user()->name ?? 'Your' }}_Resume_v2.pdf (ATS Score: 78%)</option>
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
        <textarea class="mi" rows="3" style="resize:vertical;" placeholder="Tell {{ $job->company ?? 'the company' }} why you're a perfect fit — keep it to 3–4 sentences…"></textarea>
      </div>
      <div class="ats-chip">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="var(--green)" stroke-width="1.3"/><path d="M5 8.5L7 10.5L11 6" stroke="var(--green)" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span style="font-size:12px;color:var(--green);">Your resume matches <strong>{{ $job->match ?? 91 }}%</strong> of this role's requirements — excellent chance of getting shortlisted!</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost" style="flex:1;justify-content:center;" onclick="closeApply()">Cancel</button>
        <button type="button" class="btn btn-primary" style="flex:1.5;justify-content:center;" onclick="goModalStep(2)">Continue →</button>
      </div>
    </div>

    <!-- Step 2 -->
    <div id="step2content" style="display:none;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;" class="mf">
        <div>
          <label class="ml">LinkedIn Profile</label>
          <input class="mi" type="url" value="linkedin.com/in/you" placeholder="linkedin.com/in/…">
        </div>
        <div>
          <label class="ml">Portfolio / GitHub</label>
          <input class="mi" type="url" value="github.com/you" placeholder="github.com/…">
        </div>
      </div>
      <div class="mf">
        <label class="ml">Expected Salary (USD)</label>
        <input class="mi" type="text" value="$185,000" placeholder="e.g. $175,000">
      </div>
      <div class="mf">
        <label class="ml">Notice Period</label>
        <select class="mi">
          <option>Immediately available</option>
          <option>2 weeks</option>
          <option selected>4 weeks</option>
          <option>6 weeks</option>
          <option>3 months</option>
        </select>
      </div>
      <div class="mf">
        <label class="ml">Work authorization in US?</label>
        <select class="mi">
          <option>Yes — I'm authorized to work in the US</option>
          <option selected>No — I need visa sponsorship</option>
        </select>
      </div>
      <div class="mf">
        <label class="ml">Anything else you'd like to add?</label>
        <textarea class="mi" rows="2" style="resize:vertical;" placeholder="Optional additional context…"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost" style="flex:0.8;justify-content:center;" onclick="goModalStep(1)">← Back</button>
        <button type="button" class="btn btn-primary" style="flex:1.5;justify-content:center;" onclick="goModalStep(3)">Review Application →</button>
      </div>
    </div>

    <!-- Step 3 -->
    <div id="step3content" style="display:none;">
      <div style="background:var(--surface2);border:1px solid var(--border2);border-radius:var(--rl);padding:16px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:11px;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border);">
          <div class="user-av" style="width:40px;height:40px;font-size:15px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center;">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
          <div>
            <div style="font-size:15px;font-weight:700;color:var(--text);">{{ auth()->user()->name ?? 'User' }}</div>
            <div style="font-size:12px;color:var(--text3);">{{ auth()->user()->email ?? 'email@example.com' }} · Remote</div>
          </div>
          <div style="margin-left:auto;text-align:right;">
            <div style="font-size:13px;font-weight:700;color:var(--v3);">{{ $job->match ?? 91 }}%</div>
            <div style="font-size:10px;color:var(--text3);">AI Match</div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:12px;color:var(--text2);">
          <div>📄 <strong style="color:var(--text);">Resume:</strong> v3 (ATS 94%)</div>
          <div>💰 <strong style="color:var(--text);">Expected:</strong> $185K</div>
          <div>📅 <strong style="color:var(--text);">Notice:</strong> 4 weeks</div>
          <div>🌍 <strong style="color:var(--text);">Visa needed:</strong> Yes</div>
        </div>
      </div>
      <div style="display:flex;align-items:flex-start;gap:8px;background:var(--v-dim2);border:1px solid rgba(124,58,237,0.2);border-radius:var(--r);padding:12px;margin-bottom:16px;">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="color:var(--v3);flex-shrink:0;margin-top:1px;"><circle cx="7" cy="7" r="5.5" stroke="currentColor" stroke-width="1.3"/><path d="M7 4.5V7M7 9.5H7.01" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        <span style="font-size:12px;color:var(--text2);line-height:1.6;">By submitting you agree to the <a href="#" style="color:var(--v3);">Privacy Policy</a>. Your information will be processed in accordance with GDPR.</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost" style="flex:0.8;justify-content:center;" onclick="goModalStep(2)">← Edit</button>
        <form action="{{ route('jobs.apply', $job) }}" method="POST" id="finalApplyForm" style="flex:1.5;">
            @csrf
            <button type="button" class="btn btn-primary" style="width: 100%; justify-content:center;" onclick="submitApplication()">
              Submit Application ✦
            </button>
        </form>
      </div>
    </div>

    <!-- Success -->
    <div id="successContent" style="display:none;text-align:center;padding:14px 0;">
      <div style="width:64px;height:64px;border-radius:50%;background:var(--green-dim);border:2px solid var(--green);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 16px;animation:popInDetails 0.4s cubic-bezier(0.34,1.56,0.64,1) both;">✓</div>
      <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:700;color:var(--text);margin-bottom:8px;">Application Submitted!</div>
      <div style="font-size:13px;color:var(--text2);line-height:1.7;margin-bottom:20px;">Great job! The recruiting team will review your application within <strong style="color:var(--text);">2–3 business days</strong>. We'll notify you by email with any updates.</div>
      <div style="display:flex;gap:8px;justify-content:center;">
        <button type="button" class="btn btn-ghost" onclick="closeApply()">Close</button>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Track in Dashboard →</a>
      </div>
    </div>

  </div>
</div>

@endsection

@section('scripts')
<script>
    // Sticky footer visibility
    window.addEventListener('scroll', () => {
        const hero = document.querySelector('.job-hero-details');
        const footer = document.getElementById('stickyFooter');
        if (hero.getBoundingClientRect().bottom < 0) {
            footer.classList.add('visible');
        } else {
            footer.classList.remove('visible');
        }
    });

    function shareJob() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $job->title }} at {{ $job->company }}',
                url: window.location.href
            });
        } else {
            copyLink();
        }
    }

    function copyLink() {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }

    function toggleSave() {
        alert('Job saved successfully!');
    }
    /* ── APPLY MODAL ── */
    let modalStep = 1;

    function openApply() {
      modalStep = 1;
      showModalStep(1);
      document.getElementById('applyModal').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeApply() {
      document.getElementById('applyModal').classList.remove('open');
      document.body.style.overflow = '';
    }

    function closeModalOutside(e) {
      if (e.target === document.getElementById('applyModal')) closeApply();
    }

    function goModalStep(step) {
      modalStep = step;
      showModalStep(step);
    }

    function showModalStep(step) {
      ['step1content','step2content','step3content','successContent'].forEach((id,i) => {
        document.getElementById(id).style.display = (i === step - 1) ? 'block' : 'none';
      });
      // update step dots
      for (let i = 1; i <= 3; i++) {
        const item   = document.getElementById('step' + i + 'item');
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
        fetch('{{ route("jobs.apply", $job) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(res => {
            if (!res.ok) throw new Error('Request failed');
            return res.json();
        })
        .then(data => {
            if(data.success) {
                ['step1content','step2content','step3content'].forEach(id => {
                  document.getElementById(id).style.display = 'none';
                });
                document.getElementById('successContent').style.display = 'block';
                document.getElementById('modalSteps').style.display = 'none';
            }
        })
        .catch(() => { alert('Could not submit application. Please try again.'); });
    }

    /* ── KEYBOARD: ESC closes modal ── */
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        closeApply();
        document.body.style.overflow = '';
      }
    });
</script>
@endsection
