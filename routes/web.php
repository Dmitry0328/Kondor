<?php

use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('store.home');
Route::get('/catalog', [StorefrontController::class, 'catalog'])->name('store.catalog');
Route::get('/product/{slug}', [StorefrontController::class, 'product'])->name('store.product');
Route::get('/contacts', [StorefrontController::class, 'contacts'])->name('store.contacts');
