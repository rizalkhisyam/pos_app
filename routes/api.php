<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'Register']);
Route::post('login', [AuthController::class, 'Login']);

Route::middleware('auth:sanctum')->get('products', [ProductController::class, 'show']);
Route::middleware('auth:sanctum')->post('products', [ProductController::class, 'create']);
Route::middleware('auth:sanctum')->delete('products', [ProductController::class, 'destroy']);
Route::middleware('auth:sanctum')->patch('products', [ProductController::class, 'update']);

Route::middleware('auth:sanctum')->post('category', [CategoryController::class, 'create']);
Route::middleware('auth:sanctum')->get('category', [CategoryController::class, 'getCategory']);
Route::middleware('auth:sanctum')->delete('category', [CategoryController::class, 'destroy']);
Route::middleware('auth:sanctum')->patch('category', [CategoryController::class, 'update']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->name;
});
