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

        $query = Product::query();

        switch ($sort) {
            case 'asc':
                $query->orderBy('price', 'asc');
                break;
            case 'desc':
                $query->orderBy('price', 'desc');
                break;
            case 'sale':
                $query->orderBy('discount_percent', 'desc'); // hoặc tên cột giảm giá của bạn
                break;
            case 'views':
                $query->orderBy('view_count', 'desc'); // hoặc tên cột lượt xem của bạn
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        return view('page.soapFlower', [
            'products' => $products,
            'currentSort' => $sort,
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
