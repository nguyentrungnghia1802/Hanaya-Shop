<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoriesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Prepare fake storage and public/images directory
        Storage::fake('public');
        if (!file_exists(public_path('images'))) {
            mkdir(public_path('images'), 0777, true);
        }

        // Create an admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_categories_list(): void
    {
        Category::factory()->count(5)->create();

        $response = $this->get(route('admin.category')); // GET /admin/category

        $response->assertStatus(200)
                 ->assertViewIs('admin.categories.index')
                 ->assertViewHas('categories')
                 ->assertSee(Category::first()->name);
    }

    public function test_admin_can_view_create_category_form(): void
    {
        $response = $this->get(route('admin.category.create'));

        $response->assertStatus(200)
                 ->assertViewIs('admin.categories.create');
    }

    public function test_admin_can_create_category_with_valid_data(): void
    {
        $file = UploadedFile::fake()->image('cat.jpg');
        $data = [
            'name'        => 'New Category',
            'description' => 'Some description',
            'image'       => $file,
        ];

        $response = $this->post(route('admin.category.store'), $data);

        $response->assertRedirect(route('admin.category'))
                 ->assertSessionHas('success', 'Category created successfully!');

        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
        $filePath = public_path('images/' . Category::first()->image_path);
        $this->assertFileExists($filePath);
    }

    public function test_admin_can_view_edit_category_form(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.category.edit', $category->id));

        $response->assertStatus(200)
                 ->assertViewIs('admin.categories.edit')
                 ->assertViewHas('category')
                 ->assertSee($category->name);
    }

    public function test_admin_can_update_category_with_valid_data(): void
    {
        $category = Category::factory()->create(['image_path' => 'base.jpg']);
        $file = UploadedFile::fake()->image('upd.jpg');
        $data = [
            'name'        => 'Updated Category',
            'description' => 'Updated desc',
            'image'       => $file,
        ];

        $response = $this->put(route('admin.category.update', $category->id), $data);

        $response->assertRedirect(route('admin.category'))
                 ->assertSessionHas('success', 'Category updated successfully!');

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Category']);
        $filePath = public_path('images/' . Category::find($category->id)->image_path);
        $this->assertFileExists($filePath);
    }

    public function test_admin_can_delete_category_and_remove_image(): void
    {
        $category = Category::factory()->create(['image_path' => 'base.jpg']);
        // Create dummy file
        file_put_contents(public_path('images/base.jpg'), 'dummy');

        $response = $this->deleteJson(route('admin.category.destroy', $category->id));

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
        $this->assertFileDoesNotExist(public_path('images/base.jpg'));
    }

    public function test_admin_can_search_categories_and_return_html(): void
    {
        Category::factory()->create(['name' => 'Alpha Category']);
        Category::factory()->create(['name' => 'Beta Category']);

        $resp = $this->getJson(route('admin.category.search', ['query' => 'Alpha']));
        $resp->assertStatus(200)
             ->assertJsonStructure(['html']);

        $html = $resp->json('html');
        $this->assertStringContainsString('Alpha Category', $html);
        $this->assertStringNotContainsString('Beta Category', $html);
    }

    public function test_admin_can_view_category_detail_and_json(): void
    {
        $category = Category::factory()->create(['image_path' => 'base.jpg']);

        // HTML view
        $resp = $this->get(route('admin.category.show', $category->id));
        $resp->assertStatus(200)
             ->assertViewIs('admin.categories.show')
             ->assertViewHas('category');

        // AJAX JSON
        $json = $this->getJson(route('admin.category.show', $category->id) . '?ajax=1');
        $json->assertStatus(200)
             ->assertJsonStructure(['success','id','name','description','image_path']);
    }

    public function test_non_admin_cannot_access_category_routes(): void
    {
        auth()->logout();
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $routes = [
            route('admin.category'),
            route('admin.category.create'),
            route('admin.category.store'),
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertStatus(403);
        }
    }
}
