<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OnlineVisitorsController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SiteAdminNotificationController;
use App\Http\Controllers\SiteImageController;
use App\Http\Controllers\TradeInController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/robots.txt', function () {
    $content = implode("\n", [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        'Disallow: /login',
        'Disallow: /order-tracking',
        'Disallow: /catalog/shared/',
        'Disallow: /cart/shared/',
        'Disallow: /catalog/preview/',
        '',
        'Sitemap: ' . route('sitemap'),
        '',
    ]);

    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
});

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/shared/{token}', [CartController::class, 'showShared'])->name('cart.shared');
Route::post('/cart/share', [CartController::class, 'share'])->name('cart.share');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/online-visitors/ping', [OnlineVisitorsController::class, 'ping'])->name('online-visitors.ping');
Route::get('/order-tracking', [OrderTrackingController::class, 'show'])->name('orders.track');
Route::post('/order-tracking', [OrderTrackingController::class, 'lookup'])->name('orders.track.lookup');

Route::middleware('auth')->group(function () {
    Route::get('/admin-notifications/feed', [SiteAdminNotificationController::class, 'index'])->name('admin.notifications.feed');
    Route::post('/admin/site-images', [SiteImageController::class, 'store'])->name('site-images.store');
    Route::get('/catalog/preview/{token}', [ProductController::class, 'showPreview'])->name('product.preview');
    Route::post('/catalog/preview/{token}/persist', [ProductController::class, 'persistPreview'])->name('product.preview.persist');
});

Route::view('/catalog', 'catalog')->name('catalog');
Route::get('/catalog/compare', [CompareController::class, 'show'])->name('catalog.compare');
Route::get('/devices', [AccessoryController::class, 'index'])->name('accessories.index');
Route::get('/devices/{slug}', [AccessoryController::class, 'show'])->name('accessories.show');
Route::get('/trade-in', [TradeInController::class, 'show'])->name('trade-in');
Route::post('/trade-in', [TradeInController::class, 'store'])->name('trade-in.submit');

Route::get('/catalog/shared/{token}', [ProductController::class, 'showShared'])->name('product.shared');
Route::post('/catalog/{slug}/share', [ProductController::class, 'share'])->name('product.share');
Route::get('/catalog/{slug}', [ProductController::class, 'show'])->name('product.show');
