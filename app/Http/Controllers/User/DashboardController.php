<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index() 
    {
        // Cache các dữ liệu trang chủ trong 30 phút
        $cacheKey = 'dashboard_data_' . date('Y-m-d-H');
        
        $data = Cache::remember($cacheKey, now()->addMinutes(30), function () {
            return [
                'topSeller' => $this->getTopSellerProducts(),
                'latestByCategory' => $this->getLatestByCategory(),
                'latest' => Product::latest()->take(8)->get(),
                'onSale' => Product::where('discount_percent', '>', 0)
                    ->orderByDesc('discount_percent')
                    ->take(8)
                    ->get(),
                'mostViewed' => Product::orderByDesc('view_count')
                    ->take(8)
                    ->get(),
                'categories' => Category::withCount('product')->get(),
                'latestPosts' => Post::where('status', true)
                    ->with('author')
                    ->latest()
                    ->take(3)
                    ->get(),
            ];
        });

        // Banner data không cache vì có thể thay đổi thường xuyên
        $banners = [
            [
                'image' => 'fixed_resources/banner1.jpg',
                'title' => 'Chào mừng đến với Hanaya Shop',
                'subtitle' => 'Nơi mang đến những sản phẩm hoa xà phòng tuyệt đẹp và các quà tặng ý nghĩa',
                'button_text' => 'Khám phá ngay',
                'button_link' => route('user.products.index')
            ],
            [
                'image' => 'fixed_resources/banner2.jpg',
                'title' => 'Bộ sưu tập Hoa Xà Phòng',
                'subtitle' => 'Những bông hoa vĩnh cửu với hương thơm dịu nhẹ',
                'button_text' => 'Xem bộ sưu tập',
                'button_link' => route('user.products.index', ['category_name' => 'soap-flower'])
            ],
            [
                'image' => 'fixed_resources/banner3.jpg',
                'title' => 'Quà tặng đặc biệt',
                'subtitle' => 'Những món quà ý nghĩa cho người thân yêu',
                'button_text' => 'Tìm quà ngay',
                'button_link' => route('user.products.index', ['category_name' => 'souvenir'])
            ]
        ];

        return view('page.dashboard', array_merge($data, ['banners' => $banners]));
    }

    private function getTopSellerProducts()
    {
        return Product::select('products.*', DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold'))
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->groupBy(
                'products.id', 'products.name', 'products.price', 'products.image_url', 
                'products.stock_quantity', 'products.category_id', 'products.descriptions', 
                'products.created_at', 'products.updated_at', 'products.discount_percent', 'products.view_count'
            )
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();
    }

    private function getLatestByCategory()
    {
        $categoryMapping = [
            'soap-flower' => ['Soap Flower', 'Hoa xà phòng', 'soap flower'],
            'paper-flower' => ['Paper Flower', 'Hoa giấy', 'paper flower'],
            'fresh-flowers' => ['Fresh Flowers', 'Hoa tươi', 'fresh flowers'],
            'souvenir' => ['Souvenir', 'Quà lưu niệm', 'souvenir']
        ];

        $latestByCategory = [];
        foreach ($categoryMapping as $key => $names) {
            $products = Product::whereHas('category', function($q) use ($names) {
                $q->where(function($subQ) use ($names) {
                    foreach ($names as $name) {
                        $subQ->orWhere('name', 'like', "%$name%");
                    }
                });
            })
            ->latest()
            ->take(4)
            ->get();
            
            if ($products->count() > 0) {
                $latestByCategory[$key] = [
                    'name' => $this->getCategoryDisplayName($key),
                    'products' => $products,
                    'slug' => $key
                ];
            }
        }
        
        return $latestByCategory;
    }

    private function getCategoryDisplayName($key)
    {
        $displayNames = [
            'soap-flower' => 'Hoa Xà Phòng',
            'paper-flower' => 'Hoa Giấy',
            'fresh-flowers' => 'Hoa Tươi',
            'souvenir' => 'Quà Lưu Niệm'
        ];
        
        return $displayNames[$key] ?? ucfirst(str_replace('-', ' ', $key));
    }
}
