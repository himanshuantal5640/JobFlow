<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JobFlow — Recruiter Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;0,900;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg:        #04080F;
  --sidebar:   #060B14;
  --s1:        #0B1220;
  --s2:        #101828;
  --s3:        #162033;
  --border:    rgba(255,255,255,0.05);
  --border2:   rgba(255,255,255,0.09);
  --border3:   rgba(255,255,255,0.15);

  --teal:      #00D4AA;
  --teal2:     #00EDBC;
  --teal-d:    rgba(0,212,170,0.12);
  --teal-d2:   rgba(0,212,170,0.07);

  --sky:       #38BDF8;
  --sky-d:     rgba(56,189,248,0.12);

  --violet:    #818CF8;
  --violet-d:  rgba(129,140,248,0.12);

  --amber:     #FBBF24;
  --amber-d:   rgba(251,191,36,0.12);

  --rose:      #FB7185;
  --rose-d:    rgba(251,113,133,0.12);

  --emerald:   #34D399;
  --emerald-d: rgba(52,211,153,0.12);

  --text:      #E8F0FE;
  --text2:     #7B8DB0;
  --text3:     #3D4F6B;

  --sw:  256px;
  --th:  60px;
  --r:   12px;
  --rl:  18px;
  --rxl: 24px;
  --ease: cubic-bezier(0.4,0,0.2,1);
  --t: 0.18s;
}

html, body { height: 100%; }
body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  display: flex;
  overflow-x: hidden;
  min-height: 100vh;
}

::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--s3); border-radius: 10px; }

/* ─── SIDEBAR ─── */
.sidebar {
  width: var(--sw);
  min-height: 100vh;
  background: var(--sidebar);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  position: fixed;
  inset: 0 auto 0 0;
  z-index: 60;
}

.sidebar-logo {
  height: var(--th);
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 18px;
  border-bottom: 1px solid var(--border);
  text-decoration: none;
  flex-shrink: 0;
}

.logo-gem {
  width: 30px; height: 30px;
  background: linear-gradient(135deg, var(--teal), var(--sky));
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Fraunces', serif;
  font-size: 15px; font-weight: 900; color: #020c18;
}

.logo-word {
  font-family: 'Fraunces', serif;
  font-size: 18px; font-weight: 700; color: var(--text);
  letter-spacing: -0.3px;
}
.logo-word em { color: var(--teal); font-style: normal; }

.sidebar-role-tag {
  margin: 14px 14px 4px;
  padding: 6px 10px;
  background: var(--teal-d2);
  border: 1px solid rgba(0,212,170,0.15);
  border-radius: 8px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--teal);
  display: flex;
  align-items: center;
  gap: 6px;
}

.sidebar-role-tag::before {
  content: '';
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--teal);
  animation: blink 2s ease infinite;
}

@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

.nav-group { padding: 12px 10px 4px; }

.nav-group-label {
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--text3);
  padding: 0 8px;
  margin-bottom: 4px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 8px 10px;
  border-radius: 9px;
  color: var(--text2);
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all var(--t) var(--ease);
  text-decoration: none;
  margin-bottom: 1px;
  position: relative;
}

.nav-item:hover { background: var(--s1); color: var(--text); }

.nav-item.active {
  background: var(--teal-d);
  color: var(--teal);
}

.nav-item.active::before {
  content: '';
  position: absolute;
  left: 0; top: 20%; bottom: 20%;
  width: 2.5px;
  background: var(--teal);
  border-radius: 0 2px 2px 0;
}

.nav-icon {
  width: 30px; height: 30px;
  border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px;
  flex-shrink: 0;
  transition: background var(--t);
}

.nav-item.active .nav-icon { background: rgba(0,212,170,0.15); }
.nav-item:hover:not(.active) .nav-icon { background: var(--s2); }

.nav-badge {
  margin-left: auto;
  font-size: 10px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 20px;
  background: var(--teal);
  color: #020c18;
}

