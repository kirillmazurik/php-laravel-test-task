<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(
    [
        'prefix'     => 'v1',
        'version'    => 1,
    ],
    static function() {
        Route::get('orders', [\App\Http\Controllers\V1\OrdersXMLController::class, 'list'])->name('orders.list2');
        Route::get('orders/search', [\App\Http\Controllers\V1\OrdersSearchXMLController::class, 'search'])->name('orders.search2');
        Route::post('orders', [\App\Http\Controllers\V1\OrdersXMLController::class, 'create'])->name('orders.create2');
        Route::get('orders/{id}', [\App\Http\Controllers\V1\OrdersXMLController::class, 'getOne'])->name('orders.getOne2');
        Route::delete('orders/{id}', [\App\Http\Controllers\V1\OrdersXMLController::class, 'delete'])->name('orders.delete2');
        Route::patch('orders/{id}', [\App\Http\Controllers\V1\OrdersXMLController::class, 'update'])->name('orders.update2');
    }
);

