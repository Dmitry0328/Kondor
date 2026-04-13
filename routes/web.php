<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteAdminNotificationController;
use App\Http\Controllers\SiteImageController;
use App\Http\Controllers\TradeInController;
use App\Support\StorefrontBuilds;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home')],
        ['loc' => route('catalog')],
        ['loc' => route('trade-in')],
    ];

    foreach (StorefrontBuilds::all() as $build) {
        $slug = trim((string) ($build['slug'] ?? ''));

        if ($slug === '') {
            continue;
        }

        $urls[] = ['loc' => route('product.show', ['slug' => $slug])];
    }

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = implode("\n", [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        'Disallow: /login',
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
Route::get('/order-tracking', [OrderTrackingController::class, 'show'])->name('orders.track');
Route::post('/order-tracking', [OrderTrackingController::class, 'lookup'])->name('orders.track.lookup');

Route::middleware('auth')->group(function () {
    Route::get('/admin-notifications/feed', [SiteAdminNotificationController::class, 'index'])->name('admin.notifications.feed');
    Route::post('/admin/site-images', [SiteImageController::class, 'store'])->name('site-images.store');
    Route::get('/catalog/preview/{token}', [ProductController::class, 'showPreview'])->name('product.preview');
    Route::post('/catalog/preview/{token}/persist', [ProductController::class, 'persistPreview'])->name('product.preview.persist');
});

Route::view('/catalog', 'catalog')->name('catalog');
Route::get('/trade-in', [TradeInController::class, 'show'])->name('trade-in');
Route::post('/trade-in', [TradeInController::class, 'store'])->name('trade-in.submit');

Route::get('/catalog/shared/{token}', [ProductController::class, 'showShared'])->name('product.shared');
Route::post('/catalog/{slug}/share', [ProductController::class, 'share'])->name('product.share');
Route::get('/catalog/{slug}', [ProductController::class, 'show'])->name('product.show');
