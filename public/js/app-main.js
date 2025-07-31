/**
 * Main App JavaScript - CSP Compliant
 * Handles page loading overlay and navigation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Block clicks on nav-link and delay page change
    document.querySelectorAll('nav a, button[type="submit"]').forEach(link => {
        link.addEventListener('click', function(e) {
            // Only handle internal links (no target _blank, not anchor, no modifier key)
            if (
                this.target === '_blank' ||
                this.href && this.href.startsWith('javascript:') ||
                this.href === '#' ||
                e.ctrlKey || e.shiftKey || e.metaKey || e.altKey
            ) return;
            
            const overlay = document.getElementById('pageLoadingOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
            }
        });
    });
    
    // When clicking Delete Account button in modal, turn off loading overlay immediately
    document.querySelectorAll('button').forEach(btn => {
        if (btn.textContent.trim() === 'Delete Account') {
            btn.addEventListener('click', function() {
                const overlay = document.getElementById('pageLoadingOverlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            });
        }
    });
});
