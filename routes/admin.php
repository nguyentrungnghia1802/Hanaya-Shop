<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ImageUploadController;

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/product', [ProductsController::class, 'index'])->name('product');
    Route::get('/product/search', [ProductsController::class, 'search'])->name('product.search');
    Route::get('/product/create', [ProductsController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductsController::class, 'store'])->name('product.store');
    Route::delete('/product/{id}', [ProductsController::class, 'destroy'])->name('product.destroy');
    Route::get('/product/{id}', [ProductsController::class, 'show'])->name('product.show');
    Route::get('/product/{id}/edit', [ProductsController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductsController::class, 'update'])->name('product.update');

    Route::get('/category', [CategoriesController::class, 'index'])->name('category');
    Route::get('/category/search', [CategoriesController::class, 'search'])->name('category.search');
    Route::get('/category/create', [CategoriesController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoriesController::class, 'store'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoriesController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoriesController::class, 'update'])->name('category.update');
    Route::get('/category/{id}', [CategoriesController::class, 'show'])->name('category.show');
    Route::delete('/category/{id}', [CategoriesController::class, 'destroy'])->name('category.destroy');

    // POST ROUTES
    Route::get('/post', [PostController::class, 'index'])->name('post.index');
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');
    
    // IMAGE UPLOAD ROUTES
    Route::post('/upload/ckeditor-image', [ImageUploadController::class, 'uploadCKEditorImage'])->name('upload.ckeditor.image');
    Route::post('/upload/post-image', [ImageUploadController::class, 'uploadPostImage'])->name('upload.post.image');
    Route::post('/posts/upload-image', [ImageUploadController::class, 'uploadTinyMCEImage'])->name('upload.tinymce.image');

    Route::get('/user', [UsersController::class, 'index'])->name('user');
    Route::get('/user/search', [UsersController::class, 'search'])->name('user.search');    
    Route::get('/user/create', [UsersController::class, 'create'])->name('user.create');
    Route::post('/user', [UsersController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UsersController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UsersController::class, 'destroySingle'])->name('user.destroy');
    Route::delete('/user', [UsersController::class, 'destroy'])->name('user.destroy.multiple');
    Route::get('/user/{id}', [UsersController::class, 'show'])->name('user.show');

    Route::get('/order', [OrdersController::class, 'index'])->name('order');
    Route::get('/order/{id}', [OrdersController::class, 'show'])->name('order.show');
    Route::put('/orders/{order}/confirm', [OrdersController::class, 'confirm'])->name('order.confirm');


    Route::get('/review', [ReviewsController::class, 'index'])->name('review');
    Route::get('/statistical', [StatisticalController::class, 'index'])->name('statistical');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});