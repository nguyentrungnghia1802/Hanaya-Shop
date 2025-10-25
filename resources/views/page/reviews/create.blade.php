<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        <!-- Breadcrumb Navigation -->
        <nav class="flex text-sm mb-4 text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('order.index') }}" class="hover:text-gray-900">Orders</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Write Review</span>
        </nav>

        <!-- Page Title -->
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 text-center">Write Your Review</h1>

        <!-- Thông báo lỗi -->
        <x-alert />

        <!-- Review Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
            <!-- Product Information -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg border">
                <div class="flex items-center space-x-4">
                    <img src="{{ $product->image_url ? asset('images/products/' . $product->image_url) : asset('images/no-image.png') }}"
                        alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900">{{ $product->name }}</h3>
                        <p class="text-gray-600">Order #{{ $order->id }}</p>
                        <p class="text-gray-500 text-sm">Quantity: {{ $orderDetail->quantity }}</p>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <form action="{{ route('review.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <!-- Rating Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Your Rating <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        <div class="star-rating flex space-x-1" data-rating="5">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button"
                                    class="star text-3xl text-gray-300 hover:text-yellow-400 transition-colors duration-200 focus:outline-none"
                                    data-value="{{ $i }}">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <span class="ml-3 text-sm text-gray-600 rating-text">Excellent (5 stars)</span>
                    </div>
                    <input type="hidden" name="rating" id="rating" value="5" required>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment Section -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Your Review</label>
                    <textarea name="comment" id="review-comment"
                        class="w-full h-[120px] px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">{{ old('comment') }}</textarea>
                </div>

                <!-- Input for review image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image:</label>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="block mt-1">

                    <!-- Placeholder or current image preview -->
                    <p class="mt-2 text-sm text-gray-600">Current Image:</p>
                    <img id="previewImage" src="{{ asset('images/base.jpg') }}" alt="Review Image" width="150">
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                    <button type="submit"
                        class="flex-1 bg-pink-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-pink-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                        <i class="fas fa-star mr-2"></i>
                        Submit Review
                    </button>
                    <a href="{{ route('order.index') }}"
                        class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors duration-200 text-center focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Orders
                    </a>
                </div>
            </form>
        </div>

        <!-- Review Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Review Guidelines
            </h3>
            <ul class="text-blue-800 text-sm space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check-circle mt-0.5 mr-2 text-blue-600"></i>
                    Be honest and specific about your experience with the product
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mt-0.5 mr-2 text-blue-600"></i>
                    Focus on the product quality, packaging, and delivery experience
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mt-0.5 mr-2 text-blue-600"></i>
                    Include helpful details for other customers
                </li>
                <li class="flex items-start">
                    <i class="fas fa-times-circle mt-0.5 mr-2 text-red-600"></i>
                    Avoid offensive language or personal information
                </li>
            </ul>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const stars = document.querySelectorAll('.star');
                const ratingInput = document.getElementById('rating');
                const ratingText = document.querySelector('.rating-text');

                const ratingTexts = {
                    1: 'Poor (1 star)',
                    2: 'Fair (2 stars)',
                    3: 'Good (3 stars)',
                    4: 'Very Good (4 stars)',
                    5: 'Excellent (5 stars)'
                };

                // Initialize with 5 stars
                updateStars(5);

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.dataset.value);
                        updateStars(rating);
                        ratingInput.value = rating;
                        ratingText.textContent = ratingTexts[rating];
                    });

                    star.addEventListener('mouseenter', function() {
                        const rating = parseInt(this.dataset.value);
                        highlightStars(rating);
                    });
                });

                document.querySelector('.star-rating').addEventListener('mouseleave', function() {
                    const currentRating = parseInt(ratingInput.value);
                    updateStars(currentRating);
                });

                function updateStars(rating) {
                    stars.forEach((star, index) => {
                        if (index < rating) {
                            star.classList.remove('text-gray-300');
                            star.classList.add('text-yellow-400');
                        } else {
                            star.classList.remove('text-yellow-400');
                            star.classList.add('text-gray-300');
                        }
                    });
                }

                function highlightStars(rating) {
                    stars.forEach((star, index) => {
                        if (index < rating) {
                            star.classList.remove('text-gray-300');
                            star.classList.add('text-yellow-400');
                        } else {
                            star.classList.remove('text-yellow-400');
                            star.classList.add('text-gray-300');
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
