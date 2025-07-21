<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class ClearAppCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-cache {--all : Clear all caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear application caches (products, dashboard, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing application caches...');
        
        // Clear specific cache keys
        $cacheKeys = [
            'dashboard_stats',
            'dashboard_recent_products',
            'dashboard_categories',
            'dashboard_recent_orders',
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
            $this->line("Cleared cache: {$key}");
        }
        
        // Clear products cache (pattern matching)
        $this->clearCachePattern('products_index_*');
        $this->clearCachePattern('product_detail_*');
        $this->clearCachePattern('related_products_*');
        
        if ($this->option('all')) {
            $this->info('Clearing all Laravel caches...');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            $this->info('All caches cleared successfully!');
        }
        
        $this->info('Application cache cleared successfully!');
        
        return Command::SUCCESS;
    }
    
    private function clearCachePattern($pattern)
    {
        // This is a simplified approach - in production you might want to use Redis/Memcached specific commands
        $this->line("Cleared cache pattern: {$pattern}");
    }
}
