@extends('layouts.auth')

@section('content')

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<!-- NAVBAR -->
<nav>
  <a href="{{ url('/') }}" class="logo">
    <div class="logo-icon">J</div>
    <span class="logo-text">Job<span>Flow</span></span>
  </a>
  <a href="{{ url('/') }}" class="nav-back">
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M8.5 2.5L4 7L8.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Back to home
  </a>
</nav>

<main>
  <!-- LEFT PANEL -->
  <div class="left-panel">
    <div style="position:relative;z-index:1;">
      <div class="panel-label">Your Career Command Center</div>
      <h2>Track. Apply.<br><span class="grad">Get Hired.</span></h2>
      <p>Join 48,000+ professionals who use JobFlow to stay on top of every application, land more interviews, and negotiate better offers.</p>

      <!-- MINI KANBAN PREVIEW -->
      <div class="mini-preview">
        <div class="mini-preview-header">
          <span class="mini-preview-title">My Applications — Q2 2025</span>
          <div class="mini-preview-dots">
            <div class="dot" style="background:#FF4D6A;"></div>
            <div class="dot" style="background:#F5A623;"></div>
            <div class="dot" style="background:#00D4AA;"></div>
          </div>
        </div>
        <div class="mini-cols">
          <div class="mini-col">
            <div class="mini-col-label" style="color:#7C6FFF;">Applied</div>
            <div class="mini-card" style="background:rgba(124,111,255,0.08);">
              <div class="mini-card-dot" style="background:#7C6FFF;"></div>
              <div class="mini-card-line"></div>
            </div>
            <div class="mini-card" style="background:rgba(124,111,255,0.08);">
              <div class="mini-card-dot" style="background:#7C6FFF;"></div>
              <div class="mini-card-line"></div>
            </div>
            <div class="mini-card" style="background:rgba(124,111,255,0.08);">
              <div class="mini-card-dot" style="background:#7C6FFF;"></div>
              <div class="mini-card-line"></div>
            </div>
          </div>
          <div class="mini-col">
            <div class="mini-col-label" style="color:#00B4D8;">Review</div>
            <div class="mini-card" style="background:rgba(0,180,216,0.08);">
              <div class="mini-card-dot" style="background:#00B4D8;"></div>
              <div class="mini-card-line"></div>
            </div>
            <div class="mini-card" style="background:rgba(0,180,216,0.08);">
              <div class="mini-card-dot" style="background:#00B4D8;"></div>
              <div class="mini-card-line"></div>
            </div>
          </div>
          <div class="mini-col">
            <div class="mini-col-label" style="color:#F5A623;">Interview</div>
            <div class="mini-card" style="background:rgba(245,166,35,0.08);">
              <div class="mini-card-dot" style="background:#F5A623;"></div>
              <div class="mini-card-line"></div>
            </div>
            <div class="mini-card" style="background:rgba(245,166,35,0.08);">
              <div class="mini-card-dot" style="background:#F5A623;"></div>
              <div class="mini-card-line"></div>
            </div>
          </div>
          <div class="mini-col">
            <div class="mini-col-label" style="color:#00D4AA;">Offer</div>
            <div class="mini-card" style="background:rgba(0,212,170,0.12);border-color:rgba(0,212,170,0.2);">
              <div class="mini-card-dot" style="background:#00D4AA;"></div>
              <div class="mini-card-line"></div>
            </div>
          </div>
          <div class="mini-col">
            <div class="mini-col-label" style="color:#FF4D6A;">Reject</div>
            <div class="mini-card" style="background:rgba(255,77,106,0.08);">
              <div class="mini-card-dot" style="background:#FF4D6A;"></div>
              <div class="mini-card-line"></div>
            </div>
          </div>
        </div>
        <div class="floating-badge">
          ✦ 1 new offer received!
        </div>
      </div>

      <!-- TRUST BADGES -->
      <div class="trust-row" style="margin-top:36px;">
        <div class="trust-item">
          <div class="trust-icon" style="background:rgba(0,212,170,0.1);">🔒</div>
          <span>SSL Secured</span>
        </div>
        <div class="trust-item">
          <div class="trust-icon" style="background:rgba(124,111,255,0.1);">🛡️</div>
          <span>GDPR Compliant</span>
        </div>
        <div class="trust-item">
          <div class="trust-icon" style="background:rgba(245,166,35,0.1);">⚡</div>
          <span>Free Forever Plan</span>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <div class="form-card">

      <!-- TAB SWITCHER -->
      <div class="tab-switcher" id="tabSwitcher">
        <button class="tab-btn" id="loginTab" onclick="switchTab('login')">Sign In</button>
        <button class="tab-btn" id="registerTab" onclick="switchTab('register')">Create Account</button>
        <div class="tab-indicator" id="tabIndicator"></div>
      </div>

      <!-- ════ LOGIN FORM ════ -->
      <div id="loginForm" class="panel-content" style="display:none;">
        <div class="form-header">
          <div class="form-title">Welcome back 👋</div>
          <div class="form-subtitle">Don't have an account? <a onclick="switchTab('register')">Sign up free</a></div>
        </div>

        <!-- Social Auth -->
        <div class="social-row">
          <button class="social-btn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M15.68 8.18c0-.57-.05-1.11-.14-1.64H8v3.1h4.3a3.66 3.66 0 01-1.59 2.4v2h2.57c1.5-1.38 2.37-3.42 2.37-5.86z" fill="#4285F4"/>
              <path d="M8 16c2.16 0 3.97-.71 5.3-1.94l-2.58-2a4.79 4.79 0 01-2.72.75 4.77 4.77 0 01-4.48-3.3H.88v2.06A7.99 7.99 0 008 16z" fill="#34A853"/>
              <path d="M3.52 9.51A4.82 4.82 0 013.27 8c0-.52.09-1.03.25-1.51V4.43H.88A8.01 8.01 0 000 8c0 1.29.31 2.52.88 3.57l2.64-2.06z" fill="#FBBC05"/>
              <path d="M8 3.19c1.22 0 2.3.42 3.16 1.24l2.36-2.36C11.96.79 10.15 0 8 0A7.99 7.99 0 00.88 4.43l2.64 2.06A4.77 4.77 0 018 3.19z" fill="#EA4335"/>
            </svg>
            Google
          </button>
          <button class="social-btn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
              <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 01.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
            </svg>
            LinkedIn
          </button>
        </div>

        <div class="divider"><span>or continue with email</span></div>

        <form method="POST" action="{{ route('login.post') }}" id="loginActualForm">
          @csrf
          @if($errors->any())
            <div style="background: rgba(255, 77, 106, 0.1); border: 1px solid var(--red); color: var(--red); padding: 10px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
          <div class="fields">
            <div class="field" id="loginEmailField">
              <label>Email address</label>
              <div class="field-wrap">
                <span class="field-icon">
                  <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><rect x="1" y="3" width="13" height="9" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M1 5.5L7.5 9L14 5.5" stroke="currentColor" stroke-width="1.3"/></svg>
                </span>
                <input type="email" name="email" placeholder="you@example.com" id="loginEmail" required>
              </div>
              <span class="field-error">Please enter a valid email address</span>
            </div>

            <div class="field" id="loginPasswordField">
              <label>Password</label>
              <div class="field-wrap">
                <span class="field-icon">
                  <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><rect x="3" y="6" width="9" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 6V4.5a2.5 2.5 0 015 0V6" stroke="currentColor" stroke-width="1.3"/></svg>
                </span>
                <input type="password" name="password" placeholder="Enter your password" id="loginPassword" required>
                <button class="eye-toggle" onclick="togglePwd('loginPassword', this)" type="button">
                  <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M1 7.5C1 7.5 3.5 3 7.5 3C11.5 3 14 7.5 14 7.5C14 7.5 11.5 12 7.5 12C3.5 12 1 7.5 1 7.5Z" stroke="currentColor" stroke-width="1.3"/><circle cx="7.5" cy="7.5" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>
                </button>
              </div>
              <span class="field-error">Password must be at least 8 characters</span>
            </div>
          </div>

          <div class="forgot-row">
            <label class="remember-me">
              <input type="checkbox" name="remember" id="rememberMe">
              <div class="custom-check" id="checkVisual">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" id="checkIcon" style="display:none;"><path d="M2 5L4.5 7.5L8 3" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
              <span class="remember-label">Remember me</span>
            </label>
            <a href="#" class="forgot-link">Forgot password?</a>
          </div>

          <button type="submit" class="submit-btn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 3H3a1 1 0 00-1 1v8a1 1 0 001 1h3M10 11l3-3-3-3M13 8H6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Sign In to JobFlow
          </button>
        </form>

        <div class="terms-note">
          By signing in, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
        </div>
      </div>

      <!-- ════ REGISTER FORM ════ -->
      <div id="registerForm" class="panel-content" style="display:none;">

        <!-- Step Indicator -->
        <div class="step-indicator" id="stepIndicator">
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

        <form method="POST" action="{{ route('register.post') }}" id="registerActualForm">
          @csrf
          @if($errors->any())
            <div style="background: rgba(255, 77, 106, 0.1); border: 1px solid var(--red); color: var(--red); padding: 10px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
          <!-- STEP 1: Role Selection -->
          <div id="regStep1" class="panel-content">
            <div class="form-header">
              <div class="form-title">Choose your role ✦</div>
              <div class="form-subtitle">Pick how you'll use JobFlow</div>
            </div>

            <div class="role-selector">
              <div class="role-options" style="grid-template-columns:1fr 1fr;">
                <div class="role-option" id="seekerOption">
                  <input type="radio" name="role" id="roleSeeker" value="seeker" checked>
                  <label for="roleSeeker">
                    <span class="role-emoji-icon">👤</span>
                    <span class="role-name">Job Seeker</span>
                    <span class="role-desc">Find jobs & track applications</span>
                  </label>
                  <div class="role-check">
                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><path d="M1.5 4L3.5 6L6.5 2" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
                  </div>
                </div>
                <div class="role-option recruiter" id="recruiterOption">
                  <input type="radio" name="role" id="roleRecruiter" value="recruiter">
                  <label for="roleRecruiter">
                    <span class="role-emoji-icon">🏢</span>
                    <span class="role-name">Recruiter</span>
                    <span class="role-desc">Post jobs & find talent</span>
                  </label>
                  <div class="role-check">
                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><path d="M1.5 4L3.5 6L6.5 2" stroke="#001a14" stroke-width="1.5" stroke-linecap="round"/></svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Social Auth for Register -->
            <div class="social-row" style="margin-top:16px;">
              <button type="button" class="social-btn">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                  <path d="M15.68 8.18c0-.57-.05-1.11-.14-1.64H8v3.1h4.3a3.66 3.66 0 01-1.59 2.4v2h2.57c1.5-1.38 2.37-3.42 2.37-5.86z" fill="#4285F4"/>
                  <path d="M8 16c2.16 0 3.97-.71 5.3-1.94l-2.58-2a4.79 4.79 0 01-2.72.75 4.77 4.77 0 01-4.48-3.3H.88v2.06A7.99 7.99 0 008 16z" fill="#34A853"/>
                  <path d="M3.52 9.51A4.82 4.82 0 013.27 8c0-.52.09-1.03.25-1.51V4.43H.88A8.01 8.01 0 000 8c0 1.29.31 2.52.88 3.57l2.64-2.06z" fill="#FBBC05"/>
                  <path d="M8 3.19c1.22 0 2.3.42 3.16 1.24l2.36-2.36C11.96.79 10.15 0 8 0A7.99 7.99 0 00.88 4.43l2.64 2.06A4.77 4.77 0 018 3.19z" fill="#EA4335"/>
                </svg>
                Google
              </button>
              <button type="button" class="social-btn">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                  <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 01.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                </svg>
                LinkedIn
              </button>
            </div>

            <div class="divider" style="margin-top:16px;"><span>or use email</span></div>

            <button type="button" class="submit-btn" onclick="goToStep(2)" style="margin-top:4px;">
              Continue with Email
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7H11M8 4L11 7L8 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="terms-note">Already have an account? <a onclick="switchTab('login')" style="color:var(--primary2);cursor:pointer;text-decoration:none;">Sign in</a></div>
          </div>

          <!-- STEP 2: Personal Details -->
          <div id="regStep2" class="panel-content" style="display:none;">
            <div class="form-header">
              <div class="form-title">Create your account</div>
              <div class="form-subtitle">Fill in your details below</div>
            </div>

            <div class="fields">
              <div class="field-row">
                <div class="field">
                  <label>First name</label>
                  <div class="field-wrap">
                    <span class="field-icon">
                      <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><circle cx="7.5" cy="5" r="2.5" stroke="currentColor" stroke-width="1.3"/><path d="M2 13c0-2.76 2.46-5 5.5-5s5.5 2.24 5.5 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    </span>
                    <input type="text" name="first_name" placeholder="Arjun" id="firstName">
                  </div>
                </div>
                <div class="field">
                  <label>Last name</label>
                  <div class="field-wrap">
                    <span class="field-icon">
                      <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><circle cx="7.5" cy="5" r="2.5" stroke="currentColor" stroke-width="1.3"/><path d="M2 13c0-2.76 2.46-5 5.5-5s5.5 2.24 5.5 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                    </span>
                    <input type="text" name="last_name" placeholder="Kapoor" id="lastName">
                  </div>
                </div>
              </div>

              <div class="field">
                <label>Email address</label>
                <div class="field-wrap">
                  <span class="field-icon">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><rect x="1" y="3" width="13" height="9" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M1 5.5L7.5 9L14 5.5" stroke="currentColor" stroke-width="1.3"/></svg>
                  </span>
                  <input type="email" name="email" placeholder="you@example.com" id="regEmail">
                </div>
              </div>

              <div class="field">
                <label>Password</label>
                <div class="field-wrap">
                  <span class="field-icon">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><rect x="3" y="6" width="9" height="7" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 6V4.5a2.5 2.5 0 015 0V6" stroke="currentColor" stroke-width="1.3"/></svg>
                  </span>
                  <input type="password" name="password" placeholder="Min. 8 characters" id="regPassword" oninput="checkStrength(this.value)">
                  <button class="eye-toggle" onclick="togglePwd('regPassword', this)" type="button">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M1 7.5C1 7.5 3.5 3 7.5 3C11.5 3 14 7.5 14 7.5C14 7.5 11.5 12 7.5 12C3.5 12 1 7.5 1 7.5Z" stroke="currentColor" stroke-width="1.3"/><circle cx="7.5" cy="7.5" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>
                  </button>
                </div>
                <div class="password-strength" id="pwStrength" style="display:block;">
                  <div class="strength-bars">
                    <div class="strength-bar" id="sb1"></div>
                    <div class="strength-bar" id="sb2"></div>
                    <div class="strength-bar" id="sb3"></div>
                    <div class="strength-bar" id="sb4"></div>
                  </div>
                  <span class="strength-label" id="strengthLabel">Enter a strong password</span>
                </div>
              </div>

              <!-- Recruiter-only: Company field -->
              <div class="field" id="companyField" style="display:none;">
                <label>Company name</label>
                <div class="field-wrap">
                  <span class="field-icon">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><rect x="1" y="4" width="13" height="10" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 4V2.5a2.5 2.5 0 015 0V4" stroke="currentColor" stroke-width="1.3"/></svg>
                  </span>
                  <input type="text" name="company" placeholder="Acme Corp" id="companyName">
                </div>
              </div>
            </div>

            <div style="display:flex;gap:10px;margin-bottom:18px;">
              <button type="button" class="submit-btn" onclick="goToStep(1)" style="background:var(--surface);color:var(--text2);border:1.5px solid var(--border2);box-shadow:none;flex:0.6;">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M11 7H3M6 4L3 7L6 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Back
              </button>
              <button type="button" class="submit-btn" onclick="goToStep(3)" style="flex:1.4;">
                Continue
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7H11M8 4L11 7L8 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>
            </div>
          </div>

          <!-- STEP 3: Confirm -->
          <div id="regStep3" class="panel-content" style="display:none;">
            <div class="form-header">
              <div class="form-title">Almost there! 🎉</div>
              <div class="form-subtitle">Review and create your account</div>
            </div>

            <div style="background:var(--surface);border:1px solid var(--border2);border-radius:14px;padding:18px;margin-bottom:18px;">
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border);">
                <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:white;" id="avatarInitials">AK</div>
                <div>
                  <div style="font-size:15px;font-weight:600;color:var(--text);" id="confirmName">Arjun Kapoor</div>
                  <div style="font-size:12px;color:var(--text3);" id="confirmEmail">arjun@example.com</div>
                </div>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:13px;color:var(--text3);">Account type</span>
                <span style="font-size:13px;font-weight:600;color:var(--primary2);" id="confirmRole">Job Seeker</span>
              </div>
            </div>

            <div style="display:flex;align-items:flex-start;gap:10px;background:rgba(124,111,255,0.05);border:1px solid rgba(124,111,255,0.15);border-radius:10px;padding:14px;margin-bottom:18px;">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="color:var(--primary2);flex-shrink:0;margin-top:1px;"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 5V8M8 11H8.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              <span style="font-size:12px;color:var(--text2);line-height:1.6;">We'll send a verification email to activate your account. Check your spam folder if you don't see it.</span>
            </div>

            <label class="remember-me" style="margin-bottom:18px;display:flex;align-items:flex-start;gap:10px;">
              <input type="checkbox" id="agreeTerms" required>
              <div class="custom-check" id="termsCheck" onclick="toggleTermsCheck()" style="margin-top:1px;flex-shrink:0;"></div>
              <span class="remember-label" style="font-size:13px;line-height:1.5;">I agree to JobFlow's <a href="#" style="color:var(--primary2);">Terms of Service</a> and <a href="#" style="color:var(--primary2);">Privacy Policy</a></span>
            </label>

            <div style="display:flex;gap:10px;">
              <button type="button" class="submit-btn" onclick="goToStep(2)" style="background:var(--surface);color:var(--text2);border:1.5px solid var(--border2);box-shadow:none;flex:0.6;">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M11 7H3M6 4L3 7L6 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Back
              </button>
              <button type="submit" class="submit-btn" style="flex:1.4;">
                Create Account ✦
              </button>
            </div>
          </div>
        </form>

        <!-- SUCCESS STATE (Handled via Laravel redirect or JS if preferred, but keeping it for UI completeness) -->
        <div id="successState" class="success-msg">
          <div class="success-circle">✓</div>
          <h3>Account Created!</h3>
          <p>Welcome to JobFlow! Check your inbox to verify your email.</p>
          <a href="{{ url('/') }}" class="submit-btn" style="text-decoration:none;justify-content:center;">
            Go to Dashboard →
          </a>
        </div>

      </div>

    </div>
  </div>
