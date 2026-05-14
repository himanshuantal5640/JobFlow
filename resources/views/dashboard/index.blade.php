@extends('layouts.dashboard')

@section('title', 'Job Seeker Dashboard')
@section('topbar_title', 'Dashboard')

@section('content')

  <!-- Greeting Banner -->
  <div class="greeting-banner">
    <div class="greeting-text">
      <h2>Good morning, {{ Auth::user()->name ?? 'Arjun' }} 👋</h2>
      <p>You have <strong style="color:var(--gold);">2 interviews</strong> this week and <strong style="color:var(--accent);">3 new responses</strong>. Keep the momentum going!</p>
    </div>
    <div class="greeting-streak">
      <div class="streak-item">
        <div class="streak-num" style="color:var(--gold);">🔥 7</div>
        <div class="streak-label">Day streak</div>
      </div>
      <div class="streak-divider"></div>
      <div class="streak-item">
        <div class="streak-num" style="color:var(--accent);">94%</div>
        <div class="streak-label">Profile score</div>
      </div>
      <div class="streak-divider"></div>
      <div class="streak-item">
        <div class="streak-num" style="color:var(--primary2);">Top 12%</div>
        <div class="streak-label">Active seeker rank</div>
      </div>
    </div>
  </div>

  <!-- Upcoming Interview Banner -->
  <div class="interview-banner">
    <div class="interview-icon">🎯</div>
    <div class="interview-text">
      <strong>Upcoming: Technical Interview at Stripe</strong>
      <p>Friday, May 9 · 3:00 PM IST · Video Call (Google Meet)</p>
    </div>
    <div class="interview-countdown">
      <div class="count-box">
        <div class="count-num" id="cntD">01</div>
        <div class="count-label">Days</div>
      </div>
      <div class="count-box">
        <div class="count-num" id="cntH">14</div>
        <div class="count-label">Hrs</div>
      </div>
      <div class="count-box">
        <div class="count-num" id="cntM">22</div>
        <div class="count-label">Min</div>
      </div>
    </div>
    <button class="btn btn-ghost" style="margin-left:8px;">Prep Now →</button>
  </div>

  <!-- Analytics Cards -->
  <div class="analytics-row">
    <div class="stat-card c-primary">
      <div class="stat-icon" style="background:var(--primary-dim);">📤</div>
      <div class="stat-value" style="color:var(--primary2);" data-target="24">0</div>
      <div class="stat-label">Total Applied</div>
      <div class="stat-trend trend-up">↑ +4 this week</div>
    </div>
    <div class="stat-card c-blue">
      <div class="stat-icon" style="background:var(--blue-dim);">👁</div>
      <div class="stat-value" style="color:var(--blue);" data-target="11">0</div>
      <div class="stat-label">Under Review</div>
      <div class="stat-trend trend-up">↑ +2 this week</div>
    </div>
    <div class="stat-card c-gold">
      <div class="stat-icon" style="background:var(--gold-dim);">🗓</div>
      <div class="stat-value" style="color:var(--gold);" data-target="4">0</div>
      <div class="stat-label">Interviews</div>
      <div class="stat-trend trend-up">↑ +2 vs last month</div>
    </div>
    <div class="stat-card c-accent">
      <div class="stat-icon" style="background:var(--accent-dim);">🎉</div>
      <div class="stat-value" style="color:var(--accent);" data-target="1">0</div>
      <div class="stat-label">Offers Received</div>
      <div class="stat-trend trend-up">🔥 New this week!</div>
    </div>
    <div class="stat-card c-pink">
      <div class="stat-icon" style="background:var(--pink-dim);">📈</div>
      <div class="stat-value" style="color:var(--pink);" data-target="17">0</div>
      <div class="stat-label">Success Rate %</div>
      <div class="stat-trend trend-up">↑ +3% vs last month</div>
    </div>
  </div>

  <!-- KANBAN BOARD -->
  <div class="kanban-section">
    <div class="section-head">
      <h3>Application Pipeline</h3>
      <div class="section-head-right">
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Filter</button>
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Sort ↕</button>
        <button class="btn btn-primary" style="font-size:12px;padding:6px 12px;" onclick="openAddModal()">+ Add</button>
      </div>
    </div>

    <div class="kanban-board" id="kanbanBoard">
      <!-- APPLIED -->
      <div class="kanban-col" data-col="applied" ondragover="onDragOver(event)" ondrop="onDrop(event,'applied')">
        <div class="col-header">
          <div class="col-title-row">
            <div class="col-dot" style="background:#6C63FF;box-shadow:0 0 6px rgba(108,99,255,0.5);"></div>
            <span class="col-title" style="color:var(--primary2);">Applied</span>
          </div>
          <span class="col-count" id="cnt-applied">3</span>
        </div>
        <div class="col-cards" id="cards-applied">
          <div class="k-card" draggable="true" ondragstart="onDragStart(event,'google-swe','applied')" id="card-google-swe">
            <div style="position:absolute;top:0;left:0;width:3px;bottom:0;border-radius:12px 0 0 12px;background:var(--primary);"></div>
            <div class="kc-top">
              <div class="kc-company">
                <div class="kc-logo" style="background:linear-gradient(135deg,#4285F4,#34A853);color:white;">G</div>
                <span class="kc-company-name">Google</span>
              </div>
              <span class="kc-urgency urgency-hot">🔥 Hot</span>
            </div>
            <div class="kc-title">Senior Software Engineer</div>
            <div class="kc-tags">
              <span class="kc-tag">React</span><span class="kc-tag">Node.js</span><span class="kc-tag">GCP</span>
            </div>
            <div class="kc-footer">
              <span class="kc-date">📅 Applied May 2</span>
              <span class="kc-salary">$180K+</span>
            </div>
          </div>
          <!-- More cards could be dynamically loaded here -->
        </div>
        <button class="add-card-btn" onclick="openAddModal()">
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          Add application
        </button>
      </div>

      <!-- Other columns (Under Review, Interview, Offer, Rejected) -->
      @php
        $columns = [
          ['id' => 'review', 'title' => 'Under Review', 'color' => '#38BDF8', 'count' => 2],
          ['id' => 'interview', 'title' => 'Interview', 'color' => '#F5A623', 'count' => 2],
          ['id' => 'offer', 'title' => 'Offer', 'color' => '#00CBA8', 'count' => 1],
          ['id' => 'rejected', 'title' => 'Rejected', 'color' => '#FF4D6A', 'count' => 2],
        ];
      @endphp

      @foreach($columns as $col)
        <div class="kanban-col" data-col="{{ $col['id'] }}" ondragover="onDragOver(event)" ondrop="onDrop(event,'{{ $col['id'] }}')">
          <div class="col-header">
            <div class="col-title-row">
              <div class="col-dot" style="background:{{ $col['color'] }};box-shadow:0 0 6px {{ $col['color'] }}80;"></div>
              <span class="col-title" style="color:{{ $col['color'] }};">{{ $col['title'] }}</span>
            </div>
            <span class="col-count" id="cnt-{{ $col['id'] }}">{{ $col['count'] }}</span>
          </div>
          <div class="col-cards" id="cards-{{ $col['id'] }}">
            <!-- Dummy content for now -->
            @if($col['id'] == 'review')
               <div class="k-card" draggable="true" ondragstart="onDragStart(event,'stripe-design','review')" id="card-stripe-design">
                <div style="position:absolute;top:0;left:0;width:3px;bottom:0;border-radius:12px 0 0 12px;background:#38BDF8;"></div>
                <div class="kc-top">
                  <div class="kc-company">
                    <div class="kc-logo" style="background:linear-gradient(135deg,#635BFF,#9B94FF);color:white;">S</div>
                    <span class="kc-company-name">Stripe</span>
                  </div>
                  <span class="kc-urgency urgency-hot">👀 Active</span>
                </div>
                <div class="kc-title">Product Designer</div>
                <div class="kc-tags"><span class="kc-tag">Figma</span></div>
                <div class="kc-footer"><span class="kc-date">📅 Apr 28</span><span class="kc-salary">$145K+</span></div>
              </div>
            @endif
          </div>
          <button class="add-card-btn" onclick="openAddModal()">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Add application
          </button>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Bottom Grid: Chart + Activity -->
  <div class="bottom-grid">
    <div class="card">
      <div class="section-head">
        <h3>Application Activity</h3>
        <div style="display:flex;gap:6px;">
          <button class="btn btn-ghost" style="font-size:11px;padding:5px 10px;">Week</button>
          <button class="btn btn-primary" style="font-size:11px;padding:5px 10px;">Month</button>
        </div>
      </div>
      <div class="chart-bars">
        @for($i=1; $i<=8; $i++)
          <div class="chart-bar-group">
            <div class="chart-bar" style="height:{{ rand(30, 90) }}%;background:var(--primary-dim);border-top:2px solid var(--primary);"></div>
            <div class="chart-bar" style="height:{{ rand(10, 70) }}%;background:var(--accent-dim);border-top:2px solid var(--accent);"></div>
          </div>
        @endfor
      </div>
      <div class="chart-labels">
        <span class="chart-label">Apr 7</span><span class="chart-label">Apr 14</span><span class="chart-label">Apr 21</span><span class="chart-label">Apr 28</span><span class="chart-label">May 1</span><span class="chart-label">May 5</span><span class="chart-label">May 6</span><span class="chart-label">May 7</span>
      </div>
    </div>

    <div class="card">
      <h3>Recent Activity</h3>
      <div class="activity-feed">
        <div class="activity-item">
          <div class="activity-icon" style="background:var(--accent-dim);">🎉</div>
          <div><div class="activity-text"><strong>Shopify</strong> sent an offer letter!</div><div class="activity-time">2 hours ago</div></div>
        </div>
        <div class="activity-item">
          <div class="activity-icon" style="background:var(--gold-dim);">📅</div>
          <div><div class="activity-text"><strong>Stripe</strong> interview scheduled</div><div class="activity-time">5 hours ago</div></div>
        </div>
      </div>
    </div>
  </div>

  <!-- RECOMMENDED JOBS -->
  <div class="recs-section">
    <div class="section-head">
      <h3>Recommended for You <span style="font-size:11px;color:var(--primary2);background:var(--primary-dim);padding:2px 8px;border-radius:20px;margin-left:8px;font-weight:500;">AI Matched</span></h3>
    </div>
    <div class="recs-grid">
       <div class="rec-card">
        <div class="rec-top">
          <div class="rec-company-row">
            <div class="rec-logo" style="background:linear-gradient(135deg,#FF9500,#FF6B00);color:white;">A</div>
            <div><div class="rec-company">Airbnb</div><div class="rec-title">Staff Engineer</div></div>
          </div>
          <button class="save-btn" onclick="toggleSave(this)">★</button>
        </div>
        <div class="rec-tags"><span class="rec-tag">Go</span><span class="rec-tag">Remote</span></div>
        <div class="rec-footer">
          <div class="rec-salary">$175K <span>/ year</span></div>
          <div class="match-pill" style="background:var(--accent-dim);color:var(--accent);">⚡ 92%</div>
        </div>
      </div>
      <!-- More dummy rec cards -->
    </div>
  </div>

  <!-- ROW 3: ATS Score + Skills + Goals -->
  <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;margin-top:28px;">
    <div class="card">
      <h3>Resume Score</h3>
      <div style="display:flex;align-items:center;gap:20px;margin:20px 0;">
        <div style="position:relative;width:88px;height:88px;">
          <svg width="88" height="88" viewBox="0 0 88 88"><circle cx="44" cy="44" r="36" fill="none" stroke="var(--surface3)" stroke-width="7"/><circle cx="44" cy="44" r="36" fill="none" stroke="var(--primary)" stroke-width="7" stroke-dasharray="226" stroke-dashoffset="54" transform="rotate(-90 44 44)"/></svg>
          <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;">76</div>
        </div>
        <div style="font-size:12px;color:var(--text2);">Your resume passes most ATS filters.</div>
      </div>
      <button class="btn btn-primary" style="width:100%;justify-content:center;">Update Resume</button>
    </div>

    <div class="card">
      <h3>Skill Snapshot</h3>
      <div style="display:flex;flex-direction:column;gap:10px;margin-top:15px;">
        <div style="font-size:12px;">React / Next.js <div style="height:4px;background:var(--surface3);margin-top:4px;"><div style="width:92%;height:100%;background:var(--primary);"></div></div></div>
        <div style="font-size:12px;">TypeScript <div style="height:4px;background:var(--surface3);margin-top:4px;"><div style="width:85%;height:100%;background:var(--primary);"></div></div></div>
      </div>
    </div>

    <div class="card">
      <h3>Weekly Goals</h3>
      <div style="display:flex;flex-direction:column;gap:10px;margin-top:15px;">
        <div style="font-size:13px;display:flex;align-items:center;gap:10px;"><input type="checkbox" checked> Apply to 5 jobs</div>
        <div style="font-size:13px;display:flex;align-items:center;gap:10px;"><input type="checkbox" checked> Update resume</div>
        <div style="font-size:13px;display:flex;align-items:center;gap:10px;"><input type="checkbox"> Prep interview</div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
<script>
  // COUNTER ANIMATION
  document.querySelectorAll('.stat-value[data-target]').forEach(el => {
    const target = parseInt(el.dataset.target);
    let current = 0;
    const step = Math.ceil(target / 40);
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = current;
      if (current >= target) clearInterval(timer);
    }, 30);
  });

  // DRAG AND DROP
  let dragCard = null;
  function onDragStart(e, cardId, sourceCol) {
    dragCard = cardId;
    e.target.style.opacity = '0.4';
  }
  function onDragOver(e) { e.preventDefault(); }
  function onDrop(e, targetCol) {
    e.preventDefault();
    if (!dragCard) return;
    const card = document.getElementById('card-' + dragCard);
    document.getElementById('cards-' + targetCol).appendChild(card);
    window.showToast('Moved to ' + targetCol);
  }

  function toggleSave(btn) { btn.classList.toggle('saved'); window.showToast('Saved'); }

  // Countdown timer logic... (omitted for brevity, but same as provided)
</script>
@endsection
