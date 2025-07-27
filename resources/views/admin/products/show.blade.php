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

                    <!-- Product Reviews Section -->
                    <div class="mt-8">
                        <div class="bg-white border rounded-lg p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Customer Reviews</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600">{{ $reviews->total() }} reviews</span>
                                    @if ($reviews->count() > 0)
                                        <x-star-rating :rating="$product->reviews()->avg('rating') ?? 5" size="sm" show-text />
                                    @endif
                                </div>
                            </div>

                            @if ($reviews->count() > 0)
                                <div class="space-y-6">
                                    @foreach ($reviews as $review)
                                        <div
                                            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3 mb-3">
                                                        <div
                                                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <span class="text-blue-600 font-semibold text-sm">
                                                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-sm font-medium text-gray-900">
                                                                {{ $review->user->name }}</h4>
                                                            <div class="flex items-center space-x-2 mt-1">
                                                                <x-star-rating :rating="$review->rating" size="sm" />
                                                                <span class="text-xs text-gray-500">
                                                                    Order #{{ $review->order_id }}
                                                                </span>
                                                                <span class="text-xs text-gray-500">•</span>
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $review->created_at->format('M d, Y H:i') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if ($review->comment)
                                                        <div
                                                            class="text-gray-700 text-sm leading-relaxed prose prose-sm max-w-none pl-11">
                                                            {!! $review->comment !!}
                                                        </div>
                                                    @endif

                                                    {{-- Image Review --}}
                                                    @if ($review->image_path)
                                                        <div class="mt-3">
                                                            <img src="{{ asset('images/reviews/' . $review->image_path) }}"
                                                                alt="Review Image"
                                                                class="w-full max-w-xs h-auto object-cover rounded-lg shadow-md">
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Delete Review Button -->
                                                <div class="flex-shrink-0 ml-4">
                                                    <form method="POST"
                                                        action="{{ route('admin.product.review.delete', $review->id) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                @if ($reviews->hasPages())
                                    <div class="mt-6 flex justify-center">
                                        {{ $reviews->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <div
                                        class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h4>
                                    <p class="text-gray-600">This product hasn't received any reviews yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
