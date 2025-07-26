/**
 * Order JavaScript - CSP Compliant
 * Handles order confirmation dialogs and form submissions
 */

document.addEventListener('DOMContentLoaded', function() {
    initOrderCancelButtons();
    initOrderForms();
});

function initOrderCancelButtons() {
    const cancelButtons = document.querySelectorAll('[data-confirm-cancel]');
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmed = confirm('Are you sure you want to cancel this order?');
            if (confirmed) {
                // Navigate to the cancel URL
                window.location.href = this.href;
            }
        });
    });
}

function initOrderForms() {
    const orderForms = document.querySelectorAll('.order-form');
    
    orderForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            }
        });
    });
}
