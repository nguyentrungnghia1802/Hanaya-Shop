@props(['posts'])

<div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-lg p-6 border border-blue-200">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            {{ __('home.latest_news') }}
        </h3>
        <a href="{{ route('posts.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center transition-colors duration-300">
            {{ __('home.view_all_posts') }}
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    
    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 latest-posts-grid">
            @foreach($posts as $post)
                <a href="{{ route('posts.show', $post->id) }}" class="group cursor-pointer bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden focus:outline-none focus:ring-2 focus:ring-blue-400 flex flex-col h-full">
                    <!-- Fixed Image Area -->
                    <div class="relative overflow-hidden rounded-t-xl">
                        <div class="aspect-w-16 aspect-h-9 h-48">
                            <img src="{{ $post->image ? asset('images/posts/' . $post->image) : asset('fixed_resources/no_image.png') }}" 
                                alt="{{ $post->title }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="absolute top-3 left-3">
                            <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full shadow-lg font-medium">
                                {{ $post->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Fixed Content Area -->
                    <div class="p-4 flex flex-col flex-1">
                        <!-- Fixed Title Area - Exactly 2 lines -->
                        <div class="h-12 mb-3">
                            <h4 class="font-semibold text-lg text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-6">
                                {{ $post->title }}
                            </h4>
                        </div>
                        
                        <!-- Fixed Description Area - Exactly 3 lines -->
                        <div class="h-16 mb-3">
                            <p class="text-gray-600 text-sm line-clamp-3 leading-5">
                                {{ Str::limit(html_entity_decode(strip_tags($post->content)), 120) }}
                            </p>
                        </div>
                        
                        <!-- Fixed Meta Info Area -->
                        <div class="h-8 mb-3">
                            <div class="flex items-center text-xs text-gray-500 space-x-4">
                                <span class="flex items-center truncate">
                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">{{ $post->author->name ?? 'Admin' }}</span>
                                </span>
                                <span class="flex items-center truncate">
                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $post->created_at->diffForHumans() }}</span>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Button Area - Always at bottom -->
                        <div class="mt-auto pt-2">
                            <span class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-300">
                                {{ __('posts.read_more') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <p class="text-gray-500 text-lg">{{ __('home.no_posts_available') }}</p>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Ensure consistent line height for better text alignment */
.line-clamp-2, .line-clamp-3 {
    word-wrap: break-word;
    hyphens: auto;
}

/* Add hover effect for cards */
.group:hover .line-clamp-2,
.group:hover .line-clamp-3 {
    color: inherit;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .latest-posts-grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .latest-posts-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1025px) {
    .latest-posts-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
