@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('styles')
    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--text); }
        .profile-card { background: var(--s1); border-radius: 24px; padding: 40px; border: 1px solid var(--border); max-width: 600px; }
        .profile-avatar { width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, var(--teal), var(--sky)); display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 32px; color: #060912; margin-bottom: 32px; }
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
        .form-input { width: 100%; background: var(--s2); border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; color: var(--text); font-family: inherit; font-size: 14px; transition: all 0.2s; }
        .form-input:focus { border-color: var(--teal); outline: none; background: var(--s3); }
    </style>
@endsection

@section('content')
<header class="header" style="margin-bottom: 32px;">
    <div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-sub">Manage your personal information and account settings.</p>
    </div>
</header>

<div class="profile-card">
    <div class="profile-avatar">{{ substr($user->name, 0, 1) }}</div>

    <form action="{{ route('seeker.profile.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" placeholder="Your name">
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" placeholder="Your email">
        </div>

        <div style="padding-top:16px; border-top:1px solid var(--border); margin-top:32px;">
            <button type="submit" class="btn btn-teal" style="width:100%; justify-content:center;">Update Profile</button>
        </div>
    </form>

    <form action="{{ route('logout') }}" method="POST" style="margin-top: 16px;">
        @csrf
        <button type="submit" class="btn btn-ghost" style="width:100%; justify-content:center; color:var(--rose);">Logout from Session</button>
    </form>
</div>
@endsection
