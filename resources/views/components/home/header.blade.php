<header class="bg-pink-500 text-white py-4 shadow">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">
            <a href="{{ route('dashboard') }}">🌸 Hanaya Shop</a>
        </h1>
        <nav>
            <a href="{{ route('dashboard') }}" class="px-3 hover:underline">Trang chủ</a>
            <a href="#" class="px-3 hover:underline">Danh mục</a>
            <a href="#" class="px-3 hover:underline">Liên hệ</a>
        </nav>
    </div>
    {{-- Nếu cần, bạn có thể mở rộng thêm phần header cho các chức năng đăng nhập, logout, ... --}}
    @isset($header)
        <div class="max-w-7xl mx-auto py-2 px-4">
            {{ $header }}
        </div>
    @endisset
</header>
