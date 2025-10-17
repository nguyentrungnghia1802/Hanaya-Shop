<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class soapFlowerController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort');
        $keyword = $request->query('q');

        $query = Product::query();

        // Tìm kiếm theo nhiều trường, bao gồm cả tên category
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('descriptions', 'like', "%$keyword%")
                  ->orWhere('price', 'like', "%$keyword%")
                  ->orWhere('image_url', 'like', "%$keyword%")
                  ->orWhere('category_id', 'like', "%$keyword%")
                  ->orWhereHas('category', function($catQ) use ($keyword) {
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
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->appends(['sort' => $sort, 'q' => $keyword]);

        return view('page.soapFlower', [
            'products' => $products,
            'currentSort' => $sort,
            'keyword' => $keyword,
        ]);
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Lấy các sản phẩm khác để gợi ý (trừ sản phẩm hiện tại)
        $relatedProducts = Product::where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('page.productDetail', compact('product', 'relatedProducts'));
    }
}
