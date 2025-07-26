<?php

use App\Http\Controllers\User\ChatbotController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/user.php';
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';

// Chatbot route
Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');