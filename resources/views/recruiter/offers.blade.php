@extends('layouts.dashboard')

@section('title', 'Offers Sent — Recruiter Portal')

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
            <h1 class="page-title">Offers Extended</h1>
            <p class="page-sub">{{ count($offers) }} active offers awaiting candidate response.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-ghost">Export Offers</button>
        </div>
    </header>

    <div class="pipeline-list">
        @forelse($offers as $app)
        <div class="applicant-row">
            <div style="display:flex; align-items:center; gap:16px;">
                <div class="ac-avatar" style="width:48px; height:48px; font-size:20px; background:var(--rose-d); color:var(--rose);">{{ substr($app->user->name, 0, 1) }}</div>
                <div>
                    <div style="font-weight:700; color:var(--text);">{{ $app->user->name }}</div>
                    <div style="font-size:12px; color:var(--text3);">{{ $app->user->email }}</div>
                </div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text3); text-transform:uppercase; font-weight:700; margin-bottom:4px;">Position</div>
                <div style="font-weight:600; color:var(--text2);">{{ $app->jobPost->title }}</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text3); text-transform:uppercase; font-weight:700; margin-bottom:4px;">Status</div>
                <span class="status-badge active" style="background:var(--rose-d); color:var(--rose);">● Offer Sent</span>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('recruiter.messages.start', $app->user->id) }}" class="btn btn-ghost" style="padding:10px;">💬</a>
                <form action="{{ route('recruiter.applications.status', $app) }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="status" value="hired">
                    <button type="submit" class="btn btn-teal" style="width:100%; justify-content:center; font-size:12px;">Confirm Hire</button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:80px; text-align:center; color:var(--text3); background:var(--s1); border-radius:24px; border:1px solid var(--border);">
            <div style="font-size:48px; margin-bottom:20px;">🚀</div>
            <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:12px;">No active offers</h3>
            <p>Candidates you send offers to will appear here.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
