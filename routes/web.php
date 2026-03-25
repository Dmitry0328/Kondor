<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/catalog', 'catalog')->name('catalog');

Route::get('/catalog/{slug}', function (string $slug) {
    $build = collect(config('kondor_storefront.builds', []))->firstWhere('slug', $slug);

    abort_unless($build, 404);

    return view('product', ['build' => $build]);
})->name('product.show');