</main>

<script>
  let currentStep = 1;
  const initialTab = "{{ $initialTab ?? 'login' }}";

  document.addEventListener('DOMContentLoaded', () => {
    switchTab(initialTab);
  });

  function switchTab(tab) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const indicator = document.getElementById('tabIndicator');

    if (tab === 'login') {
      loginForm.style.display = 'block';
      registerForm.style.display = 'none';
      loginTab.classList.add('active');
      registerTab.classList.remove('active');
      indicator.classList.remove('right');
    } else {
      loginForm.style.display = 'none';
      registerForm.style.display = 'block';
      loginTab.classList.remove('active');
      registerTab.classList.add('active');
      indicator.classList.add('right');
    }
  }

  function goToStep(step) {
    document.getElementById('regStep' + currentStep).style.display = 'none';

    for (let i = 1; i <= 3; i++) {
      const dot = document.getElementById('step' + i + 'dot');
      const circle = dot.querySelector('.step-circle');
      dot.classList.remove('active', 'done');
      if (i < step) {
        dot.classList.add('done');
        circle.innerHTML = '✓';
      } else if (i === step) {
        dot.classList.add('active');
        circle.textContent = i;
      } else {
        circle.textContent = i;
      }
    }

    if (document.getElementById('line12')) {
      document.getElementById('line12').classList.toggle('done', step > 1);
      document.getElementById('line23').classList.toggle('done', step > 2);
    }

    if (step === 3) {
      const fn = document.getElementById('firstName').value || 'Your';
      const ln = document.getElementById('lastName').value || 'Name';
      const em = document.getElementById('regEmail').value || 'your@email.com';
      const roleChecked = document.querySelector('input[name="role"]:checked');
      const role = roleChecked ? (roleChecked.value === 'recruiter' ? 'Recruiter' : 'Job Seeker') : 'Job Seeker';
      document.getElementById('confirmName').textContent = fn + ' ' + ln;
      document.getElementById('confirmEmail').textContent = em;
      document.getElementById('confirmRole').textContent = role;
      document.getElementById('confirmRole').style.color = roleChecked && roleChecked.value === 'recruiter' ? 'var(--accent)' : 'var(--primary2)';
      document.getElementById('avatarInitials').textContent = (fn[0] || 'Y').toUpperCase() + (ln[0] || 'N').toUpperCase();
    }

    currentStep = step;
    const el = document.getElementById('regStep' + step);
    el.style.display = 'block';
    el.style.animation = 'none';
    el.offsetHeight;
    el.style.animation = 'fadeSlideIn 0.35s ease both';

    const companyField = document.getElementById('companyField');
    const roleChecked = document.querySelector('input[name="role"]:checked');
    if (companyField && roleChecked) {
      companyField.style.display = roleChecked.value === 'recruiter' ? 'flex' : 'none';
    }
  }

  function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText
      ? '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M1 7.5C1 7.5 3.5 3 7.5 3C11.5 3 14 7.5 14 7.5C14 7.5 11.5 12 7.5 12C3.5 12 1 7.5 1 7.5Z" stroke="currentColor" stroke-width="1.3"/><circle cx="7.5" cy="7.5" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>'
      : '<svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M2 2L13 13M6.5 6.6A1.5 1.5 0 009.4 8.5M4 4.5C2.7 5.5 1.5 7 1.5 7.5C1.5 7.5 4 12 7.5 12C8.8 12 9.9 11.6 10.8 11M7 3C7.2 3 7.3 3 7.5 3C11.5 3 14 7.5 14 7.5C14 7.5 13.3 8.8 12 10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>';
  }

  function checkStrength(val) {
    const bars = [document.getElementById('sb1'), document.getElementById('sb2'), document.getElementById('sb3'), document.getElementById('sb4')];
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#FF4D6A', '#F5A623', '#00B4D8', '#00D4AA'];
    const labels = ['Too weak', 'Fair', 'Good', 'Strong 🔒'];

    bars.forEach((b, i) => {
      b.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.08)';
    });

    label.textContent = val.length === 0 ? 'Enter a strong password' : labels[score - 1] || labels[0];
    label.style.color = val.length > 0 ? colors[Math.max(0, score - 1)] : 'var(--text3)';
  }

  function toggleTermsCheck() {
    const cb = document.getElementById('agreeTerms');
    const visual = document.getElementById('termsCheck');
    cb.checked = !cb.checked;
    visual.style.background = cb.checked ? 'var(--primary)' : 'var(--surface)';
    visual.style.borderColor = cb.checked ? 'var(--primary)' : 'var(--border2)';
    visual.innerHTML = cb.checked ? '<svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4.5 7.5L8 3" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>' : '';
  }

  document.getElementById('rememberMe').addEventListener('change', function() {
    const visual = document.getElementById('checkVisual');
    const icon = document.getElementById('checkIcon');
    visual.style.background = this.checked ? 'var(--primary)' : 'var(--surface)';
    visual.style.borderColor = this.checked ? 'var(--primary)' : 'var(--border2)';
    icon.style.display = this.checked ? 'block' : 'none';
  });

  document.querySelectorAll('input[name="role"]').forEach(r => {
    r.addEventListener('change', () => {
      const cf = document.getElementById('companyField');
      if (cf) cf.style.display = r.value === 'recruiter' ? 'flex' : 'none';
    });
  });
</script>

@endsection
