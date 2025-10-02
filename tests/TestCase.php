<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    /**
     * Make a POST JSON request with CSRF token
     */
    /**
     * Make a POST JSON request with CSRF token
     */
    protected function postJsonWithCsrf($uri, array $data = [], array $headers = [])
    {
        $this->startSession();
        $data['_token'] = $this->app['session']->token();

        return $this->postJson($uri, $data, $headers);
    }

    /**
     * Make a DELETE JSON request with CSRF token
     */
    protected function deleteJsonWithCsrf($uri, array $data = [], array $headers = [])
    {
        $this->startSession();
        $data['_token'] = $this->app['session']->token();

        return $this->deleteJson($uri, $data, $headers);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure we're using testing storage disk to avoid deleting demo images
        $this->app['config']->set('filesystems.default', 'testing');

        // Set cache driver to array for testing to avoid mocking issues
        $this->app['config']->set('cache.default', 'array');

        // Set session driver to array for testing
        $this->app['config']->set('session.driver', 'array');

        // Optimize MySQL for testing speed
        $this->app['config']->set('database.connections.mysql.options', [
            \PDO::ATTR_EMULATE_PREPARES => true,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
        ]);

        // Disable query logging for speed
        $this->app['config']->set('database.connections.mysql.dump.add_drop_database', false);
        $this->app['config']->set('database.connections.mysql.dump.add_locks', false);

        // Use faster hashing for tests
        $this->app['config']->set('hashing.bcrypt.rounds', 4);

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

    /**
     * Make a POST request with CSRF token
     */
    protected function postWithCsrf($uri, array $data = [], array $headers = [])
    {
        $this->startSession();
        $data['_token'] = $this->app['session']->token();

        return $this->post($uri, $data, $headers);
    }

    /**
     * Make a PUT request with CSRF token
     */
    protected function putWithCsrf($uri, array $data = [], array $headers = [])
    {
        $this->startSession();
        $data['_token'] = $this->app['session']->token();

        return $this->put($uri, $data, $headers);
    }

    /**
     * Make a DELETE request with CSRF token
     */
    protected function deleteWithCsrf($uri, array $data = [], array $headers = [])
    {
        $this->startSession();
        $data['_token'] = $this->app['session']->token();

        return $this->delete($uri, $data, $headers);
    }
}
