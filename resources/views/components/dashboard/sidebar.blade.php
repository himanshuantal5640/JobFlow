<aside class="sidebar">
  <div class="logo-row">
    <div class="logo-icon">J</div>
    <div class="logo-text">JobFlow</div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Overview</div>
    <a href="{{ auth()->user()->role === 'recruiter' ? route('recruiter.dashboard') : route('dashboard') }}" class="nav-item {{ request()->routeIs('recruiter.dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
      <span>📊</span> Dashboard
    </a>
    
    @if(auth()->user()->role === 'recruiter')
      <a href="{{ route('recruiter.dashboard') }}#section-job-postings" class="nav-item">
        <span>📋</span> My Job Posts
      </a>
      <a href="{{ route('recruiter.dashboard') }}#section-applicants" class="nav-item">
        <span>👥</span> All Applicants
      </a>
    @else
      <a href="{{ route('applications.index') }}" class="nav-item {{ request()->routeIs('applications.index') ? 'active' : '' }}">
        <span>💼</span> My Applications
      </a>
      <a href="{{ route('jobs.index') }}" class="nav-item {{ request()->routeIs('jobs.index') ? 'active' : '' }}">
        <span>🔍</span> Browse Jobs
      </a>
    @endif
  </div>

  <div class="nav-section">
    <div class="nav-label">Hiring</div>
    @if(auth()->user()->role === 'recruiter')
      <a href="{{ route('recruiter.interviews') }}" class="nav-item {{ request()->routeIs('recruiter.interviews') ? 'active' : '' }}"><span>🗓</span> Interviews</a>
      <a href="{{ route('recruiter.offers') }}" class="nav-item {{ request()->routeIs('recruiter.offers') ? 'active' : '' }}"><span>🚀</span> Offers Sent</a>
    @else
      <a href="{{ route('seeker.analyzer') }}" class="nav-item {{ request()->routeIs('seeker.analyzer') ? 'active' : '' }}"><span>📄</span> Resume Analyzer</a>
      <a href="{{ route('seeker.interviews') }}" class="nav-item {{ request()->routeIs('seeker.interviews') ? 'active' : '' }}"><span>🗓</span> My Interviews</a>
    @endif
    
    <a href="{{ route('messages.index') }}" class="nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
      <span>💬</span> Messages
    </a>
  </div>

  <div class="nav-section" style="margin-top:auto;">
    <div class="nav-label">Settings</div>
    <a href="{{ auth()->user()->role === 'recruiter' ? route('recruiter.profile') : route('seeker.profile') }}" class="nav-item {{ request()->routeIs('recruiter.profile') || request()->routeIs('seeker.profile') ? 'active' : '' }}">
      <span>{{ auth()->user()->role === 'recruiter' ? '🏢' : '👤' }}</span> Profile Settings
    </a>
    <form action="{{ route('logout') }}" method="POST" style="margin-top: 8px;">
      @csrf
      <button type="submit" class="nav-item" style="background:none; border:none; width:100%; cursor:pointer; text-align:left;">
        <span>🚪</span> Logout
      </button>
    </form>
  </div>
</aside>
