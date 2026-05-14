@extends('layouts.dashboard')

@section('title', 'My Applications')

@section('styles')
    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--text); }
        .app-list-card { background: var(--s1); border-radius: 20px; border: 1px solid var(--border); padding: 16px 24px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s; }
        .app-list-card:hover { border-color: var(--teal); transform: translateX(5px); }
        .al-logo { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 20px; color: #060912; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .status-applied { background: rgba(45, 212, 191, 0.1); color: var(--teal); }
        .status-shortlisted { background: rgba(56, 189, 248, 0.1); color: var(--sky); }
        .status-interview { background: rgba(251, 191, 36, 0.1); color: var(--amber); }
        .status-offer { background: rgba(167, 139, 250, 0.1); color: var(--violet); }
        .status-rejected { background: rgba(251, 113, 133, 0.1); color: var(--rose); }
    </style>
@endsection

@section('content')
<header class="header" style="margin-bottom: 32px;">
    <div>
        <h1 class="page-title">My Applications</h1>
        <p class="page-sub">Track all your submitted applications and their current status.</p>
    </div>
</header>

<div class="applications-list">
    @forelse($applications as $app)
        @php
            $colors = ['var(--teal)', 'var(--sky)', 'var(--violet)', 'var(--amber)'];
            $color = $colors[$loop->index % 4];
        @endphp
        <div class="app-list-card" onclick="window.location.href='{{ route('jobs.show', $app->jobPost->id) }}'" style="cursor:pointer;">
            <div style="display:flex; align-items:center; gap:20px;">
                <div class="al-logo" style="background:{{ $color }};">{{ substr($app->jobPost->company, 0, 1) }}</div>
                <div>
                    <div style="font-family:'DM Serif Display',serif; font-size:18px; color:var(--text);">{{ $app->jobPost->title }}</div>
                    <div style="font-size:12px; color:var(--text3);">{{ $app->jobPost->company }} · Applied on {{ $app->created_at->format('M d, Y') }}</div>
                </div>
            </div>
            
            <div style="display:flex; align-items:center; gap:32px;">
                @if($app->status === 'offer')
                    <div style="text-align:right;">
                        <div style="font-size:14px; font-weight:700; color:var(--violet);">{{ $app->offer_details['salary'] ?? '' }}</div>
                        <a href="{{ route('applications.offer', $app) }}" onclick="event.stopPropagation()" style="font-size:15px; color:var(--violet); font-weight:700; text-decoration:none; margin-top:8px; display:block;">Review Offer →</a>
                    </div>
                @endif
                <div class="status-badge status-{{ $app->status }}">
                    {{ ucfirst($app->status) }}
                </div>
                <div style="color:var(--text3); font-size:18px;">›</div>
            </div>
        </div>
    @empty
        <div style="padding:60px; text-align:center; color:var(--text3); background:var(--s1); border-radius:24px; border:1px solid var(--border);">
            <div style="font-size:48px; margin-bottom:16px;">📂</div>
            <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:8px;">No applications yet</h3>
            <p>Start your career journey by applying to your first job.</p>
            <button class="btn btn-teal" style="margin-top:24px;" onclick="window.location.href='{{ route('jobs.index') }}'">Browse Jobs</button>
        </div>
    @endforelse
</div>
@endsection
