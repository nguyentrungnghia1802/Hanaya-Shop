@props(['selectedCategory' => null])

@php
    $currentCategoryName = request()->get('category_name');
@endphp

<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-3 text-center">Select Category</h3>
    
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <!-- All Products -->
        <a href="{{ route('user.products.index') }}" 
           class="category-item group relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:shadow-md {{ !$currentCategoryName ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
            <div class="p-3 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center {{ !$currentCategoryName ? 'bg-pink-500' : 'bg-gray-100 group-hover:bg-pink-100' }}">
                    <svg class="w-6 h-6 {{ !$currentCategoryName ? 'text-white' : 'text-gray-600 group-hover:text-pink-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H9m4 8H7m6 4v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2z"></path>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold {{ !$currentCategoryName ? 'text-pink-700' : 'text-gray-700 group-hover:text-pink-600' }}">
                    All Products
                </h4>
            </div>
        </a>

        <!-- Soap Flower -->
        <a href="{{ route('user.products.index', ['category_name' => 'soap-flower']) }}" 
           class="category-item group relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:shadow-md {{ $currentCategoryName === 'soap-flower' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
            <div class="p-3 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center {{ $currentCategoryName === 'soap-flower' ? 'bg-pink-500' : 'bg-gray-100 group-hover:bg-pink-100' }}">
                    <svg class="w-6 h-6 {{ $currentCategoryName === 'soap-flower' ? 'text-white' : 'text-gray-600 group-hover:text-pink-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold {{ $currentCategoryName === 'soap-flower' ? 'text-pink-700' : 'text-gray-700 group-hover:text-pink-600' }}">
                    Soap Flowers
                </h4>
            </div>
        </a>

        <!-- Special Flower -->
        <a href="{{ route('user.products.index', ['category_name' => 'special-flower']) }}" 
           class="category-item group relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:shadow-md {{ $currentCategoryName === 'special-flower' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
            <div class="p-3 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center {{ $currentCategoryName === 'special-flower' ? 'bg-pink-500' : 'bg-gray-100 group-hover:bg-pink-100' }}">
                    <svg class="w-6 h-6 {{ $currentCategoryName === 'special-flower' ? 'text-white' : 'text-gray-600 group-hover:text-pink-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold {{ $currentCategoryName === 'special-flower' ? 'text-pink-700' : 'text-gray-700 group-hover:text-pink-600' }}">
                    Special Flowers
                </h4>
            </div>
        </a>

        <!-- Fresh Flowers -->
        <a href="{{ route('user.products.index', ['category_name' => 'fresh-flowers']) }}" 
           class="category-item group relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:shadow-md {{ $currentCategoryName === 'fresh-flowers' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
            <div class="p-3 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center {{ $currentCategoryName === 'fresh-flowers' ? 'bg-pink-500' : 'bg-gray-100 group-hover:bg-pink-100' }}">
                    <svg class="w-6 h-6 {{ $currentCategoryName === 'fresh-flowers' ? 'text-white' : 'text-gray-600 group-hover:text-pink-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold {{ $currentCategoryName === 'fresh-flowers' ? 'text-pink-700' : 'text-gray-700 group-hover:text-pink-600' }}">
                    Fresh Flowers
                </h4>
            </div>
        </a>

        <!-- Souvenir -->
        <a href="{{ route('user.products.index', ['category_name' => 'souvenir']) }}" 
           class="category-item group relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:shadow-md {{ $currentCategoryName === 'souvenir' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
            <div class="p-3 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center {{ $currentCategoryName === 'souvenir' ? 'bg-pink-500' : 'bg-gray-100 group-hover:bg-pink-100' }}">
                    <svg class="w-6 h-6 {{ $currentCategoryName === 'souvenir' ? 'text-white' : 'text-gray-600 group-hover:text-pink-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold {{ $currentCategoryName === 'souvenir' ? 'text-pink-700' : 'text-gray-700 group-hover:text-pink-600' }}">
                    Souvenirs
                </h4>
            </div>
        </a>
    </div>
</div>

