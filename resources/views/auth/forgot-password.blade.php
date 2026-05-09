@extends('layouts.auth')

@section('content')
<section class="left-panel">
  <div class="glow-orb"></div>
  <div class="left-content">
    <a href="/" class="logo">
      <div class="logo-icon">J</div>
      <span class="logo-text">Job<span>Flow</span></span>
    </a>
    <div class="hero-text">
      <h1>Secure Your <br/><span>Account.</span></h1>
      <p>Reset your password instantly to regain access to your professional dashboard.</p>
    </div>
  </div>
</section>

<section class="right-panel">
  <div class="auth-container">
    <div class="form-header">
      <h2 class="form-title">Reset Password ✦</h2>
      <p class="form-subtitle">Enter your email and new password below</p>
    </div>

    @if(session('error'))
      <div style="background: rgba(255, 77, 106, 0.1); border: 1px solid var(--red); color: var(--red); padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 13px;">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      
      <div class="fields">
        <div class="field">
          <label>Email Address</label>
          <div class="field-wrap">
            <div class="field-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <input type="email" name="email" placeholder="you@example.com" required autofocus>
          </div>
        </div>

        <div class="field">
          <label>New Password</label>
          <div class="field-wrap">
            <div class="field-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <input type="password" name="password" placeholder="Min. 8 characters" required>
          </div>
        </div>

        <div class="field">
          <label>Confirm Password</label>
          <div class="field-wrap">
            <div class="field-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 11V7a5 5 0 0 1 10 0v4"/><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/></svg>
            </div>
            <input type="password" name="password_confirmation" placeholder="Repeat password" required>
          </div>
        </div>
      </div>

      <button type="submit" class="submit-btn">
        <span>Update Password</span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>

      <div class="auth-switch" style="margin-top: 30px; text-align: center;">
        <a href="{{ route('login') }}" style="color: var(--text3); font-size: 13px; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          Back to Sign In
        </a>
      </div>
    </form>
  </div>
</section>
@endsection
