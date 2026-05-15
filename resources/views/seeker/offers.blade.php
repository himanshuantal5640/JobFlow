@extends('layouts.dashboard')

@section('title', 'My Job Offers')

@section('styles')
    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--text); }
        .offer-list-card { background: var(--s1); border-radius: 24px; border: 1px solid var(--border); padding: 24px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s; position: relative; overflow: hidden; }
        .offer-list-card:hover { border-color: var(--violet); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(167, 139, 250, 0.05); }
        .ol-logo { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-family: 'DM Serif Display', serif; font-size: 24px; color: #060912; }
        .offer-badge { background: var(--violet-d); color: var(--violet); padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
@endsection

@section('content')
<header class="header" style="margin-bottom: 32px;">
    <div>
        <h1 class="page-title">Job Offers</h1>
        <p class="page-sub">Review and decide on your official job offers.</p>
    </div>
</header>

<div class="offers-list">
    @forelse($offers as $app)
        @php
            $colors = ['var(--teal)', 'var(--sky)', 'var(--violet)', 'var(--amber)'];
            $color = $colors[$loop->index % 4];
        @endphp
        <div class="offer-list-card">
            <div style="display:flex; align-items:center; gap:20px;">
                <div class="ol-logo" style="background:{{ $color }};">{{ substr($app->jobPost->company, 0, 1) }}</div>
                <div>
                    <div style="font-family:'DM Serif Display',serif; font-size:20px; color:var(--text); margin-bottom:4px;">{{ $app->jobPost->title }}</div>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <span style="font-size:13px; color:var(--text2);">{{ $app->jobPost->company }}</span>
                        <span class="offer-badge">New Offer 🎉</span>
                    </div>
                </div>
            </div>
            
            <div style="display:flex; align-items:center; gap:16px;">
                <div style="text-align:right; margin-right:8px;">
                    <div style="font-size:15px; font-weight:700; color:var(--teal);">{{ $app->offer_details['salary'] ?? '' }}</div>
                    <div style="font-size:11px; color:var(--text3);">Joining: {{ \Carbon\Carbon::parse($app->offer_details['start_date'] ?? '')->format('M d, Y') }}</div>
                </div>
                <a href="{{ route('applications.offer', $app) }}" class="btn btn-teal" style="background:var(--violet); font-size:13px; padding:10px 24px; border-radius:12px;">Review Offer ✦</a>
            </div>
        </div>
    @empty
        <div style="padding:80px 40px; text-align:center; color:var(--text3); background:var(--s1); border-radius:32px; border:1px solid var(--border);">
            <div style="font-size:64px; margin-bottom:20px;">🎁</div>
            <h3 style="font-family:'DM Serif Display',serif; font-size:28px; color:var(--text); margin-bottom:12px;">No active offers</h3>
            <p style="max-width:400px; margin:0 auto 24px;">Your offers will appear here once recruiters send them. Keep applying and good luck!</p>
            <a href="{{ route('jobs.index') }}" class="btn btn-teal" style="padding:12px 32px;">Explore More Jobs</a>
        </div>
    @endforelse
</div>
@endsection
