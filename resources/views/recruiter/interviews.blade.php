@extends('layouts.dashboard')

@section('title', 'Interviews — Recruiter Portal')

@section('styles')
    @vite(['resources/css/recruiter.css'])
    <style>
        .pipeline-page { padding: 20px 0; }
        .page-header { margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; }
        .applicant-row { background: var(--s1); border-radius: 20px; border: 1px solid var(--border); padding: 20px; margin-bottom: 16px; display: grid; grid-template-columns: 1fr 1fr 1fr 180px; align-items: center; gap: 24px; transition: all 0.2s; }
        .applicant-row:hover { border-color: var(--teal); transform: translateX(8px); }
    </style>
@endsection

@section('content')
<div class="pipeline-page">
    <header class="page-header">
        <div>
            <h1 class="page-title">Interview Pipeline</h1>
            <p class="page-sub">Managing {{ count($interviews) }} candidates currently in the interview stage.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-ghost">Filter by Job ▾</button>
            <button class="btn btn-teal">Schedule New Interview</button>
        </div>
    </header>

    <div class="pipeline-list">
        @forelse($interviews as $app)
        <div class="applicant-row">
            <div style="display:flex; align-items:center; gap:16px;">
                <div class="ac-avatar" style="width:48px; height:48px; font-size:20px;">{{ substr($app->user->name, 0, 1) }}</div>
                <div>
                    <div style="font-weight:700; color:var(--text);">{{ $app->user->name }}</div>
                    <div style="font-size:12px; color:var(--text3);">{{ $app->user->email }}</div>
                </div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text3); text-transform:uppercase; font-weight:700; margin-bottom:4px;">Applied For</div>
                <div style="font-weight:600; color:var(--text2);">{{ $app->jobPost->title }}</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text3); text-transform:uppercase; font-weight:700; margin-bottom:4px;">Stage</div>
                <span class="status-badge active" style="background:var(--violet-d); color:var(--violet);">● Interview Round 2</span>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('recruiter.messages.start', $app->user->id) }}" class="btn btn-ghost" style="padding:10px;">💬</a>
                <form action="{{ route('recruiter.applications.status', $app) }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status" value="offer">
                    <button type="submit" class="btn btn-teal" style="width:100%; justify-content:center; font-size:12px;">Make Offer</button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:80px; text-align:center; color:var(--text3); background:var(--s1); border-radius:24px; border:1px solid var(--border);">
            <div style="font-size:48px; margin-bottom:20px;">🗓</div>
            <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:12px;">No interviews scheduled</h3>
            <p>Candidates you move to the interview stage will appear here.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
