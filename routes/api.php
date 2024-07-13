<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\PublicUserController;

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

Route::prefix('/auth')->controller(AuthUserController::class)->middleware(['api'])->group(function () {
    Route::post('logout', [AuthUserController::class, 'logout']);
    Route::post('refresh', [AuthUserController::class, 'refresh']);
    Route::post('me', [AuthUserController::class, 'me']);
});

Route::prefix('/user')->controller(PublicUserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
