<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 1 category trước
        $category = \App\Models\Product\Category::firstOrCreate([
            'name' => 'Test Category',
        ], [
            'description' => 'Category for testing',
            'image_path' => 'base.jpg',
        ]);

        // Tạo một số products test
        $products = [
            [
                'name' => 'Test Product 1',
                'descriptions' => 'This is a test product',
                'price' => 100000,
                'stock_quantity' => 10,
                'discount_percent' => 0,
                'view_count' => 0,
                'category_id' => $category->id,
                'image_url' => 'default-product.jpg',
            ],
            [
                'name' => 'Test Product 2',
                'descriptions' => 'Another test product',
                'price' => 200000,
                'stock_quantity' => 5,
                'discount_percent' => 10,
                'view_count' => 0,
                'category_id' => $category->id,
                'image_url' => 'default-product.jpg',
            ],
        ];

        foreach ($products as $productData) {
            \App\Models\Product\Product::firstOrCreate([
                'name' => $productData['name'],
            ], $productData);
        }
    }
}
