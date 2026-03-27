<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\SiteAdminNotificationController;
use App\Http\Controllers\SiteImageController;
use App\Support\StorefrontBuilds;
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
});

Route::view('/catalog', 'catalog')->name('catalog');

Route::get('/catalog/{slug}', function (string $slug) {
    $build = StorefrontBuilds::findBySlug($slug);

    abort_unless($build, 404);

    return view('product', ['build' => $build]);
})->name('product.show');
