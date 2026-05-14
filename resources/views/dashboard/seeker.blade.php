@extends('layouts.dashboard')

@section('title', 'Seeker Dashboard')

@section('styles')
    @vite(['resources/css/seeker.css'])
    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--text); }
        .stat-card { border-radius: 20px; border: 1px solid var(--border); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--teal); box-shadow: 0 10px 30px rgba(0,0,0,0.4); }
        .kanban-col { background: var(--s1); border-radius: 24px; padding: 20px; border: 1px solid var(--border); }
        .k-card { background: var(--s2); border-radius: 16px; border: 1px solid var(--border); padding: 16px; margin-bottom: 12px; transition: all 0.2s; }
        .k-card:hover { border-color: var(--teal); background: var(--s3); }
    </style>
@endsection

@section('content')
  <header class="header" style="margin-bottom: 32px;">
    <div>
      <h1 class="page-title">Welcome back, {{ Auth::user()->name }} 👋</h1>
      <p class="page-sub">You have {{ $applications->where('status', 'interview')->count() }} interviews this week. Keep going!</p>
    </div>
    <div class="header-actions">
      <div class="icon-btn" style="background:var(--s2); padding:8px; border-radius:10px; cursor:pointer;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
      <a href="{{ route('messages.index') }}" class="icon-btn" style="background:var(--s2); padding:8px; border-radius:10px; cursor:pointer; position:relative; text-decoration:none; color:inherit;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          @if($messagesCount > 0)<div style="position:absolute; top:8px; right:8px; width:8px; height:8px; background:var(--teal); border-radius:50%; border:2px solid var(--s2);"></div>@endif
      </a>
      <button class="btn btn-teal" onclick="window.location.href='{{ route('jobs.index') }}'">Browse All Jobs</button>
    </div>
  </header>

  <!-- Analytics Cards -->
  <div class="analytics-row">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(45, 212, 191, 0.1); color:var(--teal);">📤</div>
      <div class="stat-value" style="color:var(--teal);" data-target="{{ $applications->count() }}">0</div>
      <div class="stat-label">Total Applied</div>
      <div class="stat-trend trend-up">↑ Live status</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(56, 189, 248, 0.1); color:var(--sky);">👁</div>
      <div class="stat-value" style="color:var(--sky);" data-target="{{ $applications->where('status', 'applied')->count() }}">0</div>
      <div class="stat-label">Under Review</div>
      <div class="stat-trend trend-up">↑ Current</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(251, 191, 36, 0.1); color:var(--amber);">🗓</div>
      <div class="stat-value" style="color:var(--amber);" data-target="{{ $applications->where('status', 'interview')->count() }}">0</div>
      <div class="stat-label">Interviews</div>
      <div class="stat-trend trend-up">↑ Scheduled</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(167, 139, 250, 0.1); color:var(--violet);">🎉</div>
      <div class="stat-value" style="color:var(--violet);" data-target="{{ $applications->where('status', 'offer')->count() }}">0</div>
      <div class="stat-label">Offers Received</div>
      <div class="stat-trend trend-up">🔥 New!</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(251, 113, 133, 0.1); color:var(--rose);">📈</div>
      <div class="stat-value" style="color:var(--rose);" data-target="94">0</div>
      <div class="stat-label">Profile Score</div>
      <div class="stat-trend trend-up">↑ Top 10%</div>
    </div>
  </div>

  <!-- KANBAN BOARD -->
  <div class="kanban-section">
    <div class="section-head">
      <h3 style="font-family:'DM Serif Display',serif; font-size:22px;">Application Pipeline</h3>
      <div class="section-head-right">
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Filter</button>
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Sort ↕</button>
      </div>
    </div>

    <div class="kanban-board" id="kanbanBoard">
      @php
        $statuses = [
            'applied' => ['title' => 'Applied', 'color' => 'var(--teal)'],
            'shortlisted' => ['title' => 'Shortlisted', 'color' => 'var(--sky)'],
            'interview' => ['title' => 'Interview', 'color' => 'var(--amber)'],
            'offer' => ['title' => 'Offer', 'color' => 'var(--violet)'],
            'rejected' => ['title' => 'Rejected', 'color' => 'var(--rose)'],
        ];
      @endphp

      @foreach($statuses as $status => $data)
      <div class="kanban-col">
        <div class="col-header">
          <div class="col-title-row">
            <div class="col-dot" style="background:{{ $data['color'] }}; box-shadow: 0 0 10px {{ $data['color'] }};"></div>
            <span class="col-title" style="color:{{ $data['color'] }};">{{ $data['title'] }}</span>
          </div>
          <span class="col-count">{{ $applications->where('status', $status)->count() }}</span>
        </div>
        <div class="col-cards">
          @foreach($applications->where('status', $status) as $app)
          @php
            $colors = ['var(--teal)', 'var(--sky)', 'var(--violet)', 'var(--amber)'];
            $color = $colors[$loop->index % 4];
          @endphp
          <div class="k-card" onclick="window.location.href='{{ route('jobs.show', $app->jobPost->id) }}'" style="cursor:pointer; position:relative;">
            <div style="position:absolute; top:0; left:0; width:4px; bottom:0; border-radius:12px 0 0 12px; background:{{ $data['color'] }};"></div>
            <div class="kc-top">
              <div class="kc-company">
                <div class="kc-logo" style="background:{{ $color }}; color:#060912;">{{ substr($app->jobPost->company, 0, 1) }}</div>
                <span class="kc-company-name">{{ $app->jobPost->company }}</span>
              </div>
            </div>
            <div class="kc-title">{{ $app->jobPost->title }}</div>
            <div class="kc-tags">
              @foreach($app->jobPost->skillsList() as $skill)
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
      <h3 style="font-family:'DM Serif Display',serif; font-size:22px;">Recommended for You</h3>
      <button class="btn btn-ghost" style="font-size:12px;" onclick="window.location.href='{{ route('jobs.index') }}'">View More →</button>
    </div>
    <div class="recs-grid">
      @foreach($recommendedJobs as $job)
       @php
        $colors = ['var(--teal)', 'var(--sky)', 'var(--violet)', 'var(--amber)'];
        $color = $colors[$loop->index % 4];
       @endphp
       <div class="rec-card" onclick="window.location.href='{{ route('jobs.show', $job->id) }}'" style="cursor:pointer; background:var(--s1); border-radius:24px;">
        <div class="rec-top">
          <div class="rec-company-row">
            <div class="rec-logo" style="background:{{ $color }}; color:#060912;">{{ substr($job->company, 0, 1) }}</div>
            <div>
                <div class="rec-company">{{ $job->company }}</div>
                <div class="rec-title">{{ $job->title }}</div>
            </div>
          </div>
          <button class="save-btn">★</button>
        </div>
        <div class="rec-tags">
            @foreach($job->skillsList() as $skill)
                <span class="rec-tag">{{ $skill }}</span>
            @endforeach
        </div>
        <div class="rec-footer">
          <div class="rec-salary">{{ $job->salary }}</div>
          <div class="match-pill" style="background:rgba(45, 212, 191, 0.1); color:var(--teal);">⚡ {{ $job->match }}%</div>
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
