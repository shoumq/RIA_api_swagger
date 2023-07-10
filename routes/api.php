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

Route::get('/api', [ProductController::class, 'getProducts'])->name('getProducts');
Route::get('/api/id/{id}', [ProductController::class, 'getProductId'])->name('getProductId');
Route::post('/api/create', [ProductController::class, 'createProduct'])->name('createProduct');
Route::patch('/api/edit/{id}/', [ProductController::class, 'updateProduct'])->name('updateProduct');
Route::delete('/api/delete/{id}/', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
