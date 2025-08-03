<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <h1 class="text-3xl font-bold">🌸 About Hanaya Shop</h1>
                    <p class="text-pink-100 mt-2">Discover our story, mission, and the passionate team behind Hanaya Shop</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Welcome to Hanaya Shop</h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Where meaningful flowers and gifts come together to create unforgettable moments. 
                    We believe that every flower tells a story, and every gift carries a message of love.
                </p>
            </div>

            <!-- Our Story Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
                <div class="space-y-6">
                    <h3 class="text-3xl font-bold text-gray-900">Our Story</h3>
                    <div class="space-y-4 text-gray-600 leading-relaxed">
                        <p>
                            Founded with a passion for bringing beauty and joy into people's lives, Hanaya Shop began as a small dream 
                            to create something special in the world of flowers and gifts. Our journey started with a simple belief: 
                            that flowers have the power to express emotions that words sometimes cannot.
                        </p>
                        <p>
                            What makes us unique is our dedication to crafting soap flowers - eternal blooms that capture the beauty 
                            of fresh flowers while lasting forever. These handcrafted creations combine the visual appeal of fresh 
                            flowers with the practicality and longevity that our customers love.
                        </p>
                        <p>
                            Today, Hanaya Shop has grown into a trusted destination for those seeking meaningful gifts, beautiful 
                            decorations, and heartfelt expressions of love and appreciation.
                        </p>
                    </div>
                </div>
                <div class="lg:pl-8">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <img src="{{ asset('fixed_resources/about_story.jpg') }}" 
                             alt="Hanaya Shop Story" 
                             class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Our Beginning</h4>
                            <p class="text-gray-600 text-sm">
                                From a small workshop to a beloved shop, every step has been guided by our commitment to quality and beauty.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Mission & Values -->
            <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Our Mission & Values</h3>
                    <p class="text-gray-600 text-lg">The principles that guide everything we do</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Quality -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">Quality Excellence</h4>
                        <p class="text-gray-600">
                            We are committed to delivering only the highest quality products, ensuring every item meets our strict standards of beauty and craftsmanship.
                        </p>
                    </div>

                    <!-- Customer Care -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">Customer First</h4>
                        <p class="text-gray-600">
                            Your satisfaction is our priority. We go above and beyond to ensure every customer has a wonderful experience with us.
                        </p>
                    </div>

                    <!-- Sustainability -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c-1.657 0-3-4.03-3-9s1.343-9 3-9m0 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">Sustainability</h4>
                        <p class="text-gray-600">
                            We believe in responsible business practices and creating products that bring lasting joy while being mindful of our environment.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Our Products -->
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">What We Offer</h3>
                    <p class="text-gray-600 text-lg">Discover our carefully curated collection</p>
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
                                <h4 class="text-xl font-semibold text-gray-900">Soap Flowers</h4>
                            </div>
                            <p class="text-gray-600">
                                Handcrafted eternal flowers that combine beauty with functionality. Perfect for decoration and special occasions.
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
                                <h4 class="text-xl font-semibold text-gray-900">Fresh Flowers</h4>
                            </div>
                            <p class="text-gray-600">
                                Beautiful fresh flower arrangements for every occasion, from romantic bouquets to celebration centerpieces.
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
                                <h4 class="text-xl font-semibold text-gray-900">Special Flowers</h4>
                            </div>
                            <p class="text-gray-600">
                                Elegant and unique flower selections crafted for memorable moments, perfect for expressing love, gratitude, or sympathy.
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
                                <h4 class="text-xl font-semibold text-gray-900">Souvenirs</h4>
                            </div>
                            <p class="text-gray-600">
                                Thoughtfully curated gifts and souvenirs that show you care, perfect for expressing your feelings.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meet Our Team -->
            <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Meet Our Team</h3>
                    <p class="text-gray-600 text-lg">The passionate individuals behind Hanaya Shop</p>
                </div>

                <!-- Team Photo -->
                <div class="text-center mb-12">
                    <div class="inline-block rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('fixed_resources/team_group.jpg') }}" 
                             alt="Hanaya Shop Team" 
                             class="w-full max-w-2xl h-64 object-cover">
                    </div>
                    <p class="text-gray-600 mt-4 italic">Our amazing team working together to bring you the best</p>
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
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Alex Johnson</h4>
                        <p class="text-pink-600 font-medium mb-3">Founder & Creative Director</p>
                        <p class="text-gray-600 text-sm">
                            With over 8 years of experience in floral design, Alex founded Hanaya Shop with a vision to make beautiful flowers accessible to everyone.
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
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Nguyen Trung Nghia</h4>
                        <p class="text-pink-600 font-medium mb-3">Head of Operations</p>
                        <p class="text-gray-600 text-sm">
                            Nghia ensures every order is perfectly crafted and delivered with care. His attention to detail makes every customer experience exceptional.
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
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Michael Rivera</h4>
                        <p class="text-pink-600 font-medium mb-3">Lead Artisan</p>
                        <p class="text-gray-600 text-sm">
                            Michael's artistic expertise brings our soap flowers to life. His innovative techniques create stunning pieces that last forever.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact & Location -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Get In Touch</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Address</p>
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
                                <p class="font-medium text-gray-900">Phone</p>
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
                                <p class="font-medium text-gray-900">Email</p>
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
                                <p class="font-medium text-gray-900">Opening Hours</p>
                                <p class="text-gray-600">Monday - Sunday: 8:00 AM - 10:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Why Choose Us -->
                <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Why Choose Hanaya Shop?</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Premium Quality</p>
                                <p class="text-gray-600 text-sm">Every product is carefully crafted with the finest materials</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Fast Delivery</p>
                                <p class="text-gray-600 text-sm">Quick and reliable shipping to your doorstep</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Excellent Support</p>
                                <p class="text-gray-600 text-sm">Our team is always ready to help you</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Custom Orders</p>
                                <p class="text-gray-600 text-sm">We create personalized gifts for special occasions</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('user.products.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Start Shopping Now
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
