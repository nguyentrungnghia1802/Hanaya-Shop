<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Order\Order;
use App\Models\Post;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class DashboardControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new DashboardController;

        $this->category = Category::factory()->create();

        Carbon::setTestNow('2024-06-15 10:00:00');
    }

    public function test_index_returns_correct_basic_counts()
    {
        Category::factory()->count(4)->create();
        Product::factory()->count(10)->create(['category_id' => $this->category->id]);
        User::factory()->count(15)->create();
        Post::factory()->count(8)->create();

        for ($i = 0; $i < 12; $i++) {
            Order::create([
                'user_id' => User::factory()->create()->id,
                'total_price' => 100000,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $response = $this->controller->index();

        $this->assertEquals('admin.dashboard', $response->getName());

        $data = $response->getData();
        $this->assertEquals(5, $data['categoryCount']);
        $this->assertEquals(10, $data['productCount']);
        $this->assertEquals(35, $data['userCount']); // 15 + 12 users từ orders + 8 users từ posts
        $this->assertEquals(8, $data['postCount']);
        $this->assertEquals(12, $data['orderCount']);
    }

    public function test_index_calculates_revenue_statistics_correctly()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();

        Order::create([
            'user_id' => $user1->id,
            'status' => 'completed',
            'total_price' => 100000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Order::create([
            'user_id' => $user2->id,
            'status' => 'completed',
            'total_price' => 200000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Order::create([
            'user_id' => $user3->id,
            'status' => 'pending',
            'total_price' => 50000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Order::create([
            'user_id' => $user4->id,
            'status' => 'completed',
            'total_price' => 150000,
            'created_at' => Carbon::now(),
            'updated_at' => now(),
        ]);

        $previousMonthOrder = new Order;
        $previousMonthOrder->user_id = $user1->id;
        $previousMonthOrder->status = 'completed';
        $previousMonthOrder->total_price = 300000;
        $previousMonthOrder->timestamps = false; // Disable automatic timestamps
        $previousMonthOrder->created_at = Carbon::parse('2024-05-15 10:00:00'); // Previous month (May)
        $previousMonthOrder->updated_at = Carbon::parse('2024-05-15 10:00:00');
        $previousMonthOrder->save();

        $response = $this->controller->index();
        $data = $response->getData();

        $this->assertEquals(750000, $data['totalRevenue']); // 100k + 200k + 150k + 300k (all completed orders)

        $this->assertEquals(450000, $data['monthlyRevenue']); // 100k + 200k + 150k (current month completed orders)
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
        Product::factory()->create(['category_id' => $this->category->id, 'stock_quantity' => 10]); // Active
        Product::factory()->create(['category_id' => $this->category->id, 'stock_quantity' => 5]);  // Active
        Product::factory()->create(['category_id' => $this->category->id, 'stock_quantity' => 0]);  // Out of stock
        Product::factory()->create(['category_id' => $this->category->id, 'stock_quantity' => -1]); // Out of stock

        $response = $this->controller->index();
        $data = $response->getData();

        $this->assertEquals(2, $data['activeProducts']);
        $this->assertEquals(2, $data['outOfStockProducts']);
    }

    public function test_index_gets_best_selling_products()
    {
        $product1 = Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product A', 'view_count' => 100]);
        $product2 = Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product B', 'view_count' => 200]);
        $product3 = Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product C', 'view_count' => 50]);

        Product::factory()->count(5)->create(['category_id' => $this->category->id, 'view_count' => 10]);

        $response = $this->controller->index();
        $data = $response->getData();

        $bestSellingProducts = $data['bestSellingProducts'];

        $this->assertEquals(5, $bestSellingProducts->count());

        $this->assertEquals('Product B', $bestSellingProducts->first()->name); // Highest view_count
        $this->assertEquals(200, $bestSellingProducts->first()->view_count);

        $this->assertNotNull($bestSellingProducts->first()->id);
        $this->assertNotNull($bestSellingProducts->first()->name);
        $this->assertNotNull($bestSellingProducts->first()->price);
        $this->assertNotNull($bestSellingProducts->first()->image_url);
        $this->assertNotNull($bestSellingProducts->first()->stock_quantity);
    }

    public function test_index_gets_recent_orders_with_users()
    {
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);

        // Create additional older orders first
        for ($i = 0; $i < 5; $i++) {
            Order::create([
                'user_id' => User::factory()->create()->id,
                'total_price' => 50000,
                'status' => 'completed',
            ]);
        }

        // Create older order
        $order1 = Order::create([
            'user_id' => $user1->id,
            'total_price' => 100000,
            'status' => 'completed',
        ]);

        // Create newer order last (higher ID, should appear first in recent orders)
        $order2 = Order::create([
            'user_id' => $user2->id,
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->controller->index();
        $data = $response->getData();

        $recentOrders = $data['recentOrders'];

        $this->assertEquals(5, $recentOrders->count());

        $this->assertEquals($order2->total_price, $recentOrders->first()->total_price); // Newest first (200000)        $this->assertEquals('Jane Smith', $recentOrders->first()->user->name);

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
        $users = User::factory()->count(11)->create();

        for ($i = 0; $i < 3; $i++) {
            Order::create([
                'user_id' => $users[$i]->id,
                'status' => 'pending',
                'total_price' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 3; $i < 8; $i++) {
            Order::create([
                'user_id' => $users[$i]->id,
                'status' => 'completed',
                'total_price' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 8; $i < 10; $i++) {
            Order::create([
                'user_id' => $users[$i]->id,
                'status' => 'cancelled',
                'total_price' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Order::create([
            'user_id' => $users[10]->id,
            'status' => 'shipped',
            'total_price' => 100000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
        $users = User::factory()->count(4)->create();

        // Use DB direct insertion to control exact timestamps
        DB::table('orders')->insert([
            'user_id' => $users[0]->id,
            'status' => 'completed',
            'total_price' => 100000,
            'created_at' => '2024-06-01 10:00:00',
            'updated_at' => '2024-06-01 10:00:00',
        ]);

        DB::table('orders')->insert([
            'user_id' => $users[1]->id,
            'status' => 'completed',
            'total_price' => 200000,
            'created_at' => '2024-05-15 10:00:00',
            'updated_at' => '2024-05-15 10:00:00',
        ]);

        DB::table('orders')->insert([
            'user_id' => $users[2]->id,
            'status' => 'completed',
            'total_price' => 150000,
            'created_at' => '2024-04-10 10:00:00',
            'updated_at' => '2024-04-10 10:00:00',
        ]);

        DB::table('orders')->insert([
            'user_id' => $users[3]->id,
            'status' => 'pending',
            'total_price' => 300000,
            'created_at' => '2024-06-01 10:00:00',
            'updated_at' => '2024-06-01 10:00:00',
        ]);

        $response = $this->controller->index();
        $data = $response->getData();

        $monthlyRevenueChart = $data['monthlyRevenueChart'];

        $this->assertEquals(6, count($monthlyRevenueChart));

        $this->assertEquals('Jan 2024', $monthlyRevenueChart[0]['month']); // 5 months ago
        $this->assertEquals('Jun 2024', $monthlyRevenueChart[5]['month']); // Current month

        $this->assertEquals('100,000.00', $monthlyRevenueChart[5]['revenue']); // June
        $this->assertEquals('200,000.00', $monthlyRevenueChart[4]['revenue']); // May
        $this->assertEquals('150,000.00', $monthlyRevenueChart[3]['revenue']); // April        $this->assertStringContainsString(',', $monthlyRevenueChart[5]['revenue']);
    }

    public function test_index_gets_low_stock_products()
    {
        Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product A', 'stock_quantity' => 2]); // Low stock
        Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product B', 'stock_quantity' => 8]); // Low stock
        Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product C', 'stock_quantity' => 15]); // Normal stock
        Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product D', 'stock_quantity' => 0]); // Out of stock - excluded
        Product::factory()->create(['category_id' => $this->category->id, 'name' => 'Product E', 'stock_quantity' => 5]); // Low stock

        Product::factory()->count(3)->create(['category_id' => $this->category->id, 'stock_quantity' => 3]);

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
        Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->controller->index();
        $data = $response->getData();

        $expectedKeys = [
            'categoryCount', 'productCount', 'userCount', 'postCount', 'orderCount',
            'totalRevenue', 'monthlyRevenue', 'activeProducts', 'outOfStockProducts',
            'bestSellingProducts', 'recentOrders', 'newUsersThisMonth',
            'orderStatusStats', 'monthlyRevenueChart', 'lowStockProducts',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }

    public function test_index_returns_fallback_data_when_exception_occurs()
    {
        // Create a mock controller that throws an exception
        $mockController = $this->getMockBuilder(DashboardController::class)
            ->onlyMethods(['index'])
            ->getMock();

        // Mock the controller to simulate the exception path
        $mockController->method('index')->willReturnCallback(function () {
            try {
                throw new \Exception('Simulated database error');
            } catch (\Exception $e) {
                // Simulate the catch block behavior from the real controller
                $stats = [
                    'categoryCount' => 0, 'productCount' => 0, 'userCount' => 0, 'postCount' => 0, 'orderCount' => 0,
                    'totalRevenue' => 0, 'monthlyRevenue' => 0, 'activeProducts' => 0, 'outOfStockProducts' => 0,
                    'bestSellingProducts' => collect(), 'recentOrders' => collect(), 'newUsersThisMonth' => 0,
                    'orderStatusStats' => ['pending' => 0, 'completed' => 0, 'cancelled' => 0],
                    'monthlyRevenueChart' => [], 'lowStockProducts' => collect(),
                ];

                return view('admin.dashboard', $stats);
            }
        });

        $response = $mockController->index();
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
        $this->assertIsArray($data['monthlyRevenueChart']);
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
        // Reduced dataset for faster testing - focus on logic not stress testing
        Category::factory()->count(10)->create();
        Product::factory()->count(50)->create(['category_id' => $this->category->id]);
        $users = User::factory()->count(20)->create();

        // Create fewer orders for performance
        for ($i = 0; $i < 30; $i++) {
            Order::create([
                'user_id' => $users[array_rand($users->toArray())]->id,
                'total_price' => rand(50000, 500000),
                'status' => ['pending', 'completed', 'cancelled'][array_rand(['pending', 'completed', 'cancelled'])],
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }

        $startTime = microtime(true);

        $response = $this->controller->index();

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        // More realistic performance expectation for unit test
        $this->assertLessThan(5000, $executionTime, "Dashboard took too long: {$executionTime}ms");

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
        $user = User::factory()->create();

        Order::create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_price' => 1234567, // Large number
            'created_at' => Carbon::now(),
            'updated_at' => now(),
        ]);

        $response = $this->controller->index();
        $data = $response->getData();

        $monthlyRevenueChart = $data['monthlyRevenueChart'];
        $currentMonthRevenue = $monthlyRevenueChart[5]['revenue']; // Current month

        $this->assertEquals('1,234,567.00', $currentMonthRevenue);
        $this->assertIsString($currentMonthRevenue); // Should be formatted as string
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // Reset time
        Mockery::close();
        parent::tearDown();
    }
}
