<?php

namespace Tests\Feature\Admin;

use App\Models\Address;
use App\Models\Order\Order;
use App\Models\Post;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardControllerFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'admin',
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
        // Create test data with specific counts
        Category::factory(3)->create();

        $category = Category::factory()->create();
        Product::factory(5)->create(['category_id' => $category->id]);

        // Create users and ensure they're used in posts (not creating new ones)
        $users = User::factory(7)->create();
        Post::factory(4)->create(['user_id' => $users[0]->id]);

        Order::factory(6)->create([
            'user_id' => $users[1]->id,
            'address_id' => Address::factory()->create(['user_id' => $users[1]->id])->id,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $this->assertEquals(4, $response->viewData('categoryCount')); // 3 + 1 extra category
        $this->assertEquals(5, $response->viewData('productCount'));
        $this->assertEquals(8, $response->viewData('userCount')); // 7 + 1 admin user
        $this->assertEquals(4, $response->viewData('postCount'));
        $this->assertEquals(6, $response->viewData('orderCount'));
    }

    public function test_dashboard_calculates_revenue_correctly()
    {
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 100000,
            'created_at' => Carbon::now(),
        ]);

        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 200000,
            'created_at' => Carbon::now(),
        ]);

        Order::factory()->create([
            'status' => 'pending',
            'total_price' => 50000,
            'created_at' => Carbon::now(),
        ]);

        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 75000,
            'created_at' => Carbon::now()->subMonth(),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        // Total revenue: 100000 + 200000 + 75000 = 375000
        $this->assertEquals(375000, $response->viewData('totalRevenue'));

        // Monthly revenue = 300000
        $this->assertEquals(300000, $response->viewData('monthlyRevenue'));
    }

    public function test_dashboard_calculates_product_statistics_correctly()
    {
        $categories = Category::factory(2)->create();
        Product::factory(3)->create(['stock_quantity' => 10, 'category_id' => $categories->first()->id]);
        Product::factory(2)->create(['stock_quantity' => 0, 'category_id' => $categories->last()->id]);
        Product::factory(1)->create(['stock_quantity' => -1, 'category_id' => $categories->first()->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $this->assertEquals(3, $response->viewData('activeProducts'));
        $this->assertEquals(3, $response->viewData('outOfStockProducts'));
    }

    public function test_dashboard_shows_best_selling_products()
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['view_count' => 100, 'category_id' => $category->id]);
        $product2 = Product::factory()->create(['view_count' => 200, 'category_id' => $category->id]);
        $product3 = Product::factory()->create(['view_count' => 50, 'category_id' => $category->id]);
        Product::factory(2)->create(['view_count' => 10, 'category_id' => $category->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $bestSellingProducts = $response->viewData('bestSellingProducts');

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
            'created_at' => Carbon::now(),
        ]);

        $olderOrder = Order::factory()->create([
            'user_id' => $user->id,
            'total_price' => 50000,
            'created_at' => Carbon::now()->subDays(5),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $recentOrders = $response->viewData('recentOrders');

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

        $this->assertEquals(4, $response->viewData('newUsersThisMonth')); // 3 + 1 admin user
    }

    public function test_dashboard_shows_order_status_distribution()
    {
        Order::factory(2)->create(['status' => 'pending']);
        Order::factory(3)->create(['status' => 'completed']);
        Order::factory(1)->create(['status' => 'cancelled']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $orderStatusStats = $response->viewData('orderStatusStats');

        $this->assertEquals(2, $orderStatusStats['pending']);
        $this->assertEquals(3, $orderStatusStats['completed']);
        $this->assertEquals(1, $orderStatusStats['cancelled']);
    }

    public function test_dashboard_generates_monthly_revenue_chart_data()
    {
        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 100000,
            'created_at' => Carbon::now(),
        ]);

        Order::factory()->create([
            'status' => 'completed',
            'total_price' => 200000,
            'created_at' => Carbon::now()->subMonth(),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $monthlyRevenueChart = $response->viewData('monthlyRevenueChart');

        $this->assertCount(6, $monthlyRevenueChart);
        $this->assertArrayHasKey('month', $monthlyRevenueChart[0]);
        $this->assertArrayHasKey('revenue', $monthlyRevenueChart[0]);
    }

    public function test_dashboard_shows_low_stock_products()
    {
        $category = Category::factory()->create();
        $lowStock1 = Product::factory()->create(['stock_quantity' => 5, 'category_id' => $category->id]);
        $lowStock2 = Product::factory()->create(['stock_quantity' => 10, 'category_id' => $category->id]);
        Product::factory()->create(['stock_quantity' => 15, 'category_id' => $category->id]);
        Product::factory()->create(['stock_quantity' => 0, 'category_id' => $category->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $lowStockProducts = $response->viewData('lowStockProducts');

        $this->assertCount(2, $lowStockProducts);
        $this->assertEquals($lowStock1->id, $lowStockProducts->first()->id);
    }

    public function test_dashboard_handles_database_exceptions_gracefully()
    {
        DB::statement('DROP TABLE IF EXISTS temp_categories');

        $this->mock(\App\Models\Product\Category::class, function ($mock) {
            $mock->shouldReceive('count')->andThrow(new \Exception('Database error'));
        });

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $this->assertEquals(0, $response->viewData('categoryCount'));
        $this->assertEquals(0, $response->viewData('totalRevenue'));
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $response->viewData('bestSellingProducts'));
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
            'created_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $monthlyRevenueChart = $response->viewData('monthlyRevenueChart');

        $currentMonthRevenue = end($monthlyRevenueChart)['revenue'];
        $this->assertStringContainsString('.', $currentMonthRevenue);
    }
}
