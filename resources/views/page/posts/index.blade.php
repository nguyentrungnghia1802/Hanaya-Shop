<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-2 sm:px-4 sm:py-12">
        <!-- Page Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">✒Posts</h2>

            <!-- Search Form -->
            <div class="mb-8 flex justify-center">
                <form method="GET" action="{{ route('posts.index') }}" class="flex gap-2 w-full max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                        class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <button type="submit"
                        class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Search</button>
                    @if (request('search'))
                        <a href="{{ route('posts.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Clear</a>
                    @endif
                </form>
            </div>

            @if (request('search'))
                <div class="mb-6 text-center text-gray-600">
                    Search results for: "<strong>{{ request('search') }}</strong>"
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($posts as $post)
                    <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                        <a href="{{ route('posts.show', $post->slug) }}">
                            @if ($post->image)
                                <img src="{{ asset('images/posts/' . $post->image) }}" alt="{{ $post->title }}"
                                    class="h-48 w-full object-cover rounded mb-4">
                            @endif
                            <h3 class="text-lg font-bold mb-2 text-pink-700">{{ $post->title }}</h3>
                        </a>
                        <div class="text-sm text-gray-600 mb-2">{{ $post->created_at->format('d/m/Y') }} by
                            {{ $post->author->name ?? 'Admin' }}</div>
                        <div class="text-gray-700 line-clamp-3">
                            {{ Str::limit(html_entity_decode(strip_tags($post->content)), 120) }}</div>
                        <a href="{{ route('posts.show', $post->slug) }}"
                            class="mt-4 text-pink-600 hover:underline">Read more</a>
                    </div>
                @empty
                    <div class="col-span-2 text-center text-gray-500">No posts available.</div>
                @endforelse
            </div>
            <div class="mt-8">{{ $posts->appends(request()->query())->links() }}</div>
    </div>
</x-app-layout>
