<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::middleware(['auth', 'verified'])->group(function () {

    // Pages
    Route::post('checkout', [PageController::class, 'checkout'])->name('checkout');
    Route::get('builder', [PageController::class, 'builder'])->name('builder');

    // Orders
    Route::resource('orders', OrderController::class);

    // Addresses
    // Route::resource('addresses', ShippingAddressController::class);

    // Cart
    Route::resource('cart', CartController::class);
    Route::post('cart/add/{product}', [CartController::class, 'store']);
    Route::post('cart/clear', [CartController::class, 'clear']);
    Route::put('cart/update/{product_id}', [CartController::class, 'updateQuantity']);

    //Products
    Route::get('/getDetailedCartItems', [ProductController::class, 'getDetailedCartItems']);
});

//Products
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::middleware(IsAdmin::class)->group(function () {

    // Products
    Route::post('products', [ProductController::class, 'store']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // Categories
    Route::resource('categories', CategoryController::class);

    //Users
    Route::resource('users', UserController::class);
});



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
