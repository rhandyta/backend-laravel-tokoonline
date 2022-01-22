<?php

use App\Http\Controllers\AuthController;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kavist\RajaOngkir\Facades\RajaOngkir;
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

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('/', function (Request $request) {
});
Route::group([
    'prefix' => 'user',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('profile', function (Request $request) {
        return auth()->user();
    });
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
