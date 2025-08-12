<?php

namespace Database\Factories\Product;

use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        static $categories = [
            ['name' => 'Hoa sáp', 'description' => 'Những bông hoa vĩnh cửu với hương thơm dịu nhẹ'],
            ['name' => 'Hoa giấy', 'description' => 'Hoa giấy thủ công tinh xảo và đẹp mắt'],
            ['name' => 'Hoa tươi', 'description' => 'Hoa tươi tự nhiên được chăm sóc cẩn thận'],
            ['name' => 'Quà lưu niệm', 'description' => 'Những món quà ý nghĩa cho người thân yêu'],
            ['name' => 'Phụ kiện', 'description' => 'Các phụ kiện trang trí và làm đẹp'],
        ];
        static $index = 0;
        
        $category = $categories[$index] ?? [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence
        ];
        $index++;
        
        return [
            'name' => $category['name'],
            'description' => $category['description'],
            'image_path' => $this->faker->imageUrl(640, 480, 'business', true),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
