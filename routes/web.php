<?php

use App\Data\SalesOrderData;
use App\Http\Controllers\ProductController;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\HomePage;
use App\Livewire\PageStatic;
use App\Livewire\ProductCatalog;
use App\Livewire\SalesOrderDetail;
use App\Mail\SalesOrderCancelledMail;
use App\Mail\SalesOrderCompletedMail;
use App\Mail\SalesOrderCreatedMail;
use App\Mail\SalesOrderProgressedMail;
use App\Mail\ShippingReceiptNumberUpdatedMail;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/products', ProductCatalog::class)->name('product-catalog');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/order-confirmed/{sales_order:trx_id}', SalesOrderDetail::class)->name('order-confirmed');
Route::get('/page/{page:slug?}', PageStatic::class)->name('page');

Route::webhooks('moota/callback');

// Route::get('/mailable', function () {
//   return new ShippingReceiptNumberUpdatedMail(
//     SalesOrderData::from(
//       SalesOrder::latest()->first()
//     )
//   );
// });
