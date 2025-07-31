@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Category Details') }} - {{ $category->name }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('admin.category') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Categories
                    </a>
                </div>

                <!-- Category Information Card -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category Image -->
                        <div class="flex justify-center md:justify-start">
                            <div class="w-full max-w-sm">
                                <img src="{{ asset('images/categories/' . ($category->image_path ?? 'base.jpg')) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-64 object-cover rounded-lg shadow-md border">
                            </div>
                        </div>

                        <!-- Category Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category ID</label>
                                <p class="text-lg font-semibold text-gray-900">#{{ $category->id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $category->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                                <p class="text-gray-900">{{ $category->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Updated At</label>
                                <p class="text-gray-900">{{ $category->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="bg-white border rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                    <div class="prose max-w-none">
                        @if($category->description)
                            <div class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($category->description)) !!}
                            </div>
                        @else
                            <p class="text-gray-500 italic">No description available</p>
                        @endif
                    </div>
                </div>

                <!-- Products Count Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $category->product->count() }}</div>
                            <div class="text-sm text-gray-600">Total Products</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $category->product->where('status', 'active')->count() ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600">Active Products</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">
                                {{ $category->product->where('status', 'inactive')->count() ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600">Inactive Products</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.category.edit', $category->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Category
                    </a>

                    <button type="button" data-confirm-delete
                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Category
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md relative">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Delete</h3>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this category? This action cannot be undone.</p>
        
        <div class="flex justify-end space-x-3">
            <button type="button" data-close-modal
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded transition duration-200">
                Cancel
            </button>
            <button type="button" data-delete-category 
                    data-delete-url="{{ route('admin.category.destroy', $category->id) }}"
                    data-redirect-url="{{ route('admin.category') }}"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition duration-200">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Overlay -->
<div id="deleteOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Đảm bảo modal delete ẩn khi trang load
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteOverlay').classList.add('hidden');
});

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteOverlay').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteOverlay').classList.add('hidden');
}

async function deleteCategory() {
    try {
        const response = await fetch("{{ route('admin.category.destroy', $category->id) }}", {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                Accept: "application/json",
            },
        });

        if (response.ok) {
            // Redirect to categories list with success message
            window.location.href = "{{ route('admin.category') }}";
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

// Close modal when clicking overlay
document.getElementById('deleteOverlay').addEventListener('click', closeDeleteModal);
</script>

@push('scripts')
<script src="{{ asset('js/admin-category-show.js') }}"></script>
@endpush

@endsection