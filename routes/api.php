<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



Route::get('products', [ProductController::class, 'getProducts']);
Route::post('add-product', [ProductController::class, 'addProduct']);
Route::get('get-product/{id}', [ProductController::class, 'getProduct']);
Route::put('update-product/{id}', [ProductController::class, 'updateProduct']);
Route::delete('delete-product/{id}', [ProductController::class, 'deleteProduct']);
Route::get('specific-products', [ProductController::class, 'getSpecificProducts']);
Route::get('products-category/{category}', [ProductController::class, 'getProductsByCategory']);



Route::post('add-to-cart/{product_id}', [CartController::class, 'addToCart']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
