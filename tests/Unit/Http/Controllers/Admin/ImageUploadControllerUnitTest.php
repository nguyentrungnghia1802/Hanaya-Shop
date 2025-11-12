<?php

namespace Tests\Unit\Admin;

use App\Http\Controllers\Admin\ImageUploadController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class ImageUploadControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $testUploadPath;
    protected $testPostPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ImageUploadController();
        $this->testUploadPath = public_path('images/posts');
        $this->testPostPath = public_path('images/post_featured');

        if (!file_exists($this->testUploadPath)) {
            mkdir($this->testUploadPath, 0755, true);
        }
        
        if (!file_exists($this->testPostPath)) {
            mkdir($this->testPostPath, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testUploadPath)) {
            array_map('unlink', glob($this->testUploadPath . '/*'));
        }
        
        if (file_exists($this->testPostPath)) {
            array_map('unlink', glob($this->testPostPath . '/*'));
        }
        
        parent::tearDown();
    }

    #[Test]
    public function uploadCKEditorImage_success_with_valid_image()
    {
        // Skip if GD extension not available
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }
        
        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600);
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        $response = $this->controller->uploadCKEditorImage($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('filename', $responseData);
        $this->assertArrayHasKey('url', $responseData);
        $this->assertTrue($responseData['uploaded']);
    }

    #[Test]
    public function uploadCKEditorImage_validates_file_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        $this->expectException(ValidationException::class);
        $this->controller->uploadCKEditorImage($request);
    }

    #[Test]
    public function uploadCKEditorImage_handles_missing_file()
    {
        $request = Request::create('/upload', 'POST');

        $this->expectException(ValidationException::class);
        $this->controller->uploadCKEditorImage($request);
    }

    #[Test]
    public function uploadPostImage_success_with_valid_image()
    {
        // Skip if GD extension not available
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }
        
        $file = UploadedFile::fake()->image('featured-image.jpg', 1200, 800);
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        $response = $this->controller->uploadPostImage($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('filename', $responseData);
        $this->assertArrayHasKey('url', $responseData);
        $this->assertTrue($responseData['uploaded']);
    }

    #[Test]
    public function uploadPostImage_returns_error_when_no_image_file_in_request()
    {
        $request = Request::create('/upload', 'POST');

        $this->expectException(ValidationException::class);
        $this->controller->uploadPostImage($request);
    }
}