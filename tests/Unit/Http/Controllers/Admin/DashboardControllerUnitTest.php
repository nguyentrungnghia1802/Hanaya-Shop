<?php

namespace Tests\Unit\Http\Controllers\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\User;
use App\Models\Post;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Mockery;

class DashboardControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new DashboardController();
        
        // Freeze time for consistent testing
        Carbon::setTestNow('2024-06-15 10:00:00');
    }

    public function test_index_returns_correct_basic_counts()
    {
        Category::factory()->count(5)->create();
        Product::factory()->count(10)->create();
        User::factory()->count(15)->create();
        Post::factory()->count(8)->create();
        Order::factory()->count(12)->create();

        $response = $this->controller->index();

        $this->assertEquals('admin.dashboard', $response->getName());
        
        $data = $response->getData();
        $this->assertEquals(5, $data['categoryCount']);
        $this->assertEquals(10, $data['productCount']);
        $this->assertEquals(15, $data['userCount']);
        $this->assertEquals(8, $data['postCount']);
        $this->assertEquals(12, $data['orderCount']);
    }
    public function test_index_calculates_revenue_statistics_correctly()
    {
        Order::factory()->create(['status' => 'completed', 'total_price' => 100000]);
        Order::factory()->create(['status' => 'completed', 'total_price' => 200000]);
        Order::factory()->create(['status' => 'pending', 'total_price' => 50000]);
        
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 150000,
            'created_at' => Carbon::now() // 6/2024
        ]);
        
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 300000,
            'created_at' => Carbon::now()->subMonth() // 5/2024
        ]);

        $response = $this->controller->index();
        $data = $response->getData();

        $this->assertEquals(750000, $data['totalRevenue']); // 100k + 200k + 150k + 300k

        $this->assertEquals(250000, $data['monthlyRevenue']); // 100k + 200k + 150k 
    }

    public function test_index_handles_null_revenue_with_fallback()
    {
        
        $response = $this->controller->index();
        $data = $response->getData();

        $this->assertEquals(0, $data['totalRevenue']);
        $this->assertEquals(0, $data['monthlyRevenue']);
    }

    public function test_index_calculates_product_stock_statistics()
    {
        Product::factory()->create(['stock_quantity' => 10]); // Active
        Product::factory()->create(['stock_quantity' => 5]);  // Active
        Product::factory()->create(['stock_quantity' => 0]);  // Out of stock
        Product::factory()->create(['stock_quantity' => -1]); // Out of stock

        $response = $this->controller->index();
        $data = $response->getData();

        $this->assertEquals(2, $data['activeProducts']);
        
        $this->assertEquals(2, $data['outOfStockProducts']);
    }

    public function test_index_gets_best_selling_products()
    {
        $product1 = Product::factory()->create(['name' => 'Product A', 'view_count' => 100]);
        $product2 = Product::factory()->create(['name' => 'Product B', 'view_count' => 200]);
        $product3 = Product::factory()->create(['name' => 'Product C', 'view_count' => 50]);
        
        Product::factory()->count(5)->create(['view_count' => 10]);

        $response = $this->controller->index();
        $data = $response->getData();

        $bestSellingProducts = $data['bestSellingProducts'];
        
        $this->assertEquals(5, $bestSellingProducts->count());
        
        $this->assertEquals('Product B', $bestSellingProducts->first()->name); // Highest view_count
        $this->assertEquals(200, $bestSellingProducts->first()->view_count);
        
        $this->assertTrue($bestSellingProducts->first()->has('id'));
        $this->assertTrue($bestSellingProducts->first()->has('name'));
        $this->assertTrue($bestSellingProducts->first()->has('price'));
        $this->assertTrue($bestSellingProducts->first()->has('image_url'));
        $this->assertTrue($bestSellingProducts->first()->has('stock_quantity'));
    }

    public function test_index_gets_recent_orders_with_users()
    {
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);

        $order1 = Order::factory()->create([
            'user_id' => $user1->id,
            'total_price' => 100000,
            'status' => 'completed',
            'created_at' => Carbon::now()->subMinutes(10)
        ]);
        
        $order2 = Order::factory()->create([
            'user_id' => $user2->id,
            'total_price' => 200000,
            'status' => 'pending',
            'created_at' => Carbon::now()->subMinutes(5) // Newer
        ]);
        
        Order::factory()->count(5)->create(['created_at' => Carbon::now()->subHours(1)]);

        $response = $this->controller->index();
        $data = $response->getData();

        $recentOrders = $data['recentOrders'];
        
        $this->assertEquals(5, $recentOrders->count());
        
        $this->assertEquals($order2->id, $recentOrders->first()->id); // Newest first
        
        $this->assertEquals('Jane Smith', $recentOrders->first()->user->name);
        
        $firstOrder = $recentOrders->first();
        $this->assertTrue(isset($firstOrder->id));
        $this->assertTrue(isset($firstOrder->user_id));
        $this->assertTrue(isset($firstOrder->total_price));
        $this->assertTrue(isset($firstOrder->status));
        $this->assertTrue(isset($firstOrder->created_at));
    }

    public function test_index_counts_new_users_this_month()
    {
        User::factory()->create(['created_at' => Carbon::now()]); // June 2024
        User::factory()->create(['created_at' => Carbon::now()->subDays(5)]); // June 2024
        
        User::factory()->create(['created_at' => Carbon::now()->subMonth()]); // May 2024
        User::factory()->create(['created_at' => Carbon::now()->addMonth()]); // July 2024

        $response = $this->controller->index();
        $data = $response->getData();

        $this->assertEquals(2, $data['newUsersThisMonth']);
    }

    public function test_index_calculates_order_status_distribution()
    {
        Order::factory()->count(3)->create(['status' => 'pending']);
        Order::factory()->count(5)->create(['status' => 'completed']);
        Order::factory()->count(2)->create(['status' => 'cancelled']);
        Order::factory()->create(['status' => 'shipped']); 

        $response = $this->controller->index();
        $data = $response->getData();

        $orderStatusStats = $data['orderStatusStats'];
        
        $this->assertEquals(3, $orderStatusStats['pending']);
        $this->assertEquals(5, $orderStatusStats['completed']);
        $this->assertEquals(2, $orderStatusStats['cancelled']);
        
        $this->assertArrayHasKey('pending', $orderStatusStats);
        $this->assertArrayHasKey('completed', $orderStatusStats);
        $this->assertArrayHasKey('cancelled', $orderStatusStats);
    }

    public function test_index_generates_monthly_revenue_chart()
    {

        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 100000,
            'created_at' => Carbon::parse('2024-06-01') // June
        ]);
        
        Order::factory()->create([
            'status' => 'completed', 
            'total_price' => 200000,
            'created_at' => Carbon::parse('2024-05-15') // May
        ]);
        
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 150000,
            'created_at' => Carbon::parse('2024-04-10') // April
        ]);
        
        Order::factory()->create([
            'status' => 'pending',
            'total_price' => 300000,
            'created_at' => Carbon::parse('2024-06-01')
        ]);

        $response = $this->controller->index();
        $data = $response->getData();

        $monthlyRevenueChart = $data['monthlyRevenueChart'];
        
        $this->assertEquals(6, count($monthlyRevenueChart));
        
        $this->assertEquals('Jan 2024', $monthlyRevenueChart[0]['month']); // 5 months ago
        $this->assertEquals('Jun 2024', $monthlyRevenueChart[5]['month']); // Current month
        
        $this->assertEquals('100,000', $monthlyRevenueChart[5]['revenue']); // June
        $this->assertEquals('200,000', $monthlyRevenueChart[4]['revenue']); // May  
        $this->assertEquals('150,000', $monthlyRevenueChart[3]['revenue']); // April
        
        $this->assertStringContains(',', $monthlyRevenueChart[5]['revenue']);
    }

    public function test_index_gets_low_stock_products()
    {
        Product::factory()->create(['name' => 'Product A', 'stock_quantity' => 2]); // Low stock
        Product::factory()->create(['name' => 'Product B', 'stock_quantity' => 8]); // Low stock
        Product::factory()->create(['name' => 'Product C', 'stock_quantity' => 15]); // Normal stock
        Product::factory()->create(['name' => 'Product D', 'stock_quantity' => 0]); // Out of stock - excluded
        Product::factory()->create(['name' => 'Product E', 'stock_quantity' => 5]); // Low stock
        
        Product::factory()->count(3)->create(['stock_quantity' => 3]);

        $response = $this->controller->index();
        $data = $response->getData();

        $lowStockProducts = $data['lowStockProducts'];
        
        foreach ($lowStockProducts as $product) {
            $this->assertGreaterThan(0, $product->stock_quantity); // > 0
            $this->assertLessThanOrEqual(10, $product->stock_quantity); // <= 10
        }
        
        $this->assertEquals(5, $lowStockProducts->count());
        
        $this->assertEquals(2, $lowStockProducts->first()->stock_quantity); // Lowest first
        
        $firstProduct = $lowStockProducts->first();
        $this->assertTrue(isset($firstProduct->id));
        $this->assertTrue(isset($firstProduct->name));
        $this->assertTrue(isset($firstProduct->stock_quantity)); 
        $this->assertTrue(isset($firstProduct->image_url));
    }

    public function test_index_compacts_all_statistics_correctly()
    {
        Category::factory()->create();
        Product::factory()->create();

        $response = $this->controller->index();
        $data = $response->getData();

        $expectedKeys = [
            'categoryCount', 'productCount', 'userCount', 'postCount', 'orderCount',
            'totalRevenue', 'monthlyRevenue', 'activeProducts', 'outOfStockProducts',
            'bestSellingProducts', 'recentOrders', 'newUsersThisMonth',
            'orderStatusStats', 'monthlyRevenueChart', 'lowStockProducts'
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }

    public function test_index_returns_fallback_data_when_exception_occurs()
    {
        $this->mock(Category::class, function ($mock) {
            $mock->shouldReceive('count')->andThrow(new \Exception('Database error'));
        });

        $response = $this->controller->index();
        $data = $response->getData();

        $this->assertEquals(0, $data['categoryCount']);
        $this->assertEquals(0, $data['productCount']);
        $this->assertEquals(0, $data['userCount']);
        $this->assertEquals(0, $data['postCount']);
        $this->assertEquals(0, $data['orderCount']);
        $this->assertEquals(0, $data['totalRevenue']);
        $this->assertEquals(0, $data['monthlyRevenue']);
        $this->assertEquals(0, $data['activeProducts']);
        $this->assertEquals(0, $data['outOfStockProducts']);
        
        $this->assertInstanceOf(Collection::class, $data['bestSellingProducts']);
        $this->assertTrue($data['bestSellingProducts']->isEmpty());
        $this->assertInstanceOf(Collection::class, $data['recentOrders']);
        $this->assertTrue($data['recentOrders']->isEmpty());
        $this->assertInstanceOf(Collection::class, $data['lowStockProducts']);
        $this->assertTrue($data['lowStockProducts']->isEmpty());
        
        $this->assertEquals(['pending' => 0, 'completed' => 0, 'cancelled' => 0], $data['orderStatusStats']);
        $this->assertEquals([], $data['monthlyRevenueChart']);
    }

    public function test_index_returns_correct_view_with_data()
    {
        $response = $this->controller->index();

        $this->assertEquals('admin.dashboard', $response->getName());
        
        $viewData = $response->getData();
        $this->assertNotEmpty($viewData);
        
        $this->assertArrayHasKey('categoryCount', $viewData);
        $this->assertArrayHasKey('totalRevenue', $viewData);
        $this->assertArrayHasKey('bestSellingProducts', $viewData);
    }

    public function test_index_performance_with_large_dataset()
    {
        Category::factory()->count(100)->create();
        Product::factory()->count(500)->create();
        User::factory()->count(200)->create();
        Order::factory()->count(300)->create();

        $startTime = microtime(true);
        
        $response = $this->controller->index();
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        $this->assertLessThan(2000, $executionTime, "Dashboard took too long: {$executionTime}ms");
        
        $this->assertEquals('admin.dashboard', $response->getName());
    }

    public function test_index_handles_date_edge_cases()
    {
        Carbon::setTestNow('2024-01-31 23:59:59');
        
        User::factory()->create(['created_at' => Carbon::now()]);
        User::factory()->create(['created_at' => Carbon::now()->addSecond()]); // Next month
        
        $response = $this->controller->index();
        $data = $response->getData();
        
        $this->assertEquals(1, $data['newUsersThisMonth']);
    }

    public function test_monthly_revenue_chart_number_formatting()
    {
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 1234567, // Large number
            'created_at' => Carbon::now()
        ]);

        $response = $this->controller->index();
        $data = $response->getData();

        $monthlyRevenueChart = $data['monthlyRevenueChart'];
        $currentMonthRevenue = $monthlyRevenueChart[5]['revenue']; // Current month
        
        $this->assertEquals('1,234,567', $currentMonthRevenue);
        $this->assertIsString($currentMonthRevenue); // Should be formatted as string
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // Reset time
        Mockery::close();
        parent::tearDown();
    }
}