<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard'); // Cập nhật đường dẫn mới
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/soapFlower', function () {
    return view('pages.soapFlower');
})->name('soapFlower');

Route::get('/paperFlower', function () {
    return view('pages.paperFlower');
})->name('paperFlower');

Route::get('/souvenir', function () {
    return view('pages.souvenir');
})->name('souvenir');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
