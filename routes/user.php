<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\soapFlowerController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;

Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/soapFlower', [soapFlowerController::class, 'index'])->name('soapFlower');
    Route::get('/soapFlower/{id}', [soapFlowerController::class, 'show'])->name('soapFlower.show');

    Route::get('/paperFlower', function () {
        return view('page.paperFlower');
    })->name('paperFlower');

    Route::get('/souvenir', function () {
        return view('page.souvenir');
    })->name('souvenir');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart', [CartController::class,'buyNow'])->name('cart.buyNow');

    Route::post('/checkout-preview', [CheckoutController::class, 'preview'])->name('checkout.preview');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});