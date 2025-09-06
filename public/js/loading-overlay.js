/**
 * Loading Overlay Handler - CSP Compliant
 * Handles loading overlay for both user and admin layouts
 * Prevents infinite loading when using browser back/forward buttons
 * Enhanced for Docker production environment
 */

(function() {
    'use strict';
    
    // Hide loading overlay function
    function hideLoadingOverlay() {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            // Handle both style-based and class-based overlays
            overlay.style.display = 'none';
            overlay.style.visibility = 'hidden';
            overlay.classList.add('hidden');
            overlay.classList.remove('flex', 'items-center', 'justify-center');
            
            // Force reflow to ensure changes are applied
            overlay.offsetHeight;
        }
    }
    
    // Show loading overlay function
    function showLoadingOverlay() {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            // Handle both style-based and class-based overlays
            overlay.style.display = 'flex';
            overlay.style.visibility = 'visible';
            overlay.classList.remove('hidden');
            overlay.classList.add('flex', 'items-center', 'justify-center');
            
            // Force reflow to ensure changes are applied
            overlay.offsetHeight;
        }
    }
    
    // Force hide overlay immediately when script loads
    hideLoadingOverlay();
    
    // Multiple event listeners for better coverage
    
    // Handle browser back/forward navigation
    window.addEventListener('pageshow', function(event) {
        hideLoadingOverlay();
        // Also handle persisted pages (from cache)
        if (event.persisted) {
            hideLoadingOverlay();
        }
    });
    
    // Handle page visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            hideLoadingOverlay();
        }
    });
    
    // Handle window focus
    window.addEventListener('focus', function() {
        hideLoadingOverlay();
    });
    
    // Handle beforeunload to show loading
    window.addEventListener('beforeunload', function() {
        // Only show loading if it's a navigation, not a refresh
        if (document.activeElement && document.activeElement.tagName === 'A') {
            showLoadingOverlay();
        }
    });
    
    // Handle popstate (browser back/forward)
    window.addEventListener('popstate', function() {
        hideLoadingOverlay();
    });
    
    // Handle hashchange
    window.addEventListener('hashchange', function() {
        hideLoadingOverlay();
    });
    
    // Multiple fallback timeouts for production environment
    setTimeout(hideLoadingOverlay, 50);
    setTimeout(hideLoadingOverlay, 100);
    setTimeout(hideLoadingOverlay, 200);
    setTimeout(hideLoadingOverlay, 500);
    
    // Make functions globally available
    window.hideLoadingOverlay = hideLoadingOverlay;
    window.showLoadingOverlay = showLoadingOverlay;
    
    // Debug logging for production troubleshooting
    if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
        console.log('Loading overlay handler initialized for production');
    }
    
})();
