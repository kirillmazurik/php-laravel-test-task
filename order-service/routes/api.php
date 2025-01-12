<?php

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

Route::group(
    [
        'prefix'     => 'v1',
        'version'    => 1,
    ],
    static function() {
        Route::get('orders', [\App\Http\Controllers\V1\OrdersJSONController::class, 'list'])->name('orders.list');
        Route::get('orders/search', [\App\Http\Controllers\V1\OrdersSearchJSONController::class, 'search'])->name('orders.search');
        Route::post('orders', [\App\Http\Controllers\V1\OrdersJSONController::class, 'create'])->name('orders.create');
        Route::get('orders/{id}', [\App\Http\Controllers\V1\OrdersJSONController::class, 'getOne'])->name('orders.getOne');
        Route::delete('orders/{id}', [\App\Http\Controllers\V1\OrdersJSONController::class, 'delete'])->name('orders.delete');
        Route::patch('orders/{id}', [\App\Http\Controllers\V1\OrdersJSONController::class, 'update'])->name('orders.update');
        
        Route::get('addresses', [\App\Http\Controllers\V1\AddressesJSONController::class, 'list'])->name('addresses.list');
    }
);
