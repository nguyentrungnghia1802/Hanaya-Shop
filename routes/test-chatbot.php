<?php

use Illuminate\Support\Facades\Route;

// Test route for chatbot multilanguage
Route::get('/test-chatbot', function () {
    $locales = ['en', 'vi', 'ja'];
    $testMessages = [
        'store info',
        'help',
        'products',
        'orders'
    ];
    
    $results = [];
    
    foreach ($locales as $locale) {
        app()->setLocale($locale);
        $results[$locale] = [
            'greeting' => __('chatbot.greeting'),
            'help' => __('chatbot.help'),
            'store_info' => __('chatbot.store_info'),
            'price_info' => __('chatbot.price_info'),
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->name('test.chatbot.multilang');
