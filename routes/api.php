<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use App\Models\City;
use App\Models\Province;
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

// Auth
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('get-province', [AuthController::class, 'getProvince']);
    Route::get('get-cities', [AuthController::class, 'getCity']);
});

// Profile
Route::group([
    'prefix' => 'user',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('{user:slug}', [UserController::class, 'show']);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});


// tester
Route::get('/', function (Request $request) {
});


// Category
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('category', CategoryController::class, ['except' => ['create', 'edit']]);
});

// Product
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('product', ProductController::class, ['except' => ['create', 'edit']]);
});

//Cart

Route::group(['auth:sanctum'], function () {
    Route::resource('cart', CartController::class, ['only' => ['store', 'destroy', 'index']]);
});

// transaction
Route::post('transaction', [TransactionController::class, 'transaction'])->name('transaction');