.nav-badge.rose { background: var(--rose); color: white; }
.nav-badge.amber { background: var(--amber); color: #1a0e00; }

/* Company card at bottom */
.sidebar-company {
  margin: auto 10px 10px;
  background: var(--s1);
  border: 1px solid var(--border2);
  border-radius: var(--r);
  padding: 12px;
  cursor: pointer;
  transition: border-color var(--t);
}

.sidebar-company:hover { border-color: var(--border3); }

.company-row {
  display: flex;
  align-items: center;
  gap: 9px;
  margin-bottom: 10px;
}

.company-logo {
  width: 32px; height: 32px;
  border-radius: 9px;
  background: linear-gradient(135deg, var(--teal), var(--sky));
  display: flex; align-items: center; justify-content: center;
  font-family: 'Fraunces', serif;
  font-size: 14px; font-weight: 700; color: #020c18;
  flex-shrink: 0;
}

.company-name { font-size: 13px; font-weight: 600; color: var(--text); }
.company-sub { font-size: 11px; color: var(--text3); }

.company-stats {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 6px;
}

.co-stat {
  text-align: center;
  background: var(--s2);
  border-radius: 7px;
  padding: 6px 4px;
}

.co-stat-val {
  font-family: 'Fraunces', serif;
  font-size: 15px;
  font-weight: 700;
  color: var(--teal);
  line-height: 1;
}

.co-stat-label {
  font-size: 9px;
  color: var(--text3);
  margin-top: 2px;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

/* ─── TOPBAR ─── */
.topbar {
  position: fixed;
  top: 0;
  left: var(--sw);
  right: 0;
  height: var(--th);
  background: rgba(4,8,15,0.88);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  padding: 0 24px;
  gap: 14px;
  z-index: 50;
}

.page-title {
  font-family: 'Fraunces', serif;
  font-size: 17px;
  font-weight: 700;
  color: var(--text);
  letter-spacing: -0.3px;
}

.page-sub { font-size: 12px; color: var(--text3); margin-top: 1px; }

.topbar-search {
  margin-left: 24px;
  flex: 1;
  max-width: 300px;
  position: relative;
}

.topbar-search input {
  width: 100%;
  background: var(--s1);
  border: 1px solid var(--border2);
  border-radius: 9px;
  padding: 7px 12px 7px 34px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 13px;
  color: var(--text);
  outline: none;
  transition: border-color var(--t);
}

.topbar-search input:focus { border-color: var(--teal); }
.topbar-search input::placeholder { color: var(--text3); }

.topbar-search-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text3);
  pointer-events: none;
}

.topbar-right {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 8px;
}

.icon-btn {
  width: 34px; height: 34px;
  border-radius: 9px;
  background: var(--s1);
  border: 1px solid var(--border2);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--text2);
  transition: all var(--t);
  position: relative;
}

.icon-btn:hover { background: var(--s2); color: var(--text); border-color: var(--border3); }

.notif-dot {
  position: absolute;
  top: 6px; right: 6px;
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--rose);
  border: 1.5px solid var(--sidebar);
}

.btn {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 13px;
  font-weight: 600;
  padding: 7px 16px;
  border-radius: 9px;
  cursor: pointer;
  border: none;
  transition: all var(--t) var(--ease);
  display: inline-flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
  white-space: nowrap;
}

.btn-teal {
  background: linear-gradient(135deg, var(--teal), #00BBAA);
  color: #020c18;
  box-shadow: 0 0 16px rgba(0,212,170,0.22);
}

.btn-teal:hover { transform: translateY(-1px); box-shadow: 0 0 24px rgba(0,212,170,0.38); }

.btn-ghost {
  background: var(--s1);
  color: var(--text2);
  border: 1px solid var(--border2);
}

.btn-ghost:hover { background: var(--s2); color: var(--text); border-color: var(--border3); }

.btn-outline-teal {
  background: var(--teal-d2);
  color: var(--teal);
  border: 1px solid rgba(0,212,170,0.25);
}

.btn-outline-teal:hover { background: var(--teal-d); border-color: rgba(0,212,170,0.4); }

/* ─── MAIN ─── */
.main {
  margin-left: var(--sw);
  margin-top: var(--th);
  flex: 1;
  padding: 24px;
  min-height: calc(100vh - var(--th));
}

/* ─── ANIMATIONS ─── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(14px); }
  to   { opacity: 1; transform: none; }
}

.a0 { animation: fadeUp 0.4s ease both; }
.a1 { animation: fadeUp 0.4s ease 0.07s both; }
.a2 { animation: fadeUp 0.4s ease 0.13s both; }
.a3 { animation: fadeUp 0.4s ease 0.19s both; }
.a4 { animation: fadeUp 0.4s ease 0.25s both; }
.a5 { animation: fadeUp 0.4s ease 0.31s both; }

/* ─── STAT CARDS ─── */
.stats-row {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 12px;
  margin-bottom: 22px;
}

.stat-card {
  background: var(--s1);
  border: 1px solid var(--border);
  border-radius: var(--rl);
  padding: 16px;
  transition: all var(--t) var(--ease);
  position: relative;
  overflow: hidden;
  cursor: default;
}

.stat-card:hover { border-color: var(--border2); transform: translateY(-2px); }

.stat-card::after {
  content: '';
  position: absolute;
  bottom: 0; left: 10%; right: 10%;
  height: 1px;
  opacity: 0;
  transition: opacity var(--t);
}

.stat-card:hover::after { opacity: 1; }

.st-teal::after   { background: var(--teal); }
.st-sky::after    { background: var(--sky); }
.st-amber::after  { background: var(--amber); }
.st-violet::after { background: var(--violet); }
.st-rose::after   { background: var(--rose); }

.stat-icon {
  width: 34px; height: 34px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px;
  margin-bottom: 12px;
}

.stat-val {
  font-family: 'Fraunces', serif;
  font-size: 30px;
  font-weight: 700;
  letter-spacing: -1px;
  line-height: 1;
  margin-bottom: 3px;
}

.stat-lbl { font-size: 11px; color: var(--text3); font-weight: 500; }

.stat-delta {
  margin-top: 8px;
  font-size: 11px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 3px;
}

.up   { color: var(--emerald); }
.down { color: var(--rose); }

/* ─── SECTION HEADER ─── */
.sh {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 14px;
}

.sh-title {
  font-family: 'Fraunces', serif;
  font-size: 15px;
  font-weight: 700;
  letter-spacing: -0.2px;
}

.sh-right {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* ─── CARD ─── */
.card {
  background: var(--s1);
  border: 1px solid var(--border);
  border-radius: var(--rl);
  padding: 18px;
}

/* ─── JOB POSTINGS TABLE ─── */
.jobs-section { margin-bottom: 22px; }

.jobs-table {
  display: flex;
  flex-direction: column;
  gap: 0;
  background: var(--s1);
  border: 1px solid var(--border);
  border-radius: var(--rl);
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr 120px;
  gap: 12px;
  padding: 10px 16px;
  background: var(--s2);
  border-bottom: 1px solid var(--border);
}

.th-cell {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--text3);
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr 120px;
  gap: 12px;
  padding: 13px 16px;
  align-items: center;
  border-bottom: 1px solid var(--border);
  transition: background var(--t);
  cursor: pointer;
  position: relative;
}

.table-row:last-child { border-bottom: none; }
.table-row:hover { background: var(--s2); }

.table-row::before {
  content: '';
  position: absolute;
  left: 0; top: 0; bottom: 0;
  width: 2px;
  opacity: 0;
  transition: opacity var(--t);
}

.table-row:hover::before { opacity: 1; }

.tr-teal::before   { background: var(--teal); }
.tr-sky::before    { background: var(--sky); }
.tr-amber::before  { background: var(--amber); }
.tr-violet::before { background: var(--violet); }
.tr-rose::before   { background: var(--rose); }

.job-title-cell {
  display: flex;
  align-items: center;
  gap: 10px;
}

.job-logo {
  width: 34px; height: 34px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px;
  font-weight: 700;
  font-family: 'Fraunces', serif;
  flex-shrink: 0;
  color: white;
}

.job-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 2px;
}

