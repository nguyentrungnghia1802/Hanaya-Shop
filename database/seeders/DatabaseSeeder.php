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
        Category::factory()->count(2)->create();
        Product::factory()->count(20)->create();
        $this->call([
            PostSeeder::class,
        ]);
    }
}