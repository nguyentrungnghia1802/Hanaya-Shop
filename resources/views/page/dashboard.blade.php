<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <p>{{ __('Sản phẩm bán chạy') }}</p>
            </div>
        </div>
    </div>


    <div class="py-12">
        <!-- Slider: sử dụng component slider đã tạo -->
        <section class="p-6 m-8">
            <x-home.slider :images="[
                'hoa_1.jpg',
                'hoa_2.jpg',
                'hoa_3.jpg',
                'hoa_1.jpg',
                'hoa_2.jpg',
                'hoa_3.jpg',
                'hoa_1.jpg',
                'hoa_2.jpg',
                'hoa_3.jpg',
            ]" />
        </section>

        <div class="p-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <p>{{ __('Sale') }}</p>
            </div>
        </div>
    </div>

        <section class="p-6 m-8">
            <x-home.slider :images="[
                'hoa_1.jpg',
                'hoa_2.jpg',
                'hoa_3.jpg',
                'hoa_1.jpg',
                'hoa_2.jpg',
                'hoa_3.jpg',
                'hoa_1.jpg',
                'hoa_2.jpg',
                'hoa_3.jpg',
            ]" />
        </section>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __('!') }}</p>
                    <p>{{ __('') }}</p>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
