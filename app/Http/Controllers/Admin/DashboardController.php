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
    /**
     * Display the admin dashboard with cached summary statistics.
     */
    public function index()
    {
        // Retrieve statistics from cache (10 minutes) or compute them if not cached
        $stats = Cache::remember('admin_dashboard_stats', 600, function () {
            return [
                'categoryCount' => Category::count(), // Total number of product categories
                'productCount' => Product::count(),   // Total number of products
                'userCount' => User::count(),         // Total number of users
            ];
        });

        // Pass the statistics to the dashboard view
        return view('admin.dashboard', $stats);
    }
}
