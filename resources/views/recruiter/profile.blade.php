@extends('layouts.dashboard')

@section('title', 'Company Profile')

@section('head')
<style>
    .profile-card {
        background: var(--s1);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 40px;
        max-width: 720px;
        margin: 0 auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 20px;
        margin-bottom: 40px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 30px;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 30px;
        background: linear-gradient(135deg, var(--teal), var(--sky));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 44px;
        color: #060912;
        font-family: 'DM Serif Display', serif;
        box-shadow: 0 10px 25px rgba(45, 212, 191, 0.3);
    }
    .profile-info h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 32px;
        color: var(--text);
        margin-bottom: 8px;
    }
    .profile-info p {
        color: var(--text3);
        font-size: 15px;
    }
    .form-group {
        margin-bottom: 24px;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text3);
        margin-bottom: 10px;
    }
    .form-input {
        width: 100%;
        background: var(--s2);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px 18px;
        color: var(--text);
        font-family: 'Geist', sans-serif;
        font-size: 14px;
        transition: all 0.2s;
    }
    .form-input:focus {
        border-color: var(--teal);
        outline: none;
        background: var(--s3);
    }
    .btn-save {
        background: var(--teal);
        color: #060912;
        border: none;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        margin-top: 12px;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 212, 191, 0.4);
    }
    .btn-logout {
        background: rgba(251, 113, 133, 0.05);
        color: var(--rose);
        border: 1px solid rgba(251, 113, 133, 0.2);
        border-radius: 14px;
        padding: 14px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-logout:hover {
        background: var(--rose);
        color: #060912;
        border-color: var(--rose);
    }
</style>
@endsection

@section('content')
<header class="header" style="margin-bottom: 32px;">
  <div>
    <h1 class="page-title">Company Profile</h1>
    <p class="page-sub">Manage your organization's details and preferences</p>
  </div>
</header>

<div style="padding: 20px 0;">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ substr($user->company ?? $user->name, 0, 1) }}
            </div>
        </div>

        <form action="{{ route('recruiter.profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" name="company" class="form-input" value="{{ old('company', $user->company) }}" placeholder="e.g. Acme Corp">
            </div>

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" placeholder="Your name">
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" placeholder="Work email">
            </div>

            <button type="submit" class="btn-save">Update Profile</button>
        </form>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Logout from Session
            </button>
        </form>
    </div>
</div>
@endsection
