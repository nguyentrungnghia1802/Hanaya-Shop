@extends('layouts.admin')

@section('header')
    <!-- Page header -->
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Category') }}
    </h2>
@endsection

@section('content')
    <!-- Edit flower category form -->
    <form 
        id="categoryForm-edit" 
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
               bg-white p-6 rounded-lg shadow-lg z-50 w-full max-w-3xl space-y-4"
        action="{{ route('admin.category.update', $category->id) }}"
        method="POST" 
        enctype="multipart/form-data">

        @csrf <!-- CSRF token for security -->
        @method('PUT') <!-- HTTP method override to PUT -->

        <!-- Input for flower category name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
            <input 
                type="text" 
                name="name" 
                id="name-edit" 
                value="{{ old('name', $category->name) }}" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            
            <!-- Custom client-side error message (initially hidden) -->
            <p id="errorMsg-edit" class="hidden text-red-500 text-sm mt-1">This category already exists.</p>
        </div>

        <!-- Input for category image file -->
        <input 
            type="file" 
            name="image" 
            id="imageInput" 
            accept="image/*" 
            value="{{ $category->image_path }}">

        <!-- Preview of the current image, if exists -->
        @if($category->image_path)
            <p class="text-sm text-gray-700 mt-2">Current Image:</p>
            <img 
                id="previewImage" 
                src="{{ asset('images/categories/' . $category->image_path) }}" 
                alt="Category Image" 
                width="150">
        @endif

        <!-- Input for category description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea 
                name="description" 
                id="description-edit" 
                cols="30" 
                rows="10"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none description">{{ old('description', $category->description) }}</textarea>
        </div>

        <!-- Action buttons: Cancel and Save -->
        <div class="flex justify-end gap-2">
            <button 
                type="button" 
                id="cancelBtn"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
                Cancel
            </button>
            <button 
                type="submit"
                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                Save
            </button>
        </div>
    </form>

    <!-- Add JavaScript for Cancel button -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Cancel button click
            document.getElementById('cancelBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                    window.location.href = '{{ route('admin.category') }}';
                }
            });

            // Optional: Handle image preview when file is selected
            document.getElementById('imageInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('previewImage');
                        if (preview) {
                            preview.src = e.target.result;
                        } else {
                            // Create new preview image if doesn't exist
                            const img = document.createElement('img');
                            img.id = 'previewImage';
                            img.src = e.target.result;
                            img.width = 150;
                            img.alt = 'Category Image Preview';
                            document.getElementById('imageInput').insertAdjacentElement('afterend', img);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
