import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions to show loading state on buttons (Event Delegation)
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('form');
        if (form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.classList.contains('no-spinner')) {
                submitBtn.classList.add('btn-loading');
            }
        }
    });

    // Handle generic button clicks for spinner (Event Delegation)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-spinner');
        if (btn) {
            btn.classList.add('btn-loading');
        }
    });
});