.job-dept {
  font-size: 11px;
  color: var(--text3);
}

.cell-text {
  font-size: 12px;
  color: var(--text2);
}

.cell-num {
  font-family: 'Fraunces', serif;
  font-size: 16px;
  font-weight: 700;
  color: var(--text);
}

.cell-sub {
  font-size: 10px;
  color: var(--text3);
  margin-top: 1px;
}

/* Progress bar cell */
.prog-cell { display: flex; flex-direction: column; gap: 4px; }
.prog-row  { display: flex; align-items: center; gap: 6px; }

.prog-bar {
  flex: 1;
  height: 4px;
  background: var(--s3);
  border-radius: 2px;
  overflow: hidden;
}

.prog-fill {
  height: 100%;
  border-radius: 2px;
  transition: width 1s ease;
}

.prog-val {
  font-size: 10px;
  font-weight: 700;
  color: var(--teal);
  white-space: nowrap;
}

/* Status badge */
.status-badge {
  font-size: 11px;
  font-weight: 600;
  padding: 3px 9px;
  border-radius: 5px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  border: 1px solid;
}

.s-active   { color: var(--teal);   background: var(--teal-d2); border-color: rgba(0,212,170,0.2); }
.s-review   { color: var(--amber);  background: var(--amber-d); border-color: rgba(251,191,36,0.2); }
.s-filled   { color: var(--violet); background: var(--violet-d); border-color: rgba(129,140,248,0.2); }
.s-draft    { color: var(--text3);  background: var(--s2); border-color: var(--border2); }
.s-paused   { color: var(--rose);   background: var(--rose-d);  border-color: rgba(251,113,133,0.2); }

.row-actions {
  display: flex;
  gap: 5px;
}

/* ─── PIPELINE GRID ─── */
.pipeline-grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 18px;
  margin-bottom: 22px;
}

/* Applicant pipeline funnel */
.funnel {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.funnel-stage {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  background: var(--s2);
  border: 1px solid var(--border);
  border-radius: var(--r);
  cursor: pointer;
  transition: all var(--t);
  position: relative;
  overflow: hidden;
}

.funnel-stage:hover { border-color: var(--border2); }

.funnel-stage.active-stage {
  border-color: rgba(0,212,170,0.3);
  background: rgba(0,212,170,0.04);
}

.funnel-fill {
  position: absolute;
  left: 0; top: 0; bottom: 0;
  border-radius: var(--r) 0 0 var(--r);
  pointer-events: none;
  transition: width 1.2s var(--ease);
}

.stage-info { position: relative; z-index: 1; flex: 1; }

.stage-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 2px;
}

.stage-sub { font-size: 11px; color: var(--text3); }

.stage-count {
  font-family: 'Fraunces', serif;
  font-size: 22px;
  font-weight: 700;
  color: var(--text);
  position: relative;
  z-index: 1;
  flex-shrink: 0;
}

.stage-pct {
  font-size: 11px;
  font-weight: 600;
  position: relative;
  z-index: 1;
  flex-shrink: 0;
  width: 36px;
  text-align: right;
}

/* Top sources */
.source-list { display: flex; flex-direction: column; gap: 8px; }

.source-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.source-icon {
  width: 28px; height: 28px;
  border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px;
  flex-shrink: 0;
}

.source-name { font-size: 12px; color: var(--text2); flex: 1; }

