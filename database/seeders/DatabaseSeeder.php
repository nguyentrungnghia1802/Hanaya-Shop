<?php

namespace Database\Seeders;

use App\Models\Product\Category;
use App\Models\Product\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo categories trước
        Category::factory()->count(5)->create();

        // Tạo products
        Product::factory()->count(50)->create();

        // Tạo posts và users
        $this->call([
            UserSeeder::class,
            PostSeeder::class,
        ]);
    }
}
