<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ImageUploadControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $testUploadPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testUploadPath = public_path('images/posts');
        
        // Tạo thư mục test nếu chưa có
        if (!file_exists($this->testUploadPath)) {
            mkdir($this->testUploadPath, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Dọn dẹp files test
        if (file_exists($this->testUploadPath)) {
            $files = glob($this->testUploadPath . '/*');
            foreach ($files as $file) {
                if (is_file($file) && (strpos(basename($file), 'post_content_') === 0 || strpos(basename($file), 'post_featured_') === 0)) {
                    unlink($file);
                }
            }
        }
        parent::tearDown();
    }

    
    public function can_upload_ckeditor_image_via_http()
    {
        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600)->size(1024);

        $response = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $file
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['url'])
                ->assertJson(function ($json) {
                    return str_contains($json['url'], 'images/posts/post_content_') &&
                           str_ends_with($json['url'], '.jpg');
                });

        // Verify file exists on disk
        $responseData = $response->json();
        $filename = basename(parse_url($responseData['url'], PHP_URL_PATH));
        $this->assertFileExists($this->testUploadPath . '/' . $filename);
    }

    
    public function can_upload_post_featured_image_via_http()
    {
        $file = UploadedFile::fake()->image('featured.png', 1200, 800);

        $response = $this->postJson('/admin/upload/post-image', [
            'image' => $file
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'url', 'filename'])
                ->assertJson(['success' => true])
                ->assertJson(function ($json) {
                    return str_contains($json['url'], 'images/posts/post_featured_') &&
                           str_starts_with($json['filename'], 'post_featured_') &&
                           str_ends_with($json['filename'], '.png');
                });

        // Verify file exists on disk
        $responseData = $response->json();
        $this->assertFileExists($this->testUploadPath . '/' . $responseData['filename']);
    }

    
    public function ckeditor_upload_fails_with_invalid_file_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $response = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $file
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['upload']);
    }

    
    public function post_image_upload_fails_with_oversized_file()
    {
        $file = UploadedFile::fake()->image('huge.jpg')->size(11000); // > 10MB

        $response = $this->postJson('/admin/upload/post-image', [
            'image' => $file
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['image']);
    }

    
    public function ckeditor_upload_fails_without_file()
    {
        $response = $this->postJson('/admin/upload/ckeditor', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['upload']);
    }

    
    public function post_image_upload_fails_without_file()
    {
        $response = $this->postJson('/admin/upload/post-image', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['image']);
    }

    
    public function ckeditor_upload_supports_all_valid_image_formats()
    {
        $validFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        foreach ($validFormats as $format) {
            $file = UploadedFile::fake()->image("test.{$format}");

            $response = $this->postJson('/admin/upload/ckeditor', [
                'upload' => $file
            ]);

            $response->assertStatus(200);
            
            $responseData = $response->json();
            $this->assertStringEndsWith(".{$format}", $responseData['url']);
            
            // Verify file exists
            $filename = basename(parse_url($responseData['url'], PHP_URL_PATH));
            $this->assertFileExists($this->testUploadPath . '/' . $filename);
        }
    }

    
    public function post_image_upload_supports_all_valid_image_formats()
    {
        $validFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        foreach ($validFormats as $format) {
            $file = UploadedFile::fake()->image("featured.{$format}");

            $response = $this->postJson('/admin/upload/post-image', [
                'image' => $file
            ]);

            $response->assertStatus(200);
            
            $responseData = $response->json();
            $this->assertStringEndsWith(".{$format}", $responseData['filename']);
            
            // Verify file exists
            $this->assertFileExists($this->testUploadPath . '/' . $responseData['filename']);
        }
    }

    
    public function uploaded_files_have_unique_names_with_timestamp()
    {
        Carbon::setTestNow(Carbon::parse('2024-01-15 10:30:45'));
        Str::createRandomStringsUsing(function ($length) {
            return 'abc12345';
        });

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $file
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        
        $this->assertStringContainsString('post_content_20240115103045_abc12345.jpg', $responseData['url']);

        // Cleanup
        Carbon::setTestNow();
        Str::createRandomStringsNormally();
    }

    
    public function multiple_uploads_generate_different_filenames()
    {
        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');

        $response1 = $this->postJson('/admin/upload/ckeditor', ['upload' => $file1]);
        $response2 = $this->postJson('/admin/upload/ckeditor', ['upload' => $file2]);

        $response1->assertStatus(200);
        $response2->assertStatus(200);

        $data1 = $response1->json();
        $data2 = $response2->json();

        $this->assertNotEquals($data1['url'], $data2['url']);
        
        // Both files should exist
        $filename1 = basename(parse_url($data1['url'], PHP_URL_PATH));
        $filename2 = basename(parse_url($data2['url'], PHP_URL_PATH));
        
        $this->assertFileExists($this->testUploadPath . '/' . $filename1);
        $this->assertFileExists($this->testUploadPath . '/' . $filename2);
    }

    
    public function upload_creates_directory_if_not_exists()
    {
        // Remove directory to test creation
        if (file_exists($this->testUploadPath)) {
            rmdir($this->testUploadPath);
        }

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $file
        ]);

        $response->assertStatus(200);
        $this->assertDirectoryExists($this->testUploadPath);
        
        // Check directory permissions
        $permissions = fileperms($this->testUploadPath) & 0777;
        $this->assertEquals(0755, $permissions);
    }

    
    public function uploaded_images_are_accessible_via_asset_url()
    {
        $file = UploadedFile::fake()->image('accessible.jpg');

        $response = $this->postJson('/admin/upload/post-image', [
            'image' => $file
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();

        // Verify URL format
        $expectedUrlPattern = '/^' . preg_quote(config('app.url'), '/') . '\/images\/posts\/post_featured_\d{14}_[a-zA-Z0-9]{8}\.jpg$/';
        $this->assertMatchesRegularExpression($expectedUrlPattern, $responseData['url']);

        // Verify file is actually accessible
        $filename = $responseData['filename'];
        $this->assertFileExists($this->testUploadPath . '/' . $filename);
    }

    
    public function can_handle_concurrent_uploads()
    {
        $files = [
            UploadedFile::fake()->image('concurrent1.jpg'),
            UploadedFile::fake()->image('concurrent2.png'),
            UploadedFile::fake()->image('concurrent3.gif')
        ];

        $responses = [];
        foreach ($files as $index => $file) {
            $responses[] = $this->postJson('/admin/upload/ckeditor', [
                'upload' => $file
            ]);
        }

        // All should succeed
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        // All should have unique URLs
        $urls = array_map(function ($response) {
            return $response->json()['url'];
        }, $responses);

        $this->assertEquals(count($urls), count(array_unique($urls)));

        // All files should exist
        foreach ($responses as $response) {
            $responseData = $response->json();
            $filename = basename(parse_url($responseData['url'], PHP_URL_PATH));
            $this->assertFileExists($this->testUploadPath . '/' . $filename);
        }
    }

    
    public function different_endpoints_use_different_filename_prefixes()
    {
        $ckeditorFile = UploadedFile::fake()->image('ckeditor.jpg');
        $postFile = UploadedFile::fake()->image('post.jpg');

        $ckeditorResponse = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $ckeditorFile
        ]);

        $postResponse = $this->postJson('/admin/upload/post-image', [
            'image' => $postFile
        ]);

        $ckeditorResponse->assertStatus(200);
        $postResponse->assertStatus(200);

        $ckeditorData = $ckeditorResponse->json();
        $postData = $postResponse->json();

        $this->assertStringContainsString('post_content_', $ckeditorData['url']);
        $this->assertStringContainsString('post_featured_', $postData['filename']);
    }

    
    public function upload_respects_file_size_limit()
    {
        // Test exactly at limit (10MB = 10240KB)
        $maxSizeFile = UploadedFile::fake()->image('max-size.jpg')->size(10240);

        $response = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $maxSizeFile
        ]);

        $response->assertStatus(200);

        // Test over limit
        $oversizeFile = UploadedFile::fake()->image('oversize.jpg')->size(10241);

        $response = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $oversizeFile
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['upload']);
    }

    
    public function json_response_format_is_correct_for_ckeditor()
    {
        $file = UploadedFile::fake()->image('format-test.jpg');

        $response = $this->postJson('/admin/upload/ckeditor', [
            'upload' => $file
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['url'])
                ->assertJsonMissing(['success', 'filename', 'message']);
    }

    
    public function json_response_format_is_correct_for_post_image()
    {
        $file = UploadedFile::fake()->image('format-test.png');

        $response = $this->postJson('/admin/upload/post-image', [
            'image' => $file
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'url', 'filename'])
                ->assertJson(['success' => true]);
    }

    
    public function validates_mime_types_correctly()
    {
        $invalidFiles = [
            ['name' => 'document.txt', 'mime' => 'text/plain'],
            ['name' => 'archive.zip', 'mime' => 'application/zip'],
            ['name' => 'video.mp4', 'mime' => 'video/mp4'],
        ];

        foreach ($invalidFiles as $fileData) {
            $file = UploadedFile::fake()->create($fileData['name'], 1024, $fileData['mime']);

            $response = $this->postJson('/admin/upload/ckeditor', [
                'upload' => $file
            ]);

            $response->assertStatus(422)
                    ->assertJsonValidationErrors(['upload']);
        }
    }
}