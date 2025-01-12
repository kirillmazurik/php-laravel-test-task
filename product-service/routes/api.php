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
        Route::get('products', [\App\Http\Controllers\V1\ProductsJSONController::class, 'list'])->name('products.list');
        Route::get('products/search', [\App\Http\Controllers\V1\ProductsJSONController::class, 'search'])->name('products.search');
        Route::post('products', [\App\Http\Controllers\V1\ProductsJSONController::class, 'create'])->name('products.create');
        Route::get('products/{id}', [\App\Http\Controllers\V1\ProductsJSONController::class, 'getOne'])->name('products.getOne');
        Route::delete('products/{id}', [\App\Http\Controllers\V1\ProductsJSONController::class, 'delete'])->name('products.delete');
        Route::patch('products/{id}', [\App\Http\Controllers\V1\ProductsJSONController::class, 'update'])->name('products.update');
    }
);
