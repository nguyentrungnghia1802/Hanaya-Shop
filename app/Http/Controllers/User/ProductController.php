<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort');
        $keyword = $request->query('q');
        $categoryId = $request->query('category');
        $categoryName = $request->query('category_name');

        // Create cache key based on query parameters
        $cacheKey = 'products_index_' . md5(serialize([
            'sort' => $sort,
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'categoryName' => $categoryName,
            'page' => $request->query('page', 1)
        ]));

        // Cache the results for 15 minutes
        $result = Cache::remember($cacheKey, 900, function () use ($sort, $keyword, $categoryId, $categoryName, $request) {
            $query = Product::with('category');

            // Filter by category_name if provided
            if ($categoryName) {
                $categoryMapping = [
                    'soap-flower' => ['Soap Flower', 'Hoa xà phòng', 'soap flower'],
                    'fresh-flower' => ['Fresh Flower', 'Hoa tươi', 'fresh flower'],
                    'special-flower' => ['Special Flower', 'Hoa đặc biệt', 'special flower'],
                    'souvenir' => ['Souvenir', 'Quà lưu niệm', 'souvenir']
                ];

                if (isset($categoryMapping[$categoryName])) {
                    $categoryNames = $categoryMapping[$categoryName];
                    $query->whereHas('category', function ($q) use ($categoryNames) {
                        $q->where(function ($subQ) use ($categoryNames) {
                            foreach ($categoryNames as $name) {
                                $subQ->orWhere('name', 'like', "%$name%");
                            }
                        });
                    });
                }
            }
            // Filter by category ID if provided (fallback)
            elseif ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            // Tìm kiếm theo nhiều trường, bao gồm cả tên category
            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%")
                        ->orWhere('descriptions', 'like', "%$keyword%")
                        ->orWhere('price', 'like', "%$keyword%")
                        ->orWhere('image_url', 'like', "%$keyword%")
                        ->orWhere('category_id', 'like', "%$keyword%")
                        ->orWhereHas('category', function ($catQ) use ($keyword) {
                            $catQ->where('name', 'like', "%$keyword%")
                                ->orWhere('descriptions', 'like', "%$keyword%")
                            ;
                        });
                });
            }

            switch ($sort) {
                case 'asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'sale':
                    $query->orderBy('discount_percent', 'desc');
                    break;
                case 'views':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'bestseller':
                    // Join with order_details to get best sellers
                    $query->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
                        ->select('products.*', DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold'))
                        ->groupBy('products.id', 'products.name', 'products.price', 'products.image_url', 'products.stock_quantity', 'products.category_id', 'products.descriptions', 'products.created_at', 'products.updated_at', 'products.discount_percent', 'products.view_count')
                        ->orderByDesc('total_sold');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $products = $query->paginate(10)->appends([
                'sort' => $sort,
                'q' => $keyword,
                'category' => $categoryId,
                'category_name' => $categoryName
            ]);

            // Get all categories for filter dropdown
            $categories = \App\Models\Product\Category::withCount('product')->get();

            // Determine page title based on category
            $pageTitle = 'Products - Hanaya Shop';
            if ($categoryName) {
                $categoryTitles = [
                    'soap-flower' => 'Soap Flower',
                    'fresh-flower' => 'Fresh Flower',
                    'special-flower' => 'Special Flower',
                    'souvenir' => 'Souvenir'
                ];
                $pageTitle = $categoryTitles[$categoryName] ?? $pageTitle;
            }

            return [
                'products' => $products,
                'categories' => $categories,
                'pageTitle' => $pageTitle,
            ];
        });

        return view('page.products.index', array_merge($result, [
            'currentSort' => $sort,
            'keyword' => $keyword,
            'selectedCategory' => $categoryId,
            'selectedCategoryName' => $categoryName,
        ]));
    }


    public function show($id)
    {
        // Cache the product details
        $product = Cache::remember("product_detail_{$id}", 1800, function () use ($id) {
            return Product::findOrFail($id);
        });

        // Increment view count (don't cache this)
        Product::where('id', $id)->increment('view_count');

        // Cache related products
        $relatedProducts = Cache::remember("related_products_{$id}", 1800, function () use ($id) {
            return Product::where('id', '!=', $id)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        });

        return view('page.products.productDetail', compact('product', 'relatedProducts'));
    }
}
