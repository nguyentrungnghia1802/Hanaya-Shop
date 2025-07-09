<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 600, function () {
            return [
                'categoryCount' => Category::count(),
                'productCount' => Product::count(),
                'userCount' => User::count(),
            ];
        });
        return view('admin.dashboard', $stats);
    }
}
