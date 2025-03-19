<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hanaya Shop - Hoa tươi cho mọi khoảnh khắc</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Header component -->
        <x-home.header />

        <!-- Nội dung chính: Slot mặc định chứa nội dung view con -->
        <main class="max-w-7xl mx-auto p-6">
            {{ $slot }}
        </main>

        <!-- Footer component -->
        <x-home.footer />
    </div>
</body>
</html>
