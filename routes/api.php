<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicUserController;
use App\Http\Controllers\SaleController;

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

/**
 * Rotas públicas da API.
 */
Route::prefix('/user')->controller(PublicUserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

/**
 * Rotas autenticadas da API.
 */
Route::prefix('/auth')->controller(AuthUserController::class)->middleware(['api', 'validate.token'])->group(function () {
    Route::prefix('/user')->group(function () {
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/me', 'me');
    });

    Route::prefix('/clients')->controller(ClientsController::class)->group(function () {
        Route::get('/', 'listAll');
        Route::get('/{id}', 'show');
        Route::post('/register', 'register');
        Route::put('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });

    Route::prefix('/products')->controller(ProductController::class)->group(function () {
        Route::get('/', 'listAll');
        Route::get('/{id}', 'show');
        Route::post('/register', 'register');
        Route::put('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
        Route::patch('/restore/{id}', 'restore');
    });

    Route::prefix('/sales')->controller(SaleController::class)->group(function () {
        Route::post('/register', 'register');
    });
});
