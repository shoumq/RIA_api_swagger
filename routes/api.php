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

Route::get('/', [ProductController::class, 'getProducts'])->name('getProducts');
Route::get('/{id}', [ProductController::class, 'getProductId'])->name('getProductId');
Route::post('/create', [ProductController::class, 'createProduct'])->name('createProduct');
Route::patch('/{id}/edit', [ProductController::class, 'updateProduct'])->name('updateProduct');
Route::delete('/{id}/delete', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
