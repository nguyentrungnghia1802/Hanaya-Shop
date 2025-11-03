@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }}
    </h2>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Display form validation errors --}}
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Display error message stored in session --}}
                    @if (session('error'))
                        <div class="mb-4 text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Display success message stored in session --}}
                    @if (session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form to create a new product --}}
                    <form id="productForm" class="mb-20 max-w-md mx-auto bg-white p-6 rounded-lg shadow-md space-y-4"
                        action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">

                        {{-- CSRF token to prevent cross-site request forgery --}}
                        @csrf

                        {{-- Form title --}}
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('admin.add_new_product') }}</h2>

                        {{-- Product name input --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.product_name') }}</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"
                                value="{{ old('name') }}">

                            {{-- Shown only when product name is duplicated (client-side) --}}
                            <p id="errorMsg" class="hidden text-red-500 text-sm mt-1">{{ __('admin.product_already_exists') }}</p>
                        </div>

                        {{-- Product description input --}}
                        <div>
                            <label for="descriptions" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.product_description') }}</label>
                            <input type="text" name="descriptions" id="descriptions"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        </div>

                        {{-- Product price input --}}
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.price') }}</label>
                            <div class="flex items-center">
                                <input type="number" name="price" id="price" min="0" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                                <span class="ml-2">USD</span>
                            </div>
                        </div>

                        {{-- Stock quantity input --}}
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.stock_quantity') }}</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" required min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        </div>

                        {{-- Discount Percent --}}
                        <div>
                            <label for="discount_percent" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.discount_percent') }} (%)</label>
                            <input type="number" name="discount_percent" id="discount_percent" min="0" max="100" step="0.01" value="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        </div>

                        {{-- Product category selection --}}
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.product_category') }}</label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                                <option value="">-- {{ __('admin.select_category') }} --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Product image input --}}
                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.product_image') }}</label>
                            <input type="file" name="image_url" id="image_url" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        </div>

                        {{-- Preview selected image --}}
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">{{ __('admin.current_image') }}</p>
                            <img id="create-image" src="{{ asset('images/base.jpg') }}" alt="Product Image" width="150"
                                class="mt-1 rounded border">
                        </div>

                        {{-- Submit form button --}}
                        <button type="submit"
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                            {{ __('admin.save') }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript to preview image before submitting --}}
    <script>
        document.getElementById('image_url').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('create-image').src = URL.createObjectURL(file);
            }
        });
    </script>
@endsection
