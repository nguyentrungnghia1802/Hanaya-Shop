<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'descriptions' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(50000, 500000),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'image_url' => $this->faker->imageUrl(640, 480, 'flowers', true, 'Soap Flower'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(), // Thêm dòng này
            'category_id' => 1, // hoặc random nếu có nhiều category
        ];
    }
}