.source-bar {
  width: 80px;
  height: 4px;
  background: var(--s3);
  border-radius: 2px;
  overflow: hidden;
}

.source-fill {
  height: 100%;
  border-radius: 2px;
}

.source-count { font-size: 12px; font-weight: 600; color: var(--text); width: 26px; text-align: right; }

/* ─── APPLICANTS PANEL ─── */
.applicants-section { margin-bottom: 22px; }

.filter-tabs {
  display: flex;
  gap: 4px;
  background: var(--s2);
  border-radius: 10px;
  padding: 3px;
}

.filter-tab {
  font-size: 12px;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 8px;
  cursor: pointer;
  color: var(--text3);
  transition: all var(--t);
  border: none;
  background: transparent;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.filter-tab.active {
  background: var(--s3);
  color: var(--text);
  border: 1px solid var(--border2);
}

.filter-tab:hover:not(.active) { color: var(--text2); }

/* Applicant card */
.applicants-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.applicant-card {
  background: var(--s1);
  border: 1px solid var(--border);
  border-radius: var(--rl);
  padding: 16px;
  transition: all var(--t) var(--ease);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.applicant-card:hover {
  border-color: var(--border3);
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.applicant-card.shortlisted {
  border-color: rgba(0,212,170,0.25);
  background: rgba(0,212,170,0.03);
}

.applicant-card.rejected-card {
  opacity: 0.55;
}

.ac-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 12px;
}

.ac-info { display: flex; align-items: center; gap: 10px; }

.ac-avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Fraunces', serif;
  font-size: 14px; font-weight: 700; color: #020c18;
  flex-shrink: 0;
  position: relative;
}

.ac-online {
  position: absolute;
  bottom: 0; right: 0;
  width: 9px; height: 9px;
  border-radius: 50%;
  background: var(--teal);
  border: 1.5px solid var(--s1);
}

.ac-name { font-size: 13px; font-weight: 600; color: var(--text); }
.ac-role { font-size: 11px; color: var(--text3); margin-top: 1px; }

.ac-actions { display: flex; gap: 5px; }

.ac-btn {
  width: 26px; height: 26px;
  border-radius: 7px;
  background: var(--s2);
  border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--text3);
  transition: all var(--t);
  font-size: 12px;
}

.ac-btn:hover { color: var(--text); border-color: var(--border2); background: var(--s3); }
.ac-btn.approve:hover { color: var(--teal); border-color: rgba(0,212,170,0.3); background: var(--teal-d); }
.ac-btn.reject:hover  { color: var(--rose); border-color: rgba(251,113,133,0.3); background: var(--rose-d); }

.ac-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  margin-bottom: 10px;
}

.ac-tag {
  font-size: 10px;
  padding: 2px 8px;
  border-radius: 4px;
  background: var(--s3);
  color: var(--text2);
  border: 1px solid var(--border);
}

.ac-match {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 10px;
}

.ac-match-lbl { font-size: 11px; color: var(--text3); }

.ac-match-bar {
  flex: 1;
  height: 5px;
  background: var(--s3);
  border-radius: 3px;
  overflow: hidden;
}

.ac-match-fill {
  height: 100%;
  border-radius: 3px;
  background: linear-gradient(90deg, var(--teal), var(--sky));
}

.ac-match-pct {
  font-size: 11px;
  font-weight: 700;
  color: var(--teal);
  width: 28px;
  text-align: right;
}

.ac-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 10px;
  border-top: 1px solid var(--border);
}

.ac-exp { font-size: 11px; color: var(--text3); }
.ac-date { font-size: 11px; color: var(--text3); }

.ac-stage-pill {
  font-size: 10px;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 4px;
  border: 1px solid;
}

/* ─── BOTTOM ROW ─── */
.bottom-row {
  display: grid;
  grid-template-columns: 1.2fr 1fr 1fr;
  gap: 18px;
  margin-bottom: 22px;
}

/* Hiring velocity chart */
.bar-chart {
  display: flex;
  align-items: flex-end;
  gap: 5px;
  height: 90px;
}

.bc-bar-wrap {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  height: 100%;
  justify-content: flex-end;
}

.bc-bar {
  width: 100%;
  border-radius: 4px 4px 0 0;
  min-height: 4px;
  transition: opacity var(--t);
  cursor: pointer;
  position: relative;
}

.bc-bar:hover { opacity: 0.75; }

.bc-label { font-size: 10px; color: var(--text3); }

/* Diversity chart (donut) */
.diversity-chart {
  display: flex;
  align-items: center;
  gap: 16px;
}

.donut-wrap {
  position: relative;
  width: 80px; height: 80px;
  flex-shrink: 0;
}

