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
        Route::get('products', [\App\Http\Controllers\V1\ProductsXMLController::class, 'list'])->name('products.list2');
        Route::get('products/search', [\App\Http\Controllers\V1\ProductsXMLController::class, 'search'])->name('products.search2');
        Route::post('products', [\App\Http\Controllers\V1\ProductsXMLController::class, 'create'])->name('products.create2');
        Route::get('products/{id}', [\App\Http\Controllers\V1\ProductsXMLController::class, 'getOne'])->name('products.getOne2');
        Route::delete('products/{id}', [\App\Http\Controllers\V1\ProductsXMLController::class, 'delete'])->name('products.delete2');
        Route::patch('products/{id}', [\App\Http\Controllers\V1\ProductsXMLController::class, 'update'])->name('products.update2');
    }
);
