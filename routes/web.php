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
use App\Http\Controllers\User\soapFlowerController;
use App\Http\Controllers\User\CartController;




Route::get('/', function () {
    return view('page.dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Route cho người dùng
    Route::get('/dashboard', function () {
        return view('page.dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/soapFlower', [soapFlowerController::class, 'index'])->name('soapFlower');
    Route::get('/soapFlower/{id}', [soapFlowerController::class, 'show'])->name('soapFlower.show');


    Route::get('/paperFlower', function () {
        return view('page.paperFlower');
    })->name('paperFlower');

    Route::get('/souvenir', function () {
        return view('page.souvenir');
    })->name('souvenir');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

use App\Http\Middleware\IsAdmin;

Route::middleware(['auth', IsAdmin::class])->prefix(prefix: 'admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/product', [ProductsController::class, 'index'])->name('product');
    Route::get('/product/create', [ProductsController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductsController::class, 'store'])->name('product.store');

    Route::delete('/product/{id}', [ProductsController::class, 'destroy'])->name('product.destroy');

    Route::get('/product/{id}', [ProductsController::class, 'show'])->name('product.show');


    Route::get('/product/{id}/edit', [ProductsController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductsController::class, 'update'])->name('product.update');


    Route::get('/category', [CategoriesController::class, 'index'])->name('category');
    Route::get('/category/create', [CategoriesController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoriesController::class, 'store'])->name('category.store');

    Route::get('/category/{id}/edit', [CategoriesController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoriesController::class, 'update'])->name('category.update');
    
    Route::get('/category/{id}', [CategoriesController::class, 'show'])->name('category.show');
    Route::delete('/category/{id}', [CategoriesController::class, 'destroy'])->name('category.destroy');
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
