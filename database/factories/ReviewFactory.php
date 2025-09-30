<?php

namespace Database\Factories;

use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(3, 5), // Mostly positive reviews
            'comment' => $this->faker->paragraph(2),
            'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
            'order_id' => Order::inRandomOrder()->first()?->id ?? 1,
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
