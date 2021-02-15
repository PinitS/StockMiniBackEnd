<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductHistoryController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreOrderController;
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
Route::prefix('Store')->group(function () {
    Route::get('/getAll', [StoreController::class, 'getStores']);
    Route::get('/getOne/{id}', [StoreController::class, 'getStore']);
    Route::post('/create', [StoreController::class, 'create']);
    Route::post('/update', [StoreController::class, 'update']);
    Route::delete('/delete/{id}', [StoreController::class, 'delete']);
});

Route::prefix('MainCategory')->group(function () {
    Route::get('/getAll', [MainCategoryController::class, 'getMainCategories']);
    Route::get('/getOne/{id}', [MainCategoryController::class, 'getMainCategory']);
    Route::post('/create', [MainCategoryController::class, 'create']);
    Route::post('/update', [MainCategoryController::class, 'update']);
    Route::delete('/delete/{id}', [MainCategoryController::class, 'delete']);
});

Route::prefix('Category')->group(function () {
    Route::get('/getAll', [CategoryController::class, 'getCategories']);
    Route::get('/getOne/{id}', [CategoryController::class, 'getCategory']);
    Route::post('/create', [CategoryController::class, 'create']);
    Route::post('/update', [CategoryController::class, 'update']);
    Route::delete('/delete/{id}', [CategoryController::class, 'delete']);
});

Route::prefix('Type')->group(function () {
    Route::get('/getAll', [TypeController::class, 'getTypes']);
    Route::get('/getOne/{id}', [TypeController::class, 'getType']);
    Route::post('/create', [TypeController::class, 'create']);
    Route::post('/update', [TypeController::class, 'update']);
    Route::delete('/delete/{id}', [TypeController::class, 'delete']);
});

Route::prefix('Product')->group(function () {
    Route::get('/getAll', [ProductController::class, 'getProducts']);
    Route::get('/getOne/{id}', [ProductController::class, 'getProduct']);
    Route::post('/create', [ProductController::class, 'create']);
    Route::post('/update', [ProductController::class, 'update']);
    Route::post('/changeActive', [ProductController::class, 'changeActive']);
    Route::delete('/delete/{id}', [ProductController::class, 'delete']);
});

Route::prefix('Stock')->group(function () {
    Route::post('/getProductsFillers', [StockController::class, 'getProductsFillers']);
    Route::post('/create', [StockController::class, 'create']);
});

Route::prefix('Other')->group(function () {
    Route::get('/getAllDropDown', [OtherController::class, 'getAllDropDown']);
});

Route::prefix('ProductHistory')->group(function () {
    Route::get('/getAll/{id}', [ProductHistoryController::class, 'getAll']);
    Route::post('/changeStatus', [ProductHistoryController::class, 'changeStatus']);
    Route::post('/changeAmountHistory', [ProductHistoryController::class, 'changeAmountHistory']);
});

Route::prefix('Cashier')->group(function () {
    Route::post('/getProductsFillers', [CashierController::class, 'getProductsFillers']);
});

Route::prefix('Order')->group(function () {
    Route::post('/getOrder', [OrderController::class, 'getOrder']);
    Route::post('/getOrderStatusOne', [OrderController::class, 'getOrderStatusOne']);
    Route::post('/create', [OrderController::class, 'create']);
    Route::post('/changeAmount', [OrderController::class, 'changeAmount']);
    Route::post('/changeStatus', [OrderController::class, 'changeStatus']);
    Route::delete('/delete/{order_id}/{product_id}', [OrderController::class, 'delete']);
});

Route::prefix('StoreOrder')->group(function () {
    Route::post('/getOrderDetails', [StoreOrderController::class, 'getOrderDetails']);
    Route::post('/updateStatus', [StoreOrderController::class, 'updateStatus']);


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


