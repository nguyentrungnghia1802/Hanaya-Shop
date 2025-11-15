<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    // Cache durations in seconds
    const DASHBOARD_CACHE_DURATION = 1800; // 30 minutes

    const PRODUCTS_CACHE_DURATION = 900;   // 15 minutes

    const PRODUCT_DETAIL_DURATION = 1800;  // 30 minutes

    /**
     * Clear all product-related caches
     */
    public static function clearProductCaches()
    {
        // Clear dashboard cache as products affect dashboard stats
        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_recent_products');

        // Note: For pattern-based cache clearing, you would need Redis or Memcached
        // For now, we'll clear known cache keys

        // If you're using file cache, you could implement pattern clearing here
    }

    /**
     * Clear dashboard cache
     */
    public static function clearDashboardCache()
    {
        $keys = [
            'dashboard_stats',
            'dashboard_recent_products',
            'dashboard_categories',
            'dashboard_recent_orders',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear specific product cache
     */
    public static function clearProductCache($productId)
    {
        Cache::forget("product_detail_{$productId}");
        Cache::forget("related_products_{$productId}");

        // Clear dashboard cache as well since product changes affect it
        self::clearDashboardCache();
    }

    /**
     * Generate cache key for products index
     */
    public static function getProductsIndexCacheKey($params)
    {
        return 'products_index_'.md5(serialize($params));
    }

    /**
     * Generate cache key for product detail
     */
    public static function getProductDetailCacheKey($productId)
    {
        return "product_detail_{$productId}";
    }

    /**
     * Generate cache key for related products
     */
    public static function getRelatedProductsCacheKey($productId)
    {
        return "related_products_{$productId}";
    }
}
