<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Product\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoriesControllerFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => 'admin'
        ]);
        Storage::fake('public');
    }

    public function test_index_displays_paginated_categories()
    {
        Category::factory()->count(25)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.category'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories');

        $categories = $response->viewData('categories');
        $this->assertEquals(20, $categories->perPage());
        $this->assertEquals(25, $categories->total());
        $this->assertEquals(1, $categories->currentPage());

        $response->assertSee('Next');
    }

    public function test_index_second_page_pagination()
    {
        Category::factory()->count(25)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.category', ['page' => 2]));

        $response->assertStatus(200);
        $categories = $response->viewData('categories');
        $this->assertEquals(2, $categories->currentPage());
        $this->assertEquals(5, $categories->count());
    }

    public function test_create_displays_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.category.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
        $response->assertSee('name="name"', false);
        $response->assertSee('name="description"', false);
        $response->assertSee('name="image"', false);
    }

    public function test_store_creates_category_with_valid_data_and_image()
    {
        Storage::fake('public');
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $uploadedFile = UploadedFile::fake()->image('category.jpg', 500, 500);

        $categoryData = [
            'name' => 'Electronics & Gadgets',
            'description' => 'All electronic devices and gadgets',
            'image' => $uploadedFile
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.category.store'), $categoryData);

        $response->assertRedirect(route('admin.category'));
        $response->assertSessionHas('success', 'Category created successfully!');

        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics & Gadgets',
            'description' => 'All electronic devices and gadgets'
        ]);

        $category = Category::where('name', 'Electronics & Gadgets')->first();
        $this->assertNotNull($category->image_path);
        $this->assertNotEquals('base.jpg', $category->image_path);
        $this->assertTrue(file_exists(public_path('images/categories/' . $category->image_path)));
    }

    public function test_store_creates_category_without_image_uses_default()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $categoryData = [
            'name' => 'Books & Literature',
            'description' => 'All kinds of books'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.category.store'), $categoryData);

        $response->assertRedirect(route('admin.category'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Books & Literature',
            'image_path' => 'base.jpg'
        ]);
    }

    public function test_store_validation_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.category.store'), [
                'description' => 'Test description'
            ]);

        $response->assertSessionHasErrors(['name']);

        $existingCategory = Category::factory()->create(['name' => 'Existing Category']);

        $response = $this->actingAs($this->user)
            ->post(route('admin.category.store'), [
                'name' => 'Existing Category',
                'description' => 'Test description'
            ]);

        $response->assertSessionHasErrors(['name']);

        $largeFile = UploadedFile::fake()->image('large.jpg')->size(3000);

        $response = $this->actingAs($this->user)
            ->post(route('admin.category.store'), [
                'name' => 'Test Category',
                'image' => $largeFile
            ]);

        $response->assertSessionHasErrors(['image']);
    }

    public function test_edit_displays_form_with_category_data()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'description' => 'Test Description'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category', $category);
        $response->assertSee('value="' . $category->name . '"', false);
        $response->assertSee($category->description);
    }

    public function test_edit_with_nonexistent_id_returns_404()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.category.edit', 999));

        $response->assertStatus(404);
    }

    public function test_update_category_with_new_data_and_image()
    {
        Storage::fake('public');
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $category = Category::factory()->create([
            'name' => 'Old Category',
            'description' => 'Old Description',
            'image_path' => 'old_image.jpg'
        ]);

        file_put_contents(public_path('images/categories/old_image.jpg'), 'fake content');

        $newImage = UploadedFile::fake()->image('new_image.jpg');

        $updateData = [
            'name' => 'Updated Category Name',
            'description' => 'Updated Category Description',
            'image' => $newImage,
            '_method' => 'PUT'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.category.update', $category->id), $updateData);

        $response->assertRedirect(route('admin.category'));
        $response->assertSessionHas('success', 'Category updated successfully!');

        $updatedCategory = Category::find($category->id);
        $this->assertNotEquals('old_image.jpg', $updatedCategory->image_path);
        $this->assertFalse(file_exists(public_path('images/categories/old_image.jpg')));
    }

    public function test_update_category_without_changing_image()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $category = Category::factory()->create([
            'name' => 'Original Name',
            'description' => 'Original Description',
            'image_path' => 'existing_image.jpg'
        ]);

        $updateData = [
            'name' => 'Updated Name Only',
            'description' => 'Updated Description Only',
            '_method' => 'PUT'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.category.update', $category->id), $updateData);

        $response->assertRedirect(route('admin.category'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name Only',
            'image_path' => 'existing_image.jpg'
        ]);
    }

    public function test_update_validation_unique_name_excludes_current_record()
    {
        $category1 = Category::factory()->create(['name' => 'Category One']);
        $category2 = Category::factory()->create(['name' => 'Category Two']);

        $response = $this->actingAs($this->user)
            ->put(route('admin.category.update', $category1->id), [
                'name' => 'Category One',
                'description' => 'Updated description'
            ]);

        $response->assertRedirect(route('admin.category'));

        $response = $this->actingAs($this->user)
            ->put(route('admin.category.update', $category1->id), [
                'name' => 'Category Two',
                'description' => 'Updated description'
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_destroy_deletes_category_and_image_file()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');
        Log::shouldReceive('info')->once()->with('Image deleted successfully.');

        $category = Category::factory()->create([
            'name' => 'Category To Delete',
            'image_path' => 'delete_me.jpg'
        ]);

        $imagePath = public_path('images/categories/delete_me.jpg');
        file_put_contents($imagePath, 'fake image content');

        $response = $this->actingAs($this->user)
            ->delete(route('admin.category.destroy', $category->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);

        $this->assertFalse(file_exists($imagePath));
    }

    public function test_destroy_category_without_image()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $category = Category::factory()->create([
            'name' => 'No Image Category',
            'image_path' => null
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.category.destroy', $category->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    public function test_destroy_when_image_file_not_exists()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $category = Category::factory()->create([
            'image_path' => 'nonexistent.jpg'
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.category.destroy', $category->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    public function test_search_returns_filtered_categories()
    {
        Category::factory()->create(['name' => 'Electronics', 'description' => 'Tech gadgets']);
        Category::factory()->create(['name' => 'Books', 'description' => 'Reading materials']);
        Category::factory()->create(['name' => 'Electronic Books', 'description' => 'Digital reading']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.search', ['query' => 'Electronic']));

        $response->assertStatus(200);
        $response->assertJson([
            'html' => true
        ], true);

        $responseData = $response->json();
        $this->assertArrayHasKey('html', $responseData);
    }

    public function test_search_with_empty_query_returns_all()
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.search'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['html']);
    }

    public function test_search_by_description()
    {
        Category::factory()->create(['name' => 'Gaming', 'description' => 'Electronic entertainment']);
        Category::factory()->create(['name' => 'Sports', 'description' => 'Physical activities']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.search', ['query' => 'entertainment']));

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertArrayHasKey('html', $responseData);
    }

    public function test_show_returns_view_for_regular_request()
    {
        $category = Category::factory()->create([
            'name' => 'Show Category',
            'description' => 'Category for show test',
            'image_path' => 'show_test.jpg'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.show', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.show');
        $response->assertViewHas('category', $category);
        $response->assertSee($category->name);
        $response->assertSee($category->description);
    }

    public function test_show_returns_json_for_ajax_request()
    {
        $category = Category::factory()->create([
            'name' => 'AJAX Category',
            'description' => 'Category for AJAX test',
            'image_path' => 'ajax_test.jpg'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.show', $category->id), [
                'X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'id' => $category->id,
            'name' => 'AJAX Category',
            'description' => 'Category for AJAX test'
        ]);

        $responseData = $response->json();
        $this->assertStringContains('images/categories/ajax_test.jpg', $responseData['image_path']);
    }

    public function test_show_detects_json_accept_header()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.show', $category->id), [
                'Accept' => 'application/json'
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_show_detects_ajax_query_parameter()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.show', $category->id) . '?ajax=1');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_show_handles_null_description()
    {
        $category = Category::factory()->create([
            'description' => null
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.category.show', $category->id), [
                'Accept' => 'application/json'
            ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals('', $responseData['description']);
    }

    public function test_routes_require_authentication()
    {
        $category = Category::factory()->create();

        $routes = [
            ['GET', route('admin.category')],
            ['GET', route('admin.category.create')],
            ['POST', route('admin.category.store')],
            ['GET', route('admin.category.edit', $category->id)],
            ['PUT', route('admin.category.update', $category->id)],
            ['DELETE', route('admin.category.destroy', $category->id)],
            ['GET', route('admin.category.search')],
            ['GET', route('admin.category.show', $category->id)],
        ];

        foreach ($routes as [$method, $route]) {
            $response = $this->call($method, $route);
            $this->assertContains($response->getStatusCode(), [302, 401]);
        }
    }

    public function test_cache_is_cleared_after_modifications()
    {
        Cache::shouldReceive('forget')->times(3)->with('admin_categories_all');

        $category = Category::factory()->create();

        $this->actingAs($this->user)
            ->post(route('admin.category.store'), [
                'name' => 'Cache Test Category'
            ]);

        $this->actingAs($this->user)
            ->put(route('admin.category.update', $category->id), [
                'name' => 'Updated Cache Test'
            ]);

        $this->actingAs($this->user)
            ->delete(route('admin.category.destroy', $category->id));
    }

    public function test_complete_category_lifecycle()
    {
        Storage::fake('public');
        Cache::shouldReceive('forget')->times(2)->with('admin_categories_all');
        Log::shouldReceive('info')->once();

        $createResponse = $this->actingAs($this->user)
            ->post(route('admin.category.store'), [
                'name' => 'Lifecycle Test Category',
                'description' => 'Testing complete lifecycle',
                'image' => UploadedFile::fake()->image('lifecycle.jpg')
            ]);

        $createResponse->assertRedirect(route('admin.category'));

        $category = Category::where('name', 'Lifecycle Test Category')->first();
        $this->assertNotNull($category);

        $showResponse = $this->actingAs($this->user)
            ->get(route('admin.category.show', $category->id));

        $showResponse->assertStatus(200);
        $showResponse->assertSee('Lifecycle Test Category');

        $updateResponse = $this->actingAs($this->user)
            ->put(route('admin.category.update', $category->id), [
                'name' => 'Updated Lifecycle Category',
                'description' => 'Updated description'
            ]);

        $updateResponse->assertRedirect(route('admin.category'));

        $deleteResponse = $this->actingAs($this->user)
            ->delete(route('admin.category.destroy', $category->id));

        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson(['success' => true]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }
}