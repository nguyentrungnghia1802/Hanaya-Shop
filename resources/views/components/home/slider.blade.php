@props(['products'])

<!-- Swiper Slider -->
<div class="swiper mySwiper relative px-8">
    @if ($products->isNotEmpty())
        <div class="swiper-wrapper">
            @foreach ($products as $productItem)
                <div class="swiper-slide">
                    <div class="bg-white rounded shadow hover:shadow-xl overflow-hidden transition">
                        <div class="relative">
                            <img src="{{ $productItem->image_url }}" class="w-full h-48 object-cover"
                                alt="{{ $productItem->name }}">
                            <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                Giảm xx%
                            </span>
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold">{{ $productItem->name }}</h3>
                            <p class="text-pink-600 font-bold text-lg mt-1">
                                {{ number_format($productItem->price, 0, ',', '.') }}₫
                            </p>
                            <p class="text-xs text-gray-600">Khuyến mãi thêm đến <span
                                    class="text-red-500 font-semibold">500.000₫</span></p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-yellow-500 text-sm">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <button class="text-gray-500 hover:text-red-600">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            {{-- <a href="{{ route('soapFlower.show', $productItem->id) }}"
                                class="mt-4 block text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded">
                                Xem chi tiết
                            </a> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-500">Không có sản phẩm nào để hiển thị.</p>
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
