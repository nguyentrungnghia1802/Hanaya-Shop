<?php

namespace Tests\Unit\Admin;

use App\Http\Controllers\Admin\PostController;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected $user;

    protected $testUploadPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new PostController;
        $this->user = User::factory()->create();
        $this->testUploadPath = public_path('images/posts');

        if (! file_exists($this->testUploadPath)) {
            mkdir($this->testUploadPath, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testUploadPath)) {
            $files = glob($this->testUploadPath.'/*');
            foreach ($files as $file) {
                if (is_file($file) && strpos(basename($file), 'post_featured_') === 0) {
                    unlink($file);
                }
            }
        }
        parent::tearDown();
    }

    #[Test]
    public function index_returns_all_posts_with_author()
    {
        // Arrange
        $posts = Post::factory()->count(3)->create(['user_id' => $this->user->id]);
        $request = Request::create('/admin/posts', 'GET');

        // Act
        $response = $this->controller->index($request);

        // Assert
        $this->assertEquals('admin.posts.index', $response->name());
        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getData()['posts']);
        $this->assertEquals(3, $response->getData()['posts']->total());
    }

    #[Test]
    public function index_filters_posts_by_search_term_in_title()
    {
        // Arrange
        Post::factory()->create(['title' => 'Laravel Tutorial', 'user_id' => $this->user->id]);
        Post::factory()->create(['title' => 'PHP Guide', 'user_id' => $this->user->id]);
        Post::factory()->create(['title' => 'JavaScript Basics', 'user_id' => $this->user->id]);

        $request = Request::create('/admin/posts', 'GET', ['search' => 'Laravel']);

        // Act
        $response = $this->controller->index($request);

        // Assert
        $posts = $response->getData()['posts'];
        $this->assertEquals(1, $posts->total());
        $this->assertEquals('Laravel Tutorial', $posts->first()->title);
    }

    #[Test]
    public function index_filters_posts_by_search_term_in_content()
    {
        // Arrange
        Post::factory()->create([
            'title' => 'Web Development',
            'content' => 'This is about Laravel framework',
            'user_id' => $this->user->id,
        ]);
        Post::factory()->create([
            'title' => 'Mobile Development',
            'content' => 'This is about React Native',
            'user_id' => $this->user->id,
        ]);

        $request = Request::create('/admin/posts', 'GET', ['search' => 'Laravel']);

        // Act
        $response = $this->controller->index($request);

        // Assert
        $posts = $response->getData()['posts'];
        $this->assertEquals(1, $posts->total());
        $this->assertEquals('Web Development', $posts->first()->title);
    }

    #[Test]
    public function index_preserves_search_parameters_in_pagination()
    {
        // Arrange
        Post::factory()->count(15)->create(['user_id' => $this->user->id]);
        $request = Request::create('/admin/posts', 'GET', ['search' => 'test']);

        // Act
        $response = $this->controller->index($request);

        // Assert
        $posts = $response->getData()['posts'];
        $this->assertStringContainsString('search=test', $posts->url(2));
    }

    #[Test]
    public function index_orders_posts_by_created_at_desc()
    {
        // Arrange
        $oldPost = Post::factory()->create([
            'created_at' => Carbon::now()->subDays(2),
            'user_id' => $this->user->id,
        ]);
        $newPost = Post::factory()->create([
            'created_at' => Carbon::now(),
            'user_id' => $this->user->id,
        ]);

        $request = Request::create('/admin/posts', 'GET');

        // Act
        $response = $this->controller->index($request);

        // Assert
        $posts = $response->getData()['posts'];
        $this->assertEquals($newPost->id, $posts->first()->id);
    }

    #[Test]
    public function show_returns_post_with_author()
    {
        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        // Act
        $response = $this->controller->show($post->id);

        // Assert
        $this->assertEquals('admin.posts.show', $response->getName());
        $this->assertEquals($post->id, $response->getData()['post']->id);
        $this->assertNotNull($response->getData()['post']->author);
    }

    #[Test]
    public function show_throws_exception_when_post_not_found()
    {
        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->controller->show(999);
    }

    #[Test]
    public function create_returns_create_view()
    {
        // Act
        $response = $this->controller->create();

        // Assert
        $this->assertEquals('admin.posts.create', $response->getName());
        $this->assertArrayNotHasKey('edit', $response->getData());
        $this->assertArrayNotHasKey('post', $response->getData());
    }

    #[Test]
    public function store_creates_post_successfully_without_image()
    {
        // Arrange
        Auth::shouldReceive('id')->andReturn($this->user->id);

        $requestData = [
            'title' => 'Test Post Title',
            'content' => 'This is test content',
            'status' => true,
        ];
        $request = Request::create('/admin/posts', 'POST', $requestData);

        // Act
        $response = $this->controller->store($request);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/post', $response->getTargetUrl());

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post Title',
            'slug' => 'test-post-title',
            'content' => 'This is test content',
            'status' => true,
            'user_id' => $this->user->id,
            'image' => null,
        ]);
    }

    #[Test]
    public function store_creates_post_with_image_upload()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        Auth::shouldReceive('id')->andReturn($this->user->id);
        Carbon::setTestNow(Carbon::parse('2024-01-15 10:30:45'));
        Str::createRandomStringsUsing(function ($length) {
            return 'abc12345';
        });

        $file = UploadedFile::fake()->image('test-image.jpg');
        $requestData = [
            'title' => 'Post with Image',
            'content' => 'Content with image',
            'status' => true,
        ];
        $request = Request::create('/admin/posts', 'POST', $requestData, [], ['image' => $file]);

        // Act
        $response = $this->controller->store($request);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());

        $this->assertDatabaseHas('posts', [
            'title' => 'Post with Image',
            'image' => 'post_featured_20240115103045_abc12345.jpg',
        ]);

        $this->assertFileExists($this->testUploadPath.'/post_featured_20240115103045_abc12345.jpg');

        // Cleanup
        Carbon::setTestNow();
        Str::createRandomStringsNormally();
    }

    #[Test]
    public function store_validates_required_fields()
    {
        // Arrange
        $request = Request::create('/admin/posts', 'POST', []);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    #[Test]
    public function store_validates_image_file_type()
    {
        // Arrange
        $file = UploadedFile::fake()->create('document.pdf', 1024);
        $requestData = [
            'title' => 'Test Post',
            'content' => 'Test content',
        ];
        $request = Request::create('/admin/posts', 'POST', $requestData, [], ['image' => $file]);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    #[Test]
    public function store_creates_upload_directory_if_not_exists()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        Auth::shouldReceive('id')->andReturn($this->user->id);
        if (file_exists($this->testUploadPath)) {
            // Remove all files first, then directory
            $files = glob($this->testUploadPath.'/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($this->testUploadPath);
        }

        $file = UploadedFile::fake()->image('test.jpg');
        $requestData = [
            'title' => 'Test Post',
            'content' => 'Test content',
        ];
        $request = Request::create('/admin/posts', 'POST', $requestData, [], ['image' => $file]);

        // Act
        $this->controller->store($request);

        // Assert
        $this->assertTrue(file_exists($this->testUploadPath));
    }

    #[Test]
    public function store_sets_default_status_to_true()
    {
        // Arrange
        Auth::shouldReceive('id')->andReturn($this->user->id);

        $requestData = [
            'title' => 'Test Post',
            'content' => 'Test content',
        ];
        $request = Request::create('/admin/posts', 'POST', $requestData);

        // Act
        $this->controller->store($request);

        // Assert
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'status' => true,
        ]);
    }

    #[Test]
    public function edit_returns_edit_view_with_post()
    {
        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        // Act
        $response = $this->controller->edit($post->id);

        // Assert
        $this->assertEquals('admin.posts.create', $response->getName());
        $data = $response->getData();
        $this->assertTrue($data['edit']);
        $this->assertEquals($post->id, $data['post']->id);
    }

    #[Test]
    public function edit_throws_exception_when_post_not_found()
    {
        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->controller->edit(999);
    }

    #[Test]
    public function update_updates_post_without_image()
    {
        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $requestData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 0,
        ];
        $request = Request::create('/admin/posts/'.$post->id, 'PUT', $requestData);

        // Act
        $response = $this->controller->update($request, $post->id);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());

        $post->refresh();
        $this->assertEquals('Updated Title', $post->title);
        $this->assertEquals('updated-title', $post->slug);
        $this->assertEquals('Updated content', $post->content);
        $this->assertEquals(0, $post->status);
    }

    #[Test]
    public function update_replaces_old_image_with_new_one()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        File::shouldReceive('exists')->andReturn(true);
        File::shouldReceive('delete')->once();
        
        // Mock filesystem for translation loading
        $this->mock('files', function ($mock) {
            $mock->shouldReceive('get')->andReturn('[]');
        });

        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'image' => 'old_image.jpg',
        ]);

        Carbon::setTestNow(Carbon::parse('2024-01-15 14:25:30'));
        Str::createRandomStringsUsing(function ($length) {
            return 'xyz98765';
        });

        $newFile = UploadedFile::fake()->image('new-image.jpg');
        $requestData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];
        $request = Request::create('/admin/posts/'.$post->id, 'PUT', $requestData, [], ['image' => $newFile]);

        // Act
        $this->controller->update($request, $post->id);

        // Assert
        $post->refresh();
        $this->assertEquals('post_featured_20240115142530_xyz98765.jpg', $post->image);

        // Cleanup
        Carbon::setTestNow();
        Str::createRandomStringsNormally();
    }

    #[Test]
    public function update_skips_old_image_deletion_if_file_not_exists()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        File::shouldReceive('exists')->andReturn(false);
        File::shouldReceive('delete')->never();

        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'image' => 'non_existing_image.jpg',
        ]);

        $newFile = UploadedFile::fake()->image('new-image.jpg');
        $requestData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];
        $request = Request::create('/admin/posts/'.$post->id, 'PUT', $requestData, [], ['image' => $newFile]);

        // Act
        $this->controller->update($request, $post->id);

        $this->assertTrue(true);
    }

    #[Test]
    public function update_validates_required_fields()
    {
        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $request = Request::create('/admin/posts/'.$post->id, 'PUT', []);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->update($request, $post->id);
    }

    #[Test]
    public function update_validates_image_file_type()
    {
        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $file = UploadedFile::fake()->create('document.txt', 1024);
        $requestData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];
        $request = Request::create('/admin/posts/'.$post->id, 'PUT', $requestData, [], ['image' => $file]);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $this->controller->update($request, $post->id);
    }

    #[Test]
    public function update_throws_exception_when_post_not_found()
    {
        // Arrange
        $requestData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ];
        $request = Request::create('/admin/posts/999', 'PUT', $requestData);

        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->controller->update($request, 999);
    }

    #[Test]
    public function update_sets_default_status_to_true()
    {
        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id, 'status' => false]);

        $requestData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];
        $request = Request::create('/admin/posts/'.$post->id, 'PUT', $requestData);

        // Act
        $this->controller->update($request, $post->id);

        // Assert
        $post->refresh();
        $this->assertEquals(1, $post->status); // Fixed: Compare with 1 instead of true
    }

    #[Test]
    public function update_creates_upload_directory_if_not_exists()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        if (file_exists($this->testUploadPath)) {
            // Remove all files first, then directory
            $files = glob($this->testUploadPath.'/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($this->testUploadPath);
        }

        $file = UploadedFile::fake()->image('test.jpg');
        $requestData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];
        $request = Request::create('/admin/posts/'.$post->id, 'PUT', $requestData, [], ['image' => $file]);

        // Act
        $this->controller->update($request, $post->id);

        // Assert
        $this->assertTrue(file_exists($this->testUploadPath));
    }

    #[Test]
    public function destroy_deletes_post_successfully()
    {
        // Arrange
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        // Act
        $response = $this->controller->destroy($post->id);

        // Assert
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/post', $response->getTargetUrl());
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    #[Test]
    public function destroy_throws_exception_when_post_not_found()
    {
        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->controller->destroy(999);
    }

    #[Test]
    public function slug_is_generated_correctly_from_title()
    {
        // Arrange
        Auth::shouldReceive('id')->andReturn($this->user->id);

        $requestData = [
            'title' => 'This is a Test Post with Special Characters!@#',
            'content' => 'Test content',
        ];
        $request = Request::create('/admin/posts', 'POST', $requestData);

        // Act
        $this->controller->store($request);

        // Assert
        // Fixed: Str::slug()
        $this->assertDatabaseHas('posts', [
            'title' => 'This is a Test Post with Special Characters!@#',
            'slug' => 'this-is-a-test-post-with-special-characters-at',
        ]);
    }

    #[Test]
    public function image_upload_supports_all_valid_formats()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        Auth::shouldReceive('id')->andReturn($this->user->id);
        $validFormats = ['jpeg', 'png', 'jpg', 'gif'];

        foreach ($validFormats as $format) {
            // Arrange
            $file = UploadedFile::fake()->image("test.{$format}");
            $requestData = [
                'title' => "Test Post {$format}",
                'content' => 'Test content',
            ];
            $request = Request::create('/admin/posts', 'POST', $requestData, [], ['image' => $file]);

            // Act
            $this->controller->store($request);

            // Assert
            $this->assertDatabaseHas('posts', [
                'title' => "Test Post {$format}",
            ]);
        }
    }

    public static function tearDownAfterClass(): void
    {
        Mockery::close();
        parent::tearDownAfterClass();
    }
}
