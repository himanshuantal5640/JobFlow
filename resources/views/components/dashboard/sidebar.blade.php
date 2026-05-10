<aside class="sidebar">
  <a href="{{ url('/') }}" class="sidebar-logo">
    <div class="logo-mark">J</div>
    <span class="logo-name">Job<span>Flow</span></span>
  </a>

  <div class="sidebar-section">
    <div class="sidebar-section-label">Overview</div>
    <a class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
      <div class="nav-icon">⊞</div>
      Dashboard
    </a>
    <a class="nav-item {{ Request::is('applications*') ? 'active' : '' }}" href="#">
      <div class="nav-icon">📋</div>
      My Applications
      <span class="nav-badge">12</span>
    </a>
    <a class="nav-item {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
      <div class="nav-icon">🔍</div>
      Browse Jobs
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">★</div>
      Saved Jobs
      <span class="nav-badge" style="background:var(--gold);">5</span>
    </a>
  </div>

  <div class="sidebar-section">
    <div class="sidebar-section-label">Tools</div>
    <a class="nav-item" href="#">
      <div class="nav-icon">📄</div>
      Resume Analyzer
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">💬</div>
      Messages
      <span class="nav-badge" style="background:var(--pink);">3</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">📅</div>
      Interviews
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">📊</div>
      Analytics
    </a>
  </div>

  <div class="sidebar-section">
    <div class="sidebar-section-label">Account</div>
    <a class="nav-item" href="#">
      <div class="nav-icon">⚙</div>
      Settings
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">?</div>
      Help Center
    </a>
  </div>

  <div class="sidebar-profile">
    <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'Guest', 0, 2)) }}</div>
    <div>
      <div class="profile-name">{{ Auth::user()->name ?? 'Guest User' }}</div>
      <div class="profile-role">Job Seeker · Pro</div>
    </div>
    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:none;">@csrf</form>
    <svg class="profile-menu-icon" width="14" height="14" viewBox="0 0 14 14" fill="none" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="cursor:pointer;">
        <path d="M2 5L7 9L12 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
  </div>
</aside>
