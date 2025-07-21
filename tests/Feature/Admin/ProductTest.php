<?php

namespace Test\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    protected $category;
    protected $admin;
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        Storage::fake('public');
        if (!file_exists(public_path('images'))) {
            mkdir(public_path('images'), 0777, true);
        }

        $this->category = Category::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin, 'web');
    }

    #[Test]
    public function test_admin_can_view_product_list()
    {
        Product::factory()->count(3)->create([
            'category_id' => $this->category->id,
        ]);

        $response = $this->get(route('admin.product'));
        $response->assertStatus(200)
                 ->assertViewIs('admin.products.index')
                 ->assertViewhas('products');
        $response->assertSee(Product::first()->name);
    }

    #[Test]
    public function test_admin_create_product_with_valid_data()
    {
        $file = UploadedFile::fake()->image('photo.jpg');
        $data = [
            'name'           => 'Test Product',
            'description'    => 'Test',
            'price'          => 99.99,
            'stock_quantity' => 10,
            'image_url'      => $file,
            'category_id'    => $this->category->id,
        ];
        // Cache::shouldReceive('forget')->once()->with('admin_product_all');
        $response = $this->post(route('admin.product.store'), $data);
        $response->assertRedirect(route('admin.product'))
                 ->assertSessionHas('success', 'Product created successfully!');

        $this->assertDatabaseHas('products', [
            'name'        => 'Test Product',
            'category_id' => $this->category->id,
        ]);

        /**
         * @var Storage Fake $disk
         */
        $disk = Storage::disk('public');
        $disk->assertExists('image/' . Product::first()->image_url);
        // $filepath = public_path('image/' . Product::first()->image_url);
        // $this->assertFileExists($filepath);
    }

    #[Test]
    public function admin_can_view_create_form()
    {
        $response = $this->get(route('admin.product.create'));

        $response->assertStatus(200)
                 ->assertViewIs('admin.products.create')
                 ->assertViewHas('categories');
    }

    #[Test]
    public function admin_can_view_edit_form()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        $response = $this->get(route('admin.product.edit', $product->id));

        $response->assertStatus(200)
                 ->assertViewIs('admin.products.edit')
                 ->assertViewHasAll(['product', 'categories'])
                 ->assertSee($product->name);
    }

    #[Test]
    public function admin_can_update_product_with_valid_data()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);
        $file = UploadedFile::fake()->image('new.jpg');
        $data = [
            'name'           => 'Updated Name',
            'descriptions'   => 'Updated desc',
            'price'          => 123.45,
            'stock_quantity' => 5,
            'image_url'      => $file,
            'category_id'    => $this->category->id,
        ];

        Cache::shouldReceive('forget')->once()->with('admin_products_all');

        $response = $this->put(route('admin.product.update', $product->id), $data);

        $response->assertRedirect(route('admin.product'))
                 ->assertSessionHas('success', 'Product updated successfully!');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
        /**
         * @var Storage fake $disk
         */
        $disk = Storage::disk('public');
        $disk->assertExists('images/' . Product::find($product->id)->image_url);
    }

    #[Test]
    public function admin_can_view_product_detail_and_json()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id]);

        // HTML view
        $html = $this->get(route('admin.product.show', $product->id));
        $html->assertStatus(200)
             ->assertViewIs('admin.products.show')
             ->assertViewHas('product')
             ->assertSee($product->name);

        // JSON via AJAX
        $json = $this->getJson(route('admin.product.show', ['id' => $product->id, 'ajax' => '1']));
        $json->assertStatus(200)
             ->assertJsonStructure(['success','id','name','descriptions','price','stock_quantity','category_name','image_url']);
    }

    #[Test]
    public function admin_can_delete_product_and_handle_ajax()
    {
        $product = Product::factory()->create(['category_id' => $this->category->id, 'image_url' => 'base.jpg']);

        // Non-AJAX delete
        $resp1 = $this->delete(route('admin.product.destroy', $product->id));
        $resp1->assertRedirect(route('admin.product'))
              ->assertSessionHas('success', 'Product deleted successfully!');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        // Recreate for AJAX
        $prod2 = Product::factory()->create(['category_id' => $this->category->id]);
        $resp2 = $this->deleteJson(route('admin.product.destroy', $prod2->id));
        $resp2->assertStatus(200)
              ->assertJson(['success' => true]);
    }

    #[Test]
    public function admin_can_search_products_and_return_html()
    {
        $prodA = Product::factory()->create(['name' => 'Alpha', 'category_id' => $this->category->id]);
        $prodB = Product::factory()->create(['name' => 'Beta', 'category_id' => $this->category->id]);

        // Empty query returns all
        $resp1 = $this->getJson(route('admin.product.search', ['query' => '']));
        $resp1->assertStatus(200)
              ->assertJsonStructure(['html']);

        // Search specific term
        $resp2 = $this->getJson(route('admin.product.search', ['query' => 'Alpha']));
        $resp2->assertStatus(200)
              ->assertJsonFragment(['html']);
        $viewHtml = $resp2->json('html');
        $this->assertStringContainsString('Alpha', $viewHtml);
        $this->assertStringNotContainsString('Beta', $viewHtml);
    }

    #[Test]
    public function non_admin_cannot_access_product_routes()
    {
        // Logout admin and login as normal user
        auth()->logout();
        $user = User::factory()->create();
        $this->actingAs($user);

        $routes = [
            route('admin.dashbroad'),
            route('admin.product.create'),
            route('admin.product'),
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertStatus(403);
        }
    }

}


