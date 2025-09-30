<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Order\Order;
use App\Models\Cart\Cart;
use App\Models\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersControllerFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@test.com'
        ]);
        
        $this->regularUser = User::factory()->create([
            'role' => 'user',
            'name' => 'Regular User',
            'email' => 'user@test.com'
        ]);
    }

    public function test_index_displays_users_list_with_pagination()
    {
        User::factory(25)->create(['role' => 'user']);

        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
        
        $users = $response->viewData('users');
        $this->assertInstanceOf(LengthAwarePaginator::class, $users);
        $this->assertEquals(20, $users->perPage());
        
        $userIds = $users->pluck('id')->toArray();
        $this->assertNotContains($this->adminUser->id, $userIds);
    }

    public function test_create_displays_form()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
    }

    public function test_store_creates_multiple_users_successfully()
    {
        $usersData = [
            'users' => [
                [
                    'name' => 'User One',
                    'email' => 'user1@test.com',
                    'password' => 'password123',
                    'role' => 'user'
                ],
                [
                    'name' => 'User Two',
                    'email' => 'user2@test.com',
                    'password' => 'password456',
                    'role' => 'admin'
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
                         ->post(route('admin.user.store'), $usersData);

        $response->assertRedirect(route('admin.user'));
        $response->assertSessionHas('success', 'Account created successfully!');

        $this->assertDatabaseHas('users', [
            'name' => 'User One',
            'email' => 'user1@test.com',
            'role' => 'user'
        ]);
        
        $this->assertDatabaseHas('users', [
            'name' => 'User Two',
            'email' => 'user2@test.com',
            'role' => 'admin'
        ]);

        $user1 = User::where('email', 'user1@test.com')->first();
        $this->assertTrue(Hash::check('password123', $user1->password));
    }

    public function test_store_validates_user_data()
    {
        $invalidData = [
            'users' => [
                [
                    'name' => '', // Required field empty
                    'email' => 'invalid-email', // Invalid email
                    'password' => '123', // Too short
                    'role' => 'invalid_role' // Invalid role
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
                         ->post(route('admin.user.store'), $invalidData);

        $response->assertSessionHasErrors([
            'users.0.name',
            'users.0.email',
            'users.0.password',
            'users.0.role'
        ]);
    }

    public function test_store_validates_unique_email()
    {
        $duplicateEmailData = [
            'users' => [
                [
                    'name' => 'Test User',
                    'email' => $this->regularUser->email, 
                    'password' => 'password123',
                    'role' => 'user'
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
                         ->post(route('admin.user.store'), $duplicateEmailData);

        $response->assertSessionHasErrors(['users.0.email']);
    }

    public function test_store_clears_cache_after_creating_users()
    {
        Cache::put('admin_users_all', 'test_data', 600);
        $this->assertTrue(Cache::has('admin_users_all'));

        $usersData = [
            'users' => [
                [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => 'password123',
                    'role' => 'user'
                ]
            ]
        ];

        $this->actingAs($this->adminUser)
             ->post(route('admin.user.store'), $usersData);

        $this->assertFalse(Cache::has('admin_users_all'));
    }

    public function test_edit_displays_user_form()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.edit', $this->regularUser->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas('user');
        
        $user = $response->viewData('user');
        $this->assertEquals($this->regularUser->id, $user->id);
    }

    public function test_edit_prevents_self_editing()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.edit', $this->adminUser->id));

        $response->assertStatus(403);
    }

    public function test_edit_returns_404_for_nonexistent_user()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.edit', 99999));

        $response->assertStatus(404);
    }

    public function test_update_user_successfully()
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'role' => 'admin',
            'password' => 'newpassword123'
        ];

        $response = $this->actingAs($this->adminUser)
                         ->put(route('admin.user.update', $this->regularUser->id), $updateData);

        $response->assertRedirect(route('admin.user'));
        $response->assertSessionHas('success', 'Account updated successfully!');

        // Verify database updated
        $this->assertDatabaseHas('users', [
            'id' => $this->regularUser->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'role' => 'admin'
        ]);

        // Verify password updated
        $updatedUser = User::find($this->regularUser->id);
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
    }

    public function test_update_preserves_password_when_empty()
    {
        $originalPassword = $this->regularUser->password;
        
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'role' => 'admin',
            'password' => '' // Empty password
        ];

        $this->actingAs($this->adminUser)
             ->put(route('admin.user.update', $this->regularUser->id), $updateData);

        $updatedUser = User::find($this->regularUser->id);
        $this->assertEquals($originalPassword, $updatedUser->password);
    }

    public function test_update_prevents_self_updating()
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'role' => 'user'
        ];

        $response = $this->actingAs($this->adminUser)
                         ->put(route('admin.user.update', $this->adminUser->id), $updateData);

        $response->assertStatus(403);
    }

    public function test_update_validates_data()
    {
        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'role' => 'invalid_role',
            'password' => '123'
        ];

        $response = $this->actingAs($this->adminUser)
                         ->put(route('admin.user.update', $this->regularUser->id), $invalidData);

        $response->assertSessionHasErrors(['name', 'email', 'role', 'password']);
    }

    public function test_destroy_multiple_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        
        $deleteData = [
            'ids' => [$user1->id, $user2->id, $user3->id]
        ];

        $response = $this->actingAs($this->adminUser)
                         ->delete(route('admin.user.destroy.multiple'), $deleteData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Selected accounts deleted successfully!'
        ]);

        // Verify users deleted
        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseMissing('users', ['id' => $user2->id]);
        $this->assertDatabaseMissing('users', ['id' => $user3->id]);
    }

    public function test_destroy_prevents_self_deletion()
    {
        $user1 = User::factory()->create();
        
        $deleteData = [
            'ids' => [$user1->id, $this->adminUser->id] 
        ];

        $this->actingAs($this->adminUser)
             ->delete(route('admin.user.destroy.multiple'), $deleteData);

        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseHas('users', ['id' => $this->adminUser->id]);
    }

    public function test_destroy_returns_json_for_ajax_request()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($this->adminUser)
                         ->deleteJson(route('admin.user.destroy.multiple'), [
                             'ids' => [$user->id]
                         ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_destroy_single_user()
    {
        app()->setLocale('vi');
        
        $user = User::factory()->create();

        $response = $this->actingAs($this->adminUser)
                         ->delete(route('admin.user.destroy', $user->id));

        $response->assertRedirect(route('admin.user'));
        $response->assertSessionHas('success', 'Xóa tài khoản thành công!');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_destroy_single_prevents_self_deletion()
    {
        $response = $this->actingAs($this->adminUser)
                         ->delete(route('admin.user.destroy', $this->adminUser->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $this->adminUser->id]);
    }

    public function test_destroy_single_returns_json_for_ajax()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->adminUser)
                         ->deleteJson(route('admin.user.destroy', $user->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_show_displays_user_details_with_relationships()
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $this->regularUser->id]);
        $cart = Cart::factory()->create([
            'user_id' => $this->regularUser->id,
            'product_id' => $product->id
        ]);

        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.show', $this->regularUser->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
        $response->assertViewHasAll(['user', 'orders', 'carts']);

        $this->assertEquals($this->regularUser->id, $response->viewData('user')->id);
        $this->assertCount(1, $response->viewData('orders'));
        $this->assertCount(1, $response->viewData('carts'));
    }

    public function test_search_finds_users_by_name_and_email()
    {
        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
        
        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com'
        ]);

        // Search by name
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.search', ['query' => 'John']));

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertArrayHasKey('html', $responseData);
        $this->assertStringContainsString('John Doe', $responseData['html']);

        // Search by email
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.search', ['query' => 'jane@example.com']));

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertArrayHasKey('html', $responseData);
        $this->assertStringContainsString('Jane Smith', $responseData['html']);
    }

    public function test_search_excludes_current_admin()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.search', ['query' => $this->adminUser->name]));

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertStringNotContainsString($this->adminUser->name, $data['html']);
    }

    public function test_search_returns_no_users_found_message()
    {
        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.search', ['query' => 'nonexistentuser']));

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertArrayHasKey('html', $responseData);
        $this->assertStringContainsString('No users found.', $responseData['html']);
    }

    public function test_search_returns_properly_formatted_html()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user'
        ]);

        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.search', ['query' => 'Test']));

        $response->assertStatus(200);
        $data = $response->json();
        $html = $data['html'];

        $this->assertStringContainsString('<tr>', $html);
        $this->assertStringContainsString('Test User', $html);
        $this->assertStringContainsString('test@example.com', $html);
        $this->assertStringContainsString('Edit', $html);
        $this->assertStringContainsString('Delete', $html);
        $this->assertStringContainsString('View Details', $html);
    }

    public function test_search_escapes_html_entities()
    {
        $user = User::factory()->create([
            'name' => '<script>alert("xss")</script>',
            'email' => 'test@example.com'
        ]);

        $response = $this->actingAs($this->adminUser)
                         ->get(route('admin.user.search', ['query' => 'script']));

        $response->assertStatus(200);
        $data = $response->json();
        
        // HTML entities should be escaped
        $this->assertStringContainsString('&lt;script&gt;', $data['html']);
        $this->assertStringNotContainsString('<script>', $data['html']);
    }

    public function test_all_operations_clear_cache()
    {
        // Test update clears cache
        Cache::put('admin_users_all', 'test_data', 600);
        
        $this->actingAs($this->adminUser)
             ->put(route('admin.user.update', $this->regularUser->id), [
                 'name' => 'Updated',
                 'email' => 'updated@test.com',
                 'role' => 'user'
             ]);
        
        $this->assertFalse(Cache::has('admin_users_all'));

        // Test destroy clears cache
        Cache::put('admin_users_all', 'test_data', 600);
        $user = User::factory()->create();
        
        $this->actingAs($this->adminUser)
             ->delete(route('admin.user.destroy.multiple'), ['ids' => [$user->id]]);
        
        $this->assertFalse(Cache::has('admin_users_all'));

        // Test destroySingle clears cache
        Cache::put('admin_users_all', 'test_data', 600);
        $user2 = User::factory()->create();
        
        $this->actingAs($this->adminUser)
             ->delete(route('admin.user.destroy', $user2->id));
        
        $this->assertFalse(Cache::has('admin_users_all'));
    }

    public function test_all_routes_require_authentication()
    {
        $user = User::factory()->create();
        
        $routes = [
            ['GET', route('admin.user')],
            ['GET', route('admin.user.create')],
            ['POST', route('admin.user.store')],
            ['GET', route('admin.user.edit', $user->id)],
            ['PUT', route('admin.user.update', $user->id)],
            ['DELETE', route('admin.user.destroy.multiple')],
            ['DELETE', route('admin.user.destroy', $user->id)],
            ['GET', route('admin.user.show', $user->id)],
            ['GET', route('admin.user.search')]
        ];

        foreach ($routes as $route) {
            $response = $this->call($route[0], $route[1]);
            $this->assertTrue(
                $response->isRedirect() || $response->status() === 401,
                "Route {$route[1]} should require authentication"
            );
        }
    }

    public function test_routes_require_admin_authorization()
    {
        $regularUser = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($regularUser)
                         ->get(route('admin.user'));
        
        $this->assertTrue(
            $response->status() === 403 || $response->isRedirect(),
            'Regular user should not access admin routes'
        );
    }
}