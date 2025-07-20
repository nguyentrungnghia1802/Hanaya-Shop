<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Loading overlay đặt ngay sau <body> -->
    <div id="pageLoadingOverlay" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.8);backdrop-filter:blur(2px);align-items:center;justify-content:center;">
        <div style="font-size:2rem;color:#ec4899;display:flex;flex-direction:column;align-items:center;">
            <svg class="animate-spin h-14 w-14 mb-4 text-pink-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <div class="text-xl font-semibold text-pink-600 tracking-wide animate-pulse">Loading...</div>
        </div>
    </div>
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <footer class="bg-gray-800 text-gray-200 py-8 mt-10">
            <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">

                <div>
                    <h3 class="text-lg font-semibold mb-4">Hanaya Shop</h3>
                    <p class="text-sm">
                        Chuyên cung cấp hoa cao cấp, quà tặng ý nghĩa và phụ kiện trang trí.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên kết nhanh</h3>
                    <ul class="text-sm space-y-2">
                        <li><a href="/" class="hover:text-white">Trang chủ</a></li>
                        <li><a href="/products" class="hover:text-white">Sản phẩm</a></li>
                        <li><a href="/about" class="hover:text-white">Giới thiệu</a></li>
                        <li><a href="/contact" class="hover:text-white">Liên hệ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên hệ</h3>
                    <p class="text-sm"> Địa chỉ: Hà Nội</p>
                    <p class="text-sm"> Số điện thoại: </p>
                    <p class="text-sm"> Email: support@hanaya.vn</p>
                </div>
            </div>

            <div class="text-center text-sm text-gray-400 mt-8 border-t border-gray-700 pt-4">
                &copy; {{ date('Y') }} Hanaya.
            </div>
        </footer>

    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chặn click trên nav-link và delay chuyển trang
        document.querySelectorAll('nav a,button[type="submit"]').forEach(link => {
            link.addEventListener('click', function(e) {
                // Chỉ xử lý link nội bộ (không có target _blank, không phải anchor, không có modifier key)
                if (
                    this.target === '_blank' ||
                    this.href && this.href.startsWith('javascript:') ||
                    this.href === '#' ||
                    e.ctrlKey || e.shiftKey || e.metaKey || e.altKey
                ) return;
                document.getElementById('pageLoadingOverlay').style.display = 'flex';
            });
        });
        // Khi bấm nút Delete Account trong modal, tắt overlay loading ngay lập tức
        document.querySelectorAll('button').forEach(btn => {
            if (btn.textContent.trim() === 'Delete Account') {
                btn.addEventListener('click', function() {
                    document.getElementById('pageLoadingOverlay').style.display = 'none';
                });
            }
        });
    });
    </script>
</body>

</html>
