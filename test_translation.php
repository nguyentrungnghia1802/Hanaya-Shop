<?php
// Test translation functionality
require_once 'vendor/autoload.php';

// Simulate Laravel app initialization
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Set locale to Vietnamese
app()->setLocale('vi');

echo "Current locale: " . app()->getLocale() . "\n";
echo "Available locales: " . json_encode(config('app.available_locales')) . "\n";

// Test translations
echo "\n=== Testing Vietnamese translations ===\n";
echo "Navigation Home: " . __('common.navigation.home') . "\n";
echo "Navigation Products: " . __('common.navigation.products') . "\n";
echo "Navigation Cart: " . __('common.navigation.cart') . "\n";

echo "\n=== Testing Admin translations ===\n";
echo "Admin Products: " . __('admin.products') . "\n";
echo "Admin Categories: " . __('admin.categories') . "\n";
echo "Admin Orders: " . __('admin.orders') . "\n";

// Test Japanese
app()->setLocale('ja');
echo "\n=== Testing Japanese translations ===\n";
echo "Current locale: " . app()->getLocale() . "\n";
echo "Navigation Home: " . __('common.navigation.home') . "\n";
echo "Navigation Products: " . __('common.navigation.products') . "\n";

// Test English
app()->setLocale('en');
echo "\n=== Testing English translations ===\n";
echo "Current locale: " . app()->getLocale() . "\n";
echo "Navigation Home: " . __('common.navigation.home') . "\n";
echo "Navigation Products: " . __('common.navigation.products') . "\n";
