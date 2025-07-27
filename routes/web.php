<?php

use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\ChatbotController; 

require __DIR__.'/user.php';
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';

// Chatbot route
Route::post('/chatbot', [App\Http\Controllers\ChatbotController::class, 'chat'])
    ->name('chatbot.chat');

// // TinyMCE Demo Route
// Route::get('/tinymce-demo', function () {
//     return view('tinymce-demo');
// })->name('tinymce.demo');
