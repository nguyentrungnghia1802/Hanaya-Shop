/**
 * Dashboard JavaScript - CSP Compliant
 * Handles all dashboard functionality including cart operations and popups
 */

document.addEventListener('DOMContentLoaded', function() {
    // Cart functionality
    document.querySelectorAll('.add-to-cart-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = form.getAttribute('action');
            const quantity = form.querySelector('input[name="quantity"]').value || 1;

            fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessPopup();
                    } else {
                        alert('Error adding product to cart');
                    }
                })
                .catch(() => {
                    alert('An error occurred while adding the product');
                });
        });
    });
    
    // Success popup close handler
    const closePopupButton = document.querySelector('[data-close-popup]');
    if (closePopupButton) {
        closePopupButton.addEventListener('click', function() {
            const popup = document.getElementById('success-popup');
            if (popup) {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
            }
        });
    }
});

function showSuccessPopup() {
    const popup = document.getElementById('success-popup');
    if (popup) {
        popup.classList.remove('hidden');
        popup.classList.add('flex');
    }
}
