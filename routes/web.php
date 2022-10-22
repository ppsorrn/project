<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(LoginController::class)->group(function () {
    Route::get('/auth/login', 'loginForm')->name('login'); // name this route to login by default setting.
    Route::post('/auth/login', 'authenticate')->name('authenticate');
    Route::get('/auth/logout', 'logout')->name('logout');
});

Route::controller(MainController::class)->group(function ()
{
    Route::get('/home', 'showHome');
    Route::get('/contact', 'showContact');
});

Route::controller(ProductController::class)->middleware('auth')->group(function () {
    Route::get('/product', 'list')->name('product-list');
    Route::get('/product/create', 'createForm')->name('product-create-form');
    Route::post('/product/create', 'create')->name('product-create');
    Route::get('/product/{product}', 'show')->name('product-view');
    Route::get('/product/{product}/shop', 'showShop',)->name('product-view-shop');
    Route::get('/product/{product}/update', 'updateForm')->name('product-update-form');
    Route::post('/product/{product}/update', 'update')->name('product-update');
    Route::get('/product/{product}/delete', 'delete')->name('product-delete');
    Route::get('/product/{product}/shop/add', 'addShopForm')->name('product-add-shop-form');
    Route::post('/product/{product}/shop/add', 'addShop')->name('product-add-shop');
    Route::get('/product/{product}/shop/{shop}/remove', 'removeShop')->name('product-remove-shop');
});

Route::controller(ShopController::class)->middleware('auth')->group(function () {
        Route::get('/shop', 'list')->name('shop-list');
        Route::get('/shop/create', 'createForm')->name('shop-create-form');
        Route::post('/shop/create', 'create')->name('shop-create');
        Route::get('/shop/{shop}', 'show')->name('shop-view');
        Route::get('/shop/{shop}/product', 'showProduct',)->name('shop-view-product');
        Route::get('/shop/{shop}/update', 'updateForm')->name('shop-update-form');
        Route::post('/shop/{shop}/update', 'update')->name('shop-update');
        Route::get('/shop/{shop}/delete', 'delete')->name('shop-delete');
        Route::get('/shop/{shop}/product/add', 'addProductForm')->name('shop-add-product-form');
        Route::post('/shop/{shop}/product/add', 'addProduct')->name('shop-add-product');
        Route::get('/shop/{shop}/product/{product}/remove', 'removeProduct')->name('shop-remove-product');
    });