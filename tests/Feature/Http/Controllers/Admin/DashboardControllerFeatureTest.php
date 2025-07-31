<?php

namespace Tests\Feature\Admin;

use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\User;
use App\Models\Post;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class DashboardControllerFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    public function test_dashboard_index_returns_correct_view()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_dashboard_displays_basic_statistics_correctly()
    {
        Category::factory(3)->create();
        Product::factory(5)->create();
        User::factory(7)->create();
        Post::factory(4)->create();
        Order::factory(6)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHasAll([
            'categoryCount',
            'productCount',
            'userCount',
            'postCount',
            'orderCount'
        ]);

        $viewData = $response->viewData();
        $this->assertEquals(3, $viewData['categoryCount']);
        $this->assertEquals(5, $viewData['productCount']);
        $this->assertEquals(8, $viewData['userCount']); // 7 + 1 admin user
        $this->assertEquals(4, $viewData['postCount']);
        $this->assertEquals(6, $viewData['orderCount']);
    }

    public function test_dashboard_calculates_revenue_correctly()
    {
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 100000,
            'created_at' => Carbon::now()
        ]);

        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 200000,
            'created_at' => Carbon::now()
        ]);

        Order::factory()->create([
            'status' => 'pending',
            'total_price' => 50000,
            'created_at' => Carbon::now()
        ]);

        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 75000,
            'created_at' => Carbon::now()->subMonth()
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();

        // Total revenue: 100000 + 200000 + 75000 = 375000 
        $this->assertEquals(375000, $viewData['totalRevenue']);

        // Monthly revenue = 300000
        $this->assertEquals(300000, $viewData['monthlyRevenue']);
    }

    public function test_dashboard_calculates_product_statistics_correctly()
    {
        Product::factory(3)->create(['stock_quantity' => 10]);
        Product::factory(2)->create(['stock_quantity' => 0]);
        Product::factory(1)->create(['stock_quantity' => -1]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();

        $this->assertEquals(3, $viewData['activeProducts']);
        $this->assertEquals(3, $viewData['outOfStockProducts']);
    }

    public function test_dashboard_shows_best_selling_products()
    {
        $product1 = Product::factory()->create(['view_count' => 100]);
        $product2 = Product::factory()->create(['view_count' => 200]);
        $product3 = Product::factory()->create(['view_count' => 50]);
        Product::factory(3)->create(['view_count' => 10]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();
        $bestSellingProducts = $viewData['bestSellingProducts'];

        $this->assertCount(5, $bestSellingProducts);
        $this->assertEquals($product2->id, $bestSellingProducts->first()->id);
        $this->assertEquals(200, $bestSellingProducts->first()->view_count);
    }

    public function test_dashboard_shows_recent_orders_with_user_info()
    {
        $user = User::factory()->create(['name' => 'Test User']);

        $recentOrder = Order::factory()->create([
            'user_id' => $user->id,
            'total_price' => 100000,
            'created_at' => Carbon::now()
        ]);

        $olderOrder = Order::factory()->create([
            'user_id' => $user->id,
            'total_price' => 50000,
            'created_at' => Carbon::now()->subDays(5)
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();
        $recentOrders = $viewData['recentOrders'];

        $this->assertCount(2, $recentOrders);
        $this->assertEquals($recentOrder->id, $recentOrders->first()->id);
        $this->assertEquals('Test User', $recentOrders->first()->user->name);
    }

    public function test_dashboard_counts_new_users_this_month()
    {
        User::factory(3)->create(['created_at' => Carbon::now()]);

        User::factory(2)->create(['created_at' => Carbon::now()->subMonth()]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();

        $this->assertEquals(3, $viewData['newUsersThisMonth']);
    }

    public function test_dashboard_shows_order_status_distribution()
    {
        Order::factory(2)->create(['status' => 'pending']);
        Order::factory(3)->create(['status' => 'completed']);
        Order::factory(1)->create(['status' => 'cancelled']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();
        $orderStatusStats = $viewData['orderStatusStats'];

        $this->assertEquals(2, $orderStatusStats['pending']);
        $this->assertEquals(3, $orderStatusStats['completed']);
        $this->assertEquals(1, $orderStatusStats['cancelled']);
    }

    public function test_dashboard_generates_monthly_revenue_chart_data()
    {
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 100000,
            'created_at' => Carbon::now()
        ]);

        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 200000,
            'created_at' => Carbon::now()->subMonth()
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();
        $monthlyRevenueChart = $viewData['monthlyRevenueChart'];

        $this->assertCount(6, $monthlyRevenueChart);
        $this->assertArrayHasKey('month', $monthlyRevenueChart[0]);
        $this->assertArrayHasKey('revenue', $monthlyRevenueChart[0]);
    }

    public function test_dashboard_shows_low_stock_products()
    {
        $lowStock1 = Product::factory()->create(['stock_quantity' => 5]);
        $lowStock2 = Product::factory()->create(['stock_quantity' => 10]);
        Product::factory()->create(['stock_quantity' => 15]);
        Product::factory()->create(['stock_quantity' => 0]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();
        $lowStockProducts = $viewData['lowStockProducts'];

        $this->assertCount(2, $lowStockProducts);
        $this->assertEquals($lowStock1->id, $lowStockProducts->first()->id);
    }

    public function test_dashboard_handles_database_exceptions_gracefully()
    {
        \DB::statement('DROP TABLE IF EXISTS temp_categories');

        $this->mock(\App\Models\Product\Category::class, function ($mock) {
            $mock->shouldReceive('count')->andThrow(new \Exception('Database error'));
        });

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $viewData = $response->viewData();

        $this->assertEquals(0, $viewData['categoryCount']);
        $this->assertEquals(0, $viewData['totalRevenue']);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $viewData['bestSellingProducts']);
    }

    public function test_dashboard_requires_authentication()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_requires_admin_role()
    {
        $regularUser = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($regularUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_dashboard_performs_well_with_large_dataset()
    {
        Category::factory(100)->create();
        Product::factory(500)->create();
        User::factory(1000)->create();
        Order::factory(200)->create();

        $startTime = microtime(true);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $executionTime = microtime(true) - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(2, $executionTime);
    }

    public function test_dashboard_formats_revenue_numbers_correctly()
    {
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 1234567,
            'created_at' => Carbon::now()
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $viewData = $response->viewData();
        $monthlyRevenueChart = $viewData['monthlyRevenueChart'];

        $currentMonthRevenue = end($monthlyRevenueChart)['revenue'];
        $this->assertStringContains('.', $currentMonthRevenue);
    }
}