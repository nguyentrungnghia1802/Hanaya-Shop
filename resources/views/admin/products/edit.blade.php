@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Product') }} {{-- Page header --}}
    </h2>
@endsection

@section('content')

{{-- Product Edit Form --}}
<form id="productForm-edit"
      class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
      bg-white p-6 rounded-lg shadow-lg z-50 w-full max-w-3xl space-y-4"
      action="{{ route('admin.product.update', $product->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf {{-- CSRF protection --}}
    @method('PUT') {{-- Use PUT method for resource update --}}

    {{-- Product Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
        <input type="text" name="name" id="name-edit" value="{{ old('name', $product->name) }}" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Product Description --}}
    <div>
        <label for="descriptions" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="descriptions" id="descriptions-edit" cols="30" rows="4"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">{{ old('descriptions', $product->descriptions) }}</textarea>
    </div>

    {{-- Product Price --}}
    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
        <input type="number" name="price" id="price-edit" value="{{ old('price', $product->price) }}" required min="0"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Stock Quantity --}}
    <div>
        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
        <input type="number" name="stock_quantity" id="stock_quantity-edit" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Product Category Selection --}}
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Product Category</label>
        <select name="category_id" id="category_id-edit" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            <option value="">-- Select a category --</option>
            @foreach ($categories as $category)
                {{-- Preserve selected category after form submission --}}
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Product Image Upload --}}
    <div>
        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
        <input type="file" name="image_url" id="imageInput" accept="image/*"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Display current image (if exists) --}}
    @if($product->image_url)
        <p class="text-sm text-gray-600">Current image:</p>
        <img id="previewImage" src="{{ asset('images/' . $product->image_url) }}" alt="Product Image" width="150"
             class="mt-1 rounded border">
    @endif

    {{-- Action Buttons --}}
    <div class="flex justify-end gap-2">
        {{-- Cancel button: Go back to product listing --}}
        <a href="{{ route('admin.product') }}"
           class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
            Cancel
        </a>
        {{-- Submit button: Save changes --}}
        <button type="submit"
                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            Save
        </button>
    </div>
</form>

@endsection

{{-- Preview uploaded image before submitting --}}
<script>
    document.getElementById('imageInput')?.addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('previewImage').src = URL.createObjectURL(file);
        }
    });
</script>
