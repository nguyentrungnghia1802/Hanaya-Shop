<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\UsersController;
use App\Models\Cart\Cart;
use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class UsersControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected $currentUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UsersController;

        $this->currentUser = User::factory()->create([
            'id' => 1,
            'role' => 'admin',
            'name' => 'Admin User',
        ]);

        Auth::shouldReceive('id')->andReturn($this->currentUser->id);
    }

    public function test_index_returns_paginated_users_excluding_current_user()
    {
        User::factory()->count(25)->create();

        $response = $this->controller->index();

        $this->assertEquals('admin.users.index', $response->getName());

        $users = $response->getData()['users'];

        $this->assertEquals(20, $users->perPage());

        $this->assertEquals(25, $users->total()); // Exclude current user

        $userIds = $users->pluck('id')->toArray();
        $this->assertNotContains($this->currentUser->id, $userIds);
    }

    public function test_create_returns_create_view()
    {
        $response = $this->controller->create();

        $this->assertEquals('admin.users.create', $response->getName());
    }

    public function test_store_creates_multiple_users_successfully()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $request = Request::create('/admin/users', 'POST', [
            'users' => [
                [
                    'name' => 'User One',
                    'email' => 'user1@example.com',
                    'password' => 'password123',
                    'role' => 'user',
                ],
                [
                    'name' => 'User Two',
                    'email' => 'user2@example.com',
                    'password' => 'password456',
                    'role' => 'admin',
                ],
            ],
        ]);

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('users', [
            'name' => 'User One',
            'email' => 'user1@example.com',
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'user1@example.com')->first();
        $this->assertTrue(password_verify('password123', $user->password));

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_store_validation_requires_users_array()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = Request::create('/admin/users', 'POST', [
            'users' => 'not_an_array',
        ]);

        $this->controller->store($request);
    }

    public function test_store_validation_email_must_be_unique()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = Request::create('/admin/users', 'POST', [
            'users' => [
                [
                    'name' => 'Test User',
                    'email' => 'existing@example.com', // Duplicate email
                    'password' => 'password123',
                    'role' => 'user',
                ],
            ],
        ]);

        $this->controller->store($request);
    }

    public function test_store_validation_role_must_be_valid()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = Request::create('/admin/users', 'POST', [
            'users' => [
                [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => 'password123',
                    'role' => 'invalid_role', // Invalid role
                ],
            ],
        ]);

        $this->controller->store($request);
    }

    public function test_edit_returns_edit_view_with_user()
    {
        $user = User::factory()->create(['id' => 99]);

        $response = $this->controller->edit(99);

        $this->assertEquals('admin.users.edit', $response->getName());
        $this->assertEquals(99, $response->getData()['user']->id);
    }

    public function test_edit_prevents_editing_current_user()
    {
        $this->expectException(HttpException::class);

        // Try to edit current user (ID = 1)
        $this->controller->edit($this->currentUser->id);
    }

    public function test_edit_throws_exception_for_nonexistent_user()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->controller->edit(999);
    }

    public function test_update_user_successfully()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user = User::factory()->create([
            'id' => 99,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'user',
        ]);

        $request = Request::create('/admin/users/99', 'PUT', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'admin',
            'password' => 'newpassword123',
        ]);

        $response = $this->controller->update($request, 99);

        $this->assertDatabaseHas('users', [
            'id' => 99,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'admin',
        ]);

        $updatedUser = User::find(99);
        $this->assertTrue(password_verify('newpassword123', $updatedUser->password));

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_update_user_without_password_keeps_old_password()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user = User::factory()->create([
            'id' => 99,
            'password' => bcrypt('oldpassword'),
        ]);
        $oldPasswordHash = $user->password;

        $request = Request::create('/admin/users/99', 'PUT', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'user',
            // No password field
        ]);

        $this->controller->update($request, 99);

        $updatedUser = User::find(99);
        $this->assertEquals($oldPasswordHash, $updatedUser->password);
    }

    public function test_update_prevents_updating_current_user()
    {
        $this->expectException(HttpException::class);

        $request = Request::create('/admin/users/1', 'PUT', [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'user',
        ]);

        $this->controller->update($request, $this->currentUser->id);
    }

    public function test_update_validation_email_unique_excludes_current_user()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        // Update user1 with same email - should pass
        $request = Request::create('/admin/users/'.$user1->id, 'PUT', [
            'name' => 'Updated Name',
            'email' => 'user1@example.com', // Same email
            'role' => 'user',
        ]);

        $response = $this->controller->update($request, $user1->id);
        $this->assertEquals(302, $response->getStatusCode());

        // Update user1 with user2's email - should fail
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request2 = Request::create('/admin/users/'.$user1->id, 'PUT', [
            'name' => 'Updated Name',
            'email' => 'user2@example.com', // Different user's email
            'role' => 'user',
        ]);

        $this->controller->update($request2, $user1->id);
    }

    public function test_destroy_deletes_multiple_users()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user1 = User::factory()->create(['id' => 10]);
        $user2 = User::factory()->create(['id' => 11]);
        $user3 = User::factory()->create(['id' => 12]);

        $request = Request::create('/admin/users/destroy', 'DELETE', [
            'ids' => [10, 11, 12],
        ]);

        $response = $this->controller->destroy($request);

        $this->assertDatabaseMissing('users', ['id' => 10]);
        $this->assertDatabaseMissing('users', ['id' => 11]);
        $this->assertDatabaseMissing('users', ['id' => 12]);

        // Expect JSON response (200) not redirect (302)
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
    }

    public function test_destroy_handles_single_id_as_string()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user = User::factory()->create(['id' => 10]);

        $request = Request::create('/admin/users/destroy', 'DELETE', [
            'ids' => '10', // String instead of array
        ]);

        $response = $this->controller->destroy($request);

        $this->assertDatabaseMissing('users', ['id' => 10]);
    }

    public function test_destroy_excludes_current_user_from_deletion()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user = User::factory()->create(['id' => 10]);

        $request = Request::create('/admin/users/destroy', 'DELETE', [
            'ids' => [10, 1], // Include current user ID (1)
        ]);

        $response = $this->controller->destroy($request);

        $this->assertDatabaseMissing('users', ['id' => 10]);

        $this->assertDatabaseHas('users', ['id' => 1]);
    }

    public function test_destroy_returns_json_for_ajax_request()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user = User::factory()->create(['id' => 10]);

        $request = Request::create('/admin/users/destroy', 'DELETE', [
            'ids' => [10],
        ]);
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $response = $this->controller->destroy($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('message', $responseData);
    }

    public function test_destroy_single_deletes_user_successfully()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user = User::factory()->create(['id' => 10]);

        $this->app->instance('request', Request::create('/admin/users/10', 'DELETE'));

        $response = $this->controller->destroySingle(10);

        $this->assertDatabaseMissing('users', ['id' => 10]);

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_destroy_single_prevents_deleting_current_user()
    {
        $this->expectException(HttpException::class);

        $this->controller->destroySingle($this->currentUser->id);
    }

    public function test_destroy_single_returns_json_for_ajax()
    {
        Cache::shouldReceive('forget')->once()->with('admin_users_all');

        $user = User::factory()->create(['id' => 10]);

        $mockRequest = Request::create('/admin/users/10', 'DELETE');
        $mockRequest->headers->set('X-Requested-With', 'XMLHttpRequest');
        $this->app->instance('request', $mockRequest);

        $response = $this->controller->destroySingle(10);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['success' => true], $response->getData(true));
    }

    public function test_show_displays_user_with_orders_and_carts()
    {
        // Create a real user
        $user = User::factory()->create();

        // Create real orders for this user
        $orders = Order::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->controller->show($user->id);

        $this->assertEquals('admin.users.show', $response->getName());

        $viewData = $response->getData();
        $this->assertEquals($user->id, $viewData['user']->id);
        $this->assertCount(2, $viewData['orders']);
        // Cart relationship might be optional, so just check it exists in view data
        $this->assertArrayHasKey('carts', $viewData);
    }

    public function test_search_finds_users_by_name_and_email()
    {
        User::factory()->create([
            'id' => 10,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
        ]);

        User::factory()->create([
            'id' => 11,
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'role' => 'admin',
        ]);

        $request = Request::create('/admin/users/search', 'GET', [
            'query' => 'john',
        ]);

        $response = $this->controller->search($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('html', $responseData);

        $html = $responseData['html'];
        $this->assertStringContainsString('John Doe', $html);
        $this->assertStringContainsString('john@example.com', $html);
        $this->assertStringNotContainsString('Jane Smith', $html);
    }

    public function test_search_with_empty_query_returns_all_users()
    {
        User::factory()->count(3)->create();

        $request = Request::create('/admin/users/search', 'GET');

        $response = $this->controller->search($request);

        $responseData = $response->getData(true);
        $html = $responseData['html'];

        $this->assertStringContainsString('<tr>', $html);
    }

    public function test_search_returns_no_results_message()
    {

        $request = Request::create('/admin/users/search', 'GET', [
            'query' => 'nonexistent',
        ]);

        $response = $this->controller->search($request);

        $responseData = $response->getData(true);
        $html = $responseData['html'];

        $this->assertStringContainsString('No users found', $html);
        $this->assertStringContainsString('colspan="6"', $html);
    }

    public function test_search_html_escapes_user_data()
    {
        User::factory()->create([
            'id' => 10,
            'name' => '<script>alert("xss")</script>',
            'email' => '<b>test@example.com</b>',
            'role' => 'user',
        ]);

        $request = Request::create('/admin/users/search', 'GET', [
            'query' => 'script',
        ]);

        $response = $this->controller->search($request);

        $responseData = $response->getData(true);
        $html = $responseData['html'];

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<b>test@example.com</b>', $html);
        $this->assertStringContainsString('&lt;b&gt;test@example.com&lt;/b&gt;', $html);
    }

    public function test_search_excludes_current_user()
    {
        // Create another user that matches search query
        $otherUser = User::factory()->create([
            'name' => 'Other Admin User',
            'email' => 'other@example.com',
        ]);

        $request = Request::create('/admin/users/search', 'GET', [
            'query' => 'Admin', // Should match both current and other user
        ]);

        $response = $this->controller->search($request);

        $responseData = $response->getData(true);
        $html = $responseData['html'];

        // Should contain other user but exclude current user
        $this->assertStringContainsString('Other Admin User', $html);
        // Make sure current user ID (1) is excluded from results
        $this->assertStringNotContainsString('value="1"', $html);
    }

    public function test_cache_is_cleared_after_modifications()
    {
        Cache::shouldReceive('forget')->times(4)->with('admin_users_all');

        $user = User::factory()->create(['id' => 10]);

        // Test store
        $storeRequest = Request::create('/admin/users', 'POST', [
            'users' => [['name' => 'Test', 'email' => 'test@example.com', 'password' => 'password', 'role' => 'user']],
        ]);
        $this->controller->store($storeRequest);

        // Test update
        $updateRequest = Request::create('/admin/users/10', 'PUT', [
            'name' => 'Updated', 'email' => 'updated@example.com', 'role' => 'user',
        ]);
        $this->controller->update($updateRequest, 10);

        // Test destroy
        $destroyRequest = Request::create('/admin/users/destroy', 'DELETE', ['ids' => [10]]);
        $this->controller->destroy($destroyRequest);

        // Test destroySingle
        $user2 = User::factory()->create(['id' => 11]);
        $this->app->instance('request', Request::create('/admin/users/11', 'DELETE'));
        $this->controller->destroySingle(11);

        // Assert that cache methods were called as expected
        $this->assertTrue(true, 'Cache forget methods were called successfully');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
