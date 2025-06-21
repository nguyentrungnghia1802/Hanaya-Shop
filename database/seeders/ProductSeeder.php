<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 20 product giả
        Product::factory()->count(20)->create();
    }
}
