<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 w-4/5 mx-auto ">
        <section class="p-6 m-8 rounded-xl bg-gray-300">
            <p class="text-2xl font-semibold mb-4">Sản phẩm mới</p>
            <x-home.slider :products="$latest" />
        </section>

        <section class="p-6 m-8 rounded-xl bg-gray-300">
            <p class="text-2xl font-semibold mb-4">Sản phẩm bán chạy</p>
            <x-home.slider :products="$topSeller" />
        </section>
    </div>
</x-app-layout>
