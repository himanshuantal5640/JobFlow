@extends('layouts.auth')

@section('content')
<section class="left-panel">
  <div class="left-content">
    <div style="margin-bottom: 40px;">
        <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.15em; color: #7C6FFF; text-transform: uppercase;">— YOUR CAREER COMMAND CENTER</span>
        <h1 style="font-family: 'Syne', sans-serif; font-size: 64px; font-weight: 800; line-height: 1; letter-spacing: -2px; color: white; margin: 20px 0;">Track. Apply. <br/><span style="background: linear-gradient(to right, #A091FF, #00D4AA); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Get Hired.</span></h1>
        <p style="color: #9A9AB8; font-size: 16px; line-height: 1.6; max-width: 440px;">Join 48,000+ professionals who use JobFlow to stay on top of every application, land more interviews, and negotiate better offers.</p>
    </div>

    <!-- Kanban Preview -->
    <div class="kanban-preview" style="position: relative; margin-bottom: 50px;">
        <div class="kanban-header">
            <span style="font-family: 'Syne', sans-serif; font-size: 15px; color: #9A9AB8; font-weight: 700;">My Applications — Q2 2025</span>
            <div style="display: flex; gap: 6px;">
                <div style="width: 10px; height: 10px; background: #FF4D6A; border-radius: 50%;"></div>
                <div style="width: 10px; height: 10px; background: #F5A623; border-radius: 50%;"></div>
                <div style="width: 10px; height: 10px; background: #00D4AA; border-radius: 50%;"></div>
            </div>
        </div>
        
        <div class="kanban-cols" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px;">
            @php 
                $cols = [
                    'APPLIED' => ['color' => '#7C6FFF', 'cards' => 3],
                    'REVIEW' => ['color' => '#00D4AA', 'cards' => 2],
                    'INTERVIEW' => ['color' => '#F5A623', 'cards' => 2],
                    'OFFER' => ['color' => '#00F5C4', 'cards' => 1],
                    'REJECT' => ['color' => '#FF4D6A', 'cards' => 1]
                ]; 
            @endphp
            @foreach($cols as $name => $data)
                <div class="kanban-col">
                    <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: {{ $data['color'] }}; margin-bottom: 16px; text-align: center; letter-spacing: 0.1em;">{{ $name }}</div>
                    <div class="kanban-cards">
                        @for($i=0; $i<$data['cards']; $i++)
                            <div class="kanban-card">
                                <div class="card-dot" style="background: {{ $data['color'] }};"></div>
                                <div class="card-line"></div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>
        <div class="floating-badge">
            <span style="font-size: 18px;">✦</span> 1 new offer received!
        </div>
    </div>

    <!-- Trust Badges -->
    <div style="display: flex; gap: 24px; margin-top: 40px;">
        <div class="trust-badge">
            <div class="trust-icon-box" style="background: rgba(0, 212, 170, 0.1);"><svg width="14" height="14" fill="#00D4AA" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg></div>
            SSL Secured
        </div>
        <div class="trust-badge">
            <div class="trust-icon-box" style="background: rgba(124, 111, 255, 0.1);"><svg width="14" height="14" fill="#7C6FFF" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg></div>
            GDPR Compliant
        </div>
        <div class="trust-badge">
            <div class="trust-icon-box" style="background: rgba(245, 166, 35, 0.1);"><svg width="14" height="14" fill="#F5A623" viewBox="0 0 24 24"><path d="M7 2v11h3v9l7-12h-4l4-8z"/></svg></div>
            Free Forever Plan
        </div>
    </div>
  </div>
</section>

<section class="right-panel">
  <div class="auth-container">
    <div class="tab-switcher">
      <div class="tab-indicator"></div>
      <a href="{{ route('login') }}" class="tab-btn active">Sign In</a>
      <a href="{{ route('register') }}" class="tab-btn">Create Account</a>
    </div>

    <div class="form-header">
      <h2 class="form-title">Welcome back 👋</h2>
      <p class="form-subtitle">Don't have an account? <a href="{{ route('register') }}">Sign up free</a></p>
    </div>

    <!-- Social Row -->
    <div class="social-row">
        <button class="social-btn"><img src="https://www.google.com/favicon.ico" width="16"> Google</button>
        <button class="social-btn"><img src="https://www.linkedin.com/favicon.ico" width="16"> LinkedIn</button>
    </div>

    <div class="divider"><span>or continue with email</span></div>

    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      <div class="field">
        <label>Email address</label>
        <div class="field-wrap">
          <div class="field-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
          <input type="email" name="email" placeholder="you@example.com" required>
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="field-wrap">
          <div class="field-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
          <input type="password" name="password" placeholder="Enter your password" required>
          <button type="button" class="eye-toggle"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>
      </div>

      <div class="forgot-row">
        <label class="remember-me">
          <input type="checkbox" name="remember">
          <div class="custom-check"></div>
          <span class="remember-label">Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
      </div>

      <button type="submit" class="submit-btn" style="background: #7C6FFF;">
        <span>Sign In to JobFlow</span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3"/></svg>
      </button>
    </form>
  </div>
</section>
@endsection