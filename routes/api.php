<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::get('get-provinces-list', [AuthController::class, 'getProvincesList'])->name('provinces');
    Route::get('get-cities-list', [AuthController::class, 'getCitiesList'])->name('cities');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

// tester
Route::get('/', function (Request $request) {
});


// Category
Route::resource('category', CategoryController::class);
// Product
Route::resource('product', ProductController::class);



Route::group([
    'prefix' => 'user',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('profile', function (Request $request) {
        return auth()->user();
    });
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
