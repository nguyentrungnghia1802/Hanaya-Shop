<x-app-layout>
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-6 sm:py-12">
        <!-- Page Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">{{ $pageTitle ?? 'Hanaya Shop Products' }}</h2>

        <!-- Category Navigation -->
        <x-category-navigation />

        <!-- Filter & Search -->
        <div class="mb-6 sm:mb-8">
            @php
                $currentSort = request()->get('sort');
                $keyword = request()->get('q');
                $selectedCategoryName = request()->get('category_name');
            @endphp

            <form method="GET" action="{{ route('user.products.index') }}" class="space-y-4">
                <!-- Search Row - Mobile Responsive -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <input type="text" name="q" value="{{ $keyword }}" placeholder="Search products..."
                        class="px-3 py-2 text-sm sm:text-base rounded border focus:outline-none focus:ring focus:ring-pink-300 flex-1 min-w-0">

                    @if ($selectedCategoryName)
                        <input type="hidden" name="category_name" value="{{ $selectedCategoryName }}">
                    @endif

                    <button type="submit"
                        class="bg-pink-600 text-white px-4 sm:px-6 py-2 text-sm sm:text-base rounded hover:bg-pink-700 transition-colors whitespace-nowrap">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>

                <!-- Sort Options Row - Mobile Responsive -->
                <div class="space-y-3">
                    <span class="block text-sm sm:text-lg font-bold text-gray-700">Sort by:</span>
                    <input type="hidden" name="q" value="{{ $keyword }}">
                    @if ($selectedCategoryName)
                        <input type="hidden" name="category_name" value="{{ $selectedCategoryName }}">
                    @endif

                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'desc', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'desc' ? 'bg-pink-600 font-bold' : '' }}">
                            Price High - Low
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'asc', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'asc' ? 'bg-pink-600 font-bold' : '' }}">
                            Price Low - High
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'sale', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'sale' ? 'bg-pink-600 font-bold' : '' }}">
                            On Sale
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'views', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'views' ? 'bg-pink-600 font-bold' : '' }}">
                            Most Viewed
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'bestseller', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'bestseller' ? 'bg-pink-600 font-bold' : '' }}">
                            Best Selling
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'latest', 'q' => $keyword, 'category' => $selectedCategory, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'latest' || !$currentSort ? 'bg-pink-600 font-bold' : '' }}">
                            Latest
                        </a>
                    </div>
                </div>
            </form>

            <!-- Active Filters Display -->
            @if ($selectedCategory || $selectedCategoryName || $keyword)
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-sm font-medium text-gray-600">Bộ lọc đang áp dụng:</span>

                    @if ($keyword)
                        <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                            Từ khóa: "{{ $keyword }}"
                            <a href="{{ route('user.products.index', array_merge(request()->except('q'), ['category' => $selectedCategory, 'category_name' => $selectedCategoryName])) }}"
                                class="ml-2 text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif

                    @if ($selectedCategory)
                        @php $categoryName = $categories->where('id', $selectedCategory)->first()->name ?? 'Unknown' @endphp
                        <span
                            class="inline-flex items-center bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                            Category: {{ $categoryName }}
                            <a href="{{ route('user.products.index', array_merge(request()->except('category'), ['q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                                class="ml-2 text-green-600 hover:text-green-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif

                    @if ($selectedCategoryName)
                        <span
                            class="inline-flex items-center bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded-full">
                            Type: {{ $pageTitle }}
                            <a href="{{ route('user.products.index', array_merge(request()->except('category_name'), ['q' => $keyword, 'category' => $selectedCategory])) }}"
                                class="ml-2 text-purple-600 hover:text-purple-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif

                    <a href="{{ route('user.products.index') }}"
                        class="text-sm text-red-600 hover:text-red-800 underline">
                        Xóa tất cả bộ lọc
                    </a>
                </div>
            @endif
        </div>

        <!-- Product Grid - Mobile Responsive -->
        <div
            class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 lg:gap-6">
            @if ($products->count() > 0)
                @foreach ($products as $productItem)
                    <div
                        class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 flex flex-col transform hover:scale-105">
                        <div class="relative">
                            <div class="aspect-[3/4] w-full bg-gray-100 overflow-hidden">
                                <img src="{{ $productItem->image_url ? asset('images/products/' . $productItem->image_url) : asset('images/no-image.png') }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                                    alt="{{ $productItem->name }}" loading="lazy">
                            </div>
                            @if ($productItem->discount_percent > 0)
                                <span
                                    class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    -{{ $productItem->discount_percent }}%
                                </span>
                            @endif
                            <button
                                class="absolute top-2 right-2 bg-white bg-opacity-80 text-gray-600 hover:text-red-500 p-2 rounded-full transition-colors">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                        <div class="p-3 sm:p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-gray-800 line-clamp-2 mb-2">
                                    {{ $productItem->name }}</h3>
                                @if ($productItem->category)
                                    <p class="text-xs text-pink-600 font-medium mb-1">
                                        {{ $productItem->category->name }}</p>
                                @endif
                                <p class="text-xs text-gray-600 line-clamp-2 mb-3">{{ $productItem->descriptions }}</p>
                            </div>

                            <div class="space-y-2">
                                @if ($productItem->discount_percent > 0)
                                    <div class="space-y-1">
                                        <div class="text-pink-600 font-bold text-lg">
                                            {{ number_format($productItem->discounted_price, 0, ',', '.') }}₫
                                        </div>
                                        <div class="text-xs text-gray-500 line-through">
                                            {{ number_format($productItem->price, 0, ',', '.') }}₫
                                        </div>
                                    </div>
                                @else
                                    <div class="text-pink-600 font-bold text-lg">
                                        {{ number_format($productItem->price, 0, ',', '.') }}₫
                                    </div>
                                @endif

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>{{ $productItem->view_count ?? 0 }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-box mr-1"></i>{{ $productItem->stock_quantity }}
                                    </span>
                                </div>

                                <div class="flex items-center text-yellow-400 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= 4 ? '' : ' text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-gray-500 text-xs ml-1">(4.0)</span>
                                </div>

                                <a href="{{ route('user.products.show', $productItem->id) }}"
                                    class="block text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 sm:py-3 rounded-lg transition-colors duration-300 text-sm">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full flex flex-col items-center justify-center py-12 sm:py-16">
                    <img src="{{ asset('fixed_resources/not_founded.jpg') }}" alt="No products found"
                        class="w-24 sm:w-32 h-24 sm:h-32 mb-4 opacity-70">
                    <p class="text-base sm:text-lg text-gray-500 font-semibold">No matching products found.</p>
                    <p class="text-sm text-gray-400 mt-2">Try searching with different keywords</p>
                </div>
            @endif
        </div>

        <!-- Phân trang - Mobile Responsive -->
        <div class="flex justify-center mt-8 sm:mt-12">
            <div class="w-full max-w-md sm:max-w-none">
                {{ $products->links() }}
            </div>
        </div>

        <!-- Nút Liên hệ & Lên đầu trang - Mobile Optimized -->
        <div class="fixed bottom-4 right-4 flex flex-col gap-3 z-50">
            <a href="tel:0123456789"
                class="bg-pink-600 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-full shadow-lg hover:bg-pink-700 transition-all duration-300 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-headset"></i>
                <span class="hidden sm:inline">Liên hệ</span>
            </a>
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="bg-gray-700 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-full shadow-lg hover:bg-gray-600 transition-all duration-300">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </div>
</x-app-layout>
