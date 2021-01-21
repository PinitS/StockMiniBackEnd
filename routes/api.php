<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ProductController;



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
Route::prefix('MainCategory')->group(function () {
    Route::get('/getAll', [MainCategoryController::class, 'getMainCategories']);
    Route::get('/getOne/{id}', [MainCategoryController::class, 'getMainCategory']);
    Route::post('/create', [MainCategoryController::class, 'create']);
    Route::post('/update', [MainCategoryController::class, 'update']);
    Route::delete('/delete', [MainCategoryController::class, 'delete']);
});

Route::prefix('Category')->group(function () {
    Route::get('/getAll', [CategoryController::class, 'getCategories']);
    Route::get('/getOne/{id}', [CategoryController::class, 'getCategory']);
    Route::post('/create', [CategoryController::class, 'create']);
    Route::post('/update', [CategoryController::class, 'update']);
    Route::delete('/delete', [CategoryController::class, 'delete']);
});

Route::prefix('Store')->group(function () {
    Route::get('/getAll', [StoreController::class, 'getStores']);
    Route::get('/getOne/{id}', [StoreController::class, 'getStore']);
    Route::post('/create', [StoreController::class, 'create']);
    Route::post('/update', [StoreController::class, 'update']);
    Route::delete('/delete', [StoreController::class, 'delete']);
});

Route::prefix('Type')->group(function () {
    Route::get('/getAll', [TypeController::class, 'getTypes']);
    Route::get('/getOne/{id}', [TypeController::class, 'getType']);
    Route::post('/create', [TypeController::class, 'create']);
    Route::post('/update', [TypeController::class, 'update']);
    Route::delete('/delete', [TypeController::class, 'delete']);
});

Route::prefix('Product')->group(function () {
    Route::get('/getAll', [ProductController::class, 'getProducts']);
    Route::get('/getOne/{id}', [ProductController::class, 'getProduct']);
    Route::post('/create', [ProductController::class, 'create']);
    Route::post('/update', [ProductController::class, 'update']);
    Route::delete('/delete', [ProductController::class, 'delete']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
