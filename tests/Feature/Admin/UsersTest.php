<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order\Order;
use App\Models\Cart\Cart;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UsersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure admin images not needed but guard exists
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_users_list(): void
    {
        // Only users with role 'user'
        User::factory()->count(3)->create(['role' => 'user']);
        User::factory()->create(['role' => 'admin']);

        $response = $this->get(route('admin.user')); // GET /admin/user

        $response->assertStatus(200)
                 ->assertViewIs('admin.users.index')
                 ->assertViewHas('users')
                 ->assertSee(User::where('role','user')->first()->name);
    }

    public function test_admin_can_view_create_user_form(): void
    {
        $response = $this->get(route('admin.user.create'));

        $response->assertStatus(200)
                 ->assertViewIs('admin.users.create');
    }

    public function test_admin_can_store_multiple_users(): void
    {
        $data = [
            'users' => [
                ['name'=>'UserA','email'=>'a@example.com','password'=>'secret'],
                ['name'=>'UserB','email'=>'b@example.com','password'=>'secret'],
            ],
        ];

        $response = $this->post(route('admin.user.store'), $data);

        $response->assertRedirect(route('admin.user'))
                 ->assertSessionHas('success', 'Tạo tài khoản thành công!');

        $this->assertDatabaseHas('users', ['email'=>'a@example.com','role'=>'user']);
        $this->assertDatabaseHas('users', ['email'=>'b@example.com','role'=>'user']);
    }

    public function test_admin_can_view_edit_user_form(): void
    {
        $user = User::factory()->create(['role'=>'user']);

        $response = $this->get(route('admin.user.edit', $user->id));

        $response->assertStatus(200)
                 ->assertViewIs('admin.users.edit')
                 ->assertViewHas('user')
                 ->assertSee($user->email);
    }

    public function test_admin_can_update_user_without_password(): void
    {
        $user = User::factory()->create(['role'=>'user']);
        $data = ['name'=>'NewName','email'=>'new@example.com'];

        $response = $this->put(route('admin.user.update', $user->id), $data);

        $response->assertRedirect(route('admin.user'))
                 ->assertSessionHas('success', 'Cập nhật tài khoản thành công!');

        $this->assertDatabaseHas('users', ['id'=>$user->id,'name'=>'NewName','email'=>'new@example.com']);
    }

    public function test_admin_can_update_user_with_password(): void
    {
        $user = User::factory()->create(['role'=>'user']);
        $data = ['name'=>'NewName','email'=>'new2@example.com','password'=>'newpass'];

        $response = $this->put(route('admin.user.update', $user->id), $data);

        $response->assertRedirect(route('admin.user'))
                 ->assertSessionHas('success', 'Cập nhật tài khoản thành công!');

        $this->assertTrue(
            \Hash::check('newpass', $user->fresh()->password)
        );
    }

    public function test_admin_can_delete_users_and_handle_ajax(): void
    {
        $u1 = User::factory()->create(['role'=>'user']);
        $u2 = User::factory()->create(['role'=>'user']);

        // normal request
        $response = $this->delete(route('admin.user.destroy'), ['ids'=>[$u1->id]]);
        $response->assertRedirect(route('admin.user'))
                 ->assertSessionHas('success', 'Xóa tài khoản thành công!');
        $this->assertDeleted('users',['id'=>$u1->id]);

        // AJAX request
        $response2 = $this->deleteJson(route('admin.user.destroy'), ['ids'=>$u2->id]);
        $response2->assertStatus(200)->assertJson(['success'=>true]);
        $this->assertDeleted('users',['id'=>$u2->id]);
    }

    public function test_admin_can_view_user_detail_with_orders_and_carts(): void
    {
        $user = User::factory()->create(['role'=>'user']);
        Order::factory()->for($user,'order')->count(2)->create();
        Cart::factory()->for($user,'cart')->count(3)->create();

        $response = $this->get(route('admin.user.show', $user->id));
        $response->assertStatus(200)
                 ->assertViewIs('admin.users.show')
                 ->assertViewHasAll(['user','orders','carts']);
    }

    public function test_admin_can_search_users_and_return_html(): void
    {
        User::factory()->create(['role'=>'user','name'=>'Alice','email'=>'alice@example.com']);
        User::factory()->create(['role'=>'user','name'=>'Bob','email'=>'bob@example.com']);

        $resp = $this->getJson(route('admin.user.search', ['query'=>'Alice']));
        $resp->assertStatus(200)->assertJsonStructure(['html']);
        $html = $resp->json('html');
        $this->assertStringContainsString('Alice', $html);
        $this->assertStringNotContainsString('Bob', $html);
    }

    public function test_non_admin_cannot_access_user_routes(): void
    {
        auth()->logout();
        $user = User::factory()->create(['role'=>'user']);
        $this->actingAs($user);
        $routes = [route('admin.user'), route('admin.user.create'), route('admin.user.store')];
        foreach($routes as $route) {
            $this->get($route)->assertStatus(403);
        }
    }
}
