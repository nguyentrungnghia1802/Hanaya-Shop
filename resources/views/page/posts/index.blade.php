<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">{{ __('Bài viết về hoa & sản phẩm') }}</h2>
        
        <!-- Form tìm kiếm -->
        <div class="mb-8 flex justify-center">
            <form method="GET" action="{{ route('posts.index') }}" class="flex gap-2 w-full max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm bài viết..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Tìm kiếm</button>
                @if(request('search'))
                    <a href="{{ route('posts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Xóa</a>
                @endif
            </form>
        </div>
        
        @if(request('search'))
            <div class="mb-6 text-center text-gray-600">
                Kết quả tìm kiếm cho: "<strong>{{ request('search') }}</strong>"
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($posts as $post)
                <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                    <a href="{{ route('posts.show', $post->slug) }}">
                        @if($post->image)
                            <img src="{{ asset('images/posts/' . $post->image) }}" alt="{{ $post->title }}" class="h-48 w-full object-cover rounded mb-4">
                        @endif
                        <h3 class="text-lg font-bold mb-2 text-pink-700">{{ $post->title }}</h3>
                    </a>
                    <div class="text-sm text-gray-600 mb-2">{{ $post->created_at->format('d/m/Y') }} bởi {{ $post->author->name ?? 'Admin' }}</div>
                    <div class="text-gray-700 line-clamp-3">{{ Str::limit(strip_tags($post->content), 120) }}</div>
                    <a href="{{ route('posts.show', $post->slug) }}" class="mt-4 text-pink-600 hover:underline">Đọc tiếp</a>
                </div>
            @empty
                <div class="col-span-2 text-center text-gray-500">Chưa có bài viết nào.</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $posts->appends(request()->query())->links() }}</div>
    </div>
</x-app-layout>
