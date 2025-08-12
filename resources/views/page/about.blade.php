<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <h1 class="text-3xl font-bold">🌸 {{ __('about.about_hanaya') }}</h1>
                    <p class="text-pink-100 mt-2">{{ __('about.discover_story') }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ __('about.welcome_to_hanaya') }}</h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    {{ __('about.hero_description') }}
                </p>
            </div>

            <!-- Our Story Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
                <div class="space-y-6">
                    <h3 class="text-3xl font-bold text-gray-900">{{ __('about.our_story') }}</h3>
                    <div class="space-y-4 text-gray-600 leading-relaxed">
                        <p>{{ __('about.story_paragraph_1') }}</p>
                        <p>{{ __('about.story_paragraph_2') }}</p>
                        <p>{{ __('about.story_paragraph_3') }}</p>
                    </div>
                </div>
                <div class="lg:pl-8">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <img src="{{ asset('fixed_resources/about_story.jpg') }}" 
                             alt="Hanaya Shop Story" 
                             class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('about.our_beginning') }}</h4>
                            <p class="text-gray-600 text-sm">
                                {{ __('about.our_beginning_description') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Mission & Values -->
            <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('about.mission_values') }}</h3>
                    <p class="text-gray-600 text-lg">{{ __('about.principles_guide') }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Quality -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">{{ __('about.quality_excellence') }}</h4>
                        <p class="text-gray-600">
                            {{ __('about.quality_excellence_desc') }}
                        </p>
                    </div>

                    <!-- Customer Care -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">{{ __('about.customer_first') }}</h4>
                        <p class="text-gray-600">
                            {{ __('about.customer_first_description') }}
                        </p>
                    </div>

                    <!-- Sustainability -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c-1.657 0-3-4.03-3-9s1.343-9 3-9m0 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">{{ __('about.sustainability') }}</h4>
                        <p class="text-gray-600">
                            {{ __('about.sustainability_description') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Our Products -->
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('about.what_we_offer') }}</h3>
                    <p class="text-gray-600 text-lg">{{ __('about.discover_collection') }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">
                    <!-- Soap Flowers -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/soap_flower.jpg') }}" 
                                 alt="Soap Flowers" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ __('about.soap_flowers') }}</h4>
                            </div>
                            <p class="text-gray-600">
                                {{ __('about.soap_flowers_description') }}
                            </p>
                        </div>
                    </div>

                    <!-- Fresh Flowers -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/fresh_flower.jpg') }}" 
                                 alt="Fresh Flowers" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ __('about.fresh_flowers') }}</h4>
                            </div>
                            <p class="text-gray-600">
                                {{ __('about.fresh_flowers_description') }}
                            </p>
                        </div>
                    </div>

                    <!-- Special Flowers -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/special_flower.jpg') }}" 
                                 alt="Special Flowers" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ __('about.special_flowers') }}</h4>
                            </div>
                            <p class="text-gray-600">
                                {{ __('about.special_flowers_description') }}
                            </p>
                        </div>
                    </div>

                    <!-- Souvenirs -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/souvenir.jpg') }}" 
                                 alt="Souvenirs" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ __('about.souvenirs') }}</h4>
                            </div>
                            <p class="text-gray-600">
                                {{ __('about.souvenirs_description') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meet Our Team -->
            <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('about.meet_our_team') }}</h3>
                    <p class="text-gray-600 text-lg">{{ __('about.team_description') }}</p>
                </div>

                <!-- Team Photo -->
                <div class="text-center mb-12">
                    <div class="inline-block rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('fixed_resources/team_group.jpg') }}" 
                             alt="Hanaya Shop Team" 
                             class="w-full max-w-2xl h-64 object-cover">
                    </div>
                    <p class="text-gray-600 mt-4 italic">{{ __('about.team_group_description') }}</p>
                </div>

                <!-- Individual Team Members -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Team Member 1 -->
                    <div class="text-center">
                        <div class="relative mb-6">
                            <img src="{{ asset('fixed_resources/author_1.jpg') }}" 
                                 alt="Team Member 1" 
                                 class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg">
                            <div class="absolute inset-0 w-32 h-32 rounded-full mx-auto bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ __('about.alex_johnson') }}</h4>
                        <p class="text-pink-600 font-medium mb-3">{{ __('about.founder_creative_director') }}</p>
                        <p class="text-gray-600 text-sm">
                            {{ __('about.alex_description') }}
                        </p>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="text-center">
                        <div class="relative mb-6">
                            <img src="{{ asset('fixed_resources/about/nghia.jpg') }}" 
                                 alt="Team Member 2" 
                                 class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg">
                            <div class="absolute inset-0 w-32 h-32 rounded-full mx-auto bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ __('about.nguyen_trung_nghia') }}</h4>
                        <p class="text-pink-600 font-medium mb-3">{{ __('about.head_operations') }}</p>
                        <p class="text-gray-600 text-sm">
                            {{ __('about.nghia_description') }}
                        </p>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="text-center">
                        <div class="relative mb-6">
                            <img src="{{ asset('fixed_resources/author_3.jpg') }}" 
                                 alt="Team Member 3" 
                                 class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg">
                            <div class="absolute inset-0 w-32 h-32 rounded-full mx-auto bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ __('about.michael_rivera') }}</h4>
                        <p class="text-pink-600 font-medium mb-3">{{ __('about.lead_artisan') }}</p>
                        <p class="text-gray-600 text-sm">
                            {{ __('about.michael_description') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact & Location -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('about.get_in_touch') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.address') }}</p>
                                <p class="text-gray-600">{{ config('constants.shop_address') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.phone') }}</p>
                                <p class="text-gray-600">{{ config('constants.shop_phone') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.email') }}</p>
                                <p class="text-gray-600">{{ config('constants.shop_email') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.opening_hours') }}</p>
                                <p class="text-gray-600">{{ __('about.opening_hours_time') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Why Choose Us -->
                <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('about.why_choose_hanaya_detailed') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.premium_quality') }}</p>
                                <p class="text-gray-600 text-sm">{{ __('about.premium_quality_desc') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.fast_delivery') }}</p>
                                <p class="text-gray-600 text-sm">{{ __('about.fast_delivery_desc') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.excellent_support') }}</p>
                                <p class="text-gray-600 text-sm">{{ __('about.excellent_support_desc') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('about.custom_orders') }}</p>
                                <p class="text-gray-600 text-sm">{{ __('about.custom_orders_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('user.products.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            {{ __('about.start_shopping_now') }}
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
