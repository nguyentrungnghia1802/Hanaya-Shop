<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
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
            'updated_at' => now(), // Thêm dòng này
            'category_id' => $this->faker->numberBetween(10, 16), // random category_id between 1 and 10
        ];
    }
}