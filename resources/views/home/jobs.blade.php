<section id="featured">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-eyebrow">Featured Opportunities</div>
      <h2 class="section-title">Handpicked Jobs, Just for You</h2>
      <p class="section-sub">AI-matched roles based on your skills and preferences</p>
    </div>
    
    <div class="jobs-grid reveal">
      @foreach($jobs as $job)
      <div class="job-card">
        <div class="job-card-top">
          <div class="company-info">
            <div class="company-logo" style="background:linear-gradient(135deg, var(--primary), var(--accent));color:white;">
              {{ substr($job->company, 0, 1) }}
            </div>
            <div>
              <div class="company-name">{{ $job->company }}</div>
              <div class="job-title">{{ $job->title }}</div>
            </div>
          </div>
          <span class="job-badge {{ $loop->first ? 'badge-hot' : 'badge-new' }}">
            {{ $loop->first ? '🔥 Hot' : '✨ New' }}
          </span>
        </div>
        
        <div class="job-tags">
          @foreach(array_slice($job->skillsList(), 0, 8) as $skill)
          <span class="tag">{{ $skill }}</span>
          @endforeach
        </div>
        
        <div class="job-card-bottom">
          <div>
            <div class="job-salary">$140K – $220K <span>/ year</span></div>
            <div class="job-meta">📍 Remote&nbsp;•&nbsp; ⏱ Full-time</div>
          </div>
          <a href="{{ route('login') }}" class="btn btn-primary" style="padding:8px 16px;font-size:13px;border-radius:9px;">Apply</a>
        </div>
        
        <div class="match-bar">
          <span class="match-label">Match</span>
          <div class="bar-track">
            <div class="bar-fill" style="width:{{ $job->match }}%"></div>
          </div>
          <span class="match-pct">{{ $job->match }}%</span>
        </div>
      </div>
      @endforeach

      <!-- Static example to fill the grid -->
      <div class="job-card">
        <div class="job-card-top">
          <div class="company-info">
            <div class="company-logo" style="background:linear-gradient(135deg,#FF6347,#FF4500);color:white;">M</div>
            <div>
              <div class="company-name">Meta Platforms</div>
              <div class="job-title">ML Research Scientist</div>
            </div>
          </div>
          <span class="job-badge badge-remote">🌐 Remote</span>
        </div>
        <div class="job-tags">
          <span class="tag">Python</span><span class="tag">PyTorch</span><span class="tag">LLMs</span>
        </div>
        <div class="job-card-bottom">
          <div>
            <div class="job-salary">$200K – $280K <span>/ year</span></div>
            <div class="job-meta">📍 Menlo Park, CA&nbsp;•&nbsp; ⏱ Full-time</div>
          </div>
          <a href="{{ route('login') }}" class="btn btn-primary" style="padding:8px 16px;font-size:13px;border-radius:9px;">Apply</a>
        </div>
        <div class="match-bar">
          <span class="match-label">Match</span>
          <div class="bar-track">
            <div class="bar-fill" style="width:91%"></div>
          </div>
          <span class="match-pct">91%</span>
        </div>
      </div>

      <div class="job-card" style="border-style:dashed;display:flex;align-items:center;justify-content:center;text-align:center;min-height:220px;">
        <div>
          <div style="font-size:32px;margin-bottom:14px;">🔍</div>
          <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:600;color:var(--text2);margin-bottom:8px;">Browse All Jobs</div>
          <div style="font-size:13px;color:var(--text3);margin-bottom:16px;">12,000+ opportunities waiting</div>
          <a href="{{ route('register') }}" class="btn btn-ghost" style="font-size:13px;padding:8px 18px;">View All →</a>
        </div>
      </div>

    </div>
  </div>
</section>