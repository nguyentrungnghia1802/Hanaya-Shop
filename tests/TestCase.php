<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure we're using testing storage disk to avoid deleting demo images
        $this->app['config']->set('filesystems.default', 'testing');

        // Create testing directories if they don't exist
        $testingDisk = Storage::disk('testing');
        if (! $testingDisk->exists('images')) {
            $testingDisk->makeDirectory('images');
            $testingDisk->makeDirectory('images/products');
            $testingDisk->makeDirectory('images/categories');
            $testingDisk->makeDirectory('images/posts');
            $testingDisk->makeDirectory('images/reviews');
        }
    }

    protected function tearDown(): void
    {
        // Clean up testing storage after each test to keep it clean
        $testingDisk = Storage::disk('testing');
        if ($testingDisk->exists('images')) {
            $testingDisk->deleteDirectory('images');
        }

        parent::tearDown();
    }
}
