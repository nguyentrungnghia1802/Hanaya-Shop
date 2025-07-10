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

<body class="font-sans antialiased bg-gray-100">
    <!-- Loading overlay đặt ngay sau <body> -->
    <div id="pageLoadingOverlay" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);align-items:center;justify-content:center;">
        <div style="font-size:2rem;color:#2563eb;">
            <svg class="animate-spin h-10 w-10 mr-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            Loading...
        </div>
    </div>
    <div class="min-h-screen">
        @include('layouts.admin-nav')
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Load TinyMCE từ CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
        .create(document.querySelector('.description'), {
          toolbar: [
            'undo', 'redo',
            '|', 'bold', 'italic', 'underline',
            '|', 'bulletedList', 'numberedList',
            '|', 'alignment',
            '|', 'link',
            '|', 'removeFormat'
          ]
        }).then(editor => {
          editorInstance = editor;
        })
        .catch(error => {
          console.error(error);
        });

        // xử lí khi chọn ảnh
        document.getElementById('imageInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('previewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chặn click trên nav-link và delay chuyển trang
        document.querySelectorAll('nav a, .admin-nav a, .sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Chỉ xử lý link nội bộ (không có target _blank, không phải anchor, không có modifier key)
                if (
                    this.target === '_blank' ||
                    this.href.startsWith('javascript:') ||
                    this.href === '#' ||
                    e.ctrlKey || e.shiftKey || e.metaKey || e.altKey
                ) return;
                e.preventDefault();
                document.getElementById('pageLoadingOverlay').style.display = 'flex';
                setTimeout(() => {
                    window.location.href = this.href;
                }, 150);
            });
        });
    });
    </script>
</body>
</html>