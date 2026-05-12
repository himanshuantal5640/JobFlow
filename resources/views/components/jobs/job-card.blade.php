@props(['job'])

@php
    $accentMap = [
        'Engineering' => 'indigo',
        'Design' => 'cyan',
        'Developer Rel.' => 'lime',
        'AI / ML' => 'emerald',
        'Product' => 'amber',
        'Infrastructure' => 'rose'
    ];
    $accent = $accentMap[$job->department] ?? 'indigo';
    $skills = json_decode($job->skills ?? '[]');
@endphp

<div class="job-card jc-accent-{{ $accent }}" onclick="openJob({{ $job->id }})">
    <div class="jc-top">
        <div class="jc-company-row">
            <div class="jc-logo" style="background: linear-gradient(135deg, var(--indigo), var(--cyan)); color: white;">
                {{ substr($job->company, 0, 1) }}
            </div>
            <div>
                <div style="font-size:11px;color:var(--text3);">{{ $job->company }}</div>
                <a href="{{ route('jobs.show', $job->id) }}" class="jc-title" onclick="event.stopPropagation()">{{ $job->title }}</a>
                <div class="jc-meta">📍 {{ $job->location }} &nbsp;·&nbsp; {{ $job->work_mode }}</div>
            </div>
        </div>
        <div class="jc-right-col">
            <button class="bookmark-btn" onclick="event.stopPropagation(); toggleBookmark(this, {{ $job->id }})" title="Save job">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 2C2.5 1.17 3.17 0.5 4 0.5H9C9.83 0.5 10.5 1.17 10.5 2V12L6.5 9.5L2.5 12V2Z" stroke="currentColor" stroke-width="1.3"/></svg>
            </button>
            @if($job->match >= 90)
                <span class="jc-badge badge-hot">🔥 Hot</span>
            @elseif($job->created_at->diffInDays() < 2)
                <span class="jc-badge badge-new">✨ New</span>
            @endif
        </div>
    </div>
    <div class="jc-tags">
        @foreach($skills as $skill)
            <span class="jc-tag">{{ $skill }}</span>
        @endforeach
    </div>
    <div class="jc-match">
        <span class="jc-match-label">AI Match</span>
        <div class="jc-match-track"><div class="jc-match-fill" style="width:{{ $job->match }}%;"></div></div>
        <span class="jc-match-pct">{{ $job->match }}%</span>
    </div>
    <div class="jc-footer">
        <div>
            <div class="jc-salary">{{ $job->salary }}</div>
            <div class="jc-info">{{ $job->department }} &nbsp;·&nbsp; Posted {{ $job->created_at->diffForHumans() }}</div>
        </div>
        <button class="jc-apply" onclick="event.stopPropagation(); openQuickApply({{ $job->id }})">Apply →</button>
    </div>
</div>
