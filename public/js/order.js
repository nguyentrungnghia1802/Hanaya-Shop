/**
 * Order JavaScript - CSP Compliant
 * Handles order confirmation dialogs and form submissions
 */

document.addEventListener('DOMContentLoaded', function() {
    initOrderCancelButtons();
    initOrderForms();
    initReviewButtons();
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
                submitButton.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Processing...';
            }
        });
    });
}

function initReviewButtons() {
    // Handle review button clicks to prevent conflicts
    const reviewButtons = document.querySelectorAll('a[href*="review.create"]');
    
    reviewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Stop event propagation to prevent parent elements from handling the click
            e.stopPropagation();
        });
    });
    
    // Handle product link clicks
    const productLinks = document.querySelectorAll('a[href*="products.show"]');
    
    productLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Stop event propagation to prevent conflicts
            e.stopPropagation();
        });
    });
}
