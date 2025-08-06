<x-app-layout>
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-6 sm:py-12">
        <!-- Page Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">🌸{{ $pageTitle ?? __('product.hanaya_shop_products') }}</h2>

        <!-- Category Navigation -->
        <x-category-navigation />

        <!-- Filter & Search -->
        <div class="mb-6 sm:mb-8">
            @php
                $currentSort = request()->get('sort');
                $keyword = request()->get('q');
                $selectedCategoryName = request()->get('category_name');
            @endphp

            <!-- Search Status Banner -->
            @if ($keyword || $selectedCategoryName)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="text-sm">
                            @if ($keyword && $selectedCategoryName)
                                {{ __('product.searching') }} for "<strong>{{ $keyword }}</strong>" 
                                {{ __('product.in category') }} "<strong>{{ ucfirst(str_replace('-', ' ', $selectedCategoryName)) }}</strong>"
                            @elseif($keyword)
                                {{ __('product.searching') }} for "<strong>{{ $keyword }}</strong>" 
                                {{ __('product.in all products') }}
                            @elseif($selectedCategoryName)
                                {{ __('product.showing_products_in_category') }}
                                "<strong>{{ ucfirst(str_replace('-', ' ', $selectedCategoryName)) }}</strong>"
                            @endif
                        </span>
                        <a href="{{ route('user.products.index') }}"
                            class="ml-auto text-blue-600 hover:text-blue-800 text-sm font-medium">
                            {{ __('product.clear_filters') }}
                        </a>
                    </div>
                </div>
            @endif

            <form method="GET" action="{{ route('user.products.index') }}" class="space-y-4">
                <!-- Search Row - Mobile Responsive -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="q" value="{{ $keyword }}"
                            placeholder="{{ $selectedCategoryName ? 'Search in ' . ucfirst(str_replace('-', ' ', $selectedCategoryName)) . ' category...' : __('product.searching_products') }}"
                            class="w-full px-3 py-2 text-sm sm:text-base rounded border focus:outline-none focus:ring focus:ring-pink-300">
                        @if ($selectedCategoryName)
                            <span
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-xs text-gray-500 bg-pink-100 px-2 py-1 rounded">
                                {{ ucfirst(str_replace('-', ' ', $selectedCategoryName)) }}
                            </span>
                        @endif
                    </div>

                    @if ($selectedCategoryName)
                        <input type="hidden" name="category_name" value="{{ $selectedCategoryName }}">
                    @endif

                    <button type="submit"
                        class="bg-pink-600 text-white px-4 sm:px-6 py-2 text-sm sm:text-base rounded hover:bg-pink-700 transition-colors whitespace-nowrap">
                        <i
                            class="fas fa-search mr-2"></i>{{ $selectedCategoryName ? 'Search in Category' : __('product.search_all') }}
                    </button>
                </div>

                <!-- Sort Options Row - Mobile Responsive -->
                <div class="space-y-3">
                    <span class="block text-sm sm:text-lg font-bold text-gray-700">{{ __('product.sort_by') }}:</span>
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'desc', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'desc' ? 'bg-pink-600 font-bold' : '' }}">
                            {{ __('product.price_high_low') }}
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'asc', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'asc' ? 'bg-pink-600 font-bold' : '' }}">
                            {{ __('product.price_low_high') }}
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'sale', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'sale' ? 'bg-pink-600 font-bold' : '' }}">
                            {{ __('product.on_sale') }}
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'views', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'views' ? 'bg-pink-600 font-bold' : '' }}">
                            {{ __('product.most_viewed') }}
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'bestseller', 'q' => $keyword, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'bestseller' ? 'bg-pink-600 font-bold' : '' }}">
                            {{ __('product.best_selling') }}
                        </a>
                        <a href="{{ route('user.products.index', array_merge(request()->except('sort'), ['sort' => 'latest', 'q' => $keyword, 'category' => $selectedCategory, 'category_name' => $selectedCategoryName])) }}"
                            class="bg-gray-700 text-white px-2 sm:px-4 py-2 text-xs sm:text-sm rounded hover:bg-pink-600 transition text-center {{ $currentSort === 'latest' || !$currentSort ? 'bg-pink-600 font-bold' : '' }}">
                            {{ __('product.latest') }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Active Filters Display -->
            @if ($selectedCategory || $selectedCategoryName || $keyword)
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-sm font-medium text-gray-600">{{ __('product.filtering_applied') }}</span>

                    @if ($keyword)
                        <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                            {{ __('product.keyword') }}: "{{ $keyword }}"
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
                            {{ __('product.category') }}: {{ $categoryName }}
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
                        {{ __('product.clear_all_filters') }}
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
                        class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 flex flex-col transform hover:scale-105 h-full">
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
                        <div class="p-3 sm:p-4 flex-1 flex flex-col">
                            <!-- Fixed height title area -->
                            <div class="h-12 mb-2">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-800 line-clamp-2">
                                    {{ $productItem->name }}</h3>
                            </div>

                            @if ($productItem->category)
                                <p class="text-xs text-pink-600 font-medium mb-1">
                                    {{ $productItem->category->name }}</p>
                            @endif

                            <!-- Fixed height description -->
                            <div class="h-10 mb-3">
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $productItem->descriptions }}</p>
                            </div>

                            <!-- Fixed height price area -->
                            <div class="h-12 mb-2">
                                @if ($productItem->discount_percent > 0)
                                    <div class="space-y-1">
                                        <div class="text-pink-600 font-bold text-lg">
                                            ${{ number_format($productItem->discounted_price, 2, '.', ',') }}
                                        </div>
                                        <div class="text-xs text-gray-500 line-through">
                                            ${{ number_format($productItem->price, 2, '.', ',') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-pink-600 font-bold text-lg">
                                        ${{ number_format($productItem->price, 2, '.', ',') }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1"></i>{{ $productItem->view_count ?? 0 }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-box mr-1"></i>{{ $productItem->stock_quantity }}
                                </span>
                            </div>

                            <div class="flex items-center text-yellow-400 text-sm mb-3">
                                @php
                                    $avgRating = round($productItem->reviews_avg_rating ?? 5, 1); // trung bình rating
                                @endphp

                                <div class="flex items-center text-yellow-400 text-sm mb-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star{{ $i <= floor($avgRating) ? '' : ' text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-gray-500 text-xs ml-1">
                                        ({{ $avgRating }})
                                        {{-- hoặc nếu có dùng withCount: --}}
                                        {{-- ({{ $avgRating }} / {{ $productItem->reviews_count }} đánh giá) --}}
                                    </span>
                                </div>

                            </div>

                            <!-- Button pushes to bottom -->
                            <div class="mt-auto">
                                <a href="{{ route('user.products.show', $productItem->id) }}"
                                    class="block text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 sm:py-3 rounded-lg transition-colors duration-300 text-sm">
                                    <i class="fas fa-eye mr-2"></i>{{ __('product.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full flex flex-col items-center justify-center py-12 sm:py-16">
                    <img src="{{ asset('fixed_resources/not_founded.jpg') }}" alt="No products found"
                        class="w-24 sm:w-32 h-24 sm:h-32 mb-4 opacity-70">
                    <p class="text-base sm:text-lg text-gray-500 font-semibold">{{ __('product.no_matching_products') }}</p>
                    <p class="text-sm text-gray-400 mt-2">{{ __('product.try_different_keywords') }}</p>
                </div>
            @endif
        </div>

        <!-- Phân trang - Mobile Responsive -->
        <div class="flex justify-center mt-8 sm:mt-12">
            <div class="w-full max-w-md sm:max-w-none">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

