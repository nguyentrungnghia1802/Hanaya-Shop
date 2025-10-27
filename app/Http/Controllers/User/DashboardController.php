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
        // Cache dashboard data for 30 minutes
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

        // Get banners from config constants
        $banners = config('constants.banners');
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
            'special-flower' => ['Special Flower', 'Hoa đặc biệt', 'special flower'],
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
            'soap-flower' => 'Soap Flower',
            'special-flower' => 'Special Flower',
            'fresh-flowers' => 'Fresh Flowers',
            'souvenir' => 'Souvenir'
        ];
        
        return $displayNames[$key] ?? ucfirst(str_replace('-', ' ', $key));
    }
}
