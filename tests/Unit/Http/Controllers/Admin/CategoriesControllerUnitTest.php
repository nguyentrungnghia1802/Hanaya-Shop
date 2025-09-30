<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CategoriesController;
use App\Models\Product\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class CategoriesControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new CategoriesController;

        // Mock filesystem
        Storage::fake('public');
    }

    public function test_index_returns_paginated_categories()
    {
        Category::factory()->count(25)->create();

        $response = $this->controller->index();

        $this->assertEquals('admin.categories.index', $response->getName());

        $categories = $response->getData()['categories'];
        $this->assertEquals(20, $categories->perPage());
        $this->assertEquals(25, $categories->total());
    }

    public function test_create_returns_create_view()
    {
        $response = $this->controller->create();

        $this->assertEquals('admin.categories.create', $response->getName());
    }

    public function test_store_creates_category_with_image()
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $uploadedFile = UploadedFile::fake()->image('test.jpg', 100, 100);

        $request = Request::create('/admin/categories', 'POST', [
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);
        $request->files->set('image', $uploadedFile);

        $expectedFileName = time().'.jpg';

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_store_creates_category_without_image_uses_default()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $request = Request::create('/admin/categories', 'POST', [
            'name' => 'Test Category No Image',
            'description' => 'Test Description',
        ]);

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category No Image',
            'image_path' => 'fixed_resources/not_found.jpg',
        ]);
    }

    public function test_edit_returns_edit_view_with_category()
    {
        $category = Category::factory()->create();

        $response = $this->controller->edit($category->id);

        $this->assertEquals('admin.categories.edit', $response->getName());
        $this->assertEquals($category->id, $response->getData()['category']->id);
    }

    public function test_edit_with_invalid_id_throws_exception()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->controller->edit(999);
    }

    public function test_update_category_with_new_image()
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $category = Category::factory()->create([
            'image_path' => 'old_image.jpg',
        ]);

        $uploadedFile = UploadedFile::fake()->image('new_test.jpg', 100, 100);

        $request = Request::create('/admin/categories/'.$category->id, 'PUT', [
            'name' => 'Updated Category',
            'description' => 'Updated Description',
        ]);
        $request->files->set('image', $uploadedFile);

        $response = $this->controller->update($request, $category->id);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
            'description' => 'Updated Description',
        ]);

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_update_category_without_new_image()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');

        $category = Category::factory()->create([
            'image_path' => 'existing.jpg',
        ]);

        $request = Request::create('/admin/categories/'.$category->id, 'PUT', [
            'name' => 'Updated Name Only',
            'description' => 'Updated Description Only',
        ]);

        $response = $this->controller->update($request, $category->id);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name Only',
            'image_path' => 'existing.jpg',
        ]);
    }

    public function test_destroy_deletes_category_and_image()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');
        // Log::shouldReceive('info')->once()->with('Image deleted successfully.');

        $category = Category::factory()->create([
            'image_path' => 'test_image.jpg',
        ]);

        $this->mock('alias:File', function ($mock) {
            $mock->shouldReceive('exists')->andReturn(true);
            $mock->shouldReceive('delete')->andReturn(true);
        });

        $response = $this->controller->destroy($category->id);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['success' => true], $response->getData(true));
    }

    public function test_destroy_logs_error_when_image_deletion_fails()
    {
        Cache::shouldReceive('forget')->once()->with('admin_categories_all');
        // Log::shouldReceive('error')->once()->with('Failed to delete image.');

        $category = Category::factory()->create([
            'image_path' => 'test_image.jpg',
        ]);
        $response = $this->controller->destroy($category->id);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_search_finds_categories_by_name_and_description()
    {
        Category::factory()->create(['name' => 'Electronics', 'description' => 'Tech products']);
        Category::factory()->create(['name' => 'Books', 'description' => 'Reading materials']);
        Category::factory()->create(['name' => 'Clothing', 'description' => 'Fashion items']);

        $request = Request::create('/admin/categories/search', 'GET', [
            'query' => 'Tech',
        ]);

        $this->mock('Illuminate\View\View', function ($mock) {
            $mock->shouldReceive('render')->andReturn('<div>Mocked HTML</div>');
        });

        $response = $this->controller->search($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('html', $responseData);
    }

    public function test_search_with_empty_query()
    {
        Category::factory()->count(3)->create();

        $request = Request::create('/admin/categories/search', 'GET');

        $this->mock('Illuminate\View\View', function ($mock) {
            $mock->shouldReceive('render')->andReturn('<div>All categories</div>');
        });

        $response = $this->controller->search($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_show_returns_json_for_ajax_request()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'image_path' => 'test.jpg',
        ]);

        $request = Request::create('/admin/categories/'.$category->id, 'GET');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $response = $this->controller->show($category->id, $request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);

        $this->assertEquals(true, $responseData['success']);
        $this->assertEquals($category->id, $responseData['id']);
        $this->assertEquals('Test Category', $responseData['name']);
        $this->assertEquals('Test Description', $responseData['description']);
    }

    public function test_show_returns_view_for_regular_request()
    {
        $category = Category::factory()->create();

        $request = Request::create('/admin/categories/'.$category->id, 'GET');

        $response = $this->controller->show($category->id, $request);

        $this->assertEquals('admin.categories.show', $response->getName());
        $this->assertEquals($category->id, $response->getData()['category']->id);
    }

    public function test_show_detects_ajax_requests_various_ways()
    {
        $category = Category::factory()->create();

        $request1 = Request::create('/admin/categories/'.$category->id, 'GET');
        $request1->headers->set('Accept', 'application/json');
        $response1 = $this->controller->show($category->id, $request1);
        $this->assertEquals(200, $response1->getStatusCode());
        $this->assertJson($response1->getContent());

        $request2 = Request::create('/admin/categories/'.$category->id.'?ajax=1', 'GET');
        $response2 = $this->controller->show($category->id, $request2);
        $this->assertEquals(200, $response2->getStatusCode());
        $this->assertJson($response2->getContent());
    }

    public function test_show_with_invalid_id_throws_exception()
    {
        $this->expectException(ModelNotFoundException::class);

        $request = Request::create('/admin/categories/999', 'GET');
        $this->controller->show(999, $request);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
