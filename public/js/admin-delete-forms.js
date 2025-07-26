/**
 * Admin Delete Forms JavaScript - CSP Compliant
 * Handles form deletion confirmations
 */
document.addEventListener('DOMContentLoaded', function() {
    initDeleteForms();
});

function initDeleteForms() {
    // Bind all forms with data-confirm-delete attribute
    const deleteForms = document.querySelectorAll('form[data-confirm-delete]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const confirmMessage = this.getAttribute('data-confirm-message') || 'Are you sure you want to delete?';
            if (confirm(confirmMessage)) {
                // Submit the form if user confirms
                this.submit();
            }
        });
    });
}
