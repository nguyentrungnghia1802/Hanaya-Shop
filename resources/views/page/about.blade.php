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
                            {{ __('about.quality_description') }}
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
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:scale-105"
                         onclick="redirectToProducts('soap-flowers')">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/soap_flower.jpg') }}" 
                                 alt="Soap Flowers" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:scale-105"
                         onclick="redirectToProducts('fresh-flowers')">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/fresh_flower.jpg') }}" 
                                 alt="Fresh Flowers" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:scale-105"
                         onclick="redirectToProducts('special-flowers')">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/special_flower.jpg') }}" 
                                 alt="Special Flowers" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:scale-105"
                         onclick="redirectToProducts('souvenirs')">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('fixed_resources/about/souvenir.jpg') }}" 
                                 alt="Souvenirs" 
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
            <div class="bg-white rounded-2xl shadow-xl p-0 lg:p-0 mb-20 overflow-hidden">
                <!-- Team Header with Large Background -->
                <div class="relative w-full h-[220px] sm:h-[400px] md:h-[560px] flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('fixed_resources/about/team.png') }}" alt="Hanaya Shop Team" class="absolute inset-0 w-full h-full object-cover object-center opacity-70">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
                    <div class="relative z-10 px-4 sm:px-8 flex flex-col items-center justify-end h-full mb-8">
                        <h3 class="text-1xl sm:text-2xl md:text-4xl font-bold text-white mb-2 sm:mb-4 drop-shadow-lg" style="text-shadow: 0 2px 8px #000, 0 0 2px #fff;">{{ __('about.meet_our_team') }}</h3>
                        <p class="text-sm sm:text-lg text-pink-100 mb-2 sm:mb-4 drop-shadow-lg" style="text-shadow: 0 2px 8px #000;">{{ __('about.team_description') }}</p>
                        <p class="text-sm sm:text-base text-pink-100 italic drop-shadow-lg" style="text-shadow: 0 2px 8px #000;">{{ __('about.team_group_description') }}</p>
                    </div>
                </div>
                <!-- Individual Team Members -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-8 lg:px-12 py-12">
                    <!-- Team Member 1 -->
                    <div class="text-center bg-white rounded-2xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 cursor-pointer team-member-card" 
                         onclick="window.open('https://github.com/Nezuko1909', '_blank')">
                        <div class="relative mb-6">
                            <img src="{{ asset('fixed_resources/about/quyen.jpg') }}"
                                 alt="Team Member 1"
                                 class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg transition-transform duration-300">
                            <div class="absolute inset-0 w-32 h-32 rounded-full mx-auto bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ __('about.tan_van_quyen') }}</h4>
                        <p class="text-pink-600 font-medium mb-3">{{ __('about.team_member') }}</p>
                        <p class="text-gray-600 text-sm mb-3">
                            {{ __('about.quyen_description') }}
                        </p>
                        <div class="flex items-center justify-center space-x-2 text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="text-xs">{{ __('about.click_to_view_github') }}</span>
                        </div>
                    </div>
                    <!-- Team Member 2 -->
                    <div class="text-center bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-105 transform transition-all duration-300 cursor-pointer"
                         onclick="window.open('https://github.com/nguyentrungnghia1802', '_blank')">
                        <div class="relative mb-6">
                            <img src="{{ asset('fixed_resources/about/nghia.jpg') }}"
                                 alt="Team Member 2"
                                 class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg">
                            <div class="absolute inset-0 w-32 h-32 rounded-full mx-auto bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ __('about.nguyen_trung_nghia') }}</h4>
                        <p class="text-pink-600 font-medium mb-3">{{ __('about.team_leader') }}</p>
                        <p class="text-gray-600 text-sm mb-3">
                            {{ __('about.nghia_description') }}
                        </p>
                        <div class="flex items-center justify-center space-x-2 text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="text-xs">{{ __('about.click_to_view_github') }}</span>
                        </div>
                    </div>
                    <!-- Team Member 3 -->
                    <div class="text-center bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl hover:scale-105 transform transition-all duration-300 cursor-pointer"
                         onclick="window.open('https://github.com/leducanhtai', '_blank')">
                        <div class="relative mb-6">
                            <img src="{{ asset('fixed_resources/about/tai.jpg') }}"
                                 alt="Team Member 3"
                                 class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg">
                            <div class="absolute inset-0 w-32 h-32 rounded-full mx-auto bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ __('about.le_duc_anh_tai') }}</h4>
                        <p class="text-pink-600 font-medium mb-3">{{ __('about.team_member') }}</p>
                        <p class="text-gray-600 text-sm mb-3">
                            {{ __('about.tai_description') }}
                        </p>
                        <div class="flex items-center justify-center space-x-2 text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="text-xs">{{ __('about.click_to_view_github') }}</span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Location -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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

    <!-- JavaScript for category navigation -->
    <script>
        function redirectToProducts(category) {
            // Create URL with category parameter
            let url = "{{ route('user.products.index') }}";
            
            // Map category names to filter values (matching controller expectations)
            const categoryMap = {
                'soap-flowers': 'soap-flower',
                'fresh-flowers': 'fresh-flower', 
                'special-flowers': 'special-flower',
                'souvenirs': 'souvenir'
            };
            
            // Get the actual category value for filtering
            const filterValue = categoryMap[category] || category;
            
            // Add category_name parameter to URL (not category)
            url += '?category_name=' + encodeURIComponent(filterValue);
            
            // Redirect to products page
            window.location.href = url;
        }
    </script>
</x-app-layout>
