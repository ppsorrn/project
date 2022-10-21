<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;

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