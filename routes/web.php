<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Admin\CategoriesController;

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

    Route::get('/product', [ProductsController::class, 'index'])->name('product');

    Route::get('/category', [CategoriesController::class, 'index'])->name('category');
    Route::post('/category', [CategoriesController::class, 'store'])->name('category.create');
    Route::delete('/category/{id}', [CategoriesController::class, 'destroy'])->name('category.destroy');
    Route::put('/category/{id}', [CategoriesController::class, 'update'])->name('category.update');
    Route::get('/category/search', [CategoriesController::class, 'search'])->name('category.search');


    Route::get('/user', [UsersController::class, 'index'])->name('user');

    Route::get('/order', [OrdersController::class, 'index'])->name('order');

    Route::get('/review', [ReviewsController::class, 'index'])->name('review');

    Route::get('/statistical', [StatisticalController::class, 'index'])->name('statistical');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
