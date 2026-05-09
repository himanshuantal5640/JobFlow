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
        <div class="kanban-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; gap: 6px;">
                <div style="width: 10px; height: 10px; background: #FF4D6A; border-radius: 50%;"></div>
                <div style="width: 10px; height: 10px; background: #F5A623; border-radius: 50%;"></div>
                <div style="width: 10px; height: 10px; background: #00D4AA; border-radius: 50%;"></div>
            </div>
            <span style="font-family: 'Syne', sans-serif; font-size: 15px; color: #9A9AB8; font-weight: 700;">My Applications — Q2 2025</span>
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
  <div class="auth-container" style="max-width: 500px;">
    <div class="tab-switcher">
      <div class="tab-indicator right"></div>
      <a href="{{ route('login') }}" class="tab-btn">Sign In</a>
      <a href="{{ route('register') }}" class="tab-btn active">Create Account</a>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator" style="margin-top: 40px;">
      <div class="step-dot active" id="step1dot">
        <div class="step-circle">1</div>
        <span class="step-text">Role</span>
      </div>
      <div class="step-line" id="line12"></div>
      <div class="step-dot" id="step2dot">
        <div class="step-circle">2</div>
        <span class="step-text">Details</span>
      </div>
      <div class="step-line" id="line23"></div>
      <div class="step-dot" id="step3dot">
        <div class="step-circle">3</div>
        <span class="step-text">Confirm</span>
      </div>
    </div>

    <form method="POST" action="{{ route('register.post') }}" id="registerForm">
      @csrf
      
      <!-- STEP 1: Role Selection -->
      <div id="regStep1">
        <div class="form-header">
          <h2 class="form-title">Choose your role ✦</h2>
          <p class="form-subtitle">Pick how you'll use JobFlow</p>
        </div>

        <div class="role-options" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div class="role-option">
                <input type="radio" name="role" id="seeker" value="seeker" checked>
                <label for="seeker" style="padding: 24px;">
                    <div style="background: rgba(124,111,255,0.1); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;"><svg width="24" height="24" fill="#7C6FFF" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg></div>
                    <span class="role-name" style="font-size: 15px;">Job Seeker</span>
                    <span class="role-desc">Find jobs & track applications</span>
                </label>
                <div class="role-check">✓</div>
            </div>
            <div class="role-option recruiter">
                <input type="radio" name="role" id="recruiter" value="recruiter">
                <label for="recruiter" style="padding: 24px;">
                    <div style="background: rgba(0,212,170,0.1); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;"><svg width="24" height="24" fill="#00D4AA" viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg></div>
                    <span class="role-name" style="font-size: 15px;">Recruiter</span>
                    <span class="role-desc">Post jobs & find talent</span>
                </label>
                <div class="role-check">✓</div>
            </div>
        </div>

        <div class="social-row">
            <button type="button" class="social-btn"><img src="https://www.google.com/favicon.ico" width="14"> Continue with Google</button>
            <button type="button" class="social-btn"><img src="https://www.linkedin.com/favicon.ico" width="14"> Continue with LinkedIn</button>
        </div>

        <div class="divider"><span>or use email</span></div>

        <button type="button" onclick="nextStep(2)" class="submit-btn">
          <span>Continue with Email →</span>
        </button>
      </div>

      <!-- STEP 2: Details -->
      <div id="regStep2" style="display: none;">
        <div class="form-header">
          <h2 class="form-title">Create your account ✦</h2>
          <p class="form-subtitle">Fill in your details below</p>
        </div>

        <div class="field-row">
            <div class="field">
                <label>First name</label>
                <div class="field-wrap">
                    <div class="field-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <input type="text" name="first_name" placeholder="Arjun" required>
                </div>
            </div>
            <div class="field">
                <label>Last name</label>
                <div class="field-wrap">
                    <div class="field-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <input type="text" name="last_name" placeholder="Kapoor" required>
                </div>
            </div>
        </div>

        <div class="field">
            <label>Email address</label>
            <div class="field-wrap">
                <div class="field-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
                <input type="email" name="email" placeholder="you@example.com" required>
            </div>
        </div>

        <div class="field">
            <label>Password</label>
            <div class="field-wrap">
                <div class="field-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
                <input type="password" name="password" placeholder="Min. 8 characters" required>
                <button type="button" class="eye-toggle"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            <p style="font-size: 11px; color: var(--text3); margin-top: 6px;">Enter a strong password</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-top: 30px;">
            <button type="button" onclick="nextStep(1)" class="submit-btn" style="background: transparent; border: 1px solid var(--border); color: white;">
                ← Back
            </button>
            <button type="submit" class="submit-btn">
                Continue →
            </button>
        </div>
      </div>
    </form>
  </div>
</section>

<script>
  function nextStep(step) {
    const step1 = document.getElementById('regStep1');
    const step2 = document.getElementById('regStep2');
    const dot2 = document.getElementById('step2dot');
    const line12 = document.getElementById('line12');

    if (step === 2) {
      step1.style.display = 'none';
      step2.style.display = 'block';
      dot2.classList.add('active', 'done');
      line12.classList.add('done');
    } else {
      step2.style.display = 'none';
      step1.style.display = 'block';
      dot2.classList.remove('active', 'done');
      line12.classList.remove('done');
    }
  }
</script>
@endsection