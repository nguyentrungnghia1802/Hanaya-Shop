@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Product Details') }} - {{ $product->name }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('admin.product') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            Back to Products
                        </a>
                    </div>

                    <!-- Product Information Card -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Image -->
                            <div class="flex justify-center md:justify-start">
                                <div class="aspect-w-3 aspect-h-4 w-full max-w-xs">
                                    <img src="{{ asset('images/products/' . ($product->image_url ?? 'base.jpg')) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover rounded-lg shadow-md border">
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product ID</label>
                                    <p class="text-lg font-semibold text-gray-900">#{{ $product->id }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                    <p class="text-gray-900">{{ $product->category->name ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                    <p class="text-gray-900">{{ number_format($product->price, 0, ',', '.') }} USD</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                                    <p class="text-gray-900">{{ $product->stock_quantity }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                                    <p class="text-gray-900">{{ $product->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Updated At</label>
                                    <p class="text-gray-900">{{ $product->updated_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="bg-white border rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <div class="prose max-w-none">
                            @if ($product->descriptions)
                                <div class="text-gray-700 leading-relaxed">
                                    {!! nl2br(e($product->descriptions)) !!}
                                </div>
                            @else
                                <p class="text-gray-500 italic">No description available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.product.edit', $product->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
