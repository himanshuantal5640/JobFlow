@extends('layouts.dashboard')

@section('title', 'Resume Analyzer')

@section('styles')
    @vite(['resources/css/analyzer.css'])
    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--text); }
        .analyzer-card { background: var(--s1); border-radius: 24px; padding: 24px; border: 1px solid var(--border); margin-bottom: 24px; }
        .hist-card { background: var(--s2); border-radius: 16px; padding: 16px; border: 1px solid var(--border); cursor: pointer; transition: all 0.2s; }
        .hist-card:hover, .hist-card.selected { border-color: var(--teal); background: var(--s3); }
        .score-ring-wrap { position: relative; width: 120px; height: 120px; }
        .score-num { font-family: 'DM Serif Display', serif; font-size: 36px; color: var(--teal); }
        .skill-chip { background: var(--s2); border: 1px solid var(--border); padding: 4px 12px; border-radius: 8px; font-size: 12px; }
        .skill-chip.found { color: var(--teal); border-color: rgba(45, 212, 191, 0.3); }
        .skill-chip.missing { color: var(--rose); border-color: rgba(251, 113, 133, 0.3); }
    </style>
@endsection

@section('content')
<header class="header" style="margin-bottom: 32px;">
    <div>
        <h1 class="page-title">Resume Analyzer</h1>
        <p class="page-sub">Optimize your resume for ATS algorithms and recruiter visibility.</p>
    </div>
    <div class="header-actions">
        <button class="btn btn-teal" onclick="triggerUpload()">Analyze New Resume</button>
    </div>
</header>

