<!-- Swiper Slider -->
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach (['hoa_1.jpg', 'hoa_2.jpg', 'hoa_3.jpg'] as $image)
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
        max-width: 700px;
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

    @media (max-width: 768px) {
        .slider-image {
            height: 250px;
            /* Chiều cao nhỏ hơn trên điện thoại */
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        });
    });
</script>