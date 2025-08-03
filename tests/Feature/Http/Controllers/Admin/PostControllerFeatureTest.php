<?php

namespace Tests\Feature\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;
use Carbon\Carbon;

class PostControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $testUploadPath;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->testUploadPath = public_path('images/posts');
        
        if (!file_exists($this->testUploadPath)) {
            mkdir($this->testUploadPath, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testUploadPath)) {
            $files = glob($this->testUploadPath . '/*');
            foreach ($files as $file) {
                if (is_file($file) && strpos(basename($file), 'post_featured_') === 0) {
                    unlink($file);
                }
            }
        }
        parent::tearDown();
    }

    
    public function can_access_posts_index_page()
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.index'));

        $response->assertStatus(200)
                ->assertViewIs('admin.posts.index')
                ->assertViewHas('posts')
                ->assertSee($posts[0]->title)
                ->assertSee($posts[1]->title)
                ->assertSee($posts[2]->title);
    }

    
    public function posts_index_displays_with_pagination()
    {
        Post::factory()->count(25)->create();

        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.index'));

        $response->assertStatus(200);
        $posts = $response->viewData('posts');
        
        $this->assertEquals(10, $posts->perPage());
        $this->assertEquals(25, $posts->total());
        $this->assertEquals(3, $posts->lastPage());
    }

    
    public function can_search_posts_by_title()
    {
        $matchingPost = Post::factory()->create(['title' => 'Laravel Tutorial']);
        $nonMatchingPost = Post::factory()->create(['title' => 'Vue.js Guide']);

        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.index', ['search' => 'Laravel']));

        $response->assertStatus(200)
                ->assertSee($matchingPost->title)
                ->assertDontSee($nonMatchingPost->title);
    }

    
    public function can_search_posts_by_content()
    {
        $matchingPost = Post::factory()->create([
            'title' => 'Programming Guide',
            'content' => 'This post is about Laravel framework'
        ]);
        $nonMatchingPost = Post::factory()->create([
            'title' => 'Design Guide', 
            'content' => 'This post is about CSS styling'
        ]);

        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.index', ['search' => 'Laravel']));

        $response->assertStatus(200)
                ->assertSee($matchingPost->title)
                ->assertDontSee($nonMatchingPost->title);
    }

    
    public function search_preserves_parameters_in_pagination()
    {
        Post::factory()->count(15)->create(['title' => 'Laravel Tutorial Part']);

        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.index', ['search' => 'Laravel']));

        $response->assertStatus(200);
        $posts = $response->viewData('posts');
        
        $this->assertStringContains('search=Laravel', $posts->nextPageUrl());
    }

    
    public function can_view_single_post()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.show', $post->id));

        $response->assertStatus(200)
                ->assertViewIs('admin.posts.show')
                ->assertViewHas('post')
                ->assertSee($post->title)
                ->assertSee($post->content);
    }

    
    public function viewing_nonexistent_post_returns_404()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.show', 999));

        $response->assertStatus(404);
    }

    
    public function can_access_create_post_page()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.create'));

        $response->assertStatus(200)
                ->assertViewIs('admin.posts.create')
                ->assertViewMissing('edit')
                ->assertViewMissing('post');
    }

    
    public function can_create_post_without_image()
    {
        $postData = [
            'title' => 'New Test Post',
            'content' => 'This is test content for the post',
            'status' => true
        ];

        $response = $this->actingAs($this->user)
                        ->post(route('admin.post.store'), $postData);

        $response->assertRedirect(route('admin.post.index'))
                ->assertSessionHas('success', 'Tạo bài viết thành công');

        $this->assertDatabaseHas('posts', [
            'title' => 'New Test Post',
            'slug' => 'new-test-post',
            'content' => 'This is test content for the post',
            'status' => true,
            'user_id' => $this->user->id,
            'image' => null
        ]);
    }

    
    public function can_create_post_with_image()
    {
        Carbon::setTestNow(Carbon::parse('2024-01-15 10:30:45'));
        Str::createRandomStringsUsing(function ($length) {
            return 'abc12345';
        });

        $image = UploadedFile::fake()->image('featured.jpg', 800, 600);
        $postData = [
            'title' => 'Post with Image',
            'content' => 'Content with featured image',
            'status' => true,
            'image' => $image
        ];

        $response = $this->actingAs($this->user)
                        ->post(route('admin.post.store'), $postData);

        $response->assertRedirect(route('admin.post.index'))
                ->assertSessionHas('success');

        $expectedFilename = 'post_featured_20240115103045_abc12345.jpg';
        
        $this->assertDatabaseHas('posts', [
            'title' => 'Post with Image',
            'image' => $expectedFilename
        ]);

        $this->assertFileExists($this->testUploadPath . '/' . $expectedFilename);

        // Cleanup
        Carbon::setTestNow();
        Str::createRandomStringsNormally();
    }

    
    public function post_creation_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
                        ->post(route('admin.post.store'), []);

        $response->assertStatus(302)
                ->assertSessionHasErrors(['title', 'content']);
    }

    
    public function post_creation_validates_image_type()
    {
        $invalidFile = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');
        
        $postData = [
            'title' => 'Test Post',
            'content' => 'Test content',
            'image' => $invalidFile
        ];

        $response = $this->actingAs($this->user)
                        ->post(route('admin.post.store'), $postData);

        $response->assertStatus(302)
                ->assertSessionHasErrors(['image']);
    }

    
    public function post_creation_validates_title_length()
    {
        $longTitle = str_repeat('a', 256); // Over 255 characters
        
        $postData = [
            'title' => $longTitle,
            'content' => 'Test content'
        ];

        $response = $this->actingAs($this->user)
                        ->post(route('admin.post.store'), $postData);

        $response->assertStatus(302)
                ->assertSessionHasErrors(['title']);
    }

    
    public function can_access_edit_post_page()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.edit', $post->id));

        $response->assertStatus(200)
                ->assertViewIs('admin.posts.create')
                ->assertViewHas('edit', true)
                ->assertViewHas('post')
                ->assertSee($post->title);
    }

    
    public function editing_nonexistent_post_returns_404()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('admin.post.edit', 999));

        $response->assertStatus(404);
    }

    
    public function can_update_post_without_changing_image()
    {
        $post = Post::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original content'
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => false
        ];

        $response = $this->actingAs($this->user)
                        ->put(route('admin.post.update', $post->id), $updateData);

        $response->assertRedirect(route('admin.post.index'))
                ->assertSessionHas('success', 'Cập nhật bài viết thành công');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'content' => 'Updated content',
            'status' => false
        ]);
    }

    
    public function can_update_post_with_new_image()
    {
        // Create post with existing image
        $existingImage = UploadedFile::fake()->image('existing.jpg');
        $existingImageName = 'existing_image.jpg';
        $existingImage->move($this->testUploadPath, $existingImageName);

        $post = Post::factory()->create(['image' => $existingImageName]);

        Carbon::setTestNow(Carbon::parse('2024-01-15 14:25:30'));
        Str::createRandomStringsUsing(function ($length) {
            return 'xyz98765';
        });

        $newImage = UploadedFile::fake()->image('new.png');
        $updateData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
            'status' => true,
            'image' => $newImage
        ];

        $response = $this->actingAs($this->user)
                        ->put(route('admin.post.update', $post->id), $updateData);

        $response->assertRedirect(route('admin.post.index'));

        $expectedNewFilename = 'post_featured_20240115142530_xyz98765.png';
        
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'image' => $expectedNewFilename
        ]);

        // New image should exist
        $this->assertFileExists($this->testUploadPath . '/' . $expectedNewFilename);
        
        // Old image should be deleted
        $this->assertFileDoesNotExist($this->testUploadPath . '/' . $existingImageName);

        // Cleanup
        Carbon::setTestNow();
        Str::createRandomStringsNormally();
    }

    
    public function update_handles_missing_old_image_gracefully()
    {
        $post = Post::factory()->create(['image' => 'nonexistent.jpg']);

        $newImage = UploadedFile::fake()->image('new.jpg');
        $updateData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
            'image' => $newImage
        ];

        $response = $this->actingAs($this->user)
                        ->put(route('admin.post.update', $post->id), $updateData);

        $response->assertRedirect(route('admin.post.index'))
                ->assertSessionHas('success');

        // Should still update successfully
        $post->refresh();
        $this->assertNotNull($post->image);
        $this->assertFileExists($this->testUploadPath . '/' . $post->image);
    }

    
    public function update_validates_required_fields()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)
                        ->put(route('admin.post.update', $post->id), []);

        $response->assertStatus(302)
                ->assertSessionHasErrors(['title', 'content']);
    }

    
    public function updating_nonexistent_post_returns_404()
    {
        $response = $this->actingAs($this->user)
                        ->put(route('admin.post.update', 999), [
                            'title' => 'Test',
                            'content' => 'Test'
                        ]);

        $response->assertStatus(404);
    }

    
    public function can_delete_post_without_image()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)
                        ->delete(route('admin.post.destroy', $post->id));

        $response->assertRedirect(route('admin.post.index'))
                ->assertSessionHas('success', 'Xóa bài viết thành công');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    
    public function can_delete_post_with_image()
    {
        // Create post with image
        $imageName = 'test_image.jpg';
        $imagePath = $this->testUploadPath . '/' . $imageName;
        
        // Create actual image file
        $image = UploadedFile::fake()->image('test.jpg');
        $image->move($this->testUploadPath, $imageName);
        
        $post = Post::factory()->create(['image' => $imageName]);

        $this->assertFileExists($imagePath);

        $response = $this->actingAs($this->user)
                        ->delete(route('admin.post.destroy', $post->id));

        $response->assertRedirect(route('admin.post.index'))
                ->assertSessionHas('success');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        // Note: Current implementation doesn't delete image file on post deletion
        // This might be a business requirement to test or improve
    }

    public function deleting_nonexistent_post_returns_404()
    {
        $response = $this->actingAs($this->user)
                        ->delete(route('admin.post.destroy', 999));

        $response->assertStatus(404);
    }

    public function post_slug_is_generated_correctly()
    {
        $postData = [
            'title' => 'This Is A Test Post With Spaces',
            'content' => 'Test content'
        ];

        $this->actingAs($this->user)
            ->post(route('admin.post.store'), $postData);

        $this->assertDatabaseHas('posts', [
            'title' => 'This Is A Test Post With Spaces',
            'slug' => 'this-is-a-test-post-with-spaces'
        ]);
    }

    public function post_status_defaults_to_true_when_not_provided()
    {
        $postData = [
            'title' => 'Test Post',
            'content' => 'Test content'
        ];

        $this->actingAs($this->user)
            ->post(route('admin.post.store'), $postData);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'status' => true
        ]);
    }

    public function can_set_post_status_to_false()
    {
        $postData = [
            'title' => 'Draft Post',
            'content' => 'Draft content',
            'status' => false
        ];

        $this->actingAs($this->user)
            ->post(route('admin.post.store'), $postData);

        $this->assertDatabaseHas('posts', [
            'title' => 'Draft Post',
            'status' => false
        ]);
    }

    public function posts_are_associated_with_authenticated_user()
    {
        $postData = [
            'title' => 'User Post',
            'content' => 'User content'
        ];

        $this->actingAs($this->user)
            ->post(route('admin.post.store'), $postData);

        $this->assertDatabaseHas('posts', [
            'title' => 'User Post',
            'user_id' => $this->user->id
        ]);
    }

    public function unauthenticated_users_cannot_access_post_routes()
    {
        $post = Post::factory()->create();

        // Test all routes require authentication
        $routes = [
            ['GET', route('admin.post.index')],
            ['GET', route('admin.post.show', $post->id)],
            ['GET', route('admin.post.create')],
            ['POST', route('admin.post.store')],
            ['GET', route('admin.post.edit', $post->id)],
            ['PUT', route('admin.post.update', $post->id)],
            ['DELETE', route('admin.post.destroy', $post->id)]
        ];

        foreach ($routes as [$method, $url]) {
            $response = $this->call($method, $url);
            $this->assertTrue(
                $response->isRedirect() && str_contains($response->headers->get('Location'), 'login'),
                "Route {$method} {$url} should redirect to login"
            );
        }
    }

    public function image_upload_creates_directory_if_not_exists()
    {
        // Remove directory
        if (file_exists($this->testUploadPath)) {
            rmdir($this->testUploadPath);
        }

        $image = UploadedFile::fake()->image('test.jpg');
        $postData = [
            'title' => 'Test Post',
            'content' => 'Test content',
            'image' => $image
        ];

        $response = $this->actingAs($this->user)
                        ->post(route('admin.post.store'), $postData);

        $response->assertRedirect(route('admin.post.index'));
        
        $this->assertDirectoryExists($this->testUploadPath);
        
        // Check directory permissions
        $permissions = fileperms($this->testUploadPath) & 0777;
        $this->assertEquals(0755, $permissions);
    }

    public function can_handle_unicode_characters_in_title()
    {
        $postData = [
            'title' => 'Bài viết về Hoa',
            'content' => 'Nội dung bài viết tiếng Việt'
        ];

        $this->actingAs($this->user)
            ->post(route('admin.post.store'), $postData);

        $this->assertDatabaseHas('posts', [
            'title' => 'Bài viết về Hoa',
            'slug' => 'bai-viet-ve-hoa'
        ]);
    }
}