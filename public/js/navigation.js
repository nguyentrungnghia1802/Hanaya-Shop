// Navigation script for logout handling
document.addEventListener('DOMContentLoaded', function() {
    // Handle logout links
    const logoutLinks = document.querySelectorAll('[data-logout-link]');
    
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Find the parent form and submit it
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });
});
