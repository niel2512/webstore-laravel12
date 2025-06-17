<?php

use App\Http\Controllers\ProductController;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\HomePage;
use App\Livewire\ProductCatalog;
use App\Livewire\SalesOrderDetail;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/products', ProductCatalog::class)->name('product-catalog');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/order-confirmed/{sales_order:trx_id}', SalesOrderDetail::class)->name('order-confirmed');
Route::view('/page', 'pages.page')->name('page');
