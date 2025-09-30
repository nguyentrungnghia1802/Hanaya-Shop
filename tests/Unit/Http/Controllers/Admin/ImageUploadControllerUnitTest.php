<?php

namespace Tests\Unit\Admin;

use App\Http\Controllers\Admin\ImageUploadController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ImageUploadControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected $testUploadPath;

    protected $testPostPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ImageUploadController;
        $this->testUploadPath = public_path('images/posts');
        $this->testPostPath = public_path('images/post_featured');

        if (! file_exists($this->testUploadPath)) {
            mkdir($this->testUploadPath, 0755, true);
        }

        if (! file_exists($this->testPostPath)) {
            mkdir($this->testPostPath, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testUploadPath)) {
            array_map('unlink', glob($this->testUploadPath.'/*'));
        }

        if (file_exists($this->testPostPath)) {
            array_map('unlink', glob($this->testPostPath.'/*'));
        }

        parent::tearDown();
    }

    #[Test]
    public function upload_ck_editor_image_success_with_valid_image()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600);
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        $response = $this->controller->uploadCKEditorImage($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('url', $responseData);
        $this->assertStringContainsString('/images/posts/', $responseData['url']);
    }

    #[Test]
    public function upload_ck_editor_image_validates_file_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');
        $request = Request::create('/upload', 'POST', [], [], ['upload' => $file]);

        $response = $this->controller->uploadCKEditorImage($request);

        // Check if it returns error response (status 500 or has error key)
        $this->assertTrue(
            $response->getStatusCode() >= 400 ||
            (json_decode($response->getContent(), true) !== null &&
             array_key_exists('error', json_decode($response->getContent(), true)))
        );
    }

    #[Test]
    public function upload_ck_editor_image_handles_missing_file()
    {
        $request = Request::create('/upload', 'POST');

        $response = $this->controller->uploadCKEditorImage($request);

        // Check if it returns error response (status 400 or has error key)
        $this->assertTrue(
            $response->getStatusCode() >= 400 ||
            (json_decode($response->getContent(), true) !== null &&
             array_key_exists('error', json_decode($response->getContent(), true)))
        );
    }

    #[Test]
    public function upload_post_image_success_with_valid_image()
    {
        // Skip if GD extension not available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        $file = UploadedFile::fake()->image('featured-image.jpg', 1200, 800);
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);

        $response = $this->controller->uploadPostImage($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('url', $responseData);
        $this->assertArrayHasKey('filename', $responseData);
        $this->assertTrue($responseData['success']);
    }

    #[Test]
    public function upload_post_image_returns_error_when_no_image_file_in_request()
    {
        $request = Request::create('/upload', 'POST');

        try {
            $this->controller->uploadPostImage($request);
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertTrue(true); // Test passed
        }
    }
}
