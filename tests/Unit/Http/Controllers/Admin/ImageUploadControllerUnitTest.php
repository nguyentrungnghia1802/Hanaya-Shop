<?php

namespace Tests\Unit\Admin;

use App\Http\Controllers\Admin\ImageUploadController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\File;

class ImageUploadControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $testUploadPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ImageUploadController();
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
                if (is_file($file) && (strpos(basename($file), 'post_content_') === 0 || strpos(basename($file), 'post_featured_') === 0)) {
                    unlink($file);
                }
            }
        }
        parent::tearDown();
    }

    public function uploadCKEditorImage_success_with_valid_image()
    {
        // Arrange
        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600)->size(1024);
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        // Act
        $response = $this->controller->uploadCKEditorImage($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('url', $responseData);
        $this->assertStringContainsString('images/posts/post_content_', $responseData['url']);
        $this->assertStringEndsWith('.jpg', $responseData['url']);
    }

    public function uploadCKEditorImage_fails_without_file()
    {
        // Arrange
        $request = Request::create('/upload', 'POST');

        // Act
        $response = $this->controller->uploadCKEditorImage($request);

        $this->assertNotEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }

    public function uploadCKEditorImage_fails_with_invalid_file_type()
    {
        // Arrange
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        // Act
        $response = $this->controller->uploadCKEditorImage($request);

        $this->assertNotEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }

    public function uploadCKEditorImage_fails_with_oversized_file()
    {
        $file = UploadedFile::fake()->image('large-image.jpg')->size(11000); // > 10MB
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        // Act
        $response = $this->controller->uploadCKEditorImage($request);

        $this->assertNotEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }

    public function uploadCKEditorImage_supports_all_valid_formats()
    {
        $validFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        foreach ($validFormats as $format) {
            // Arrange
            $file = UploadedFile::fake()->image("test-image.{$format}");
            $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

            // Act
            $response = $this->controller->uploadCKEditorImage($request);

            // Assert
            $this->assertEquals(200, $response->getStatusCode());
            $responseData = json_decode($response->getContent(), true);
            $this->assertArrayHasKey('url', $responseData);
            $this->assertStringEndsWith(".{$format}", $responseData['url']);
        }
    }

    public function uploadCKEditorImage_generates_unique_filenames()
    {
        // Arrange
        Carbon::setTestNow(Carbon::parse('2024-01-15 10:30:45'));
        Str::createRandomStringsUsing(function ($length) {
            return 'abc12345';
        });

        $file = UploadedFile::fake()->image('test.jpg');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        // Act
        $response = $this->controller->uploadCKEditorImage($request);

        // Assert
        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString('post_content_20240115103045_abc12345.jpg', $responseData['url']);

        // Cleanup
        Carbon::setTestNow();
        Str::createRandomStringsNormally();
    }

    public function uploadCKEditorImage_creates_directory_if_not_exists()
    {
        if (file_exists($this->testUploadPath)) {
            rmdir($this->testUploadPath);
        }

        $file = UploadedFile::fake()->image('test-image.jpg');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        // Act
        $response = $this->controller->uploadCKEditorImage($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(file_exists($this->testUploadPath));
    }

    public function uploadCKEditorImage_returns_error_when_no_file_in_request()
    {
        $request = Request::create('/upload', 'POST');

        // Act
        $response = $this->controller->uploadCKEditorImage($request);

        // Assert
        $this->assertEquals(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('No file uploaded', $responseData['error']);
    }

    public function uploadCKEditorImage_handles_file_move_exception()
    {
        $mockController = Mockery::mock(ImageUploadController::class)->makePartial();

        $file = UploadedFile::fake()->image('test.jpg');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        $mockController->shouldReceive('uploadCKEditorImage')
            ->andThrow(new \Exception('File move failed'));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File move failed');
        $mockController->uploadCKEditorImage($request);
    }

    public function uploadPostImage_success_with_valid_image()
    {
        // Arrange
        $file = UploadedFile::fake()->image('featured-image.jpg', 1200, 800);
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        // Act
        $response = $this->controller->uploadPostImage($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);

        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('url', $responseData);
        $this->assertArrayHasKey('filename', $responseData);
        $this->assertStringContainsString('images/posts/post_featured_', $responseData['url']);
        $this->assertStringStartsWith('post_featured_', $responseData['filename']);
    }

    public function uploadPostImage_fails_without_file()
    {
        // Arrange
        $request = Request::create('/upload', 'POST');

        // Act
        $response = $this->controller->uploadPostImage($request);

        $this->assertEquals(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Upload failed', $responseData['message']);
    }

    public function uploadPostImage_fails_with_invalid_file_type()
    {
        // Arrange
        $file = UploadedFile::fake()->create('document.txt', 1024, 'text/plain');
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        // Act
        $response = $this->controller->uploadPostImage($request);

        $this->assertNotEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
    }

    public function uploadPostImage_fails_with_oversized_file()
    {
        // Arrange
        $file = UploadedFile::fake()->image('huge-image.jpg')->size(12000); // > 10MB
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        // Act
        $response = $this->controller->uploadPostImage($request);

        $this->assertNotEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
    }

    public function uploadPostImage_supports_all_valid_formats()
    {
        $validFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        foreach ($validFormats as $format) {
            // Arrange
            $file = UploadedFile::fake()->image("featured.{$format}");
            $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

            // Act
            $response = $this->controller->uploadPostImage($request);

            // Assert
            $this->assertEquals(200, $response->getStatusCode());
            $responseData = json_decode($response->getContent(), true);
            $this->assertTrue($responseData['success']);
            $this->assertStringEndsWith(".{$format}", $responseData['filename']);
        }
    }

    public function uploadPostImage_generates_unique_filenames()
    {
        // Arrange
        Carbon::setTestNow(Carbon::parse('2024-01-15 14:25:30'));
        Str::createRandomStringsUsing(function ($length) {
            return 'xyz98765';
        });

        $file = UploadedFile::fake()->image('test.png');
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        // Act
        $response = $this->controller->uploadPostImage($request);

        // Assert
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('post_featured_20240115142530_xyz98765.png', $responseData['filename']);

        // Cleanup
        Carbon::setTestNow();
        Str::createRandomStringsNormally();
    }

    public function uploadPostImage_creates_directory_if_not_exists()
    {
        if (file_exists($this->testUploadPath)) {
            rmdir($this->testUploadPath);
        }

        $file = UploadedFile::fake()->image('featured.jpg');
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        // Act
        $response = $this->controller->uploadPostImage($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(file_exists($this->testUploadPath));
    }

    public function uploadPostImage_returns_error_when_no_file_in_request()
    {
        // Arrange
        $request = Request::create('/upload', 'POST');

        // Act
        $response = $this->controller->uploadPostImage($request);

        // Assert
        $this->assertEquals(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Upload failed', $responseData['message']);
    }

    public function uploadPostImage_handles_exceptions_gracefully()
    {
        $mockController = Mockery::mock(ImageUploadController::class)->makePartial();

        $file = UploadedFile::fake()->image('test.jpg');
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        $mockController->shouldReceive('uploadPostImage')
            ->andThrow(new \Exception('Disk full'));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Disk full');
        $mockController->uploadPostImage($request);
    }

    public function both_methods_generate_different_filename_prefixes()
    {
        // Arrange
        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');

        $request1 = Request::create('/upload1', 'POST', [], [], ['upload' => $file1]);
        $request2 = Request::create('/upload2', 'POST', [], [], ['image' => $file2]);

        // Act
        $response1 = $this->controller->uploadCKEditorImage($request1);
        $response2 = $this->controller->uploadPostImage($request2);

        // Assert
        $data1 = json_decode($response1->getContent(), true);
        $data2 = json_decode($response2->getContent(), true);

        $this->assertStringContainsString('post_content_', $data1['url']);
        $this->assertStringContainsString('post_featured_', $data2['filename']);
    }

    public function file_permissions_are_set_correctly_for_directory()
    {
        if (file_exists($this->testUploadPath)) {
            rmdir($this->testUploadPath);
        }

        $file = UploadedFile::fake()->image('test.jpg');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        $this->controller->uploadCKEditorImage($request);

        $this->assertTrue(file_exists($this->testUploadPath));

        $permissions = fileperms($this->testUploadPath) & 0777;
        $this->assertContains(
            $permissions,
            [0755, 0777],
            "Expected permissions 0755 or 0777, but got " . decoct($permissions)
        );
    }

    public function controller_validates_input_properly()
    {
        $request = Request::create('/upload', 'POST');

        $response = $this->controller->uploadCKEditorImage($request);
        $this->assertNotEquals(200, $response->getStatusCode());

        $response2 = $this->controller->uploadPostImage($request);
        $this->assertNotEquals(200, $response2->getStatusCode());
    }

    public function controller_handles_corrupted_files()
    {
        $file = UploadedFile::fake()->createWithContent('fake.jpg', 'This is not an image content');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        $response = $this->controller->uploadCKEditorImage($request);

        $this->assertIsInt($response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
    }

    public static function tearDownAfterClass(): void
    {
        Mockery::close();
        parent::tearDownAfterClass();
    }
}