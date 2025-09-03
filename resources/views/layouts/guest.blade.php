<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <!-- Header: Logo + Language Switcher -->
            <div class="w-full sm:max-w-md flex justify-between items-center mb-6 px-6">
                <a href="/" class="flex items-center">
                    <x-application-logo class="w-16 h-16 mr-3" />
                    <span class="text-3xl font-light text-gray-800">{{ __('common.shop_name') }}</span>
                </a>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center px-4 py-2 text-sm text-gray-700 bg-white rounded-lg shadow hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <span class="mr-2">{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                        <a href="{{ route('locale.set', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg {{ app()->getLocale() == 'en' ? 'bg-pink-50 text-pink-700' : '' }}">
                            🇺🇸 English
                        </a>
                        <a href="{{ route('locale.set', 'vi') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'vi' ? 'bg-pink-50 text-pink-700' : '' }}">
                            🇻🇳 Tiếng Việt
                        </a>
                        <a href="{{ route('locale.set', 'ja') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg {{ app()->getLocale() == 'ja' ? 'bg-pink-50 text-pink-700' : '' }}">
                            🇯🇵 日本語
                        </a>
                    </div>
                </div>
            </div>

            <!-- ...existing code... -->

            <!-- Main Content Card -->
            <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
