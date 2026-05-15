@extends('layouts.dashboard')

@section('title', 'Job Offer Details')

@section('styles')
    <style>
        .offer-page { max-width: 800px; margin: 0 auto; }
        .offer-card { background: var(--s1); border-radius: 32px; border: 1px solid var(--border); padding: 40px; position: relative; overflow: hidden; }
        .offer-card::before { content: ''; position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(167, 139, 250, 0.1), transparent 70%); pointer-events: none; }
        
        .offer-header { text-align: center; margin-bottom: 40px; }
        .offer-confetti { font-size: 48px; margin-bottom: 16px; display: block; animation: bounce 2s infinite; }
        .offer-title { font-family: 'DM Serif Display', serif; font-size: 36px; color: var(--text); margin-bottom: 8px; }
        .offer-subtitle { color: var(--text3); font-size: 16px; }

        .offer-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; }
        .detail-box { background: var(--s2); border-radius: 20px; padding: 24px; border: 1px solid var(--border); }
        .db-label { font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; }
        .db-value { font-family: 'DM Serif Display', serif; font-size: 24px; color: var(--text); }
        
        .offer-letter-section { background: rgba(167, 139, 250, 0.05); border: 1.5px dashed rgba(167, 139, 250, 0.3); border-radius: 24px; padding: 32px; text-align: center; margin-bottom: 40px; }
        
        .action-row { display: flex; gap: 16px; }
        .action-btn { flex: 1; padding: 16px; border-radius: 16px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-accept { background: var(--teal); color: #060912; box-shadow: 0 10px 20px rgba(45, 212, 191, 0.2); }
        .btn-accept:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(45, 212, 191, 0.3); }
        .btn-reject { background: var(--s2); color: var(--rose); border: 1px solid var(--border); }
        .btn-reject:hover { background: var(--rose-d); color: white; border-color: var(--rose); }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
@endsection

@section('content')
<div class="offer-page">
    <div class="offer-card">
        <div class="offer-header">
            <span class="offer-confetti">🎉</span>
            <h1 class="offer-title">Congratulations!</h1>
            <p class="offer-subtitle">You've received an official job offer from <strong>{{ $application->jobPost->company }}</strong></p>
        </div>

        <div class="offer-details-grid">
            <div class="detail-box">
                <div class="db-label">Position</div>
                <div class="db-value">{{ $application->jobPost->title }}</div>
            </div>
            <div class="detail-box">
                <div class="db-label">Offered Salary</div>
                <div class="db-value" style="color:var(--teal);">{{ $application->offer_details['salary'] ?? 'N/A' }}</div>
            </div>
            <div class="detail-box">
                <div class="db-label">Start Date</div>
                <div class="db-value">{{ \Carbon\Carbon::parse($application->offer_details['start_date'] ?? '')->format('M d, Y') }}</div>
            </div>
            <div class="detail-box">
                <div class="db-label">Work Mode</div>
                <div class="db-value">{{ $application->jobPost->work_mode ?? 'On-site' }}</div>
            </div>
        </div>

        <div class="offer-letter-section">
            <div style="font-size:32px; margin-bottom:12px;">📄</div>
            <h3 style="color:var(--text); font-size:18px; margin-bottom:8px;">Official Offer Letter</h3>
            <p style="color:var(--text3); font-size:13px; margin-bottom:20px;">Please review the detailed terms and conditions in the attached PDF document.</p>
            @if($application->offer_letter_path)
                <a href="{{ Storage::url($application->offer_letter_path) }}" target="_blank" class="btn btn-teal" style="display:inline-flex; padding:14px 40px; background:var(--violet); color:white; border-radius:14px; font-weight:700; text-decoration:none; box-shadow:0 10px 20px rgba(167, 139, 250, 0.2);">Download Offer PDF ⬇</a>
            @else
                <p style="color:var(--amber); font-size:12px;">Offer letter document is being prepared.</p>
            @endif
        </div>

        <div class="action-row" id="initialActions">
            <button type="button" class="action-btn btn-reject" onclick="toggleRejectionReason()">Decline Offer</button>
            <form action="{{ route('applications.offer.decision', $application) }}" method="POST" style="flex:2;">
                @csrf
                <input type="hidden" name="decision" value="accepted">
                <button type="submit" class="action-btn btn-accept">Accept Offer ✦</button>
            </form>
        </div>

        <div id="rejectionSection" style="display:none; margin-top:20px; animation: slideDown 0.3s ease both;">
            <form action="{{ route('applications.offer.decision', $application) }}" method="POST">
                @csrf
                <input type="hidden" name="decision" value="rejected">
                <div style="margin-bottom:16px;">
                    <label style="font-size:13px; color:var(--text2); margin-bottom:8px; display:block;">Reason for declining (Optional)</label>
                    <textarea name="reason" style="width:100%; background:var(--s2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); font-family:inherit; font-size:14px; min-height:100px; outline:none; resize:none;" placeholder="Tell us why you are declining this offer..."></textarea>
                </div>
                <div style="display:flex; gap:12px;">
                    <button type="button" class="btn btn-ghost" onclick="toggleRejectionReason()" style="flex:1; justify-content:center;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex:2; justify-content:center; background:var(--rose);">Confirm Decline</button>
                </div>
            </form>
        </div>
        
        <p style="text-align:center; margin-top:24px; font-size:11px; color:var(--text3);">By clicking Accept, you agree to the terms mentioned in the offer letter.</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleRejectionReason() {
        const initial = document.getElementById('initialActions');
        const rejection = document.getElementById('rejectionSection');
        if (rejection.style.display === 'none') {
            initial.style.display = 'none';
            rejection.style.display = 'block';
        } else {
            initial.style.display = 'flex';
            rejection.style.display = 'none';
        }
    }
</script>
<style>
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
