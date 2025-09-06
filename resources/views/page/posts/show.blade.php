<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">{{ $post->title }}</h2>
        <div class="bg-white rounded-lg shadow p-6">
            @if($post->image)
                <img src="{{ asset('images/posts/' . $post->image) }}" alt="{{ $post->title }}" class="h-64 w-full object-cover rounded mb-6">
            @endif
            <div class="text-sm text-gray-600 mb-2">{{ $post->created_at->format('d/m/Y') }} {{ __('posts.by') }} {{ $post->author->name ?? 'Admin' }}</div>
            <div class="prose max-w-none text-gray-800" style="font-size:inherit;">
                {!! $post->content !!}
            </div>
        </div>
        <div class="mt-8">
            <a href="{{ route('posts.index') }}" class="text-pink-600 hover:underline">← {{ __('posts.back_to_posts') }}</a>
        </div>
    </div>
</x-app-layout>