<div class="analyzer-page">
  <!-- ── SECTION 0: PREVIOUS ANALYSES ── -->
  <div class="analyzer-card">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
      <h3 style="font-family:'DM Serif Display',serif; font-size:20px;">Your Resume Versions</h3>
      <span style="font-size:12px; color:var(--text3);">{{ count($resumes) }} versions stored</span>
    </div>
    <div class="history-row" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:16px;" id="historyList">
      @foreach($resumes as $resume)
      <div class="hist-card {{ $loop->first ? 'selected' : '' }}" onclick="loadResumeData({{ json_encode($resume) }}, this)">
        <div style="font-size:13px; font-weight:600; margin-bottom:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $resume->file_name }}</div>
        <div style="font-size:11px; color:var(--text3); margin-bottom:12px;">{{ $resume->created_at->format('M d, Y') }}</div>
        <div style="font-size:18px; font-weight:800; color: {{ $resume->score > 80 ? 'var(--teal)' : ($resume->score > 60 ? 'var(--amber)' : 'var(--rose)') }};">
            {{ $resume->score }} <span style="font-size:10px; color:var(--text3); font-weight:400;">ATS Score</span>
        </div>
      </div>
      @endforeach
      
      <div class="hist-card" style="border-style:dashed; display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:90px;" onclick="triggerUpload()">
        <div style="font-size:20px; margin-bottom:4px;">+</div>
        <div style="font-size:12px; font-weight:700;">Upload New</div>
      </div>
    </div>
  </div>

  <form id="uploadForm" style="display:none;">
    @csrf
    <input type="file" id="fileInput" name="resume" onchange="handleFileUpload(this)">
  </form>

  <!-- ── SECTION 2: SCANNING ── -->
  <div id="scanSection" style="display:none; flex-direction:column; align-items:center; justify-content:center; padding:60px 0;">
    <div style="width:300px; height:4px; background:var(--s2); border-radius:2px; overflow:hidden; margin-bottom:24px;">
        <div id="scanProgress" style="width:0%; height:100%; background:var(--teal); transition:width 0.2s;"></div>
    </div>
    <div style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:8px;">Analyzing your resume...</div>
    <div style="font-size:14px; color:var(--text3);" id="scanStatus">Reading document structure</div>
  </div>

  <!-- ── SECTION 3: RESULTS ── -->
  <div id="resultsSection" style="{{ $latestResume ? '' : 'display:none;' }}">
    @if($latestResume)
    <div class="analyzer-card" style="display:grid; grid-template-columns:150px 1fr 300px; gap:40px; align-items:center;">
        <div class="score-ring-wrap">
            <svg width="120" height="120" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="54" fill="none" stroke="var(--s2)" stroke-width="8"/>
                <circle id="scoreRing" cx="60" cy="60" r="54" fill="none" stroke="var(--teal)" stroke-width="8" stroke-dasharray="339.29" stroke-dashoffset="{{ 339.29 * (1 - $latestResume->score/100) }}" stroke-linecap="round" transform="rotate(-90 60 60)" style="transition: stroke-dashoffset 2s ease;"/>
            </svg>
            <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <div class="score-num" id="displayScore">{{ $latestResume->score }}</div>
                <div style="font-size:10px; color:var(--text3); font-weight:700; text-transform:uppercase;">Score</div>
            </div>
        </div>
        <div>
            <h2 style="font-family:'DM Serif Display',serif; font-size:28px; color:var(--text); margin-bottom:12px;" id="scoreGrade">Outstanding Match ✦</h2>
            <p style="color:var(--text2); line-height:1.6; font-size:14px;" id="scoreDesc">Your resume passes {{ $latestResume->score }}% of ATS filters. It's highly competitive, but we've found key areas where you can still improve to reach a perfect score.</p>
            <div style="display:flex; gap:12px; margin-top:20px;">
                <span style="background:rgba(45, 212, 191, 0.1); color:var(--teal); padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;" id="skillsCount">{{ count($latestResume->details['skills_found'] ?? []) }} Skills Found</span>
                <span style="background:rgba(251, 113, 133, 0.1); color:var(--rose); padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;" id="gapsCount">{{ count($latestResume->details['skill_gaps'] ?? []) }} Skill Gaps</span>
            </div>
        </div>
        <div style="display:flex; flex-direction:column; gap:8px;">
            <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px;">
                <span style="color:var(--text3);">Keywords</span>
                <span style="color:var(--teal); font-weight:700;" id="keywordsPct">{{ $latestResume->details['keywords'] ?? 0 }}%</span>
            </div>
            <div style="height:6px; background:var(--s2); border-radius:3px; overflow:hidden;"><div id="keywordsBar" style="width:{{ $latestResume->details['keywords'] ?? 0 }}%; height:100%; background:var(--teal);"></div></div>
            
            <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px; margin-top:12px;">
                <span style="color:var(--text3);">Formatting</span>
                <span style="color:var(--sky); font-weight:700;" id="formattingPct">{{ $latestResume->details['formatting'] ?? 0 }}%</span>
            </div>
            <div style="height:6px; background:var(--s2); border-radius:3px; overflow:hidden;"><div id="formattingBar" style="width:{{ $latestResume->details['formatting'] ?? 0 }}%; height:100%; background:var(--sky);"></div></div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
        <div class="analyzer-card">
            <h3 style="font-family:'DM Serif Display',serif; font-size:20px; margin-bottom:20px;">Extracted Skills</h3>
            <div style="display:flex; flex-wrap:wrap; gap:8px;" id="skillsGrid">
                @foreach($latestResume->details['skills_found'] ?? [] as $skill)
                    <span class="skill-chip found">{{ $skill }}</span>
                @endforeach
                @foreach($latestResume->details['skill_gaps'] ?? [] as $gap)
                    <span class="skill-chip missing">{{ $gap }}</span>
                @endforeach
            </div>
        </div>
        <div class="analyzer-card">
            <h3 style="font-family:'DM Serif Display',serif; font-size:20px; margin-bottom:20px;">Improvement Plan</h3>
            <div style="display:flex; flex-direction:column; gap:12px;" id="improvementPlan">
                <div style="display:flex; gap:12px; padding:12px; background:var(--s2); border-radius:12px; border-left:4px solid var(--rose);">
                    <div style="font-size:18px;">🔑</div>
                    <div>
                        <div style="font-size:13px; font-weight:700;">Add Missing Keywords</div>
                        <div style="font-size:11px; color:var(--text3);">Include "{{ implode(', ', $latestResume->details['skill_gaps'] ?? []) }}" to match more roles.</div>
                    </div>
                </div>
                <div style="display:flex; gap:12px; padding:12px; background:var(--s2); border-radius:12px; border-left:4px solid var(--amber);">
                    <div style="font-size:18px;">📊</div>
                    <div>
                        <div style="font-size:13px; font-weight:700;">Quantify Impact</div>
                        <div style="font-size:11px; color:var(--text3);">Add specific metrics to your experience bullets.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div style="padding:60px; text-align:center; color:var(--text3); background:var(--s1); border-radius:24px; border:1px solid var(--border);">
            <div style="font-size:48px; margin-bottom:16px;">📄</div>
            <h3 style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--text); margin-bottom:8px;">No Resume Analyzed</h3>
            <p>Upload your first resume to get an ATS score and improvement plan.</p>
            <button class="btn btn-teal" style="margin-top:24px;" onclick="triggerUpload()">Upload Resume Now</button>
        </div>
    @endif
  </div>
