<!-- Swiper Slider -->
<div class="swiper mySwiper relative px-8">
    <div class="swiper-wrapper">
        @foreach ($images as $image)
            <div class="swiper-slide">
                <img src="{{ asset('images/' . $image) }}" alt="Flower" class="slider-image">
            </div>
        @endforeach
    </div>
    <!-- Nút điều hướng -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev" ></div>
</div>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<style>
    .slider-image {
        width: 100%;
        max-width: 300px;
        object-fit: cover;
        border-radius: 10px;
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        height: 100%;
    }
    /* .swiper-button-prev {
        background-color: white;
        background-blend-mode: normal;
        outline: 5px white;
        outline-offset: 15px;
    }
    .swiper-button-next {
        background-color: white;
        background-blend-mode: normal;
        outline: 5px white;
        outline-offset: 15px;
    } */

    @media (max-width: 300px) {
        .slider-image {
            height: 200px; /*mobile*/
        }
    }
</style>

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