.donut-text {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.donut-num {
  font-family: 'Fraunces', serif;
  font-size: 18px;
  font-weight: 700;
  color: var(--teal);
  line-height: 1;
}

.donut-sub { font-size: 9px; color: var(--text3); }

.diversity-legend { flex: 1; display: flex; flex-direction: column; gap: 6px; }

.div-item {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 11px;
  color: var(--text2);
}

.div-dot {
  width: 8px; height: 8px;
  border-radius: 2px;
  flex-shrink: 0;
}

.div-pct { margin-left: auto; font-weight: 600; color: var(--text); }

/* Message preview */
.msg-list { display: flex; flex-direction: column; gap: 0; }

.msg-item {
  display: flex;
  gap: 10px;
  padding: 10px 0;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  transition: all var(--t);
}

.msg-item:last-child { border-bottom: none; }
.msg-item:hover { padding-left: 4px; }

.msg-avatar {
  width: 32px; height: 32px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Fraunces', serif;
  font-size: 12px; font-weight: 700;
  flex-shrink: 0;
}

.msg-name { font-size: 12px; font-weight: 600; color: var(--text); }
.msg-preview { font-size: 11px; color: var(--text3); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.msg-time { font-size: 10px; color: var(--text3); white-space: nowrap; }

.unread-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--teal);
  flex-shrink: 0;
  margin-top: 4px;
}

