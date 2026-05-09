<header class="topbar">
  <div>
    <div class="topbar-title">@yield('topbar_title', 'Dashboard')</div>
    <div class="topbar-sub">{{ now()->format('l, j F Y') }}</div>
  </div>
  <div class="topbar-right">
    <div class="tooltip-wrap">
      <div class="icon-btn">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.4"/><path d="M10.5 10.5L14 14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
      </div>
      <div class="tooltip">Search</div>
    </div>
    <div class="tooltip-wrap">
      <div class="icon-btn" style="position:relative;">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 1.5C5.5 1.5 3.5 3.5 3.5 6V9.5L2 11H14L12.5 9.5V6C12.5 3.5 10.5 1.5 8 1.5Z" stroke="currentColor" stroke-width="1.4"/><path d="M6.5 11C6.5 11.83 7.17 12.5 8 12.5C8.83 12.5 9.5 11.83 9.5 11" stroke="currentColor" stroke-width="1.4"/></svg>
        <div class="notif-dot"></div>
      </div>
      <div class="tooltip">Notifications</div>
    </div>
    <button class="btn btn-primary" onclick="openAddModal()">
      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 2V12M2 7H12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
      Add Application
    </button>
  </div>
</header>
