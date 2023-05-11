<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['guest'])->group(function () {
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

    Route::apiResource('product', \App\Http\Controllers\ProductController::class);
    Route::apiResource('transaction', \App\Http\Controllers\TransactionController::class)->only(['index', 'store']);
});
