<aside class="filter-sidebar">
    <form id="filterForm" action="{{ route('jobs.index') }}" method="GET">
        <div class="fs-title">
            Filters
            <span class="fs-clear" onclick="clearAllFilters()">Clear all</span>
        </div>

        <!-- Job Type -->
        <div class="filter-group">
            <div class="fg-label">Job Type</div>
            @foreach(['Full-time', 'Part-time', 'Contract', 'Internship'] as $type)
                <label class="filter-check {{ in_array($type, (array)request('job_type')) ? 'checked' : '' }}">
                    <input type="checkbox" name="job_type[]" value="{{ $type }}" {{ in_array($type, (array)request('job_type')) ? 'checked' : '' }} onchange="this.form.submit()">
                    <div class="check-box">
                        @if(in_array($type, (array)request('job_type')))
                            <svg width="9" height="9" viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
                        @endif
                    </div>
                    <span class="check-label">{{ $type }}</span>
                </label>
            @endforeach
        </div>

        <!-- Work Mode -->
        <div class="filter-group">
            <div class="fg-label">Work Mode</div>
            <div class="filter-pills">
                @foreach(['Remote' => '🌍', 'On-site' => '🏢', 'Hybrid' => '⚡'] as $mode => $icon)
                    <label class="filter-pill {{ in_array($mode, (array)request('work_mode')) ? 'active' : '' }}">
                        <input type="checkbox" name="work_mode[]" value="{{ $mode }}" {{ in_array($mode, (array)request('work_mode')) ? 'checked' : '' }} style="display:none;" onchange="this.form.submit()">
                        {{ $icon }} {{ $mode }}
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Experience Level -->
        <div class="filter-group">
            <div class="fg-label">Experience</div>
            @foreach(['Entry' => 'Entry Level', 'Mid' => 'Mid Level', 'Senior' => 'Senior', 'Lead' => 'Staff / Lead'] as $val => $label)
                <label class="filter-check {{ in_array($val, (array)request('experience')) ? 'checked' : '' }}">
                    <input type="checkbox" name="experience[]" value="{{ $val }}" {{ in_array($val, (array)request('experience')) ? 'checked' : '' }} onchange="this.form.submit()">
                    <div class="check-box">
                        @if(in_array($val, (array)request('experience')))
                            <svg width="9" height="9" viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
                        @endif
                    </div>
                    <span class="check-label">{{ $label }}</span>
                </label>
            @endforeach
        </div>

        <!-- Industry/Department -->
        <div class="filter-group">
            <div class="fg-label">Department</div>
            @foreach(['Engineering', 'Design', 'Product', 'AI / ML', 'Marketing'] as $dept)
                <label class="filter-check {{ in_array($dept, (array)request('department')) ? 'checked' : '' }}">
                    <input type="checkbox" name="department[]" value="{{ $dept }}" {{ in_array($dept, (array)request('department')) ? 'checked' : '' }} onchange="this.form.submit()">
                    <div class="check-box">
                        @if(in_array($dept, (array)request('department')))
                            <svg width="9" height="9" viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
                        @endif
                    </div>
                    <span class="check-label">{{ $dept }}</span>
                </label>
            @endforeach
        </div>

        <button type="submit" class="btn btn-indigo" style="width:100%; justify-content:center;">Apply Filters</button>
    </form>
</aside>

<script>
    function clearAllFilters() {
        window.location.href = "{{ route('jobs.index') }}";
    }
</script>
