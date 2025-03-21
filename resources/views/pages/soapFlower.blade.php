<x-app-layout>
    <div class="py-12">
        <section class="mb-8">
            <x-grid.product-grid :products="[
                ['image' => 'hoa_1.jpg', 'name' => 'Hoa 1', 'price' => 100000],
                ['image' => 'hoa_2.jpg', 'name' => 'Hoa 2', 'price' => 150000],
                ['image' => 'hoa_3.jpg', 'name' => 'Hoa 3', 'price' => 200000],
            ]" />
        </section>
    </div>
</x-app-layout>
