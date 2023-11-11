<?php

use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
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

// bisa diakses ketika login dan memberikan token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('login', [UserController::class, 'fetch']);
    Route::post('login', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);

    // checkout
    Route::post('checkout', [TransactionController::class, 'checkout']);

    // transaksi
    Route::get('transaction', [TransactionController::class, 'all']);
    // transaksi update
    Route::put('transaction/{id}', [TransactionController::class, 'update']);
});

// gabisa diakses kalo ga login
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

// product
Route::get('product', [ProductController::class, 'all']);

// midtrans callback
Route::post('midtrans/callback', [MidtransController::class, 'callback']);