/* ─── QUICK POST MODAL ─── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.75);
  backdrop-filter: blur(6px);
  z-index: 200;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.22s;
}

.modal-overlay.open { opacity: 1; pointer-events: all; }

.modal {
  background: var(--s1);
  border: 1px solid var(--border2);
  border-radius: var(--rxl);
  padding: 26px;
  width: 480px;
  max-width: 92vw;
  transform: translateY(18px) scale(0.97);
  transition: transform 0.28s var(--ease);
}

.modal-overlay.open .modal { transform: none; }

.modal-title {
  font-family: 'Fraunces', serif;
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-x {
  width: 28px; height: 28px;
  border-radius: 7px;
  background: var(--s2);
  border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--text2);
  font-size: 14px;
  transition: all var(--t);
}

.modal-x:hover { background: var(--s3); color: var(--text); }

.mf { margin-bottom: 12px; }
.ml { font-size: 12px; font-weight: 500; color: var(--text2); margin-bottom: 5px; display: block; }

.mi {
  width: 100%;
  background: var(--s2);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 9px 12px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 13px;
  color: var(--text);
  outline: none;
  transition: border-color var(--t);
  appearance: none;
}

.mi:focus { border-color: var(--teal); }
.mi::placeholder { color: var(--text3); }

.mf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* ─── TOAST ─── */
.toast {
  position: fixed;
  bottom: 24px;
  left: calc(var(--sw) + 50%);
  transform: translateX(-50%) translateY(10px);
  background: var(--s3);
  border: 1px solid var(--border2);
  border-radius: 10px;
  padding: 10px 18px;
  font-size: 13px;
  color: var(--text);
  z-index: 999;
  opacity: 0;
  transition: all 0.3s ease;
  pointer-events: none;
  box-shadow: 0 8px 24px rgba(0,0,0,0.4);
  white-space: nowrap;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 1300px) {
  .stats-row { grid-template-columns: repeat(3, 1fr); }
  .applicants-grid { grid-template-columns: repeat(2, 1fr); }
  .bottom-row { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 1024px) {
  .pipeline-grid { grid-template-columns: 1fr; }
  .table-header, .table-row { grid-template-columns: 2fr 1fr 1fr 1fr 90px; }
  .table-header > *:nth-child(3),
  .table-row   > *:nth-child(3) { display: none; }
}

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .topbar, .main { left: 0; margin-left: 0; }
  .bottom-row { grid-template-columns: 1fr; }
  .applicants-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- ═══════════════ SIDEBAR ═══════════════ -->
<aside class="sidebar">
  <a href="/" class="sidebar-logo">
    <div class="logo-gem">J</div>
    <span class="logo-word">Job<em>Flow</em></span>
  </a>

  <div class="sidebar-role-tag">Recruiter Portal</div>

  <div class="nav-group">
    <div class="nav-group-label">Overview</div>
    <a class="nav-item active" href="#">
      <div class="nav-icon">⊞</div> Dashboard
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">📋</div> My Job Posts
      <span class="nav-badge">{{ $activeJobsCount }}</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">👥</div> All Applicants
      <span class="nav-badge amber">{{ $totalApplicants }}</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">⭐</div> Shortlisted
      <span class="nav-badge">{{ $shortlistedCount }}</span>
    </a>
  </div>

  <div class="nav-group">
    <div class="nav-group-label">Hiring</div>
    <a class="nav-item" href="#">
      <div class="nav-icon">📅</div> Interviews
      <span class="nav-badge amber">{{ $interviewsCount }}</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">🎯</div> Offers Sent
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">💬</div> Messages
      <span class="nav-badge rose">7</span>
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">📊</div> Analytics
    </a>
  </div>

  <div class="nav-group">
    <div class="nav-group-label">Settings</div>
    <a class="nav-item" href="#">
      <div class="nav-icon">🏢</div> Company Profile
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">👤</div> Team Members
    </a>
    <a class="nav-item" href="#">
      <div class="nav-icon">⚙</div> Preferences
    </a>
  </div>

  <div class="sidebar-company">
    <div class="company-row">
      <div class="company-logo">{{ substr(auth()->user()->company ?? auth()->user()->name, 0, 1) }}</div>
      <div>
        <div class="company-name">{{ auth()->user()->company ?? auth()->user()->name }}</div>
        <div class="company-sub">Recruiter · Pro Plan</div>
      </div>
    </div>
    <div class="company-stats">
      <div class="co-stat">
        <div class="co-stat-val">{{ $activeJobsCount }}</div>
        <div class="co-stat-label">Active</div>
      </div>
      <div class="co-stat">
        <div class="co-stat-val">{{ $totalApplicants }}</div>
        <div class="co-stat-label">Applied</div>
      </div>
      <div class="co-stat">
        <div class="co-stat-val">{{ $shortlistedCount }}</div>
        <div class="co-stat-label">Shortlist</div>
      </div>
    </div>
  </div>
</aside>

<!-- ═══════════════ TOPBAR ═══════════════ -->
<header class="topbar">
  <div>
    <div class="page-title">Recruiter Dashboard</div>
    <div class="page-sub">{{ date('l, d F Y') }} · {{ auth()->user()->company ?? 'Your Company' }}</div>
  </div>
  <div class="topbar-search">
    <svg class="topbar-search-icon" width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="6" cy="6" r="4.5" stroke="currentColor" stroke-width="1.3"/><path d="M9.5 9.5L13 13" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
    <input type="text" placeholder="Search candidates, jobs…">
  </div>
  <div class="topbar-right">
    <div class="icon-btn" style="position:relative;">
      <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M7.5 1.5C5 1.5 3 3.5 3 6V9.5L1.5 11H13.5L12 9.5V6C12 3.5 10 1.5 7.5 1.5Z" stroke="currentColor" stroke-width="1.3"/><path d="M6 11C6 11.83 6.67 12.5 7.5 12.5S9 11.83 9 11" stroke="currentColor" stroke-width="1.3"/></svg>
      <div class="notif-dot"></div>
    </div>
    <div class="icon-btn">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><circle cx="7.5" cy="5" r="2.5" stroke="currentColor" stroke-width="1.3"/><path d="M2 13c0-2.76 2.46-5 5.5-5s5.5 2.24 5.5 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        </button>
      </form>
    </div>
    <button class="btn btn-teal" onclick="openModal()">
      <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M6.5 1.5V11.5M1.5 6.5H11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
      Post New Job
    </button>
  </div>
</header>

<!-- ═══════════════ MAIN ═══════════════ -->
<main class="main">

  @if(session('success'))
    <div style="background: rgba(0, 212, 170, 0.1); border: 1px solid var(--teal); color: var(--teal); padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        {{ session('success') }}
    </div>
  @endif

  <!-- ── STATS ROW ── -->
  <div class="stats-row a0">
    <div class="stat-card st-teal">
      <div class="stat-icon" style="background:var(--teal-d);">📋</div>
      <div class="stat-val" style="color:var(--teal);" data-target="{{ $activeJobsCount }}">0</div>
      <div class="stat-lbl">Active Postings</div>
      <div class="stat-delta up">↑ +2 this month</div>
    </div>
    <div class="stat-card st-amber">
      <div class="stat-icon" style="background:var(--amber-d);">📥</div>
      <div class="stat-val" style="color:var(--amber);" data-target="{{ $totalApplicants }}">0</div>
      <div class="stat-lbl">Total Applicants</div>
      <div class="stat-delta up">↑ +38 this week</div>
    </div>
    <div class="stat-card st-sky">
      <div class="stat-icon" style="background:var(--sky-d);">⭐</div>
      <div class="stat-val" style="color:var(--sky);" data-target="{{ $shortlistedCount }}">0</div>
      <div class="stat-lbl">Shortlisted</div>
      <div class="stat-delta up">↑ +5 this week</div>
    </div>
    <div class="stat-card st-violet">
      <div class="stat-icon" style="background:var(--violet-d);">🗓</div>
      <div class="stat-val" style="color:var(--violet);" data-target="{{ $interviewsCount }}">0</div>
      <div class="stat-lbl">Interviews Scheduled</div>
      <div class="stat-delta up">↑ +3 this week</div>
    </div>
    <div class="stat-card st-rose">
      <div class="stat-icon" style="background:var(--rose-d);">⚡</div>
      <div class="stat-val" style="color:var(--rose);" data-target="12">0</div>
      <div class="stat-lbl">Avg. Days to Hire</div>
      <div class="stat-delta up">↓ −3 vs last quarter</div>
    </div>
  </div>

  <!-- ── JOB POSTINGS TABLE ── -->
  <div class="jobs-section a1">
    <div class="sh">
      <span class="sh-title">Active Job Postings</span>
      <div class="sh-right">
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Filter ▾</button>
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Export</button>
        <button class="btn btn-teal" style="font-size:12px;padding:6px 14px;" onclick="openModal()">+ Post Job</button>
      </div>
    </div>

    <div class="jobs-table">
      <div class="table-header">
        <div class="th-cell">Job Title</div>
        <div class="th-cell">Status</div>
        <div class="th-cell">Posted</div>
        <div class="th-cell">Applicants</div>
        <div class="th-cell">Fill Rate</div>
        <div class="th-cell">Actions</div>
      </div>

      @forelse($jobPostings as $job)
      <div class="table-row tr-teal" onclick="filterByJob('{{ $job->title }}')">
        <div class="job-title-cell">
          <div class="job-logo" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);">{{ substr($job->title, 0, 1) }}</div>
          <div>
            <div class="job-name">{{ $job->title }}</div>
            <div class="job-dept">{{ $job->department ?? 'Engineering' }} · {{ $job->location ?? 'Remote' }} · {{ $job->salary ?? '$140K–$190K' }}</div>
          </div>
        </div>
        <div><span class="status-badge s-active">● {{ ucfirst($job->status ?? 'active') }}</span></div>
        <div><div class="cell-text">{{ $job->created_at->format('M d, Y') }}</div><div class="cell-sub">{{ $job->created_at->diffForHumans() }}</div></div>
        <div><div class="cell-num">{{ $job->applications_count }}</div><div class="cell-sub">0 shortlisted</div></div>
        <div class="prog-cell">
          <div class="prog-row">
            <div class="prog-bar"><div class="prog-fill" style="width:68%;background:var(--teal);"></div></div>
            <span class="prog-val">68%</span>
          </div>
        </div>
        <div class="row-actions">
          <button class="btn btn-outline-teal" style="font-size:11px;padding:5px 10px;" onclick="event.stopPropagation();showToast('Viewing applicants…')">View</button>
          <button class="btn btn-ghost" style="font-size:11px;padding:5px 8px;" onclick="event.stopPropagation();">⋯</button>
        </div>
      </div>
      @empty
      <div style="padding: 40px; text-align: center; color: var(--text3);">
        No job postings found. Click "Post New Job" to get started.
      </div>
      @endforelse

    </div>
  </div>

  <!-- ── PIPELINE + SOURCES ── -->
  <div class="pipeline-grid a2">

    <!-- Applicant Funnel -->
    <div class="card">
      <div class="sh" style="margin-bottom:14px;">
        <span class="sh-title">Hiring Funnel — All Roles</span>
        <div class="sh-right">
          <button class="btn btn-ghost" style="font-size:11px;padding:5px 10px;">This Month ▾</button>
        </div>
      </div>
      <div class="funnel">

        <div class="funnel-stage active-stage" onclick="setActiveStage(this)">
          <div class="funnel-fill" style="width:100%;background:rgba(0,212,170,0.06);"></div>
          <div class="stage-info">
            <div class="stage-name">Total Applied</div>
            <div class="stage-sub">All active roles combined</div>
          </div>
          <div class="stage-count">{{ $totalApplicants }}</div>
          <div class="stage-pct" style="color:var(--teal);">100%</div>
        </div>

        <div class="funnel-stage" onclick="setActiveStage(this)">
          <div class="funnel-fill" style="width:72%;background:rgba(56,189,248,0.05);"></div>
          <div class="stage-info">
            <div class="stage-name">Screened / Reviewed</div>
            <div class="stage-sub">Passed initial ATS filter</div>
          </div>
          <div class="stage-count">0</div>
          <div class="stage-pct" style="color:var(--sky);">0%</div>
        </div>

        <div class="funnel-stage" onclick="setActiveStage(this)">
          <div class="funnel-fill" style="width:44%;background:rgba(251,191,36,0.05);"></div>
          <div class="stage-info">
            <div class="stage-name">Shortlisted</div>
            <div class="stage-sub">Manually reviewed & approved</div>
          </div>
          <div class="stage-count">{{ $shortlistedCount }}</div>
          <div class="stage-pct" style="color:var(--amber);">0%</div>
        </div>

      </div>
    </div>

    <!-- Traffic Sources -->
    <div class="card">
      <div class="sh" style="margin-bottom:16px;">
        <span class="sh-title">Top Sources</span>
        <button class="btn btn-ghost" style="font-size:11px;padding:5px 10px;">Details →</button>
      </div>
      <div class="source-list">
        <div class="source-item">
          <div class="source-icon" style="background:var(--teal-d);">🌐</div>
          <span class="source-name">JobFlow Platform</span>
          <div class="source-bar"><div class="source-fill" style="width:90%;background:var(--teal);"></div></div>
          <span class="source-count" style="color:var(--teal);">{{ $totalApplicants }}</span>
        </div>
      </div>

      <!-- Divider -->
      <div style="border-top:1px solid var(--border);margin:16px 0;"></div>

      <!-- Time-to-hire -->
      <div class="sh-title" style="margin-bottom:12px;">Avg. Time to Hire by Role</div>
      <div style="display:flex;flex-direction:column;gap:8px;">
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="font-size:11px;color:var(--text2);width:110px;flex-shrink:0;">Full-Stack Eng.</span>
          <div class="prog-bar" style="flex:1;height:5px;"><div class="prog-fill" style="width:60%;background:var(--teal);height:5px;border-radius:3px;"></div></div>
          <span style="font-size:11px;font-weight:600;color:var(--teal);width:36px;text-align:right;">18d</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ── APPLICANTS ── -->
  <div class="applicants-section a3">
    <div class="sh" style="margin-bottom:12px;">
      <span class="sh-title">
        Applicants
        <span id="jobFilterLabel" style="font-size:12px;color:var(--teal);background:var(--teal-d);border:1px solid rgba(0,212,170,0.2);border-radius:5px;padding:2px 8px;margin-left:8px;font-weight:500;">Recent Applications</span>
      </span>
      <div class="sh-right">
        <div class="filter-tabs" id="filterTabs">
          <button class="filter-tab active" onclick="setFilter(this,'all')">All ({{ $totalApplicants }})</button>
          <button class="filter-tab" onclick="setFilter(this,'shortlisted')">Shortlisted ({{ $shortlistedCount }})</button>
        </div>
        <button class="btn btn-ghost" style="font-size:12px;padding:6px 12px;">Sort ↕</button>
      </div>
    </div>

    <div class="applicants-grid" id="applicantsGrid">

      @forelse($recentApplicants as $application)
      <div class="applicant-card @if($application->status == 'shortlisted') shortlisted @endif" id="ac-{{ $application->id }}">
        <div class="ac-top">
          <div class="ac-info">
            <div class="ac-avatar" style="background:linear-gradient(135deg,var(--teal),var(--sky));">
              {{ substr($application->user->name, 0, 1) }}
              <div class="ac-online"></div>
            </div>
            <div>
              <div class="ac-name">{{ $application->user->name }}</div>
              <div class="ac-role">{{ $application->jobPost->title }}</div>
            </div>
          </div>
          <div class="ac-actions">
            <div class="ac-btn approve" onclick="shortlist('ac-{{ $application->id }}')" title="Shortlist">⭐</div>
            <div class="ac-btn" onclick="showToast('Opening profile…')" title="View Profile">👤</div>
            <div class="ac-btn reject" onclick="rejectCard('ac-{{ $application->id }}')" title="Reject">✕</div>
          </div>
        </div>
        <div class="ac-tags">
          @foreach(json_decode($application->jobPost->skills ?? '[]') as $skill)
          <span class="ac-tag">{{ $skill }}</span>
          @endforeach
        </div>
        <div class="ac-match">
          <span class="ac-match-lbl">Match</span>
          <div class="ac-match-bar"><div class="ac-match-fill" style="width:{{ $application->jobPost->match }}%;"></div></div>
          <span class="ac-match-pct">{{ $application->jobPost->match }}%</span>
        </div>
        <div class="ac-footer">
          <span class="ac-exp">{{ $application->user->location ?? 'Remote' }}</span>
          <span class="ac-stage-pill" style="color:var(--teal);background:var(--teal-d);border-color:rgba(0,212,170,0.25);">{{ ucfirst($application->status) }}</span>
        </div>
      </div>
      @empty
      <div style="grid-column: span 3; padding: 40px; text-align: center; color: var(--text3);">
        No recent applications found.
      </div>
      @endforelse

    </div>
  </div>

