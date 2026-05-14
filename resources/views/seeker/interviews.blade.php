@extends('layouts.dashboard')

@section('title', 'My Interviews')

@section('styles')
    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--text); }
        .interview-card { background: var(--s1); border-radius: 24px; padding: 24px; border: 1px solid var(--border); margin-bottom: 20px; transition: all 0.3s; }
        .interview-card:hover { border-color: var(--teal); transform: translateY(-5px); }
        .ic-logo { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 24px; color: #060912; }
        .ic-badge { background: rgba(251, 191, 36, 0.1); color: var(--amber); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .ic-info { margin-bottom: 20px; }
        .ic-info-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text3); margin-top: 8px; }
    </style>
@endsection

@section('content')
<header class="header" style="margin-bottom: 32px;">
    <div>
        <h1 class="page-title">My Interviews</h1>
        <p class="page-sub">Manage your upcoming interviews and preparations.</p>
    </div>
</header>

<div class="interviews-grid" style="max-width: 900px;">
    @forelse($interviews as $interview)
        @php
            $colors = ['var(--teal)', 'var(--sky)', 'var(--violet)', 'var(--amber)'];
            $color = $colors[$loop->index % 4];
        @endphp
        <div class="interview-card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px;">
                <div style="display:flex; gap:16px; align-items:center;">
                    <div class="ic-logo" style="background:{{ $color }};">{{ substr($interview->jobPost->company, 0, 1) }}</div>
                    <div>
                        <div style="font-size:12px; color:var(--text3); font-weight:600;">{{ $interview->jobPost->company }}</div>
                        <div style="font-family:'DM Serif Display',serif; font-size:22px; color:var(--text);">{{ $interview->jobPost->title }}</div>
                    </div>
                </div>
                <div class="ic-badge">Upcoming</div>
            </div>
            
            <div class="ic-info">
                <div class="ic-info-item">
                    <span>📅</span>
                    <span>Scheduled for <strong>{{ now()->addDays(2)->format('M d, Y') }} at 10:00 AM</strong></span>
                </div>
                <div class="ic-info-item">
                    <span>🎥</span>
                    <span>Video Call · Link will be sent via email</span>
                </div>
            </div>

            <div style="display:flex; gap:12px; padding-top:20px; border-top:1px solid var(--border);">
                <button class="btn btn-ghost" style="flex:1; justify-content:center;">Reschedule</button>
                <button class="btn btn-teal" style="flex:2; justify-content:center;">Preparation Guide →</button>
            </div>
        </div>
    @empty
        <div style="padding:60px; text-align:center; color:var(--text3); background:var(--s1); border-radius:24px; border:1px solid var(--border);">
            <div style="font-size:48px; margin-bottom:16px;">🗓</div>
            <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:8px;">No interviews scheduled</h3>
            <p>Applied jobs that reach the interview stage will appear here.</p>
            <button class="btn btn-teal" style="margin-top:24px;" onclick="window.location.href='{{ route('jobs.index') }}'">Browse Jobs</button>
        </div>
    @endforelse
</div>
@endsection
