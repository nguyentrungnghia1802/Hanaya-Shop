/**
 * Admin Post Create JavaScript - CSP Compliant
 * Handles cancel confirmation functionality and image preview
 */
document.addEventListener('DOMContentLoaded', function() {
    initCancelButtons();
    initImagePreview();
});

function initCancelButtons() {
    // Bind cancel buttons with data-confirm-cancel attribute
    const cancelButtons = document.querySelectorAll('[data-confirm-cancel]');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            confirmCancel();
        });
    });
}

function confirmCancel() {
    if (confirm('Bạn có chắc muốn hủy? Tất cả thay đổi sẽ bị mất!')) {
        // Get redirect URL from data attribute or use default
        const redirectUrl = document.querySelector('[data-confirm-cancel]')?.getAttribute('data-redirect-url') || '/admin/post';
        window.location.href = redirectUrl;
    }
}

function initImagePreview() {
    // Image preview functionality
    const imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
}
