<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() 
    {
        $topSeller = Product::select('products.*', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->groupBy('products.id', 'products.name', 'products.price', 'products.image_url', 'products.stock_quantity', 'products.category_id', 'products.created_at', 'products.updated_at')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();
        $latest = Product::latest()->take(10)->get();

        return view('page.dashboard', ['latest' => $latest, 'topSeller' => $topSeller]);
    }
}
