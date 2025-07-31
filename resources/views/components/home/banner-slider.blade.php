@props(['banners' => []])

<!-- Banner Slider -->
<div class="banner-swiper-container relative w-full h-64 md:h-96 lg:h-[500px] overflow-hidden rounded-lg shadow-lg">
    <div class="swiper bannerSwiper h-full">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
                <div class="swiper-slide relative">
                    <img src="{{ asset($banner['image']) }}" 
                         alt="{{ $banner['title'] }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white p-4">
                            <h2 class="text-2xl md:text-4xl lg:text-6xl font-bold mb-4">{{ $banner['title'] }}</h2>
                            <p class="text-lg md:text-xl mb-6 max-w-2xl">{{ $banner['subtitle'] }}</p>
                            @if(isset($banner['button_text']) && isset($banner['button_link']))
                                <a href="{{ $banner['button_link'] }}" 
                                   class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-300">
                                    {{ $banner['button_text'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Default banners if no banners provided -->
                <div class="swiper-slide relative">
                    <img src="{{ asset('images/banner1.jpg') }}" 
                         alt="Welcome to Hanaya Shop" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white p-4">
                            <h2 class="text-2xl md:text-4xl lg:text-6xl font-bold mb-4">Chào mừng đến với Hanaya Shop</h2>
                            <p class="text-lg md:text-xl mb-6 max-w-2xl">Nơi mang đến những sản phẩm hoa xà phòng tuyệt đẹp và các quà tặng ý nghĩa</p>
                            <a href="{{ route('user.products.index') }}" 
                               class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-300">
                                Khám phá ngay
                            </a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide relative">
                    <img src="{{ asset('images/banner2.jpg') }}" 
                         alt="Soap Flowers Collection" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white p-4">
                            <h2 class="text-2xl md:text-4xl lg:text-6xl font-bold mb-4">Bộ sưu tập Hoa Xà Phòng</h2>
                            <p class="text-lg md:text-xl mb-6 max-w-2xl">Những bông hoa vĩnh cửu với hương thơm dịu nhẹ</p>
                            <a href="{{ route('user.products.index') }}" 
                               class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-300">
                                Xem bộ sưu tập
                            </a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide relative">
                    <img src="{{ asset('images/banner3.jpg') }}" 
                         alt="Special Gifts" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white p-4">
                            <h2 class="text-2xl md:text-4xl lg:text-6xl font-bold mb-4">Quà tặng đặc biệt</h2>
                            <p class="text-lg md:text-xl mb-6 max-w-2xl">Những món quà ý nghĩa cho người thân yêu</p>
                            <a href="{{ route('user.products.index') }}" 
                               class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-300">
                                Tìm quà ngay
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination dots -->
        <div class="swiper-pagination"></div>
        
        <!-- Navigation arrows -->
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>
    </div>
</div>

<!-- Add Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<!-- Add Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Swiper JS initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Swiper to load then initialize
    if (typeof Swiper !== 'undefined' && typeof initBannerSwiper === 'function') {
        initBannerSwiper();
    } else {
        // If Swiper or function is not loaded yet, wait for it
        const checkSwiper = setInterval(() => {
            if (typeof Swiper !== 'undefined' && typeof initBannerSwiper === 'function') {
                clearInterval(checkSwiper);
                initBannerSwiper();
            }
        }, 100);
        
        // Fallback: if initBannerSwiper not available, create a basic swiper
        setTimeout(() => {
            if (typeof Swiper !== 'undefined' && typeof initBannerSwiper !== 'function') {
                clearInterval(checkSwiper);
                const bannerSwiper = new Swiper('.bannerSwiper', {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                });
            }
        }, 1000);
    }
});
</script>

<style>
.banner-swiper-container .swiper-button-next,
.banner-swiper-container .swiper-button-prev {
    color: rgba(255, 255, 255, 0.8) !important;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 50%;
    width: 40px !important;
    height: 40px !important;
    margin-top: -20px !important;
}

.banner-swiper-container .swiper-button-next:hover,
.banner-swiper-container .swiper-button-prev:hover {
    color: rgba(255, 255, 255, 1) !important;
    background: rgba(0, 0, 0, 0.6);
}

.banner-swiper-container .swiper-button-next:after,
.banner-swiper-container .swiper-button-prev:after {
    font-size: 18px !important;
    font-weight: bold;
}

.banner-swiper-container .swiper-pagination-bullet {
    background: rgba(255, 255, 255, 0.6);
    opacity: 1;
}

.banner-swiper-container .swiper-pagination-bullet-active {
    background: rgba(255, 255, 255, 1);
}

/* Mobile responsiveness for navigation */
@media (max-width: 768px) {
    .banner-swiper-container .swiper-button-next,
    .banner-swiper-container .swiper-button-prev {
        width: 35px !important;
        height: 35px !important;
        margin-top: -17px !important;
    }
    
    .banner-swiper-container .swiper-button-next:after,
    .banner-swiper-container .swiper-button-prev:after {
        font-size: 16px !important;
    }
}
</style>