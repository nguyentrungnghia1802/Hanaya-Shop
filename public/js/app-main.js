/**
 * Main App JavaScript - CSP Compliant
 * Handles page loading overlay and navigation
 * Enhanced for Docker production environment
 */

// Force hide loading overlay immediately when script loads
if (typeof window.hideLoadingOverlay === 'function') {
    window.hideLoadingOverlay();
}

document.addEventListener('DOMContentLoaded', function() {
    // Ensure loading overlay is hidden when DOM is ready
    if (typeof window.hideLoadingOverlay === 'function') {
        window.hideLoadingOverlay();
    }
    
    // Block clicks on nav-link and delay page change
    document.querySelectorAll('nav a, button[type="submit"]').forEach(link => {
        link.addEventListener('click', function(e) {
            // Only handle internal links (no target _blank, not anchor, no modifier key)
            if (
                this.target === '_blank' ||
                this.href && this.href.startsWith('javascript:') ||
                this.href === '#' ||
                e.ctrlKey || e.shiftKey || e.metaKey || e.altKey ||
                this.getAttribute('data-no-loading') === 'true'
            ) return;
            
            if (typeof window.showLoadingOverlay === 'function') {
                window.showLoadingOverlay();
            }
        });
    });
    
    // When clicking Delete Account button in modal, turn off loading overlay immediately
    document.querySelectorAll('button').forEach(btn => {
        if (btn.textContent.trim() === 'Delete Account') {
            btn.addEventListener('click', function() {
                if (typeof window.hideLoadingOverlay === 'function') {
                    window.hideLoadingOverlay();
                }
            });
        }
    });
});

// Additional safety nets for production environment
window.addEventListener('load', function() {
    if (typeof window.hideLoadingOverlay === 'function') {
        window.hideLoadingOverlay();
    }
});

// Handle when page becomes visible again
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && typeof window.hideLoadingOverlay === 'function') {
        window.hideLoadingOverlay();
    }
});

// Handle window focus
window.addEventListener('focus', function() {
    if (typeof window.hideLoadingOverlay === 'function') {
        window.hideLoadingOverlay();
    }
});
