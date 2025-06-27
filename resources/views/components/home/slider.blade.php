<!-- Swiper Slider -->
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($images as $image)
            <div class="swiper-slide">
                <img src="{{ asset('images/' . $image) }}" alt="Flower" class="slider-image">
            </div>
        @endforeach
    </div>
    <!-- Nút điều hướng -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<style>
    .slider-image {
        width: 100%;
        max-width: 400px;
        object-fit: cover;
        /* Giữ tỉ lệ, cắt ảnh nếu cần */
        border-radius: 10px;
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        /* Căn ngang */
        align-items: flex-end;
        /* Đặt ảnh ở đáy */
        height: 100%;
        /* Đảm bảo slide chiếm toàn bộ chiều cao */
    }

    @media (max-width: 400px) {
        .slider-image {
            height: 300px;
            /* Chiều cao nhỏ hơn trên điện thoại */
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new Swiper(".mySwiper", {
            loop: true,
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
            spaceBetween:50
        });

    });
</script>
