<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    return view('pages.dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Route cho người dùng
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->middleware(['verified'])->name('dashboard');

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
});

use App\Http\Middleware\IsAdmin;

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
