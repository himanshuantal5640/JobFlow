<!-- ADD APPLICATION MODAL -->
<div class="modal-overlay" id="addModal" onclick="if(event.target === this) closeModal()">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Add Application</div>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>

    <form action="#" method="POST">
        @csrf
        <div class="modal-field">
          <label class="modal-label">Company Name</label>
          <input type="text" name="company" class="modal-input" placeholder="e.g. Google, Stripe, Figma...">
        </div>
        <div class="modal-field">
          <label class="modal-label">Job Title</label>
          <input type="text" name="title" class="modal-input" placeholder="e.g. Senior Software Engineer">
        </div>
        <div class="modal-field">
          <label class="modal-label">Stage</label>
          <select name="status" class="modal-input modal-select">
            <option value="applied">Applied</option>
            <option value="review">Under Review</option>
            <option value="interview">Interview</option>
            <option value="offer">Offer</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <div class="modal-field">
          <label class="modal-label">Salary Range (Optional)</label>
          <input type="text" name="salary" class="modal-input" placeholder="e.g. $120K – $160K">
        </div>
        <div class="modal-field">
          <label class="modal-label">Applied Date</label>
          <input type="date" name="applied_at" class="modal-input" value="{{ date('Y-m-d') }}">
        </div>
        <div style="display:flex;gap:10px;margin-top:6px;">
          <button type="button" class="btn btn-ghost" style="flex:1;justify-content:center;" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-primary" style="flex:1.5;justify-content:center;">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 2V12M2 7H12" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            Add to Pipeline
          </button>
        </div>
    </form>
  </div>
</div>
