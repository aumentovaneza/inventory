<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function(){
    Route::group(['prefix' => 'products'], function(){
        Route::group(['prefix' => '{product}'], function(){
            Route::get('/', [ProductController::class, 'show']);
            Route::post('/variant', [ProductVariantController::class, 'store']);
            Route::get('apply/inventory', [InventoryController::class, 'takeoutInventory']);
        });
        Route::get('', [ProductController::class, 'index']);
        Route::post('', [ProductController::class, 'store']);
    });
});