</div>
@endsection

@section('scripts')
<script>
    function triggerUpload() {
        document.getElementById('fileInput').click();
    }

    function handleFileUpload(input) {
        if (!input.files || !input.files[0]) return;
        
        const formData = new FormData(document.getElementById('uploadForm'));
        
        document.getElementById('resultsSection').style.display = 'none';
        document.getElementById('scanSection').style.display = 'flex';
        
        let progress = 0;
        const bar = document.getElementById('scanProgress');
        const status = document.getElementById('scanStatus');
        const statuses = ['Reading file...', 'Extracting keywords...', 'Running ATS check...', 'Calculating match score...', 'Finalizing report...'];
        
        const interval = setInterval(() => {
            progress += 5;
            bar.style.width = progress + '%';
            status.innerText = statuses[Math.floor(progress / 21)] || 'Finalizing...';
            
            if (progress >= 90) {
                clearInterval(interval);
            }
        }, 150);

        fetch('{{ route('seeker.analyzer.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                progress = 100;
                bar.style.width = '100%';
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Analysis failed. Please try again.');
            document.getElementById('scanSection').style.display = 'none';
            document.getElementById('resultsSection').style.display = 'block';
        });
    }

    function loadResumeData(resume, el) {
        // Update selection
        document.querySelectorAll('.hist-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');

        // Update display
        document.getElementById('displayScore').innerText = resume.score;
        document.getElementById('scoreRing').style.strokeDashoffset = 339.29 * (1 - resume.score / 100);
        document.getElementById('scoreDesc').innerText = `Your resume passes ${resume.score}% of ATS filters. It's highly competitive, but we've found key areas where you can still improve to reach a perfect score.`;
        
        const details = resume.details;
        document.getElementById('keywordsPct').innerText = (details.keywords || 0) + '%';
        document.getElementById('keywordsBar').style.width = (details.keywords || 0) + '%';
        document.getElementById('formattingPct').innerText = (details.formatting || 0) + '%';
        document.getElementById('formattingBar').style.width = (details.formatting || 0) + '%';
        
        document.getElementById('skillsCount').innerText = (details.skills_found || []).length + ' Skills Found';
        document.getElementById('gapsCount').innerText = (details.skill_gaps || []).length + ' Skill Gaps';
        
        // Update Skills Grid
        let skillsHtml = '';
        (details.skills_found || []).forEach(s => skillsHtml += `<span class="skill-chip found">${s}</span>`);
        (details.skill_gaps || []).forEach(g => skillsHtml += `<span class="skill-chip missing">${g}</span>`);
        document.getElementById('skillsGrid').innerHTML = skillsHtml;

        // Update Improvement Plan
        document.getElementById('improvementPlan').innerHTML = `
            <div style="display:flex; gap:12px; padding:12px; background:var(--s2); border-radius:12px; border-left:4px solid var(--rose);">
                <div style="font-size:18px;">🔑</div>
                <div>
                    <div style="font-size:13px; font-weight:700;">Add Missing Keywords</div>
                    <div style="font-size:11px; color:var(--text3);">Include "${(details.skill_gaps || []).join(', ')}" to match more roles.</div>
                </div>
            </div>
            <div style="display:flex; gap:12px; padding:12px; background:var(--s2); border-radius:12px; border-left:4px solid var(--amber);">
                <div style="font-size:18px;">📊</div>
                <div>
                    <div style="font-size:13px; font-weight:700;">Quantify Impact</div>
                    <div style="font-size:11px; color:var(--text3);">Add specific metrics to your experience bullets.</div>
                </div>
            </div>
        `;
    }
</script>
@endsection
