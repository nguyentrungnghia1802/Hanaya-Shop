<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Random;

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
            'updated_at' => now(),
            'category_id' => Category::factory(),
            'discount_percent' => $this->faker->randomFloat(2, 0, 50), // 0-50% discount
            'view_count' => $this->faker->numberBetween(0, 1000), // 0-1000 views
        ];
    }
}