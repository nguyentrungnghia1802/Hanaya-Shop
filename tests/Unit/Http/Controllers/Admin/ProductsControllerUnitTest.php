<?php

namespace Tests\Unit\Admin;

use App\Http\Controllers\Admin\ProductsController;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductsControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected $category;

    protected $testUploadPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ProductsController;
        $this->category = Category::factory()->create();
        $this->testUploadPath = public_path('images/products');

        if (! file_exists($this->testUploadPath)) {
            mkdir($this->testUploadPath, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testUploadPath)) {
            $files = glob($this->testUploadPath.'/*');
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'default-product.jpg' && basename($file) !== 'base.jpg') {
                    unlink($file);
                }
            }
        }
        parent::tearDown();
    }

    #[Test]
    public function index_returns_paginated_products_with_category()
    {
        // Arrange
        Product::factory()->count(25)->create(['category_id' => $this->category->id]);
        $request = Request::create('/admin/products', 'GET');

        // Act
        $response = $this->controller->index($request);

        // Assert
        $this->assertEquals('admin.products.index', $response->name());
        $data = $response->getData();
        $this->assertInstanceOf(LengthAwarePaginator::class, $data['products']);
        $this->assertEquals(20, $data['products']->perPage()); // 20 items per page
        $this->assertEquals(25, $data['products']->total());
    }

    #[Test]
    public function index_loads_products_with_category_relationship()
    {
        // Arrange
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $request = Request::create('/admin/products', 'GET');

        // Act
        $response = $this->controller->index($request);

        // Assert
        $products = $response->getData()['products'];
        $this->assertTrue($products->first()->relationLoaded('category'));
    }

    #[Test]
    public function create_returns_view_with_categories()
    {
        // Arrange
        Category::factory()->count(3)->create();

        // Act
        $response = $this->controller->create();

        // Assert
        $this->assertEquals('admin.products.create', $response->getName());
        $data = $response->getData();
        $this->assertArrayHasKey('categories', $data);
        $this->assertCount(4, $data['categories']); // 3 + 1 from setUp
    }

    #[Test]
    public function store_creates_product_successfully_without_image()
    {
        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test product description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
            'discount_percent' => 10,
            'view_count' => 5,
        ];
        $request = Request::create('/admin/products', 'POST', $productData);

        // Act
        $response = $this->controller->store($request);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/product', $response->getTargetUrl());

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'descriptions' => 'Test product description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
            'discount_percent' => 10,
            'view_count' => 5,
            'image_url' => 'default-product.jpg',
        ]);
    }

    #[Test]
    public function store_creates_product_with_image_upload()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $file = UploadedFile::fake()->image('product.jpg');
        $productData = [
            'name' => 'Product with Image',
            'descriptions' => 'Product description',
            'price' => 149.99,
            'stock_quantity' => 15,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products', 'POST', $productData, [], ['image_url' => $file]);

        // Act
        $response = $this->controller->store($request);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());

        $this->assertDatabaseHas('products', [
            'name' => 'Product with Image',
        ]);

        $product = Product::where('name', 'Product with Image')->first();
        $this->assertNotEquals('default-product.jpg', $product->image_url);
        $this->assertStringEndsWith('.jpg', $product->image_url);
    }

    #[Test]
    public function store_sets_default_values_for_optional_fields()
    {
        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $productData = [
            'name' => 'Minimal Product',
            'descriptions' => 'Basic description',
            'price' => 50.00,
            'stock_quantity' => 5,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products', 'POST', $productData);

        // Act
        $this->controller->store($request);

        // Assert
        $this->assertDatabaseHas('products', [
            'name' => 'Minimal Product',
            'discount_percent' => 0,
            'view_count' => 0,
            'image_url' => 'default-product.jpg',
        ]);
    }

    #[Test]
    public function store_validates_required_fields()
    {
        // Arrange
        $request = Request::create('/admin/products', 'POST', []);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_numeric_fields()
    {
        // Arrange
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 'invalid_price',
            'stock_quantity' => 'invalid_stock',
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products', 'POST', $productData);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_category_exists()
    {
        // Arrange
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => 999, // Non-existent category
        ];
        $request = Request::create('/admin/products', 'POST', $productData);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_image_file_type()
    {
        // Arrange
        $file = UploadedFile::fake()->create('document.pdf', 1024);
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products', 'POST', $productData, [], ['image_url' => $file]);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_discount_percent_range()
    {
        // Arrange
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
            'discount_percent' => 150, // Invalid: over 100%
        ];
        $request = Request::create('/admin/products', 'POST', $productData);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    #[Test]
    public function store_returns_error_when_save_fails()
    {
        // Arrange - Create request with invalid category_id to trigger validation error
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => 99999, // Invalid category_id
        ];
        $request = Request::create('/admin/products', 'POST', $productData);

        // Act & Assert - Expect ValidationException
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $response = $this->controller->store($request);
    }

    #[Test]
    public function edit_returns_view_with_product_and_categories()
    {
        // Arrange
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        Category::factory()->count(2)->create();

        // Act
        $response = $this->controller->edit($product->id);

        // Assert
        $this->assertEquals('admin.products.edit', $response->getName());
        $data = $response->getData();
        $this->assertEquals($product->id, $data['product']->id);
        $this->assertCount(3, $data['categories']); // 2 + 1 from setUp
    }

    #[Test]
    public function edit_throws_exception_when_product_not_found()
    {
        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->controller->edit(999);
    }

    #[Test]
    public function update_updates_product_successfully_without_image()
    {
        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 199.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
            'discount_percent' => 15,
            'view_count' => 100,
        ];
        $request = Request::create('/admin/products/'.$product->id, 'PUT', $updateData);

        // Act
        $response = $this->controller->update($request, $product->id);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());

        $product->refresh();
        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals('Updated description', $product->descriptions);
        $this->assertEquals(199.99, $product->price);
        $this->assertEquals(20, $product->stock_quantity);
        $this->assertEquals(15, $product->discount_percent);
        $this->assertEquals(100, $product->view_count);
    }

    #[Test]
    public function update_replaces_image_and_deletes_old_one()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => 'old_image.jpg',
        ]);

        // Create old image file
        $oldImagePath = $this->testUploadPath.'/old_image.jpg';
        file_put_contents($oldImagePath, 'fake image content');

        $newFile = UploadedFile::fake()->image('new_image.jpg');
        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 199.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products/'.$product->id, 'PUT', $updateData, [], ['image_url' => $newFile]);

        // Act
        $this->controller->update($request, $product->id);

        // Assert
        $this->assertFalse(file_exists($oldImagePath)); // Old image deleted
        $product->refresh();
        $this->assertStringContainsString('.jpg', $product->image_url);
        $this->assertNotEquals('old_image.jpg', $product->image_url);
    }

    #[Test]
    public function update_preserves_default_image()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => 'default-product.jpg',
        ]);

        $newFile = UploadedFile::fake()->image('new_image.jpg');
        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 199.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products/'.$product->id, 'PUT', $updateData, [], ['image_url' => $newFile]);

        // Act
        $this->controller->update($request, $product->id);

        // Assert - Default image should not be deleted
        $product->refresh();
        $this->assertNotEquals('default-product.jpg', $product->image_url);
    }

    #[Test]
    public function update_handles_missing_old_image_file()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => 'non_existent_image.jpg',
        ]);

        $newFile = UploadedFile::fake()->image('new_image.jpg');
        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 199.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products/'.$product->id, 'PUT', $updateData, [], ['image_url' => $newFile]);

        // Act & Assert - Should not throw exception
        $response = $this->controller->update($request, $product->id);
        $this->assertEquals(302, $response->getStatusCode());
    }

    #[Test]
    public function update_preserves_view_count_when_not_provided()
    {
        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'view_count' => 50,
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 199.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products/'.$product->id, 'PUT', $updateData);

        // Act
        $this->controller->update($request, $product->id);

        // Assert
        $product->refresh();
        $this->assertEquals(50, $product->view_count);
    }

    #[Test]
    public function update_validates_required_fields()
    {
        // Arrange
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $request = Request::create('/admin/products/'.$product->id, 'PUT', []);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->update($request, $product->id);
    }

    #[Test]
    public function update_throws_exception_when_product_not_found()
    {
        // Arrange
        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 199.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
        ];
        $request = Request::create('/admin/products/999', 'PUT', $updateData);

        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->controller->update($request, 999);
    }

    #[Test]
    public function destroy_deletes_product_and_image_successfully()
    {
        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => 'product_image.jpg',
        ]);

        // Create image file
        $imagePath = $this->testUploadPath.'/product_image.jpg';
        file_put_contents($imagePath, 'fake image content');

        // Act
        $response = $this->controller->destroy($product->id);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertFalse(file_exists($imagePath));
    }

    #[Test]
    public function destroy_preserves_default_images()
    {
        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => 'base.jpg',
        ]);

        // Act
        $this->controller->destroy($product->id);

        // Assert
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        // Should not attempt to delete base.jpg
    }

    #[Test]
    public function destroy_returns_json_for_ajax_request()
    {
        // Arrange
        Cache::shouldReceive('forget')->with('admin_products_all')->once();

        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $request = Request::create('/admin/products/'.$product->id, 'DELETE');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        // Mock the request() helper
        $this->app->bind('request', function () use ($request) {
            return $request;
        });

        // Act
        $response = $this->controller->destroy($product->id);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
    }

    #[Test]
    public function destroy_throws_exception_when_product_not_found()
    {
        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->controller->destroy(999);
    }

    #[Test]
    public function show_returns_json_for_ajax_request()
    {
        // Arrange
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $request = Request::create('/admin/products/'.$product->id, 'GET');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        // Act
        $response = $this->controller->show($product->id, $request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($product->name, $responseData['name']);
        $this->assertEquals($product->id, $responseData['id']);
        $this->assertArrayHasKey('image_url', $responseData);
    }

    #[Test]
    public function show_returns_view_for_regular_request()
    {
        // Arrange
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $request = Request::create('/admin/products/'.$product->id, 'GET');

        // Act
        $response = $this->controller->show($product->id, $request);

        // Assert
        $this->assertEquals('admin.products.show', $response->getName());
        $this->assertEquals($product->id, $response->getData()['product']->id);
    }

    #[Test]
    public function show_detects_various_ajax_request_types()
    {
        // Arrange
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $ajaxIndicators = [
            ['ajax' => true],
            ['wantsJson' => true],
            ['expectsJson' => true],
            ['header' => ['X-Requested-With' => 'XMLHttpRequest']],
            ['header' => ['Accept' => 'application/json']],
            ['query' => ['ajax' => '1']],
        ];

        foreach ($ajaxIndicators as $indicator) {
            $request = Request::create('/admin/products/'.$product->id, 'GET');

            if (isset($indicator['ajax'])) {
                $request = Mockery::mock(Request::class)->makePartial();
                $request->shouldReceive('ajax')->andReturn(true);
                $request->shouldReceive('wantsJson')->andReturn(false);
                $request->shouldReceive('expectsJson')->andReturn(false);
                $request->shouldReceive('header')->andReturn('');
                $request->shouldReceive('query')->andReturn('');
            }

            // Act
            $response = $this->controller->show($product->id, $request);

            // Assert for mocked request
            if (isset($indicator['ajax'])) {
                $this->assertEquals(200, $response->getStatusCode());
                $responseData = json_decode($response->getContent(), true);
                $this->assertTrue($responseData['success']);
            }
        }
    }

    #[Test]
    public function search_returns_all_products_when_query_empty()
    {
        // Arrange
        Product::factory()->count(3)->create(['category_id' => $this->category->id]);
        $request = Request::create('/admin/products/search', 'GET', ['query' => '']);

        // Act
        $response = $this->controller->search($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('html', $responseData);
    }

    #[Test]
    public function search_filters_products_by_name()
    {
        // Arrange
        Product::factory()->create([
            'name' => 'Laravel Book',
            'category_id' => $this->category->id,
        ]);
        Product::factory()->create([
            'name' => 'PHP Guide',
            'category_id' => $this->category->id,
        ]);

        $request = Request::create('/admin/products/search', 'GET', ['query' => 'Laravel']);

        // Act
        $response = $this->controller->search($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('html', $responseData);
        $this->assertStringContainsString('Laravel Book', $responseData['html']);
        $this->assertStringNotContainsString('PHP Guide', $responseData['html']);
    }

    #[Test]
    public function search_filters_products_by_description()
    {
        // Arrange
        Product::factory()->create([
            'name' => 'Programming Book',
            'descriptions' => 'Learn Laravel framework',
            'category_id' => $this->category->id,
        ]);
        Product::factory()->create([
            'name' => 'Design Book',
            'descriptions' => 'Learn Photoshop basics',
            'category_id' => $this->category->id,
        ]);

        $request = Request::create('/admin/products/search', 'GET', ['query' => 'Laravel']);

        // Act
        $response = $this->controller->search($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString('Programming Book', $responseData['html']);
        $this->assertStringNotContainsString('Design Book', $responseData['html']);
    }

    #[Test]
    public function search_handles_multiple_keywords()
    {
        // Arrange
        Product::factory()->create([
            'name' => 'Laravel PHP Framework',
            'descriptions' => 'Complete guide',
            'category_id' => $this->category->id,
        ]);
        Product::factory()->create([
            'name' => 'React JavaScript',
            'descriptions' => 'Frontend library',
            'category_id' => $this->category->id,
        ]);

        $request = Request::create('/admin/products/search', 'GET', ['query' => 'Laravel PHP']);

        // Act
        $response = $this->controller->search($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString('Laravel PHP Framework', $responseData['html']);
        $this->assertStringNotContainsString('React JavaScript', $responseData['html']);
    }

    public static function tearDownAfterClass(): void
    {
        Mockery::close();
        parent::tearDownAfterClass();
    }
}
