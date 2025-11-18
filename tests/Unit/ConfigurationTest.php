<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * Test that Laravel automatically uses .env.testing during tests.
     */
    public function test_laravel_uses_testing_environment()
    {
        // Kiểm tra environment
        $this->assertEquals('testing', app()->environment());

        // Kiểm tra database
        $this->assertEquals('hanaya_shop_test', config('database.connections.mysql.database'));
        $this->assertEquals('mysql', config('database.default'));

        // Kiểm tra filesystem
        $this->assertEquals('testing', config('filesystems.default'));

        // Kiểm tra cache driver
        $this->assertEquals('array', config('cache.default'));

        // Kiểm tra session driver
        $this->assertEquals('array', config('session.driver'));

        // Kiểm tra mail driver
        $this->assertEquals('array', config('mail.default'));

        echo "\n✅ Laravel đang sử dụng .env.testing tự động!\n";
        echo '🗄️  Database: '.config('database.connections.mysql.database')."\n";
        echo '💾 Filesystem: '.config('filesystems.default')."\n";
        echo '📧 Mail: '.config('mail.default')."\n";
    }

    /**
     * Test storage isolation.
     */
    public function test_storage_isolation_works()
    {
        // Kiểm tra storage disk
        $this->assertEquals('testing', config('filesystems.default'));

        // Tạo file test trong storage testing
        Storage::put('test-file.txt', 'Test content');

        // Kiểm tra file tồn tại trong testing storage
        $this->assertTrue(Storage::exists('test-file.txt'));

        // Kiểm tra path thực tế
        $path = Storage::path('test-file.txt');
        $this->assertStringContainsString('storage\\framework', $path);
        $this->assertStringContainsString('testing', $path);

        echo '📁 Test file path: '.$path."\n";

        // Cleanup
        Storage::delete('test-file.txt');
    }
}
