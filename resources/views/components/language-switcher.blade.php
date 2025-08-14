<div x-data="{ open: false }" class="relative inline-block text-left">
    <!-- Language Button -->
    <button @click="open = !open" @click.away="open = false" 
            class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span class="hidden sm:inline">{{ __('common.language') }}</span>
        <span class="sm:hidden">
            @switch(app()->getLocale())
                @case('en')
                    EN
                    @break
                @case('vi')
                    VI
                    @break
                @case('ja')
                    JP
                    @break
                @default
                    EN
            @endswitch
        </span>
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
        <div class="py-1">
            <!-- English -->
            <a href="{{ route('locale.set', 'en') }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 
                      {{ app()->getLocale() === 'en' ? 'bg-gray-50 text-pink-600 font-medium' : '' }}">
                <span class="w-6 text-center mr-3">🇺🇸</span>
                {{ __('common.english') }}
                @if(app()->getLocale() === 'en')
                    <svg class="w-4 h-4 ml-auto text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </a>

            <!-- Vietnamese -->
            <a href="{{ route('locale.set', 'vi') }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 
                      {{ app()->getLocale() === 'vi' ? 'bg-gray-50 text-pink-600 font-medium' : '' }}">
                <span class="w-6 text-center mr-3">🇻🇳</span>
                {{ __('common.vietnamese') }}
                @if(app()->getLocale() === 'vi')
                    <svg class="w-4 h-4 ml-auto text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </a>

            <!-- Japanese -->
            <a href="{{ route('locale.set', 'ja') }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 
                      {{ app()->getLocale() === 'ja' ? 'bg-gray-50 text-pink-600 font-medium' : '' }}">
                <span class="w-6 text-center mr-3">🇯🇵</span>
                {{ __('common.japanese') }}
                @if(app()->getLocale() === 'ja')
                    <svg class="w-4 h-4 ml-auto text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </a>
        </div>
    </div>
</div>
