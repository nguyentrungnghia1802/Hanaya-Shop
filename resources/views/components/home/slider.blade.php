@props(['products'])

<!-- Swiper Slider -->
<div class="swiper mySwiper relative px-8">
    @if ($products->isNotEmpty())
        <div class="swiper-wrapper">
            @foreach ($products as $productItem)
                <div class="swiper-slide">
                    <div class="bg-white rounded shadow hover:shadow-xl overflow-hidden transition group">
                        <div class="relative">
                            <img src="{{ asset('images/products/' . ($productItem->image_url ?? 'default-product.jpg')) }}" 
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                 alt="{{ $productItem->name }}">
                            @if($productItem->discount_percent > 0)
                            <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                -{{ $productItem->discount_percent }}%
                            </span>
                            @endif
                            
                            <!-- Quick Actions Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="flex gap-2">
                                    <a href="{{ route('user.products.show', $productItem->id) }}" 
                                       class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors"
                                       title="Quick View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button onclick="addToCart({{ $productItem->id }})" 
                                            class="bg-pink-600 text-white p-2 rounded-full hover:bg-pink-700 transition-colors"
                                            title="Add to Cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold line-clamp-2 mb-2">{{ $productItem->name }}</h3>
                            
                            @if($productItem->discount_percent > 0)
                            <div class="flex items-center mb-1">
                                <p class="text-pink-600 font-bold text-lg">
                                    ${{ number_format($productItem->discounted_price ?? $productItem->price, 2) }}
                                </p>
                                <p class="text-gray-500 text-sm line-through ml-2">
                                    ${{ number_format($productItem->price, 2) }}
                                </p>
                            </div>
                            @else
                            <p class="text-pink-600 font-bold text-lg mb-1">
                                ${{ number_format($productItem->price, 2) }}
                            </p>
                            @endif
                            
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span><i class="fas fa-eye mr-1"></i>{{ $productItem->view_count ?? 0 }} views</span>
                                <span><i class="fas fa-box mr-1"></i>{{ $productItem->stock_quantity }} in stock</span>
                            </div>
                            
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-yellow-500 text-sm">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <button class="text-gray-500 hover:text-red-600" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            
                            <a href="{{ route('user.products.show', $productItem->id) }}"
                                class="mt-3 block text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-500">No products to display.</p>
    @endif
    <!-- Arrow button -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new Swiper(".mySwiper", {
            loop: false,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
                1280: {
                    slidesPerView: 5,
                }
            },
            spaceBetween: 50
        });

    });
</script>
