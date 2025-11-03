@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Product') }} {{-- Page header --}}
    </h2>
@endsection

@section('content')

{{-- Product Edit Form --}}
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <form id="productForm-edit"
              class="p-6 space-y-4"
              action="{{ route('admin.product.update', $product->id) }}"
              method="POST"
              enctype="multipart/form-data">

    @csrf {{-- CSRF protection --}}
    @method('PUT') {{-- Use PUT method for resource update --}}

    {{-- Product Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.product_name') }}</label>
        <input type="text" name="name" id="name-edit" value="{{ old('name', $product->name) }}" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Product Description --}}
    <div>
        <label for="descriptions" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.description') }}</label>
        <textarea name="descriptions" id="descriptions-edit" cols="30" rows="4"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">{{ old('descriptions', $product->descriptions) }}</textarea>
    </div>

    {{-- Product Price --}}
    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.price') }}</label>
        <div class="flex items-center">
            <input type="number" name="price" id="price-edit" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            <span class="ml-2">USD</span>
        </div>
    </div>

    {{-- Stock Quantity --}}
    <div>
        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.stock_quantity') }}</label>
        <input type="number" name="stock_quantity" id="stock_quantity-edit" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Discount Percent --}}
    <div>
        <label for="discount_percent" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.discount_percent') }} (%)</label>
        <input type="number" name="discount_percent" id="discount_percent-edit" value="{{ old('discount_percent', $product->discount_percent) }}" min="0" max="100" step="0.01"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Product Category Selection --}}
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.product_category') }}</label>
        <select name="category_id" id="category_id-edit" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            <option value="">-- {{ __('admin.select_category') }} --</option>
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
        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.product_image') }}</label>
        <input type="file" name="image_url" id="imageInput" accept="image/*"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    {{-- Display current image (if exists) --}}
    @if($product->image_url)
        <p class="text-sm text-gray-600">{{ __('admin.current_image') }}</p>
        <img id="previewImage" src="{{ asset('images/products/' . $product->image_url) }}" alt="Product Image" width="150"
             class="mt-1 rounded border">
    @endif

    {{-- Action Buttons --}}
    <div class="flex justify-end gap-2">
        {{-- Cancel button: Go back to product listing --}}
        <a href="{{ route('admin.product') }}"
           class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
            {{ __('admin.cancel') }}
        </a>
        {{-- Submit button: Save changes --}}
        <button type="submit"
                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            {{ __('admin.save') }}
        </button>
    </div>
        </form>
    </div>
</div>

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
