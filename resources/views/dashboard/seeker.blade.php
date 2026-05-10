@extends('layouts.dashboard')

@section('title', 'Job Seeker Dashboard')
@section('topbar_title', 'Dashboard')

@section('styles')
    @vite(['resources/css/seeker.css'])
@endsection

@section('content')

  <!-- Greeting Banner -->
  <div class="greeting-banner">
    <div class="greeting-text">
      <h2>Good morning, {{ Auth::user()->name }} 👋</h2>
      <p>You have <strong style="color:var(--gold);">{{ $applications->where('status', 'interview')->count() }} interviews</strong> this week and <strong style="color:var(--accent);">{{ $applications->count() }} total applications</strong>. Keep the momentum going!</p>
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
  @if($applications->where('status', 'interview')->count() > 0)
  <div class="interview-banner">
    <div class="interview-icon">🎯</div>
    <div class="interview-text">
      <strong>Upcoming: Interview for {{ $applications->where('status', 'interview')->first()->jobPost->title }}</strong>
      <p>Check your email for calendar invitation · Video Call</p>
    </div>
    <button class="btn btn-ghost" style="margin-left:8px;">Prep Now →</button>
  </div>
  @endif

  <!-- Analytics Cards -->
  <div class="analytics-row">
    <div class="stat-card c-primary">
      <div class="stat-icon" style="background:var(--primary-dim);">📤</div>
      <div class="stat-value" style="color:var(--primary2);" data-target="{{ $applications->count() }}">0</div>
      <div class="stat-label">Total Applied</div>
      <div class="stat-trend trend-up">↑ Recently updated</div>
    </div>
    <div class="stat-card c-blue">
      <div class="stat-icon" style="background:var(--blue-dim);">👁</div>
      <div class="stat-value" style="color:var(--blue);" data-target="{{ $applications->where('status', 'applied')->count() }}">0</div>
      <div class="stat-label">Under Review</div>
      <div class="stat-trend trend-up">↑ Current status</div>
    </div>
    <div class="stat-card c-gold">
      <div class="stat-icon" style="background:var(--gold-dim);">🗓</div>
      <div class="stat-value" style="color:var(--gold);" data-target="{{ $applications->where('status', 'interview')->count() }}">0</div>
      <div class="stat-label">Interviews</div>
      <div class="stat-trend trend-up">↑ Actively scheduled</div>
    </div>
    <div class="stat-card c-accent">
      <div class="stat-icon" style="background:var(--accent-dim);">🎉</div>
      <div class="stat-value" style="color:var(--accent);" data-target="{{ $applications->where('status', 'offer')->count() }}">0</div>
      <div class="stat-label">Offers Received</div>
      <div class="stat-trend trend-up">🔥 New this week!</div>
    </div>
    <div class="stat-card c-pink">
      <div class="stat-icon" style="background:var(--pink-dim);">📈</div>
      <div class="stat-value" style="color:var(--pink);" data-target="17">0</div>
      <div class="stat-label">Success Rate %</div>
      <div class="stat-trend trend-up">↑ Profile power</div>
    </div>
  </div>

  <!-- KANBAN BOARD -->
  <div class="kanban-section">
    <div class="section-head">
      <h3>Application Pipeline</h3>
      <div class="section-head-right">
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Filter</button>
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Sort ↕</button>
      </div>
    </div>

    <div class="kanban-board" id="kanbanBoard">
      <!-- APPLIED -->
      @php
        $statuses = [
            'applied' => ['title' => 'Applied', 'color' => '#6C63FF'],
            'shortlisted' => ['title' => 'Shortlisted', 'color' => '#38BDF8'],
            'interview' => ['title' => 'Interview', 'color' => '#F5A623'],
            'offer' => ['title' => 'Offer', 'color' => '#00CBA8'],
            'rejected' => ['title' => 'Rejected', 'color' => '#FF4D6A'],
        ];
      @endphp

      @foreach($statuses as $status => $data)
      <div class="kanban-col" data-col="{{ $status }}" ondragover="onDragOver(event)" ondrop="onDrop(event,'{{ $status }}')">
        <div class="col-header">
          <div class="col-title-row">
            <div class="col-dot" style="background:{{ $data['color'] }};box-shadow:0 0 6px {{ $data['color'] }}80;"></div>
            <span class="col-title" style="color:{{ $data['color'] }};">{{ $data['title'] }}</span>
          </div>
          <span class="col-count" id="cnt-{{ $status }}">{{ $applications->where('status', $status)->count() }}</span>
        </div>
        <div class="col-cards" id="cards-{{ $status }}">
          @foreach($applications->where('status', $status) as $app)
          <div class="k-card" draggable="true" id="card-{{ $app->id }}">
            <div style="position:absolute;top:0;left:0;width:3px;bottom:0;border-radius:12px 0 0 12px;background:{{ $data['color'] }};"></div>
            <div class="kc-top">
              <div class="kc-company">
                <div class="kc-logo" style="background:linear-gradient(135deg,#4285F4,#34A853);color:white;">{{ substr($app->jobPost->title, 0, 1) }}</div>
                <span class="kc-company-name">{{ $app->jobPost->company }}</span>
              </div>
            </div>
            <div class="kc-title">{{ $app->jobPost->title }}</div>
            <div class="kc-tags">
              @foreach(json_decode($app->jobPost->skills ?? '[]') as $skill)
                <span class="kc-tag">{{ $skill }}</span>
              @endforeach
            </div>
            <div class="kc-footer">
              <span class="kc-date">📅 {{ $app->created_at->format('M d') }}</span>
              <span class="kc-salary">{{ $app->jobPost->salary }}</span>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- RECOMMENDED JOBS -->
  <div class="recs-section">
    <div class="section-head">
      <h3>Recommended for You <span style="font-size:11px;color:var(--primary2);background:var(--primary-dim);padding:2px 8px;border-radius:20px;margin-left:8px;font-weight:500;">AI Matched</span></h3>
    </div>
    <div class="recs-grid">
      @foreach($recommendedJobs as $job)
       <div class="rec-card">
        <div class="rec-top">
          <div class="rec-company-row">
            <div class="rec-logo" style="background:linear-gradient(135deg,#FF9500,#FF6B00);color:white;">{{ substr($job->title, 0, 1) }}</div>
            <div><div class="rec-company">{{ $job->company }}</div><div class="rec-title">{{ $job->title }}</div></div>
          </div>
          <button class="save-btn">★</button>
        </div>
        <div class="rec-tags">
            @foreach(json_decode($job->skills ?? '[]') as $skill)
                <span class="rec-tag">{{ $skill }}</span>
            @endforeach
        </div>
        <div class="rec-footer">
          <div class="rec-salary">{{ $job->salary }}</div>
          <div class="match-pill" style="background:var(--accent-dim);color:var(--accent);">⚡ {{ $job->match }}%</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

@endsection

@section('scripts')
<script>
  // COUNTER ANIMATION
  document.querySelectorAll('.stat-value[data-target]').forEach(el => {
    const target = parseInt(el.dataset.target);
    let current = 0;
    const step = Math.max(1, Math.ceil(target / 40));
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = current;
      if (current >= target) clearInterval(timer);
    }, 30);
  });
</script>
@endsection
