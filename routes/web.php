<?php
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
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

Route::group(['prefix' => 'products'], function(){
    Route::group(['prefix' => '{product}'], function(){
        Route::get('/', [ProductController::class, 'show']);
        Route::post('/variant', [ProductVariantController::class, 'store']);
    });
    Route::group(['prefix' => 'inventory'], function(){
        Route::get('form', [InventoryController::class, 'index'])->name('inventory.form');
        Route::get('apply', [InventoryController::class, 'takeoutInventory'])->name('inventory.apply');
    });
    Route::get('', [ProductController::class, 'index']);
    Route::post('', [ProductController::class, 'store']);
});
