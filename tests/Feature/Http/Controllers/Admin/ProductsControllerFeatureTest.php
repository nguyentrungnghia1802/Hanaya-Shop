<?php

namespace Tests\Feature\Admin;

use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductsControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $category;

    protected $testUploadPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->testUploadPath = public_path('images/products');

        if (! file_exists($this->testUploadPath)) {
            mkdir($this->testUploadPath, 0755, true);
        }

        $defaultImage = UploadedFile::fake()->image('default-product.jpg');
        $defaultImage->move($this->testUploadPath, 'default-product.jpg');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testUploadPath)) {
            $files = glob($this->testUploadPath.'/*');
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'default-product.jpg') {
                    unlink($file);
                }
            }
        }
        Cache::flush();
        parent::tearDown();
    }

    public function can_access_products_index_page()
    {
        $products = Product::factory()->count(5)->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.product'));

        $response->assertStatus(200)
            ->assertViewIs('admin.products.index')
            ->assertViewHas('products')
            ->assertSee($products[0]->name)
            ->assertSee($products[1]->name);
    }

    public function products_index_displays_with_pagination()
    {
        Product::factory()->count(45)->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.product'));

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertEquals(20, $products->perPage());
        $this->assertEquals(45, $products->total());
        $this->assertEquals(3, $products->lastPage());
    }

    public function products_are_loaded_with_category_relationship()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.product'));

        $response->assertStatus(200);
        $products = $response->viewData('products');

        $this->assertTrue($products->first()->relationLoaded('category'));
    }

    public function can_access_create_product_page()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.product.create'));

        $response->assertStatus(200)
            ->assertViewIs('admin.products.create')
            ->assertViewHas('categories')
            ->assertSee($categories[0]->name)
            ->assertSee($categories[1]->name);
    }

    public function can_create_product_without_image()
    {
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'This is a test product description',
            'price' => 99.99,
            'stock_quantity' => 50,
            'category_id' => $this->category->id,
            'discount_percent' => 10,
            'view_count' => 0,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $response->assertRedirect(route('admin.product'))
            ->assertSessionHas('success', 'Product created successfully!');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'descriptions' => 'This is a test product description',
            'price' => 99.99,
            'stock_quantity' => 50,
            'category_id' => $this->category->id,
            'discount_percent' => 10,
            'view_count' => 0,
            'image_url' => 'default-product.jpg',
        ]);
    }

    public function can_create_product_with_image()
    {
        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        $productData = [
            'name' => 'Product with Image',
            'descriptions' => 'Product with custom image',
            'price' => 149.99,
            'stock_quantity' => 25,
            'category_id' => $this->category->id,
            'image_url' => $image,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $response->assertRedirect(route('admin.product'))
            ->assertSessionHas('success');

        $product = Product::where('name', 'Product with Image')->first();
        $this->assertNotNull($product);
        $this->assertNotEquals('default-product.jpg', $product->image_url);
        $this->assertFileExists($this->testUploadPath.'/'.$product->image_url);
    }

    public function product_creation_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), []);

        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'name', 'descriptions', 'price', 'stock_quantity', 'category_id',
            ]);
    }

    public function product_creation_validates_numeric_fields()
    {
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 'invalid_price',
            'stock_quantity' => 'invalid_quantity',
            'category_id' => $this->category->id,
            'discount_percent' => 'invalid_discount',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['price', 'stock_quantity', 'discount_percent']);
    }

    public function product_creation_validates_category_exists()
    {
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => 999, // Non-existent category
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['category_id']);
    }

    public function product_creation_validates_image_type()
    {
        $invalidFile = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
            'image_url' => $invalidFile,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['image_url']);
    }

    public function product_creation_validates_price_minimum()
    {
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => -10,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['price']);
    }

    public function product_creation_validates_discount_range()
    {
        $productData = [
            'name' => 'Test Product',
            'descriptions' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
            'discount_percent' => 150, // Over 100%
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['discount_percent']);
    }

    public function product_creation_invalidates_cache()
    {
        Cache::put('admin_products_all', 'cached_data', 60);
        $this->assertTrue(Cache::has('admin_products_all'));

        $productData = [
            'name' => 'Cache Test Product',
            'descriptions' => 'Test cache invalidation',
            'price' => 99.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id,
        ];

        $this->actingAs($this->user)
            ->post(route('admin.product.store'), $productData);

        $this->assertFalse(Cache::has('admin_products_all'));
    }

    public function can_access_edit_product_page()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $categories = Category::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.product.edit', $product->id));

        $response->assertStatus(200)
            ->assertViewIs('admin.products.edit')
            ->assertViewHas('product')
            ->assertViewHas('categories')
            ->assertSee($product->name);
    }

    public function editing_nonexistent_product_returns_404()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.product.edit', 999));

        $response->assertStatus(404);
    }

    public function can_update_product_without_changing_image()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Original Name',
            'price' => 50.00,
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'descriptions' => 'Updated description',
            'price' => 75.00,
            'stock_quantity' => 30,
            'category_id' => $this->category->id,
            'discount_percent' => 15,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('admin.product.update', $product->id), $updateData);

        $response->assertRedirect(route('admin.product'))
            ->assertSessionHas('success', 'Product updated successfully!');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
            'price' => 75.00,
            'discount_percent' => 15,
        ]);
    }

    public function can_update_product_with_new_image()
    {
        // Create existing image
        $existingImage = UploadedFile::fake()->image('existing.jpg');
        $existingImageName = time().'_existing.jpg';
        $existingImage->move($this->testUploadPath, $existingImageName);

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => $existingImageName,
        ]);

        $newImage = UploadedFile::fake()->image('new.png');
        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 99.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
            'image_url' => $newImage,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('admin.product.update', $product->id), $updateData);

        $response->assertRedirect(route('admin.product'));

        $product->refresh();
        $this->assertNotEquals($existingImageName, $product->image_url);
        $this->assertFileExists($this->testUploadPath.'/'.$product->image_url);
        $this->assertFileDoesNotExist($this->testUploadPath.'/'.$existingImageName);
    }

    public function update_does_not_delete_default_image()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => 'default-product.jpg',
        ]);

        $newImage = UploadedFile::fake()->image('new.jpg');
        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 99.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
            'image_url' => $newImage,
        ];

        $this->actingAs($this->user)
            ->put(route('admin.product.update', $product->id), $updateData);

        // Default image should still exist
        $this->assertFileExists($this->testUploadPath.'/default-product.jpg');
    }

    public function update_preserves_view_count_when_not_provided()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'view_count' => 100,
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 99.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
        ];

        $this->actingAs($this->user)
            ->put(route('admin.product.update', $product->id), $updateData);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'view_count' => 100,
        ]);
    }

    public function update_invalidates_cache()
    {
        Cache::put('admin_products_all', 'cached_data', 60);
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $updateData = [
            'name' => 'Updated Product',
            'descriptions' => 'Updated description',
            'price' => 99.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id,
        ];

        $this->actingAs($this->user)
            ->put(route('admin.product.update', $product->id), $updateData);

        $this->assertFalse(Cache::has('admin_products_all'));
    }

    public function updating_nonexistent_product_returns_404()
    {
        $response = $this->actingAs($this->user)
            ->put(route('admin.product.update', 999), [
                'name' => 'Test',
                'descriptions' => 'Test',
                'price' => 99.99,
                'stock_quantity' => 10,
                'category_id' => $this->category->id,
            ]);

        $response->assertStatus(404);
    }

    public function can_delete_product_without_image()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.product.destroy', $product->id));

        $response->assertRedirect(route('admin.product'))
            ->assertSessionHas('success', 'Product deleted successfully!');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function can_delete_product_with_custom_image()
    {
        // Create custom image
        $customImage = UploadedFile::fake()->image('custom.jpg');
        $customImageName = time().'_custom.jpg';
        $customImage->move($this->testUploadPath, $customImageName);

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => $customImageName,
        ]);

        $this->assertFileExists($this->testUploadPath.'/'.$customImageName);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.product.destroy', $product->id));

        $response->assertRedirect(route('admin.product'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertFileDoesNotExist($this->testUploadPath.'/'.$customImageName);
    }

    public function delete_does_not_remove_base_image()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'image_url' => 'base.jpg',
        ]);

        // Create base.jpg for test
        $baseImage = UploadedFile::fake()->image('base.jpg');
        $baseImage->move($this->testUploadPath, 'base.jpg');

        $this->actingAs($this->user)
            ->delete(route('admin.product.destroy', $product->id));

        $this->assertFileExists($this->testUploadPath.'/base.jpg');
    }

    public function delete_via_ajax_returns_json()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('admin.product.destroy', $product->id));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function delete_invalidates_cache()
    {
        Cache::put('admin_products_all', 'cached_data', 60);
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $this->actingAs($this->user)
            ->delete(route('admin.product.destroy', $product->id));

        $this->assertFalse(Cache::has('admin_products_all'));
    }

    public function deleting_nonexistent_product_returns_404()
    {
        $response = $this->actingAs($this->user)
            ->delete(route('admin.product.destroy', 999));

        $response->assertStatus(404);
    }

    public function can_view_product_details_via_web()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.product.show', $product->id));

        $response->assertStatus(200)
            ->assertViewIs('admin.products.show')
            ->assertViewHas('product')
            ->assertSee($product->name);
    }

    public function can_view_product_details_via_ajax()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->getJson(route('admin.product.show', $product->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'id' => $product->id,
                'name' => $product->name,
                'descriptions' => $product->descriptions,
                'price' => $product->price,
                'stock_quantity' => $product->stock_quantity,
                'category_name' => $this->category->name,
            ])
            ->assertJsonStructure(['image_url']);
    }

    public function ajax_detection_works_with_xhr_header()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get(route('admin.product.show', $product->id));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function ajax_detection_works_with_accept_header()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('admin.product.show', $product->id));

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function ajax_detection_works_with_query_parameter()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.product.show', $product->id).'?ajax=1');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function can_search_products_by_name()
    {
        $matchingProduct = Product::factory()->create([
            'name' => 'Laravel Book',
            'category_id' => $this->category->id,
        ]);
        $nonMatchingProduct = Product::factory()->create([
            'name' => 'Vue.js Guide',
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.product.search'), ['query' => 'Laravel']);

        $response->assertStatus(200)
            ->assertJsonStructure(['html']);

        $html = $response->json('html');
        $this->assertStringContainsString($matchingProduct->name, $html);
        $this->assertStringNotContainsString($nonMatchingProduct->name, $html);
    }

    public function can_search_products_by_description()
    {
        $matchingProduct = Product::factory()->create([
            'name' => 'Programming Book',
            'descriptions' => 'Learn Laravel framework basics',
            'category_id' => $this->category->id,
        ]);
        $nonMatchingProduct = Product::factory()->create([
            'name' => 'Design Book',
            'descriptions' => 'Learn CSS and HTML',
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.product.search'), ['query' => 'Laravel']);

        $response->assertStatus(200);
        $html = $response->json('html');
        $this->assertStringContainsString($matchingProduct->name, $html);
        $this->assertStringNotContainsString($nonMatchingProduct->name, $html);
    }

    public function search_with_multiple_keywords()
    {
        $matchingProduct = Product::factory()->create([
            'name' => 'Advanced Laravel Framework',
            'descriptions' => 'Learn advanced Laravel concepts',
            'category_id' => $this->category->id,
        ]);
        $partialMatchProduct = Product::factory()->create([
            'name' => 'Laravel Basics',
            'descriptions' => 'Simple introduction',
            'category_id' => $this->category->id,
        ]);
        $nonMatchingProduct = Product::factory()->create([
            'name' => 'Vue.js Guide',
            'descriptions' => 'Learn Vue.js',
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.product.search'), ['query' => 'Laravel Advanced']);

        $response->assertStatus(200);
        $html = $response->json('html');

        // Both keywords must be present
        $this->assertStringContainsString($matchingProduct->name, $html);
        $this->assertStringNotContainsString($partialMatchProduct->name, $html);
        $this->assertStringNotContainsString($nonMatchingProduct->name, $html);
    }

    public function search_with_empty_query_returns_all_products()
    {
        $products = Product::factory()->count(3)->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.product.search'), ['query' => '']);

        $response->assertStatus(200);
        $html = $response->json('html');

        foreach ($products as $product) {
            $this->assertStringContainsString($product->name, $html);
        }
    }

    public function search_loads_category_relationship()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.product.search'), ['query' => $product->name]);

        $response->assertStatus(200);
        $html = $response->json('html');
        $this->assertStringContainsString($this->category->name, $html);
    }

    public function unauthenticated_users_cannot_access_product_routes()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $routes = [
            ['GET', route('admin.product')],
            ['GET', route('admin.product.create')],
            ['POST', route('admin.product.store')],
            ['GET', route('admin.product.edit', $product->id)],
            ['PUT', route('admin.product.update', $product->id)],
            ['DELETE', route('admin.product.destroy', $product->id)],
            ['GET', route('admin.product.show', $product->id)],
            ['POST', route('admin.product.search')],
        ];

        foreach ($routes as [$method, $url]) {
            $response = $this->call($method, $url);
            $this->assertTrue(
                $response->isRedirect() && str_contains($response->headers->get('Location'), 'login'),
                "Route {$method} {$url} should redirect to login"
            );
        }
    }
}
