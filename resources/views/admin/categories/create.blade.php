@extends('layouts.admin')

@section('header')
    <!-- Page header -->
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Add Flower Category') }}
    </h2>
@endsection

@section('content')
    <!-- The form for adding a new flower category -->
    <form 
        id="categoryForm" 
        action="{{ route('admin.category.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="mb-20 max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md space-y-4">
        
        @csrf <!-- CSRF token for form security -->
        
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Add Flower Category</h2>

        <!-- Input field for flower category name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                required 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            
            <!-- Display backend validation error for name -->
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <!-- Custom client-side error message (initially hidden) -->
            <p id="errorMsg" class="hidden text-red-500 text-sm mt-1">This category already exists.</p>
        </div>

        <!-- Input for flower category image -->
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Category Image:</label>
            <input 
                type="file" 
                name="image" 
                id="imageInput" 
                accept="image/*"
                class="block mt-1">
            
            <!-- Placeholder or current image preview -->
            <p class="mt-2 text-sm text-gray-600">Current Image:</p>
            <img 
                id="previewImage" 
                src="{{ asset('images/base.jpg') }}" 
                alt="Category Image" 
                width="150">
        </div>

        <!-- Input for category description (plain textarea) -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea 
                name="description" 
                id="description" 
                class="w-full h-[120px] px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"
                >{{ old('description') }}</textarea>
        </div>

        <!-- Submit button -->
        <button 
            type="submit" 
            class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
            Save
        </button>
    </form>
@endsection