</main>

<!-- ═══════════════ POST JOB MODAL ═══════════════ -->
<div class="modal-overlay" id="postModal" onclick="closeModalOutside(event)">
  <div class="modal">
    <div class="modal-title">
      Post a New Job
      <div class="modal-x" onclick="closeModal()">✕</div>
    </div>

    <form action="{{ route('jobs.store') }}" method="POST" id="publishJobForm">
        @csrf
        <div class="mf">
          <label class="ml">Job Title</label>
          <input class="mi" type="text" name="title" placeholder="e.g. Senior Backend Engineer" required>
        </div>
        <div class="mf-row">
          <div class="mf">
            <label class="ml">Department</label>
            <select class="mi" name="department">
              <option>Engineering</option><option>Product</option><option>Design</option>
              <option>Data / ML</option><option>DevOps</option><option>Marketing</option>
            </select>
          </div>
          <div class="mf">
            <label class="ml">Work Mode</label>
            <select class="mi" name="location">
              <option>Remote</option><option>Hybrid</option><option>On-site</option>
            </select>
          </div>
        </div>
        <div class="mf">
          <label class="ml">Salary Range</label>
          <input class="mi" type="text" name="salary" placeholder="$120,000 - $160,000">
        </div>
        <div class="mf">
          <label class="ml">Job Description</label>
          <textarea class="mi" name="description" rows="3" placeholder="Describe responsibilities…" style="resize:vertical;"></textarea>
        </div>
        <div style="display:flex;gap:10px;margin-top:4px;">
          <button type="button" class="btn btn-ghost" style="flex:1;justify-content:center;" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-teal" style="flex:1.5;justify-content:center;">
            Publish Job ✦
          </button>
        </div>
    </form>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script>
  // ── STAT COUNTERS ──
  document.querySelectorAll('.stat-val[data-target]').forEach(el => {
    const target = +el.dataset.target;
    let current = 0;
    const step = Math.max(1, Math.ceil(target / 45));
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = current;
      if (current >= target) clearInterval(timer);
    }, 28);
  });

  // ── MODAL ──
  function openModal() { document.getElementById('postModal').classList.add('open'); }
  function closeModal() { document.getElementById('postModal').classList.remove('open'); }
  function closeModalOutside(e) { if (e.target === document.getElementById('postModal')) closeModal(); }

  // ── TOAST ──
  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    t.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(t._timer);
    t._timer = setTimeout(() => {
      t.style.opacity = '0';
      t.style.transform = 'translateX(-50%) translateY(10px)';
    }, 2400);
  }
</script>
</body>
</html>
