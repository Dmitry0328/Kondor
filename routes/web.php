<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteAdminNotificationController;
use App\Http\Controllers\SiteImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/shared/{token}', [CartController::class, 'showShared'])->name('cart.shared');
Route::post('/cart/share', [CartController::class, 'share'])->name('cart.share');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::middleware('auth')->group(function () {
    Route::get('/admin-notifications/feed', [SiteAdminNotificationController::class, 'index'])->name('admin.notifications.feed');
    Route::post('/admin/site-images', [SiteImageController::class, 'store'])->name('site-images.store');
    Route::get('/catalog/preview/{token}', [ProductController::class, 'showPreview'])->name('product.preview');
    Route::post('/catalog/preview/{token}/persist', [ProductController::class, 'persistPreview'])->name('product.preview.persist');
});

Route::view('/catalog', 'catalog')->name('catalog');
Route::view('/trade-in', 'trade-in')->name('trade-in');

Route::get('/catalog/shared/{token}', [ProductController::class, 'showShared'])->name('product.shared');
Route::post('/catalog/{slug}/share', [ProductController::class, 'share'])->name('product.share');
Route::get('/catalog/{slug}', [ProductController::class, 'show'])->name('product.show');
