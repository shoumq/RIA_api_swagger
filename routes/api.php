<?php

use App\Http\Controllers\ProductController;
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

Route::get('/swagger/api', [ProductController::class, 'getProducts'])->name('getProducts');
Route::get('/swagger/api/{id}', [ProductController::class, 'getProductId'])->name('getProductId');
Route::post('/swagger/api/create', [ProductController::class, 'createProduct'])->name('createProduct');
Route::patch('/swagger/api/{id}/edit', [ProductController::class, 'updateProduct'])->name('updateProduct');
Route::delete('/swagger/api/{id}/delete', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
