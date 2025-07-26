/**
 * Admin Category Show JavaScript - CSP Compliant
 * Handles delete category functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    initDeleteModal();
});

function initDeleteModal() {
    // Delete button handler
    const deleteBtn = document.querySelector('[data-confirm-delete]');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            confirmDelete();
        });
    }

    // Close modal button handler
    const closeBtn = document.querySelector('[data-close-modal]');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            closeDeleteModal();
        });
    }

    // Delete confirm button handler
    const confirmBtn = document.querySelector('[data-delete-category]');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            deleteCategory();
        });
    }

    // Close modal when clicking overlay
    const overlay = document.getElementById('deleteOverlay');
    if (overlay) {
        overlay.addEventListener('click', closeDeleteModal);
    }
}

function confirmDelete() {
    const modal = document.getElementById('deleteModal');
    const overlay = document.getElementById('deleteOverlay');
    
    if (modal) modal.classList.remove('hidden');
    if (overlay) overlay.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const overlay = document.getElementById('deleteOverlay');
    
    if (modal) modal.classList.add('hidden');
    if (overlay) overlay.classList.add('hidden');
}

async function deleteCategory() {
    const deleteForm = document.querySelector('[data-delete-category]');
    const deleteUrl = deleteForm ? deleteForm.getAttribute('data-delete-url') : null;
    
    if (!deleteUrl) {
        alert('Delete URL not found');
        return;
    }

    try {
        const response = await fetch(deleteUrl, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                Accept: "application/json",
            },
        });

        if (response.ok) {
            // Redirect to categories list with success message
            const redirectUrl = deleteForm.getAttribute('data-redirect-url') || '/admin/category';
            window.location.href = redirectUrl;
        } else {
            alert('Failed to delete category');
            closeDeleteModal();
        }
    } catch (error) {
        console.error('Error deleting category:', error);
        alert('An error occurred while deleting the category');
        closeDeleteModal();
    }
}
