@props(['categories'])

<div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H9m4 8H7m6 4v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2z"></path>
            </svg>
                        Product Categories
        </h3>
        <a href="{{ route('user.products.index') }}" class="text-white hover:text-pink-200 font-medium flex items-center">
            View All 
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    
    @if($categories->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('user.products.index', ['category' => $category->id]) }}" 
                   class="group block">
                    <div class="bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm rounded-lg p-6 transition-all duration-300 hover:transform hover:scale-105">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                                <img src="{{ asset('images/categories/' . ($category->image_path ?? 'base.jpg')) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-12 h-12 object-cover rounded-full">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg mb-1 group-hover:text-pink-200 transition-colors">
                                    {{ $category->name }}
                                </h4>
                                <p class="text-sm text-white text-opacity-80 line-clamp-2">
                                    {{ strip_tags($category->description ?? 'Discover our collection of ' . strtolower($category->name)) }}
                                </p>
                                <div class="flex items-center mt-2 text-xs text-white text-opacity-70">
                                    <span>{{ $category->product_count ?? $category->product->count() }} products</span>
                                </div>
                            </div>
                            <div class="text-white group-hover:text-pink-200 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-white text-opacity-60 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H9m4 8H7m6 4v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002-2z"></path>
            </svg>
            <p class="text-white text-opacity-80 text-lg">No product categories available</p>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
